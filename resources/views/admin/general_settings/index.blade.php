@extends('adminlte::page')

@section('title', __('General Settings'))

@section('content_header')
    <h1>{{ __('General Settings') }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">{{ __('General') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="#logos" data-toggle="tab">{{ __('Logos & Images') }}</a></li>
        </ul>
    </div>
    <div class="card-body">
        <form id="settingsForm" enctype="multipart/form-data">
            @csrf
            <div class="tab-content">
                <!-- General Tab -->
                <div class="active tab-pane" id="general">
                    <div class="form-group row">
                        <label for="school_name" class="col-sm-2 col-form-label">{{ __('School Name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="school_name" name="school_name" value="{{ $settings->school_name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="school_address" class="col-sm-2 col-form-label">{{ __('Address') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="school_address" name="school_address" value="{{ $settings->school_address }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="school_phone" class="col-sm-2 col-form-label">{{ __('Phone') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="school_phone" name="school_phone" value="{{ $settings->school_phone }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="school_email" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="school_email" name="school_email" value="{{ $settings->school_email }}">
                        </div>
                    </div>
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group row">
                        <label for="currency_symbol" class="col-sm-2 col-form-label">{{ __('Currency Symbol') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="{{ $settings->currency_symbol }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="footer_text" class="col-sm-2 col-form-label">{{ __('Footer Text') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="footer_text" name="footer_text" value="{{ $settings->footer_text }}">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Logos Tab -->
                <div class="tab-pane" id="logos">
                    <div class="form-group row">
                        <label for="logo" class="col-sm-2 col-form-label">{{ __('Logo') }}</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                                    <label class="custom-file-label" for="logo">{{ __('Choose file') }}</label>
                                </div>
                            </div>
                            <div class="mt-2" id="logo-preview-container">
                                @if($settings->logo)
                                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ __('Current Logo') }}" style="max-height: 50px;" id="current-logo">
                                @else
                                    <img src="" alt="{{ __('Current Logo') }}" style="max-height: 50px; display: none;" id="current-logo">
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group row">
                        <label for="favicon" class="col-sm-2 col-form-label">{{ __('Favicon') }}</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="favicon" name="favicon" accept="image/x-icon,image/png,image/jpg,image/svg+xml">
                                    <label class="custom-file-label" for="favicon">{{ __('Choose file') }}</label>
                                </div>
                            </div>
                            <div class="mt-2" id="favicon-preview-container">
                                @if($settings->favicon)
                                    <img src="{{ asset('storage/' . $settings->favicon) }}" alt="{{ __('Current Favicon') }}" style="max-height: 32px;" id="current-favicon">
                                @else
                                    <img src="" alt="{{ __('Current Favicon') }}" style="max-height: 32px; display: none;" id="current-favicon">
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#settingsForm').submit(function(e) {
            e.preventDefault();
            
            // Validate form (basic)
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }

            var formData = new FormData(this);

            // Show loading state
            Swal.fire({
                title: '{{ __("Uploading...") }}',
                text: '{{ __("Please wait while we save your settings") }}',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('admin.general-settings.update') }}",
                type: 'POST',
                data: formData,
                success: function (response) {
                    console.log('Server response:', response);
                    
                    // Update images if new ones were uploaded
                    if (response.logo_url) {
                        var logoUrl = '{{ asset("storage") }}/' + response.logo_url + '?t=' + new Date().getTime();
                        console.log('Updating logo to:', logoUrl);
                        
                        // Update or create logo image
                        if ($('#current-logo').length) {
                            $('#current-logo').attr('src', logoUrl);
                        } else {
                            $('label[for="logo"]').parent().parent().after('<div class="mt-2"><img src="' + logoUrl + '" alt="Current Logo" style="max-height: 50px;" id="current-logo"></div>');
                        }
                    }
                    
                    if (response.favicon_url) {
                        var faviconUrl = '{{ asset("storage") }}/' + response.favicon_url + '?t=' + new Date().getTime();
                        console.log('Updating favicon to:', faviconUrl);
                        
                        // Update or create favicon image
                        if ($('#current-favicon').length) {
                            $('#current-favicon').attr('src', faviconUrl);
                        } else {
                            $('label[for="favicon"]').parent().parent().after('<div class="mt-2"><img src="' + faviconUrl + '" alt="Current Favicon" style="max-height: 32px;" id="current-favicon"></div>');
                        }
                        
                        // Update favicon in browser tab
                        if ($('link[rel="icon"]').length) {
                            $('link[rel="icon"]').attr('href', faviconUrl);
                        } else {
                            $('head').append('<link rel="icon" href="' + faviconUrl + '">');
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Success!") }}',
                        text: response.success,
                        showConfirmButton: true,
                        confirmButtonText: '{{ __("OK") }}'
                    }).then(() => {
                        // Reload to update all instances
                        location.reload(); 
                    });
                },
                error: function (xhr) {
                    console.error('Error response:', xhr);
                    
                    var errorMessage = '{{ __("An error occurred while saving settings.") }}';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMessage = "";
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + "<br>";
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                     
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Error!") }}',
                        html: errorMessage,
                        showConfirmButton: true,
                        confirmButtonText: '{{ __("OK") }}'
                    });
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        
        // Custom File Input
        bsCustomFileInput.init();

        // Preview logo on file select
        $('#logo').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#current-logo').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Preview favicon on file select
        $('#favicon').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#current-favicon').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@stop
