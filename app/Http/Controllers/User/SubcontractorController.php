<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Subcontractor;

class SubcontractorController extends Controller
{
    public function index()
    {
        $data['set'] = 'subcontractors';
        return view('user.subcontractor.subcontractors',$data);
    }

    public function get_subcontractors(Request $request)
    {
        if($request->ajax())
        {
            $data = Subcontractor::orderby('subcontractor_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_subcontractor'))
                        {
                            $btn .= '<a href="'.route('user.edit_subcontractor',['id'=>$row->subcontractor_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.subcontractor_status'))
                        {
                            if($row->subcontractor_status == 1)
                            {
                                $btn .= '<a href="'.route('user.subcontractor_status',['id'=>$row->subcontractor_id,'status'=>$row->subcontractor_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                            }
                            else
                            {
                                $btn .= '<a href="'.route('user.subcontractor_status',['id'=>$row->subcontractor_id,'status'=>$row->subcontractor_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_subcontractor(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['subcontractor_name' => 'required',
                      'subcontractor_email' => 'required|email',
                      'subcontractor_phone' => 'required',
                      'subcontractor_address' => 'required'];

            $messages = ['subcontractor_name.required' => 'Please Enter Name',
                         'subcontractor_email.required' => 'Please Enter Email Address',
                         'subcontractor_email.email' => 'Please Enter Valid Email Address',
                         'subcontractor_phone.required' => 'Please Enter Phone',
                         'subcontractor_address.required' => 'Please Enter Address'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_user = Subcontractor::where('subcontractor_email',$request->subcontractor_email)->count();

                if($check_user > 0)
                {
                    return redirect()->back()->with('error','Subcontractor Email Already Added');
                }

                $ins['subcontractor_name']       = $request->subcontractor_name;
                $ins['subcontractor_email']      = $request->subcontractor_email;
                $ins['subcontractor_phone']      = $request->subcontractor_phone;
                $ins['subcontractor_address']    = $request->subcontractor_address;
                $ins['subcontractor_added_on']   = date('Y-m-d H:i:s');
                $ins['subcontractor_added_by']   = Auth::user()->user_id;
                $ins['subcontractor_updated_on'] = date('Y-m-d H:i:s');
                $ins['subcontractor_updated_by'] = Auth::user()->user_id;
                $ins['subcontractor_status']     = 1;

                $add = Subcontractor::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Subcontractor Added Successfully');
                }
            }
        }

        $data['set'] = 'subcontractors';
        return view('user.subcontractor.add_subcontractor',$data);
    }

    public function edit_subcontractor(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['subcontractor_name' => 'required',
                      'subcontractor_email' => 'required|email',
                      'subcontractor_phone' => 'required',
                      'subcontractor_address' => 'required'];

            $messages = ['subcontractor_name.required' => 'Please Enter Name',
                         'subcontractor_email.required' => 'Please Enter Email Address',
                         'subcontractor_email.email' => 'Please Enter Valid Email Address',
                         'subcontractor_phone.required' => 'Please Enter Phone',
                         'subcontractor_address.required' => 'Please Enter Address'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_subcontractor = Subcontractor::where('subcontractor_email',$request->subcontractor_email)->where('subcontractor_id','!=',$request->route('id'))->count();

                if($check_subcontractor > 0)
                {
                    return redirect()->back()->with('error','Subcontractor Email Already Added');
                }

                $upd['subcontractor_name']       = $request->subcontractor_name;
                $upd['subcontractor_email']      = $request->subcontractor_email;
                $upd['subcontractor_phone']      = $request->subcontractor_phone;
                $upd['subcontractor_address']    = $request->subcontractor_address;
                $upd['subcontractor_updated_on'] = date('Y-m-d H:i:s');
                $upd['subcontractor_updated_by'] = Auth::user()->user_id;

                Subcontractor::where('subcontractor_id',$request->route('id'))->update($upd);

                return redirect()->back()->with('success','Subcontractor Updated Successfully');
            }
        }

        $data['subcontractor'] = Subcontractor::where('subcontractor_id',$request->route('id'))->first();

        if(!isset($data['subcontractor']))
        {
            return redirect()->route('user.subcontractors');
        }

        $data['set'] = 'subcontractors';
        return view('user.subcontractor.edit_subcontractor',$data);
    }

    public function subcontractor_status(Request $request)
    {
        $id = $request->route('id');
        $status = $request->route('status');

        if($status == 1)
        {
            $upd['subcontractor_status'] = 0;
        }
        else
        {
            $upd['subcontractor_status'] = 1;
        }

        $where['subcontractor_id'] = $id;

        $update = Subcontractor::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}
