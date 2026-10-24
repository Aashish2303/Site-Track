<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndentItem extends Model
{
    use \App\Models\Concerns\HasProjectParent;
    protected $table = 'indent_items';

    protected $primaryKey = 'iitem_id';

    public $timestamps = false;

    protected $fillable = ['iitem_indent','iitem_item','iitem_category','iitem_size','iitem_qty','iitem_unit','iitem_added_on','iitem_added_by','iitem_updated_on','iitem_updated_by','iitem_status'];

    public function indent()
    {
        return $this->belongsTo(Indent::class, 'iitem_indent', 'indent_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'iitem_item', 'material_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'iitem_category', 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'iitem_unit', 'unit_id');
    }
}
