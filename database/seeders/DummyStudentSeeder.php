<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = DB::table('sections')->get();
        
        $firstNames = ['Arjun', 'Priya', 'Rahul', 'Anjali', 'Vikram', 'Saniya', 'Amit', 'Pooja', 'Karan', 'Sneha'];
        $lastNames = ['Sharma', 'Patel', 'Verma', 'Rao', 'Singh', 'Khan', 'Das', 'Gupta', 'Mehta', 'Reddy'];

        foreach ($sections as $section) {
            $count = rand(5, 8);
            
            for ($i = 1; $i <= $count; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $name = "{$firstName} {$lastName} {$section->id}_{$i}";
                $email = strtolower("{$firstName}.{$lastName}." . uniqid() . "@test.com");
                
                DB::table('students')->insert([
                    'name' => $name,
                    'email' => $email,
                    'roll_number' => $i,
                    'dob' => '2015-01-01',
                    'grade_id' => $section->grade_id,
                    'section_id' => $section->id,
                    'school_id' => $section->school_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $this->command->info("Inserted {$count} students for section ID {$section->id}");
        }
    }
}
