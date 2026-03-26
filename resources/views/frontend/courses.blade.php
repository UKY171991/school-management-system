@extends('layouts.frontend')

@section('title', 'Our Courses')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h6 class="text-primary fw-bold text-uppercase ls-1">Academic Programs</h6>
        <h2 class="display-5 fw-bold">Explore Our Curriculum</h2>
        <p class="text-muted mt-3" style="max-width: 600px; margin: 0 auto;">We offer a wide range of academic programs designed to cater to the diverse interests and career aspirations of our students.</p>
    </div>

    <div class="row g-4">
        <!-- Primary Education -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-primary text-white p-4 text-center">
                    <i class="bi bi-backpack display-4 mb-3"></i>
                    <h4 class="fw-bold">Primary School</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Building a strong foundation with a focus on literacy, numeracy, and creative thinking for grades 1-5.</p>
                    <ul class="list-unstyled mt-4 d-grid gap-2 text-muted small">
                        <li><i class="bi bi-check2 text-primary me-2"></i> Interactive Learning</li>
                        <li><i class="bi bi-check2 text-primary me-2"></i> Art & Craft</li>
                        <li><i class="bi bi-check2 text-primary me-2"></i> Basic Computer Skills</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0 p-4 pt-0">
                    <a href="#" class="btn btn-outline-primary rounded-pill w-100 fw-bold">View Syllabus</a>
                </div>
            </div>
        </div>

        <!-- Middle School -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-info text-white p-4 text-center">
                    <i class="bi bi-book-half display-4 mb-3"></i>
                    <h4 class="fw-bold">Middle School</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Expanding knowledge and critical thinking skills with introduced specialized subjects for grades 6-8.</p>
                    <ul class="list-unstyled mt-4 d-grid gap-2 text-muted small">
                        <li><i class="bi bi-check2 text-info me-2"></i> Science Labs</li>
                        <li><i class="bi bi-check2 text-info me-2"></i> Foreign Languages</li>
                        <li><i class="bi bi-check2 text-info me-2"></i> Sports Activities</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0 p-4 pt-0">
                    <a href="#" class="btn btn-outline-info rounded-pill w-100 fw-bold">View Syllabus</a>
                </div>
            </div>
        </div>

        <!-- High School -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-success text-white p-4 text-center">
                    <i class="bi bi-mortarboard display-4 mb-3"></i>
                    <h4 class="fw-bold">High School</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Preparing students for higher education and careers with advanced streams in Science, Commerce, and Arts.</p>
                    <ul class="list-unstyled mt-4 d-grid gap-2 text-muted small">
                        <li><i class="bi bi-check2 text-success me-2"></i> Career Counseling</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Advanced Preparation</li>
                        <li><i class="bi bi-check2 text-success me-2"></i> Leadership Programs</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0 p-4 pt-0">
                    <a href="#" class="btn btn-outline-success rounded-pill w-100 fw-bold">View Syllabus</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Extra Curricular -->
    <div class="row mt-5">
        <div class="col-12 text-center mb-5">
            <h3 class="fw-bold">Beyond Academics</h3>
        </div>
        <div class="col-md-3 mb-4 text-center" data-aos="fade-up">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <i class="bi bi-music-note-beamed fs-1 text-primary mb-3"></i>
                <h5>Music & Arts</h5>
            </div>
        </div>
        <div class="col-md-3 mb-4 text-center" data-aos="fade-up" data-aos-delay="100">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <i class="bi bi-dribbble fs-1 text-warning mb-3"></i>
                <h5>Sports Excellence</h5>
            </div>
        </div>
        <div class="col-md-3 mb-4 text-center" data-aos="fade-up" data-aos-delay="200">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <i class="bi bi-cpu fs-1 text-info mb-3"></i>
                <h5>Robotics Club</h5>
            </div>
        </div>
        <div class="col-md-3 mb-4 text-center" data-aos="fade-up" data-aos-delay="300">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <i class="bi bi-chat-quote fs-1 text-danger mb-3"></i>
                <h5>Debate Society</h5>
            </div>
        </div>
    </div>
</div>
@endsection
