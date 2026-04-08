<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::first();
        if (!auth()->user()->isMasterAdmin()) {
            $school = \App\Models\School::find(auth()->user()->school_id);
            if ($school) {
                $settings->school_name = $school->name;
                $settings->school_address = $school->address;
                $settings->school_phone = $school->phone;
                $settings->school_email = $school->email;
                $settings->logo = $school->logo;
            }
        }
        return view('admin.general_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
            'start_roll_number' => 'required|integer|min:1',
        ];

        if (auth()->user()->isMasterAdmin()) {
            $rules['footer_text'] = 'nullable|string|max:255';
            $rules['currency_symbol'] = 'nullable|string|max:10';
            $rules['favicon'] = 'nullable|image|mimes:ico,png,jpg,svg,webp,gif|max:1024';
        }

        $request->validate($rules);

        $settings = GeneralSetting::firstOrFail();

        if (!auth()->user()->isMasterAdmin()) {
            $school = \App\Models\School::findOrFail(auth()->user()->school_id);
            
            $updateData = [
                'name' => $request->input('school_name', $school->name),
                'address' => $request->input('school_address', $school->address),
                'phone' => $request->input('school_phone', $school->phone),
                'email' => $request->input('school_email', $school->email),
                'start_roll_number' => $request->input('start_roll_number', $school->start_roll_number),
            ];

            if ($request->hasFile('logo')) {
                if ($school->logo && file_exists(public_path('storage/' . $school->logo))) {
                    @unlink(public_path('storage/' . $school->logo));
                }
                $file = $request->file('logo');
                $filename = time() . '_logo.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/schools/logos'), $filename);
                $updateData['logo'] = 'schools/logos/' . $filename;
            }

            $school->update($updateData);

            return response()->json([
                'success' => 'School settings updated successfully', 
                'logo_url' => $updateData['logo'] ?? $school->logo, 
                'favicon_url' => $settings->favicon
            ]);
        }

        $data = $request->only([
            'school_name',
            'school_address',
            'school_phone',
            'school_email',
            'footer_text',
            'currency_symbol',
            'start_roll_number',
        ]);

        if ($request->hasFile('logo')) {
            if ($settings->logo && file_exists(public_path('storage/' . $settings->logo))) {
                @unlink(public_path('storage/' . $settings->logo));
            }
            $file = $request->file('logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/settings'), $filename);
            $data['logo'] = 'settings/' . $filename;
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon && file_exists(public_path('storage/' . $settings->favicon))) {
                @unlink(public_path('storage/' . $settings->favicon));
            }
            $file = $request->file('favicon');
            $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/settings'), $filename);
            $data['favicon'] = 'settings/' . $filename;
        }

        $settings->update($data);

        return response()->json(['success' => 'Settings updated successfully', 'logo_url' => $data['logo'] ?? $settings->logo, 'favicon_url' => $data['favicon'] ?? $settings->favicon]);
    }
}
