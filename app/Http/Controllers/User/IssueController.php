<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\IssueExport;
use App\Models\User;
use App\Models\Project;
use App\Models\IssueCategory;
use App\Models\Issue;

class IssueController extends Controller
{
    public function index()
    {
        $data['total_open'] = Issue::where('issue_status',1)->count();
        $data['total_inprogress'] = Issue::where('issue_status',2)->count();
        $data['total_resolved'] = Issue::where('issue_status',3)->count();
        $data['total_closed'] = Issue::where('issue_status',4)->count();

        $data['set'] = 'issues';
        return view('user.issue.issues',$data);
    }

    public function get_issues(Request $request)
    {
        if($request->ajax())
        {
            $data = Issue::orderby('issue_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('issue_date', function($row){

                        $issue_date = date('d M Y',strtotime($row->issue_date));

                        return $issue_date;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('category_name', function($row){

                        $category_name = $row->category->icategory_name;

                        return $category_name;
                    })
                    ->addColumn('assigned_to', function($row){

                        $assigned_to = $row->engineer->user_name;

                        return $assigned_to;
                    })
                    ->addColumn('raised_by', function($row){

                        $raised_by = $row->raise->user_name;

                        return $raised_by;
                    })
                    ->addColumn('priority', function($row){

                        if($row->issue_priority == 1)
                        {
                            $priority = '<span class="badge bg-info">Low</span>';
                        }
                        elseif($row->issue_priority == 2)
                        {
                            $priority = '<span class="badge bg-primary">Medium</span>';
                        }
                        elseif($row->issue_priority == 3)
                        {
                            $priority = '<span class="badge bg-warning">High</span>';
                        }
                        elseif($row->issue_priority == 4)
                        {
                            $priority = '<span class="badge bg-danger">Critical</span>';
                        }

                        return $priority;
                    })
                    ->addColumn('status', function($row){

                        if($row->issue_status == 1)
                        {
                            $status = '<span class="badge bg-danger">Open</span>';
                        }
                        elseif($row->issue_status == 2)
                        {
                            $status = '<span class="badge bg-info">In Progress</span>';
                        }
                        elseif($row->issue_status == 3)
                        {
                            $status = '<span class="badge bg-success">Resolved</span>';
                        }
                        elseif($row->issue_status == 4)
                        {
                            $status = '<span class="badge bg-warning">Closed</span>';
                        }

                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_issue'))
                        {
                            $btn .= '<a href="'.route('user.edit_issue',['id'=>$row->issue_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.issue_status'))
                        {
                            $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-dark" onclick="issue_status('.$row->issue_id.')" title="Status"><i class="ti ti-check"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','priority','status'])
                    ->make(true);
        }
    }

    public function issues_download_excel(Request $request)
    {
        $filename = 'issues_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new IssueExport,$filename);
    }

    public function add_issue(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['issue_date' => 'required',
                      'issue_project' => 'required',
                      'issue_title' => 'required',
                      'issue_description' => 'required',
                      'issue_category' => 'required',
                      'issue_priority' => 'required',
                      'issue_assign' => 'required'];

            $messages = ['issue_date.required' => 'Please Select Date',
                         'issue_project.required' => 'Please Select Project',
                         'issue_title.required' => 'Please Enter Title',
                         'issue_description.required' => 'Please Enter Description',
                         'issue_category.required' => 'Please Select Category',
                         'issue_priority.required' => 'Please Select Priority',
                         'issue_assign.required' => 'Please Select Assigned To'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['issue_date']        = $request->issue_date;
                $ins['issue_project']     = $request->issue_project;
                $ins['issue_title']       = $request->issue_title;
                $ins['issue_description'] = $request->issue_description;
                $ins['issue_category']    = $request->issue_category;
                $ins['issue_priority']    = $request->issue_priority;
                $ins['issue_assign']      = $request->issue_assign;
                $ins['issue_remarks']     = $request->issue_remarks;
                $ins['issue_added_on']    = date('Y-m-d H:i:s');
                $ins['issue_added_by']    = Auth::user()->user_id;
                $ins['issue_updated_on']  = date('Y-m-d H:i:s');
                $ins['issue_updated_by']  = Auth::user()->user_id;
                $ins['issue_status']      = 1;

                if($request->hasFile('issue_image'))
                {
                    $issue_image = $request->issue_image->store('assets/admin/uploads/issue');

                    $issue_image = explode('/',$issue_image);
                    $issue_image = end($issue_image);
                    $ins['issue_image'] = $issue_image;
                }

                $add = Issue::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Issue Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = IssueCategory::where('icategory_status',1)->orderby('icategory_name','asc')->get();

        $where_engineer['user_role'] = 3;
        $where_engineer['user_status'] = 1;
        $data['engineers'] = User::where($where_engineer)->orderby('user_name','asc')->get();

        $data['set'] = 'issues';
        return view('user.issue.add_issue',$data);
    }

    public function edit_issue(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['issue_date' => 'required',
                      'issue_project' => 'required',
                      'issue_title' => 'required',
                      'issue_description' => 'required',
                      'issue_category' => 'required',
                      'issue_priority' => 'required',
                      'issue_assign' => 'required'];

            $messages = ['issue_date.required' => 'Please Select Date',
                         'issue_project.required' => 'Please Select Project',
                         'issue_title.required' => 'Please Enter Title',
                         'issue_description.required' => 'Please Enter Description',
                         'issue_category.required' => 'Please Select Category',
                         'issue_priority.required' => 'Please Select Priority',
                         'issue_assign.required' => 'Please Select Assigned To'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['issue_date']        = $request->issue_date;
                $upd['issue_project']     = $request->issue_project;
                $upd['issue_title']       = $request->issue_title;
                $upd['issue_description'] = $request->issue_description;
                $upd['issue_category']    = $request->issue_category;
                $upd['issue_priority']    = $request->issue_priority;
                $upd['issue_assign']      = $request->issue_assign;
                $upd['issue_remarks']     = $request->issue_remarks;
                $upd['issue_updated_on']  = date('Y-m-d H:i:s');
                $upd['issue_updated_by']  = Auth::user()->user_id;

                if($request->hasFile('issue_image'))
                {
                    $issue_image = $request->issue_image->store('assets/admin/uploads/issue');

                    $issue_image = explode('/',$issue_image);
                    $issue_image = end($issue_image);
                    $upd['issue_image'] = $issue_image;
                }

                Issue::where('issue_id',$request->segment(3))->update($upd);

                return redirect()->back()->with('success','Issue Updated Successfully');
            }
        }

        $data['issue'] = Issue::where('issue_id',$request->segment(3))->first();

        if(!isset($data['issue']))
        {
            return redirect()->route('user.issues');
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['categories'] = IssueCategory::where('icategory_status',1)->orderby('icategory_name','asc')->get();

        $where_engineer['user_role'] = 3;
        $where_engineer['user_status'] = 1;
        $data['engineers'] = User::where($where_engineer)->orderby('user_name','asc')->get();

        $data['set'] = 'issues';
        return view('user.issue.edit_issue',$data);
    }

    public function issue_status(Request $request)
    {
        $data['issue'] = Issue::where('issue_id',$request->issue_id)->first();

        $data['set'] = 'issues';
        return view('user.issue.issue_status',$data);
    }

    public function add_issue_status(Request $request)
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
                $upd['issue_sremarks']   = $request->issue_sremarks;
                $upd['issue_status']     = $request->status;
                $upd['issue_updated_on'] = date('Y-m-d H:i:s');
                $upd['issue_updated_by'] = Auth::user()->user_id;

                Issue::where('issue_id',$request->issue_id)->update($upd);

                return redirect()->back()->with('success','Status Updated Successfully');
            }
        }
    }
}
