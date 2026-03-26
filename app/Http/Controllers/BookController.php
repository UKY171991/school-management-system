<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Book::with('school')->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $books = $query->get();
            return response()->json($books);
        }
        $schools = \App\Models\School::all();
        return view('books.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'quantity' => 'required|integer|min:0',
        ]);

        $book = \App\Models\Book::create($validated);
        return response()->json(['success' => 'Book added to catalog.', 'book' => $book]);
    }

    public function show(string $id)
    {
        return response()->json(\App\Models\Book::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $book = \App\Models\Book::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,'.$book->id,
            'quantity' => 'required|integer|min:0',
        ]);

        $book->update($validated);
        return response()->json(['success' => 'Book updated successfully.']);
    }

    public function destroy(string $id)
    {
        $book = \App\Models\Book::findOrFail($id);
        $book->delete();
        return response()->json(['success' => 'Book removed from catalog.']);
    }
}
