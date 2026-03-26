<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $student->name }} - {{ $exam->name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <style>
        body { font-family: 'Outfit', sans-serif; background: #fff; color: #333; }
        .print-only { display: none; }
        @media print {
            .no-print { display: none; }
            .print-only { display: block; }
            body { -webkit-print-color-adjust: exact; }
            .card { border: none !important; }
            .table-bordered th, .table-bordered td { border: 1px solid #333 !important; }
        }
        .report-card { 
            max-width: 900px; 
            margin: 20px auto; 
            padding: 40px; 
            border: 2px solid #333;
            background: #fff;
            position: relative;
        }
        .school-header { 
            text-align: center; 
            border-bottom: 3px double #333; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .school-name { font-size: 32px; font-weight: 800; text-transform: uppercase; margin: 0; color: #1a237e; }
        .school-info { font-size: 14px; margin: 5px 0; }
        .report-title { 
            text-align: center; 
            background: #1a237e; 
            color: #fff; 
            padding: 10px; 
            font-size: 20px; 
            font-weight: 700; 
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .student-details { margin-bottom: 30px; }
        .detail-item { margin-bottom: 10px; font-size: 16px; border-bottom: 1px dashed #ccc; padding-bottom: 5px; }
        .detail-label { font-weight: 700; width: 150px; display: inline-block; }
        .table thead th { background-color: #f0f2f5 !important; color: #1a237e; border-bottom: 2px solid #333 !important; text-align: center; }
        .result-summary { margin-top: 30px; display: flex; justify-content: space-between; align-items: flex-start; }
        .marks-summary { width: 300px; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 16px; border-bottom: 1px solid #eee; }
        .summary-label { font-weight: 600; }
        .status-box { 
            padding: 20px; 
            border: 2px solid #333; 
            text-align: center; 
            width: 200px; 
            font-size: 24px; 
            font-weight: 800;
        }
        .passed { color: #2e7d32; border-color: #2e7d32; }
        .failed { color: #c62828; border-color: #c62828; }
        .footer { margin-top: 80px; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature-box { border-top: 1px solid #333; width: 180px; text-align: center; padding-top: 5px; font-weight: 600; font-size: 14px; position: relative; }
        .digital-sig { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); max-height: 50px; pointer-events: none; mix-blend-mode: multiply; }
    </style>
</head>
<body>
    <div class="container py-4 no-print">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <button onclick="window.print()" class="btn btn-primary btn-lg shadow-sm px-5">
                    <i class="fas fa-print mr-2"></i> Print Report Card
                </button>
                <button onclick="window.close()" class="btn btn-outline-secondary btn-lg shadow-sm ml-2">
                    Close
                </button>
            </div>
        </div>
    </div>

    <div class="report-card">
        <div class="school-header d-flex justify-content-between align-items-center">
            <div class="header-logo">
                @if($student->school && $student->school->logo)
                    <img src="{{ $student->school->logo_url }}" alt="School Logo" style="height: 100px;">
                @else
                    <div style="height: 100px; width: 100px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #aaa;">SCHOOL LOGO</div>
                @endif
            </div>
            <div class="header-text text-center">
                <h1 class="school-name mb-0">{{ $student->school->name ?? 'School Management System' }}</h1>
                <p class="school-info mb-0">{{ $student->school->address ?? 'Main Campus, Academic Square' }}</p>
                <p class="school-info mb-0">Contact: {{ $student->school->phone ?? 'XXXX-XXXXXX' }}</p>
            </div>
            <div class="student-photo">
                @if($student->photo)
                    <img src="{{ $student->photo_url }}" alt="Student Photo" style="height: 100px; width: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                @else
                    <div style="height: 100px; width: 100px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #aaa;">STUDENT PHOTO</div>
                @endif
            </div>
        </div>

        <div class="report-title">
            STUDENT PROGRESS REPORT ({{ strtoupper($exam->name) }})
        </div>

        <div class="student-details">
            <div class="row">
                <div class="col-4">
                    <div class="detail-item"><span class="detail-label">Student Name:</span> {{ $student->name }}</div>
                    <div class="detail-item"><span class="detail-label">Father's Name:</span> {{ $student->father_name ?: 'N/A' }}</div>
                    <div class="detail-item"><span class="detail-label">Mother's Name:</span> {{ $student->mother_name ?: 'N/A' }}</div>
                </div>
                <div class="col-4">
                    <div class="detail-item"><span class="detail-label">Roll Number:</span> {{ $student->roll_number }}</div>
                    <div class="detail-item"><span class="detail-label">Admission No:</span> {{ $student->id }}</div>
                    <div class="detail-item"><span class="detail-label">Date of Birth:</span> {{ $student->dob ? date('d M, Y', strtotime($student->dob)) : 'N/A' }}</div>
                </div>
                <div class="col-4 text-right">
                    <div class="detail-item"><span class="detail-label">Class:</span> {{ $student->grade->name ?? 'N/A' }}</div>
                    <div class="detail-item"><span class="detail-label">Section:</span> {{ $student->section->name ?? 'N/A' }}</div>
                    <div class="detail-item"><span class="detail-label">Date of Issue:</span> {{ date('d M, Y') }}</div>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="80">S.No</th>
                    <th>Subject</th>
                    <th width="150" class="text-center">Maximum Marks</th>
                    <th width="150" class="text-center">Marks Obtained</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marks as $index => $m)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $m->subject->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $m->max_marks ?: 100 }}</td>
                        <td class="text-center font-weight-bold">{{ $m->marks_obtained }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f8f9fa; font-weight: 800;">
                    <td colspan="2" class="text-right">GRAND TOTAL</td>
                    <td class="text-center">{{ $totalMax }}</td>
                    <td class="text-center">{{ $totalObtained }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="result-summary">
            <div class="marks-summary">
                <div class="summary-item">
                    <span class="summary-label">Percentage:</span>
                    <span>{{ number_format($percentage, 2) }}%</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Position in Class:</span>
                    <span class="font-weight-bold text-primary">{{ $position }}</span>
                </div>
            </div>

            <div class="status-box {{ $percentage >= 40 ? 'passed' : 'failed' }}">
                {{ $percentage >= 40 ? 'PASSED' : 'FAILED' }}
            </div>
        </div>

        <div class="footer">
            <div class="signature-box">
                @if($student->grade && $student->grade->teacher && $student->grade->teacher->signature)
                    <img src="{{ $student->grade->teacher->signature_url }}" class="digital-sig" alt="Teacher Signature">
                @endif
                <div class="mt-1">{{ $student->grade->teacher->name ?? 'Class Teacher' }}</div>
                <div class="small text-muted" style="font-size: 10px; font-weight: 400;">Class Teacher Signature</div>
            </div>
            <div class="signature-box">
                <div class="mt-4">Parent Signature</div>
            </div>
            <div class="signature-box">
                @if($student->school && $student->school->principal_signature)
                    <img src="{{ $student->school->signature_url }}" class="digital-sig" alt="Principal Signature">
                @endif
                <div class="mt-4">Principal Signature</div>
            </div>
        </div>
    </div>

    <script>
        // Load Font Awesome
        (function() {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
            document.head.appendChild(link);
        })();
    </script>
</body>
</html>
