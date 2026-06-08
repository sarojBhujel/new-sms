<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Exports\StudentsImportTemplateExport;
use App\Http\Requests\ImportStudentsRequest;
use App\Http\Requests\StoreStudents;
use App\Imports\StudentImport;
use App\Models\FiscalYear;
use App\Models\Student;
use App\Models\Type_Blood;
use App\Repository\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
            $query = Student::with([
                'gender',
                'grade',
                'currentFiscalDetail.fiscalYear',
                'currentFiscalDetail.classroom',
                'currentFiscalDetail.section',
            ]);

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
            $columns = ['id', 'name', 'email', 'gender_id', 'Grade_id', 'id', 'id', 'id', 'id'];
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $students = $query->skip($start)->take($length)->get();

            $data = $students->map(function ($student) {
                $detail = $student->currentFiscalDetail;

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'gender' => optional($student->gender)->Name,
                    'grade' => optional($student->grade)->Name,
                    'classroom' => optional($detail->classroom)->Name_Class ?: optional($student->classroom)->Name_Class,
                    'section' => optional($detail->section)->Name_Section ?: optional($student->section)->Name_Section,
                    'admission_no' => optional($detail)->admission_no,
                    'roll_no' => optional($detail)->roll_no,
                    'academic_year' => optional($detail->fiscalYear)->name ?: $student->academic_year,
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

    public function idCard($id)
    {
        $student = Student::with([
            'gender',
            'grade',
            'myparent',
            'images',
            'currentFiscalDetail.fiscalYear',
            'currentFiscalDetail.classroom',
            'currentFiscalDetail.section',
            'currentFiscalDetail.faculty',
        ])->findOrFail($id);

        $bloodGroup = optional(Type_Blood::find($student->blood_id))->Name;

        return view('pages.Students.id_card', compact('student', 'bloodGroup'));
    }

    public function edit($id)
    {
        return $this->Student->Edit_Student($id);
    }


    public function update(StoreStudents $request, $id)
    {
        return $this->Student->Update_Student($request);
    }


    public function destroy(Request $request, $id)
    {
        return $this->Student->Delete_Student($request);
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

    public function downloadImportTemplate()
    {
        return Excel::download(new StudentsImportTemplateExport(), 'students-import-template.xlsx');
    }

    public function import(ImportStudentsRequest $request)
    {
        $activeFiscalYear = FiscalYear::active();

        if (!$activeFiscalYear) {
            return redirect()->back()->withErrors(['file' => 'An active fiscal year is required before importing students.']);
        }

        $import = new StudentImport();

        try {
            Excel::import($import, $request->file('file'));

            $failures = collect($import->failures())->map(function ($failure) {
                return sprintf('Row %d: %s', $failure->row(), implode(', ', $failure->errors()));
            })->toArray();

            if (!empty($failures) || method_exists($import, 'errors') && count($import->errors())) {
                return redirect()->back()->with('import_failures', $failures);
            }

            return redirect()->back()->with('success', 'Students imported successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['file' => $exception->getMessage()]);
        }
    }
}
