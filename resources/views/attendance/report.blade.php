@extends('adminlte::page')

@section('title', __('Attendance Report'))

@section('content_header')
    <h1>{{ __('Attendance Report') }}</h1>
@stop

@section('content')
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
    }
    .status-box {
        width: 25px;
        height: 25px;
        font-size: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        color: white;
        font-weight: bold;
    }
    .status-P { background-color: #28a745; }
    .status-A { background-color: #dc3545; }
    .status-L { background-color: #ffc107; color: black; }
    .status-E { background-color: #17a2b8; }
    
    .table-responsive {
        font-size: 12px;
    }
    th.rotate {
        height: 80px;
        white-space: nowrap;
    }
    th.rotate > div {
        transform: rotate(-90deg);
        width: 30px;
        margin-bottom: 25px;
    }
    /* Fixed column for names */
    .table td:first-child, .table th:first-child {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: #fff;
    }
    .table td:nth-child(2), .table th:nth-child(2) {
        position: sticky;
        left: 50px; /* Adjust based on ID column width */
        z-index: 1;
        background-color: #fff;
    }
    
    .legend-item {
        display: inline-block;
        margin-right: 15px;
        font-size: 12px;
    }
    .legend-box {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 3px;
        margin-right: 5px;
        vertical-align: middle;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Monthly Attendance Report') }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('School') }}</label>
                            <select id="filter_school_id" class="form-control select2">
                                <option value="">{{ __('Select School') }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('Class') }}</label>
                            <select id="filter_grade" class="form-control select2">
                                <option value="">{{ __('Select Class') }}</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" data-school="{{ $grade->school_id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('Section') }}</label>
                            <select id="filter_section" class="form-control select2">
                                <option value="">{{ __('All Sections') }}</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" data-grade="{{ $section->grade_id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('Month') }}</label>
                            <select id="filter_month" class="form-control select2">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ __(date('F', mktime(0, 0, 0, $m, 1))) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ __('Year') }}</label>
                            <select id="filter_year" class="form-control select2">
                                @for($y=date('Y'); $y>=date('Y')-5; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-3">
                         <button class="btn btn-primary btn-block shadow-sm" id="getReport">
                             <i class="fas fa-search mr-1"></i> {{ __('Get Report') }}
                         </button>
                    </div>
                </div>

                <div class="row mb-2">
                     <div class="col-12 text-right">
                         <div class="legend-item"><span class="legend-box status-P"></span> {{ __('Present') }}</div>
                         <div class="legend-item"><span class="legend-box status-A"></span> {{ __('Absent') }}</div>
                         <div class="legend-item"><span class="legend-box status-L"></span> {{ __('Late') }}</div>
                         <div class="legend-item"><span class="legend-box status-E"></span> {{ __('Excused') }}</div>
                     </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="reportTable">
                        <thead id="tableHead">
                            <!-- Headers dynamically generated -->
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    var allGrades = @json($grades);
    var allSections = @json($sections);

    $(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $('.select2').select2({ theme: 'bootstrap4' });

         // Dependent Dropdowns (Same filtering logic as Index)
        $('#filter_school_id').change(function() {
            let schoolId = $(this).val();
            let gradeSelect = $('#filter_grade');
            let sectionSelect = $('#filter_section');
            
            gradeSelect.empty().append('<option value="">{{ __('Select Class') }}</option>');
            allGrades.forEach(function(g) {
                if(!schoolId || g.school_id == schoolId) {
                    let option = new Option(g.name, g.id, false, false);
                    gradeSelect.append(option);
                }
            });
            gradeSelect.val('').trigger('change.select2');

            sectionSelect.empty().append('<option value="">{{ __('All Sections') }}</option>');
            sectionSelect.val('').trigger('change.select2');
        });

        $('#filter_grade').change(function() {
            let gradeId = $(this).val();
            let sectionSelect = $('#filter_section');
            
            sectionSelect.empty().append('<option value="">{{ __('All Sections') }}</option>');
            if(gradeId) {
                allSections.forEach(function(s) {
                    if(s.grade_id == gradeId) {
                        let option = new Option(s.name, s.id, false, false);
                        sectionSelect.append(option);
                    }
                });
            }
            sectionSelect.val('').trigger('change.select2');
        });

        $('#getReport').click(function() {
            let schoolId = $('#filter_school_id').val();
            let gradeId = $('#filter_grade').val();
            let sectionId = $('#filter_section').val();
            let month = $('#filter_month').val();
            let year = $('#filter_year').val();

            if(!schoolId || !gradeId) {
                Swal.fire("{{ __('Warning') }}", "{{ __('Please select School and Class.') }}", 'warning');
                return;
            }

            Swal.fire({title: "{{ __('Loading Report...') }}", allowOutsideClick: false, onBeforeOpen: () => { Swal.showLoading(); }});

            $.get("{{ route('attendance.report') }}", {
                school_id: schoolId,
                grade_id: gradeId,
                section_id: sectionId,
                month: month,
                year: year
            }, function(response) {
                Swal.close();
                
                let days = response.daysInMonth;
                let data = response.data;
                
                // Build Header
                let thead = '<tr><th style="min-width: 50px;">{{ __('Roll') }}</th><th style="min-width: 150px;">{{ __('Student Name') }}</th>';
                for(let i=1; i<=days; i++) {
                    thead += `<th class="text-center p-1" style="width: 25px;">${i}</th>`;
                }
                thead += '<th class="bg-success text-white">P</th><th class="bg-danger text-white">A</th><th class="bg-warning text-dark">L</th><th class="bg-info text-white">E</th></tr>';
                $('#tableHead').html(thead);

                // Build Body
                let tbody = '';
                if(data.length === 0) {
                     tbody = `<tr><td colspan="${days + 6}" class="text-center">{{ __('No students found for this criteria.') }}</td></tr>`;
                } else {
                    data.forEach(function(row) {
                        tbody += '<tr>';
                        tbody += `<td>${row.student.roll_number}</td>`;
                        tbody += `<td>${row.student.name}</td>`;
                        
                        for(let i=1; i<=days; i++) {
                            let status = row.attendance[i];
                            let content = '-';
                            let cssClass = '';
                            
                            if(status) {
                                if(status == 'present') { content = 'P'; cssClass = 'status-P'; }
                                else if(status == 'absent') { content = 'A'; cssClass = 'status-A'; }
                                else if(status == 'late') { content = 'L'; cssClass = 'status-L'; }
                                else if(status == 'excused') { content = 'E'; cssClass = 'status-E'; }
                                
                                tbody += `<td class="text-center p-1"><span class="status-box ${cssClass}">${content}</span></td>`;
                            } else {
                                tbody += `<td class="text-center p-1 text-muted bg-light"></td>`;
                            }
                        }
                        
                        tbody += `<td class="text-center font-weight-bold">${row.summary.present}</td>`;
                        tbody += `<td class="text-center font-weight-bold">${row.summary.absent}</td>`;
                        tbody += `<td class="text-center font-weight-bold">${row.summary.late}</td>`;
                        tbody += `<td class="text-center font-weight-bold">${row.summary.excused}</td>`;
                        
                        tbody += '</tr>';
                    });
                }
                $('#tableBody').html(tbody);
                
            }).fail(function() {
                Swal.fire("{{ __('Error') }}", "{{ __('Failed to load report.') }}", 'error');
            });
        });
    });
</script>
@stop
