<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Imports\BoqImport;
use App\Models\Client;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectBoq;
use App\Models\DailyProgress;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => 'nullable|string|max:100']);
        $query = Project::with(['client', 'engineer'])->where('project_trash', 1);
        if ($request->filled('search')) {
            $query->where('project_name', 'like', '%'.$request->search.'%');
        }
        $data['projects'] = $query->orderBy('project_name')->paginate(12)->withQueryString();
        $data['boqStats'] = \Illuminate\Support\Facades\DB::table('project_boqs')
            ->selectRaw('pboq_project, COUNT(*) as items, SUM(pboq_amount) as budget')->groupBy('pboq_project')->get()->keyBy('pboq_project');
        $data['reportedValue'] = \Illuminate\Support\Facades\DB::table('daily_progress as p')
            ->join('project_boqs as b', 'b.pboq_id', '=', 'p.dprogress_boq')->where('p.dprogress_trash', 1)
            ->selectRaw('p.dprogress_project, SUM(p.dprogress_qty * b.pboq_rate) as value')->groupBy('p.dprogress_project')->pluck('value', 'dprogress_project');
        $data['approvedValue'] = app(\App\Services\WorkflowSchema::class)->ready()
            ? \Illuminate\Support\Facades\DB::table('progress_reviews as r')->join('daily_progress as p', 'p.dprogress_id', '=', 'r.progress_id')
                ->join('project_boqs as b', 'b.pboq_id', '=', 'p.dprogress_boq')->where('p.dprogress_trash', 1)->where('r.state', 'approved')
                ->selectRaw('p.dprogress_project, SUM(r.approved_qty * b.pboq_rate) as value')->groupBy('p.dprogress_project')->pluck('value', 'dprogress_project')
            : collect();
        $data['set'] = 'projects';
        return view('user.project.projects',$data);
    }

    public function get_projects(Request $request)
    {
        if($request->ajax())
        {
            $data = Project::orderby('project_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('client_name', function($row){

                        $client_name = $row->client->client_name;

                        return $client_name;
                    })
                    ->addColumn('engineer_name', function($row){

                        $engineer_name = $row->engineer->user_name;

                        return $engineer_name;
                    })
                    ->addColumn('period', function($row){

                        $period = date('d M Y',strtotime($row->project_start)).' - '.date('d M Y',strtotime($row->project_end));

                        return $period;
                    })
                    ->addColumn('budget', function($row){

                        $budget = Helper::moneyFormat($row->project_budget);

                        return $budget;
                    })
                    ->addColumn('boq_items', function($row){

                        $boq_items = ProjectBoq::where('pboq_project',$row->project_id)->count();

                        return $boq_items;
                    })
                    ->addColumn('progress', function($row){

                        $boqs = ProjectBoq::where('pboq_project',$row->project_id)->get();
                        $boq_total = array_sum(array_column($boqs->toArray(),'pboq_amount'));

                        $all_progress = DailyProgress::join('project_boqs','project_boqs.pboq_id', 'daily_progress.dprogress_boq')->where('dprogress_project',$row->project_id)->get();

                        $progress_amount = array();

                        foreach($all_progress as $progress)
                        {
                            $progress_qty[$progress->dprogress_boq][] = $progress->dprogress_qty;
                            $progress_amount[] = $progress->dprogress_qty * $progress->pboq_rate;
                        }

                        $done_value = array_sum($progress_amount); 
                        if($boq_total == 0)
                        {
                            $total_average = 0;
                        }
                        else
                        {
                            $total_average = ($done_value / $boq_total) * 100;
                            $total_average = round($total_average);   
                        }

                        $rowProgressColor = $total_average >= 100 ? '#00b884' : ($total_average >= 60 ? '#ffc221' : '#ff4d57');

                        $progress = '<div class="boq-row-progress-wrap" style="--boq-row-progress: '.$total_average.'%; --boq-row-progress-color: '.$rowProgressColor.'" aria-label="'.$total_average.'% complete">
                           <div class="boq-row-progress">
                              <span></span>
                           </div>
                           <span class="boq-row-progress-percent">'.$total_average.'%</span>
                        </div>';

                        return $progress;
                    })
                    ->addColumn('status', function($row){

                        if($row->project_status == 1)
                        {
                            $status = '<span class="badge bg-primary">Active</span>';
                        }
                        elseif($row->project_status == 2)
                        {
                            $status = '<span class="badge bg-danger">On Hold</span>';
                        }
                        elseif($row->project_status == 3)
                        {
                            $status = '<span class="badge bg-success">Completed</span>';
                        }

                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.view_project'))
                        {
                            $btn .= '<a href="'.route('user.view_project',['id'=>$row->project_id]).'" class="btn btn-icon btn-sm btn-info" title="View"><i class="ti ti-eye"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.edit_project'))
                        {
                            $btn .= '<a href="'.route('user.edit_project',['id'=>$row->project_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.project_status'))
                        {
                            $btn .= '<a href="#" class="btn btn-icon btn-sm btn-dark" data-st-click="project_status('.$row->project_id.')" title="Status"><i class="ti ti-check"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','progress','status'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_project(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['project_name' => 'required',
                      'project_location' => 'required',
                      'project_client' => 'required',
                      'project_engineer' => 'required',
                      'project_start' => 'required',
                      'project_end' => 'required',
                      'project_budget' => 'required'];

            $messages = ['project_name.required' => 'Please Enter Project',
                         'project_location.required' => 'Please Enter Location',
                         'project_client.required' => 'Please Select Client',
                         'project_engineer.required' => 'Please Select Site Engineer',
                         'project_start.required' => 'Please Select Start Date',
                         'project_end.required' => 'Please Select End Date',
                         'project_budget.required' => 'Please Enter Budget'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_project = Project::where('project_name',$request->project_name)->count();

                if($check_project > 0)
                {
                    return redirect()->back()->with('error','Project Already Added');
                }

                $ins['project_name']       = $request->project_name;
                $ins['project_location']   = $request->project_location;
                $ins['project_client']     = $request->project_client;
                $ins['project_engineer']   = $request->project_engineer;
                $ins['project_start']      = $request->project_start;
                $ins['project_end']        = $request->project_end;
                $ins['project_budget']     = $request->project_budget;
                $ins['project_added_on']   = date('Y-m-d H:i:s');
                $ins['project_added_by']   = Auth::user()->user_id;
                $ins['project_updated_on'] = date('Y-m-d H:i:s');
                $ins['project_updated_by'] = Auth::user()->user_id;
                $ins['project_status']     = 1;
                $ins['project_trash']      = 1;

                try {
                $add = \Illuminate\Support\Facades\DB::transaction(function () use ($ins, $request) {
                    $project = Project::create($ins);
                    Excel::import(new BoqImport($project->project_id), $request->boq_file);
                    return $project;
                });
                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['boq_file' => 'The BOQ has invalid rows. Check item names, units, positive quantities and non-negative rates.']);
                }

                if($add)
                {

                    return redirect()->back()->with('success','Project Added Successfully');
                }
            }
        }

        $data['clients'] = Client::where('client_status',1)->orderby('client_name','asc')->get();

        $where_engineer['user_role'] = 3;
        $where_engineer['user_status'] = 1;
        $data['engineers'] = User::where($where_engineer)->orderby('user_name','asc')->get();

        $data['set'] = 'projects';
        return view('user.project.add_project',$data);
    }

    public function edit_project(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['project_name' => 'required',
                      'project_location' => 'required',
                      'project_client' => 'required',
                      'project_engineer' => 'required',
                      'project_start' => 'required',
                      'project_end' => 'required',
                      'project_budget' => 'required'];

            $messages = ['project_name.required' => 'Please Enter Project',
                         'project_location.required' => 'Please Enter Location',
                         'project_client.required' => 'Please Select Client',
                         'project_engineer.required' => 'Please Select Site Engineer',
                         'project_start.required' => 'Please Select Start Date',
                         'project_end.required' => 'Please Select End Date',
                         'project_budget.required' => 'Please Enter Budget'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_project = Project::where('project_name',$request->project_name)->where('project_id','!=',$request->route('id'))->count();

                if($check_project > 0)
                {
                    return redirect()->back()->with('error','Project Already Added');
                }

                $upd['project_name']       = $request->project_name;
                $upd['project_location']   = $request->project_location;
                $upd['project_client']     = $request->project_client;
                $upd['project_engineer']   = $request->project_engineer;
                $upd['project_start']      = $request->project_start;
                $upd['project_end']        = $request->project_end;
                $upd['project_budget']     = $request->project_budget;
                $upd['project_updated_on'] = date('Y-m-d H:i:s');
                $upd['project_updated_by'] = Auth::user()->user_id;

                Project::where('project_id',$request->route('id'))->update($upd);

                return redirect()->back()->with('success','Project Updated Successfully');
            }
        }

        $data['project'] = Project::where('project_id',$request->route('id'))->first();

        if(!isset($data['project']))
        {
            return redirect()->route('user.projects');
        }

        $data['clients'] = Client::where('client_status',1)->orderby('client_name','asc')->get();

        $where_engineer['user_role'] = 3;
        $where_engineer['user_status'] = 1;
        $data['engineers'] = User::where($where_engineer)->orderby('user_name','asc')->get();

        $data['set'] = 'projects';
        return view('user.project.edit_project',$data);
    }

    public function view_project(Request $request)
    {
        $project = Project::where('project_trash', 1)->findOrFail($request->route('id'));
        $request->session()->put('workspace_project_id', $project->project_id);
        return redirect()->route('user.project_workspace');
    }

    public function project_status(Request $request)
    {
        $data['project'] = Project::where('project_id',$request->project_id)->first();

        $data['set'] = 'projects';
        return view('user.project.project_status',$data);
    }

    public function add_project_status(Request $request)
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
                $upd['project_status']     = $request->status;
                $upd['project_updated_on'] = date('Y-m-d H:i:s');
                $upd['project_updated_by'] = Auth::user()->user_id;

                Project::where('project_id',$request->project_id)->update($upd);

                return redirect()->back()->with('success','Status Updated Successfully');
            }
        }
    }
}
