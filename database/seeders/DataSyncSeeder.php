<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Subject;

class DataSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sync Sections with Grades
        $sections = Section::with('grade')->get();
        foreach ($sections as $section) {
            if ($section->grade && $section->school_id != $section->grade->school_id) {
                $section->school_id = $section->grade->school_id;
                $section->save();
                $this->command->info("Updated Section {$section->id} to school {$section->school_id}");
            }
        }

        // Sync Subjects with Grades
        $subjects = Subject::with('grade')->get();
        foreach ($subjects as $subject) {
            if ($subject->grade && $subject->school_id != $subject->grade->school_id) {
                $subject->school_id = $subject->grade->school_id;
                $subject->save();
                $this->command->info("Updated Subject {$subject->id} to school {$subject->school_id}");
            }
        }
    }
}
