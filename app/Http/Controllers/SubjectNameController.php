<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectNameRequest;
use App\Http\Requests\UpdateSubjectNameRequest;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\SubjectName;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectNameController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SubjectName::withCount('subjects');

            if ($request->filled('search.value')) {
                $search = $request->input('search.value');
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }

            $recordsTotal = SubjectName::count();
            $recordsFiltered = $query->count();

            $columns = ['id', 'name', 'code', 'subjects_count', 'id'];
            $orderColumn = $request->input('order.0.column', 1);
            $orderDir = $request->input('order.0.dir', 'asc');

            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $classrooms = Classroom::orderBy('Name_Class')->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('pages.SubjectNames.SubjectNames', compact('classrooms', 'teachers'));
    }

    public function create()
    {
        // Create handled in index modal
    }

    public function store(StoreSubjectNameRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $classroomIds = $request->input('classroom_ids', []);
                $teacherIds = $request->input('teacher_id', []);
                $gradeIds = $request->input('grade_ids', []);

                foreach ($request->input('name') as $index => $name) {
                    $subjectName = SubjectName::create([
                        'name' => $name,
                        'code' => $request->input('code')[$index] ?? null,
                    ]);

                    foreach ($classroomIds as $classroomId) {
                        Subject::create([
                            'subject_name_id' => $subjectName->id,
                            'classroom_id' => $classroomId,
                            'teacher_id' => $teacherIds[$classroomId] ?? null,
                            'grade_id' => $gradeIds[$classroomId] ?? null,
                        ]);
                    }
                }
            });

            toastr()->success('Subject name(s) saved successfully');
            return redirect()->route('subject-names.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(SubjectName $subjectName)
    {
        $subjectName->load(['subjects.classroom', 'subjects.teacher']);

        return response()->json([
            'id' => $subjectName->id,
            'name' => $subjectName->name,
            'code' => $subjectName->code,
            'mappings' => $subjectName->subjects->map(function ($subject) {
                return [
                    'classroom' => optional($subject->classroom)->Name_Class,
                    'teacher' => optional($subject->teacher)->name,
                ];
            })->values(),
        ]);
    }

    public function edit(SubjectName $subjectName)
    {
        $mappedTeachers = $subjectName->subjects()->pluck('teacher_id', 'classroom_id')->toArray();

        return response()->json([
            'id' => $subjectName->id,
            'name' => $subjectName->name,
            'code' => $subjectName->code,
            'mapped_teachers' => $mappedTeachers,
            'classroom_ids' => array_values($subjectName->subjects()->pluck('classroom_id')->toArray()),
        ]);
    }

    public function update(UpdateSubjectNameRequest $request, SubjectName $subjectName)
    {
        try {
            DB::transaction(function () use ($request, $subjectName) {
                $subjectName->update([
                    'name' => $request->input('name'),
                    'code' => $request->input('code'),
                ]);

                $classroomIds = $request->input('classroom_ids', []);
                $teacherIds = $request->input('teacher_id', []);
                $gradeIds = $request->input('grade_ids', []);

                foreach ($classroomIds as $classroomId) {
                    Subject::updateOrCreate(
                        ['subject_name_id' => $subjectName->id, 'classroom_id' => $classroomId],
                        [
                            'teacher_id' => $teacherIds[$classroomId] ?? null,
                            'grade_id' => $gradeIds[$classroomId] ?? null,
                        ]
                    );
                }

                Subject::where('subject_name_id', $subjectName->id)
                    ->whereNotIn('classroom_id', $classroomIds)
                    ->delete();
            });

            return response()->json(['message' => 'Subject name updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(Request $request, SubjectName $subjectName)
    {
        try {
            $subjectName->delete();

            if ($request->ajax()) {
                return response()->json(['message' => 'Subject name deleted successfully.']);
            }

            toastr()->error('Data has been deleted successfully');
            return redirect()->route('subject-names.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
