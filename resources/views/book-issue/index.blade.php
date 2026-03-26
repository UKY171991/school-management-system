@extends('adminlte::page')

@section('title', __('Book Issue Management'))

@section('content_header')
    <h1>{{ __('Book Issue Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Issued Books List') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="newIssueBtn">
                        <i class="fas fa-plus"></i> {{ __('Issue New Book') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="issuesTable">
                    <thead>
                        <tr>
                            <th>{{ __('Book Title') }}</th>
                            <th>{{ __('Student Name') }}</th>
                            <th>{{ __('Issue Date') }}</th>
                            <th>{{ __('Return Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="issueList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Issue Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="issueForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Issue Book') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="issue_id">
                    
                    <div class="form-group">
                        <label>{{ __('Select Book') }}</label>
                        <select class="form-control select2" name="book_id" id="book_id" required style="width: 100%;">
                            <option value="">{{ __('Select Book') }}</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->isbn }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Select Student') }}</label>
                        <select class="form-control select2" name="student_id" id="student_id" required style="width: 100%;">
                            <option value="">{{ __('Select Student') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Issue Date') }}</label>
                        <input type="date" class="form-control" name="issue_date" id="issue_date" required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Due Date') }}</label>
                        <input type="date" class="form-control" name="due_date" id="due_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select class="form-control" name="status" id="status">
                            <option value="Issued">{{ __('Issued') }}</option>
                            <option value="Returned">{{ __('Returned') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
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
        $('.select2').select2();

        var baseUrl = "{{ route('book-issue.index') }}";

        function loadIssues() {
            $.get(baseUrl, function (data) {
                let rows = '';
                data.forEach(item => {
                    let isReturned = item.return_date != null;
                    let statusBadge = isReturned ? '<span class="badge badge-success">{{ __("Returned") }}</span>' : '<span class="badge badge-warning">{{ __("Issued") }}</span>';
                    let bookTitle = item.book ? item.book.title : "{{ __('N/A') }}";
                    let studentName = item.student ? (item.student.name + ' (' + item.student.roll_number + ')') : "{{ __('N/A') }}";
                    
                    rows += `
                        <tr id="issue_${item.id}">
                            <td>${bookTitle}</td>
                            <td>${studentName}</td>
                            <td>${item.issue_date}</td>
                            <td>${item.due_date}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <button class="btn btn-xs btn-info editIssue" data-id="${item.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteIssue" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#issueList').html(rows);
            });
        }

        loadIssues();

        $('#newIssueBtn').click(function() {
            $('#issueForm').trigger("reset");
            $('#issue_id').val('');
            $('#modalTitle').text("{{ __('Issue Book') }}");
            $('#book_id').val('').trigger('change');
            $('#student_id').val('').trigger('change');
            $('#status').val('Issued'); // Default
            $('#issueModal').modal('show');
        });

        $('#issueForm').submit(function(e) {
            e.preventDefault();
            let id = $('#issue_id').val();
            let url = id ? baseUrl + '/' + id : "{{ route('book-issue.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#issueModal').modal('hide');
                    loadIssues();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    let msg = "{{ __('Something went wrong') }}";
                    if(xhr.responseJSON && xhr.responseJSON.errors) {
                         msg = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    Swal.fire("{{ __('Error') }}", msg, 'error');
                }
            });
        });

        $('body').on('click', '.editIssue', function() {
            let id = $(this).data('id');
            $.get(baseUrl + '/' + id, function(data) {
                $('#issue_id').val(data.id);
                $('#book_id').val(data.book_id).trigger('change');
                $('#student_id').val(data.student_id).trigger('change');
                $('#issue_date').val(data.issue_date);
                $('#due_date').val(data.due_date);
                $('#status').val(data.return_date ? 'Returned' : 'Issued');
                $('#modalTitle').text("{{ __('Edit Issue Record') }}");
                $('#issueModal').modal('show');
            });
        });

        $('body').on('click', '.deleteIssue', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this record?') }}")) {
                $.ajax({
                    url: baseUrl + '/' + id,
                    type: "DELETE",
                    success: function(data) {
                        $(`#issue_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
