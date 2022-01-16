<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::check()) return response()->json(['message' => 'Sorry, you are not authorized to access this route.'], 401);
        $user = Auth::user();
        if ($user->isAdministrator()) return $next($request);
        if ($user->status == 'active' and in_array($permission, $user->allPermissions())) return $next($request);
        return response()->json(['message' => 'Sorry, you are forbidden from accessing this route.'], 403);
    }
}
