<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApprovedCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق مما إذا كانت الشركة مسجلة الدخول وحالتها Approved
        if (Auth::guard('company')->check() && Auth::guard('company')->user()->state === 'Approved') {
            return $next($request);
        }

        // في حال لم تكن الشركة معتمدة أو غير مسجلة الدخول
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Your company account is not approved yet or you are not logged in.'
        ], 403);
    }
}
