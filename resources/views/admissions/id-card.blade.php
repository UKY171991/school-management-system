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
            --school-gold-light: #e6c88f;
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
            color: white;
            text-align: center;
            padding: 15px 20px 10px;
            position: relative;
            border-bottom: 4px solid var(--school-gold);
        }

        .school-info {
            position: relative;
            z-index: 2;
            padding-right: 75px; /* Reserve space for the badge */
            text-align: center;
        }

        .school-info .school-name {
            font-size: 1.4rem;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
            color: var(--school-gold-light);
            line-height: 1.1;
        }

        .school-info .location {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 3px;
            font-weight: 500;
            line-height: 1.2;
        }

        .school-info .contact {
            font-size: 0.75rem;
            opacity: 0.9;
            margin-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .contact-item + .contact-item {
            border-left: 1px solid rgba(255,255,255,0.3);
            padding-left: 12px;
        }

        .school-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 60px;
            height: 70px;
            background: #fff;
            padding: 5px;
            border-radius: 4px;
            clip-path: polygon(0% 0%, 100% 0%, 100% 85%, 50% 100%, 0% 85%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--school-gold);
            z-index: 10;
        }

        .school-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
            padding: 4px;
            background: linear-gradient(135deg, var(--school-gold-light) 0%, var(--school-gold) 100%);
            border-radius: 50%;
            width: 110px;
            height: 110px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .student-photo {
            width: 102px;
            height: 102px;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
            background: #f8f9fa;
        }

        .student-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--school-blue);
            margin: 10px 0 5px;
            letter-spacing: -0.5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .info-label {
            color: var(--school-gold);
            font-weight: 700;
            width: 110px;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            font-weight: 600;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-code-img {
            width: 120px;
            height: 120px;
            border: 1px solid #eee;
            padding: 5px;
            background: #fff;
        }

        .qr-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: #666;
            margin-top: 5px;
            text-transform: uppercase;
        }

        /* Footer Style */
        .card-footer {
            background: var(--school-blue);
            color: white;
            padding: 10px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 700;
            border-top: 4px solid var(--school-gold);
            margin-top: auto;
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
            height: 350px;
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
                <div class="row align-items-center justify-content-center mb-3">
                    <div class="col-6 d-flex justify-content-center">
                        <div class="student-photo-wrapper">
                            @if($student->photo_url)
                                <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="student-photo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=f0f2f5&color=0d244f&size=200" alt="{{ $student->name }}" class="student-photo">
                            @endif
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="qr-section">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=student:{{ $student->id }}" class="qr-code-img" style="width: 110px; height: 110px;" alt="QR Code">
                            <div class="qr-label">Scan for attendance</div>
                        </div>
                    </div>
                </div>

                <h1 class="student-name">{{ $student->name }}</h1>

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
                        <span class="contact-item"><i class="fa fa-phone"></i> {{ $student->school->phone ?? '+91 9711447614' }}</span>
                        <span class="contact-separator">|</span>
                        <span class="contact-item"><i class="fa fa-envelope"></i> {{ $student->school->email ?? 'info@thewebbrain.in' }}</span>
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
                    <div class="student-photo-wrapper">
                        @if($student->photo_url)
                            <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="student-photo">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=f0f2f5&color=0d244f&size=200" alt="{{ $student->name }}" class="student-photo">
                        @endif
                    </div>
                </div>

                <div class="h-info-col">
                    <h1 class="student-name h-student-name">{{ $student->name }}</h1>
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
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=student:{{ $student->id }}" class="qr-code-img" style="width: 150px; height: 150px;" alt="QR Code">
                        <div class="qr-label">Scan for attendance</div>
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

