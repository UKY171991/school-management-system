<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function student()
    {
        return view('portals.student');
    }

    public function teacher()
    {
        return view('portals.teacher');
    }

    public function parent()
    {
        return view('portals.parent');
    }
}
