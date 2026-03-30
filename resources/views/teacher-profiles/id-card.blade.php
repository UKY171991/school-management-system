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
            --school-green: #2e7d32;
            --school-green-light: #4caf50;
            --school-green-dark: #1b5e20;
            --premium-gray: #f8f9fa;
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
            background-color: var(--school-green);
            color: #fff;
        }

        .btn-print {
            background-color: var(--school-green-dark);
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

        .header-bg {
            background: var(--school-green);
            background-image: 
                linear-gradient(135deg, var(--school-green) 0%, var(--school-green-light) 100%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66-3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-40-39c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm20-40c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM10 50c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm10 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm80 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-10 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-40 20c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm0 10c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-10 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm10 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            position: relative;
            padding: 25px 20px 30px;
            color: #fff;
            text-align: center;
            overflow: hidden;
        }

        /* Watermark ONLY for vertical */
        .vertical-card .header-bg::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150px;
            height: 150px;
            transform: translate(-50%, -50%);
            background-image: url("{{ $teacher->school->logo_url ?? '' }}");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }

        /* Shield Badge Style */
        .shield-logo {
            position: absolute;
            top: 20px;
            right: 25px;
            width: 70px;
            height: 70px;
            background: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .shield-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Watermarks */
        .vertical-card .header-bg::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 15rem;
            height: 15rem;
            transform: translate(-50%, -50%);
            background-image: url("{{ $teacher->school->logo_url ?? '' }}");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }

        .horizontal-card .header-bg::before {
            content: '';
            position: absolute;
            top: 10px;
            right: 15px;
            width: 120px;
            height: 120px;
            background-image: url("{{ $teacher->school->logo_url ?? '' }}");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: top right;
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }

        /* HIDE shield on both for now as per last request for background logo only */
        .vertical-card .shield-logo { display: none; }
        .horizontal-card .shield-logo { display: none; }

        .header-bg::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 15px;
            background: rgba(255,255,255,0.1);
            clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 100%);
            z-index: 1;
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
            background-color: var(--school-green-light);
            background-image: linear-gradient(90deg, #81c784 0%, #a5d6a7 100%);
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1b5e20;
            margin-top: auto;
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
            <div class="header-bg">
                <div class="school-info">
                    <h2 class="school-name">{{ $teacher->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                    <div class="location">{{ $teacher->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                    <div class="contact">
                        <span class="contact-item"><i class="fa fa-phone"></i> {{ $teacher->school->phone ?? '9711447614' }}</span>
                        <span class="contact-item"><i class="fa fa-envelope"></i> {{ $teacher->school->email ?? 'info@thewebbrain.in' }}</span>
                    </div>
                </div>
                <div class="shield-logo">
                    @if(isset($teacher->school->logo_url))
                        <img src="{{ $teacher->school->logo_url }}" alt="Logo">
                    @else
                         <i class="fa fa-university fa-2x text-success"></i>
                    @endif
                </div>
            </div>

            <div class="v-photo-section">
                @if($teacher->photo_url)
                    <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="teacher-photo v-teacher-photo">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=2e7d32&size=200" alt="{{ $teacher->name }}" class="teacher-photo v-teacher-photo">
                @endif
                
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=teacher:{{ $teacher->id }}" class="v-qr-code" alt="QR Code">
            </div>

            <div class="v-body">
                <div class="designation-badge">Teacher</div>
                <div class="id-name">{{ $teacher->name }}</div>

                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Employee ID:</span>
                        <span class="info-value">EMP-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $teacher->phone ?? '+91 XXXXXXXXX' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $teacher->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Join Date:</span>
                        <span class="info-value">{{ $teacher->created_at ? $teacher->created_at->format('d-M, Y') : date('d-M, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="footer-banner">
                <i class="fa fa-pencil-alt"></i> Scan for attendance
            </div>
        </div>
    </div>

    <!-- Horizontal Card -->
    <div id="horizontal-container" class="card-container">
        <div class="id-card horizontal-card">
            <div class="h-layout">
                <div class="h-left">
                    @if($teacher->photo_url)
                        <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="teacher-photo h-teacher-photo">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=2e7d32&size=200" alt="{{ $teacher->name }}" class="teacher-photo h-teacher-photo">
                    @endif
                    
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=teacher:{{ $teacher->id }}" class="h-qr-code" alt="QR Code">
                </div>
                
                <div class="h-right">
                    <div class="header-bg h-header">
                        <div class="school-info">
                            <h2 class="school-name">{{ $teacher->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                            <div class="location">{{ $teacher->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                            <div class="contact">
                                <span class="contact-item"><i class="fa fa-phone"></i> {{ $teacher->school->phone ?? '9711447614' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="h-body">
                        <div class="h-name-group">
                            <h3 class="h-designation">Teacher</h3>
                            <h2 class="h-name">{{ $teacher->name }}</h2>
                        </div>

                        <div class="info-grid h-info-grid">
                            <div class="info-row">
                                <span class="info-label">Employee ID:</span>
                                <span class="info-value">EMP-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Phone:</span>
                                <span class="info-value">{{ $teacher->phone ?? '+91 XXXXX' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value" style="font-size: 0.75rem;">{{ $teacher->email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Join Date:</span>
                                <span class="info-value">{{ $teacher->created_at ? $teacher->created_at->format('d-M, Y') : date('d-M, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-banner">
                <i class="fa fa-pencil-alt"></i> Scan for attendance
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

