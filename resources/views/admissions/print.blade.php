<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->exists ? 'Admission Form - ' . $student->name : 'Blank Admission Form' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
        }

        .no-print {
            margin: 20px auto;
            max-width: 900px;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }

            .form-container {
                border: none !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: none !important;
                box-shadow: none !important;
            }
        }

        .form-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 50px;
            border: 1px solid #dee2e6;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 8px;
        }

        .header-container {
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .school-name {
            font-size: 28px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            color: #1a237e;
        }

        .form-title {
            text-align: center;
            background: #1a237e;
            color: #fff;
            padding: 5px;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .field-row {
            margin-bottom: 15px;
            display: flex;
            align-items: baseline;
        }

        .field-label {
            font-weight: 700;
            min-width: 180px;
            font-size: 15px;
        }

        .field-value {
            border-bottom: 1px dotted #333;
            flex-grow: 1;
            padding: 0 5px;
            font-size: 16px;
            min-height: 24px;
        }

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

        .photo-box img {
            max-width: 100%;
            max-height: 100%;
        }

        .section-title {
            font-weight: 800;
            text-decoration: underline;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 17px;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .sig-box {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            font-weight: 700;
        }
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
                @if($student->school && $student->school->logo_url)
                    <img src="{{ $student->school->logo_url }}" alt="Logo" style="max-width: 100px; max-height: 100px;">
                @endif
            </div>

            <div class="header text-center flex-grow-1 border-0 mb-0 pb-0">
                <h1 class="school-name">{{ $student->school->name ?? 'School Management System' }}</h1>
                <p class="mb-0">{{ $student->school->address ?? 'Main Campus, Academic Square' }}</p>
                <p class="mb-0">Contact: {{ $student->school->phone ?? 'XXXX-XXXXXX' }}</p>
                @if($student->school && $student->school->email)
                    <p class="mb-0">Email: {{ $student->school->email }}</p>
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
        <div class="d-flex justify-content-between mb-3 px-1" style="padding: 5px 0;">
            <div style="font-size: 16px; font-weight: 700;">Registration No: <span style="font-weight: 600; color: #d32f2f; margin-left: 5px;">{{ $student->registration_number }}</span></div>
            <div style="font-size: 16px; font-weight: 700;">Session Year: <span style="font-weight: 600; color: #1a237e; margin-left: 5px;">{{ $student->session_year }}</span></div>
        </div>

        <div class="form-title" style="margin-top: 10px; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">ADMISSION REGISTRATION FORM</div>

        <div class="section-title">STUDENT DETAILS</div>

        <div class="row">
            <div class="col-12">
                <div class="field-row">
                    <span class="field-label">Student Name:</span>
                    <span class="field-value">{{ $student->name }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <div class="field-row">
                    <span class="field-label" style="min-width: 60px;">Gender:</span>
                    <span class="field-value">{{ $student->gender }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="field-row">
                    <span class="field-label" style="min-width: 50px;">Caste:</span>
                    <span class="field-value">{{ $student->caste }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="field-row">
                    <span class="field-label" style="min-width: 50px;">Class:</span>
                    <span class="field-value">{{ $student->grade->name ?? '' }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="field-row">
                    <span class="field-label" style="min-width: 60px;">Section:</span>
                    <span class="field-value">{{ $student->section->name ?? '' }}</span>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-4">
                <div class="field-row">
                    <span class="field-label" style="min-width: 100px;">Date of Birth:</span>
                    <span class="field-value">{{ $student->dob ? date('d-m-Y', strtotime($student->dob)) : '' }}</span>
                </div>
            </div>
            <div class="col-4">
                <div class="field-row">
                    <span class="field-label" style="min-width: 120px;">Admission Date:</span>
                    <span
                        class="field-value">{{ $student->admission_date ? date('d-m-Y', strtotime($student->admission_date)) : '' }}</span>
                </div>
            </div>
            <div class="col-4">
                <div class="field-row">
                    <span class="field-label" style="min-width: 60px;">Email:</span>
                    <span class="field-value">{{ $student->email }}</span>
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
                <div class="field-row">
                    <span class="field-label">Permanent Address:</span>
                    <span class="field-value">{{ $student->address }}</span>
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