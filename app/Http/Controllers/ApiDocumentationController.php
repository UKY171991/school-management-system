<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        // Get all API routes
        $apiRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return str_starts_with($route->uri(), 'api/');
        })->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
            ];
        })->values();

        // Organize endpoints by category
        $endpoints = [
            'School Information' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/info',
                    'description' => 'Get school information',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/info?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/statistics',
                    'description' => 'Get school statistics (students, teachers, attendance, fees)',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/statistics?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/branches',
                    'description' => 'Get all branches of the school',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/branches?domain=school.example.com'),
                ],
            ],
            'Students' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/students',
                    'description' => 'Get all students (paginated)',
                    'parameters' => 'domain (required), per_page (optional), page (optional)',
                    'example' => url('/api/public-api/students?domain=school.example.com&per_page=20'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/students/{id}',
                    'description' => 'Get single student by ID or roll number',
                    'parameters' => 'domain (required), id/roll_number (required)',
                    'example' => url('/api/public-api/students/1001?domain=school.example.com'),
                ],
            ],
            'Teachers' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/teachers',
                    'description' => 'Get all teachers (paginated)',
                    'parameters' => 'domain (required), per_page (optional)',
                    'example' => url('/api/public-api/teachers?domain=school.example.com'),
                ],
            ],
            'Academic' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/grades',
                    'description' => 'Get all classes/grades with sections',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/grades?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/sections',
                    'description' => 'Get all sections',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/sections?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/exams',
                    'description' => 'Get all exams (paginated)',
                    'parameters' => 'domain (required), per_page (optional)',
                    'example' => url('/api/public-api/exams?domain=school.example.com'),
                ],
            ],
            'Attendance' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/attendance',
                    'description' => 'Get attendance records',
                    'parameters' => 'domain (required), date (optional), student_id (optional), per_page (optional)',
                    'example' => url('/api/public-api/attendance?domain=school.example.com&date=2026-03-21'),
                ],
            ],
            'Fees' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/fee-payments',
                    'description' => 'Get fee payment records',
                    'parameters' => 'domain (required), student_id (optional), status (optional), per_page (optional)',
                    'example' => url('/api/public-api/fee-payments?domain=school.example.com&status=paid'),
                ],
            ],
            'Library' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/books',
                    'description' => 'Get library books catalog',
                    'parameters' => 'domain (required), per_page (optional)',
                    'example' => url('/api/public-api/books?domain=school.example.com'),
                ],
            ],
            'Homework & Syllabus' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/homework',
                    'description' => 'Get homework assignments',
                    'parameters' => 'domain (required), per_page (optional)',
                    'example' => url('/api/public-api/homework?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/syllabus',
                    'description' => 'Get syllabus documents',
                    'parameters' => 'domain (required), per_page (optional)',
                    'example' => url('/api/public-api/syllabus?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/subjects',
                    'description' => 'Get all school subjects',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/subjects?domain=school.example.com'),
                ],
            ],
            'Transport & Hostel' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/transport/routes',
                    'description' => 'Get transport routes',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/transport/routes?domain=school.example.com'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/hostels',
                    'description' => 'Get hostel details and rooms',
                    'parameters' => 'domain (required)',
                    'example' => url('/api/public-api/hostels?domain=school.example.com'),
                ],
            ],
            'Results & Timetables' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/exam-timetables',
                    'description' => 'Get exam timetables',
                    'parameters' => 'domain (required), exam_id (optional)',
                    'example' => url('/api/public-api/exam-timetables?domain=school.example.com&exam_id=1'),
                ],
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/exam-results',
                    'description' => 'Get student exam results',
                    'parameters' => 'domain (required), student_id (optional), exam_id (optional)',
                    'example' => url('/api/public-api/exam-results?domain=school.example.com&student_id=1'),
                ],
            ],
            'Staff & Payroll' => [
                [
                    'method' => 'GET',
                    'endpoint' => '/api/public-api/payroll',
                    'description' => 'Get staff payroll records',
                    'parameters' => 'domain (required), staff_id (optional)',
                    'example' => url('/api/public-api/payroll?domain=school.example.com'),
                ],
            ],
        ];

        return view('api-documentation', compact('endpoints', 'apiRoutes'));
    }
}
