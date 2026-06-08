<?php

namespace App\Exports;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ClassesReferenceSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Classroom::select([
            'id as class_id',
            'Name_Class as class_name',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'class_id',
            'class_name',
        ];
    }

    public function title(): string
    {
        return 'Classes Reference';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:B1')->getFont()->setBold(true);
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
