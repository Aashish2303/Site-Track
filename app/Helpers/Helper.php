<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use DB;
use Auth;
use App\Models\RolePermission;
use App\Models\ModulePermission;
use App\Models\DailyProgress;
use App\Models\ProjectBoq;
use App\Models\Order;
use App\Models\OrderDelivery;

class Helper
{
	public static function mailConfig()
	{
        $config = array(
            'driver'     => Request()->settings->settings_mailer,
            'host'       => Request()->settings->settings_host,
            'port'       => Request()->settings->settings_port,
            'from'       => array('address' => Request()->settings->settings_faddress, 'name' => Request()->settings->settings_fname),
            'encryption' => Request()->settings->settings_encryption,
            'username'   => Request()->settings->settings_uname,
            'password'   => Request()->settings->settings_pwd
        );

        Config::set('mail', $config);
	}

    public static function userHasPermission($name)
	{
		$return = true;

		$assigned_permissions = array();

        //if(Auth::user()->user_role != 1)
        {
            $where_group['rpermission_role'] = Auth::user()->user_role;
            $role_permissions = RolePermission::where($where_group)->get();

            if($role_permissions->count() > 0)
            {
                $assigned_permissions = array_column($role_permissions->toArray(),'rpermission_permission');
            }

            $module_permission = ModulePermission::where('mpermission_slug',$name)->first();

            if(isset($module_permission))
            {
                if(!in_array($module_permission->mpermission_id,$assigned_permissions))
                {
                    $return = false;
                }
            }
        }

		return $return;
	}

    public static function moneyFormat($num)
    {
        $num = (string)$num;
        $lastThree = substr($num, -3);
        $restUnits = substr($num, 0, -3);

        if ($restUnits != '') {
            $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
            return $restUnits . "," . $lastThree;
        }

        return $lastThree;
    }

    public static function doneBoq($project_id)
    {
        if (app(\App\Services\WorkflowSchema::class)->ready()) {
            return DB::table('daily_progress as p')->join('progress_reviews as r', 'r.progress_id', '=', 'p.dprogress_id')
                ->where('p.dprogress_project', $project_id)->where('p.dprogress_trash', 1)->where('r.state', 'approved')
                ->selectRaw('p.dprogress_boq, SUM(r.approved_qty) as total')->groupBy('p.dprogress_boq')
                ->pluck('total', 'dprogress_boq')->all();
        }
        $where['dprogress_project'] = $project_id;
        $done_boqs = DailyProgress::select('dprogress_boq', DB::raw('sum(dprogress_qty) as total'))->where($where)->groupBy('dprogress_boq')->get();
        
        $dboqs = array();

        if($done_boqs->count() > 0)
        {
            foreach($done_boqs as $done_boq)
            {
                $dboqs[$done_boq->dprogress_boq] = $done_boq->total;
            }
        }

        return $dboqs;
    }

    public static function orderStatus($order_id)
    {
        $where['odelivery_order'] = $order_id;
        $total_delivery = OrderDelivery::where($where)->sum('odelivery_qty');

        return $total_delivery;
    }
}
