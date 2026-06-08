<?php

namespace App\Exports;

use App\Models\Section;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class SectionsReferenceSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Section::select([
            'id as section_id',
            'Name_Section as section_name',
            'Class_id as class_id',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'section_id',
            'section_name',
            'class_id',
        ];
    }

    public function title(): string
    {
        return 'Sections Reference';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:C1')->getFont()->setBold(true);
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
