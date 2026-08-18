<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyWork extends Model
{
    protected $table = 'daily_works';

    protected $primaryKey = 'dwork_id';

    public $timestamps = false;

    protected $fillable = ['dwork_date','dwork_project','dwork_description','dwork_location','dwork_qty','dwork_category','dwork_subcontractor','dwork_incharge','dwork_remarks','dwork_cforward','dwork_added_on','dwork_added_by','dwork_updated_on','dwork_updated_by','dwork_status','dwork_trash'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'dwork_project', 'project_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'dwork_category', 'category_id');
    }

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class, 'dwork_subcontractor', 'subcontractor_id');
    }

    public function incharge()
    {
        return $this->belongsTo(User::class, 'dwork_incharge', 'user_id');
    }
}