<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowAccess
{
    public static function can(string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }
        if (!app(WorkflowSchema::class)->ready()) {
            return (int) Auth::user()->user_role === 1;
        }

        // New capabilities deny access when their permission has not been installed.
        return DB::table('module_permissions')
            ->join('role_permissions', 'rpermission_permission', '=', 'mpermission_id')
            ->where('mpermission_slug', $permission)
            ->where('mpermission_status', 1)
            ->where('rpermission_status', 1)
            ->where('rpermission_role', Auth::user()->user_role)
            ->exists();
    }
}
