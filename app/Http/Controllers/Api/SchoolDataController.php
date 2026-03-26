<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Grade;
use Illuminate\Http\Request;

class SchoolDataController extends Controller
{
    /**
     * Get school information by domain name.
     */
    public function getByDomain(Request $request)
    {
        $domain = $request->query('domain');

        if (!$domain) {
            return response()->json(['error' => 'Domain parameter is required'], 400);
        }

        // Clean domain (remove http://, https://, and trailing slashes)
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        $domain = rtrim($domain, '/');

        $school = School::where('domain_name', $domain)->first();

        if (!$school) {
            return response()->json(['error' => 'School not found for domain: ' . $domain], 404);
        }

        // Prepare response with related data if needed
        $data = [
            'info' => [
                'name' => $school->name,
                'address' => $school->address,
                'phone' => $school->phone,
                'email' => $school->email,
                'logo_url' => $school->logo_url,
                'signature_url' => $school->signature_url,
                'domain' => $school->domain_name,
            ],
            // Optional: Include grades/sections or teachers if public data is desired
            // 'grades' => Grade::where('school_id', $school->id)->with('sections')->get(),
            // 'teachers' => Teacher::where('school_id', $school->id)->get(['name', 'specialization', 'photo']),
        ];

        return response()->json($data);
    }

    /**
     * Get list of all schools with domains (for index/directory if needed).
     */
    public function listWithDomains()
    {
        $schools = School::whereNotNull('domain_name')->get([
            'id', 'name', 'domain_name', 'logo'
        ])->map(function($school) {
            return [
                'id' => $school->id,
                'name' => $school->name,
                'domain' => $school->domain_name,
                'logo_url' => $school->logo ? asset('storage/' . $school->logo) : null,
            ];
        });

        return response()->json($schools);
    }
}
