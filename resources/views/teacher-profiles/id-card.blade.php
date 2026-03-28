<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher ID Card - {{ $teacher->name }}</title>
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
            height: 180px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            text-align: center;
            border-bottom: 5px solid #ffca28;
            position: relative;
        }

        .school-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            padding: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .school-name {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .school-address {
            font-size: 10px;
            opacity: 0.8;
            font-weight: 300;
        }

        .photo-container {
            width: 150px;
            height: 150px;
            margin: -75px auto 15px;
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

        .teacher-info {
            padding: 0 30px 20px;
            text-align: center;
        }

        .teacher-name {
            font-size: 24px;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 5px;
        }

        .designation {
            font-size: 14px;
            font-weight: 600;
            color: #ffca28;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            background: #1a237e;
            padding: 4px 15px;
            display: inline-block;
            border-radius: 20px;
        }

        .details-grid {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #e0e0e0;
            padding: 8px 5px;
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
            font-size: 13px;
            color: #1a237e;
            font-weight: 700;
        }

        .card-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0 30px 30px;
        }

        .qr-code {
            width: 70px;
            height: 70px;
            background: #f8f9fa;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #ccc;
        }

        .signature-box {
            text-align: center;
        }

        .signature-img {
            max-width: 100px;
            max-height: 40px;
            margin-bottom: 5px;
        }

        .sig-label {
            font-size: 10px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            border-top: 1px solid #ddd;
            padding-top: 3px;
        }

        .watermark {
            position: absolute;
            bottom: -20px;
            right: -20px;
            font-size: 100px;
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
            height: 10px;
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
            <h2 class="school-name">{{ $teacher->school->name ?? 'SCHOOL MANAGEMENT SYSTEM' }}</h2>
            <div class="school-address text-truncate px-3">{{ $teacher->school->address ?? 'Main Campus, Academic Square' }}</div>
        </div>

        <div class="photo-container">
            @if($teacher->photo_url)
                <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="profile-photo">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=1a237e&size=150" alt="{{ $teacher->name }}" class="profile-photo">
            @endif
        </div>

        <div class="teacher-info">
            <h1 class="teacher-name">{{ $teacher->name }}</h1>
            <div class="designation">{{ $teacher->specialization ?? 'FACULTY MEMBER' }}</div>

            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-id-badge"></i>Employee ID</span>
                    <span class="detail-value">EMP-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-phone"></i>Phone Number</span>
                    <span class="detail-value">{{ $teacher->phone ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-envelope"></i>Email Address</span>
                    <span class="detail-value text-truncate" style="max-width: 150px;">{{ $teacher->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-alt"></i>Join Date</span>
                    <span class="detail-value">{{ $teacher->created_at->format('d-M, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="qr-code">
                <!-- QR Placeholder -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=EMP-{{ $teacher->id }}" alt="QR" style="width: 100%; height: 100%;">
            </div>
            <div class="signature-box">
                @if($teacher->signature_url)
                    <img src="{{ $teacher->signature_url }}" alt="Signature" class="signature-img">
                @else
                    <div style="height: 40px; margin-bottom: 5px; opacity: 0.3; font-size: 10px; padding-top: 15px;">Pending Sign</div>
                @endif
                <div class="sig-label">Authorized Sign</div>
            </div>
        </div>

        <div class="watermark">TEACHER</div>
        <div class="id-strip"></div>
    </div>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
