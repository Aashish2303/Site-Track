<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use \App\Models\Concerns\HasProjectContext;

    protected $table = 'projects';

    protected $primaryKey = 'project_id';

    public $timestamps = false;

    protected $fillable = ['project_name','project_location','project_client','project_engineer','project_start','project_end','project_budget','project_added_on','project_added_by','project_updated_on','project_updated_by','project_status','project_trash'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'project_client', 'client_id');
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'project_engineer', 'user_id');
    }
}