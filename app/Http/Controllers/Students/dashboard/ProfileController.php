<?php

namespace App\Http\Controllers\Students\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index()
    {
        $information = Student::findorFail(auth()->user()->id);
        return view('pages.Students.dashboard.profile', compact('information'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $information = Student::findorFail($id);

        if (!empty($request->password)) {
            $information->name =$request->Name;
            $information->password = Hash::make($request->password);
            $information->save();
        } else {
            $information->name = $request->Name;
            $information->save();
        }
        toastr()->success('Data has been Update successfully');
        return redirect()->back();
    }


    public function destroy($id)
    {
        //
    }
}
