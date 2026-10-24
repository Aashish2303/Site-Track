<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\DailyWork;
use App\Models\LabourWorker;
use App\Models\Indent;
use App\Models\Issue;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data['active_projects'] = \Helper::userHasPermission('user.projects') ? Project::where('project_status',1)->count() : 0;

        $today = date('Y-m-d');

        $data['todays_work'] = \Helper::userHasPermission('user.daily_works') ? DailyWork::where('dwork_date',$today)->count() : 0;

        $workers = \Helper::userHasPermission('user.labour_trackers') ? LabourWorker::join('labour_trackers','labour_trackers.ltracker_id','labour_workers.lworker_tracker')->where('ltracker_date',$today)->get() : collect();

        $today_workers = array();

        if($workers->count() > 0)
        {
            foreach($workers as $worker)
            {
                $today_workers[] = $worker->lworker_male + $worker->lworker_female;
            }
        }

        $data['today_workers'] = array_sum($today_workers);

        $data['pending_approvals'] = \Helper::userHasPermission('user.indents') ? Indent::whereIn('indent_status',array(1,2))->count() : 0;

        $data['open_issues'] = \Helper::userHasPermission('user.issues') ? Issue::where('issue_status',1)->count() : 0;

        $data['todays_order'] = \Helper::userHasPermission('user.orders') ? Order::where('order_date',$today)->count() : 0;

        $data['projects'] = \Helper::userHasPermission('user.projects') && \Helper::userHasPermission('user.view_project') ? Project::where('project_status',1)->get() : collect();

        $data['pending_indents'] = \Helper::userHasPermission('user.indents') ? Indent::whereIn('indent_status',array(1,2))->get() : collect();

        $data['set'] = 'dashboard';
        return view('user.dashboard.dashboard',$data);
    }
}
