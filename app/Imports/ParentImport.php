<?php

namespace App\Imports;

use App\Models\My_Parent;
use App\Models\Nationalitie;
use App\Models\Type_Blood;
use App\Models\Religion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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

class ParentImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, SkipsEmptyRows, WithBatchInserts, WithChunkReading, WithEvents
{
    use Importable, SkipsFailures, SkipsErrors;

    private int $defaultNationalitieId;
    private int $defaultBloodTypeId;
    private int $defaultReligionId;

    public function __construct()
    {
        $nationality = Nationalitie::first();
        $bloodType = Type_Blood::first();
        $religion = Religion::first();

        if (!$nationality || !$bloodType || !$religion) {
            throw new \Exception('Default reference data is missing. Ensure nationality, blood type and religion data exist.');
        }

        $this->defaultNationalitieId = $nationality->id;
        $this->defaultBloodTypeId = $bloodType->id;
        $this->defaultReligionId = $religion->id;
    }

    public function model(array $row)
    {
        return new My_Parent([
            'email' => $row['email'],
            'password' => Hash::make(Str::random(12)),
            'Name_Father' => $row['father_name'] ?? $row['name'],
            'National_ID_Father' => $row['citizenship_no'],
            'Passport_ID_Father' => $row['citizenship_no'],
            'Phone_Father' => $row['phone'],
            'Job_Father' => $row['occupation'],
            'Nationality_Father_id' => $this->defaultNationalitieId,
            'Blood_Type_Father_id' => $this->defaultBloodTypeId,
            'Religion_Father_id' => $this->defaultReligionId,
            'Address_Father' => $row['address'],
            'Name_Mother' => $row['mother_name'],
            'National_ID_Mother' => $row['citizenship_no'],
            'Passport_ID_Mother' => $row['citizenship_no'],
            'Phone_Mother' => $row['phone'],
            'Job_Mother' => $row['occupation'],
            'Nationality_Mother_id' => $this->defaultNationalitieId,
            'Blood_Type_Mother_id' => $this->defaultBloodTypeId,
            'Religion_Mother_id' => $this->defaultReligionId,
            'Address_Mother' => $row['address'],
            'remarks' => $row['remarks'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'citizenship_no' => 'required|string|max:255|unique:my__parents,National_ID_Father',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|unique:my__parents,email',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'citizenship_no.unique' => 'The citizenship number has already been taken.',
            'email.unique' => 'The email has already been taken.',
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
