<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedClioData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-clio-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $school = \App\Models\School::where('name', 'Clio Lucas')->first();
        if (!$school) {
            $school = \App\Models\School::create([
                'name' => 'Clio Lucas',
                'address' => '123 School Street',
                'phone' => '1234567890',
                'email' => 'clio@lucas.com'
            ]);
        }

        $schoolId = $school->id;
        $this->info("Using School ID: $schoolId");

        $gradeNames = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
        $grades = [];
        foreach ($gradeNames as $name) {
            $grades[] = \App\Models\Grade::firstOrCreate([
                'school_id' => $schoolId,
                'name' => $name
            ]);
        }

        $examNames = ['Monthly Test', 'Half Yearly', 'Quarterly', 'Final Examination'];
        $examTypes = [];
        foreach ($examNames as $name) {
            $examTypes[] = \App\Models\ExamType::firstOrCreate([
                'school_id' => $schoolId,
                'name' => $name
            ]);
        }

        $allSubjects = \App\Models\Subject::all();
        if ($allSubjects->count() == 0) {
            foreach (['Math', 'Science', 'English', 'History'] as $s) {
                \App\Models\Subject::create(['name' => $s]);
            }
            $allSubjects = \App\Models\Subject::all();
        }

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $grade = $grades[array_rand($grades)];
            $student = \App\Models\Student::create([
                'school_id' => $schoolId,
                'grade_id' => $grade->id,
                'name' => $faker->name,
                'roll_number' => 'ROLL-' . ($i + 500),
                'dob' => $faker->date('Y-m-d', '2012-01-01'),
                'email' => $faker->unique()->safeEmail,
                'father_name' => $faker->name('male'),
                'mother_name' => $faker->name('female')
            ]);

            foreach ($examTypes as $exam) {
                foreach ($allSubjects->random(min(4, $allSubjects->count())) as $subject) {
                    \App\Models\Mark::create([
                        'school_id' => $schoolId,
                        'student_id' => $student->id,
                        'exam_type_id' => $exam->id,
                        'subject_id' => $subject->id,
                        'marks_obtained' => rand(40, 95),
                        'max_marks' => 100,
                        'remarks' => 'Good effort'
                    ]);
                }
            }
        }

        $this->info("Successfully seeded data for Clio Lucas.");
    }
}
