<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indent extends Model
{
    protected $table = 'indents';

    protected $primaryKey = 'indent_id';

    public $timestamps = false;

    protected $fillable = ['indent_date','indent_project','indent_urgency','indent_engineer','indent_notes','indent_napproval','indent_added_on','indent_added_by','indent_updated_on','indent_updated_by','indent_status'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'indent_project', 'project_id');
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'indent_engineer', 'user_id');
    }
}