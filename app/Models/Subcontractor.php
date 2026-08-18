<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcontractor extends Model
{
    protected $table = 'subcontractors';

    protected $primaryKey = 'subcontractor_id';

    public $timestamps = false;

    protected $fillable = ['subcontractor_name','subcontractor_email','subcontractor_phone','subcontractor_address','subcontractor_added_on','subcontractor_added_by','subcontractor_updated_on','subcontractor_updated_by','subcontractor_status'];
}