@extends('adminlte::page')

@section('title', __('Branch Management'))

@section('content_header')
    <h1>{{ __('Branch Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('All Branches') }}</h3>
                <div class="card-tools d-flex align-items-center">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="mr-2" style="width: 250px;">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Schools') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm" id="newBranchBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Branch') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="branchesTable">
                    <thead>
                        <tr>
                            <th>{{ __('School Name') }}</th>
                            <th>{{ __('Branch Name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Contact') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="branchList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Branch Modal -->
<div class="modal fade" id="branchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="branchForm">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Branch') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="branch_id">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group">
                        <label>{{ __('Select School') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->school_id }}">{{ $admin->name }} ({{ $admin->school ? $admin->school->name : __('No School') }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="school_id" id="school_id" value="{{ auth()->user()->school_id }}">
                    @endif
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>{{ __('Branch Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('e.g. Main Campus, North Branch') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Branch Code') }}</label>
                                <input type="text" class="form-control" name="code" id="code" placeholder="{{ __('e.g. MC, NB') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Address') }}</label>
                        <textarea class="form-control" name="address" id="address" rows="2" placeholder="{{ __('Branch address') }}"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Phone') }}</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="{{ __('Contact number') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Email') }}</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('Branch email') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="is_main" id="is_main" value="1">
                                    <label class="custom-control-label" for="is_main">{{ __('Main Branch') }}</label>
                                </div>
                                <small class="text-muted">{{ __('Only one branch can be set as main') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1" checked>
                                    <label class="custom-control-label" for="is_active">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Branch') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        function loadBranches() {
            let schoolId = $('#filter_school_id').val();
            console.log('Loading branches for school:', schoolId);
            
            $.get("{{ route('branches.index') }}", { school_id: schoolId }, function (data) {
                console.log('Branches loaded:', data);
                let rows = '';
                
                if (data.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center text-muted">{{ __("No branches found. Click Add Branch to create one.") }}</td></tr>';
                } else {
                    data.forEach(b => {
                        let schoolName = b.school ? `<span class="badge badge-light border">${b.school.name}</span>` : '';
                        let mainBadge = b.is_main ? '<span class="badge badge-primary ml-1">Main</span>' : '';
                        let statusBadge = b.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>';
                        
                        rows += `
                            <tr id="branch_${b.id}">
                                <td>${schoolName}</td>
                                <td>
                                    <strong>${b.name}</strong>
                                    ${mainBadge}
                                </td>
                                <td>${b.code || '<span class="text-muted">-</span>'}</td>
                                <td>
                                    ${b.phone ? `<div><i class="fas fa-phone mr-1"></i>${b.phone}</div>` : ''}
                                    ${b.email ? `<div><i class="fas fa-envelope mr-1"></i>${b.email}</div>` : ''}
                                    ${!b.phone && !b.email ? '<span class="text-muted">-</span>' : ''}
                                </td>
                                <td>${b.address || '<span class="text-muted">-</span>'}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-xs btn-warning editBranch" data-id="${b.id}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></button>
                                    ${!b.is_main ? `<button class="btn btn-xs btn-danger deleteBranch" data-id="${b.id}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>` : ''}
                                </td>
                            </tr>
                        `;
                    });
                }
                
                if ($.fn.DataTable.isDataTable('#branchesTable')) {
                    $('#branchesTable').DataTable().destroy();
                }
                $('#branchList').html(rows);
                $('#branchesTable').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "order": [[ 0, "asc" ]]
                });
            }).fail(function(xhr, status, error) {
                console.error('Error loading branches:', error);
                Swal.fire("{{ __('Error') }}", "{{ __('Failed to load branches') }}", 'error');
            });
        }

        loadBranches();

        $('#filter_school_id').change(function() {
            loadBranches();
        });

        $('#newBranchBtn').click(function() {
            $('#branchForm').trigger("reset");
            $('#branch_id').val('');
            @if(auth()->user()->isMasterAdmin())
                $('#school_id').val('').trigger('change');
            @endif
            $('#is_active').prop('checked', true);
            $('.modal-title').text("{{ __('Add Branch') }}");
            $('#branchModal').modal('show');
        });

        $('#branchForm').submit(function(e) {
            e.preventDefault();
            let id = $('#branch_id').val();
            let url = id ? `/admin/branches/${id}` : "{{ route('branches.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#branchModal').modal('hide');
                    loadBranches();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors || xhr.responseJSON;
                        let errorMsg = '';
                        if (errors.error) {
                            errorMsg = errors.error;
                        } else {
                            $.each(errors, function(key, value) {
                                errorMsg += value[0] + '<br>';
                            });
                        }
                        Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                    } else {
                        Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editBranch', function() {
            let id = $(this).data('id');
            $.get(`/admin/branches/${id}`, function(data) {
                $('#branch_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#name').val(data.name);
                $('#code').val(data.code);
                $('#address').val(data.address);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#is_main').prop('checked', data.is_main);
                $('#is_active').prop('checked', data.is_active);
                $('.modal-title').text("{{ __('Edit Branch') }}");
                $('#branchModal').modal('show');
            });
        });

        $('body').on('click', '.deleteBranch', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this branch?') }}")) {
                $.ajax({
                    url: `/admin/branches/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#branch_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON.error || "{{ __('Something went wrong') }}";
                        Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                    }
                });
            }
        });
    });
</script>
@stop
