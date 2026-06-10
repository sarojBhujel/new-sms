<?php

namespace App\Http\Controllers\Teachers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {

        $information = Teacher::findorFail(auth()->user()->id);
        return view('pages.Teachers.dashboard.profile', compact('information'));

    }

    public function update(Request $request, $id)
    {

        $information = Teacher::findorFail($id);

        if (!empty($request->password)) {
            $information->name =$request->Name;
            $information->password = Hash::make($request->password);
            $information->save();
        } else {
            $information->name =$request->Name;
            $information->save();
        }
        toastr()->success('Data has been Update successfully');
        return redirect()->back();


    }
}
