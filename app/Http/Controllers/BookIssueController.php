<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $issues = \App\Models\BookIssue::with(['book', 'student'])->latest()->get();
            return response()->json($issues);
        }
        $books = \App\Models\Book::all();
        $students = \App\Models\Student::all();
        return view('book-issue.index', compact('books', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        $data = $validated;
        $data['return_date'] = ($request->status === 'Returned') ? now() : null;

        $issue = \App\Models\BookIssue::create($data);
        return response()->json(['success' => 'Book issued successfully.', 'data' => $issue]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(\App\Models\BookIssue::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $issue = \App\Models\BookIssue::findOrFail($id);
        
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
        ]);

        $data = $validated;
        $data['return_date'] = ($request->status === 'Returned') ? ($issue->return_date ?? now()) : null;

        $issue->update($data);
        return response()->json(['success' => 'Book issue record updated.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \App\Models\BookIssue::findOrFail($id)->delete();
        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
