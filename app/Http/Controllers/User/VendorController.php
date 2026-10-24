<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\VendorExport;
use App\Models\MaterialCategory;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();

        $data['set'] = 'vendors';
        return view('user.vendor.vendors',$data);
    }

    public function get_vendors(Request $request)
    {
        if($request->ajax())
        {
            if(!empty($request->category))
            {
                $where['vendor_category'] = $request->category;    
            }

            $where['vendor_trash'] = 1;
            $data = Vendor::where($where)->orderby('vendor_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category_name', function($row){

                        $category_name = $row->category->mcategory_name;

                        return $category_name;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.call_vendor'))
                        {
                            $btn .= '<a href="tel:'.$row->vendor_mobile.'" class="btn btn-icon btn-sm btn-info" title="Call"><i class="ti ti-phone"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.edit_vendor'))
                        {
                            $btn .= '<a href="'.route('user.edit_vendor',['id'=>$row->vendor_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.vendor_status'))
                        {
                            if($row->vendor_status == 1)
                            {
                                $btn .= '<a href="'.route('user.vendor_status',['id'=>$row->vendor_id,'status'=>$row->vendor_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" data-st-click="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                            }
                            else
                            {
                                $btn .= '<a href="'.route('user.vendor_status',['id'=>$row->vendor_id,'status'=>$row->vendor_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" data-st-click="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function vendors_download_excel(Request $request)
    {
        $filename = 'vendors_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new VendorExport($request->category),$filename);
    }

    public function add_vendor(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['vendor_category' => 'required',
                      'vendor_name' => 'required',
                      'vendor_company' => 'required',
                      'vendor_mobile' => 'required',
                      'vendor_location' => 'required',
                      'vendor_type' => 'required'];

            $messages = ['vendor_category.required' => 'Please Select Category',
                         'vendor_name.required' => 'Please Enter Vendor',
                         'vendor_company.required' => 'Please Enter Company',
                         'vendor_mobile.required' => 'Please Enter Mobile Number',
                         'vendor_location.required' => 'Please Enter Location',
                         'vendor_type.required' => 'Please Enter Type'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_user = Vendor::where('vendor_name',$request->vendor_name)->count();

                if($check_user > 0)
                {
                    return redirect()->back()->with('error','Vendor Already Added');
                }

                $ins['vendor_category']   = $request->vendor_category;
                $ins['vendor_name']       = $request->vendor_name;
                $ins['vendor_company']    = $request->vendor_company;
                $ins['vendor_mobile']     = $request->vendor_mobile;
                $ins['vendor_altno']      = $request->vendor_altno;
                $ins['vendor_location']   = $request->vendor_location;
                $ins['vendor_type']       = $request->vendor_type;
                $ins['vendor_remarks']    = $request->vendor_remarks;
                $ins['vendor_added_on']   = date('Y-m-d H:i:s');
                $ins['vendor_added_by']   = Auth::user()->user_id;
                $ins['vendor_updated_on'] = date('Y-m-d H:i:s');
                $ins['vendor_updated_by'] = Auth::user()->user_id;
                $ins['vendor_status']     = 1;
                $ins['vendor_trash']      = 1;

                if($request->hasFile('vendor_card'))
                {
                    $vendor_card = app(\App\Services\PrivateUploads::class)->store($request->vendor_card, 'vendor');

                    $vendor_card = explode('/',$vendor_card);
                    $vendor_card = end($vendor_card);
                    $ins['vendor_card'] = $vendor_card;
                }

                $add = Vendor::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Vendor Added Successfully');
                }
            }
        }

        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();

        $data['set'] = 'vendors';
        return view('user.vendor.add_vendor',$data);
    }

    public function edit_vendor(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['vendor_category' => 'required',
                      'vendor_name' => 'required',
                      'vendor_company' => 'required',
                      'vendor_mobile' => 'required',
                      'vendor_location' => 'required',
                      'vendor_type' => 'required'];

            $messages = ['vendor_category.required' => 'Please Select Category',
                         'vendor_name.required' => 'Please Enter Vendor',
                         'vendor_company.required' => 'Please Enter Company',
                         'vendor_mobile.required' => 'Please Enter Mobile Number',
                         'vendor_location.required' => 'Please Enter Location',
                         'vendor_type.required' => 'Please Enter Type'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_vendor = Vendor::where('vendor_name',$request->vendor_name)->where('vendor_id','!=',$request->route('id'))->count();

                if($check_vendor > 0)
                {
                    return redirect()->back()->with('error','Vendor Email Already Added');
                }

                $upd['vendor_category']   = $request->vendor_category;
                $upd['vendor_name']       = $request->vendor_name;
                $upd['vendor_company']    = $request->vendor_company;
                $upd['vendor_mobile']     = $request->vendor_mobile;
                $upd['vendor_altno']      = $request->vendor_altno;
                $upd['vendor_location']   = $request->vendor_location;
                $upd['vendor_type']       = $request->vendor_type;
                $upd['vendor_remarks']    = $request->vendor_remarks;
                $upd['vendor_updated_on'] = date('Y-m-d H:i:s');
                $upd['vendor_updated_by'] = Auth::user()->user_id;

                if($request->hasFile('vendor_card'))
                {
                    $vendor_card = app(\App\Services\PrivateUploads::class)->store($request->vendor_card, 'vendor');

                    $vendor_card = explode('/',$vendor_card);
                    $vendor_card = end($vendor_card);
                    $upd['vendor_card'] = $vendor_card;
                }

                Vendor::where('vendor_id',$request->route('id'))->update($upd);

                return redirect()->back()->with('success','Vendor Updated Successfully');
            }
        }

        $data['vendor'] = Vendor::where('vendor_id',$request->route('id'))->first();

        if(!isset($data['vendor']))
        {
            return redirect()->route('user.vendors');
        }

        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();

        $data['set'] = 'vendors';
        return view('user.vendor.edit_vendor',$data);
    }

    public function vendor_status(Request $request)
    {
        $id = $request->route('id');
        $status = $request->route('status');

        if($status == 1)
        {
            $upd['vendor_status'] = 0;
        }
        else
        {
            $upd['vendor_status'] = 1;
        }

        $where['vendor_id'] = $id;

        $update = Vendor::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}
