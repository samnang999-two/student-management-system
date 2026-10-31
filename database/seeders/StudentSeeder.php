<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('students')->insert([
            [
                'student_name' => 'Somnagn',
                'gender' => 'male',
                'age' => 20,
                'dob' => '2005-02-21',
                'payment' => 4200.99,
                'subject' => 'CA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_name' => 'Ly ly',
                'gender' => 'female',
                'age' => 21,
                'dob' => '2005-03-21',
                'payment' => 4600.99,
                'subject' => 'Python',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_name' => 'Long',
                'gender' => 'male',
                'age' => 22,
                'dob' => '2003-02-21',
                'payment' => 2200.99,
                'subject' => 'MA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
