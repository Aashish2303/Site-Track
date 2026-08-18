<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $table = 'order_delivery';

    protected $primaryKey = 'odelivery_id';

    public $timestamps = false;

    protected $fillable = ['odelivery_order','odelivery_date','odelivery_qty','odelivery_dc','odelivery_added_on','odelivery_added_by','odelivery_updated_on','odelivery_updated_by','odelivery_status'];
}