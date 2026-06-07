<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('specializations')->delete();
        $specializations = [
            ['Nepali'],
            ['Sciences'],
            ['Computer'],
            ['English'],
        ];
        foreach ($specializations as $S) {
            Specialization::create(['Name' => $S]);
        }
    }
}
