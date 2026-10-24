<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyProgress extends Model
{
    use \App\Models\Concerns\HasProjectContext;

    protected $table = 'daily_progress';

    protected $primaryKey = 'dprogress_id';

    public $timestamps = false;

    protected $fillable = ['dprogress_date','dprogress_project','dprogress_description','dprogress_boq','dprogress_qty','dprogress_pdone','dprogress_location','dprogress_category','dprogress_subcontractor','dprogress_incharge','dprogress_remarks','dprogress_cforward','dprogress_added_on','dprogress_added_by','dprogress_updated_on','dprogress_updated_by','dprogress_status','dprogress_trash'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'dprogress_project', 'project_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'dprogress_category', 'category_id');
    }

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class, 'dprogress_subcontractor', 'subcontractor_id');
    }

    public function incharge()
    {
        return $this->belongsTo(User::class, 'dprogress_incharge', 'user_id');
    }

    public function boq()
    {
        return $this->belongsTo(ProjectBoq::class, 'dprogress_boq','pboq_id');
    }
}