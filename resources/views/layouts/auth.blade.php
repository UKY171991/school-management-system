<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($general_settings) ? $general_settings->school_name : config('app.name', 'Laravel') }} - Authentication</title>

    @if(isset($general_settings) && $general_settings->favicon)
        <link rel="icon" href="{{ Storage::url($general_settings->favicon) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: url('{{ asset('images/auth-bg.png') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .auth-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
            z-index: -1;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .auth-header h2 {
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            opacity: 0.9;
            font-weight: 300;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .auth-body {
            padding: 40px 35px;
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            background-color: #f7fafc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn-auth {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-auth:hover {
            opacity: 0.9;
            transform: scale(1.02);
            color: white;
        }

        .auth-footer {
            text-align: center;
            padding: 0 35px 30px;
            color: #718096;
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 5px;
        }

        /* Micro-animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-card {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="auth-overlay"></div>
    
    <div class="auth-card">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
