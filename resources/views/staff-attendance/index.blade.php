@extends('adminlte::page')

@section('title', __('Staff Attendance & Leave'))

@section('content_header')
    <h1>{{ __('Attendance & Leave Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('Leave Requests') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" id="newLeaveBtn">
                        <i class="fas fa-plus"></i> {{ __('Submit Leave Request') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="leavesTable">
                    <thead>
                        <tr>
                            <th>{{ __('Teacher') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Dates') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th width="150">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="leaveList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Leave Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="leaveForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Leave Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="leave_id">
                    <div class="form-group">
                        <label>{{ __('Teacher') }}</label>
                        <select class="form-control" name="teacher_id" id="teacher_id" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Leave Type') }}</label>
                        <select class="form-control" name="leave_type" id="leave_type" required>
                            <option value="Sick Leave">{{ __('Sick Leave') }}</option>
                            <option value="Casual Leave">{{ __('Casual Leave') }}</option>
                            <option value="Vacation">{{ __('Vacation') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('End Date') }}</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Reason') }}</label>
                        <textarea class="form-control" name="reason" id="reason" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Submit Request') }}</button>
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

        function loadLeaves() {
            $.get("{{ route('staff-attendance.index') }}", function (data) {
                let rows = '';
                if(data.length === 0) {
                    rows = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-calendar-times mr-2"></i>{{ __('No leave requests found') }}</td></tr>';
                } else {
                    data.forEach(l => {
                        let statusClass = l.status == 'approved' ? 'success' : (l.status == 'rejected' ? 'danger' : 'warning');
                        let teacherName = l.teacher ? l.teacher.name : 'Unknown';
                        rows += `
                            <tr id="leave_${l.id}">
                                <td>${teacherName}</td>
                                <td>${l.leave_type}</td>
                                <td>${l.start_date} to ${l.end_date}</td>
                                <td><span class="badge badge-${statusClass}">${l.status.toUpperCase()}</span></td>
                                <td>
                                    ${l.status == 'pending' ? `
                                        <button class="btn btn-xs btn-success approveLeave" data-id="${l.id}">{{ __('Approve') }}</button>
                                        <button class="btn btn-xs btn-danger rejectLeave" data-id="${l.id}">{{ __('Reject') }}</button>
                                    ` : ''}
                                    <button class="btn btn-xs btn-warning editLeave" data-id="${l.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-xs btn-outline-danger deleteLeave" data-id="${l.id}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                }
                if ($.fn.DataTable.isDataTable('#leavesTable')) {
                    $('#leavesTable').DataTable().destroy();
                }
                $('#leaveList').html(rows);
                $('#leavesTable').DataTable({
                    "responsive": true, 
                    "autoWidth": false
                });
            });
        }

        loadLeaves();

        $('#newLeaveBtn').click(function() {
            $('#leaveForm').trigger("reset");
            $('#leave_id').val('');
            $('#modalTitle').text('{{ __('Submit Leave Request') }}');
            $('#leaveModal').modal('show');
        });

        $('#leaveForm').submit(function(e) {
            e.preventDefault();
            let id = $('#leave_id').val();
            let url = id ? `/admin/staff-attendance/${id}` : "{{ route('staff-attendance.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#leaveModal').modal('hide');
                    loadLeaves();
                    Swal.fire('{{ __('Success') }}', data.success, 'success');
                },
                error: function(xhr) {
                   if(xhr.status === 422) {
                       let errors = xhr.responseJSON.errors;
                       let msg = '';
                       $.each(errors, function(key, val){ msg += val[0] + '<br>'; });
                       Swal.fire('{{ __('Validation Error') }}', msg, 'error');
                   } else {
                       Swal.fire('{{ __('Error') }}', '{{ __('Something went wrong') }}', 'error');
                   }
                }
            });
        });

        $('body').on('click', '.editLeave', function() {
            let id = $(this).data('id');
            $.get(`/admin/staff-attendance/${id}`, function(data) {
                $('#leave_id').val(data.id);
                $('#teacher_id').val(data.teacher_id);
                $('#leave_type').val(data.leave_type);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#reason').val(data.reason);
                $('#modalTitle').text('{{ __('Edit Leave Request') }}');
                $('#leaveModal').modal('show');
            });
        });

        $('body').on('click', '.approveLeave', function() {
            let id = $(this).data('id');
            updateStatus(id, 'approved');
        });

        $('body').on('click', '.rejectLeave', function() {
            let id = $(this).data('id');
            updateStatus(id, 'rejected');
        });

        function updateStatus(id, status) {
            $.ajax({
                url: `/admin/staff-attendance/${id}`,
                type: "PUT",
                data: { status: status },
                success: function(data) {
                    loadLeaves();
                    Swal.fire('{{ __('Updated') }}', data.success, 'success');
                }
            });
        }

        $('body').on('click', '.deleteLeave', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete record?') }}")) {
                $.ajax({
                    url: `/admin/staff-attendance/${id}`,
                    type: "DELETE",
                    success: function(data) {
                        $(`#leave_${id}`).remove();
                        Swal.fire('{{ __('Deleted') }}', data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
