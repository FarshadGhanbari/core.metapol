<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) return abort(401);
        $user = Auth::user();
        if ($user->isAdministrator()) return $next($request);
        if ($user->status == 'active') return $next($request);
        return abort(403);
    }
}
