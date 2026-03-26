<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('next_roll')) {
                $lastRoll = \App\Models\Student::max('roll_number');
                return response()->json(['next_roll' => ($lastRoll ? (int)$lastRoll + 1 : 1001)]);
            }
            $query = \App\Models\Student::with(['grade', 'section', 'school'])->latest();
            
            if (auth()->user()->isMasterAdmin() && $request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }

            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('grade_id', $request->grade_id);
            }
            if ($request->has('section_id') && !empty($request->section_id)) {
                $query->where('section_id', $request->section_id);
            }

            $students = $query->get();
            return response()->json($students);
        }
        $grades = collect();
        $sections = collect();
        $admins = collect();

        if (auth()->user()->isMasterAdmin()) {
            $admins = \App\Models\User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->with('school')->get();

            if ($request->has('school_id') && !empty($request->school_id)) {
                $grades = \App\Models\Grade::where('school_id', $request->school_id)->orderBy('name', 'asc')->get();
            }
            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $sections = \App\Models\Section::where('grade_id', $request->grade_id)->orderBy('name', 'asc')->get();
            }
        } else {
            $grades = \App\Models\Grade::where('school_id', auth()->user()->school_id)->orderBy('name', 'asc')->get();
            // Important: Keep sections empty initially to prevent duplicates from different grades showing up.
            // The frontend dynamic dropdown logic will populate this when a grade is selected.
            $sections = collect();
        }

        $branches = collect();
        if (auth()->user()->isMasterAdmin()) {
            if ($request->has('school_id') && !empty($request->school_id)) {
                $branches = \App\Models\Branch::where('school_id', $request->school_id)->get();
            } else {
                $branches = \App\Models\Branch::all();
            }
        } else {
            $branches = \App\Models\Branch::where('school_id', auth()->user()->school_id)->get();
        }

        return view('admissions.index', compact('grades', 'sections', 'admins', 'branches'));
    }

    public function store(Request $request)
    {
        $school_id = auth()->user()->isMasterAdmin() ? $request->school_id : auth()->user()->school_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'roll_number' => [
                'required',
                'string',
                Rule::unique('students')->where(function ($query) use ($school_id) {
                    return $query->where('school_id', $school_id);
                })
            ],
            'dob' => 'required|date',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'branch_id' => 'nullable|exists:branches,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'caste' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_phone' => 'nullable|string|max:20',
            'admission_date' => 'required|date',
            'previous_school' => 'nullable|string|max:255',
            'adhaar_number' => 'nullable|string|max:20',
            'apaar_id' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_photo.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/students'), $filename);
            $validated['photo'] = 'students/' . $filename;
        }

        $student = \App\Models\Student::create($validated);

        return response()->json(['success' => __('Student admitted successfully.'), 'student' => $student]);
    }

    public function show(string $id)
    {
        $student = \App\Models\Student::with(['grade', 'section', 'school'])->findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $school_id = auth()->user()->isMasterAdmin() ? $request->school_id : auth()->user()->school_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'roll_number' => [
                'required',
                'string',
                Rule::unique('students')->where(function ($query) use ($school_id) {
                    return $query->where('school_id', $school_id);
                })->ignore($student->id)
            ],
            'dob' => 'required|date',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'branch_id' => 'nullable|exists:branches,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'caste' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_phone' => 'nullable|string|max:20',
            'admission_date' => 'required|date',
            'previous_school' => 'nullable|string|max:255',
            'adhaar_number' => 'nullable|string|max:20',
            'apaar_id' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($request->hasFile('photo')) {
            if ($student->photo && file_exists(public_path('storage/' . $student->photo))) {
                @unlink(public_path('storage/' . $student->photo));
            }
            $file = $request->file('photo');
            $filename = time() . '_photo.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/students'), $filename);
            $validated['photo'] = 'students/' . $filename;
        }

        $student->update($validated);

        return response()->json(['success' => __('Student info updated successfully.'), 'student' => $student]);
    }

    public function destroy(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();

        return response()->json(['success' => __('Student record deleted successfully.')]);
    }

    public function deletePhoto(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        
        if ($student->photo) {
            if (file_exists(public_path('storage/' . $student->photo))) {
                @unlink(public_path('storage/' . $student->photo));
            }
            $student->photo = null;
            $student->save();
        }
        
        return response()->json(['success' => __('Photo deleted successfully.')]);
    }

    public function bulkAdmission()
    {
        $grades = \App\Models\Grade::orderBy('name', 'asc')->get();
        if (auth()->user()->isMasterAdmin()) {
            $schools = \App\Models\School::all();
            return view('admissions.bulk', compact('grades', 'schools'));
        }
        return view('admissions.bulk', compact('grades'));
    }

    public function downloadSample()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=student_admission_sample.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'Name', 'Email', 'Roll Number', 'DOB (YYYY-MM-DD)', 
            'Gender (Male/Female/Other)', 'Admission Date (YYYY-MM-DD)',
            'Father Name', 'Father Phone', 'Mother Name', 'Mother Phone',
            'Caste', 'Previous School', 'Adhaar Number', 'Apaar ID'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, [
                'John Doe', 'john@example.com', '1001', '2010-05-15', 
                'Male', date('Y-m-d'), 
                'Richard Doe', '9876543210', 'Jane Doe', '9876543211',
                'General', 'ABC School', '123456789012', 'APAAR-123'
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt,xlsx',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
        ]);

        $school_id = auth()->user()->isMasterAdmin() ? $request->school_id : auth()->user()->school_id;
        $file = $request->file('csv_file');
        
        $handle = fopen($file->getRealPath(), "r");
        $header = fgetcsv($handle); // Skip header

        $count = 0;
        $errors = [];
        $row_num = 1; // Counter for displaying error location

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_num++;
            if (empty(array_filter($data))) continue; // Skip empty rows

            if (count($data) < 6) {
                $errors[] = "Row {$row_num}: Missing required columns (Name, Email, Roll, DOB, Gender, Admission Date).";
                continue;
            }

            $gender = trim($data[4]);
            $adm_date = trim($data[5]);
            $father = trim($data[6] ?? '');
            $f_phone = trim($data[7] ?? '');
            $mother = trim($data[8] ?? '');
            $m_phone = trim($data[9] ?? '');
            $caste = trim($data[10] ?? '');
            $prev_school = trim($data[11] ?? '');
            $adhaar = trim($data[12] ?? '');
            $apaar = trim($data[13] ?? '');

            if (empty($name) || empty($email) || empty($roll) || empty($dob) || empty($gender) || empty($adm_date)) {
                $errors[] = "Row {$row_num}: Required fields (Name, Email, Roll, DOB, Gender, Admission Date) are empty.";
                continue;
            }

            if (\App\Models\Student::where('email', $email)->exists()) {
                $errors[] = "Row {$row_num}: Email already exists.";
                continue;
            }

            if (\App\Models\Student::where('roll_number', $roll)->where('school_id', $school_id)->exists()) {
                $errors[] = "Row {$row_num}: Roll Number already exists in this school.";
                continue;
            }

            try {
                \App\Models\Student::create([
                    'school_id' => $school_id,
                    'grade_id' => $request->grade_id,
                    'section_id' => $request->section_id,
                    'name' => $name,
                    'email' => $email,
                    'roll_number' => $roll,
                    'dob' => $dob,
                    'gender' => $gender,
                    'admission_date' => $adm_date,
                    'father_name' => $father,
                    'father_phone' => $f_phone,
                    'mother_name' => $mother,
                    'mother_phone' => $m_phone,
                    'caste' => $caste,
                    'previous_school' => $prev_school,
                    'adhaar_number' => $adhaar,
                    'apaar_id' => $apaar,
                ]);
                $count++;
            } catch (\Exception $e) {
                $errors[] = "Row {$row_num}: Failed to save - " . $e->getMessage();
            }
        }
        fclose($handle);

        if (count($errors) > 0) {
            $errorMsg = count($errors) . " records failed. Successfully imported " . $count . " records. Errors: " . implode(" | ", array_slice($errors, 0, 5));
            if (count($errors) > 5) $errorMsg .= " (and more...)";
            return redirect()->back()->with('error', $errorMsg);
        }

        return redirect()->route('admissions.index')->with('success', $count . " students imported successfully.");
    }

    public function print($id)
    {
        $student = \App\Models\Student::with(['grade', 'section', 'school'])->findOrFail($id);
        return view('admissions.print', compact('student'));
    }

    public function printBlank(Request $request)
    {
        $student = new \App\Models\Student();
        if ($request->has('school_id') && !empty($request->school_id)) {
            $student->school = \App\Models\School::find($request->school_id);
            if ($student->school) {
                $student->school_id = $student->school->id;
            }
        }
        return view('admissions.print', compact('student'));
    }
}
