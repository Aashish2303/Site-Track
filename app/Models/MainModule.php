<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainModule extends Model
{
    protected $table = 'main_modules';

    protected $primaryKey = 'mmodule_id';

    public $timestamps = false;

    protected $fillable = ['mmodule_name','mmodule_order','mmodule_added_on','mmodule_added_by','mmodule_updated_on','mmodule_updated_by','mmodule_status'];
}
