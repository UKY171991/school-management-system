<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - {{ $grade->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif; /* Formal font */
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header-left {
            flex: 0 0 auto;
        }
        .header-right {
            text-align: right;
            flex: 1;
        }
        .school-logo {
            max-width: 120px;
            max-height: 100px;
            object-fit: contain;
        }
        .school-name {
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            color: #000;
        }
        .school-info {
            font-size: 14px;
            line-height: 1.4;
        }
        .report-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0 30px 0;
            text-transform: uppercase;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 16px;
            padding: 0 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px 8px; /* Increased padding for better writing space */
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f8f9fa; /* Slightly lighter gray */
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }
        td.center {
            text-align: center;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }
        .signature-box {
            text-align: center;
            border-top: 1px solid #000;
            width: 200px;
            padding-top: 10px;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                -webkit-print-color-adjust: exact;
            }
            @page {
                margin: 1cm;
                size: portrait;
            }
            .btn-print {
                display: none !important;
            }
        }
        .btn-print {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <button class="btn-print no-print" onclick="window.print()">Print List</button>

    <div class="header">
        <div class="header-left">
            @if($school->logo)
                <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
            @elseif($school->logo_url)
                 <img src="{{ $school->logo_url }}" alt="School Logo" class="school-logo">
            @endif
        </div>
        <div class="header-right">
            <div class="school-name">{{ $school->name }}</div>
            <div class="school-info">
                {{ $school->address }}<br>
                {{ $school->phone }} 
                {{ $school->email ? '| ' . $school->email : '' }}
            </div>
        </div>
    </div>

    <div class="report-title">
        STUDENT LIST {{ $examType ? '- ' . $examType->name : '' }}
    </div>

    <div class="meta-info">
        <div>Class: {{ $grade->name }} {{ $section ? '- Section ' . $section->name : '' }}</div>
        <div>Class Teacher: {{ $teacher->name }}</div>
        <div>Subject: {{ $subject->name }} (Max Marks: ________)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">S.No</th>
                <th style="width: 100px;">Roll No</th>
                <th>Student Name</th>
                <th style="width: 150px;">Marks Obtained</th>
                <th style="width: 150px;">Remarks</th> 
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">{{ $student->roll_number }}</td>
                    <td style="padding-left: 15px;">{{ $student->name }}</td>
                    <td class="center" style="border-bottom: 1px solid #000;"></td> <!-- Empty for manual entry -->
                    <td class="center" style="border-bottom: 1px solid #000;"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">No students found in this class.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">Class Teacher</div>
        <div class="signature-box">Principal</div>
    </div>

</body>
</html>
