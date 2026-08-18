<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $data['set'] = 'clients';
        return view('user.client.clients',$data);
    }

    public function get_clients(Request $request)
    {
        if($request->ajax())
        {
            $data = Client::orderby('client_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_client'))
                        {
                            $btn .= '<a href="'.route('user.edit_client',['id'=>$row->client_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.client_status'))
                        {
                            if($row->client_status == 1)
                            {
                                $btn .= '<a href="'.route('user.client_status',['id'=>$row->client_id,'status'=>$row->client_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                            }
                            else
                            {
                                $btn .= '<a href="'.route('user.client_status',['id'=>$row->client_id,'status'=>$row->client_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function add_client(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['client_name' => 'required',
                      'client_email' => 'required|email',
                      'client_phone' => 'required',
                      'client_address' => 'required'];

            $messages = ['client_name.required' => 'Please Enter Name',
                         'client_email.required' => 'Please Enter Email Address',
                         'client_email.email' => 'Please Enter Valid Email Address',
                         'client_phone.required' => 'Please Enter Phone',
                         'client_address.required' => 'Please Enter Address'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_user = Client::where('client_email',$request->client_email)->count();

                if($check_user > 0)
                {
                    return redirect()->back()->with('error','Client Email Already Added');
                }

                $ins['client_name']       = $request->client_name;
                $ins['client_email']      = $request->client_email;
                $ins['client_phone']      = $request->client_phone;
                $ins['client_address']    = $request->client_address;
                $ins['client_added_on']   = date('Y-m-d H:i:s');
                $ins['client_added_by']   = Auth::user()->user_id;
                $ins['client_updated_on'] = date('Y-m-d H:i:s');
                $ins['client_updated_by'] = Auth::user()->user_id;
                $ins['client_status']     = 1;

                $add = Client::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Client Added Successfully');
                }
            }
        }

        $data['set'] = 'clients';
        return view('user.client.add_client',$data);
    }

    public function edit_client(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['client_name' => 'required',
                      'client_email' => 'required|email',
                      'client_phone' => 'required',
                      'client_address' => 'required'];

            $messages = ['client_name.required' => 'Please Enter Name',
                         'client_email.required' => 'Please Enter Email Address',
                         'client_email.email' => 'Please Enter Valid Email Address',
                         'client_phone.required' => 'Please Enter Phone',
                         'client_address.required' => 'Please Enter Address'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_client = Client::where('client_email',$request->client_email)->where('client_id','!=',$request->segment(3))->count();

                if($check_client > 0)
                {
                    return redirect()->back()->with('error','Client Email Already Added');
                }

                $upd['client_name']       = $request->client_name;
                $upd['client_email']      = $request->client_email;
                $upd['client_phone']      = $request->client_phone;
                $upd['client_address']    = $request->client_address;
                $upd['client_updated_on'] = date('Y-m-d H:i:s');
                $upd['client_updated_by'] = Auth::user()->user_id;

                Client::where('client_id',$request->segment(3))->update($upd);

                return redirect()->back()->with('success','Client Updated Successfully');
            }
        }

        $data['client'] = Client::where('client_id',$request->segment(3))->first();

        if(!isset($data['client']))
        {
            return redirect()->route('user.clients');
        }

        $data['set'] = 'clients';
        return view('user.client.edit_client',$data);
    }

    public function client_status(Request $request)
    {
        $id = $request->segment(3);
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['client_status'] = 0;
        }
        else
        {
            $upd['client_status'] = 1;
        }

        $where['client_id'] = $id;

        $update = Client::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}
