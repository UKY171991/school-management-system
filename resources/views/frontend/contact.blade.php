@extends('layouts.frontend')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-5" data-aos="fade-right">
            <h6 class="text-primary fw-bold text-uppercase ls-1">Get in Touch</h6>
            <h2 class="display-5 fw-bold mb-4">Let's Start a Conversation</h2>
            <p class="text-muted mb-5">Have questions about admissions, curriculum, or anything else? We're here to help.</p>

            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fw-bold mb-1">Visit Us</h5>
                    <p class="text-muted mb-0">{{ isset($general_settings->school_address) ? $general_settings->school_address : '123 School Street, Education City' }}</p>
                </div>
            </div>

            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-envelope fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fw-bold mb-1">Email Us</h5>
                    <p class="text-muted mb-0">{{ isset($general_settings->school_email) ? $general_settings->school_email : 'info@schooldomain.com' }}</p>
                </div>
            </div>

            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-telephone fs-4"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fw-bold mb-1">Call Us</h5>
                    <p class="text-muted mb-0">{{ isset($general_settings->school_phone) ? $general_settings->school_phone : '+1 234 567 8900' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7" data-aos="fade-left">
            <div class="bg-white p-5 rounded-4 shadow-sm border">
                <h4 class="fw-bold mb-4">Send us a Message</h4>
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">First Name</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" placeholder="John">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Last Name</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" placeholder="Doe">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Email Address</label>
                            <input type="email" class="form-control form-control-lg bg-light border-0" placeholder="john@example.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Subject</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" placeholder="Inquiry about admissions">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Message</label>
                            <textarea class="form-control form-control-lg bg-light border-0" rows="5" placeholder="How can we help you?"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-modern-primary w-100 py-3 mt-3">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.4243642340245!2d88.3639!3d22.5726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDM0JzIxLjQiTiA4OMKwMjEnNTAuMCJF!5e0!3m2!1sen!2sus!4v1625641234567!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>
@endsection
