<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndentQuotation extends Model
{
    use \App\Models\Concerns\HasProjectParent;
    protected $table = 'indent_quotations';

    protected $primaryKey = 'iquote_id';

    public $timestamps = false;

    protected $fillable = ['iquote_indent','iquote_vendor','iquote_rate','iquote_total','iquote_delivery','iquote_notes','iquote_quotation','iquote_added_on','iquote_added_by','iquote_updated_on','iquote_updated_by','iquote_status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'iquote_vendor', 'vendor_id');
    }
}
