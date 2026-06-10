<?php

namespace App\Repository;

use App\Models\Gender;
use App\Models\Specialization;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\Hash;

class TeacherRepository implements TeacherRepositoryInterface
{

    public function getAllTeachers()
    {
        return Teacher::get();
    }

    public function Getspecialization()
    {
        return Specialization::all();
    }

    public function GetGender()
    {
        return Gender::all();
    }

    public function StoreTeachers($request)
    {
        try {
            $createLoginCredentials = $request->boolean('create_login_credentials');
            
            $Teachers = new Teacher();
            $Teachers->name = $request->Name;
            $Teachers->Specialization_id = $request->Specialization_id;
            $Teachers->Gender_id = $request->Gender_id;
            $Teachers->Joining_Date = $request->Joining_Date;
            $Teachers->Address = $request->Address;
            
            if ($createLoginCredentials) {
                $Teachers->email = $request->Email;
                $Teachers->password = Hash::make($request->Password);
            } else {
                $Teachers->email = null;
                $Teachers->password = null;
            }
            
            $Teachers->save();
            toastr()->success('Data has been saved successfully');
            return redirect()->route('Teachers.create');
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    public function editTeachers($id)
    {
        return Teacher::findOrFail($id);
    }


    public function UpdateTeachers($request)
    {
        try {
            $Teachers = Teacher::findOrFail($request->id);
            $createLoginCredentials = $request->boolean('create_login_credentials');
            
            $Teachers->name = $request->Name;
            $Teachers->Specialization_id = $request->Specialization_id;
            $Teachers->Gender_id = $request->Gender_id;
            $Teachers->Joining_Date = $request->Joining_Date;
            $Teachers->Address = $request->Address;
            
            if ($createLoginCredentials) {
                $Teachers->email = $request->Email;
                
                if ($request->filled('Password')) {
                    $Teachers->password = Hash::make($request->Password);
                }
            } else {
                $Teachers->email = null;
                $Teachers->password = null;
            }
            
            $Teachers->save();
            toastr()->success('Data has been Update successfully');
            return redirect()->route('Teachers.index');
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    public function DeleteTeachers($request)
    {
        Teacher::findOrFail($request->id)->delete();
        toastr()->error('Data has been Deleted successfully');
        return redirect()->route('Teachers.index');
    }
}
