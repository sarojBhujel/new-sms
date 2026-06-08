<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\FiscalYear;
use App\Models\Gender;
use App\Models\Nationalitie;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFiscalDetail;
use App\Models\Type_Blood;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class StudentImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, SkipsEmptyRows, WithBatchInserts, WithChunkReading, WithEvents
{
    use Importable, SkipsFailures, SkipsErrors;

    private FiscalYear $activeFiscalYear;
    private int $defaultNationalitieId;
    private int $defaultBloodTypeId;

    public function __construct()
    {
        $this->activeFiscalYear = FiscalYear::active();

        if (!$this->activeFiscalYear) {
            throw new \Exception('Please create and activate a fiscal year before performing this operation.');
        }

        $nationality = Nationalitie::first();
        $bloodType = Type_Blood::first();

        if (!$nationality || !$bloodType) {
            throw new \Exception('Default nationality or blood type data is missing.');
        }

        $this->defaultNationalitieId = $nationality->id;
        $this->defaultBloodTypeId = $bloodType->id;
    }

    public function model(array $row)
    {
        $genderValue = trim($row['gender']);
        $gender = is_numeric($genderValue)
            ? Gender::find($genderValue)
            : Gender::where('Name', $genderValue)->first();

        if (!$gender) {
            throw new \Exception("Gender '{$genderValue}' not found.");
        }

        $classroom = Classroom::find($row['class_id']);
        $section = Section::find($row['section_id']);

        if (!$classroom) {
            throw new \Exception("Class ID {$row['class_id']} not found.");
        }

        if (!$section) {
            throw new \Exception("Section ID {$row['section_id']} not found.");
        }

        if ($section->Class_id !== $classroom->id) {
            throw new \Exception("Section {$section->id} does not belong to class {$classroom->id}.");
        }

        $studentName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
        $studentEmail = Str::slug($studentName ?: 'student') . '-' . uniqid() . '@example.com';

        $student = Student::create([
            'name' => $studentName,
            'email' => $studentEmail,
            'password' => Hash::make(Str::random(12)),
            'gender_id' => $gender->id,
            'nationalitie_id' => $this->defaultNationalitieId,
            'blood_id' => $this->defaultBloodTypeId,
            'Date_Birth' => $row['dob'],
            'Grade_id' => $classroom->Grade_id,
            'Classroom_id' => $classroom->id,
            'section_id' => $section->id,
            'parent_id' => $row['parent_id'],
            'academic_year' => $this->activeFiscalYear->name,
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
        ]);

        StudentFiscalDetail::create([
            'student_id' => $student->id,
            'academic_year_id' => $this->activeFiscalYear->id,
            'active_fiscal_year_id' => $this->activeFiscalYear->id,
            'admission_no' => $row['admission_no'] ?? null,
            'admission_date' => $row['admission_date'] ?? null,
            'class_id' => $classroom->id,
            'section_id' => $section->id,
            'roll_no' => $row['roll_no'] ?? null,
        ]);

        return $student;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'dob' => 'required|date|date_format:Y-m-d',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'parent_id' => 'required|exists:my__parents,id',
            'admission_no' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date|date_format:Y-m-d',
            'class_id' => 'required|exists:classrooms,id',
            'section_id' => 'required|exists:sections,id',
            'roll_no' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'parent_id.exists' => 'The selected parent ID does not exist.',
            'class_id.exists' => 'The selected class ID does not exist.',
            'section_id.exists' => 'The selected section ID does not exist.',
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                DB::beginTransaction();
            },
            AfterImport::class => function (AfterImport $event) {
                if (count($this->failures()) || count($this->errors())) {
                    DB::rollBack();
                } else {
                    DB::commit();
                }
            },
            ImportFailed::class => function (ImportFailed $event) {
                DB::rollBack();
            },
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
