<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_recaptcha_pool_amount extends Model
{
   	protected $table = 'tbl_recaptcha_pool_amount';
	protected $primaryKey = "recaptcha_pool_amount_id";
    public $timestamps = false;
}