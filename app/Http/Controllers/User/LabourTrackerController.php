<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\LabourExport;
use App\Models\User;
use App\Models\Subcontractor;
use App\Models\Project;
use App\Models\Category;
use App\Models\LabourTracker;
use App\Models\LabourWorker;

class LabourTrackerController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('date') && !empty($request->date))
        {
            $where['ltracker_date'] = $request->date;
        }
        else
        {
            $where['ltracker_date'] = date('Y-m-d');
        }
        
        if($request->has('project') && !empty($request->project))
        {
            $where['ltracker_project'] = $request->project;
        }

        $workers = LabourWorker::join('labour_trackers','labour_trackers.ltracker_id','labour_workers.lworker_tracker')->join('category','category.category_id','labour_workers.lworker_category')->where($where)->get();

        $total_workers = array();
        $total_male = array();
        $total_female = array();
        $total_cost = array();
        $trades = array();

        if($workers->count() > 0)
        {
            foreach($workers as $worker)
            {
                $total_workers[] = $worker->lworker_male + $worker->lworker_female;
                $total_male[] = $worker->lworker_male;
                $total_female[] = $worker->lworker_female;
                $total_cost[] = $worker->lworker_total;
                $trades[$worker->category_name][] = $worker->lworker_male + $worker->lworker_female;
            }
        }

        $data['total_workers'] = array_sum($total_workers);
        $data['total_male'] = array_sum($total_male);
        $data['total_female'] = array_sum($total_female);
        $data['total_cost'] = array_sum($total_cost);
        $data['trades'] = $trades;

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['set'] = 'labour_trackers';
        return view('user.labour_tracker.labour_trackers',$data);
    }

    public function get_labour_trackers(Request $request)
    {
        if($request->ajax())
        {
            if(!empty($request->date))
            {
                $where['ltracker_date'] = $request->date;
            }
            else
            {
                $where['ltracker_date'] = date('Y-m-d');
            }

            if(!empty($request->project))
            {
                $where['ltracker_project'] = $request->project;
            }

            $where['ltracker_trash'] = 1;
            $data = LabourTracker::where($where)->orderby('ltracker_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('tracker_date', function($row){

                        $tracker_date = date('d M Y',strtotime($row->ltracker_date));

                        return $tracker_date;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('categories', function($row){

                        $workers = LabourWorker::where('lworker_tracker',$row->ltracker_id)->get();

                        $trades = array();

                        if($workers->count() > 0)
                        {
                            foreach($workers as $worker)
                            {
                                $trades[$worker->category->category_name][] = $worker->lworker_male + $worker->lworker_female;
                            }
                        }

                        $categories = array();

                        foreach($trades as $category_name => $trade)
                        {
                            $categories[] = '<span class="badge badge-soft-info">'.$category_name.': '.array_sum($trade).'</span>';
                        }

                        $categories = implode(' ',$categories);

                        return $categories;
                    })
                    ->addColumn('mf', function($row){

                        $workers = LabourWorker::where('lworker_tracker',$row->ltracker_id)->get();

                        $total_male = array();
                        $total_female = array();

                        if($workers->count() > 0)
                        {
                            foreach($workers as $worker)
                            {
                                $total_male[] = $worker->lworker_male;
                                $total_female[] = $worker->lworker_female;
                            }
                        }

                        $mf = array_sum($total_male).'/'.array_sum($total_female);

                        return $mf;
                    })
                    ->addColumn('time_period', function($row){

                        $time_period = date('h:i A',strtotime($row->ltracker_in)).' - '.date('h:i A',strtotime($row->ltracker_out));

                        return $time_period;
                    })
                    ->addColumn('shift', function($row){

                        if($row->ltracker_shift == 1)
                        {
                            $shift = 'Day';   
                        }
                        elseif($row->ltracker_shift == 2)
                        {
                            $shift = 'Night';
                        }

                        return $shift;
                    })
                    ->addColumn('total_cost', function($row){

                        $total_cost = Helper::moneyFormat($row->ltracker_total);

                        return $total_cost;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_labour_tracker'))
                        {
                            $btn .= '<a href="'.route('user.edit_labour_tracker',['id'=>$row->ltracker_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','status','categories'])
                    ->make(true);
        }
    }

    public function labours_download_excel(Request $request)
    {
        $filename = 'labours_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new LabourExport($request->date,$request->project),$filename);
    }

    public function add_labour_tracker(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            app(\App\Services\LabourAttendanceService::class)->save($request);
            return redirect()->route('user.labour_trackers')->with('success', 'Attendance, wages and overtime recorded.');
        }
        if($request->has('submit'))
        {
            $rules = ['ltracker_date' => 'required',
                      'ltracker_project' => 'required',
                      'ltracker_subcontractor' => 'required',
                      'ltracker_incharge' => 'required',
                      'ltracker_shift' => 'required',
                      'ltracker_in' => 'required',
                      'ltracker_out' => 'required',
                      'ltracker_thours' => 'required'];

            $messages = ['ltracker_date.required' => 'Please Select Date',
                         'ltracker_project.required' => 'Please Select Project',
                         'ltracker_subcontractor.required' => 'Please Select Subcontractor',
                         'ltracker_incharge.required' => 'Please Select Site Incharge',
                         'ltracker_shift.required' => 'Please Select Shift',
                         'ltracker_in.required' => 'Please Select In Time',
                         'ltracker_out.required' => 'Please Select Out Time',
                         'ltracker_thours.required' => 'Please Select Times'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['ltracker_date']          = $request->ltracker_date;
                $ins['ltracker_project']       = $request->ltracker_project;
                $ins['ltracker_subcontractor'] = $request->ltracker_subcontractor;
                $ins['ltracker_incharge']      = $request->ltracker_incharge;
                $ins['ltracker_shift']         = $request->ltracker_shift;
                $ins['ltracker_remarks']       = $request->ltracker_remarks;
                $ins['ltracker_in']            = $request->ltracker_in;
                $ins['ltracker_out']           = $request->ltracker_out;
                $ins['ltracker_thours']        = $request->ltracker_thours;
                $ins['ltracker_total']         = $request->grand_total;
                $ins['ltracker_added_on']      = date('Y-m-d H:i:s');
                $ins['ltracker_added_by']      = Auth::user()->user_id;
                $ins['ltracker_updated_on']    = date('Y-m-d H:i:s');
                $ins['ltracker_updated_by']    = Auth::user()->user_id;
                $ins['ltracker_status']        = 1;
                $ins['ltracker_trash']         = 1;

                $add = LabourTracker::create($ins);

                if($add)
                {
                    if($request->has('workers') && !empty($request->workers))
                    {
                        $workers = $request->workers;

                        foreach($workers['CATEGORY'] as $key => $worker)
                        {
                            $ins_worker['lworker_tracker']    = $add->ltracker_id;
                            $ins_worker['lworker_category']   = $workers['CATEGORY'][$key];
                            $ins_worker['lworker_skill']      = $workers['SKILL'][$key];
                            $ins_worker['lworker_male']       = $workers['MALE'][$key];
                            $ins_worker['lworker_female']     = $workers['FEMALE'][$key];
                            $ins_worker['lworker_wage']       = $workers['WAGE'][$key];
                            $ins_worker['lworker_total']      = $workers['TOTAL'][$key];
                            $ins_worker['lworker_added_on']   = date('Y-m-d H:i:s');
                            $ins_worker['lworker_added_by']   = Auth::user()->user_id;
                            $ins_worker['lworker_updated_on'] = date('Y-m-d H:i:s');
                            $ins_worker['lworker_updated_by'] = Auth::user()->user_id;
                            $ins_worker['lworker_status']     = 1;

                            LabourWorker::create($ins_worker);
                        }
                    }

                    return redirect()->back()->with('success','Details Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $where_incharge['user_role'] = 7;
        $where_incharge['user_status'] = 1;
        $data['incharges'] = User::where($where_incharge)->orderby('user_name','asc')->get();

        $data['set'] = 'labour_trackers';
        return view('user.labour_tracker.add_labour_tracker',$data);
    }

    public function edit_labour_tracker(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            app(\App\Services\LabourAttendanceService::class)->save($request, (int) $request->segment(3));
            return redirect()->route('user.labour_trackers')->with('success', 'Attendance corrected. The payment balance has been recalculated.');
        }
        if($request->has('submit'))
        {
            $rules = ['ltracker_date' => 'required',
                      'ltracker_project' => 'required',
                      'ltracker_subcontractor' => 'required',
                      'ltracker_incharge' => 'required',
                      'ltracker_shift' => 'required',
                      'ltracker_in' => 'required',
                      'ltracker_out' => 'required',
                      'ltracker_thours' => 'required'];

            $messages = ['ltracker_date.required' => 'Please Select Date',
                         'ltracker_project.required' => 'Please Select Project',
                         'ltracker_subcontractor.required' => 'Please Select Subcontractor',
                         'ltracker_incharge.required' => 'Please Select Site Incharge',
                         'ltracker_shift.required' => 'Please Select Shift',
                         'ltracker_in.required' => 'Please Select In Time',
                         'ltracker_out.required' => 'Please Select Out Time',
                         'ltracker_thours.required' => 'Please Select Times'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['ltracker_date']          = $request->ltracker_date;
                $upd['ltracker_project']       = $request->ltracker_project;
                $upd['ltracker_subcontractor'] = $request->ltracker_subcontractor;
                $upd['ltracker_incharge']      = $request->ltracker_incharge;
                $upd['ltracker_shift']         = $request->ltracker_shift;
                $upd['ltracker_remarks']       = $request->ltracker_remarks;
                $upd['ltracker_in']            = $request->ltracker_in;
                $upd['ltracker_out']           = $request->ltracker_out;
                $upd['ltracker_thours']        = $request->ltracker_thours;
                $upd['ltracker_total']         = $request->grand_total;
                $upd['ltracker_updated_on']    = date('Y-m-d H:i:s');
                $upd['ltracker_updated_by']    = Auth::user()->user_id;

                LabourTracker::where('ltracker_id',$request->segment(3))->update($upd);

                LabourWorker::where('lworker_tracker',$request->segment(3))->delete();

                if($request->has('workers') && !empty($request->workers))
                {
                    $workers = $request->workers;

                    foreach($workers['CATEGORY'] as $key => $worker)
                    {
                        $ins_worker['lworker_tracker']    = $request->segment(3);
                        $ins_worker['lworker_category']   = $workers['CATEGORY'][$key];
                        $ins_worker['lworker_skill']      = $workers['SKILL'][$key];
                        $ins_worker['lworker_male']       = $workers['MALE'][$key];
                        $ins_worker['lworker_female']     = $workers['FEMALE'][$key];
                        $ins_worker['lworker_wage']       = $workers['WAGE'][$key];
                        $ins_worker['lworker_total']      = $workers['TOTAL'][$key];
                        $ins_worker['lworker_added_on']   = date('Y-m-d H:i:s');
                        $ins_worker['lworker_added_by']   = Auth::user()->user_id;
                        $ins_worker['lworker_updated_on'] = date('Y-m-d H:i:s');
                        $ins_worker['lworker_updated_by'] = Auth::user()->user_id;
                        $ins_worker['lworker_status']     = 1;

                        LabourWorker::create($ins_worker);
                    }
                }

                return redirect()->back()->with('success','Details Updated Successfully');
            }
        }

        $data['labour_tracker'] = LabourTracker::where('ltracker_id',$request->segment(3))->first();

        if(!isset($data['labour_tracker']))
        {
            return redirect()->route('user.labour_trackers');
        }

        $data['workers'] = LabourWorker::where('lworker_tracker',$request->segment(3))->get();

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $where_incharge['user_role'] = 7;
        $where_incharge['user_status'] = 1;
        $data['incharges'] = User::where($where_incharge)->orderby('user_name','asc')->get();

        $data['set'] = 'labour_trackers';
        return view('user.labour_tracker.edit_labour_tracker',$data);
    }
}
