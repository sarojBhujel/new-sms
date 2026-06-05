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
            return response()->json(Grade::select(['id', 'Name', 'Notes'])->paginate(10));
        }

        $Grades = Grade::all();
        return view('pages.Grades.Grades', compact('Grades'));
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
            $Grade->save();

            toastr()->success('Data has been saved successfully');

            return redirect()->route('Grades.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $Grade = Grade::findOrFail($id);
        return response()->json($Grade);
    }

    public function edit($id)
    {
    }

    public function update(StoreGrades $request, $id)
    {
        try {
            $validated = $request->validated();
            $Grade = Grade::findOrFail($id);

            $Grade->update([
                'Name' => $request->Name,
                'Notes' => $request->Notes,
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
