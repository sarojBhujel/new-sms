<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Classroom;
use App\Models\Grade;
use App\Models\My_Parent;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call(BloodTableSeeder::class);
        $this->call(NationalitiesTableSeeder::class);
        $this->call(ReligionTableSeeder::class);
        $this->call(SpecializationTableSeeder::class);
        $this->call(GenderTableSeeder::class);
        $this->call(MonthSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SettingsTableSeeder::class);




        Grade::truncate(); 
        Grade::create([
            'Name' =>  "Primary Stage",
            'Notes' => '',
        ]);
        Grade::create([
            'Name' =>  "Middle School",
            'Notes' => '',
        ]);
        Grade::create([
            'Name' =>  "High School",
            'Notes' => '',
        ]);


        Classroom::truncate(); 
        Classroom::create([
            'Name_Class' =>  "first level",
            'Grade_id' => '1',
        ]);
        Classroom::create([
            'Name_Class' =>  "second level",
            'Grade_id' => '1',
        ]);
        Classroom::create([
            'Name_Class' =>  "first level",
            'Grade_id' => '2',
        ]);
        Classroom::create([
            'Name_Class' =>  "second level",
            'Grade_id' => '2',
        ]);
        Classroom::create([
            'Name_Class' =>  "first level",
            'Grade_id' => '3',
        ]);
        Classroom::create([
            'Name_Class' =>  "second level",
            'Grade_id' => '3',
        ]);


        DB::table('sections')->delete();
        Section::create([
            'Name_Section' =>  "A",
            'Status' => '1',
            'Grade_id' => '1', 'Class_id' => '1',
        ]);
        Section::create([
            'Name_Section' =>  "B",
            'Status' => '1',
            'Grade_id' => '1', 'Class_id' => '1',
        ]);
        Section::create([
            'Name_Section' =>  "A",
            'Status' => '1',
            'Grade_id' => '1', 'Class_id' => '2',
        ]);
        Section::create([
            'Name_Section' =>  "B",
            'Status' => '1',
            'Grade_id' => '1', 'Class_id' => '2',
        ]);

        Section::create([
            'Name_Section' =>  "A",
            'Status' => '1',
            'Grade_id' => '2', 'Class_id' => '3',
        ]);
        Section::create([
            'Name_Section' =>  "B",
            'Status' => '1',
            'Grade_id' => '2', 'Class_id' => '3',
        ]);
        Section::create([
            'Name_Section' =>  "A",
            'Status' => '1',
            'Grade_id' => '2', 'Class_id' => '4',
        ]);
        Section::create([
            'Name_Section' =>  "B",
            'Status' => '1',
            'Grade_id' => '2', 'Class_id' => '4',
        ]);

        Section::create([
            'Name_Section' =>  "A",
            'Status' => '1',
            'Grade_id' => '3', 'Class_id' => '5',
        ]);
        Section::create([
            'Name_Section' =>  "B",
            'Status' => '1',
            'Grade_id' => '3', 'Class_id' => '5',
        ]);
        Section::create([
            'Name_Section' =>  "A",
            'Status' => '1',
            'Grade_id' => '3', 'Class_id' => '6',
        ]);
        Section::create([
            'Name_Section' =>  "B",
            'Status' => '1',
            'Grade_id' => '3', 'Class_id' => '6',
        ]);


        DB::table('my__parents')->delete();
        My_Parent::create([
            'Email' => 'my@example.com',
            'Password' => Hash::make('12345678'),
            'Name_Father' =>  "Osama",
            'National_ID_Father' => '1234567890',
            'Passport_ID_Father' => '1234567890',
            'Phone_Father' => '1234567890',
            'Job_Father' =>  "mozef",
            'Nationality_Father_id' => '1',
            'Blood_Type_Father_id' => '1',
            'Religion_Father_id' => '1',
            'Address_Father' => '21 ithad street monib',
            'Name_Mother' =>  "Salwa",
            'National_ID_Mother' => '1234567890',
            'Passport_ID_Mother' => '1234567890',
            'Phone_Mother' => '1234567890',
            'Job_Mother' =>  "mozef",
            'Nationality_Mother_id' => '1',
            'Blood_Type_Mother_id' => '1',
            'Religion_Mother_id' => '1',
            'Address_Mother' => '21 ithad street monib'
        ]);


        DB::table('students')->delete();
        Student::create([
            'email' => 'khaled@example.com',
            'password' => Hash::make('12345678'),
            'name' =>  "Khaled",
            'gender_id' => '1',
            'nationalitie_id' => '1',
            'blood_id' => '1',
            'Date_Birth' => '2002-06-12',
            'Grade_id' => '1',
            'Classroom_id' => '1',
            'section_id' => '1',
            'parent_id' => '1',
            'academic_year' => '2022',
        ]);
        Student::create([
            'email' => 'ahmed@example.com',
            'password' => Hash::make('12345678'),
            'name' =>  "ahmed",
            'gender_id' => '1',
            'nationalitie_id' => '2',
            'blood_id' => '3',
            'Date_Birth' => '1996-06-12',
            'Grade_id' => '1',
            'Classroom_id' => '1',
            'section_id' => '1',
            'parent_id' => '1',
            'academic_year' => '2022',
        ]);
        Student::create([
            'email' => 'sara@example.com',
            'password' => Hash::make('12345678'),
            'name' =>  "sara",
            'gender_id' => '2',
            'nationalitie_id' => '3',
            'blood_id' => '5',
            'Date_Birth' => '1997-06-12',
            'Grade_id' => '1',
            'Classroom_id' => '1',
            'section_id' => '1',
            'parent_id' => '1',
            'academic_year' => '2022',
        ]);

        DB::table('teachers')->delete();
        Teacher::create([
            'Email' => 'khaled@example.com',
            'Password' => Hash::make('12345678'),
            'name' =>  "Khaled",
            'gender_id' => '1',
            'Specialization_id' => '1',
            'Joining_Date' => '2022-10-17',
            'Address' => '21 ithad street monib',
        ]);

        DB::table('subjects')->delete();
        Subject::create([
            'name' =>  "science",
            'grade_id' => '1',
            'classroom_id' => '1',
            'teacher_id' => '1',
        ]);
        Subject::create([
            'name' =>  "computer",
            'grade_id' => '1',
            'classroom_id' => '1',
            'teacher_id' => '1',
        ]);
    }
}