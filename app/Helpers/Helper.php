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
        $settings = request()->settings;
        if ($settings && $settings->settings_host && $settings->settings_uname) {
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp', 'host' => $settings->settings_host,
                'port' => $settings->settings_port, 'encryption' => $settings->settings_encryption,
                'username' => $settings->settings_uname, 'password' => app(\App\Services\ServiceSecrets::class)->smtp($settings),
                'timeout' => 10,
            ]);
            Config::set('mail.from', ['address' => $settings->settings_faddress, 'name' => $settings->settings_fname]);
        }
	}

    public static function userHasPermission($name)
    {
        return app(\App\Services\RoleAccess::class)->allows($name);
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
