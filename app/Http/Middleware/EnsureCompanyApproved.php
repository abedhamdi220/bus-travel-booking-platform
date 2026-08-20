<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('company');

        if (! $guard->check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED)
                : redirect()->route('login');
        }

        return match ($guard->user()->state) {
            'Approved' => $next($request),
            'Pending' => $this->deny($request, 'Your company is still pending approval.'),
            'Rejected' => $request->expectsJson()
                ? $this->deny($request, 'Your company account was rejected.')
                : redirect()->route('company.rejected'),
            default => $this->deny($request, 'Unknown company approval state.'),
        };
    }

    private function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], Response::HTTP_FORBIDDEN);
        }

        abort(Response::HTTP_FORBIDDEN, $message);
    }
}
