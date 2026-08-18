<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = ['user_role','user_name','user_lname','user_email','user_phone','user_password','user_vpassword','user_image','user_added_on','user_added_by','user_updated_on','user_updated_by','user_status'];

    protected $hidden = ['user_password'];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'user_role', 'role_id');
    }
}
