<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RoleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$allowedRoleIds): Response
    {
        $user = Auth::user();

        if (!$user || !$user->roles_id) {
            abort(403, 'Access denied');
        }
        $permissionIds = $user->permissions()
            ->pluck('id')->toArray();
        // Bandingkan roles_id
        if ($user->roles_id == 1) {
            return $next($request);
        }
        for ($i = 0; $i < count($permissionIds); $i++) {
            # code...
            if (in_array($permissionIds[$i], $allowedRoleIds)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
