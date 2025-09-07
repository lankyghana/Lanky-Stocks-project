<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $admin = auth('admin')->user();

        if (!$admin || !$admin->hasPermission($permission)) {
            $notify[] = ['error', 'You do not have permission to access this page'];
            return back()->withNotify($notify);
        }

        return $next($request);
    }
} 