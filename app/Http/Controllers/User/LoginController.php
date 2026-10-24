<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Mail;
use Helper;
use Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_lname' => 'required',
                      'user_password' => 'required'];

            $messages = ['user_lname.required' => 'Please Enter Username',
                         'user_password.required' => 'Please Enter Password'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where['user_lname'] = $request->user_lname;
                $where['password'] = $request->user_password;

                if(Auth::attempt($where))
                {
                    if(Auth::user()->user_status == 1)
                    {
                        $request->session()->regenerate();
                        $request->session()->forget('workspace_project_id');
                        return redirect()->intended('user/projects');
                    }
                    else
                    {
                        Auth::logout();

                        return redirect()->route('user.login')->with('error','Invalid Username or Password!')->withInput();
                    }
                }
                else
                {
                    return redirect()->route('user.login')->with('error','Invalid Username or Password!')->withInput();
                }
            }
        }

        $data['set'] = 'login';
        return view('user.login.login',$data);
    }

    public function forgot_password(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_email' => 'required|email'];

            $messages = ['user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_user['user_email']  = $request->user_email;
                $where_user['user_status'] = 1;
                $user = User::where($where_user)->first();

                if(isset($user))
                {
                    Helper::mailConfig();
                    
                    $mail  = $request->user_email;

                    $uname = $data['name'] = $user->user_name;
                    $data['password'] = base64_decode($user->user_vpassword);

                    $send = Mail::send('user.mail.forgot_password', $data, function($message) use ($mail, $uname){
                         $message->to($mail, $uname)->subject(config('constants.site_title').' - Forgot Password');
                         $message->from(config('constants.mail_from'),config('constants.site_title'));
                      });

                    return redirect()->route('user.login')->with('success','Please Check Your Email');
                }
                else
                {
                    return redirect()->route('user.forgot_password')->with('error','Invalid Email Address')->withInput();
                }
            }
        }

        return view('user.login.forgot_password');
    }
}
