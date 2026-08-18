<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $data['set'] = 'category';
        return view('user.category.category', $data);
    }

    public function get_category(Request $request)
    {
        if($request->ajax()) 
        {
            $data = Category::orderby('category_id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    
                    $btn = '';

                    if(Helper::userHasPermission('user.edit_category')) 
                    {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-success" onclick="edit_category(' . $row->category_id . ')" title="Edit"><i class="ti ti-edit"></i></a> ';
                    }

                    if(Helper::userHasPermission('user.category_status')) 
                    {
                        if($row->category_status == 1) 
                        {
                            $btn .= '<a href="' . route('user.category_status', ['id' => $row->category_id, 'status' => $row->category_status]) . '" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                        } 
                        else 
                        {
                            $btn .= '<a href="' . route('user.category_status', ['id' => $row->category_id, 'status' => $row->category_status]) . '" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function add_category(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['category_name' => 'required'];

            $messages = ['category_name.required' => 'Please Enter category'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } 
            else 
            {
                $where_check['category_name'] = $request->category_name;
                $check_category = Category::where($where_check)->count();

                if($check_category > 0)
                {
                    return redirect()->back()->with('error', 'Category Already Added')->withInput();
                }

                $ins['category_name']       = $request->category_name;
                $ins['category_added_on']   = date('Y-m-d H:i:s');
                $ins['category_added_by']   = Auth::user()->user_id;
                $ins['category_updated_on'] = date('Y-m-d H:i:s');
                $ins['category_updated_by'] = Auth::user()->user_id;
                $ins['category_status']     = 1;

                $add = Category::create($ins);

                if($add) 
                {
                    return redirect()->back()->with('success', 'Category Added Successfully');
                }
            }
        }
    }

    public function edit_category(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['category_name' => 'required'];

            $messages = ['category_name.required' => 'Please Enter category'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else 
            {
                $where_check['category_name'] = $request->category_name;
                $check_category = Category::where($where_check)->where('category_id', '!=', $request->category_id)->count();

                if($check_category > 0) 
                {
                    return redirect()->back()->with('error', 'Category Already Added')->withInput();
                }

                $upd['category_name']       = $request->category_name;
                $upd['category_updated_on'] = date('Y-m-d H:i:s');
                $upd['category_updated_by'] = Auth::user()->user_id;

                Category::where('category_id', $request->category_id)->update($upd);

                return redirect()->back()->with('success', 'Category Updated Successfully');
            }
        }
    }

    public function get_category_details(Request $request)
    {
        $data['category'] = Category::where('category_id', $request->category_id)->first();

        $data['set'] = 'category';
        return view('user.category.edit_category', $data);
    }

    public function category_status(Request $request)
    {
        $id = $request->segment(3);
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['category_status'] = 0;
        }
        else
        {
            $upd['category_status'] = 1;
        }

        $where['category_id'] = $id;

        $update = Category::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success', 'Status Changed Successfully');
        }
    }
}
