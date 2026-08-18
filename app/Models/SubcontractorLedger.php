<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubcontractorLedger extends Model
{
    protected $table = 'subcontractor_ledger';

    protected $primaryKey = 'sledger_id';

    public $timestamps = false;

    protected $fillable = ['sledger_subcontractor','sledger_project','sledger_date','sledger_type','sledger_wpno','sledger_description','sledger_total','sledger_invoice','sledger_paid','sledger_remarks','sledger_added_on','sledger_added_by','sledger_updated_on','sledger_updated_by','sledger_status'];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class, 'sledger_subcontractor','subcontractor_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'sledger_project','project_id');
    }
}