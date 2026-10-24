<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['settings_title' => 'required',
                      'settings_company' => 'required',  
                      'settings_email' => 'required|email',
                      'settings_phone' => 'required',
                      'settings_address' => 'required'];

            $messages = ['settings_title.required' => 'Please Enter Website Title',
                         'settings_company.required' => 'Please Enter Company',
                         'settings_email.required' => 'Please Enter Email Address',
                         'settings_email.email' => 'Please Enter Valid Email Address',
                         'settings_phone.required' => 'Please Enter Phone',
                         'settings_address.required' => 'Please Enter Address'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except(['settings_pwd']));
            }
            else
            {
                $upd['settings_title']      = $request->settings_title;
                $upd['settings_company']    = $request->settings_company;
                $upd['settings_email']      = $request->settings_email;
                $upd['settings_phone']      = $request->settings_phone;
                $upd['settings_address']    = $request->settings_address;
                $upd['settings_updated_on'] = date('Y-m-d H:i:s');
                $upd['settings_updated_by'] = Auth::user()->user_id;

                if($request->hasFile('settings_logo'))
                {
                    $settings_logo = $request->settings_logo->store('assets/admin/uploads/logo');

                    $settings_logo = explode('/',$settings_logo);
                    $settings_logo = end($settings_logo);
                    $upd['settings_logo'] = $settings_logo;
                }

                if($request->hasFile('settings_wlogo'))
                {
                    $settings_wlogo = $request->settings_wlogo->store('assets/admin/uploads/logo');

                    $settings_wlogo = explode('/',$settings_wlogo);
                    $settings_wlogo = end($settings_wlogo);
                    $upd['settings_wlogo'] = $settings_wlogo;
                }

                if($request->hasFile('settings_favicon'))
                {
                    $settings_favicon = $request->settings_favicon->store('assets/admin/uploads/logo');

                    $settings_favicon = explode('/',$settings_favicon);
                    $settings_favicon = end($settings_favicon);
                    $upd['settings_favicon'] = $settings_favicon;
                }

                Settings::where('settings_status',1)->update($upd);

                return redirect()->back()->with('success','Details Updated Successfully');
            }
        }

        $data['set'] = 'settings';
        return view('user.settings.settings',$data);
    }

    public function email_config_settings(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['settings_mailer' => 'required',
                      'settings_host' => 'required',
                      'settings_port' => 'required',
                      'settings_uname' => 'required',
                      'settings_pwd' => 'required',
                      'settings_encryption' => 'required',
                      'settings_faddress' => 'required',
                      'settings_fname' => 'required'];

            $messages = ['settings_mailer.required' => 'Please Enter Mailer',
                         'settings_host.required' => 'Please Enter Host',
                         'settings_port.required' => 'Please Enter Port',
                         'settings_uname.required' => 'Please Enter Username',
                         'settings_pwd.required' => 'Please Enter Password',
                         'settings_encryption.required' => 'Please Enter Encryption',
                         'settings_faddress.required' => 'Please Enter From Address',
                         'settings_fname.required' => 'Please Enter From Name'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except(['settings_pwd']));
            }
            else
            {
                $upd['settings_mailer']     = $request->settings_mailer;
                $upd['settings_host']       = $request->settings_host;
                $upd['settings_port']       = $request->settings_port;
                $upd['settings_uname']      = $request->settings_uname;
                $upd['settings_pwd']        = $request->settings_pwd;
                $upd['settings_encryption'] = $request->settings_encryption;
                $upd['settings_faddress']   = $request->settings_faddress;
                $upd['settings_fname']      = $request->settings_fname;
                $upd['settings_updated_on'] = date('Y-m-d H:i:s');
                $upd['settings_updated_by'] = Auth::user()->user_id;

                Settings::where('settings_status',1)->update($upd);

                return redirect()->back()->with('success','Details Updated Successfully');
            }
        }

        $data['set'] = 'settings';
        return view('user.settings.email_config_settings',$data);
    }
}
