<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_name' => 'required',
                      'user_lname' => 'required',
                      'user_email' => 'required|email',
                      'user_phone' => 'required'];

            $messages = ['user_name.required' => 'Please Enter Name',
                         'user_lname.required' => 'Please Enter Username',
                         'user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address',
                         'user_phone.required' => 'Please Enter Mobile No'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except(['current_password', 'new_password', 'confirm_password']));
            }
            else
            {
                $where_name['user_lname'] = $request->user_lname;
                $check_name = User::where($where_name)->where('user_id','!=',Auth::user()->user_id)->count();

                if($check_name > 0)
                {
                    return redirect()->back()->with('error','Username Already Exists')->withInput($request->except(['current_password', 'new_password', 'confirm_password']));
                }

                $where_email['user_email'] = $request->user_email;
                $check_email = User::where($where_email)->where('user_id','!=',Auth::user()->user_id)->count();

                if($check_email > 0)
                {
                    return redirect()->back()->with('error','Email Address Already Exists')->withInput($request->except(['current_password', 'new_password', 'confirm_password']));
                }

                $upd['user_name']       = $request->user_name;
                $upd['user_lname']      = $request->user_lname;
                $upd['user_email']      = $request->user_email;
                $upd['user_phone']      = $request->user_phone;
                $upd['user_updated_on'] = date('Y-m-d H:i:s');
                $upd['user_updated_by'] = Auth::user()->user_id;

                if($request->hasFile('user_image'))
                {
                    $user_image = $request->user_image->store('assets/admin/uploads/profile');

                    $user_image = explode('/',$user_image);
                    $user_image = end($user_image);
                    $upd['user_image'] = $user_image;
                }

                User::where('user_id',Auth::user()->user_id)->update($upd);

                return redirect()->back()->with('success','Details Updated Successfully');
            }
        }

        $data['set'] = 'profile';
        return view('user.profile.profile',$data);
    }

    public function change_password(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['current_password' => 'required',
                      'new_password' => 'required',
                      'confirm_password' => 'required|same:new_password'];

            $messages = ['current_password.required' => 'Please Enter Current Password',
                         'new_password.required' => 'Please Enter New Password',
                         'confirm_password.required' => 'Please Enter Confirm Password'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except(['current_password', 'new_password', 'confirm_password']));
            }
            else
            {
                $check = \Illuminate\Support\Facades\Hash::check($request->current_password, Auth::user()->getAuthPassword());

                if($check > 0)
                {
                    $upd['user_password']   = bcrypt($request->confirm_password);
                    $upd['user_vpassword']  = app(\App\Services\PasswordVault::class)->encrypt($request->confirm_password);
                    $upd['user_updated_on'] = date('Y-m-d H:i:s');
                    $upd['user_updated_by'] = Auth::user()->user_id;

                    $update = User::where('user_id',Auth::user()->user_id)->update($upd);
                    Auth::user()->forceFill($upd);
                    $request->session()->put('password_hash_web', $upd['user_password']);
                    $request->session()->regenerate();

                    return redirect()->route('user.change_password')->with('success','Password Changed Successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Current Password Does Not Match');
                }
            }
        }

        $data['set'] = 'profile';
        return view('user.profile.change_password',$data);
    }

    public function check_current_password(Request $request)
    {
        $request->validate(['current_password' => 'required|string|max:1024']);
        return response()->json(\Illuminate\Support\Facades\Hash::check($request->current_password, Auth::user()->getAuthPassword()));
    }
}
