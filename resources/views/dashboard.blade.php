@extends('adminlte::page')

@section('title', __('Dashboard'))

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">{{ __('Dashboard') }}</h1>
            <p class="text-muted">{{ __('Welcome back') }}, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <div class="col-sm-6 text-right">
             <span class="badge badge-light p-2 shadow-sm border">
                 <i class="far fa-calendar-alt mr-1"></i>
                 {{ now()->translatedFormat('l, jS F Y') }}
             </span>
        </div>
    </div>
@stop

@section('content')
<style>
    .stat-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .stat-card .icon-wrapper {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 60px;
        opacity: 0.15;
    }
    .stat-card .card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }
    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .stat-card p {
        font-size: 0.95rem;
        margin-bottom: 0;
        opacity: 0.9;
    }
    .stat-card .badge-trend {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .fancy-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }
    .fancy-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        border: none;
        padding: 1rem 1.5rem;
    }
    .fancy-card .card-title {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.1rem;
    }
    
    .activity-item {
        padding: 1rem;
        border-left: 3px solid #e9ecef;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }
    .activity-item:hover {
        border-left-color: #667eea;
        background: #f8f9fa;
    }
    
    .quick-action-btn {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border: 2px solid transparent;
        transition: all 0.3s;
        font-weight: 500;
    }
    .quick-action-btn:hover {
        transform: translateX(5px);
        border-color: currentColor;
    }
    
    .info-card {
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
    }
    .info-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }
    
    .progress-modern {
        height: 8px;
        border-radius: 10px;
        background: #e9ecef;
    }
    .progress-modern .progress-bar {
        border-radius: 10px;
    }
</style>

@if($isMasterAdmin)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert shadow-sm border-0 d-flex align-items-center justify-content-between flex-wrap p-3" 
             style="background: linear-gradient(135deg, #17a2b8 0%, #118d9b 100%); color: white; border-radius: 12px;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="p-2 mr-3 rounded-circle" style="background: rgba(255,255,255,0.15);">
                    <i class="fas fa-crown fa-2x text-warning"></i>
                </div>
                <div>
                    <h5 class="mb-0 font-weight-bold text-white">{{ __('Master Administrator Panel') }}</h5>
                    <p class="mb-0 small text-white-50">{{ __('You have full system access and management controls') }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('schools.index') }}" class="btn btn-light btn-sm mr-2 px-3 shadow-sm font-weight-bold" style="color: #118d9b !important;">
                    <i class="fas fa-school mr-1"></i> {{ __('Manage Schools') }}
                </a>
                <a href="{{ route('branches.index') }}" class="btn btn-light btn-sm mr-2 px-3 shadow-sm font-weight-bold" style="color: #118d9b !important;">
                    <i class="fas fa-code-branch mr-1"></i> {{ __('Manage Branches') }}
                </a>
                <a href="{{ route('admin.api-docs') }}" class="btn btn-success btn-sm px-3 shadow-sm font-weight-bold border-0">
                    <i class="fas fa-code mr-1"></i> {{ __('API Documentation') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Stats Cards Row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3>{{ number_format($studentCount) }}</h3>
                <p>{{ __('Total Students') }}</p>
                <a href="{{ route('admissions.index') }}" class="btn btn-light btn-sm mt-2">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card bg-gradient-success text-white">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3>{{ number_format($teacherCount) }}</h3>
                <p>{{ __('Teachers') }}</p>
                <a href="{{ route('teacher-profiles.index') }}" class="btn btn-light btn-sm mt-2">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>{{ number_format($examCount) }}</h3>
                <p>{{ __('Exams Scheduled') }}</p>
                <a href="{{ route('exams.index') }}" class="btn btn-light btn-sm mt-2">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card bg-gradient-danger text-white">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="fas fa-{{ $isMasterAdmin ? 'school' : 'code-branch' }}"></i>
                </div>
                <h3>{{ number_format($isMasterAdmin ? $schoolCount : $branchCount) }}</h3>
                <p>{{ $isMasterAdmin ? __('Schools') : __('Branches') }}</p>
                <a href="{{ $isMasterAdmin ? route('schools.index') : route('branches.index') }}" class="btn btn-light btn-sm mt-2">
                    {{ __('View All') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="info-card bg-white">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white rounded-circle p-3 mr-3">
                    <i class="fas fa-calendar-check fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold">{{ number_format($presentToday) }}/{{ number_format($todayAttendance) }}</h4>
                    <small class="text-muted">{{ __('Present Today') }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="info-card bg-white">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white rounded-circle p-3 mr-3">
                    <i class="fas fa-dollar-sign fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold">₹{{ number_format($totalFees) }}</h4>
                    <small class="text-muted">{{ __('Total Fees Collected') }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="info-card bg-white">
            <div class="d-flex align-items-center">
                <div class="bg-purple text-white rounded-circle p-3 mr-3">
                    <i class="fas fa-book fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold">{{ number_format($bookCount) }}</h4>
                    <small class="text-muted">{{ __('Library Books') }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="info-card bg-white">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-white rounded-circle p-3 mr-3">
                    <i class="fas fa-graduation-cap fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold">{{ number_format($gradeCount) }}</h4>
                    <small class="text-muted">{{ __('Classes') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Charts -->
        <div class="fancy-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>{{ __('Overview Statistics') }}</h3>
            </div>
            <div class="card-body">
                <canvas id="overviewChart" style="height: 300px;"></canvas>
            </div>
        </div>

        <!-- Grade-wise Distribution -->
        @if($gradeStats->count() > 0)
        <div class="fancy-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>{{ __('Class-wise Student Distribution') }}</h3>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" style="height: 250px;"></canvas>
            </div>
        </div>
        @endif

        <!-- Recent Students -->
        <div class="card fancy-card">
            <div class="card-header bg-gradient-info text-white">
                <h3 class="card-title"><i class="fas fa-users mr-2"></i>{{ __('Recently Admitted Students') }}</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Student') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Class') }}</th>
                                @if(!$isMasterAdmin && $branchCount > 0)
                                <th>{{ __('Branch') }}</th>
                                @endif
                                <th class="text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($latestStudents as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-2">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $student->name }}</strong>
                                            <br><small class="text-muted">{{ $student->roll_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $student->grade ? $student->grade->name : 'N/A' }}
                                        {{ $student->section ? ' - ' . $student->section->name : '' }}
                                    </span>
                                </td>
                                @if(!$isMasterAdmin && $branchCount > 0)
                                <td>
                                    <span class="badge badge-info">
                                        {{ $student->branch ? $student->branch->name : 'N/A' }}
                                    </span>
                                </td>
                                @endif
                                <td class="text-right">
                                    <a href="{{ route('admissions.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ !$isMasterAdmin && $branchCount > 0 ? 5 : 4 }}" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>{{ __('No recent students found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="fancy-card">
            <div class="card-header bg-gradient-success text-white">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>{{ __('Quick Actions') }}</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admissions.index') }}" class="btn btn-primary btn-block quick-action-btn text-left">
                    <i class="fas fa-user-plus mr-2"></i> {{ __('Add New Student') }}
                </a>
                <a href="{{ route('teacher-profiles.index') }}" class="btn btn-success btn-block quick-action-btn text-left">
                    <i class="fas fa-user-tie mr-2"></i> {{ __('Add Teacher') }}
                </a>
                <a href="{{ route('exams.index') }}" class="btn btn-warning btn-block quick-action-btn text-left">
                    <i class="fas fa-calendar-plus mr-2"></i> {{ __('Schedule Exam') }}
                </a>
                <a href="{{ route('attendance.index') }}" class="btn btn-info btn-block quick-action-btn text-left">
                    <i class="fas fa-clipboard-check mr-2"></i> {{ __('Mark Attendance') }}
                </a>
                @if(Route::has('fee-payments.index'))
                <a href="{{ route('fee-payments.index') }}" class="btn btn-secondary btn-block quick-action-btn text-left">
                    <i class="fas fa-money-bill-wave mr-2"></i> {{ __('Collect Fees') }}
                </a>
                @endif
            </div>
        </div>

        <!-- Upcoming Exams -->
        <div class="fancy-card">
            <div class="card-header bg-gradient-warning text-white">
                <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>{{ __('Upcoming Exams') }}</h3>
            </div>
            <div class="card-body">
                @forelse($upcomingExams as $exam)
                <div class="activity-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>{{ $exam->name }}</strong>
                            <br><small class="text-muted">
                                <i class="far fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($exam->date)->format('M d, Y') }}
                            </small>
                        </div>
                        <span class="badge badge-warning">
                            {{ \Carbon\Carbon::parse($exam->date)->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                    <p class="mb-0">{{ __('No upcoming exams') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Branch Stats (for school admins) -->
        @if(!$isMasterAdmin && count($branchStats) > 0)
        <div class="fancy-card">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title"><i class="fas fa-code-branch mr-2"></i>{{ __('Branch Statistics') }}</h3>
            </div>
            <div class="card-body">
                @foreach($branchStats as $branch)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <strong>{{ $branch['name'] }}</strong>
                        <span class="text-muted">{{ $branch['students'] }} {{ __('students') }}</span>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar bg-primary" style="width: {{ $studentCount > 0 ? ($branch['students'] / $studentCount * 100) : 0 }}%"></div>
                    </div>
                    <small class="text-muted">{{ $branch['teachers'] }} {{ __('teachers') }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Teachers -->
        <div class="fancy-card">
            <div class="card-header bg-gradient-success text-white">
                <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i>{{ __('Recent Teachers') }}</h3>
            </div>
            <div class="card-body">
                @forelse($recentTeachers as $teacher)
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle mr-2" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $teacher->name }}</strong>
                            <br><small class="text-muted">{{ $teacher->specialization ?? __('Teacher') }}</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                    <p class="mb-0">{{ __('No recent teachers') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Overview Chart
    const ctx = document.getElementById('overviewChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["{{ __('Students') }}", "{{ __('Teachers') }}", "{{ __('Classes') }}", "{{ __('Sections') }}", "{{ __('Exams') }}"],
                datasets: [{
                    label: "{{ __('Count') }}",
                    data: [{{ $studentCount }}, {{ $teacherCount }}, {{ $gradeCount }}, {{ $sectionCount }}, {{ $examCount }}],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)'
                    ],
                    borderRadius: 8,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [3, 3] }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Grade Distribution Chart
    const gradeCtx = document.getElementById('gradeChart');
    if (gradeCtx) {
        new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: [@foreach($gradeStats as $grade)"{{ $grade->name }}",@endforeach],
                datasets: [{
                    data: [@foreach($gradeStats as $grade){{ $grade->students_count }},@endforeach],
                    backgroundColor: [
                        '#667eea', '#764ba2', '#f093fb', '#4facfe',
                        '#43e97b', '#fa709a', '#fee140', '#30cfd0',
                        '#a8edea', '#fed6e3'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { padding: 15, font: { size: 12 } }
                    }
                }
            }
        });
    }
});
</script>
@stop
