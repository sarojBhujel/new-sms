<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudents;
use App\Models\Student;
use App\Repository\StudentRepositoryInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $Student;

    public function __construct(StudentRepositoryInterface $Student)
    {
        $this->Student = $Student;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Student::with(['gender', 'grade', 'classroom', 'section']);

            if ($request->filled('search.value')) {
                $search = $request->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Student::count();
            $recordsFiltered = $query->count();

            $orderColumn = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'asc');
            $columns = ['id', 'name', 'email', 'gender_id', 'Grade_id', 'Classroom_id', 'section_id', 'id'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $students = $query->skip($start)->take($length)->get();

            $data = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'gender' => optional($student->gender)->Name,
                    'grade' => optional($student->grade)->Name,
                    'classroom' => optional($student->classroom)->Name_Class,
                    'section' => optional($student->section)->Name_Section,
                ];
            });

            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        return view('pages.Students.index');
    }


    public function create()
    {
        return $this->Student->Create_Student();
    }


    public function store(StoreStudents $request)
    {
        return $this->Student->Store_Student($request);
    }


    public function show($id)
    {
        return $this->Student->Show_Student($id);
    }


    public function edit($id)
    {
        return $this->Student->Edit_Student($id);
    }


    public function update(StoreStudents $request, $id)
    {
        return $this->Student->Update_Student($request, $id);
    }


    public function destroy(Request $request, $id)
    {
        return $this->Student->Delete_Student($request, $id);
    }

    public function Get_Classrooms($id)
    {

        return $this->Student->Get_Classrooms($id);
    }

    public function Get_Sections($id)
    {

        return $this->Student->Get_Sections($id);
    }

    public function Upload_attachment(Request $request)
    {
        return $this->Student->Upload_attachment($request);
    }

    public function Download_attachment($studentsname, $filename)
    {
        return $this->Student->Download_attachment($studentsname, $filename);
    }

    public function Delete_attachment(Request $request)
    {
        return $this->Student->Delete_attachment($request);
    }
}
