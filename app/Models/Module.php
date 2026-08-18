<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $primaryKey = 'module_id';

    public $timestamps = false;

    protected $fillable = ['module_main','module_name','module_added_on','module_added_by','module_updated_on','module_updated_by','module_status'];
}
