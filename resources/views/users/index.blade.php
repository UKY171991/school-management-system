@extends('adminlte::page')

@section('title', __('User Management'))

@section('content_header')
    <h1>{{ __('User Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('All Users') }}</h3>
                <div class="card-tools d-flex align-items-center">
                    @if(auth()->user()->isMasterAdmin())
                    <div class="mr-2" style="width: 200px;">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm" id="newUserBtn">
                        <i class="fas fa-plus"></i> {{ __('Add User') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="usersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('School') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="userList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add User') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="user_id">
                    <div class="form-group">
                        <label>{{ __('Full Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="{{ __('Enter full name') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Email Address') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" required placeholder="{{ __('Enter email address') }}">
                    </div>
                    <div class="form-group">
                        <label id="passwordLabel">{{ __('Password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('Enter password') }}">
                        <small class="text-muted" id="passwordHelp">{{ __('Leave blank to keep existing password when editing.') }}</small>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Role') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="role_id" id="role_id" required style="width: 100%;">
                            <option value="">{{ __('Select Role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(auth()->user()->isMasterAdmin())
                    <div class="form-group">
                        <label>{{ __('School') }}</label>
                        <select class="form-control select2" name="school_id" id="school_id" style="width: 100%;">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save User') }}</button>
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

        function loadUsers() {
            let schoolId = $('#filter_school_id').val();
            $.get("{{ route('users.index') }}", { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(u => {
                    let schoolName = u.school ? `<span class="badge badge-light border">${u.school.name}</span>` : '<span class="text-muted small">{{ __('None') }}</span>';
                    let roleName = u.role ? `<span class="badge badge-info">${u.role.name}</span>` : '<span class="text-muted small">{{ __('None') }}</span>';

                    rows += `
                        <tr id="user_${u.id}">
                            <td>${u.name}</td>
                            <td>${u.email}</td>
                            <td>${roleName}</td>
                            <td>${schoolName}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editUser" data-id="${u.id}" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteUser" data-id="${u.id}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                if ($.fn.DataTable.isDataTable('#usersTable')) {
                    $('#usersTable').DataTable().destroy();
                }
                $('#userList').html(rows);
                $('#usersTable').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "order": [[ 0, "asc" ]]
                });
            });
        }

        loadUsers();

        $('#filter_school_id').change(function() {
            loadUsers();
        });

        $('#newUserBtn').click(function() {
            $('#userForm').trigger("reset");
            $('#user_id').val('');
            $('#school_id').val('').trigger('change');
            $('#role_id').val('').trigger('change');
            $('#password').attr('required', true);
            $('#passwordLabel .text-danger').show();
            $('#passwordHelp').hide();
            $('.modal-title').text("{{ __('Add User') }}");
            $('#userModal').modal('show');
        });

        $('#userForm').submit(function(e) {
            e.preventDefault();
            let id = $('#user_id').val();
            let url = id ? `/admin/users/${id}` : "{{ route('users.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#userModal').modal('hide');
                    loadUsers();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + '<br>';
                        });
                        Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                    } else {
                        Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                    }
                }
            });
        });

        $('body').on('click', '.editUser', function() {
            let id = $(this).data('id');
            $.get(`/admin/users/${id}`, function(data) {
                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#school_id').val(data.school_id).trigger('change');
                $('#role_id').val(data.role_id).trigger('change');
                
                $('#password').attr('required', false);
                $('#passwordLabel .text-danger').hide();
                $('#passwordHelp').show();
                
                $('.modal-title').text("{{ __('Edit User') }}");
                $('#userModal').modal('show');
            });
        });

        $('body').on('click', '.deleteUser', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You won\'t be able to revert this!') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/admin/users/${id}`,
                        type: "DELETE",
                        success: function(data) {
                            loadUsers();
                            Swal.fire("{{ __('Deleted!') }}", data.success, 'success');
                        },
                        error: function(xhr) {
                            let errorMsg = xhr.responseJSON.error || "{{ __('Something went wrong') }}";
                            Swal.fire("{{ __('Error') }}", errorMsg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>

<style>
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        height: calc(2.25rem + 2px) !important;
    }
</style>
@stop
