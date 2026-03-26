<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MasterAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->isMasterAdmin()) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Unauthorized. Master Admin access required.'], 403);
        }

        abort(403, 'Unauthorized action.');
    }
}
