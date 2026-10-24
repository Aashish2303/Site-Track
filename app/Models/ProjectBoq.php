<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBoq extends Model
{
    use \App\Models\Concerns\HasProjectContext;

    protected $table = 'project_boqs';

    protected $primaryKey = 'pboq_id';

    public $timestamps = false;

    protected $fillable = ['pboq_project','pboq_item','pboq_unit','pboq_qty','pboq_rate','pboq_amount','pboq_remarks','pboq_extra','pboq_added_on','pboq_added_by','pboq_updated_on','pboq_updated_by','pboq_status'];
}