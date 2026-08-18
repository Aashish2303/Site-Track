<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use DataTables;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $data['set'] = 'stocks';
        return view('user.stock.stocks',$data);
    }

    public function get_stocks(Request $request)
    {
        if($request->ajax())
        {
            $data = Stock::orderby('stock_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category_name', function($row){

                        $category_name = $row->material->category->mcategory_name;

                        return $category_name;
                    })
                    ->addColumn('material_name', function($row){

                        $material_name = $row->material->material_name;

                        return $material_name;
                    })
                    ->addColumn('unit_name', function($row){

                        $unit_name = $row->material->unit->unit_name;

                        return $unit_name;
                    })
                    ->make(true);
        }
    }
}
