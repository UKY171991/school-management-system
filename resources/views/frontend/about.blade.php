@extends('layouts.frontend')

@section('title', 'About Us')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6" data-aos="fade-right">
            <h6 class="text-primary fw-bold text-uppercase ls-1 mb-3">Who We Are</h6>
            <h2 class="display-4 fw-bold mb-4">Dedicated to Excellence in Education Since 1990</h2>
            <p class="lead text-muted mb-4">We are more than just a school; we are a community dedicated to producing the leaders of tomorrow.</p>
            <p class="text-muted mb-4">Our institution has been a beacon of knowledge and character building for over three decades. We believe in providing a balanced education that nurtures the mind, body, and soul.</p>
            <div class="row g-4 mt-2">
                <div class="col-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i>
                        <span>Holistic Development</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i>
                        <span>Qualified Faculty</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i>
                        <span>Global Standards</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i>
                        <span>Creative Learning</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left">
            <div class="position-relative">
                <img src="https://img.freepik.com/free-photo/diverse-students-studying-together_53876-47000.jpg" class="img-fluid rounded-4 shadow-lg" alt="About Us">
                <div class="position-absolute bg-white p-4 rounded-3 shadow-lg" style="bottom: -30px; left: -30px; max-width: 250px;">
                    <div class="d-flex align-items-center gap-3">
                        <h1 class="text-primary fw-bold mb-0">25+</h1>
                        <div>
                            <p class="fw-bold mb-0">Years of</p>
                            <p class="text-muted small mb-0">Excellence</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row py-5 mt-5">
        <div class="col-12 text-center mb-5" data-aos="fade-up">
            <h6 class="text-primary fw-bold text-uppercase ls-1">Our Leadership</h6>
            <h2 class="fw-bold">Meet Our Principles</h2>
        </div>
        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="mb-4 mx-auto" style="width: 120px; height: 120px; overflow: hidden; border-radius: 50%;">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" class="w-100 h-100 object-fit-cover" alt="Principal">
                </div>
                <h5>Dr. John Doe</h5>
                <p class="text-primary small fw-bold">PRINCIPAL</p>
                <p class="text-muted small">Ph.D. in Education with 20+ years of experience in school administration.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="mb-4 mx-auto" style="width: 120px; height: 120px; overflow: hidden; border-radius: 50%;">
                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random" class="w-100 h-100 object-fit-cover" alt="Vice Principal">
                </div>
                <h5>Mrs. Jane Smith</h5>
                <p class="text-primary small fw-bold">VICE PRINCIPAL</p>
                <p class="text-muted small">Dedicated educator focusing on curriculum development and student welfare.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="mb-4 mx-auto" style="width: 120px; height: 120px; overflow: hidden; border-radius: 50%;">
                    <img src="https://ui-avatars.com/api/?name=Robert+Brown&background=random" class="w-100 h-100 object-fit-cover" alt="Admin Head">
                </div>
                <h5>Mr. Robert Brown</h5>
                <p class="text-primary small fw-bold">HEAD OF ADMINISTRATION</p>
                <p class="text-muted small">Ensuring smooth operations and world-class facilities for our students.</p>
            </div>
        </div>
    </div>
</div>
@endsection
