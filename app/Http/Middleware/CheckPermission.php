<?php

namespace App\Http\Middleware;

use App\Services\RoleAccess;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(app(RoleAccess::class)->allows((string) $request->route()->getName()), 403, 'You do not have permission for this action.');

        return $next($request);
    }
}
