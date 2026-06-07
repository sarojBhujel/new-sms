<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                $months = [
            ['sequence' => 1, 'name_en' => 'Baishakh', 'name_ne' => 'बैशाख'],
            ['sequence' => 2, 'name_en' => 'Jestha', 'name_ne' => 'जेठ'],
            ['sequence' => 3, 'name_en' => 'Ashadh', 'name_ne' => 'असार'],
            ['sequence' => 4, 'name_en' => 'Shrawan', 'name_ne' => 'साउन'],
            ['sequence' => 5, 'name_en' => 'Bhadra', 'name_ne' => 'भदौ'],
            ['sequence' => 6, 'name_en' => 'Ashwin', 'name_ne' => 'असोज'],
            ['sequence' => 7, 'name_en' => 'Kartik', 'name_ne' => 'कात्तिक'],
            ['sequence' => 8, 'name_en' => 'Mangsir', 'name_ne' => 'मंसिर'],
            ['sequence' => 9, 'name_en' => 'Poush', 'name_ne' => 'पुस'],
            ['sequence' => 10, 'name_en' => 'Magh', 'name_ne' => 'माघ'],
            ['sequence' => 11, 'name_en' => 'Falgun', 'name_ne' => 'फागुन'],
            ['sequence' => 12, 'name_en' => 'Chaitra', 'name_ne' => 'चैत'],
        ];
                foreach ($months as $month) {
            DB::table('nepali_months')->updateOrInsert(
                ['sequence' => $month['sequence']], // Prevents duplicates if run twice
                [
                    'name_en' => $month['name_en'],
                    'name_ne' => $month['name_ne'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
