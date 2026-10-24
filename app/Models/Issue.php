<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use \App\Models\Concerns\HasProjectContext;

    protected $table = 'issues';

    protected $primaryKey = 'issue_id';

    public $timestamps = false;

    protected $fillable = ['issue_date','issue_project','issue_title','issue_description','issue_category','issue_priority','issue_assign','issue_remarks','issue_image','issue_sremarks','issue_added_on','issue_added_by','issue_updated_on','issue_updated_by','issue_status'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'issue_project', 'project_id');
    }

    public function category()
    {
        return $this->belongsTo(IssueCategory::class, 'issue_category', 'icategory_id');
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'issue_assign', 'user_id');
    }

    public function raise()
    {
        return $this->belongsTo(User::class, 'issue_added_by', 'user_id');
    }
}