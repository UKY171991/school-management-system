@extends('adminlte::page')

@section('title', __('Hostel Rooms'))

@section('content_header')
    <h1>{{ __('Hostel Room Management') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title">{{ __('List of Rooms') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-secondary btn-sm" id="newRoomBtn">
                        <i class="fas fa-plus"></i> {{ __('Add New Room') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Hostel') }}</th>
                            <th>{{ __('Room Number') }}</th>
                            <th>{{ __('Capacity') }}</th>
                            <th width="150">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="roomList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Room Modal -->
<div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="roomForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('Add Room') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="room_id">
                    <div class="form-group">
                        <label>{{ __('Select Hostel') }}</label>
                        <select class="form-control" name="hostel_id" id="hostel_id" required>
                            @foreach(\App\Models\Hostel::all() as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }} ({{ $hostel->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Room Number') }}</label>
                        <input type="text" class="form-control" name="room_number" id="room_number" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Capacity (Beds)') }}</label>
                        <input type="number" class="form-control" name="capacity" id="capacity" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-secondary">{{ __('Save Room') }}</button>
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

        var baseUrl = "{{ route('hostel-rooms.index') }}";

        function loadRooms() {
            $.get(baseUrl, function (data) {
                let rows = '';
                data.forEach(r => {
                    let hostelName = r.hostel ? r.hostel.name : "{{ __('N/A') }}";
                    rows += `
                        <tr id="room_${r.id}">
                            <td>${hostelName}</td>
                            <td>${r.room_number}</td>
                            <td>${r.capacity}</td>
                            <td>
                                <button class="btn btn-xs btn-warning editRoom" data-id="${r.id}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger deleteRoom" data-id="${r.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('#roomList').html(rows);
            });
        }

        loadRooms();

        $('#newRoomBtn').click(function() {
            $('#roomForm').trigger("reset");
            $('#room_id').val('');
            $('#modalTitle').text("{{ __('Add Room') }}");
            $('#roomModal').modal('show');
        });

        $('#roomForm').submit(function(e) {
            e.preventDefault();
            let id = $('#room_id').val();
            let url = id ? baseUrl + '/' + id : baseUrl;
            let type = id ? "PUT" : "POST";

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(data) {
                    $('#roomModal').modal('hide');
                    loadRooms();
                    Swal.fire("{{ __('Success') }}", data.success, 'success');
                }
            });
        });

        $('body').on('click', '.editRoom', function() {
            let id = $(this).data('id');
            $.get(baseUrl + '/' + id, function(data) {
                $('#room_id').val(data.id);
                $('#hostel_id').val(data.hostel_id);
                $('#room_number').val(data.room_number);
                $('#capacity').val(data.capacity);
                $('#modalTitle').text("{{ __('Edit Room') }}");
                $('#roomModal').modal('show');
            });
        });

        $('body').on('click', '.deleteRoom', function() {
            let id = $(this).data('id');
            if(confirm("{{ __('Delete this room?') }}")) {
                $.ajax({
                    url: baseUrl + '/' + id,
                    type: "DELETE",
                    success: function(data) {
                        $(`#room_${id}`).remove();
                        Swal.fire("{{ __('Deleted') }}", data.success, 'success');
                    }
                });
            }
        });
    });
</script>
@stop
