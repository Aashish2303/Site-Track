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
        $data['active_projects'] = Project::where('project_status',1)->count();

        $today = date('Y-m-d');

        $data['todays_work'] = DailyWork::where('dwork_date',$today)->count();

        $workers = LabourWorker::join('labour_trackers','labour_trackers.ltracker_id','labour_workers.lworker_tracker')->where('ltracker_date',$today)->get();

        $today_workers = array();

        if($workers->count() > 0)
        {
            foreach($workers as $worker)
            {
                $today_workers[] = $worker->lworker_male + $worker->lworker_female;
            }
        }

        $data['today_workers'] = array_sum($today_workers);

        $data['pending_approvals'] = Indent::whereIn('indent_status',array(1,2))->count();

        $data['open_issues'] = Issue::where('issue_status',1)->count();

        $data['todays_order'] = Order::where('order_date',$today)->count();

        $data['projects'] = Project::where('project_status',1)->get();

        $data['pending_indents'] = Indent::whereIn('indent_status',array(1,2))->get();

        $data['set'] = 'dashboard';
        return view('user.dashboard.dashboard',$data);
    }
}
