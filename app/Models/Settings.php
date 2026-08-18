<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $primaryKey = 'settings_id';

    public $timestamps = false;

    protected $fillable = ['settings_title','settings_company','settings_email','settings_phone','settings_address','settings_logo','settings_wlogo','settings_favicon','settings_mailer','settings_host','settings_port','settings_uname','settings_pwd','settings_encryption','settings_faddress','settings_fname','settings_added_on','settings_added_by','settings_updated_on','settings_updated_by','settings_status'];
}
