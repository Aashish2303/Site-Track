<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourWorker extends Model
{
    protected $table = 'labour_workers';

    protected $primaryKey = 'lworker_id';

    public $timestamps = false;

    protected $fillable = ['lworker_tracker','lworker_category','lworker_skill','lworker_male','lworker_female','lworker_wage','lworker_total','lworker_added_on','lworker_added_by','lworker_updated_on','lworker_updated_by','lworker_status'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'lworker_category', 'category_id');
    }
}