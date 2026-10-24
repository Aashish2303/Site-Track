<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Unit;
use App\Models\Material;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Stock;

class OrderController extends Controller
{
    public function index()
    {
        $data['set'] = 'orders';
        return view('user.order.orders',$data);
    }

    public function get_orders(Request $request)
    {
        if($request->ajax())
        {
            $data = Order::orderby('order_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('order_date', function($row){

                        $order_date = date('d M Y',strtotime($row->order_date));

                        return $order_date;
                    })
                    ->addColumn('vendor_name', function($row){

                        $vendor_name = $row->vendor->vendor_company;

                        return $vendor_name;
                    })
                    ->addColumn('material_name', function($row){

                        $material_name = $row->material->material_name;

                        return $material_name;
                    })
                    ->addColumn('unit_name', function($row){

                        $unit_name = $row->unit->unit_name;

                        return $unit_name;
                    })
                    ->addColumn('delivered_qty', function($row){

                        $delivered_qty = OrderDelivery::where('odelivery_order',$row->order_id)->sum('odelivery_qty');

                        return $delivered_qty;
                    })
                    ->addColumn('pending_qty', function($row){

                        $delivered_qty = OrderDelivery::where('odelivery_order',$row->order_id)->sum('odelivery_qty');

                        $pending_qty = $row->order_qty - $delivered_qty;

                        return $pending_qty;
                    })
                    ->addColumn('pay_status', function($row){

                        if($row->order_pay_status == 1)
                        {
                            $status = '<span class="badge bg-danger">Pending</span>';
                        }
                        elseif($row->order_pay_status == 2)
                        {
                            $status = '<span class="badge bg-secondary">Partial</span>';
                        }
                        elseif($row->order_pay_status == 3)
                        {
                            $status = '<span class="badge bg-success">Paid</span>';
                        }

                        return $status;
                    })
                    ->addColumn('delivery_status', function($row){

                        $total_delivery = Helper::orderStatus($row->order_id);

                        if($total_delivery == 0)
                        {
                            $delivery_status = '<span class="badge bg-danger">Pending</span>';
                        }
                        elseif($total_delivery < $row->order_qty)
                        {
                            $delivery_status = '<span class="badge bg-secondary">Partial</span>';
                        }
                        elseif($total_delivery >= $row->order_qty)
                        {
                            $delivery_status = '<span class="badge bg-success">Delivered</span>';
                        }

                        return $delivery_status;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.order_delivery'))
                        {
                            $btn .= '<a href="'.route('user.order_delivery',['id'=>$row->order_id]).'" class="btn btn-icon btn-sm btn-secondary" title="Delivery"><i class="ti ti-truck"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.edit_order'))
                        {
                            $btn .= '<a href="'.route('user.edit_order',['id'=>$row->order_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.order_status'))
                        {
                            $btn .= '<a href="#" class="btn btn-icon btn-sm btn-dark" data-st-click="order_pay_status('.$row->order_id.')" title="Status"><i class="ti ti-check"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','pay_status','delivery_status'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_order(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['order_date' => 'required',
                      'order_po_no' => 'required',
                      'order_vendor' => 'required',
                      'order_material' => 'required',
                      'order_unit' => 'required',
                      'order_qty' => 'required',
                      'order_rate' => 'required'];

            $messages = ['order_date.required' => 'Please Select Date',
                         'order_po_no.required' => 'Please Enter PO Number',
                         'order_vendor.required' => 'Please Select Vendor',
                         'order_material.required' => 'Please Select Material',
                         'order_unit.required' => 'Please Select Unit',
                         'order_qty.required' => 'Please Enter Quantity',
                         'order_rate.required' => 'Please Enter Rate'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['order_date']       = $request->order_date;
                $ins['order_po_no']      = $request->order_po_no;
                $ins['order_vendor']     = $request->order_vendor;
                $ins['order_material']   = $request->order_material;
                $ins['order_unit']       = $request->order_unit;
                $ins['order_qty']        = $request->order_qty;
                $ins['order_rate']       = $request->order_rate;
                $ins['order_pay_status'] = 1;
                $ins['order_added_on']   = date('Y-m-d H:i:s');
                $ins['order_added_by']   = Auth::user()->user_id;
                $ins['order_updated_on'] = date('Y-m-d H:i:s');
                $ins['order_updated_by'] = Auth::user()->user_id;
                $ins['order_status']     = 1;

                $add = Order::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Order Added Successfully');
                }
            }
        }

        $data['vendors'] = Vendor::where('vendor_status',1)->orderby('vendor_name','asc')->get();

        $data['materials'] = Material::where('material_status',1)->orderby('material_name','asc')->get();

        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'orders';
        return view('user.order.add_order',$data);
    }

    public function select_material_unit(Request $request)
    {
        $material = Material::where('material_id',$request->material_id)->first();

        if(isset($material))
        {
            return response()->json(['unit_id' => $material->unit->unit_id,'unit_name' => $material->unit->unit_name]);
        }
    }

    public function edit_order(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['order_date' => 'required',
                      'order_po_no' => 'required',
                      'order_vendor' => 'required',
                      'order_material' => 'required',
                      'order_unit' => 'required',
                      'order_qty' => 'required',
                      'order_rate' => 'required'];

            $messages = ['order_date.required' => 'Please Select Date',
                         'order_po_no.required' => 'Please Enter PO Number',
                         'order_vendor.required' => 'Please Select Vendor',
                         'order_material.required' => 'Please Select Material',
                         'order_unit.required' => 'Please Select Unit',
                         'order_qty.required' => 'Please Enter Quantity',
                         'order_rate.required' => 'Please Enter Rate'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['order_date']       = $request->order_date;
                $upd['order_po_no']      = $request->order_po_no;
                $upd['order_vendor']     = $request->order_vendor;
                $upd['order_material']   = $request->order_material;
                $upd['order_unit']       = $request->order_unit;
                $upd['order_qty']        = $request->order_qty;
                $upd['order_rate']       = $request->order_rate;
                $upd['order_updated_on'] = date('Y-m-d H:i:s');
                $upd['order_updated_by'] = Auth::user()->user_id;

                Order::where('order_id',$request->route('id'))->update($upd);

                return redirect()->back()->with('success','Order Updated Successfully');
            }
        }

        $data['order'] = Order::where('order_id',$request->route('id'))->first();

        if(!isset($data['order']))
        {
            return redirect()->route('user.orders');
        }

        $data['vendors'] = Vendor::where('vendor_status',1)->orderby('vendor_name','asc')->get();

        $data['materials'] = Material::where('material_status',1)->orderby('material_name','asc')->get();

        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'orders';
        return view('user.order.edit_order',$data);
    }

    public function order_delivery(Request $request)
    {
        $data['order'] = Order::where('order_id',$request->route('id'))->first();

        if(!isset($data['order']))
        {
            return redirect()->route('user.orders');
        }

        $data['deliveries'] = OrderDelivery::where('odelivery_order',$data['order']->order_id)->get();

        $data['total_delivery'] = Helper::orderStatus($data['order']->order_id);

        $data['set'] = 'orders';
        return view('user.order.order_delivery',$data);
    }

    public function add_order_delivery(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['odelivery_date' => 'required',
                      'odelivery_qty' => 'required',
                      'odelivery_dc' => 'required'];

            $messages = ['odelivery_date.required' => 'Please Select Date',
                         'odelivery_qty.required' => 'Please Enter Quantity',
                         'odelivery_dc.required' => 'Please Enter Delivery Challan'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['odelivery_order']      = $request->route('id');
                $ins['odelivery_date']       = $request->odelivery_date;
                $ins['odelivery_qty']        = $request->odelivery_qty;
                $ins['odelivery_dc']         = $request->odelivery_dc;
                $ins['odelivery_added_on']   = date('Y-m-d H:i:s');
                $ins['odelivery_added_by']   = Auth::user()->user_id;
                $ins['odelivery_updated_on'] = date('Y-m-d H:i:s');
                $ins['odelivery_updated_by'] = Auth::user()->user_id;
                $ins['odelivery_status']     = 1;

                $add = OrderDelivery::create($ins);

                if($add)
                {
                    $existing_stock = Stock::where('stock_material', $request->material_id)->first();

                    if($existing_stock)
                    {
                        $upd_stock['stock_qty']        = $existing_stock->stock_qty + $request->odelivery_qty;
                        $upd_stock['stock_updated_on'] = date('Y-m-d H:i:s');
                        $upd_stock['stock_updated_by'] = Auth::user()->user_id;

                        Stock::where('stock_material', $request->material_id)->update($upd_stock);
                    }
                    else
                    {
                        $ins_stock['stock_material']   = $request->material_id;
                        $ins_stock['stock_qty']        = $request->odelivery_qty;
                        $ins_stock['stock_added_on']   = date('Y-m-d H:i:s');
                        $ins_stock['stock_added_by']   = Auth::user()->user_id;
                        $ins_stock['stock_updated_on'] = date('Y-m-d H:i:s');
                        $ins_stock['stock_updated_by'] = Auth::user()->user_id;
                        $ins_stock['stock_status']     = 1;

                        Stock::create($ins_stock);
                    }

                    return redirect()->back()->with('success','Delivery Added Successfully');
                }
            }
        }
    }

    public function order_pay_status(Request $request)
    {
        $data['order'] = Order::where('order_id',$request->order_id)->first();

        $data['set'] = 'orders';
        return view('user.order.order_pay_status',$data);
    }

    public function add_order_pay_status(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['status' => 'required'];

            $messages = ['status.required' => 'Please Select Status'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['order_pay_status'] = $request->status;
                $upd['order_updated_on'] = date('Y-m-d H:i:s');
                $upd['order_updated_by'] = Auth::user()->user_id;

                Order::where('order_id',$request->order_id)->update($upd);

                return redirect()->back()->with('success','Status Updated Successfully');
            }
        }
    }
}
