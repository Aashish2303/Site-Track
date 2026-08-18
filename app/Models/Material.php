<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';

    protected $primaryKey = 'material_id';

    public $timestamps = false;

    protected $fillable = ['material_category','material_name','material_unit','material_hsn','material_added_on','material_added_by','material_updated_on','material_updated_by','material_status'];

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category', 'mcategory_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'material_unit', 'unit_id');
    }
}