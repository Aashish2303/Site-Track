<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $data['set'] = 'users';
        return view('user.user.users',$data);
    }

    public function get_users(Request $request)
    {
        if($request->ajax())
        {
            $data = User::orderby('user_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('user_name', function($row){

                        if(!empty($row->user_image))
                        {
                            $user_image = asset(config('constants.admin_path').'uploads/profile/'.$row->user_image);
                        }
                        else
                        {
                            $user_image = asset(config('constants.admin_path').'img/no_image.png');
                        }
                    
                        $user_name = '<div class="d-flex align-items-center">
                           <a href="javascript:void(0);" class="avatar avatar-xs me-2">
                           <img src="'.$user_image.'" alt="" class="rounded">
                           </a>
                           <div>
                              <h6 class="fs-14 mb-0 fw-medium"><a href="javascript:void(0);">'.$row->user_name.'</a></h6>
                           </div>
                        </div>';

                        return $user_name;
                    })
                    ->addColumn('role_name', function($row){

                        $role_name = $row->role->role_name;

                        return $role_name;

                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_user'))
                        {
                            $btn .= '<a href="'.route('user.edit_user',['id'=>$row->user_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.user_status'))
                        {
                            if($row->user_status == 1)
                            {
                                $btn .= '<a href="'.route('user.user_status',['id'=>$row->user_id,'status'=>$row->user_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                            }
                            else
                            {
                                $btn .= '<a href="'.route('user.user_status',['id'=>$row->user_id,'status'=>$row->user_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','user_name'])
                    ->make(true);
        }
    }

    public function add_user(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_role' => 'required',
                      'user_name' => 'required',
                      'user_lname' => 'required',
                      'user_email' => 'required|email',
                      'user_phone' => 'required',
                      'user_password' => 'required'];

            $messages = ['user_role.required' => 'Please Select Role',
                         'user_name.required' => 'Please Enter Name',
                         'user_lname.required' => 'Please Enter Username',
                         'user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address',
                         'user_phone.required' => 'Please Enter Mobile No',
                         'user_password.required' => 'Please Enter Password'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_username = User::where('user_lname',$request->user_lname)->count();

                if($check_username > 0)
                {
                    return redirect()->back()->with('error','Username Already Added');
                }

                $check_user = User::where('user_email',$request->user_email)->count();

                if($check_user > 0)
                {
                    return redirect()->back()->with('error','Email Already Added');
                }

                $ins['user_role']       = $request->user_role;
                $ins['user_name']       = $request->user_name;
                $ins['user_lname']      = $request->user_lname;
                $ins['user_email']      = $request->user_email;
                $ins['user_phone']      = $request->user_phone;
                $ins['user_password']   = bcrypt($request->user_password);
                $ins['user_vpassword']  = base64_encode($request->user_password);
                $ins['user_added_on']   = date('Y-m-d H:i:s');
                $ins['user_added_by']   = Auth::user()->user_id;
                $ins['user_updated_on'] = date('Y-m-d H:i:s');
                $ins['user_updated_by'] = Auth::user()->user_id;
                $ins['user_status']     = 1;

                if($request->hasFile('user_image'))
                {
                    $user_image = $request->user_image->store('assets/admin/uploads/profile');

                    $user_image = explode('/',$user_image);
                    $user_image = end($user_image);
                    $ins['user_image'] = $user_image;
                }

                $add = User::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','User Added Successfully');
                }
            }
        }

        $data['roles'] = Role::where('role_status',1)->orderby('role_name','asc')->get();

        $data['set'] = 'users';
        return view('user.user.add_user',$data);
    }

    public function edit_user(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_role' => 'required',
                      'user_name' => 'required',
                      'user_lname' => 'required',
                      'user_email' => 'required|email',
                      'user_phone' => 'required',
                      'user_password' => 'required'];

            $messages = ['user_role.required' => 'Please Select Role',
                         'user_name.required' => 'Please Enter Name',
                         'user_lname.required' => 'Please Enter Username',
                         'user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address',
                         'user_phone.required' => 'Please Enter Mobile No',
                         'user_password.required' => 'Please Enter Password'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_username = User::where('user_lname',$request->user_lname)->where('user_id','!=',$request->segment(3))->count();

                if($check_username > 0)
                {
                    return redirect()->back()->with('error','Username Already Added');
                }

                $check_user = User::where('user_email',$request->user_email)->where('user_id','!=',$request->segment(3))->count();

                if($check_user > 0)
                {
                    return redirect()->back()->with('error','Email Already Added');
                }

                $upd['user_role']       = $request->user_role;
                $upd['user_name']       = $request->user_name;
                $upd['user_lname']      = $request->user_lname;
                $upd['user_email']      = $request->user_email;
                $upd['user_phone']      = $request->user_phone;
                $upd['user_password']   = bcrypt($request->user_password);
                $upd['user_vpassword']  = base64_encode($request->user_password);
                $upd['user_updated_on'] = date('Y-m-d H:i:s');
                $upd['user_updated_by'] = Auth::user()->user_id;

                if($request->hasFile('user_image'))
                {
                    $user_image = $request->user_image->store('assets/admin/uploads/profile');

                    $user_image = explode('/',$user_image);
                    $user_image = end($user_image);
                    $upd['user_image'] = $user_image;
                }

                User::where('user_id',$request->segment(3))->update($upd);

                return redirect()->back()->with('success','User Updated Successfully');
            }
        }

        $data['user'] = User::where('user_id',$request->segment(3))->first();

        if(!isset($data['user']))
        {
            return redirect()->route('user.users');
        }

        $data['roles'] = Role::where('role_status',1)->orderby('role_name','asc')->get();

        $data['set'] = 'users';
        return view('user.user.edit_user',$data);
    }

    public function user_status(Request $request)
    {
        $id = $request->segment(3);
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['user_status'] = 0;
        }
        else
        {
            $upd['user_status'] = 1;
        }

        $where['user_id'] = $id;

        $update = User::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}
