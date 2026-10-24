<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourTracker extends Model
{
    use \App\Models\Concerns\HasProjectContext;

    protected $table = 'labour_trackers';

    protected $primaryKey = 'ltracker_id';

    public $timestamps = false;

    protected $fillable = ['ltracker_date','ltracker_project','ltracker_subcontractor','ltracker_incharge','ltracker_shift','ltracker_remarks','ltracker_in','ltracker_out','ltracker_thours','ltracker_total','ltracker_added_on','ltracker_added_by','ltracker_updated_on','ltracker_updated_by','ltracker_status','ltracker_trash'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'ltracker_project', 'project_id');
    }

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class, 'ltracker_subcontractor', 'subcontractor_id');
    }

    public function incharge()
    {
        return $this->belongsTo(User::class, 'ltracker_incharge', 'user_id');
    }
}