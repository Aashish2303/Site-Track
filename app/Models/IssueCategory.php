<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueCategory extends Model
{
    protected $table = 'issue_category';

    protected $primaryKey = 'icategory_id';

    public $timestamps = false;

    protected $fillable = ['icategory_name','icategory_added_on','icategory_added_by','icategory_updated_on','icategory_updated_by','icategory_status'];
}