<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;
use App\Models\ModulePermission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $assigned_permissions = array();

        //if(Auth::user()->user_role != 1)
        {
            $where_role['rpermission_role'] = Auth::user()->user_role;
            $role_permissions = RolePermission::where($where_role)->get();

            if($role_permissions->count() > 0)
            {
                $assigned_permissions = array_column($role_permissions->toArray(),'rpermission_permission');
            }

            $module_permission = ModulePermission::where('mpermission_slug',$request->route()->getName())->first();

            if(isset($module_permission))
            {
                if(!in_array($module_permission->mpermission_id,$assigned_permissions))
                {
                    return redirect()->route('user.dashboard')->with('error',"You dont have this module access.");
                }
            }
        }

        return $next($request);
    }
}
