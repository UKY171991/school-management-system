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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 15px;
            z-index: 100;
        }

        .btn-check:checked+.btn-outline-primary {
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
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
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
            padding-right: 80px;
            /* Reserve space for the badge */
            text-align: center;
        }

        .school-info .school-name {
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
            color: var(--school-gold-light);
            line-height: 1.1;
            white-space: normal;
        }

        .school-info .location {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 3px;
            font-weight: 500;
            line-height: 1.2;
        }

        .school-info .contact {
            font-size: 0.68rem;
            opacity: 0.9;
            margin-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            gap: 8px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .contact-item+.contact-item {
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            padding-left: 12px;
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
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Content Sections */
        .photo-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4px;
            background: linear-gradient(135deg, var(--school-gold-light) 0%, var(--school-gold) 100%);
            border-radius: 50%;
            width: 110px;
            height: 110px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .id-photo {
            width: 102px;
            height: 102px;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
            background: #f8f9fa;
        }

        .id-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--school-blue);
            margin: 10px 0 5px;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .designation-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            background: var(--school-gold);
            padding: 3px 20px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(197, 160, 89, 0.3);
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
            color: var(--school-gold);
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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-code-img {
            width: 110px;
            height: 110px;
            border: 1px solid #eee;
            padding: 5px;
            background: #fff;
        }

        .qr-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #666;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer Style */
        .id-card .card-footer {
            background: var(--school-blue);
            color: white;
            padding: 10px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 700;
            border-top: 3px solid var(--school-gold);
            margin-top: auto;
            position: relative;
            z-index: 5;
            text-transform: uppercase;
        }

        /* Vertical Card Specific */
        .vertical-card {
            width: 330px;
            height: 540px;
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

        /* Horizontal Card Specific */
        .horizontal-card {
            width: 560px;
            height: 400px;
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
            width: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-left: 1px solid #f0f0f0;
            padding-right: 15px;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .controls {
                display: none !important;
            }

            .card-container:not(.active) {
                display: none !important;
            }

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
        <div class="nav nav-pills" role="tablist">
            <button class="btn btn-outline-primary active me-2" onclick="switchTab('vertical')">Vertical Layout</button>
            <button class="btn btn-outline-primary" onclick="switchTab('horizontal')">Horizontal Layout</button>
        </div>

        <button onclick="window.print()" class="btn-print ms-3">
            <i class="fa fa-print"></i> Print ID Card
        </button>

        <button onclick="window.close()" class="btn btn-outline-secondary ms-2">
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

            <div class="v-body">
                <div class="row g-0 align-items-center justify-content-center mb-3">
                    <div class="col-6 d-flex justify-content-center">
                        <div class="photo-wrapper">
                            @if($teacher->photo_url)
                                <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="id-photo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=0d244f&size=200"
                                    alt="{{ $teacher->name }}" class="id-photo">
                            @endif
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="qr-section">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=teacher:{{ $teacher->id }}"
                                class="qr-code-img" alt="QR Code">
                            <div class="qr-label">SCAN FOR ATTENDANCE</div>
                        </div>
                    </div>
                </div>

                <h1 class="id-name">{{ $teacher->name }}</h1>
                <span class="designation-label">Teacher</span>

                <div class="v-info-list">
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
                        <span class="info-value" style="font-size: 0.8rem;">{{ $teacher->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Join Date :</span>
                        <span class="info-value">{{ $teacher->created_at ? $teacher->created_at->format('d-M, Y') : date('d-M, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <span>SESSION: {{ date('Y') . '-' . (date('y') + 1) }}</span>
                <span>ID No: {{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <!-- Horizontal Card -->
    <div id="horizontal-container" class="card-container">
        <div class="id-card horizontal-card">
            <div class="card-header">
                <div class="school-info">
                    <h2 class="school-name">{{ $teacher->school->name ?? 'DEMO PUBLIC SCHOOL' }}</h2>
                    <div class="location">{{ $teacher->school->address ?? 'Lucknow, Uttar Pradesh' }}</div>
                    <div class="contact">
                        <span class="contact-item">
                            <i class="fa fa-phone"></i> {{ $teacher->school->phone ?? '9711447614' }}
                        </span>
                        <span class="separator">|</span>
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

            <div class="h-body">
                <div class="h-photo-col">
                    <div class="photo-wrapper">
                        @if($teacher->photo_url)
                            <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="id-photo">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=f0f2f5&color=0d244f&size=200"
                                alt="{{ $teacher->name }}" class="id-photo">
                        @endif
                    </div>
                </div>

                <div class="h-info-col">
                    <div class="text-center mb-3">
                        <h1 class="id-name" style="margin-bottom: 0;">{{ $teacher->name }}</h1>
                        <span class="designation-label" style="font-size: 0.7rem;">Teacher</span>
                        <div style="border-bottom: 1.5px solid #eee; margin-top: 8px; width: 100%;"></div>
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
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=teacher:{{ $teacher->id }}"
                            class="qr-code-img" style="width: 90px; height: 90px;" alt="QR Code">
                        <div class="qr-label">SCAN FOR ATTENDANCE</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <span>SESSION: {{ date('Y') . '-' . (date('y') + 1) }}</span>
                <span>ID No: {{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <script>
        function switchTab(type) {
            document.querySelectorAll('.btn-group .btn, .btn').forEach(btn => btn.classList.remove('active'));
            if (event.target) event.target.classList.add('active');
            document.querySelectorAll('.card-container').forEach(c => c.classList.remove('active'));
            document.getElementById(type + '-container').classList.add('active');
        }
    </script>
</body>

</html>
