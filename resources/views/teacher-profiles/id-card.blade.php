<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher ID Card - {{ $teacher->name }}</title>
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
            background: #eef2f7;
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

        .nav-pills .nav-link {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: var(--school-blue);
            color: #fff;
        }

        .btn-print {
            background-color: var(--school-blue);
            color: white;
            border: none;
            padding: 8px 25px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            background-color: #000;
            color: #fff;
        }

        /* --- Card Styles --- */
        .card-container {
            display: none;
            perspective: 1000px;
        }

        .card-container.active {
            display: block;
        }

        /* Common Elements */
        .id-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            position: relative;
            border: 1px solid #ddd;
        }

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

        .shield-logo {
            position: absolute;
            top: 20px;
            right: 15px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .shield-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
        }

        /* Watermarks */
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
            text-align: left;
        }

        .info-value {
            color: var(--school-blue);
            font-weight: 700;
            text-align: left;
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

        .school-info {
            position: relative;
            z-index: 2;
        }
        .school-info .school-name {
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .school-info .location {
            font-size: 0.85rem;
            opacity: 0.95;
            margin-top: 4px;
            font-weight: 600;
        }

        .school-info .contact {
            font-size: 0.68rem;
            opacity: 0.9;
            margin-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            gap: 8px;
            font-weight: 500;
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

        .teacher-photo {
            border: 1px solid #ddd;
            padding: 2px;
            background: #f8f9fa;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .designation-badge {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin-top: 15px;
            margin-bottom: 2px;
            text-transform: capitalize;
        }

        .id-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: #000;
            margin-bottom: 15px;
        }

        .info-grid {
            text-align: left;
            padding: 0 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #000;
            font-weight: 700;
        }

        .footer-banner {
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

        /* Vertical Specific */
        .vertical-card {
            width: 330px;
            height: 520px;
        }

        .v-photo-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px 25px 0;
            gap: 15px;
        }

        .v-teacher-photo {
            width: 100px;
            height: 110px;
        }

        .v-qr-code {
            width: 110px;
            height: 110px;
        }

        .v-body {
            text-align: center;
        }

        /* Horizontal Specific */
        .horizontal-card {
            width: 500px;
            height: 360px;
            display: flex;
            flex-direction: column;
        }

        .h-layout {
            display: flex;
            height: calc(100% - 30px); /* Adjust for footer */
        }

        .h-left {
            width: 130px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            border-right: 1px solid #f0f0f0;
            align-items: flex-start;
        }

        .h-right {
            flex: 1;
            position: relative;
        }

        .h-header {
            padding-right: 70px; /* Room for logo */
            text-align: left;
        }

        .h-teacher-photo {
            width: 100px;
            height: 110px;
        }

        .h-qr-code {
            width: 80px;
            height: 80px;
        }

        .h-body {
            padding: 10px 20px;
            text-align: left;
        }

        .h-name-group {
            margin-bottom: 10px;
        }

        .h-designation {
            font-size: 1rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .h-name {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
        }

        .h-info-grid {
            padding: 0;
        }

        /* Print Styles */
        @media print {
            body { 
                background: white; 
                padding: 0;
                margin: 0;
            }
            .controls { display: none !important; }
            .card-container:not(.active) { display: none !important; }
            .id-card { 
                box-shadow: none !important; 
                border: 1px solid #ddd !important;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

    <div class="controls no-print">
        <div class="nav nav-pills" role="tablist">
            <button class="nav-link active" onclick="switchTab('vertical')">Vertical Card</button>
            <button class="nav-link" onclick="switchTab('horizontal')">Horizontal Card</button>
        </div>
        <button onclick="window.print()" class="btn-print">
            <i class="fa fa-print"></i> Print Card
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
                    <h2 class="school-name">{{ $teacher->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                    <div class="location">{{ $teacher->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                    <div class="contact">
                        <span class="contact-item">
                            <i class="fa fa-phone"></i> {{ $teacher->school->phone ?? '9711447614' }}
                        </span>
                        <span class="contact-sep">|</span>
                        <span class="contact-item">
                            <i class="fa fa-envelope"></i> {{ $teacher->school->email ?? 'info@thewebbrain.in' }}
                        </span>
                    </div>
                </div>
                <div class="school-badge">
                    @if(isset($teacher->school->logo_url))
                        <img src="{{ $teacher->school->logo_url }}" alt="Logo">
                    @else
                         <i class="fa fa-university fa-2x text-white"></i>
                    @endif
                </div>
            </div>

            <div class="v-body" style="flex: 1; padding: 10px 15px; text-align: center;">
                <div class="row g-0 align-items-center justify-content-between mb-3 px-3">
                    <div class="col-auto">
                        <div class="student-photo-wrapper">
                            @if($teacher->photo_url)
                                <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="student-photo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=0d244f&size=200" alt="{{ $teacher->name }}" class="student-photo">
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="qr-section">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=teacher:{{ $teacher->id }}" class="qr-code-img" alt="QR Code">
                            <div class="qr-label">SCAN FOR ATTENDANCE</div>
                        </div>
                    </div>
                </div>

                <h1 class="student-name">{{ $teacher->name }}</h1>
                <span class="student-label">Teacher</span>

                <div class="v-info-list" style="text-align: left; padding: 0 10px; margin-top: 15px;">
                    <div class="info-row">
                        <span class="info-label">Employee ID :</span>
                        <span class="info-value">EMP-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone :</span>
                        <span class="info-value">{{ $teacher->phone ?? '+91 XXXXXXXXX' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email :</span>
                        <span class="info-value" style="font-size: 0.8rem;">{{ $teacher->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Join Date :</span>
                        <span class="info-value">{{ $teacher->created_at ? $teacher->created_at->format('d-M, Y') : date('d-M, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="footer-banner">
                <span>SESSION: {{ date('Y') . '-' . (date('y')+1) }}</span>
                <span>ID No: {{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <!-- Horizontal Card -->
    <div id="horizontal-container" class="card-container">
        <div class="id-card horizontal-card">
            <div class="card-header">
                <div class="school-info" style="padding-right: 85px;">
                    <h2 class="school-name">{{ $teacher->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                    <div class="location">{{ $teacher->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                    <div class="contact">
                        <span class="contact-item">
                            <i class="fa fa-phone"></i> {{ $teacher->school->phone ?? '9711447614' }}
                        </span>
                        <span class="contact-sep">|</span>
                        <span class="contact-item">
                            <i class="fa fa-envelope"></i> {{ $teacher->school->email ?? 'info@thewebbrain.in' }}
                        </span>
                    </div>
                </div>
                <div class="shield-logo">
                    @if(isset($teacher->school->logo_url))
                        <img src="{{ $teacher->school->logo_url }}" alt="Logo">
                    @else
                          <i class="fa fa-university fa-2x text-white"></i>
                    @endif
                </div>
            </div>

            <div class="h-body">
                <div class="h-photo-col" style="width: 140px;">
                    <div class="student-photo-wrapper" style="width: 120px; height: 120px;">
                        @if($teacher->photo_url)
                            <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="student-photo" style="width: 112px; height: 112px;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=0d244f&size=200" alt="{{ $teacher->name }}" class="student-photo" style="width: 112px; height: 112px;">
                        @endif
                    </div>
                </div>

                <div class="h-info-col">
                    <div class="text-center mb-3">
                        <h1 class="student-name h-student-name" style="margin-bottom: 0; text-align: center;">{{ $teacher->name }}</h1>
                        <span class="student-label" style="font-size: 0.8rem; margin-bottom: 5px; text-align: center;">Teacher</span>
                        <div style="width: 100%; border-bottom: 1px solid #eee;"></div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Employee ID :</span>
                        <span class="info-value">EMP-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone :</span>
                        <span class="info-value">{{ $teacher->phone ?? '+91 XXXXX' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email :</span>
                        <span class="info-value" style="font-size: 0.85rem;">{{ $teacher->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Join Date :</span>
                        <span class="info-value">{{ $teacher->created_at ? $teacher->created_at->format('d-M, Y') : date('d-M, Y') }}</span>
                    </div>
                </div>

                <div class="h-qr-col">
                    <div class="qr-section">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=teacher:{{ $teacher->id }}" class="qr-code-img" alt="QR Code">
                        <div class="qr-label">SCAN FOR ATTENDANCE</div>
                    </div>
                </div>
            </div>

            <div class="footer-banner">
                <span>SESSION: {{ date('Y') . '-' . (date('y')+1) }}</span>
                <span>ID No: {{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <script>
        function switchTab(type) {
            // Update buttons
            document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Update containers
            document.querySelectorAll('.card-container').forEach(c => c.classList.remove('active'));
            document.getElementById(type + '-container').classList.add('active');
        }
    </script>
</body>
</html>

