<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSections;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return response()->json(Section::with(['grade', 'classroom', 'teachers'])->select(['id', 'Name_Section', 'Grade_id', 'Class_id', 'Status'])->paginate(10));
        }

        $Grades = Grade::with(['Section'])->get();
        $list_Grades = Grade::all();
        $teachers = Teacher::all();

        return view('pages.sections.sections', compact('Grades','list_Grades', 'teachers'));
    }


    public function create()
    {
        //
    }


    public function store(StoreSections $request)
    {
        try {
            $validated = $request->validated();
            $Sections = new Section();

            $Sections->Name_Section = $request->Name_Section;
            $Sections->Grade_id = $request->Grade_id;
            $Sections->Class_id = $request->Class_id;
            $Sections->Status = 1;
            $Sections->save();
            // save in table teacher_section
            $Sections->teachers()->attach($request->teacher_id);

            toastr()->success('Data has been saved successfully');
            return redirect()->route('Sections.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        $Section = Section::with(['grade', 'classroom', 'teachers'])->findOrFail($id);
        return response()->json($Section);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $Section = Section::with(['grade', 'classroom', 'teachers'])->findOrFail($id);
        return response()->json($Section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(StoreSections $request, $id)
    {
        try {
            $validated = $request->validated();
            $Section = Section::findOrFail($id);

            $Section->Name_Section = $request->Name_Section;
            $Section->Grade_id = $request->Grade_id;
            $Section->Class_id = $request->Class_id;
            $Section->Status = isset($request->Status) ? 1 : 0;

            $Section->save();

            // update pivot table
            if (isset($request->teacher_id)) {
                $Section->teachers()->sync($request->teacher_id);
            } else {
                $Section->teachers()->sync(array());
            }

            if ($request->ajax()) {
                return response()->json(['message' => 'Section updated successfully.', 'section' => $Section->load(['grade', 'classroom', 'teachers'])]);
            }

            toastr()->success('Data has been updated successfully');
            return redirect()->route('Sections.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            Section::findOrFail($id)->delete();

            if ($request->ajax()) {
                return response()->json(['message' => 'Section deleted successfully.']);
            }

            toastr()->error('Data has been deleted successfully');
            return redirect()->route('Sections.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getClasses($id)
    {
        $list_classes = Classroom::where("Grade_id",$id)->pluck("Name_Class","id");

        return $list_classes;
    }
}
