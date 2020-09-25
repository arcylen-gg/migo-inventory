<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_email_template extends Model
{    
	protected $table = 'tbl_email_template';
	protected $primaryKey = "email_template_id";
    public $timestamps = false;
}
