<?php

$file = 'config/adminlte.php';
$content = file_get_contents($file);

// Replace adminlte::menu references with plain English
$replacements = [
    "'adminlte::menu.student_management'" => "'Student Management'",
    "'adminlte::menu.admission'" => "'Admission & Reg.'",
    "'adminlte::menu.profile_documents'" => "'Profile & Documents'",
    "'adminlte::menu.attendance_tracking'" => "'Attendance Tracking'",
    "'adminlte::menu.attendance_report'" => "'Attendance Report'",
    "'adminlte::menu.transfers_lc'" => "'Transfers & LC'",
    "'adminlte::menu.print_student_list'" => "'Print Student List'",
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);

echo "Fixed adminlte::menu references!\n";
