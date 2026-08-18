<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    protected $primaryKey = 'rpermission_id';

    public $timestamps = false;

    protected $fillable = ['rpermission_role','rpermission_permission','rpermission_added_on','rpermission_added_by','rpermission_updated_on','rpermission_updated_by','rpermission_status'];
}
