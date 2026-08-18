<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\DailyWorkExport;
use App\Models\User;
use App\Models\Subcontractor;
use App\Models\Project;
use App\Models\Category;
use App\Models\DailyWork;

class DailyWorkController extends Controller
{
    public function index()
    {
        $data['set'] = 'daily_works';
        return view('user.daily_work.daily_works',$data);
    }

    public function get_daily_works(Request $request)
    {
        if($request->ajax())
        {
            $where = 'dwork_trash = 1';

            if(!empty($request->start_date))
            {
                $where .= ' AND dwork_date >= "'.date('Y-m-d',strtotime($request->start_date)).'"';
            }

            if(!empty($request->end_date))
            {
                $where .= ' AND dwork_date <= "'.date('Y-m-d',strtotime($request->end_date)).'"';
            }

            if(!empty($request->status))
            {
                $where .= ' AND dwork_status = '.$request->status;
            }

            $data = DailyWork::whereRaw($where)->orderby('dwork_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('work_date', function($row){

                        $work_date = date('d M Y',strtotime($row->dwork_date));

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
                    ->addColumn('carry_forward', function($row){

                        if($row->dwork_cforward == 1)
                        {
                            $carry_forward = '<span class="badge bg-warning"><i class="ti ti-reload"></i> Yes</span>';
                        }
                        elseif($row->dwork_cforward == 2)
                        {
                            $carry_forward = 'No';
                        }

                        return $carry_forward;
                    })
                    ->addColumn('DT_RowClass', function($row){

                        return $row->dwork_cforward == 1 ? 'daily-work-carry-forward' : '';
                    })
                    ->addColumn('status', function($row){

                        if($row->dwork_status == 1)
                        {
                            $status = '<span class="badge bg-success">Completed</span>';
                        }
                        elseif($row->dwork_status == 2)
                        {
                            $status = '<span class="badge bg-secondary">Partial</span>';
                        }
                        elseif($row->dwork_status == 3)
                        {
                            $status = '<span class="badge bg-danger">Delayed - Carry Forward</span>';
                        }
                        elseif($row->dwork_status == 4)
                        {
                            $status = '<span class="badge bg-warning">Cancelled</span>';
                        }
                        elseif($row->dwork_status == 5)
                        {
                            $status = '<span class="badge bg-primary">Not Started</span>';
                        }

                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_daily_work'))
                        {
                            $btn .= '<a href="'.route('user.edit_daily_work',['id'=>$row->dwork_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.daily_work_status'))
                        {
                            $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-dark" onclick="daily_work_status('.$row->dwork_id.')" title="Status"><i class="ti ti-check"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','carry_forward','status'])
                    ->make(true);
        }
    }

    public function works_download_excel(Request $request)
    {
        $filename = 'works_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new DailyWorkExport($request->start_date,$request->end_date,$request->status),$filename);
    }

    public function add_daily_work(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['dwork_date' => 'required',
                      'dwork_project' => 'required',
                      'dwork_description' => 'required',
                      'dwork_location' => 'required',
                      'dwork_qty' => 'required',
                      'dwork_category' => 'required',
                      'dwork_subcontractor' => 'required',
                      'dwork_incharge' => 'required'];

            $messages = ['dwork_date.required' => 'Please Select Date',
                         'dwork_project.required' => 'Please Select Project',
                         'dwork_description.required' => 'Please Enter Description',
                         'dwork_location.required' => 'Please Enter Location',
                         'dwork_qty.required' => 'Please Enter Quantity',
                         'dwork_category.required' => 'Please Select Category',
                         'dwork_subcontractor.required' => 'Please Select Subcontractor',
                         'dwork_incharge.required' => 'Please Select Site Incharge'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['dwork_date']          = $request->dwork_date;
                $ins['dwork_project']       = $request->dwork_project;
                $ins['dwork_description']   = $request->dwork_description;
                $ins['dwork_location']      = $request->dwork_location;
                $ins['dwork_qty']           = $request->dwork_qty;
                $ins['dwork_category']      = $request->dwork_category;
                $ins['dwork_subcontractor'] = $request->dwork_subcontractor;
                $ins['dwork_incharge']      = $request->dwork_incharge;
                $ins['dwork_remarks']       = $request->dwork_remarks;
                $ins['dwork_cforward']      = $request->dwork_cforward;
                $ins['dwork_added_on']      = date('Y-m-d H:i:s');
                $ins['dwork_added_by']      = Auth::user()->user_id;
                $ins['dwork_updated_on']    = date('Y-m-d H:i:s');
                $ins['dwork_updated_by']    = Auth::user()->user_id;
                $ins['dwork_status']        = 5;
                $ins['dwork_trash']         = 1;

                $add = DailyWork::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Daily Work Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $where_incharge['user_role'] = 7;
        $where_incharge['user_status'] = 1;
        $data['incharges'] = User::where($where_incharge)->orderby('user_name','asc')->get();

        $data['set'] = 'daily_works';
        return view('user.daily_work.add_daily_work',$data);
    }

    public function edit_daily_work(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['dwork_date' => 'required',
                      'dwork_project' => 'required',
                      'dwork_description' => 'required',
                      'dwork_location' => 'required',
                      'dwork_qty' => 'required',
                      'dwork_category' => 'required',
                      'dwork_subcontractor' => 'required',
                      'dwork_incharge' => 'required'];

            $messages = ['dwork_date.required' => 'Please Select Date',
                         'dwork_project.required' => 'Please Select Project',
                         'dwork_description.required' => 'Please Enter Description',
                         'dwork_location.required' => 'Please Enter Location',
                         'dwork_qty.required' => 'Please Enter Quantity',
                         'dwork_category.required' => 'Please Select Category',
                         'dwork_subcontractor.required' => 'Please Select Subcontractor',
                         'dwork_incharge.required' => 'Please Select Site Incharge'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['dwork_date']          = $request->dwork_date;
                $upd['dwork_project']       = $request->dwork_project;
                $upd['dwork_description']   = $request->dwork_description;
                $upd['dwork_location']      = $request->dwork_location;
                $upd['dwork_qty']           = $request->dwork_qty;
                $upd['dwork_category']      = $request->dwork_category;
                $upd['dwork_subcontractor'] = $request->dwork_subcontractor;
                $upd['dwork_incharge']      = $request->dwork_incharge;
                $upd['dwork_remarks']       = $request->dwork_remarks;
                $upd['dwork_cforward']      = $request->dwork_cforward;
                $upd['dwork_updated_on']    = date('Y-m-d H:i:s');
                $upd['dwork_updated_by']    = Auth::user()->user_id;

                DailyWork::where('dwork_id',$request->segment(3))->update($upd);

                return redirect()->back()->with('success','Daily Work Updated Successfully');
            }
        }

        $data['daily_work'] = DailyWork::where('dwork_id',$request->segment(3))->first();

        if(!isset($data['daily_work']))
        {
            return redirect()->route('user.daily_works');
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $where_incharge['user_role'] = 7;
        $where_incharge['user_status'] = 1;
        $data['incharges'] = User::where($where_incharge)->orderby('user_name','asc')->get();

        $data['set'] = 'daily_works';
        return view('user.daily_work.edit_daily_work',$data);
    }

    public function daily_work_status(Request $request)
    {
        $data['daily_work'] = DailyWork::where('dwork_id',$request->dwork_id)->first();

        $data['set'] = 'daily_works';
        return view('user.daily_work.daily_work_status',$data);
    }

    public function add_daily_work_status(Request $request)
    {
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
                $upd['dwork_status']     = $request->status;
                $upd['dwork_updated_on'] = date('Y-m-d H:i:s');
                $upd['dwork_updated_by'] = Auth::user()->user_id;

                DailyWork::where('dwork_id',$request->dwork_id)->update($upd);

                return redirect()->back()->with('success','Status Updated Successfully');
            }
        }
    }
}
