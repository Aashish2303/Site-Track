<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    protected $table = 'material_category';

    protected $primaryKey = 'mcategory_id';

    public $timestamps = false;

    protected $fillable = ['mcategory_name','mcategory_added_on','mcategory_added_by','mcategory_updated_on','mcategory_updated_by','mcategory_status'];
}