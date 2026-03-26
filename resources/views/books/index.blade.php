@extends('adminlte::page')

@section('title', __('Library Catalog'))

@section('content_header')
    <h1>{{ __('Book Catalog') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">{{ __('Library Books') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" id="newBookBtn">
                        <i class="fas fa-plus"></i> {{ __('Add New Book') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-control select2" id="filter_school_id">
                            <option value="">{{ __('All Schools') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('School') }}</th>
                            <th>{{ __('ISBN') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Author') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="bookList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="bookForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Book') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="book_id">
                    <div class="form-group">
                        <label>{{ __('School') }} <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="school_id" id="school_id" required style="width: 100%;">
                            <option value="">{{ __('Select School') }}</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('ISBN') }}</label>
                        <input type="text" class="form-control" name="isbn" id="isbn" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Book Title') }}</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Author') }}</label>
                        <input type="text" class="form-control" name="author" id="author" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Quantity') }}</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Save Book') }}</button>
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

        var baseUrl = "{{ route('books.index') }}";

        // Initialize Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        function loadBooks() {
            let schoolId = $('#filter_school_id').val();
            $.get(baseUrl, { school_id: schoolId }, function (data) {
                let rows = '';
                data.forEach(b => {
                    let schoolName = b.school ? b.school.name : '-';
                    rows += `
                        <tr id="book_${b.id}">
                            <td><span class="badge badge-light border">${schoolName}</span></td>
                            <td>${b.isbn}</td>
                            <td>${b.title}</td>
                            <td>${b.author}</td>
                            <td>${b.quantity}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editBook" data-id="${b.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteBook" data-id="${b.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#bookList').html(rows);
            });
        }

        loadBooks();

        $('#filter_school_id').change(function() {
            loadBooks();
        });

        $('#newBookBtn').click(function() {
            $('#bookForm').trigger("reset");
            $('#school_id').val('').trigger('change');
            $('#book_id').val('');
            $('#modalTitle').text("{{ __('Add Book') }}");
            $('#bookModal').modal('show');
        });

        $('#bookForm').submit(function(e) {
            e.preventDefault();
            let id = $('#book_id').val();
            let url = id ? baseUrl + '/' + id : baseUrl;
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#bookModal').modal('hide');
                    loadBooks();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                },
                error: function(xhr) {
                    Swal.fire("{{ __('Error') }}", "{{ __('Something went wrong') }}", 'error');
                }
            });
        });

        $('body').on('click', '.editBook', function() {
            let id = $(this).data('id');
            $.get(baseUrl + '/' + id, function(data) {
                $('#book_id').val(data.id);
                $('#school_id').val(data.school_id).trigger('change');
                $('#isbn').val(data.isbn);
                $('#title').val(data.title);
                $('#author').val(data.author);
                $('#quantity').val(data.quantity);
                $('#modalTitle').text("{{ __('Edit Book') }}");
                $('#bookModal').modal('show');
            });
        });

        $('body').on('click', '.deleteBook', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Remove this book from catalog?') }}")) {
                $.ajax({
                    url: baseUrl + '/' + id,
                    type: "DELETE",
                    success: function(data) {
                        $(`#book_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
