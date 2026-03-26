<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->exists ? 'Admission Form - ' . $student->name : 'Blank Admission Form' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #fff; color: #333; }
        .no-print { margin: 20px auto; max-width: 900px; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
            .form-container { border: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; }
        }
        .form-container { 
            max-width: 900px; 
            margin: 20px auto; 
            padding: 40px; 
            border: 2px solid #333;
            background: #fff;
        }
        .header-container {
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .school-name { font-size: 28px; font-weight: 800; text-transform: uppercase; margin: 0; color: #1a237e; }
        .form-title { 
            text-align: center; 
            background: #1a237e; 
            color: #fff; 
            padding: 5px; 
            font-size: 18px; 
            font-weight: 700; 
            margin-bottom: 20px;
        }
        .field-row { margin-bottom: 15px; display: flex; align-items: baseline; }
        .field-label { font-weight: 700; min-width: 180px; font-size: 15px; }
        .field-value { border-bottom: 1px dotted #333; flex-grow: 1; padding: 0 5px; font-size: 16px; min-height: 24px; }
        .photo-box { 
            width: 120px; 
            height: 140px; 
            border: 2px solid #333; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 12px; 
            text-align: center;
        }
        .photo-box img { max-width: 100%; max-height: 100%; }
        .section-title { font-weight: 800; text-decoration: underline; margin-top: 20px; margin-bottom: 10px; font-size: 17px; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; }
        .sig-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 5px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg px-5">Print Form</button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg ml-2">Close</button>
    </div>

    <div class="form-container">
        <div class="header-container d-flex justify-content-between align-items-start mb-4">
            <div class="logo-box" style="width: 120px;">
                @php $activeSchool = $student->school ?? ($school ?? null); @endphp
                @if($activeSchool && $activeSchool->logo_url)
                    <img src="{{ $activeSchool->logo_url }}" alt="Logo" style="max-width: 100px; max-height: 100px;">
                @endif
            </div>

            <div class="header text-center flex-grow-1 border-0 mb-0 pb-0">
                <h1 class="school-name">{{ ($student->school ?? ($school ?? null))->name ?? 'School Management System' }}</h1>
                <p class="mb-0">{{ ($student->school ?? ($school ?? null))->address ?? 'Main Campus, Academic Square' }}</p>
                <p class="mb-0">Contact: {{ ($student->school ?? ($school ?? null))->phone ?? 'XXXX-XXXXXX' }}</p>
                @if(isset($student->school) || isset($school))
                    @php $activeSchool = $student->school ?? $school; @endphp
                    @if($activeSchool && $activeSchool->email)
                        <p class="mb-0">Email: {{ $activeSchool->email }}</p>
                    @endif
                @endif
            </div>

            <div class="photo-box position-static">
                @if($student->photo)
                    <img src="{{ $student->photo_url }}" alt="Student">
                @else
                    Paste Passport Size Photo
                @endif
            </div>
        </div>

        <div class="form-title">ADMISSION REGISTRATION FORM</div>

        <div class="section-title">STUDENT DETAILS</div>
        
        <div class="field-row" style="margin-right: 150px;">
            <span class="field-label">Student Name:</span>
            <span class="field-value">{{ $student->name }}</span>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Gender:</span>
                    <span class="field-value">{{ $student->gender }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Roll Number:</span>
                    <span class="field-value">{{ $student->roll_number }}</span>
                    <div class="ml-2 small font-italic text-muted" style="min-width: 120px; white-space: nowrap;">(Only for Office Use)</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Date of Birth:</span>
                    <span class="field-value">{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '' }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Admission Date:</span>
                    <span class="field-value">{{ $student->admission_date ? date('d-m-Y', strtotime($student->admission_date)) : '' }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Caste:</span>
                    <span class="field-value">{{ $student->caste }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Email:</span>
                    <span class="field-value">{{ $student->email }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Class:</span>
                    <span class="field-value">{{ $student->grade->name ?? '' }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Section:</span>
                    <span class="field-value">{{ $student->section->name ?? '' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">PARENT DETAILS</div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Father's Name:</span>
                    <span class="field-value">{{ $student->father_name }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Father's Mobile:</span>
                    <span class="field-value">{{ $student->father_phone }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Mother's Name:</span>
                    <span class="field-value">{{ $student->mother_name }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Mother's Mobile:</span>
                    <span class="field-value">{{ $student->mother_phone }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">OTHER INFORMATION</div>

        <div class="field-row">
            <span class="field-label">Previous School:</span>
            <span class="field-value">{{ $student->previous_school }}</span>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Adhaar Number:</span>
                    <span class="field-value">{{ $student->adhaar_number }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="field-row">
                    <span class="field-label">Apaar ID:</span>
                    <span class="field-value">{{ $student->apaar_id }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="sig-box">Guardian Signature</div>
            <div class="sig-box">Clerk Signature</div>
            <div class="sig-box">Principal Signature</div>
        </div>
    </div>
</body>
</html>
