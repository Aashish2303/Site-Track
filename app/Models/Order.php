<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    public $timestamps = false;

    protected $fillable = ['order_date','order_po_no','order_vendor','order_material','order_unit','order_qty','order_rate','order_pay_status','order_added_on','order_added_by','order_updated_on','order_updated_by','order_status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'order_vendor', 'vendor_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'order_material', 'material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'order_unit', 'unit_id');
    }
}