<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Exam Seating Plan') }}</title>
    <style>
        * {
            box-sizing: border-box; 
        }
        body {
            font-family: 'Times New Roman', Times, serif; /* Formal font */
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 100%;
            padding: 20px;
            overflow-x: hidden;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Vertically center items */
            justify-content: center; /* Horizontally center items */
        }
        .header img {
            max-height: 80px;
            margin-right: 20px; /* Space between logo and text */
        }
        .header-text {
            text-align: left; /* Text aligned left next to logo */
        }
        .school-name {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .school-info {
            font-size: 10pt;
            margin: 5px 0 0 0;
        }
        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .meta-info {
            margin-bottom: 20px;
            overflow: hidden; /* Clear floats */
        }
        .meta-left {
            float: left;
            font-weight: bold;
        }
        .meta-right {
            float: right;
            font-weight: bold;
        }
        
        /* Grid Layout Styles */
        .seating-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .seat-card {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            page-break-inside: avoid;
        }
        .seat-number {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .student-name {
            font-weight: bold;
            font-size: 11pt;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .student-details {
            font-size: 10pt;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($school->logo)
                <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo">
            @elseif($school->logo_url)
                 <img src="{{ $school->logo_url }}" alt="School Logo">
            @endif
            <div class="header-text">
                <div class="school-name">{{ $school->name }}</div>
                <div class="school-info">
                    {{ $school->address }}<br>
                    {{ __('Phone') }}: {{ $school->phone }} | {{ __('Email') }}: {{ $school->email }}
                </div>
            </div>
        </div>

        <div class="title">{{ __('EXAM SEATING PLAN') }}</div>

        <div class="meta-info">
            <div class="meta-left">{{ __('Room') }}: {{ $roomNo }}</div>
            <div class="meta-right">{{ __('Date') }}: {{ date('d-m-Y') }}</div>
        </div>
        
        <div class="mb-3">
             <strong>{{ __('Allocated Classes') }}:</strong> 
             @foreach($selectedGrades as $grade)
                {{ $grade->name }}{{ !$loop->last ? ',' : '' }}
             @endforeach
        </div>

        <div class="seating-grid">
            @foreach($mergedStudents as $index => $student)
            <div class="seat-card">
                <div class="seat-number">{{ __('Seat') }} {{ $index + 1 }}</div>
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" style="height: 60px; width: 60px; object-fit: cover; border-radius: 50%; margin: 5px auto; display: block;" alt="Student Photo">
                @endif
                <div class="student-name">{{ $student->name }}</div>
                <div class="student-details">
                    {{ __('Class') }}: {{ $student->grade->name ?? __('N/A') }} {{ $student->section ? '- ' . $student->section->name : '' }}<br>
                    {{ __('Roll No') }}: {{ $student->student_code ?? ($student->roll_number ?? __('N/A')) }}
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <script>
        window.print();
    </script>
</body>
</html>
