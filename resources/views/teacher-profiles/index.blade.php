@extends('adminlte::page')

@section('title', __('Teacher Profiles'))

@section('content_header')
    <h1>{{ __('Teacher Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Bar -->
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body p-3">
                <div class="row align-items-end">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="col-md-4">
                        <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                            <i class="fas fa-user-tie mr-1"></i> {{ __('Admin / School') }}
                        </label>
                        <select class="form-control select2 shadow-none" id="filter_school_id">
                            <option value="">{{ __('All Admins') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Teacher Profiles') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="createNewTeacher">
                        <i class="fas fa-plus"></i> {{ __('Add Teacher') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-responsive-md" id="teachersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Photo') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Specialization') }}</th>
                            <th>{{ __('Signature') }}</th>
                            <th width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="teacherList">
                        <!-- Loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Teacher Modal -->
<div class="modal fade" id="teacherModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="teacherForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherModalLabel">{{ __('Add Teacher') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="teacher_id" id="teacher_id">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group">
                        <label>{{ __('Select Admin Name') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Choose Admin...') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="school_id" id="school_id" value="{{ auth()->user()->school_id }}">
                    @endif
                    <div class="form-group">
                        <label>{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Specialization') }}</label>
                        <input type="text" class="form-control" id="specialization" name="specialization">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Photo') }}</label>
                        <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*">
                        <div id="photoPreview" class="mt-2"></div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Signature') }}</label>
                        <input type="file" class="form-control-file" id="signature" name="signature" accept="image/*">
                        <div id="signaturePreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">{{ __('Save Profile') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Teacher Modal -->
<div class="modal fade" id="viewTeacherModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewTeacherModalLabel">{{ __('Teacher Details') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <div id="viewPhotoContainer">
                            <img id="viewPhoto" src="" alt="Teacher Photo" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
                        </div>
                        <div class="mt-3">
                            <strong>{{ __('Signature') }}:</strong>
                            <div id="viewSignatureContainer" class="mt-2">
                                <img id="viewSignature" src="" alt="{{ __('Signature') }}" class="img-thumbnail" style="max-width: 100%; max-height: 80px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="35%">{{ __('Name') }}</th>
                                    <td id="viewName"></td>
                                </tr>
                                <tr>
                                    <th>{{ __('School') }}</th>
                                    <td id="viewSchool"></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td id="viewEmail"></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Phone') }}</th>
                                    <td id="viewPhone"></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Specialization') }}</th>
                                    <td id="viewSpecialization"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@stop
<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
</style>

@section('js')
<script>
    $(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        $('#filter_school_id').on('change', function() {
            table.ajax.reload();
        });

        // Initialize DataTable
        let table = $('#teachersTable').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('teacher-profiles.index') }}",
                data: function(d) {
                    d.school_id = $('#filter_school_id').val();
                },
                dataSrc: ""
            },
            columns: [
                {
                    data: 'photo',
                    name: 'photo',
                    render: function(data, type, row) {
                        return row.photo_url 
                            ? `<img src="${row.photo_url}" class="img-circle elevation-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ __('Photo') }}">` 
                            : `<span class="text-muted">{{ __('No Photo') }}</span>`;
                    },
                    className: 'text-center'
                },
                {data: 'name', name: 'name'},
                {
                    data: 'school', 
                    name: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border text-muted px-2 py-1">${data.name}</span>` : '-';
                    }
                },
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone', defaultContent: ''},
                {data: 'specialization', name: 'specialization', defaultContent: ''},
                {
                    data: 'signature',
                    name: 'signature',
                    render: function(data, type, row) {
                        return row.signature_url 
                            ? `<img src="${row.signature_url}" style="max-height: 30px;" alt="{{ __('Signature') }}">` 
                            : `<span class="text-muted">{{ __('No Sign') }}</span>`;
                    },
                    className: 'text-center'
                },
                {
                    data: 'id',
                    render: function(data) {
                        return `
                            <button class="btn btn-info btn-sm viewTeacher" data-id="${data}" title="{{ __('View Details') }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm editTeacher" data-id="${data}" title="{{ __('Edit') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm deleteTeacher" data-id="${data}" title="{{ __('Delete') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            rowId: function(a) { return 'teacher_' + a.id; }
        });

        $('#createNewTeacher').click(function () {
            $('#teacherForm').trigger("reset");
            $('#teacherModalLabel').html("{{ __('Add New Teacher') }}");
            $('#teacher_id').val('');
            @if(auth()->user()->isMasterAdmin())
                $('#school_id').val('').trigger('change');
            @endif
            $('#photoPreview').html('');
            $('#signaturePreview').html('');
            $('#teacherModal').modal('show');
        });

        $('#teacherForm').submit(function (e) {
            e.preventDefault();
            let id = $('#teacher_id').val();
            let formData = new FormData(this);
            
            let url = "{{ route('teacher-profiles.store') }}";
            if(id) {
                url = `/admin/teacher-profiles/${id}`;
                formData.append('_method', 'PUT');
            }

            $.ajax({
                data: formData,
                url: url,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#teacherForm').trigger("reset");
                    $('#teacherModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong. Please check inputs.') }}", 'error');
                }
            });
        });

        $('body').on('click', '.editTeacher', function () {
            let id = $(this).data('id');
            $.get(`/admin/teacher-profiles/${id}`, function (data) {
                $('#teacherModalLabel').html("{{ __('Edit Teacher Profile') }}");
                $('#teacher_id').val(data.id);
                $('#name').val(data.name);
                $('#school_id').val(data.school_id).trigger('change');
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#specialization').val(data.specialization);
                
                if(data.photo_url) {
                    $('#photoPreview').html(`
                        <div class="position-relative d-inline-block">
                            <img src="${data.photo_url}" width="100" class="img-thumbnail">
                            <button type="button" class="btn btn-danger btn-xs delete-asset" data-id="${data.id}" data-type="photo" style="position:absolute; top: -5px; right: -5px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                } else {
                    $('#photoPreview').empty();
                }
                
                if(data.signature_url) {
                    $('#signaturePreview').html(`
                        <div class="position-relative d-inline-block">
                            <img src="${data.signature_url}" width="100" class="img-thumbnail">
                            <button type="button" class="btn btn-danger btn-xs delete-asset" data-id="${data.id}" data-type="signature" style="position:absolute; top: -5px; right: -5px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                } else {
                    $('#signaturePreview').empty();
                }

                $('#teacherModal').modal('show');
            });
        });

        $('body').on('click', '.deleteTeacher', function () {
            let id = $(this).data('id');
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('Delete this teacher profile?') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: `/admin/teacher-profiles/${id}`,
                        success: function (data) {
                            table.ajax.reload();
                            Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                        }
                    });
                }
            });
        });

        // View Teacher Details
        $('body').on('click', '.viewTeacher', function () {
            let id = $(this).data('id');
            $.get(`/admin/teacher-profiles/${id}`, function (data) {
                $('#viewName').text(data.name || '-');
                $('#viewSchool').text(data.school ? data.school.name : '-');
                $('#viewEmail').text(data.email || '-');
                $('#viewPhone').text(data.phone || '-');
                $('#viewSpecialization').text(data.specialization || '-');
                
                // Handle Photo
                if(data.photo_url) {
                    $('#viewPhoto').attr('src', data.photo_url).show();
                    $('#viewPhotoContainer').html(`<img id="viewPhoto" src="${data.photo_url}" alt="{{ __('Photo') }}" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">`);
                } else {
                    $('#viewPhotoContainer').html('<div class="text-muted p-3 border rounded"><i class="fas fa-user fa-3x"></i><p class="mt-2">{{ __('No Photo Available') }}</p></div>');
                }

                // Handle Signature
                if(data.signature_url) {
                    $('#viewSignatureContainer').html(`<img id="viewSignature" src="${data.signature_url}" alt="{{ __('Signature') }}" class="img-thumbnail" style="max-width: 100%; max-height: 80px;">`);
                } else {
                    $('#viewSignatureContainer').html('<div class="text-muted p-2 border rounded"><small>{{ __('No Signature Available') }}</small></div>');
                }

                $('#viewTeacherModal').modal('show');
            });
        });

        // Delete Asset (Photo/Signature)
        $('body').on('click', '.delete-asset', function () {
            let id = $(this).data('id');
            let type = $(this).data('type');
            let container = $(this).closest('.form-group').find('.mt-2');

            let title = type === 'photo' ? "{{ __('Delete this photo?') }}" : "{{ __('Delete this signature?') }}";

            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: `/admin/teacher-profiles/${id}/delete-asset`,
                        data: { type: type },
                        success: function (data) {
                            container.empty();
                            table.ajax.reload();
                            Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire("{{ __('Error') }}", "{{ __('Failed to delete asset') }}", 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@stop
