<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_slot_notification extends Model
{
   	protected $table = 'tbl_slot_notification';
	protected $primaryKey = "notification_id";
    public $timestamps = false;
}

	