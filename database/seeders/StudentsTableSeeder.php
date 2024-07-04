<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Seed the application's database with student records.
     *
     * @return void
     */
    public function run()
    {
        // Define student data to be inserted
        $students = [
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'birthdate' => '2000-01-01',
                'sex' => 'MALE',
                'address' => '123 Main St, Anytown',
                'year' => 3,
                'course' => 'BSIT',
                'section' => 'A',
            ],
            [
                'firstname' => 'Jane',
                'lastname' => 'Smith',
                'birthdate' => '2001-02-15',
                'sex' => 'FEMALE',
                'address' => '456 Elm St, Othertown',
                'year' => 2,
                'course' => 'BSCS',
                'section' => 'B',
            ],
            // Add more student records as needed
        ];

        // Insert the data into the 'students' table
        DB::table('students')->insert($students);
    }
}
