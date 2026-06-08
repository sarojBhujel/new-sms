<?php

namespace App\Exports;

use App\Models\My_Parent;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ParentsReferenceSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return My_Parent::select([
            'id as parent_id',
            DB::raw("CONCAT(Name_Father, ' & ', Name_Mother) as parent_name"),
            'Phone_Father as phone',
            'National_ID_Father as citizenship_no',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'parent_id',
            'parent_name',
            'phone',
            'citizenship_no',
        ];
    }

    public function title(): string
    {
        return 'Parents Reference';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:D1')->getFont()->setBold(true);
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
