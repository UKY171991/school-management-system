<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Exam Timetable') }} - {{ $exam->name }} - {{ $section ? $section->grade->name . ' ' . $section->name : __('Multiple Sections') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Outfit:300,400,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #fff; color: #333; }
        .print-container { padding: 40px; }
        .school-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .school-name { font-size: 28px; font-weight: 700; text-transform: uppercase; margin: 0; }
        .schedule-title { text-align: center; font-size: 20px; font-weight: 600; text-decoration: underline; margin-bottom: 30px; }
        .info-bar { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 16px; }
        .table thead th { background-color: #f8f9fa !important; border-top: 2px solid #333 !important; border-bottom: 2px solid #333 !important; }
        .table td, .table th { border-color: #dee2e6 !important; padding: 12px; }
        .footer { margin-top: 60px; display: flex; justify-content: space-between; }
        .signature-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 5px; }
        @media print {
            .no-print { display: none; }
            .print-container { padding: 0; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="no-print mb-4 text-right">
            <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
                <i class="fas fa-print mr-2"></i> {{ __('Print Now') }}
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg shadow ml-2">{{ __('Close') }}</button>
        </div>

        <div class="school-header">
            <h1 class="school-name">{{ __('School Management System') }}</h1>
            <p class="mb-0">{{ __('Academic Session') }} {{ $exam->session ?? '2024-25' }}</p>
        </div>

        <h2 class="schedule-title">{{ __('EXAMINATION TIMETABLE') }}</h2>

        <div class="info-bar">
            <span><strong>{{ __('Exam') }}:</strong> {{ $exam->name }}</span>
            <span><strong>{{ __('Class & Section') }}:</strong> {{ $section ? $section->grade->name . ' - ' . $section->name : __('All / Multiple') }}</span>
            <span><strong>{{ __('Date') }}:</strong> {{ date('d M, Y') }}</span>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="150">{{ __('Date') }}</th>
                    @if(!$section) <th>{{ __('Class / Section') }}</th> @endif
                    <th width="150">{{ __('Start Time') }}</th>
                    <th width="150">{{ __('End Time') }}</th>
                    <th>{{ __('Subject') }}</th>
                    <th width="150">{{ __('Room / Hall') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($timetables as $t)
                    <tr>
                        <td class="font-weight-bold">{{ date('d-m-Y', strtotime($t->exam_date)) }}</td>
                        @if(!$section) 
                            <td>{{ $t->section->grade->name ?? '' }} - {{ $t->section->name ?? '' }}</td> 
                        @endif
                        <td>{{ date('h:i A', strtotime($t->start_time)) }}</td>
                        <td>{{ date('h:i A', strtotime($t->end_time)) }}</td>
                        <td>{{ $t->subject->name }}</td>
                        <td>{{ $t->room_number ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ !$section ? 6 : 5 }}" class="text-center py-4">{{ __('No schedule available for the selected class/exam.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">{{ __('Class Teacher') }}</div>
            <div class="signature-box">{{ __('Examination Controller') }}</div>
            <div class="signature-box">{{ __('Principal Signature') }}</div>
        </div>
    </div>

    <script>
        // Use Font Awesome via CDN for icons
        let link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
        document.head.appendChild(link);
    </script>
</body>
</html>
