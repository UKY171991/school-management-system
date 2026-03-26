<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($general_settings) ? $general_settings->school_name : config('app.name', 'Laravel') }} - @yield('title')</title>

    @if(isset($general_settings) && $general_settings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $general_settings->favicon) }}?v={{ $general_settings->updated_at ? $general_settings->updated_at->timestamp : time() }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --text-color: #2b2d42;
            --bg-color: #f8f9fa;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .btn-modern-primary {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 2px solid var(--primary-color);
        }

        .btn-modern-primary:hover {
            background-color: transparent;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 60px 0 30px;
            border-top: 1px solid #e9ecef;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: inline-block;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #f1f3f5;
            text-align: center;
            color: #adb5bd;
            font-size: 0.9rem;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding-top: 80px; 
            min-height: calc(100vh - 300px);
        }

        
    </style>
    @yield('css')
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                @if(isset($general_settings) && $general_settings->logo)
                    <img src="{{ asset('storage/' . $general_settings->logo) }}?v={{ $general_settings->updated_at ? $general_settings->updated_at->timestamp : time() }}" alt="Logo" height="40" class="me-2">
                @endif
                {{ isset($general_settings) ? $general_settings->school_name : config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses') ? 'active' : '' }}" href="{{ route('courses') }}">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                    @guest
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('login') }}" class="btn-modern-primary">Login</a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn-modern-primary">Dashboard</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <a href="{{ route('home') }}" class="footer-logo">
                        {{ isset($general_settings) ? $general_settings->school_name : config('app.name', 'Laravel') }}
                    </a>
                    <p class="text-muted mb-4">{{ isset($general_settings->school_address) ? $general_settings->school_address : 'Building a better future through education and innovation.' }}</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('courses') }}">Courses</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5>Resources</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">Academic Calendar</a></li>
                        <li><a href="#">Admissions</a></li>
                        <li><a href="#">Student Portal</a></li>
                        <li><a href="#">Parent Portal</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled footer-links">
                        @if(isset($general_settings) && $general_settings->school_address)
                            <li class="d-flex"><i class="bi bi-geo-alt me-2 mt-1 text-primary"></i> <span>{{ $general_settings->school_address }}</span></li>
                        @endif
                        @if(isset($general_settings) && $general_settings->school_phone)
                            <li class="d-flex"><i class="bi bi-telephone me-2 mt-1 text-primary"></i> <span>{{ $general_settings->school_phone }}</span></li>
                        @endif
                        @if(isset($general_settings) && $general_settings->school_email)
                            <li class="d-flex"><i class="bi bi-envelope me-2 mt-1 text-primary"></i> <span>{{ $general_settings->school_email }}</span></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">{{ isset($general_settings->footer_text) ? $general_settings->footer_text : '© ' . date('Y') . ' School Management System. All rights reserved.' }}</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
    @yield('js')
</body>
</html>
