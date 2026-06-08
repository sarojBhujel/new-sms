<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ParentImportTemplateExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return new Collection([
            [
                'name' => 'Ali Khan',
                'father_name' => 'Mohammad Khan',
                'mother_name' => 'Sara Bibi',
                'citizenship_no' => '1234567890',
                'phone' => '+9779810000000',
                'email' => 'ali.khan@example.com',
                'address' => 'Kathmandu, Nepal',
                'occupation' => 'Civil Engineer',
                'remarks' => 'Imported from template',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'name',
            'father_name',
            'mother_name',
            'citizenship_no',
            'phone',
            'email',
            'address',
            'occupation',
            'remarks',
        ];
    }

    public function title(): string
    {
        return 'Parent Import Template';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:I1')->getFont()->setBold(true);
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
