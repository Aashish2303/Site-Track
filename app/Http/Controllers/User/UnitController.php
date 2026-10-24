<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $data['set'] = 'units';
        return view('user.unit.units', $data);
    }

    public function get_units(Request $request)
    {
        if($request->ajax()) 
        {
            $data = Unit::orderby('unit_id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    
                    $btn = '';

                    if(Helper::userHasPermission('user.edit_unit')) 
                    {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-success" onclick="edit_unit(' . $row->unit_id . ')" title="Edit"><i class="ti ti-edit"></i></a> ';
                    }

                    if(Helper::userHasPermission('user.unit_status')) 
                    {
                        if($row->unit_status == 1) 
                        {
                            $btn .= '<a href="' . route('user.unit_status', ['id' => $row->unit_id, 'status' => $row->unit_status]) . '" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                        } 
                        else 
                        {
                            $btn .= '<a href="' . route('user.unit_status', ['id' => $row->unit_id, 'status' => $row->unit_status]) . '" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_unit(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['unit_name' => 'required'];

            $messages = ['unit_name.required' => 'Please Enter unit'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } 
            else 
            {
                $where_check['unit_name'] = $request->unit_name;
                $check_unit = Unit::where($where_check)->count();

                if($check_unit > 0)
                {
                    return redirect()->back()->with('error', 'Unit Already Added')->withInput();
                }

                $ins['unit_name']       = $request->unit_name;
                $ins['unit_added_on']   = date('Y-m-d H:i:s');
                $ins['unit_added_by']   = Auth::user()->user_id;
                $ins['unit_updated_on'] = date('Y-m-d H:i:s');
                $ins['unit_updated_by'] = Auth::user()->user_id;
                $ins['unit_status']     = 1;

                $add = Unit::create($ins);

                if($add) 
                {
                    return redirect()->back()->with('success', 'Unit Added Successfully');
                }
            }
        }
    }

    public function edit_unit(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['unit_name' => 'required'];

            $messages = ['unit_name.required' => 'Please Enter unit'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else 
            {
                $where_check['unit_name'] = $request->unit_name;
                $check_unit = Unit::where($where_check)->where('unit_id', '!=', $request->unit_id)->count();

                if($check_unit > 0) 
                {
                    return redirect()->back()->with('error', 'Unit Already Added')->withInput();
                }

                $upd['unit_name']       = $request->unit_name;
                $upd['unit_updated_on'] = date('Y-m-d H:i:s');
                $upd['unit_updated_by'] = Auth::user()->user_id;

                Unit::where('unit_id', $request->unit_id)->update($upd);

                return redirect()->back()->with('success', 'Unit Updated Successfully');
            }
        }
    }

    public function get_unit_details(Request $request)
    {
        $data['unit'] = Unit::where('unit_id', $request->unit_id)->first();

        $data['set'] = 'units';
        return view('user.unit.edit_unit', $data);
    }

    public function unit_status(Request $request)
    {
        $id = $request->route('id');
        $status = $request->route('status');

        if($status == 1)
        {
            $upd['unit_status'] = 0;
        }
        else
        {
            $upd['unit_status'] = 1;
        }

        $where['unit_id'] = $id;

        $update = Unit::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success', 'Status Changed Successfully');
        }
    }
}
