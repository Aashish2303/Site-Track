<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $primaryKey = 'client_id';

    public $timestamps = false;

    protected $fillable = ['client_name','client_email','client_phone','client_address','client_added_on','client_added_by','client_updated_on','client_updated_by','client_status'];
}