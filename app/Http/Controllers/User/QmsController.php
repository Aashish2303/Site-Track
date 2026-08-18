<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\Exports\QmsExport;
use App\Models\Project;
use App\Models\DailyWork;
use App\Models\LabourWorker;
use App\Models\Indent;
use App\Models\Issue;
use App\Models\Order;

class QmsController extends Controller
{
    public function index(Request $request)
    {
        $data['projects'] = Project::where('project_status',1)->get();

        $data['set'] = 'qms';
        return view('user.qms.qms',$data);
    }

    public function qms_download_excel(Request $request)
    {
        $filename = 'qms_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new QmsExport($request->project),$filename);
    }
}
