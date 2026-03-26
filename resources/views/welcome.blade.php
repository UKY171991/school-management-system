@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 100px 0;
        border-radius: 0 0 50px 50px;
        margin-top: -24px;
        margin-bottom: 50px;
    }
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .icon-box {
        width: 60px;
        height: 60px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 24px;
    }
</style>

<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Modern School Management</h1>
        <p class="lead mb-5 px-md-5">Streamline your educational institution with our comprehensive management system. Manage students, teachers, grades, and subjects all in one place with ease and precision.</p>
        @guest
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 rounded-pill fw-bold text-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill fw-bold">Register</a>
            </div>
        @else
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg px-5 rounded-pill fw-bold text-primary">Go to Dashboard</a>
        @endauth
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="card feature-card h-100 p-4 shadow-sm">
                <div class="icon-box mx-auto">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3>Students</h3>
                <p class="text-muted">Maintain comprehensive student profiles, attendance, and performance records.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card feature-card h-100 p-4 shadow-sm">
                <div class="icon-box mx-auto">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <h3>Teachers</h3>
                <p class="text-muted">Manage teacher information, specializations, and assigned classes.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card feature-card h-100 p-4 shadow-sm">
                <div class="icon-box mx-auto">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <h3>Grades</h3>
                <p class="text-muted">Organize your school into classes and sections for better management.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card feature-card h-100 p-4 shadow-sm">
                <div class="icon-box mx-auto">
                    <i class="bi bi-book-half"></i>
                </div>
                <h3>Subjects</h3>
                <p class="text-muted">Define and track subjects across different grades and teachers.</p>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
