<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student; // Make sure this path is correct to your Student model

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed 10 students using the Student factory
        Student::factory(10)->create();
    }
}

