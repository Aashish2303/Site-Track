<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignMaterial extends Model
{
    use \App\Models\Concerns\HasProjectContext;

    protected $table = 'assign_materials';

    protected $primaryKey = 'amaterial_id';

    public $timestamps = false;

    protected $fillable = ['amaterial_date','amaterial_project','amaterial_category','amaterial_material','amaterial_unit','amaterial_qty','amaterial_added_on','amaterial_added_by','amaterial_updated_on','amaterial_updated_by','amaterial_status'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'amaterial_project', 'project_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'amaterial_material', 'material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'amaterial_unit', 'unit_id');
    }
}