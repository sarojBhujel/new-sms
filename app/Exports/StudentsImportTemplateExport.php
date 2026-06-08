<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentsImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new StudentsImportSampleSheet(),
            new ParentsReferenceSheet(),
            new ClassesReferenceSheet(),
            new SectionsReferenceSheet(),
        ];
    }
}
