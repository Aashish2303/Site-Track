<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Project;
use App\Models\ProjectBoq;

class BoqTrackerController extends Controller
{
    public function index(Request $request)
    {
        $data['all_projects'] = Project::where('project_trash',1)->orderby('project_name','asc')->get();

        if($request->has('project') && !empty($request->project))
        {
            $where_project['project_id'] = $request->project;
        }

        $where_project['project_trash'] = 1;
        $data['projects'] = Project::where($where_project)->orderby('project_id','desc')->get();

        $data['set'] = 'boq_tracker';
        return view('user.boq_tracker.boq_tracker',$data);
    }
    
    public function add_boq_item(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['pboq_item' => 'required',
                      'pboq_unit' => 'required',
                      'pboq_qty' => 'required',
                      'pboq_rate' => 'required'];

            $messages = ['pboq_item.required' => 'Please Enter Item',
                         'pboq_unit.required' => 'Please Enter Unit',
                         'pboq_qty.required' => 'Please Enter Quantity',
                         'pboq_rate.required' => 'Please Enter Rate'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else 
            {
                $ins['pboq_project']    = $request->project_id;
                $ins['pboq_item']       = $request->pboq_item;
                $ins['pboq_unit']       = $request->pboq_unit;
                $ins['pboq_qty']        = $request->pboq_qty;
                $ins['pboq_rate']       = $request->pboq_rate;
                $ins['pboq_amount']     = $request->pboq_qty * $request->pboq_rate;
                $ins['pboq_remarks']    = $request->pboq_remarks;
                $ins['pboq_extra']      = 1;
                $ins['pboq_added_on']   = date('Y-m-d H:i:s');
                $ins['pboq_added_by']   = Auth::user()->user_id;
                $ins['pboq_updated_on'] = date('Y-m-d H:i:s');
                $ins['pboq_updated_by'] = Auth::user()->user_id;
                $ins['pboq_status']     = 1;

                ProjectBoq::create($ins);

                return redirect()->back()->with('success', 'Extra Item Successfully');
            }
        }
    }

    public function get_boq_project_details(Request $request)
    {
        $data['project_id'] = $request->project_id;

        $data['set'] = 'boq_tracker';
        return view('user.boq_tracker.edit_boq_project', $data);
    }

    public function edit_boq_item(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['pboq_item' => 'required',
                      'pboq_unit' => 'required',
                      'pboq_qty' => 'required',
                      'pboq_rate' => 'required'];

            $messages = ['pboq_item.required' => 'Please Enter Item',
                         'pboq_unit.required' => 'Please Enter Unit',
                         'pboq_qty.required' => 'Please Enter Quantity',
                         'pboq_rate.required' => 'Please Enter Rate'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else 
            {
                $upd['pboq_item']       = $request->pboq_item;
                $upd['pboq_unit']       = $request->pboq_unit;
                $upd['pboq_qty']        = $request->pboq_qty;
                $upd['pboq_rate']       = $request->pboq_rate;
                $upd['pboq_amount']     = $request->pboq_qty * $request->pboq_rate;
                $upd['pboq_remarks']    = $request->pboq_remarks;
                $upd['pboq_updated_on'] = date('Y-m-d H:i:s');
                $upd['pboq_updated_by'] = Auth::user()->user_id;

                ProjectBoq::where('pboq_id', $request->pboq_id)->update($upd);

                return redirect()->back()->with('success', 'BOQ Updated Successfully');
            }
        }
    }

    public function get_boq_item_details(Request $request)
    {
        $data['boq'] = ProjectBoq::where('pboq_id', $request->pboq_id)->first();

        $data['set'] = 'boq_tracker';
        return view('user.boq_tracker.edit_boq_item', $data);
    }
}
