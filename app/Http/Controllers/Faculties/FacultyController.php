<?php

namespace App\Http\Controllers\Faculties;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaculties;
use App\Models\Faculty;
use App\Models\Grade;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Faculty::with('grade');

            if (request()->has('search') && !empty(request()->input('search.value'))) {
                $search = request()->input('search.value');
                $query->where('faculty_name', 'like', "%{$search}%")
                      ->orWhere('faculty_code', 'like', "%{$search}%")
                      ->orWhereHas('grade', function ($q) use ($search) {
                          $q->where('Name', 'like', "%{$search}%");
                      });
            }

            $recordsTotal = Faculty::count();
            $recordsFiltered = $query->count();

            $orderColumn = request()->input('order.0.column', 0);
            $orderDir = request()->input('order.0.dir', 'asc');
            $columns = ['id', 'faculty_name', 'faculty_code', 'grade_id', 'status', 'id'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
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
        $data['grades'] = Grade::where('has_faculty', true)->get(['id', 'Name']);
        return view('pages.Faculties.Faculties',$data);
    }

    public function create()
    {
    }

    public function store(StoreFaculties $request)
    {
        try {
            $validated = $request->validated();
            $faculty = Faculty::create($validated);
            toastr()->success('Data has been saved successfully');
            return redirect()->route('Faculties.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $faculty = Faculty::with('grade')->findOrFail($id);
        return response()->json($faculty);
    }

    public function edit($id)
    {
        $faculty = Faculty::findOrFail($id);
        return response()->json($faculty);
    }

    public function update(StoreFaculties $request, $id)
    {
        try {
            $validated = $request->validated();
            $faculty = Faculty::findOrFail($id);
            $faculty->update($validated);
            return response()->json(['message' => 'Faculty updated successfully.', 'faculty' => $faculty]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            Faculty::findOrFail($id)->delete();
            if ($request->ajax()) {
                return response()->json(['message' => 'Faculty deleted successfully.']);
            }
            toastr()->error('Data has been deleted successfully');
            return redirect()->route('Faculties.index');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function byGrade($id)
    {
        $grade = Grade::findOrFail($id);
        $faculties = Faculty::where('grade_id', $id)
            ->where('status', true)
            ->get(['id', 'faculty_name']);

        return response()->json([
            'has_faculty' => (bool) $grade->has_faculty,
            'faculties' => $faculties,
        ]);
    }
}
