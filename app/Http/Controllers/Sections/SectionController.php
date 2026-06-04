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
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(StoreSections $request)
    {

        try {
            $validated = $request->validated();
            $Sections = Section::findOrFail($request->id);

            $Sections->Name_Section = $request->Name_Section;
            $Sections->Grade_id = $request->Grade_id;
            $Sections->Class_id = $request->Class_id;

            if (isset($request->Status)) {
                $Sections->Status = 1;
            } else {
                $Sections->Status = 0;
            }

            // update pivot tABLE
            if (isset($request->teacher_id)) {
                $Sections->teachers()->sync($request->teacher_id);
            } else {
                $Sections->teachers()->sync(array());
            }

            $Sections->save();

            toastr()->success('Data has been Update successfully');

            return redirect()->route('Sections.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function destroy(request $request)
    {

        Section::findOrFail($request->id)->delete();

        toastr()->error('Data has been Deleted successfully',' ');
        return redirect()->route('Sections.index');
    }

    public function getClasses($id)
    {
        $list_classes = Classroom::where("Grade_id",$id)->pluck("Name_Class","id");

        return $list_classes;
    }
}
