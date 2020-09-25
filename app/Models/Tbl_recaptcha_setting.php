<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_recaptcha_setting extends Model
{
   	protected $table = 'tbl_recaptcha_setting';
	protected $primaryKey = "recaptcha_setting_id";
    public $timestamps = false;
}