@extends('adminlte::page')

@section('title', __('Schools'))

@section('content_header')
    <h1>{{ __('Schools Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('List of Schools') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="createNewSchool">
                        <i class="fas fa-plus"></i> {{ __('Add New School') }}
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0" id="schoolsTable">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Logo') }}</th>
                                @if(auth()->user()->isMasterAdmin())
                                <th>{{ __('Admin Name') }}</th>
                                @endif
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Branches') }}</th>
                                <th>{{ __('Address') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Domain Name') }}</th>
                                <th>{{ __('Signature') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="schoolList">
                            <!-- <tr>
                                <td colspan="11" class="text-center">
                                    <i class="fas fa-spinner fa-spin"></i> {{ __('Loading...') }}
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Modal -->
<div class="modal fade" id="schoolModal" tabindex="-1" role="dialog" aria-labelledby="schoolModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="schoolForm" name="schoolForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="schoolModalLabel">{{ __('Add School') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="school_id" id="school_id">
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group">
                        <label for="admin_id">{{ __('Assign Admin') }}</label>
                        <select class="form-control select2" id="admin_id" name="admin_id" style="width: 100%;">
                            <option value="">{{ __('Choose Admin...') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('Only admins without a school or currently assigned to this school are shown.') }}</small>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="address">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="domain_name">{{ __('Domain Name') }}</label>
                        <input type="text" class="form-control" id="domain_name" name="domain_name" placeholder="e.g. school.example.com">
                    </div>
                    <div class="form-group">
                        <label for="logo">{{ __('School Logo') }}</label>
                        <input type="file" class="form-control" id="logo" name="logo">
                        <div id="logo-preview" class="mt-2"></div>
                    </div>
                    <div class="form-group">
                        <label for="principal_signature">{{ __('Principal Signature') }}</label>
                        <input type="file" class="form-control" id="principal_signature" name="principal_signature">
                        <div id="signature-preview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View School Modal -->
<div class="modal fade" id="viewSchoolModal" tabindex="-1" role="dialog" aria-labelledby="viewSchoolModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSchoolModalLabel">{{ __('View School Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">{{ __('Name') }}</th>
                        <td id="view_name"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Branches') }}</th>
                        <td id="view_branches"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Address') }}</th>
                        <td id="view_address"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Phone') }}</th>
                        <td id="view_phone"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Email') }}</th>
                        <td id="view_email"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Domain Name') }}</th>
                        <td id="view_domain_name"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Logo') }}</th>
                        <td id="view_logo"></td>
                    </tr>
                    <tr>
                        <th>{{ __('Signature') }}</th>
                        <td id="view_signature"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    /* Card body padding */
    .card-body.p-0 {
        padding: 0 !important;
    }
    
    /* Responsive table improvements */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    #schoolsTable {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    
    #schoolsTable th,
    #schoolsTable td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }
    
    #schoolsTable th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }
    
    #schoolsTable td {
        white-space: nowrap;
    }
    
    /* Allow wrapping for specific columns */
    @if(auth()->user()->isMasterAdmin())
    #schoolsTable td:nth-child(4), /* Name */
    #schoolsTable td:nth-child(6), /* Address */
    #schoolsTable td:nth-child(8), /* Email */
    #schoolsTable td:nth-child(9)  /* Domain */
    @else
    #schoolsTable td:nth-child(3), /* Name */
    #schoolsTable td:nth-child(5), /* Address */
    #schoolsTable td:nth-child(7), /* Email */
    #schoolsTable td:nth-child(8)  /* Domain */
    @endif
    {
        white-space: normal;
        word-wrap: break-word;
        max-width: 200px;
    }
    
    /* Ensure images don't break layout */
    #schoolsTable img {
        max-width: 50px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
    
    /* Improve badge appearance */
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        white-space: nowrap;
        display: inline-block;
        margin: 0.1rem;
    }
    
    /* Better button spacing */
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Scrollbar styling */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* DataTables wrapper styling */
    .dataTables_wrapper {
        padding: 1rem;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_length select {
        margin: 0 0.5rem;
        padding: 0.25rem 0.5rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5rem;
        padding: 0.25rem 0.5rem;
    }
    
    .dataTables_wrapper .dataTables_info {
        padding-top: 1rem;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        #schoolsTable th,
        #schoolsTable td {
            font-size: 0.8rem;
            padding: 0.5rem 0.25rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }
    }
    
    @media (max-width: 576px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: center;
            margin-bottom: 0.5rem;
        }
    }
</style>
@stop

@section('js')
<script>
    $(function () {
        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load Schools
        function loadSchools() {
            $.get("{{ route('schools.index') }}", function (data) {
                let rows = '';
                data.forEach(school => {
                    let logo = school.logo_url ? `<img src="${school.logo_url}" width="50" class="img-thumbnail">` : "{{ __('No Logo') }}";
                    let signature = school.signature_url ? `<img src="${school.signature_url}" width="50" class="img-thumbnail">` : "{{ __('No Signature') }}";
                    let adminName = school.admin ? `<span class="badge badge-info">${school.admin.name}</span>` : `<span class="text-muted">{{ __('Not Assigned') }}</span>`;
                    
                    // Branches information
                    let branchesHtml = '';
                    if (school.branches && school.branches.length > 0) {
                        let activeBranches = school.branches.filter(b => b.is_active).length;
                        let mainBranch = school.branches.find(b => b.is_main);
                        
                        branchesHtml = `
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                <span class="badge badge-primary badge-sm">${school.branches.length} {{ __('Total') }}</span>
                                <span class="badge badge-success badge-sm">${activeBranches} {{ __('Active') }}</span>
                            </div>
                        `;
                        
                        if (mainBranch) {
                            branchesHtml += `<small class="text-muted d-block text-truncate" style="max-width: 150px;" title="${mainBranch.name}"><i class="fas fa-star text-warning"></i> ${mainBranch.name}</small>`;
                        }
                        
                        branchesHtml += `<a href="/admin/branches?school_id=${school.id}" class="btn btn-xs btn-outline-primary mt-1" style="font-size: 0.75rem; padding: 0.15rem 0.4rem;"><i class="fas fa-eye"></i> {{ __('View') }}</a>`;
                    } else {
                        branchesHtml = `
                            <span class="text-muted small">{{ __('No branches') }}</span><br>
                            <a href="/admin/branches" class="btn btn-xs btn-outline-success mt-1" style="font-size: 0.75rem; padding: 0.15rem 0.4rem;"><i class="fas fa-plus"></i> {{ __('Add') }}</a>
                        `;
                    }
                    
                    rows += `
                        <tr id="school_${school.id}">
                            <td>${school.id}</td>
                            <td>${logo}</td>
                            @if(auth()->user()->isMasterAdmin())
                            <td>${adminName}</td>
                            @endif
                            <td>${school.name}</td>
                            <td>${branchesHtml}</td>
                            <td>${school.address || ''}</td>
                            <td>${school.phone || ''}</td>
                            <td>${school.email || ''}</td>
                            <td>${school.domain_name || ''}</td>
                            <td>${signature}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-info viewSchool" data-id="${school.id}" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning editSchool" data-id="${school.id}" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger deleteSchool" data-id="${school.id}" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#schoolsTable')) {
                    $('#schoolsTable').DataTable().destroy();
                }
                
                $('#schoolList').html(rows);
                
                // Initialize DataTable with responsive settings
                $('#schoolsTable').DataTable({
                    responsive: false,
                    autoWidth: true,
                    scrollX: false,
                    processing: true,
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                         '<"row"<"col-sm-12"tr>>' +
                         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    columnDefs: [
                        { targets: [1, -2], orderable: false, className: 'text-center', width: '80px' }, // Logo, Signature
                        { targets: -1, orderable: false, className: 'text-center', width: '120px' }, // Actions
                        { targets: 0, width: '50px' }, // ID
                        @if(auth()->user()->isMasterAdmin())
                        { targets: 2, width: '120px' }, // Admin Name
                        { targets: 3, width: '150px' }, // Name
                        { targets: 4, width: '150px' }, // Branches
                        @else
                        { targets: 2, width: '150px' }, // Name
                        { targets: 3, width: '150px' }, // Branches
                        @endif
                        { targets: '_all', className: 'align-middle' }
                    ],
                    order: [[0, 'desc']], // Sort by ID descending
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    language: {
                        search: "{{ __('Search') }}:",
                        lengthMenu: "{{ __('Show') }} _MENU_ {{ __('entries') }}",
                        info: "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
                        infoEmpty: "{{ __('No entries to show') }}",
                        infoFiltered: "({{ __('filtered from') }} _MAX_ {{ __('total entries') }})",
                        zeroRecords: "{{ __('No matching records found') }}",
                        emptyTable: "{{ __('No data available in table') }}",
                        paginate: {
                            first: "{{ __('First') }}",
                            last: "{{ __('Last') }}",
                            next: "{{ __('Next') }}",
                            previous: "{{ __('Previous') }}"
                        }
                    }
                });
            });
        }

        loadSchools();

        // Open Create Modal
        $('#createNewSchool').click(function () {
            $('#schoolForm').trigger("reset");
            $('#schoolModalLabel').html("{{ __('Add New School') }}");
            $('#school_id').val('');
            @if(auth()->user()->isMasterAdmin())
                $('#admin_id').val('').trigger('change');
            @endif
            $('#logo-preview, #signature-preview').empty();
            $('#schoolModal').modal('show');
        });

// Save School (Create/Update)
        $('#schoolForm').submit(function (e) {
            e.preventDefault();
            let id = $('#school_id').val();
            let url = id ? `/admin/schools/${id}` : "{{ route('schools.store') }}";
            
            // For Laravel to handle PUT with files, we use POST and _method=PUT
            let formData = new FormData(this);
            if (id) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                data: formData,
                url: url,
                type: "POST", // Always POST for file uploads, Laravel handles spoofing if needed
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#schoolForm').trigger("reset");
                    $('#schoolModal').modal('hide');
                    loadSchools();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function (data) {
                    console.log('Error:', data);
                    let errors = data.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value + '<br>';
                    });
                    Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                }
            });
        });

        // Edit School
        $('body').on('click', '.editSchool', function () {
            let id = $(this).data('id');
            $.get(`/admin/schools/${id}`, function (data) {
                $('#schoolModalLabel').html("{{ __('Edit School') }}");
                $('#school_id').val(data.id);
                $('#name').val(data.name);
                $('#address').val(data.address);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#domain_name').val(data.domain_name);
                
                @if(auth()->user()->isMasterAdmin())
                    if (data.admin) {
                        $('#admin_id').val(data.admin.id).trigger('change');
                    } else {
                        $('#admin_id').val('').trigger('change');
                    }
                @endif
                
                if (data.logo_url) {
                    $('#logo-preview').html(`
                        <div class="position-relative d-inline-block">
                            <img src="${data.logo_url}" width="100" class="img-thumbnail">
                            <button type="button" class="btn btn-danger btn-xs delete-asset" data-id="${data.id}" data-type="logo" style="position:absolute; top: -5px; right: -5px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                } else {
                    $('#logo-preview').empty();
                }

                if (data.signature_url) {
                    $('#signature-preview').html(`
                        <div class="position-relative d-inline-block">
                            <img src="${data.signature_url}" width="100" class="img-thumbnail">
                            <button type="button" class="btn btn-danger btn-xs delete-asset" data-id="${data.id}" data-type="principal_signature" style="position:absolute; top: -5px; right: -5px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);
                } else {
                    $('#signature-preview').empty();
                }
                
                $('#schoolModal').modal('show');
            });
        });

        // View School
        $('body').on('click', '.viewSchool', function () {
            let id = $(this).data('id');
            $.get(`/admin/schools/${id}`, function (data) {
                $('#view_name').text(data.name);
                $('#view_address').text(data.address || 'N/A');
                $('#view_phone').text(data.phone || 'N/A');
                $('#view_email').text(data.email || 'N/A');
                $('#view_domain_name').text(data.domain_name || 'N/A');

                // Display branches
                if (data.branches && data.branches.length > 0) {
                    let branchesHtml = '<ul class="list-unstyled mb-0">';
                    data.branches.forEach(branch => {
                        let mainBadge = branch.is_main ? '<span class="badge badge-warning ml-1">Main</span>' : '';
                        let statusBadge = branch.is_active ? '<span class="badge badge-success ml-1">Active</span>' : '<span class="badge badge-secondary ml-1">Inactive</span>';
                        branchesHtml += `
                            <li class="mb-2">
                                <strong>${branch.name}</strong> ${mainBadge} ${statusBadge}
                                ${branch.code ? `<br><small class="text-muted">Code: ${branch.code}</small>` : ''}
                                ${branch.address ? `<br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> ${branch.address}</small>` : ''}
                            </li>
                        `;
                    });
                    branchesHtml += '</ul>';
                    branchesHtml += `<a href="/admin/branches?school_id=${data.id}" class="btn btn-sm btn-primary mt-2"><i class="fas fa-eye"></i> {{ __('View All Branches') }}</a>`;
                    $('#view_branches').html(branchesHtml);
                } else {
                    $('#view_branches').html('<span class="text-muted">{{ __("No branches created") }}</span><br><a href="/admin/branches" class="btn btn-sm btn-success mt-2"><i class="fas fa-plus"></i> {{ __("Add Branch") }}</a>');
                }

                if (data.logo_url) {
                    $('#view_logo').html(`<img src="${data.logo_url}" width="100" class="img-thumbnail">`);
                } else {
                    $('#view_logo').text("{{ __('No Logo') }}");
                }

                if (data.signature_url) {
                    $('#view_signature').html(`<img src="${data.signature_url}" width="100" class="img-thumbnail">`);
                } else {
                    $('#view_signature').text("{{ __('No Signature') }}");
                }

                $('#viewSchoolModal').modal('show');
            });
        });

        // Delete School
        $('body').on('click', '.deleteSchool', function () {
            let id = $(this).data('id');
            if (confirm("{{ __('Are you sure you want to delete this school?') }}")) {
                $.ajax({
                    type: "DELETE",
                    url: `/admin/schools/${id}`,
                    success: function (data) {
                        $(`#school_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
        
        // Delete Asset (Logo/Signature)
        $('body').on('click', '.delete-asset', function () {
            let id = $(this).data('id');
            let type = $(this).data('type');
            let container = $(this).closest('.form-group').find('.mt-2');

            if (confirm("{{ __('Are you sure?') }}")) {
                $.ajax({
                    type: "DELETE",
                    url: `/admin/schools/${id}/delete-asset`,
                    data: { type: type },
                    success: function (data) {
                        container.empty();
                        loadSchools();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        Swal.fire("{{ __('Error') }}", "{{ __('Failed to delete asset') }}", 'error');
                    }
                });
            }
        });
    });
</script>
@stop
