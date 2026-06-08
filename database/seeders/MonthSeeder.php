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
            ['sequence' => 1, 'name_en' => 'Baishakh', 'name_np' => 'बैशाख'],
            ['sequence' => 2, 'name_en' => 'Jestha', 'name_np' => 'जेठ'],
            ['sequence' => 3, 'name_en' => 'Ashadh', 'name_np' => 'असार'],
            ['sequence' => 4, 'name_en' => 'Shrawan', 'name_np' => 'साउन'],
            ['sequence' => 5, 'name_en' => 'Bhadra', 'name_np' => 'भदौ'],
            ['sequence' => 6, 'name_en' => 'Ashwin', 'name_np' => 'असोज'],
            ['sequence' => 7, 'name_en' => 'Kartik', 'name_np' => 'कात्तिक'],
            ['sequence' => 8, 'name_en' => 'Mangsir', 'name_np' => 'मंसिर'],
            ['sequence' => 9, 'name_en' => 'Poush', 'name_np' => 'पुस'],
            ['sequence' => 10, 'name_en' => 'Magh', 'name_np' => 'माघ'],
            ['sequence' => 11, 'name_en' => 'Falgun', 'name_np' => 'फागुन'],
            ['sequence' => 12, 'name_en' => 'Chaitra', 'name_np' => 'चैत'],
        ];
                foreach ($months as $month) {
            DB::table('nepali_months')->updateOrInsert(
                ['sequence' => $month['sequence']], // Prevents duplicates if run twice
                [
                    'name_en' => $month['name_en'],
                    'name_np' => $month['name_np'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
