<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religions')->delete();

        $religions = [

            [
                'Hindu',
            ],
            [
                'Muslim',
            ],
            [
                 'Christian',
            ],
            [
                'Other',
            ],

        ];

        foreach ($religions as $R) {
            Religion::create(['Name' => $R]);
        }
    }
}
