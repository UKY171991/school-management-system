@extends('layouts.frontend')

@section('title', 'Home')

@section('css')
<style>
    .hero {
        padding: 180px 0 120px;
        background: radial-gradient(circle at 10% 20%, rgba(67, 97, 238, 0.05) 0%, rgba(255, 255, 255, 0) 90%);
        position: relative;
        overflow: hidden;
    }

    .hero-shape {
        position: absolute;
        top: -50%;
        right: -10%;
        width: 70%;
        height: 150%;
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.1) 0%, rgba(247, 37, 133, 0.05) 100%);
        border-radius: 50%;
        z-index: -1;
        filter: blur(80px);
    }

    .hero h1 {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 25px;
        color: #2b2d42;
    }

    .hero-highlight {
        color: var(--primary-color);
        background: linear-gradient(120deg, rgba(67, 97, 238, 0.1) 0%, rgba(67, 97, 238, 0) 100%);
        padding: 0 10px;
        border-radius: 8px;
    }

    .hero p {
        font-size: 1.25rem;
        color: #6c757d;
        margin-bottom: 40px;
        line-height: 1.7;
    }

    .feature-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .feature-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.2);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 25px;
        transition: transform 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-card {
        text-align: center;
        padding: 40px 20px;
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 10px;
        display: block;
    }

    .cta-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 100px 0;
        color: white;
        border-radius: 30px;
        margin: 50px auto;
        text-align: center;
    }

</style>
@endsection

@section('content')
<div class="hero">
    <div class="hero-shape"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <h1>Welcome to <span class="hero-highlight">{{ isset($general_settings) ? $general_settings->school_name : 'School Management' }}</span></h1>
                <p>Empowering the next generation with world-class education, innovative learning solutions, and a comprehensive management system designed for excellence.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('about') }}" class="btn btn-modern-primary btn-lg px-5">Learn More</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5" style="border-width: 2px; font-weight: 600;">Contact Us</a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://img.freepik.com/free-vector/learning-concept-illustration_114360-6186.jpg" alt="Hero Image" class="img-fluid" style="border-radius: 20px;">
            </div>
        </div>
    </div>
</div>

<div class="container py-5 section-features">
    <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary-color);">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <h3>Expert Teachers</h3>
                <p class="text-muted mb-0">Our faculty consists of highly qualified professionals dedicated to nurturing every student's potential.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(247, 37, 133, 0.1); color: var(--accent-color);">
                    <i class="bi bi-laptop"></i>
                </div>
                <h3>Modern Facilities</h3>
                <p class="text-muted mb-0">State-of-the-art labs, smart classrooms, and extensive libraries to support holistic learning.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="feature-card">
                <div class="feature-icon" style="background: rgba(76, 201, 240, 0.1); color: #4cc9f0;">
                    <i class="bi bi-trophy"></i>
                </div>
                <h3>Excellence in Sports</h3>
                <p class="text-muted mb-0">Comprehensive sports programs to ensure physical well-being and team spirit among students.</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3" data-aos="zoom-in">
            <div class="stat-card">
                <span class="stat-number">50+</span>
                <span class="fw-bold text-muted text-uppercase ls-1">Years Experience</span>
            </div>
        </div>
        <div class="col-md-3" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-card">
                <span class="stat-number">2k+</span>
                <span class="fw-bold text-muted text-uppercase ls-1">Students Enrolled</span>
            </div>
        </div>
        <div class="col-md-3" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-card">
                <span class="stat-number">100+</span>
                <span class="fw-bold text-muted text-uppercase ls-1">Expert Staff</span>
            </div>
        </div>
        <div class="col-md-3" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-card">
                <span class="stat-number">25+</span>
                <span class="fw-bold text-muted text-uppercase ls-1">Awards Won</span>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="cta-section" data-aos="fade-up">
        <h2 class="display-5 fw-bold mb-4">Ready to Join Our Community?</h2>
        <p class="lead mb-5 opacity-75">Admission is open for the upcoming academic year. Secure your spot today!</p>
        <a href="{{ route('contact') }}" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary">Apply Now</a>
    </div>
</div>
@endsection
