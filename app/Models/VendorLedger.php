<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorLedger extends Model
{
    protected $table = 'vendor_ledger';

    protected $primaryKey = 'vledger_id';

    public $timestamps = false;

    protected $fillable = ['vledger_vendor','vledger_project','vledger_date','vledger_type','vledger_pono','vledger_description','vledger_po_total','vledger_invoice','vledger_paid','vledger_remarks','vledger_added_on','vledger_added_by','vledger_updated_on','vledger_updated_by','vledger_status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vledger_vendor','vendor_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'vledger_project','project_id');
    }
}