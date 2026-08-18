<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $primaryKey = 'unit_id';

    public $timestamps = false;

    protected $fillable = ['unit_name','unit_added_on','unit_added_by','unit_updated_on','unit_updated_by','unit_status'];
}