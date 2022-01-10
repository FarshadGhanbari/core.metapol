<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::check()) return redirect('login');
        $user = Auth::user();
        if ($user->isAdmin() or in_array($permission, $user->allPermissions())) return $next($request);
        return abort(403);
    }
}
