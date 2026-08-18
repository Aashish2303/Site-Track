<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\IssueCategory;

class IssueCategoryController extends Controller
{
    public function index()
    {
        $data['set'] = 'issue_category';
        return view('user.issue_category.issue_category', $data);
    }

    public function get_issue_category(Request $request)
    {
        if($request->ajax()) 
        {
            $data = IssueCategory::orderby('icategory_id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    
                    $btn = '';

                    if(Helper::userHasPermission('user.edit_issue_category')) 
                    {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-success" onclick="edit_issue_category(' . $row->icategory_id . ')" title="Edit"><i class="ti ti-edit"></i></a> ';
                    }

                    if(Helper::userHasPermission('user.issue_category_status')) 
                    {
                        if($row->icategory_status == 1) 
                        {
                            $btn .= '<a href="' . route('user.issue_category_status', ['id' => $row->icategory_id, 'status' => $row->icategory_status]) . '" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                        } 
                        else 
                        {
                            $btn .= '<a href="' . route('user.issue_category_status', ['id' => $row->icategory_id, 'status' => $row->icategory_status]) . '" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function add_issue_category(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['icategory_name' => 'required'];

            $messages = ['icategory_name.required' => 'Please Enter Category'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } 
            else 
            {
                $where_check['icategory_name'] = $request->icategory_name;
                $check_category = IssueCategory::where($where_check)->count();

                if($check_category > 0)
                {
                    return redirect()->back()->with('error', 'Category Already Added')->withInput();
                }

                $ins['icategory_name']       = $request->icategory_name;
                $ins['icategory_added_on']   = date('Y-m-d H:i:s');
                $ins['icategory_added_by']   = Auth::user()->user_id;
                $ins['icategory_updated_on'] = date('Y-m-d H:i:s');
                $ins['icategory_updated_by'] = Auth::user()->user_id;
                $ins['icategory_status']     = 1;

                $add = IssueCategory::create($ins);

                if($add) 
                {
                    return redirect()->back()->with('success', 'Category Added Successfully');
                }
            }
        }
    }

    public function edit_issue_category(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['icategory_name' => 'required'];

            $messages = ['icategory_name.required' => 'Please Enter Category'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else 
            {
                $where_check['icategory_name'] = $request->icategory_name;
                $check_category = IssueCategory::where($where_check)->where('icategory_id', '!=', $request->icategory_id)->count();

                if($check_category > 0) 
                {
                    return redirect()->back()->with('error', 'Category Already Added')->withInput();
                }

                $upd['icategory_name']       = $request->icategory_name;
                $upd['icategory_updated_on'] = date('Y-m-d H:i:s');
                $upd['icategory_updated_by'] = Auth::user()->user_id;

                IssueCategory::where('icategory_id', $request->icategory_id)->update($upd);

                return redirect()->back()->with('success', 'Category Updated Successfully');
            }
        }
    }

    public function get_issue_category_details(Request $request)
    {
        $data['category'] = IssueCategory::where('icategory_id', $request->icategory_id)->first();

        $data['set'] = 'issue_category';
        return view('user.issue_category.edit_issue_category', $data);
    }

    public function issue_category_status(Request $request)
    {
        $id = $request->segment(3);
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['icategory_status'] = 0;
        }
        else
        {
            $upd['icategory_status'] = 1;
        }

        $where['icategory_id'] = $id;

        $update = IssueCategory::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success', 'Status Changed Successfully');
        }
    }
}
