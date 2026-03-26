<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\School;
use Symfony\Component\HttpFoundation\Response;

class ApiDomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get domain from various sources
        $domain = $request->header('X-School-Domain') 
                  ?? $request->get('domain') 
                  ?? $request->getHost();

        // Check if domain exists
        $school = School::where('domain_name', $domain)->first();

        if (!$school) {
            return response()->json([
                'error' => 'Invalid school domain',
                'message' => 'The provided domain is not registered in the system'
            ], 403);
        }

        // Attach school to request for later use
        $request->attributes->set('school', $school);

        return $next($request);
    }
}
