<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 💡 قراءة الدور وتوحيده كأحرف صغيرة لتجنب الطرد بسبب الـ "Admin" بحرف كبير
        $role = strtolower(Auth::user()->role ?? '');

        if (Auth::check() && $role === 'admin') {
            return $next($request);
        }

        // إذا لم يكن مشرفاً، يتم توجيهه للرئيسية
        return redirect('/')->with('error', 'Access Denied: Administrator privileges required.');
    }
}
