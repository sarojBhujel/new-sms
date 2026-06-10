<?php

namespace App\Repository;

use App\Models\Classroom;
use App\Models\FiscalYear;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Image;
use App\Models\My_Parent;
use App\Models\Nationalitie;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFiscalDetail;
use App\Models\Type_Blood;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentRepository implements StudentRepositoryInterface
{


    public function Get_Student()
    {
        $students = Student::with([
            'gender',
            'grade',
            'currentFiscalDetail.fiscalYear',
            'currentFiscalDetail.classroom',
            'currentFiscalDetail.section',
        ])->get();

        return view('pages.Students.index', compact('students'));
    }

    public function Edit_Student($id)
    {
        $data['Grades'] = Grade::all();
        $data['parents'] = My_Parent::all();
        $data['Genders'] = Gender::all();
        $data['nationals'] = Nationalitie::all();
        $data['bloods'] = Type_Blood::all();
        $data['activeFiscalYear'] = FiscalYear::active();

        $Students = Student::with([
            'currentFiscalDetail.fiscalYear',
            'currentFiscalDetail.classroom',
            'currentFiscalDetail.section',
        ])->findOrFail($id);

        $studentFiscalDetail = $Students->currentFiscalDetail;

        return view('pages.Students.edit', $data, compact('Students', 'studentFiscalDetail'));
    }

    public function Show_Student($id)
    {
        $Student = Student::with([
            'gender',
            'Nationality',
            'grade',
            'myparent',
            'currentFiscalDetail.fiscalYear',
            'currentFiscalDetail.classroom',
            'currentFiscalDetail.section',
        ])->findOrFail($id);

        return view('pages.Students.show', compact('Student'));
    }

    public function Update_Student($request)
    {
        DB::beginTransaction();

        try {
            $Edit_Students = Student::findOrFail($request->id);
            $createLoginCredentials = $request->boolean('create_login_credentials');

            $Edit_Students->name = $request->name;
            $Edit_Students->gender_id = $request->gender_id;
            $Edit_Students->nationalitie_id = $request->nationalitie_id;
            $Edit_Students->blood_id = $request->input('blood_id') ?: null;
            $Edit_Students->Date_Birth = $request->Date_Birth;
            $Edit_Students->Grade_id = $request->Grade_id;
            $Edit_Students->Classroom_id = $request->Classroom_id;
            $Edit_Students->section_id = $request->input('section_id') ?: null;
            $Edit_Students->parent_id = $request->parent_id;

            $fiscalYear = null;
            if ($request->filled('fiscal_year_id')) {
                $fiscalYear = FiscalYear::find($request->fiscal_year_id);
            }

            if (!$fiscalYear) {
                $fiscalYear = FiscalYear::active();
            }

            if (!$fiscalYear) {
                throw new \Exception('Please create and activate a fiscal year before performing this operation.');
            }

            if ($createLoginCredentials) {
                $Edit_Students->email = $request->email;

                if ($request->filled('password')) {
                    $Edit_Students->password = Hash::make($request->password);
                }
            } else {
                $Edit_Students->email = null;
                $Edit_Students->password = null;
            }

            $Edit_Students->academic_year = $fiscalYear->name;
            $Edit_Students->save();

            StudentFiscalDetail::updateOrCreate(
                [
                    'student_id' => $Edit_Students->id,
                    'academic_year_id' => $fiscalYear->id,
                ],
                [
                    'active_fiscal_year_id' => $fiscalYear->id,
                    'faculty_id' => $request->faculty_id,
                    'admission_no' => $request->admission_no,
                    'admission_date' => $request->admission_date,
                    'class_id' => $request->Classroom_id,
                    'section_id' => $request->input('section_id') ?: null,
                    'roll_no' => $request->roll_no,
                ]
            );

            DB::commit();
            toastr()->success('Data has been Update successfully');
            return redirect()->route('Students.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function Create_Student()
    {

        $data['my_classes'] = Grade::all();
        $data['parents'] = My_Parent::all();
        $data['Genders'] = Gender::all();
        $data['nationals'] = Nationalitie::all();
        $data['bloods'] = Type_Blood::all();
        return view('pages.Students.add', $data);
    }

    public function Get_classrooms($id)
    {
        $list_classes = Classroom::where("Grade_id", $id)->pluck("Name_Class", "id");
        return $list_classes;
    }

    //Get Sections
    public function Get_Sections($id)
    {
        $list_sections = Section::where("Class_id", $id)->pluck("Name_Section", "id");
        return $list_sections;
    }

    public function Store_Student($request)
    {
        DB::beginTransaction();

        try {
            $fiscalYear = null;
            if ($request->filled('fiscal_year_id')) {
                $fiscalYear = FiscalYear::find($request->fiscal_year_id);
            }

            if (!$fiscalYear) {
                $fiscalYear = FiscalYear::active();
            }

            if (!$fiscalYear) {
                throw new \Exception('Please create and activate a fiscal year before performing this operation.');
            }

            $createLoginCredentials = $request->boolean('create_login_credentials');

            $students = new Student();
            $students->name = $request->name;
            $students->gender_id = $request->gender_id;
            $students->nationalitie_id = $request->nationalitie_id;
            $students->blood_id = $request->input('blood_id') ?: null;
            $students->Date_Birth = $request->Date_Birth;
            $students->Grade_id = $request->Grade_id;
            $students->Classroom_id = $request->Classroom_id;
            $students->section_id = $request->input('section_id') ?: null;
            $students->parent_id = $request->parent_id;

            if ($createLoginCredentials) {
                $students->email = $request->email;
                $students->password = Hash::make($request->password);
            } else {
                $students->email = null;
                $students->password = null;
            }

            $students->academic_year = $fiscalYear->name;
            $students->save();

            StudentFiscalDetail::create([
                'student_id' => $students->id,
                'academic_year_id' => $fiscalYear->id,
                'active_fiscal_year_id' => $fiscalYear->id,
                'faculty_id' => $request->faculty_id,
                'admission_no' => $request->admission_no,
                'admission_date' => $request->admission_date,
                'class_id' => $request->Classroom_id,
                'section_id' => $request->input('section_id') ?: null,
                'roll_no' => $request->roll_no,
            ]);

            if ($request->hasfile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $name = $file->getClientOriginalName();
                    $file->storeAs('attachments/students/' . $students->name, $file->getClientOriginalName(), 'upload_attachments');

                    $images = new Image();
                    $images->filename = $name;
                    $images->imageable_id = $students->id;
                    $images->imageable_type = 'App\Models\Student';
                    $images->save();
                }
            }

            DB::commit();
            toastr()->success('Data has been saved successfully');
            return redirect()->route('Students.create');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function Delete_Student($request)
    {
        Student::destroy($request->id);
        toastr()->error('Data has been Deleted successfully');
        return redirect()->route('Students.index');
    }

    public function Upload_attachment($request)
    {
        foreach ($request->file('photos') as $file) {
            $name = $file->getClientOriginalName();
            $file->storeAs('attachments/students/' . $request->student_name, $file->getClientOriginalName(), 'upload_attachments');

            // insert in image_table
            $images = new image();
            $images->filename = $name;
            $images->imageable_id = $request->student_id;
            $images->imageable_type = 'App\Models\Student';
            $images->save();
        }
        toastr()->success('Data has been saved successfully');
        return redirect()->route('Students.show', $request->student_id);
    }

    public function Download_attachment($studentsname, $filename)
    {
        return response()->download(public_path('attachments/students/' . $studentsname . '/' . $filename));
    }

    public function Delete_attachment($request)
    {
        DB::beginTransaction();

        try {
        // Delete img in server disk
        Storage::disk('upload_attachments')->delete('attachments/students/' . $request->student_name . '/' . $request->filename);

        // Delete in data
        image::where('id', $request->id)->where('filename', $request->filename)->delete();

        DB::commit(); // insert data

        toastr()->error('Data has been Deleted successfully',' ');
        return redirect()->route('Students.show', $request->student_id);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('Students.show', $request->student_id)->withErrors(['error' => $e->getMessage()]);
        }
    }

}
