<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card - {{ $student->name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        @media print {
            body { background: white; }
            .no-print { display: none; }
            .card-wrapper { box-shadow: none !important; margin: 0 !important; }
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .card-wrapper {
            width: 380px;
            height: 580px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            position: relative;
            border: 1px solid #eee;
        }

        .card-header {
            background: linear-gradient(135deg, #1a237e 0%, #303f9f 100%);
            height: 160px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            text-align: center;
            border-bottom: 5px solid #ff9800;
            position: relative;
        }

        .school-name {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            width: 100%;
        }

        .school-address {
            font-size: 10px;
            opacity: 0.8;
            font-weight: 300;
        }

        .photo-container {
            width: 140px;
            height: 140px;
            margin: -70px auto 15px;
            position: relative;
            z-index: 5;
            border: 6px solid #fff;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background: #f8f9fa;
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-info {
            padding: 0 30px 10px;
            text-align: center;
        }

        .student-name {
            font-size: 22px;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 4px;
        }

        .class-badge {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            background: #1a237e;
            padding: 4px 15px;
            display: inline-block;
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .details-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #e0e0e0;
            padding: 6px 5px;
        }

        .detail-label {
            font-size: 11px;
            color: #777;
            font-weight: 700;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label i {
            color: #1a237e;
            width: 14px;
            text-align: center;
        }

        .detail-value {
            font-size: 12px;
            color: #1a237e;
            font-weight: 700;
        }

        .card-footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0 30px 25px;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
            border: 1px solid #eee;
        }

        .signature-box {
            text-align: center;
        }

        .sig-label {
            font-size: 9px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            border-top: 1px solid #ddd;
            padding-top: 3px;
            width: 80px;
        }

        .watermark {
            position: absolute;
            bottom: -15px;
            right: -15px;
            font-size: 80px;
            opacity: 0.03;
            font-weight: 900;
            color: #1a237e;
            z-index: 1;
            pointer-events: none;
        }

        .id-strip {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 8px;
            background: #1a237e;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary shadow-lg px-4 py-2 rounded-pill">
            <i class="fas fa-print mr-2"></i>Print ID Card
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary ml-2 rounded-pill">
            Close
        </button>
    </div>

    <div class="card-wrapper">
        <div class="card-header">
            <h2 class="school-name">{{ $student->school->name ?? 'SCHOOL MANAGEMENT SYSTEM' }}</h2>
            <div class="school-address text-truncate px-3">{{ $student->school->address ?? 'Main Campus, Academic Square' }}</div>
        </div>

        <div class="photo-container">
            @if($student->photo_url)
                <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="profile-photo">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=f0f2f5&color=1a237e&size=150" alt="{{ $student->name }}" class="profile-photo">
            @endif
        </div>

        <div class="student-info">
            <h1 class="student-name">{{ $student->name }}</h1>
            <div class="class-badge">{{ $student->grade->name ?? 'CLASS' }}{{ $student->section ? ' - ' . $student->section->name : '' }}</div>

            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-id-card"></i>Reg #</span>
                    <span class="detail-value">{{ $student->registration_number ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-hashtag"></i>Roll Number</span>
                    <span class="detail-value">{{ $student->roll_number }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-alt"></i>Session</span>
                    <span class="detail-value">{{ $student->session_year }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-birthday-cake"></i>Date of Birth</span>
                    <span class="detail-value">{{ $student->dob }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-user-friends"></i>Father Name</span>
                    <span class="detail-value text-truncate" style="max-width: 140px;">{{ $student->father_name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="signature-box w-100">
                <div style="height: 35px;"></div>
                <div class="sig-label mx-auto">Principal Sign</div>
            </div>
        </div>

        <div class="watermark">STUDENT</div>
        <div class="id-strip"></div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
