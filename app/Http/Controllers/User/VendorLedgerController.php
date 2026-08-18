<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\VendorLedgerExport;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\VendorLedger;

class VendorLedgerController extends Controller
{
    public function index()
    {
        $ledger_summaries = VendorLedger::with('vendor')->get()->groupBy('vledger_vendor');

        $data['ledger_summaries'] = $ledger_summaries->map(function($ledgers) {

            $po_total = $ledgers->sum('vledger_po_total');
            $invoice = $ledgers->sum('vledger_invoice');
            $paid = $ledgers->sum('vledger_paid');
            $balance = $po_total - $paid;
            $paid_percent = $po_total > 0 ? round(($paid / $po_total) * 100) : 0;
            $paid_percent = max(0, min(100, $paid_percent));

            return [
                'vendor_name' => optional($ledgers->first()->vendor)->vendor_company,
                'entries' => $ledgers->count(),
                'po_total' => $po_total,
                'invoice' => $invoice,
                'paid' => $paid,
                'balance' => $balance,
                'paid_percent' => $paid_percent,
            ];
        })->sortByDesc('balance')->values();

        $data['set'] = 'vendor_ledger';
        return view('user.vendor_ledger.vendor_ledger',$data);
    }

    public function get_vendor_ledger(Request $request)
    {
        if($request->ajax())
        {
            $data = VendorLedger::orderby('vledger_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('ledger_date', function($row){

                        $ledger_date = date('d M Y',strtotime($row->vledger_date));

                        return $ledger_date;
                    })
                    ->addColumn('vendor_name', function($row){

                        $vendor_name = $row->vendor->vendor_company;

                        return $vendor_name;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('type_name', function($row){

                        if($row->vledger_type == 1)
                        {
                            $type_name = 'Purchase';
                        }
                        elseif($row->vledger_type == 2)
                        {
                            $type_name = 'Advance';
                        }
                        elseif($row->vledger_type == 3)
                        {
                            $type_name = 'Return';
                        }
                        elseif($row->vledger_type == 4)
                        {
                            $type_name = 'Debit Note';
                        }
                        elseif($row->vledger_type == 5)
                        {
                            $type_name = 'Credit Note';
                        }

                        return $type_name;
                    })
                    ->addColumn('balance', function($row){

                        $balance = $row->vledger_po_total - $row->vledger_paid;

                        return $balance;
                    })
                    ->make(true);
        }
    }

    public function vendor_ledger_download_excel(Request $request)
    {
        $filename = 'vendor_ledger_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new VendorLedgerExport,$filename);
    }

    public function add_vendor_ledger(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['vledger_vendor' => 'required',
                      'vledger_project' => 'required',
                      'vledger_date' => 'required',
                      'vledger_type' => 'required',
                      'vledger_pono' => 'required',
                      'vledger_description' => 'required',
                      'vledger_po_total' => 'required',
                      'vledger_invoice' => 'required',
                      'vledger_paid' => 'required'];

            $messages = ['vledger_vendor.required' => 'Please Select Vendor',
                         'vledger_project.required' => 'Please Select Project',
                         'vledger_date.required' => 'Please Select Date',
                         'vledger_type.required' => 'Please Select Type',
                         'vledger_pono.required' => 'Please Enter PO Number',
                         'vledger_description.required' => 'Please Enter Description',
                         'vledger_po_total.required' => 'Please Enter PO Amount',
                         'vledger_invoice.required' => 'Please Enter Invoice Amount',
                         'vledger_paid.required' => 'Please Enter Paid Amount'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['vledger_vendor']      = $request->vledger_vendor;
                $ins['vledger_project']     = $request->vledger_project;
                $ins['vledger_date']        = $request->vledger_date;
                $ins['vledger_type']        = $request->vledger_type;
                $ins['vledger_pono']        = $request->vledger_pono;
                $ins['vledger_description'] = $request->vledger_description;
                $ins['vledger_po_total']    = $request->vledger_po_total;
                $ins['vledger_invoice']     = $request->vledger_invoice;
                $ins['vledger_paid']        = $request->vledger_paid;
                $ins['vledger_remarks']     = $request->vledger_remarks;
                $ins['vledger_added_on']    = date('Y-m-d H:i:s');
                $ins['vledger_added_by']    = Auth::user()->user_id;
                $ins['vledger_updated_on']  = date('Y-m-d H:i:s');
                $ins['vledger_updated_by']  = Auth::user()->user_id;
                $ins['vledger_status']      = 1;

                $add = VendorLedger::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Ledger Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['vendors'] = Vendor::where('vendor_status',1)->orderby('vendor_name','asc')->get();

        $data['set'] = 'vendor_ledger';
        return view('user.vendor_ledger.add_vendor_ledger',$data);
    }
}
