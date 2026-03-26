<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        if (in_array($locale, ['en', 'hi'])) {
            Session::put('locale', $locale);
            
            // Persist to user if logged in
            if (auth()->check()) {
                $user = auth()->user();
                $user->language = $locale;
                $user->save();
            }
        }

        return Redirect::back();
    }
}
