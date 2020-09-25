<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_Admin extends Model
{
   	protected $table = 'tbl_admin';
	protected $primaryKey = "admin_id";
    public $timestamps = false;
}

	