<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class FrontendController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::first();
        return view('frontend.home', compact('settings'));
    }

    public function about()
    {
        $settings = GeneralSetting::first();
        return view('frontend.about', compact('settings'));
    }

    public function courses()
    {
        $settings = GeneralSetting::first();
        return view('frontend.courses', compact('settings'));
    }

    public function contact()
    {
        $settings = GeneralSetting::first();
        return view('frontend.contact', compact('settings'));
    }
}
