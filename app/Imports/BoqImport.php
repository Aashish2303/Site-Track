<?php
  
namespace App\Imports;

use Auth;
use App\Models\ProjectBoq;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
  
class BoqImport implements ToModel, WithStartRow, WithValidation
{
    private int $project_id;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    function __construct($project_id) {
        
        $this->project_id = $project_id;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '1' => 'required|string|max:255', '2' => 'required|string|max:255',
            '3' => 'required|numeric|gt:0|max:999999999',
            '4' => 'required|numeric|min:0|max:999999999', '5' => 'nullable|string|max:5000',
        ];
    }

    public function model(array $row)
    {
        if(!empty($row[1]) && !empty($row[2]) && !empty($row[3]) && !empty($row[4]))
        {
            $ins['pboq_project']    = $this->project_id;
            $ins['pboq_item']       = $row[1];
            $ins['pboq_unit']       = $row[2];
            $ins['pboq_qty']        = $row[3];
            $ins['pboq_rate']       = $row[4];
            $ins['pboq_amount']     = $row[3] * $row[4];
            $ins['pboq_remarks']    = $row[5];
            $ins['pboq_extra']      = 2;
            $ins['pboq_added_on']   = date('Y-m-d H:i:s');
            $ins['pboq_added_by']   = Auth::user()->user_id;
            $ins['pboq_updated_on'] = date('Y-m-d H:i:s');
            $ins['pboq_updated_by'] = Auth::user()->user_id;
            $ins['pboq_status']     = 1;

            ProjectBoq::create($ins);
        } 
    }
}