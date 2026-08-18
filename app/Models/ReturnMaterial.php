<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnMaterial extends Model
{
    protected $table = 'return_materials';

    protected $primaryKey = 'rmaterial_id';

    public $timestamps = false;

    protected $fillable = ['rmaterial_date','rmaterial_project','rmaterial_category','rmaterial_material','rmaterial_unit','rmaterial_qty','rmaterial_added_on','rmaterial_added_by','rmaterial_updated_on','rmaterial_updated_by','rmaterial_status'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'rmaterial_project', 'project_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'rmaterial_material', 'material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'rmaterial_unit', 'unit_id');
    }
}