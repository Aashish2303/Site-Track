<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    protected $table = 'module_permissions';

    protected $primaryKey = 'mpermission_id';

    public $timestamps = false;

    protected $fillable = ['mpermission_module','mpermission_name','mpermission_slug','mpermission_added_on','mpermission_added_by','mpermission_updated_on','mpermission_updated_by','mpermission_status'];
}
