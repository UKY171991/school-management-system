<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Report Card - {{ $student->name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <style>
        body { font-family: 'Outfit', sans-serif; background: #fff; color: #333; font-size: 13px; }
        .no-print { margin-bottom: 20px; }
        
        @media print {
            @page {
                size: A4 landscape; /* Matrix reports often fit better in landscape */
                margin: 5mm;
            }
            .no-print { display: none !important; }
            body { 
                -webkit-print-color-adjust: exact; 
                margin: 0;
                padding: 0;
                font-size: 11px;
            }
            .container { 
                max-width: 100% !important; 
                width: 100% !important; 
                margin: 0 !important; 
                padding: 0 !important;
            }
            .table th, .table td { padding: 3px !important; }
        }

        .report-header { text-align: center; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 20px; }
        .school-name { font-size: 28px; font-weight: 800; text-transform: uppercase; color: #1a237e; }
        .student-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd; }
        
        .table thead th { 
            background-color: #1a237e !important; 
            color: #fff !important; 
            border: 1px solid #333 !important; 
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
        }
        .table td { border: 1px solid #ddd !important; vertical-align: middle; }
        .bg-totals { background-color: #f0f2f5 !important; font-weight: 700; }
        .footer { margin-top: 60px; display: flex; justify-content: space-between; align-items: flex-end; }
        .sig-box { border-top: 1px solid #333; width: 180px; text-align: center; padding-top: 5px; font-size: 12px; font-weight: 600; position: relative; }
        .digital-sig { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-height: 45px; pointer-events: none; mix-blend-mode: multiply; }
        
        .summary-bar { 
            display: flex; 
            justify-content: space-between; 
            background: #f8f9fa; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-top: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-3">
        <div class="no-print text-center mb-3">
            <button onclick="window.print()" class="btn btn-primary shadow-sm mr-2">
                <i class="fas fa-print mr-2"></i> Print Report Card
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary shadow-sm">Close</button>
        </div>

        <div class="report-card-wrapper">
            <!-- Header with Images -->
            <div class="report-header d-flex justify-content-between align-items-center">
                <div class="header-logo">
                    @if($student->school && $student->school->logo)
                        <img src="{{ $student->school->logo_url }}" alt="Logo" style="height: 80px;">
                    @else
                        <div style="height: 80px; width: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #aaa;">LOGO</div>
                    @endif
                </div>
                <div class="header-text text-center">
                    <h1 class="school-name mb-0">{{ $student->school->name }}</h1>
                    <p class="mb-0"><b>CONSOLIDATED ACADEMIC PROGRESS REPORT</b></p>
                    <p class="small text-muted mb-0">Session 2024-25</p>
                </div>
                <div class="student-photo">
                    @if($student->photo)
                        <img src="{{ $student->photo_url }}" alt="Student" style="height: 80px; width: 80px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                    @else
                        <div style="height: 80px; width: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #aaa;">PHOTO</div>
                    @endif
                </div>
            </div>

            <div class="student-box">
                <div class="row">
                    <div class="col-4">
                        <p class="mb-1"><b>Student Name:</b> {{ $student->name }}</p>
                        <p class="mb-1"><b>Father's Name:</b> {{ $student->father_name ?: 'N/A' }}</p>
                        <p class="mb-1"><b>Mother's Name:</b> {{ $student->mother_name ?: 'N/A' }}</p>
                    </div>
                    <div class="col-4">
                        <p class="mb-1"><b>Roll Number:</b> {{ $student->roll_number }}</p>
                        <p class="mb-1"><b>Admission No:</b> {{ $student->id }}</p>
                        <p class="mb-1"><b>Date of Birth:</b> {{ $student->dob ? date('d M, Y', strtotime($student->dob)) : 'N/A' }}</p>
                    </div>
                    <div class="col-4 text-right">
                        <p class="mb-1"><b>Class:</b> {{ $student->grade->name ?? 'N/A' }}</p>
                        <p class="mb-1"><b>Section:</b> {{ $student->section->name ?? 'N/A' }}</p>
                        <p class="mb-1"><b>Report Date:</b> {{ date('d M, Y') }}</p>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 200px;">Subject</th>
                        @foreach($allExams as $exam)
                            <th colspan="2" class="text-center">{{ strtoupper($exam->name) }}</th>
                        @endforeach
                        <th colspan="2" class="bg-totals text-center">GRAND TOTAL</th>
                    </tr>
                    <tr>
                        @foreach($allExams as $exam)
                            <th class="text-center" style="width: 60px;">Obt.</th>
                            <th class="text-center" style="width: 60px;">Max.</th>
                        @endforeach
                        <th class="bg-totals text-center" style="width: 70px;">Total Obt.</th>
                        <th class="bg-totals text-center" style="width: 70px;">Total Max.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td class="font-weight-bold">{{ $subject->name }}</td>
                            @php 
                                $rowObtained = 0;
                                $rowMax = 0;
                            @endphp
                            @foreach($allExams as $exam)
                                @php 
                                    $mark = $marksMatrix[$subject->id][$exam->id] ?? null; 
                                    $obt = $mark ? $mark->marks_obtained : '-';
                                    $max = $mark ? ($mark->max_marks ?: 100) : '-';
                                    if(is_numeric($obt)) $rowObtained += $obt;
                                    if(is_numeric($max)) $rowMax += $max;
                                @endphp
                                <td class="text-center {{ $obt === '-' ? 'text-muted' : '' }} font-weight-bold">{{ $obt }}</td>
                                <td class="text-center {{ $max === '-' ? 'text-muted' : '' }}">{{ $max }}</td>
                            @endforeach
                            <!-- Subject Wise Grand Total -->
                            <td class="text-center bg-totals text-primary" style="font-size: 14px;">{{ $rowObtained }}</td>
                            <td class="text-center bg-totals">{{ $rowMax }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-totals">
                        <td class="text-right">EXAM TOTALS</td>
                        @foreach($allExams as $exam)
                            <td class="text-center text-primary">{{ $examSummaries[$exam->id]['total_obtained'] }}</td>
                            <td class="text-center">{{ $examSummaries[$exam->id]['total_max'] }}</td>
                        @endforeach
                        <td class="text-center text-primary" style="font-size: 15px;">{{ $grandTotalObtained }}</td>
                        <td class="text-center" style="font-size: 15px;">{{ $grandTotalMax }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Summary Bar -->
            <div class="summary-bar shadow-sm align-items-center">
                <div class="flex-grow-1 d-flex justify-content-around align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase mr-2">Overall Position:</span>
                        <span class="text-danger font-weight-bold h5 mb-0">{{ $overallPosition }}</span>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase mr-2">Overall Percentage:</span>
                        <span class="text-primary h5 mb-0 font-weight-bold">{{ number_format($overallPercentage, 2) }}%</span>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase mr-2">Final Status:</span>
                        <span class="{{ $overallPercentage >= 40 ? 'text-success' : 'text-danger' }} font-weight-bold h5 mb-0 text-uppercase">
                            {{ $overallPercentage >= 40 ? 'PROMOTED' : 'DETAINED' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="sig-box">
                    @if($student->grade && $student->grade->teacher && $student->grade->teacher->signature)
                        <img src="{{ $student->grade->teacher->signature_url }}" class="digital-sig" alt="Teacher Signature">
                    @endif
                    <div class="mt-2">{{ $student->grade->teacher->name ?? 'Class Teacher Signature' }}</div>
                    @if($student->grade && $student->grade->teacher)
                        <div class="small text-muted" style="font-size: 10px; font-weight: 400;">Class Teacher</div>
                    @endif
                </div>
                <div class="sig-box">Parent Signature</div>
                <div class="sig-box">
                    @if($student->school && $student->school->principal_signature)
                        <img src="{{ $student->school->signature_url }}" class="digital-sig" alt="Principal Signature">
                    @endif
                    Principal Signature
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
