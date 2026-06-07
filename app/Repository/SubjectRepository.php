<?php


namespace App\Repository;


use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;

class SubjectRepository implements SubjectRepositoryInterface
{

    public function index()
    {
        if (request()->ajax()) {
            $query = Subject::with(['grade', 'classroom', 'teacher']);

            if (request()->has('search') && !empty(request()->input('search.value'))) {
                $search = request()->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('grade', function ($q2) use ($search) {
                            $q2->where('Name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('classroom', function ($q2) use ($search) {
                            $q2->where('Name_Class', 'like', "%{$search}%");
                        })
                        ->orWhereHas('teacher', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $recordsTotal = Subject::count();
            $recordsFiltered = $query->count();

            $columns = ['id', 'name', 'grade_id', 'classroom_id', 'teacher_id', 'id'];
            $orderColumn = request()->input('order.0.column', 1);
            $orderDir = request()->input('order.0.dir', 'asc');

            if (isset($columns[$orderColumn])) {
                if ($columns[$orderColumn] === 'grade_id') {
                    $query = $query->join('grades', 'grades.id', '=', 'subjects.grade_id')
                        ->select('subjects.*')
                        ->orderBy('grades.Name', $orderDir);
                } elseif ($columns[$orderColumn] === 'classroom_id') {
                    $query = $query->join('classrooms', 'classrooms.id', '=', 'subjects.classroom_id')
                        ->select('subjects.*')
                        ->orderBy('classrooms.Name_Class', $orderDir);
                } elseif ($columns[$orderColumn] === 'teacher_id') {
                    $query = $query->join('teachers', 'teachers.id', '=', 'subjects.teacher_id')
                        ->select('subjects.*')
                        ->orderBy('teachers.name', $orderDir);
                } else {
                    $query->orderBy($columns[$orderColumn], $orderDir);
                }
            }

            $start = request()->input('start', 0);
            $length = request()->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval(request()->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $grades = Grade::get();
        $teachers = Teacher::get();
        return view('pages.Subjects.index', compact('grades', 'teachers'));
    }

    public function create()
    {
        $grades = Grade::get();
        $teachers = Teacher::get();
        return view('pages.Subjects.create', compact('grades', 'teachers'));
    }


    public function store($request)
    {
        try {
            $subjects = new Subject();
            $subjects->name = ['en' => $request->Name, 'ar' => $request->Name_ar];
            $subjects->grade_id = $request->Grade_id;
            $subjects->classroom_id = $request->Class_id;
            $subjects->teacher_id = $request->teacher_id;
            $subjects->save();
            toastr()->success('Data has been saved successfully');
            return redirect()->route('subjects.create');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        $subject = Subject::findOrFail($id);

        if (request()->ajax()) {
            return response()->json($subject);
        }

        $grades = Grade::get();
        $teachers = Teacher::get();
        return view('pages.Subjects.edit', compact('subject', 'grades', 'teachers'));
    }

    public function update($request)
    {
        try {
            $subjects = Subject::findOrFail($request->id);
            $subjects->name = ['en' => $request->Name, 'ar' => $request->Name_ar];
            $subjects->grade_id = $request->Grade_id;
            $subjects->classroom_id = $request->Class_id;
            $subjects->teacher_id = $request->teacher_id;
            $subjects->save();

            if ($request->ajax()) {
                return response()->json(['message' => 'Subject updated successfully.', 'subject' => $subjects]);
            }

            toastr()->success('Data has been Update successfully');
            return redirect()->route('subjects.create');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($request)
    {
        try {
            Subject::destroy($request->id);

            if ($request->ajax()) {
                return response()->json(['message' => 'Subject deleted successfully.']);
            }

            toastr()->error('Data has been Deleted successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
