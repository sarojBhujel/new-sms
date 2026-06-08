<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsImportSampleSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return new Collection([
            [
                'first_name' => 'Raj',
                'last_name' => 'Sharma',
                'gender' => 'Male',
                'dob' => '2010-05-12',
                'phone' => '+9779810000001',
                'address' => 'Kathmandu, Nepal',
                'parent_id' => '1',
                'admission_no' => 'ADM-1001',
                'admission_date' => '2026-03-01',
                'class_id' => '1',
                'section_id' => '1',
                'roll_no' => '10',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'gender',
            'dob',
            'phone',
            'address',
            'parent_id',
            'admission_no',
            'admission_date',
            'class_id',
            'section_id',
            'roll_no',
        ];
    }

    public function title(): string
    {
        return 'Students Import Template';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:L1')->getFont()->setBold(true);
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
