@extends('adminlte::page')

@section('title', __('Account Settings'))

@section('content_header')
    <h1>{{ __('Account Settings') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <p class="text-muted text-center">{{ $user->role ? __($user->role->name) : __('Administrator') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link {{ $activeTab == 'profile' ? 'active' : '' }}" href="#profile" data-toggle="tab">{{ __('Profile') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ $activeTab == 'password' ? 'active' : '' }}" href="#password" data-toggle="tab">{{ __('Change Password') }}</a></li>
                    @if(auth()->user()->isMasterAdmin())
                        <li class="nav-item"><a class="nav-link" href="#maintenance" data-toggle="tab">{{ __('System Maintenance') }}</a></li>
                    @endif
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Profile Tab -->
                    <div class="{{ $activeTab == 'profile' ? 'active' : '' }} tab-pane" id="profile">
                        <form id="profileForm" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputName" name="name" value="{{ $user->name }}" placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" name="email" value="{{ $user->email }}" placeholder="{{ __('Email') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputLanguage" class="col-sm-2 col-form-label">{{ __('Language') }}</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="inputLanguage" name="language">
                                        <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="hi" {{ $user->language == 'hi' ? 'selected' : '' }}>हिन्दी (Hindi)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Password Tab -->
                    <div class="{{ $activeTab == 'password' ? 'active' : '' }} tab-pane" id="password">
                        <form id="passwordForm" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label for="current_password" class="col-sm-3 col-form-label">{{ __('Current Password') }}</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="{{ __('Current Password') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new_password" class="col-sm-3 col-form-label">{{ __('New Password') }}</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="{{ __('New Password') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new_password_confirmation" class="col-sm-3 col-form-label">{{ __('Confirm New Password') }}</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="{{ __('Confirm New Password') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-danger">{{ __('Change Password') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Maintenance Tab -->
                    @if(auth()->user()->isMasterAdmin())
                    <div class="tab-pane" id="maintenance">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box shadow-none border">
                                    <span class="info-box-icon bg-info"><i class="fas fa-broom"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ __('Clear Cache') }}</span>
                                        <span class="info-box-number text-muted font-weight-normal small">{{ __('Optimizes the application by clearing all cached data.') }}</span>
                                        <button class="btn btn-info btn-sm mt-2 maintenance-btn" data-action="optimize">{{ __('Clear Now') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box shadow-none border">
                                    <span class="info-box-icon bg-success"><i class="fas fa-database"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ __('Migrate Database') }}</span>
                                        <span class="info-box-number text-muted font-weight-normal small">{{ __('Runs database migrations to apply latest schema changes.') }}</span>
                                        <button class="btn btn-success btn-sm mt-2 maintenance-btn" data-action="migrate">{{ __('Migrate Now') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box shadow-none border">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-link"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ __('Storage Link') }}</span>
                                        <span class="info-box-number text-muted font-weight-normal small">{{ __('Creates a symbolic link for public file access.') }}</span>
                                        <button class="btn btn-warning btn-sm mt-2 maintenance-btn" data-action="storage-link">{{ __('Link Now') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="maintenanceOutput" class="mt-3 p-3 bg-dark text-light rounded d-none" style="max-height: 200px; overflow-y: auto;">
                            <pre class="m-0 text-light small" id="outputText"></pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Handle Profile Update
        $('#profileForm').submit(function(e) {
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: "{{ route('admin.settings.profile') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        title: '{{ __("Success") }}',
                        text: response.success,
                        icon: 'success'
                    }).then(() => {
                        if (response.reload) {
                            window.location.reload();
                        }
                    });
                    $('.profile-username').text($('#inputName').val());
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            let input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        });
                    } else {
                        Swal.fire('{{ __("Error") }}', '{{ __("Something went wrong") }}', 'error');
                    }
                }
            });
        });

        // Handle Password Change
        $('#passwordForm').submit(function(e) {
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: "{{ route('admin.settings.password') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire('{{ __("Success") }}', response.success, 'success');
                    $('#passwordForm')[0].reset();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            let input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        });
                    } else {
                        Swal.fire('{{ __("Error") }}', '{{ __("Something went wrong") }}', 'error');
                    }
                }
            });
        });

        // Handle Maintenance Actions
        $('.maintenance-btn').click(function() {
            let action = $(this).data('action');
            let btn = $(this);
            let originalText = btn.text();
            
            Swal.fire({
                title: '{{ __("Are you sure?") }}',
                text: "{{ __('Running this operation may briefly impact site performance.') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("Yes, proceed!") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).text('{{ __("Processing...") }}');
                    $('#maintenanceOutput').addClass('d-none');

                    $.ajax({
                        url: `/admin/maintenance/${action}`,
                        type: "POST",
                        success: function(response) {
                            btn.prop('disabled', false).text(originalText);
                            Swal.fire('{{ __("Success") }}', response.success, 'success');
                            if (response.output) {
                                $('#maintenanceOutput').removeClass('d-none');
                                $('#outputText').text(response.output);
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).text(originalText);
                            let errorMsg = xhr.responseJSON ? (xhr.responseJSON.error || xhr.responseJSON.message) : '{{ __("Something went wrong") }}';
                            Swal.fire('{{ __("Error") }}', errorMsg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@stop
