@extends('adminlte::page')

@section('title', 'Teacher Timetable - Routine View')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center d-print-none">
        <h1><i class="fas fa-calendar-alt mr-2 text-primary"></i>Teacher Timetable</h1>
        <div>
            <a href="{{ route('teacher-timetable.index', ['view' => 'list']) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm mr-2 transition-all hover:scale-105">
                <i class="fas fa-list mr-1"></i> List View
            </a>
            <button class="btn btn-primary btn-sm rounded-pill px-4 shadow transition-all hover:scale-105" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> Print Routine
            </button>
        </div>
    </div>
@stop

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
    .routine-wrapper { padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 30px; }
    .routine-title { text-align: center; font-weight: 800; text-decoration: underline; font-size: 1.8rem; margin-bottom: 30px; color: #2c3e50; text-transform: uppercase; letter-spacing: 1px; }
    .routine-table { width: 100%; border-collapse: collapse; border: 2px solid #000; table-layout: fixed; }
    .routine-table th, .routine-table td { border: 1px solid #000; padding: 10px 6px; text-align: center; vertical-align: middle; word-wrap: break-word; }
    .routine-table th { background-color: #f8f9fa; font-weight: 700; color: #000; text-transform: uppercase; font-size: 0.85rem; }
    
    .editable-cell { cursor: pointer; transition: background-color 0.2s, transform 0.1s; position: relative; }
    .editable-cell:hover { background-color: rgba(0, 123, 255, 0.08) !important; }
    .editable-cell:active { transform: scale(0.98); }
    .editable-cell::after { content: '\f303'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; top: 4px; right: 4px; font-size: 0.65rem; opacity: 0; transition: opacity 0.2s; color: #007bff; }
    .editable-cell:hover::after { opacity: 0.5; }

    .class-header { cursor: pointer; transition: color 0.2s; }
    .class-header:hover { color: #007bff; text-decoration: underline; }

    .period-name { font-weight: 800; color: #000; display: block; }
    .time-slot { font-size: 0.75rem; color: #444; font-style: italic; display: block; }
    
    .lunch-break { font-weight: 800; font-style: italic; background-color: #fefefe !important; letter-spacing: 3px; text-transform: uppercase; padding: 15px !important; font-size: 1.1rem; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important; cursor: pointer; }
    
    .cell-subject { font-weight: 800; display: block; text-transform: uppercase; margin-bottom: 2px; color: #000; font-size: 0.85rem; }
    .cell-teacher { font-size: 0.65rem; display: block; color: #333; font-weight: 600; line-height: 1.2; }
    
    .label-edit-hint { position: fixed; bottom: 20px; right: 20px; background: rgba(0,123,255,0.9); color: white; padding: 10px 20px; border-radius: 50px; font-size: 0.85rem; z-index: 1000; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }

    @media print {
        @page { size: landscape; margin: 0.5cm; }
        .main-sidebar, .main-header, .content-header, .footer, .d-print-none, .label-edit-hint { display: none !important; }
        .content-wrapper { margin: 0 !important; padding: 0 !important; background: #fff !important; }
        .routine-wrapper { box-shadow: none; padding: 0; margin: 0; border: none; }
        .routine-title { margin-bottom: 15px; font-size: 1.5rem; }
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    <div class="label-edit-hint d-print-none animate__animated animate__fadeInUp">
        <i class="fas fa-magic mr-2"></i> Click any cell, time or class to edit
    </div>

    <!-- Multi-Filter Card -->
    <div class="row d-print-none mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-body bg-light p-3">
                    <form action="{{ route('teacher-timetable.index') }}" method="GET" class="row align-items-center">
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-school text-primary"></i>
                                    </span>
                                </div>
                                <select name="school_id" class="form-control border-left-0" onchange="this.form.submit()" {{ !$isMasterAdmin ? 'disabled' : '' }}>
                                    <option value="">{{ $isMasterAdmin ? 'All Schools' : 'My School' }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                @if(!$isMasterAdmin)
                                    <input type="hidden" name="school_id" value="{{ $schoolId }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-calendar-day text-primary"></i></span>
                                </div>
                                <select name="day" class="form-control border-left-0" onchange="this.form.submit()">
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $d)
                                        <option value="{{ $d }}" {{ $selectedDay == $d ? 'selected' : '' }}>{{ $d }} Routine</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="d-inline-flex align-items-center">
                                @if($schoolId)
                                <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm mr-2" id="manageSortBtn">
                                    <i class="fas fa-sort-numeric-down mr-1"></i> Sort Classes
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm mr-2" id="addSlotBtn">
                                    <i class="fas fa-plus mr-1"></i> Add Period
                                </button>
                                @endif
                                <span class="badge badge-primary py-2 px-4 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                                    {{ $selectedDay }} Routine
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Routine Table -->
    @if($grades->count() > 0)
    <div class="routine-wrapper">
        <div class="routine-title">CLASS-WISE ROUTINE</div>
        
        <div class="table-responsive">
            <table class="routine-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 100px;">PERIOD</th>
                        <th rowspan="2" style="width: 140px;">TIME <br> <span class="small font-weight-normal">In AM</span></th>
                        @php $flatSections = []; @endphp
                        @foreach($grades as $grade)
                            @php 
                                $sectionCount = $grade->sections->count(); 
                                if($sectionCount > 0) {
                                    foreach($grade->sections as $sec) { $flatSections[] = $sec; }
                                } else {
                                    $flatSections[] = (object)['id' => null, 'name' => '-', 'grade_id' => $grade->id, 'grade' => $grade];
                                }
                            @endphp
                            <th colspan="{{ max(1, $sectionCount) }}" class="class-header {{ $sectionCount > 1 ? '' : 'align-middle' }}" {!! $sectionCount <= 1 ? 'rowspan="2"' : '' !!} data-id="{{ $grade->id }}" data-name="{{ $grade->name }}" data-sort="{{ $grade->sort_order }}">
                                {{ $grade->name }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($grades as $grade)
                            @if($grade->sections->count() > 1)
                                @foreach($grade->sections as $section)
                                    <th class="small">{{ $section->name }}</th>
                                @endforeach
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($slots as $slot)
                        @if($slot->is_break)
                            <tr class="lunch-row">
                                <td colspan="{{ 2 + count($flatSections) }}" class="lunch-break editable-slot" data-id="{{ $slot->id }}">
                                    {{ $slot->name }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="editable-slot period-name-cell" data-id="{{ $slot->id }}">
                                    <span class="period-name">{{ $slot->name }}</span>
                                </td>
                                <td class="editable-slot time-cell" data-id="{{ $slot->id }}">
                                    <span class="time-slot">{{ date('H:i', strtotime($slot->start_time)) }} - {{ date('H:i', strtotime($slot->end_time)) }}</span>
                                </td>
                                @foreach($flatSections as $section)
                                    @php
                                        $entry = $timetables->filter(function($t) use ($slot, $section) {
                                            $tStartTime = date('H:i', strtotime($t->start_time));
                                            $sStartTime = date('H:i', strtotime($slot->start_time));
                                            if($section->id) {
                                                return $t->section_id == $section->id && $tStartTime == $sStartTime;
                                            }
                                            return $t->grade_id == $section->grade_id && $tStartTime == $sStartTime;
                                        })->first();
                                    @endphp
                                    <td class="editable-cell timetable-entry-cell" 
                                        data-slot-start="{{ $slot->start_time }}" 
                                        data-slot-end="{{ $slot->end_time }}" 
                                        data-section-id="{{ $section->id ?? '' }}"
                                        data-grade-id="{{ $section->grade_id }}"
                                        data-entry-id="{{ $entry ? $entry->id : '' }}">
                                        @if($entry)
                                            <span class="cell-subject">{{ $entry->subject ? ($entry->subject->name ?? 'Subject') : '-' }}</span>
                                            <span class="cell-teacher">{{ $entry->teacher ? ($entry->teacher->name ?? 'Teacher') : '-' }}</span>
                                        @else
                                            &nbsp;
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 row d-none d-print-flex">
            <div class="col-6 text-left small text-muted">* This is a computer generated document.</div>
            <div class="col-6 text-right font-weight-bold">Principal Signature</div>
        </div>
    </div>
    @else
    @if($isMasterAdmin && !$schoolId)
    <div class="card border-0 shadow-lg rounded-lg overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-5 text-center text-white">
            <div class="mb-4" style="font-size: 4rem;"><i class="fas fa-school"></i></div>
            <h3 class="font-weight-bold mb-2">Select a School Branch</h3>
            <p class="mb-4 opacity-75" style="opacity: 0.8; font-size: 1.05rem;">Please select a school from the dropdown above to view and manage its timetable.</p>
            <div class="d-inline-block">
                <select class="form-control form-control-lg shadow" onchange="window.location.href='{{ route('teacher-timetable.index') }}?school_id='+this.value+'&day={{ $selectedDay }}'" style="min-width: 280px; font-weight: 600; border-radius: 50px; border: none;">
                    <option value="">-- Choose a School --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning shadow-sm border-0 rounded-lg p-4 text-center">
        <i class="fas fa-chalkboard fa-2x mb-2 text-warning"></i>
        <h5 class="mb-1">No Classes Found</h5>
        <p class="mb-0 small">No grades have been set up for this school yet. Please add grades under Academic Management.</p>
    </div>
    @endif
    @endif
</div>

<!-- Timetable Entry Modal (Subject/Teacher) -->
<div class="modal fade" id="timetableModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="timetableForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="timetableModalLabel">
                        <i class="fas fa-edit mr-2"></i>Edit Entry
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="timetable_id" id="timetable_id">
                    <input type="hidden" name="day" value="{{ $selectedDay }}">
                    <input type="hidden" name="section_id" id="form_section_id">
                    <input type="hidden" name="grade_id" id="form_grade_id">
                    <input type="hidden" name="school_id" id="form_school_id" value="{{ $schoolId }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Teacher <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="teacher_id" id="teacher_id" required style="width: 100%;">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-center pt-4" id="deleteEntryBtnWrapper" style="display:none;">
                            <button type="button" class="btn btn-outline-danger btn-sm" id="deleteEntryBtn">
                                <i class="fas fa-trash mr-1"></i> Delete This Entry
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="subject_id" id="subject_id" required style="width: 100%;">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-grade="{{ $subject->grade_id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                             <div class="form-group mb-4">
                                <label class="font-weight-bold">Start Time</label>
                                <input type="time" class="form-control" name="start_time" id="start_time" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group mb-4">
                                <label class="font-weight-bold">End Time</label>
                                <input type="time" class="form-control" name="end_time" id="end_time" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow" id="saveEntryBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Slot Edit Modal (Periods/Times) -->
<div class="modal fade" id="slotModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="slotForm">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title font-weight-bold" id="slotModalLabel">Edit Period Slot</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="slot_id" id="slot_id">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Period Name / Label</label>
                        <input type="text" name="name" id="slot_name" class="form-control" placeholder="e.g. 1st, 2nd, Lunch Break" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Start Time</label>
                                <input type="time" name="start_time" id="slot_start_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">End Time</label>
                                <input type="time" name="end_time" id="slot_end_time" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_break" class="custom-control-input" id="slot_is_break">
                            <label class="custom-control-label font-weight-bold" for="slot_is_break">Is a Break? (Full width row)</label>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Sort Order</label>
                        <input type="number" name="sort_order" id="slot_sort_order" class="form-control" default="0">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-danger btn-sm" id="deleteSlotBtn" style="display:none;"><i class="fas fa-trash"></i> Delete Slot</button>
                    <div>
                        <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark px-4 shadow">Save Slot</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Grade Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form id="orderForm">
                @csrf
                <div class="modal-header bg-indigo text-white" style="background-color: #6610f2;">
                    <h5 class="modal-title font-weight-bold">Configure Class Order</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-4"><i class="fas fa-info-circle mr-1"></i> Lower numbers appear first in the routine table.</p>
                    <div id="gradeOrderList">
                        @foreach($grades as $grade)
                            <div class="row align-items-center mb-3">
                                <div class="col-8 font-weight-bold">{{ $grade->name }}</div>
                                <div class="col-4">
                                    <input type="number" name="sort_orders[{{ $grade->id }}]" class="form-control form-control-sm" value="{{ $grade->sort_order }}" placeholder="0">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-indigo px-4 shadow" style="background-color: #6610f2; color: white;">Save Sequence</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#timetableModal') });

        // --- Class Sorting Logic ---
        $('#manageSortBtn').on('click', () => $('#orderModal').modal('show'));
        $('.class-header').on('click', () => $('#orderModal').modal('show'));

        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/admin/update-grades-order",
                type: "POST",
                data: $(this).serialize(),
                success: () => location.reload()
            });
        });

        // --- Timetable Entry Logic ---

        $('.timetable-entry-cell').addClass('editable-cell').on('click', function() {
            let cell = $(this);
            let entryId = cell.data('entry-id');
            let sectionId = cell.data('section-id');
            let gradeId = cell.data('grade-id');
            let startTime = cell.data('slot-start');
            let endTime = cell.data('slot-end');

            $('#timetableForm').trigger('reset');
            $('#timetable_id').val(entryId);
            $('#form_section_id').val(sectionId);
            $('#form_grade_id').val(gradeId);
            // School ID is already pre-set to filtered school in the hidden field

            $('#start_time').val(startTime.substring(0,5));
            $('#end_time').val(endTime.substring(0,5));
            $('#deleteEntryBtnWrapper').hide();

            // Filter subjects by grade if gradeId exists
            if(gradeId) {
                $('#subject_id option').each(function() {
                    let g = $(this).data('grade');
                    $(this).toggle(!g || g == gradeId);
                });
            }

            if (entryId) {
                $('#timetableModalLabel').text('Edit Timetable Entry');
                $('#deleteEntryBtnWrapper').show();
                $.get(`/admin/teacher-timetable/${entryId}`, function(data) {
                    $('#teacher_id').val(data.teacher_id).trigger('change');
                    $('#subject_id').val(data.subject_id).trigger('change');
                    $('#timetableModal').modal('show');
                });
            } else {
                $('#timetableModalLabel').text('Add Timetable Entry');
                $('#teacher_id, #subject_id').val('').trigger('change');
                $('#timetableModal').modal('show');
            }
        });

        $('#timetableForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#timetable_id').val();
            let url = id ? `/admin/teacher-timetable/${id}` : "/admin/teacher-timetable";
            let type = id ? "PUT" : "POST";

            $.ajax({
                data: $(this).serialize(),
                url: url,
                type: type,
                success: function(data) {
                    $('#timetableModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: data.success,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    console.error(xhr);
                    let errorMessage = 'Something went wrong';
                    if (xhr.status === 422) {
                        let response = xhr.responseJSON;
                        if (response.errors) {
                            errorMessage = Object.values(response.errors).flat().join('<br>');
                        } else if (response.message) {
                            errorMessage = response.message;
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage
                    });
                }
            });
        });

        $('#deleteEntryBtn').on('click', function() {
            let id = $('#timetable_id').val();
            if (confirm("Delete this entry?")) {
                $.ajax({
                    type: "DELETE",
                    url: `/admin/teacher-timetable/${id}`,
                    success: function() {
                        location.reload();
                    }
                });
            }
        });

        // --- Slot / Period Management ---

        $('.editable-slot').addClass('editable-cell').on('click', function() {
            let id = $(this).data('id');
            $('#slotModal').modal('show');
            $('#slotForm').trigger('reset');
            $('#deleteSlotBtn').show();
            
            $.get(`/admin/timetable-slots/${id}`, function(data) {
                $('#slot_id').val(data.id);
                $('#slot_name').val(data.name);
                $('#slot_start_time').val(data.start_time.substring(0,5));
                $('#slot_end_time').val(data.end_time.substring(0,5));
                $('#slot_is_break').prop('checked', data.is_break);
                $('#slot_sort_order').val(data.sort_order);
            });
        });

        $('#addSlotBtn').on('click', function() {
            $('#slotForm').trigger('reset');
            $('#slot_id').val('');
            $('#deleteSlotBtn').hide();
            $('#slotModalLabel').text('Add New Period Slot');
            $('#slotModal').modal('show');
        });

        $('#slotForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#slot_id').val();
            let url = id ? `/admin/timetable-slots/${id}` : "/admin/timetable-slots";
            let type = id ? "PUT" : "POST";

            $.ajax({
                data: $(this).serialize() + (id ? '' : `&is_break=${$('#slot_is_break').is(':checked') ? 1 : 0}`),
                url: url,
                type: type,
                success: function() {
                    location.reload();
                }
            });
        });

        $('#deleteSlotBtn').on('click', function() {
            let id = $('#slot_id').val();
            if(confirm("Delete this slot and all its contents?")) {
                $.ajax({ type: "DELETE", url: `/admin/timetable-slots/${id}`, success: () => location.reload() });
            }
        });
    });
</script>
@stop
