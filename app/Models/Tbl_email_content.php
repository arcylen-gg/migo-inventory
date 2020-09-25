<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_email_content extends Model
{
	protected $table = 'tbl_email_content';
	protected $primaryKey = "email_content_id";
    public $timestamps = false;
}
