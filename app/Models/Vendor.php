<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $primaryKey = 'vendor_id';

    public $timestamps = false;

    protected $fillable = ['vendor_category','vendor_name','vendor_company','vendor_mobile','vendor_altno','vendor_location','vendor_type','vendor_remarks','vendor_card','vendor_added_on','vendor_added_by','vendor_updated_on','vendor_updated_by','vendor_status','vendor_trash'];

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'vendor_category', 'mcategory_id');
    }
}