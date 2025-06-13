<?php

namespace App\Http\Middleware;

use Closure;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->roles->first()->name == "SuperAdmin") {
            return $next($request);
        }

        return redirect('/');  // Jika bukan admin, arahkan ke halaman lain
    }
}
