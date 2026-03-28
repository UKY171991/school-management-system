@extends('adminlte::page')

@section('title', __('Student Profile & Documents'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('Student Profiles & Documents') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Student Profiles') }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Compact Filter Bar -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body p-3">
            <form id="filterForm" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-university mr-1"></i> {{ __('Filter by School') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_school_id" name="school_id">
                        <option value="">{{ __('All Schools') }}</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted mb-1 text-uppercase">
                        <i class="fas fa-graduation-cap mr-1"></i> {{ __('Filter by Class') }}
                    </label>
                    <select class="form-control select2 shadow-none" id="filter_grade_id" name="grade_id">
                        <option value="">{{ __('All Classes & Sections') }}</option>
                        @foreach(\App\Models\Grade::all() as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }} - {{ $grade->section }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary shadow-sm px-4">
                        <i class="fas fa-search mr-1"></i> {{ __('Filter') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary shadow-sm ml-1" id="resetFilter">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-id-card mr-2"></i>{{ __('Student Directory') }}
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="profilesTable" style="width:100%">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0">{{ __('Reg #') }}</th>
                            <th class="border-top-0">{{ __('School') }}</th>
                            <th class="border-top-0">{{ __('Student Name') }}</th>
                            <th class="border-top-0">{{ __('Class / Section') }}</th>
                            <th class="border-top-0 text-center">{{ __('Docs Count') }}</th>
                            <th class="border-top-0 text-center" width="150">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="studentList">
                        <!-- Loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Profile/Docs Modal -->
<div class="modal fade" id="docsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold" id="modalTitle">
                    <i class="fas fa-folder-open mr-2"></i>{{ __('Student Documents') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div id="profileDetails" class="mb-4 bg-light p-3 rounded shadow-sm">
                    <h6 class="font-weight-bold border-bottom pb-2 mb-3 mt-0 h6 text-uppercase small text-muted">{{ __('Personal Information') }}</h6>
                    <div id="studentInfo" class="row"></div>
                </div>
                
                <h6 class="font-weight-bold mb-3 h6"><i class="fas fa-upload mr-2 text-info"></i>{{ __('Upload New Document') }}</h6>
                <form id="uploadForm" enctype="multipart/form-data" class="bg-light p-3 rounded mb-4 shadow-xs">
                    <input type="hidden" name="student_id" id="doc_student_id">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold">{{ __('Document Title') }}</label>
                                <input type="text" name="doc_name" class="form-control py-2 h-auto mt-0" placeholder="{{ __('e.g. Birth Certificate') }}" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold">{{ __('Select File') }}</label>
                                <input type="file" name="document" class="form-control-file p-1 border rounded" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-block shadow-sm">{{ __('Upload') }}</button>
                        </div>
                    </div>
                </form>
                
                <h6 class="font-weight-bold mb-3 h6"><i class="fas fa-file-invoice mr-2 text-info"></i>{{ __('Attached Documents') }}</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Uploaded Date') }}</th>
                                <th width="100" class="text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="docList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .select2-container--bootstrap4 .select2-selection {
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
    #profilesTable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #4a5568;
    }
    .modal-lg { max-width: 800px; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
</style>
@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        // Initialize DataTable
        let urlParams = new URLSearchParams(window.location.search);
        let searchTerm = urlParams.get('search') || '';

        let table = $('#profilesTable').DataTable({
            processing: true,
            search: {
                search: searchTerm
            },
            language: {
                search: "_INPUT_",
                searchPlaceholder: "{{ __('Search students...') }}"
            },
            ajax: {
                url: "{{ route('student-profiles.index') }}",
                data: function (d) {
                    d.grade_id = $('#filter_grade_id').val();
                    d.school_id = $('#filter_school_id').val();
                },
                dataSrc: ""
            },
            columns: [
                {
                    data: 'registration_number',
                    render: function(data) {
                        return `<span class="badge badge-pill badge-info px-3 py-2 font-weight-normal shadow-xs">${data || "{{ __('N/A') }}"}</span>`;
                    }
                },
                {
                    data: 'school',
                    render: function(data) {
                        return data ? `<span class="badge badge-light border text-muted">${data.name}</span>` : '-';
                    }
                },
                {
                    data: 'name',
                    render: function(data) {
                        return `<div class="font-weight-bold text-dark">${data}</div>`;
                    }
                },
                {
                    data: 'grade',
                    render: function(data) {
                        return data ? `<span class="text-primary"><i class="fas fa-chalkboard-teacher mr-1 small"></i>${data.name} - ${data.section}</span>` : "{{ __('N/A') }}";
                    }
                },
                {
                    data: 'documents',
                    className: 'text-center',
                    render: function(data) {
                        return `<span class="badge badge-secondary py-1 px-3">${data.length} {{ __('Docs') }}</span>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function (data) {
                        return `
                            <button class="btn btn-outline-info btn-sm viewDocs shadow-xs border-0" data-id="${data}">
                                <i class="fas fa-folder-open mr-1"></i> {{ __('Manage Docs') }}
                            </button>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            dom: '<"d-flex justify-content-between mb-3"lf>rt<"d-flex justify-content-between mt-3"ip>'
        });

        $('#filterForm').submit(function(e) { e.preventDefault(); table.ajax.reload(); });
        $('#resetFilter').click(function() { 
            $('#filterForm').trigger("reset"); 
            $('#filter_grade_id').val('').trigger('change'); 
            $('#filter_school_id').val('').trigger('change');
            table.ajax.reload(); 
        });

        // Use delegated event binding for dynamically loaded buttons
        $('body').on('click', '.viewDocs', function() {
            let id = $(this).data('id');
            $.get(`/admin/student-profiles/${id}`, function(data) {
                $('#doc_student_id').val(data.id);
                $('#modalTitle').html(`<i class="fas fa-folder-open mr-2 text-white"></i> {{ __('Documents for') }} ${data.name}`);
                
                $('#studentInfo').html(`
                    <div class="col-md-3 mb-2"><span class="text-muted small d-block">{{ __('Email') }}</span> <strong>${data.email}</strong></div>
                    <div class="col-md-3 mb-2"><span class="text-muted small d-block">{{ __('Reg #') }}</span> <strong>${data.registration_number || 'N/A'}</strong></div>
                    <div class="col-md-3 mb-2"><span class="text-muted small d-block">{{ __('Session') }}</span> <strong>${data.session_year || 'N/A'}</strong></div>
                    <div class="col-md-3 mb-2"><span class="text-muted small d-block">{{ __('Date of Birth') }}</span> <strong>${data.dob || '{{ __('N/A') }}'}</strong></div>
                `);
                
                let docRows = '';
                data.documents.forEach(doc => {
                    docRows += `
                        <tr id="doc_${doc.id}">
                            <td class="font-weight-bold text-dark"><i class="far fa-file-alt mr-2 text-muted"></i>${doc.name}</td>
                            <td>${new Date(doc.created_at).toLocaleDateString()}</td>
                            <td class="text-center">
                                <a href="${doc.document_url}" target="_blank" class="btn btn-sm btn-outline-success border-0"><i class="fas fa-eye"></i></a>
                                <button class="btn btn-sm btn-outline-danger border-0 deleteDoc" data-id="${doc.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#docList').html(docRows || '<tr><td colspan="3" class="text-center text-muted py-4">{{ __('No documents attached to this profile.') }}</td></tr>');
                $('#docsModal').modal('show');
            });
        });

        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('student-profiles.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    Swal.fire({ title: 'Uploaded!', text: data.success, type: 'success', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                    $('#uploadForm').trigger("reset");
                    let doc = data.document;
                    let newRow = `
                        <tr id="doc_${doc.id}">
                            <td class="font-weight-bold text-dark"><i class="far fa-file-alt mr-2 text-muted"></i>${doc.name}</td>
                            <td>${new Date().toLocaleDateString()}</td>
                            <td class="text-center">
                                <a href="${doc.document_url}" target="_blank" class="btn btn-sm btn-outline-success border-0"><i class="fas fa-eye"></i></a>
                                <button class="btn btn-sm btn-outline-danger border-0 deleteDoc" data-id="${doc.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    // If table was empty, replace "No documents" row
                    if($('#docList tr td[colspan="3"]').length) $('#docList').empty();
                    $('#docList').append(newRow);
                    table.ajax.reload(null, false); // Reload without resetting pagination
                },
                error: function(xhr) {
                    Swal.fire("{{ __('Error') }}", "{{ __('Upload failed. Check file size/type (Max 5MB).') }}", 'error');
                }
            });
        });

        $('body').on('click', '.deleteDoc', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: "{{ __('Delete Document?') }}",
                text: "{{ __('This file will be permanently removed.') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/admin/student-profiles/${id}`,
                        type: "DELETE",
                        success: function(data) {
                            $(`#doc_${id}`).remove();
                            if($('#docList tr').length === 0) $('#docList').html('<tr><td colspan="3" class="text-center text-muted py-4">{{ __('No documents attached to this profile.') }}</td></tr>');
                            Swal.fire({ title: "{{ __('Deleted!') }}", type: 'success', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                            table.ajax.reload(null, false);
                        }
                    });
                }
            });
        });
    });
</script>
@stop
