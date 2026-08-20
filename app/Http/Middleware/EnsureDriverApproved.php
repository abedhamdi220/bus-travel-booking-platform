<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDriverApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED)
                : redirect()->route('login');
        }

        $driver = $user->driver;
        if (! $driver) {
            return $this->deny($request, 'No driver profile is associated with this account.');
        }

        return match ($driver->state) {
            'Approved' => $next($request),
            'Pending' => $request->expectsJson()
                ? $this->deny($request, 'Your driver account is still pending approval.')
                : redirect()->route('driver.pending'),
            'Rejected' => $request->expectsJson()
                ? $this->deny($request, 'Your driver account was rejected.')
                : redirect()->route('driver.rejected'),
            default => $this->deny($request, 'Unknown driver approval state.'),
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
