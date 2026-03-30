<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card - {{ $student->name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --school-blue: #0d244f;
            --school-gold: #c5a059;
            --school-gold-light: #f1dca7;
            --info-label: #8c6a2c;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* --- UI Controls --- */
        .controls {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            gap: 15px;
            z-index: 100;
        }

        .btn-check:checked + .btn-outline-primary {
            background-color: var(--school-blue);
            border-color: var(--school-blue);
        }

        .btn-print {
            background-color: var(--school-blue);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* --- Card Styles --- */
        .card-container {
            display: none;
        }

        .card-container.active {
            display: block;
        }

        .id-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            position: relative;
            border: 2px solid var(--school-blue);
        }

        /* Header Style */
        .card-header {
            background: var(--school-blue);
            background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.05) 0%, transparent 40%),
                              linear-gradient(135deg, var(--school-blue) 0%, #1a3a7a 100%);
            color: white;
            text-align: center;
            padding: 15px 20px 12px;
            position: relative;
            border-bottom: 3px solid var(--school-gold);
        }

        .school-info {
            position: relative;
            z-index: 2;
            padding-right: 85px; 
            text-align: center;
        }

        .school-info .school-name {
            font-size: 1.5rem;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
            color: var(--school-gold-light);
            line-height: 1.1;
        }

        .school-info .location {
            font-size: 0.85rem;
            opacity: 0.95;
            margin-top: 3px;
            font-weight: 500;
            line-height: 1.2;
            color: #fff;
        }

        .school-info .contact {
            font-size: 0.75rem;
            opacity: 1;
            margin-top: 10px;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            color: #fff;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .contact-item i {
            color: #fff;
            font-size: 0.8rem;
        }

        .contact-sep {
            opacity: 0.6;
            margin: 0 5px;
        }

        .school-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .school-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        /* Content Sections */
        .details-section {
            padding: 15px 20px;
        }

        .student-photo-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
            background: linear-gradient(135deg, var(--school-gold-light) 0%, var(--school-gold) 100%);
            border-radius: 50%;
            width: 140px;
            height: 140px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            border: 2px solid #fff;
        }

        .student-photo {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
            background: #fff;
        }

        .student-name {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--school-blue);
            margin: 10px 0 2px;
            letter-spacing: -0.5px;
        }

        .student-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--school-blue);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: block;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 0.95rem;
            line-height: 1.2;
            border-bottom: 1px dotted #eee;
            padding-bottom: 4px;
        }

        .info-label {
            color: var(--info-label);
            font-weight: 700;
            width: 120px;
            flex-shrink: 0;
        }

        .info-value {
            color: var(--school-blue);
            font-weight: 700;
        }

        .qr-section {
            border: 1px solid #f0f0f0;
            padding: 10px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .qr-code-img {
            width: 120px;
            height: 120px;
            padding: 5px;
        }

        .qr-label {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--school-blue);
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #eee;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer Style */
        .card-footer {
            background: var(--school-blue);
            background-image: linear-gradient(to right, var(--school-blue), #1a3a7a);
            color: white;
            padding: 10px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 700;
            border-top: 4px solid var(--school-gold);
            margin-top: auto;
            position: relative;
            z-index: 5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Vertical Card Specific */
        .vertical-card {
            width: 330px;
            height: 520px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .v-body {
            flex: 1;
            padding: 10px 15px;
            text-align: center;
        }

        .v-info-list {
            text-align: left;
            padding: 0 10px;
            margin-top: 15px;
        }

        .v-qr-code-img {
            width: 110px;
            height: 110px;
            margin-bottom: 5px;
        }

        .v-qr-code-img {
            width: 130px;
            height: 130px;
        }

        /* Horizontal Card Specific */
        .horizontal-card {
            width: 530px;
            height: 380px;
            display: flex;
            flex-direction: column;
        }

        .h-body {
            display: flex;
            padding: 15px;
            align-items: center;
            flex: 1;
        }

        .h-photo-col {
            width: 120px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
        }

        .h-info-col {
            flex: 1;
            padding: 0 10px;
            border-left: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .h-qr-col {
            width: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-left: 1px solid #f0f0f0;
        }

        .h-info-row .info-label { width: 90px; }

        .h-student-name {
            margin: 0 0 10px 0;
            text-align: left;
        }

        /* Print Styles */
        @media print {
            body { background: white; padding: 0; margin: 0; }
            .controls { display: none !important; }
            .card-container:not(.active) { display: none !important; }
            .id-card { 
                box-shadow: none !important; 
                margin: 0 auto;
                border: 2px solid var(--school-blue) !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="controls no-print">
        <div class="btn-group" role="group">
            <input type="radio" class="btn-check" name="layout" id="v-radio" checked onclick="switchTab('vertical')">
            <label class="btn btn-outline-primary" for="v-radio">Vertical Layout</label>

            <input type="radio" class="btn-check" name="layout" id="h-radio" onclick="switchTab('horizontal')">
            <label class="btn btn-outline-primary" for="h-radio">Horizontal Layout</label>
        </div>
        
        <button onclick="window.print()" class="btn-print">
            <i class="fa fa-print"></i> Print ID Card
        </button>
        
        <button onclick="window.close()" class="btn btn-outline-secondary">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <!-- Vertical Card -->
    <div id="vertical-container" class="card-container active">
        <div class="id-card vertical-card">
            <div class="card-header">
                <div class="school-info">
                    <h2 class="school-name">{{ $student->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                    <div class="location">{{ $student->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                    <div class="contact">
                        <span class="contact-item">
                            <i class="fa fa-phone"></i> {{ $student->school->phone ?? '+91 9711447614' }}
                        </span>
                        <span class="contact-sep">|</span>
                        <span class="contact-item">
                            <i class="fa fa-envelope"></i> {{ $student->school->email ?? 'info@thewebbrain.in' }}
                        </span>
                    </div>
                </div>
                <div class="school-badge">
                    @if($student->school->logo_url)
                        <img src="{{ $student->school->logo_url }}" alt="Logo">
                    @else
                        <i class="fa fa-graduation-cap text-primary"></i>
                    @endif
                </div>
            </div>

            <div class="v-body">
                <div class="row g-0 align-items-center justify-content-between mb-3 px-3">
                    <div class="col-auto">
                        <div class="student-photo-wrapper">
                            @if($student->photo_url)
                                <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="student-photo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=f0f2f5&color=0d244f&size=200" alt="{{ $student->name }}" class="student-photo">
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="qr-section">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=student:{{ $student->id }}" class="qr-code-img" alt="QR Code">
                            <div class="qr-label">SCAN FOR ATTENDANCE</div>
                        </div>
                    </div>
                </div>

                <h1 class="student-name">{{ $student->name }}</h1>
                <span class="student-label">Student</span>

                <div class="v-info-list">
                    <div class="info-row">
                        <span class="info-label">Class :</span>
                        <span class="info-value">{{ $student->grade->name ?? 'N/A' }}{{ $student->section ? ' - ' . $student->section->name : '' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth :</span>
                        <span class="info-value">{{ $student->dob }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone :</span>
                        <span class="info-value">{{ $student->father_phone ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address :</span>
                        <span class="info-value">{{ $student->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <span>SESSION: {{ $student->session_year ?? date('Y') . '-' . (date('y')+1) }}</span>
                <span>ID No: {{ $student->roll_number ?? str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <!-- Horizontal Card -->
    <div id="horizontal-container" class="card-container">
        <div class="id-card horizontal-card">
            <div class="card-header">
                <div class="school-info">
                    <h2 class="school-name">{{ $student->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                    <div class="location">{{ $student->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                    <div class="contact">
                        <span class="contact-item">
                            <i class="fa fa-phone"></i> {{ $student->school->phone ?? '+91 9711447614' }}
                        </span>
                        <span class="contact-sep">|</span>
                        <span class="contact-item">
                            <i class="fa fa-envelope"></i> {{ $student->school->email ?? 'info@thewebbrain.in' }}
                        </span>
                    </div>
                </div>
                <div class="school-badge">
                    @if($student->school->logo_url)
                        <img src="{{ $student->school->logo_url }}" alt="Logo">
                    @else
                        <i class="fa fa-graduation-cap text-primary"></i>
                    @endif
                </div>
            </div>

            <div class="h-body">
                <div class="h-photo-col">
                    <div class="student-photo-wrapper" style="width: 130px; height: 130px;">
                        @if($student->photo_url)
                            <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="student-photo" style="width: 120px; height: 120px;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=f0f2f5&color=0d244f&size=200" alt="{{ $student->name }}" class="student-photo" style="width: 120px; height: 120px;">
                        @endif
                    </div>
                </div>

                <div class="h-info-col">
                    <div class="text-center mb-3">
                        <h1 class="student-name h-student-name" style="margin-bottom: 0;">{{ $student->name }}</h1>
                        <span class="student-label" style="font-size: 0.8rem; margin-bottom: 5px;">Student</span>
                        <div style="width: 100%; border-bottom: 1px solid #eee;"></div>
                    </div>
                    <div class="h-info-row info-row">
                        <span class="info-label">Class :</span>
                        <span class="info-value">{{ $student->grade->name ?? 'N/A' }}</span>
                    </div>
                    <div class="h-info-row info-row">
                        <span class="info-label">Date of Birth :</span>
                        <span class="info-value">{{ $student->dob }}</span>
                    </div>
                    <div class="h-info-row info-row">
                        <span class="info-label">Father's Name :</span>
                        <span class="info-value text-truncate" style="max-width: 150px;">{{ $student->father_name ?? 'N/A' }}</span>
                    </div>
                    <div class="h-info-row info-row">
                        <span class="info-label">Phone :</span>
                        <span class="info-value">{{ $student->father_phone ?? 'N/A' }}</span>
                    </div>
                    <div class="h-info-row info-row">
                        <span class="info-label">Address :</span>
                        <span class="info-value text-truncate" style="max-width: 150px;">{{ $student->address ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="h-qr-col">
                    <div class="qr-section">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=student:{{ $student->id }}" class="qr-code-img" alt="QR Code">
                        <div class="qr-label">SCAN FOR ATTENDANCE</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <span>SESSION: {{ $student->session_year ?? date('Y') . '-' . (date('y')+1) }}</span>
                <span>ID No: {{ $student->roll_number ?? str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <script>
        function switchTab(type) {
            document.querySelectorAll('.card-container').forEach(c => c.classList.remove('active'));
            document.getElementById(type + '-container').classList.add('active');
        }
    </script>
</body>
</html>

