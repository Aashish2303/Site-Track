<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $primaryKey = 'stock_id';

    public $timestamps = false;

    protected $fillable = ['stock_material','stock_qty','stock_added_on','stock_added_by','stock_updated_on','stock_updated_by','stock_status'];

    public function material()
    {
        return $this->belongsTo(Material::class, 'stock_material', 'material_id');
    }
}
