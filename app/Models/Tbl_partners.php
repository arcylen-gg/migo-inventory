<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_partners extends Model
{
   	protected $table = 'tbl_partners';
	protected $primaryKey = "company_id";
    public $timestamps = false;
}