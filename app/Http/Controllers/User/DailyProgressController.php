<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\DailyProgressExport;
use App\Models\User;
use App\Models\Subcontractor;
use App\Models\Project;
use App\Models\ProjectBoq;
use App\Models\Category;
use App\Models\DailyProgress;

class DailyProgressController extends Controller
{
    public function index()
    {
        $data['set'] = 'daily_progress';
        return view('user.daily_progress.daily_progress',$data);
    }

    public function get_daily_progress(Request $request)
    {
        $request->validate(['start_date' => 'nullable|date_format:Y-m-d', 'end_date' => 'nullable|date_format:Y-m-d', 'status' => 'nullable|integer|between:1,5']);
        if($request->ajax())
        {
            $where = 'dprogress_trash = 1';

            if(!empty($request->start_date))
            {
                $where .= ' AND dprogress_date >= "'.date('Y-m-d',strtotime($request->start_date)).'"';
            }

            if(!empty($request->end_date))
            {
                $where .= ' AND dprogress_date <= "'.date('Y-m-d',strtotime($request->end_date)).'"';
            }

            if(!empty($request->status))
            {
                $where .= ' AND dprogress_status = '.$request->status;
            }

            $data = DailyProgress::whereRaw($where)->orderby('dprogress_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('approved_quantity', function ($row) {
                        if (!app(\App\Services\WorkflowSchema::class)->ready()) { return 'Not verified'; }
                        return \Illuminate\Support\Facades\DB::table('progress_reviews')->where('progress_id', $row->dprogress_id)->value('approved_qty') ?? 0;
                    })
                    ->addColumn('verification', function ($row) {
                        if (!app(\App\Services\WorkflowSchema::class)->ready()) { return 'Awaiting activation'; }
                        $state = \Illuminate\Support\Facades\DB::table('progress_reviews')->where('progress_id', $row->dprogress_id)->value('state');
                        return $state === 'legacy' ? 'Unverified historic entry' : ucfirst($state ?? 'pending');
                    })
                    ->addColumn('work_date', function($row){

                        $work_date = date('d M Y',strtotime($row->dprogress_date));

                        return $work_date;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('category_name', function($row){

                        $category_name = $row->category->category_name;

                        return $category_name;
                    })
                    ->addColumn('boq_name', function($row){

                        $boq_name = $row->boq->pboq_item;

                        return $boq_name;
                    })
                    ->addColumn('progress', function($row){
                        
                        $rowProgressColor = $row->dprogress_pdone >= 100 ? '#00b884' : ($row->dprogress_pdone >= 60 ? '#ffc221' : '#ff4d57');

                        $progress = '<div class="boq-row-progress-wrap" style="--boq-row-progress: '.$row->dprogress_pdone.'%; --boq-row-progress-color: '.$rowProgressColor.'" aria-label="'.$row->dprogress_pdone.'% complete">
                           <div class="boq-row-progress">
                              <span></span>
                           </div>
                           <span class="boq-row-progress-percent">'.$row->dprogress_pdone.'%</span>
                        </div>';

                        return $progress;
                    })
                    ->addColumn('carry_forward', function($row){

                        if($row->dprogress_cforward == 1)
                        {
                            $carry_forward = '<span class="badge bg-warning"><i class="ti ti-reload"></i> Yes</span>';
                        }
                        elseif($row->dprogress_cforward == 2)
                        {
                            $carry_forward = 'No';
                        }

                        return $carry_forward;
                    })
                    ->addColumn('DT_RowClass', function($row){

                        return $row->dprogress_cforward == 1 ? 'daily-progress-carry-forward' : '';
                    })
                    ->addColumn('status', function($row){

                        if($row->dprogress_status == 1)
                        {
                            $status = '<span class="badge bg-success">Completed</span>';
                        }
                        elseif($row->dprogress_status == 2)
                        {
                            $status = '<span class="badge bg-secondary">Partial</span>';
                        }
                        elseif($row->dprogress_status == 3)
                        {
                            $status = '<span class="badge bg-danger">Delayed - Carry Forward</span>';
                        }
                        elseif($row->dprogress_status == 4)
                        {
                            $status = '<span class="badge bg-warning">Cancelled</span>';
                        }
                        elseif($row->dprogress_status == 5)
                        {
                            $status = '<span class="badge bg-primary">Not Started</span>';
                        }

                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';
                        if (app(\App\Services\WorkflowSchema::class)->ready()) {
                            $btn .= '<a class="btn btn-icon btn-sm btn-white" title="Evidence and approval history" href="'.route('user.progress_review', $row->dprogress_id).'"><i class="ti ti-clipboard-check"></i></a> ';
                        }
                        
                        if(Helper::userHasPermission('user.edit_daily_progress'))
                        {
                            $btn .= '<a href="'.route('user.edit_daily_progress',['id'=>$row->dprogress_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.daily_progress_status'))
                        {
                            $btn .= '<a href="#" class="btn btn-icon btn-sm btn-dark" data-st-click="daily_progress_status('.$row->dprogress_id.')" title="Status"><i class="ti ti-check"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','progress','carry_forward','status'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function progress_download_excel(Request $request)
    {
        $filename = 'progress_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new DailyProgressExport($request->start_date,$request->end_date,$request->status),$filename);
    }

    public function add_daily_progress(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            $entry = app(\App\Services\ProgressWorkflow::class)->save($request);
            return redirect()->route('user.progress_review', $entry->dprogress_id)->with('success', 'Progress submitted for independent review.');
        }
        if($request->has('submit'))
        {
            $rules = ['dprogress_date' => 'required',
                      'dprogress_project' => 'required',
                      'dprogress_category' => 'required',
                      'dprogress_description' => 'required',
                      'dprogress_boq' => 'required',
                      'dprogress_qty' => 'required',
                      'dprogress_location' => 'required',
                      'dprogress_subcontractor' => 'required',
                      'dprogress_incharge' => 'required'];

            $messages = ['dprogress_date.required' => 'Please Select Date',
                         'dprogress_project.required' => 'Please Select Project',
                         'dprogress_category.required' => 'Please Enter Category',
                         'dprogress_description.required' => 'Please Enter Description',
                         'dprogress_boq.required' => 'Please Select BOQ Item',
                         'dprogress_qty.required' => 'Please Enter Quantity Done',
                         'dprogress_location.required' => 'Please Enter Location',
                         'dprogress_subcontractor.required' => 'Please Select Subcontractor',
                         'dprogress_incharge.required' => 'Please Select Site Incharge'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['dprogress_date']          = $request->dprogress_date;
                $ins['dprogress_project']       = $request->dprogress_project;
                $ins['dprogress_category']      = $request->dprogress_category;
                $ins['dprogress_description']   = $request->dprogress_description;
                $ins['dprogress_boq']           = $request->dprogress_boq;
                $ins['dprogress_qty']           = $request->dprogress_qty;
                $ins['dprogress_pdone']         = $request->dprogress_pdone;
                $ins['dprogress_location']      = $request->dprogress_location;
                $ins['dprogress_subcontractor'] = $request->dprogress_subcontractor;
                $ins['dprogress_incharge']      = $request->dprogress_incharge;
                $ins['dprogress_remarks']       = $request->dprogress_remarks;
                $ins['dprogress_cforward']      = $request->dprogress_cforward;
                $ins['dprogress_added_on']      = date('Y-m-d H:i:s');
                $ins['dprogress_added_by']      = Auth::user()->user_id;
                $ins['dprogress_updated_on']    = date('Y-m-d H:i:s');
                $ins['dprogress_updated_by']    = Auth::user()->user_id;
                $ins['dprogress_status']        = 5;
                $ins['dprogress_trash']         = 1;

                $add = DailyProgress::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Daily Progress Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $where_incharge['user_role'] = 7;
        $where_incharge['user_status'] = 1;
        $data['incharges'] = User::where($where_incharge)->orderby('user_name','asc')->get();

        $data['set'] = 'daily_progress';
        return view('user.daily_progress.add_daily_progress',$data);
    }

    public function select_boq(Request $request)
    {
        $data['dboqs'] = Helper::doneBoq($request->project_id);

        $where_boq['pboq_project'] = $request->project_id;
        $where_boq['pboq_status'] = 1;
        $data['boqs'] = ProjectBoq::where($where_boq)->get();

        return view('user.daily_progress.select_boq',$data);
    }

    public function edit_daily_progress(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            $entry = app(\App\Services\ProgressWorkflow::class)->save($request, (int) $request->route('id'));
            return redirect()->route('user.progress_review', $entry->dprogress_id)->with('success', 'Correction recorded. The new revision needs approval.');
        }
        if($request->has('submit'))
        {
            $rules = ['dprogress_date' => 'required',
                      'dprogress_project' => 'required',
                      'dprogress_category' => 'required',
                      'dprogress_description' => 'required',
                      'dprogress_boq' => 'required',
                      'dprogress_qty' => 'required',
                      'dprogress_location' => 'required',
                      'dprogress_subcontractor' => 'required',
                      'dprogress_incharge' => 'required'];

            $messages = ['dprogress_date.required' => 'Please Select Date',
                         'dprogress_project.required' => 'Please Select Project',
                         'dprogress_category.required' => 'Please Enter Category',
                         'dprogress_description.required' => 'Please Enter Description',
                         'dprogress_boq.required' => 'Please Select BOQ Item',
                         'dprogress_qty.required' => 'Please Enter Quantity Done',
                         'dprogress_location.required' => 'Please Enter Location',
                         'dprogress_subcontractor.required' => 'Please Select Subcontractor',
                         'dprogress_incharge.required' => 'Please Select Site Incharge'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['dprogress_date']          = $request->dprogress_date;
                $upd['dprogress_project']       = $request->dprogress_project;
                $upd['dprogress_category']      = $request->dprogress_category;
                $upd['dprogress_description']   = $request->dprogress_description;
                $upd['dprogress_boq']           = $request->dprogress_boq;
                $upd['dprogress_qty']           = $request->dprogress_qty;
                $upd['dprogress_pdone']         = $request->dprogress_pdone;
                $upd['dprogress_location']      = $request->dprogress_location;
                $upd['dprogress_subcontractor'] = $request->dprogress_subcontractor;
                $upd['dprogress_incharge']      = $request->dprogress_incharge;
                $upd['dprogress_remarks']       = $request->dprogress_remarks;
                $upd['dprogress_cforward']      = $request->dprogress_cforward;
                $upd['dprogress_updated_on']    = date('Y-m-d H:i:s');
                $upd['dprogress_updated_by']    = Auth::user()->user_id;

                DailyProgress::where('dprogress_id',$request->route('id'))->update($upd);

                return redirect()->back()->with('success','Daily Progress Updated Successfully');
            }
        }

        $data['daily_progress'] = DailyProgress::where('dprogress_id',$request->route('id'))->first();

        if(!isset($data['daily_progress']))
        {
            return redirect()->route('user.daily_progress');
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['dboqs'] = Helper::doneBoq($data['daily_progress']->dprogress_project);

        $where_boq['pboq_project'] = $data['daily_progress']->dprogress_project;
        $where_boq['pboq_status'] = 1;
        $data['boqs'] = ProjectBoq::where($where_boq)->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $where_incharge['user_role'] = 7;
        $where_incharge['user_status'] = 1;
        $data['incharges'] = User::where($where_incharge)->orderby('user_name','asc')->get();

        $data['set'] = 'daily_progress';
        return view('user.daily_progress.edit_daily_progress',$data);
    }

    public function daily_progress_status(Request $request)
    {
        $data['daily_progress'] = DailyProgress::where('dprogress_id',$request->dprogress_id)->first();

        $data['set'] = 'daily_progress';
        return view('user.daily_progress.daily_progress_status',$data);
    }

    public function add_daily_progress_status(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            abort_unless(Helper::userHasPermission('user.daily_progress_status'), 403);
            app(\App\Services\ProgressWorkflow::class)->changeWorkStatus($request);
            return redirect()->back()->with('success', 'Work status recorded. This revision needs verification.');
        }
        if($request->has('submit'))
        {
            $rules = ['status' => 'required'];

            $messages = ['status.required' => 'Please Select Status'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['dprogress_status']     = $request->status;
                $upd['dprogress_updated_on'] = date('Y-m-d H:i:s');
                $upd['dprogress_updated_by'] = Auth::user()->user_id;

                DailyProgress::where('dprogress_id',$request->dprogress_id)->update($upd);

                return redirect()->back()->with('success','Status Updated Successfully');
            }
        }
    }
}
