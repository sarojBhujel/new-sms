<?php

namespace App\Http\Controllers\Grades;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrades;
use App\Models\Classroom;
use Illuminate\Http\Request;

use App\Models\Grade;

class GradeController extends Controller
{


    public function index()
    {
        if (request()->ajax()) {
            $query = Grade::query();
            
            // Search
            if (request()->has('search') && !empty(request()->input('search.value'))) {
                $search = request()->input('search.value');
                $query->where('Name', 'like', "%{$search}%")
                      ->orWhere('Notes', 'like', "%{$search}%");
            }

            $recordsTotal = Grade::count();
            $recordsFiltered = $query->count();

            // Sorting
            $orderColumn = request()->input('order.0.column', 0);
            $orderDir = request()->input('order.0.dir', 'asc');
            $columns = ['id', 'Name', 'Notes', 'id'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            // Pagination
            $start = request()->input('start', 0);
            $length = request()->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval(request()->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        }

        return view('pages.Grades.Grades');
    }


    public function create()
    {
    }


    public function store(StoreGrades $request)
    {
        try {
            $validated = $request->validated();

            $Grade = new Grade();

            $Grade->Name = $request->Name;
            $Grade->Notes = $request->Notes;
            $Grade->has_faculty = $request->has('has_faculty') ? 1 : 0;
            $Grade->save();

            toastr()->success('Data has been saved successfully');

            return redirect()->route('Grades.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $Grade = Grade::findOrFail($id);
        return response()->json($Grade);
    }

    public function update(StoreGrades $request, $id)
    {
        try {
            $validated = $request->validated();
            $Grade = Grade::findOrFail($id);

            $Grade->update([
                'Name' => $request->Name,
                'Notes' => $request->Notes,
                'has_faculty' => $request->has('has_faculty') ? 1 : 0,
            ]);

                return response()->json(['message' => 'Grade updated successfully.', 'grade' => $Grade]);
     
        } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $MyClass_id = Classroom::where('Grade_id', $id)->pluck('Grade_id');

            if ($MyClass_id->count() == 0) {
                Grade::findOrFail($id)->delete();

                if ($request->ajax()) {
                    return response()->json(['message' => 'Grade deleted successfully.']);
                }

                toastr()->error('Data has been deleted successfully');
                return redirect()->route('Grades.index');
            }else{

                return response()->json(['error' => "Classroom/s are associated with this grade.Operation not allowed."], 422);
            }

        } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
