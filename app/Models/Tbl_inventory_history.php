<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_inventory_history extends Model
{
	protected $table = 'tbl_inventory_history';
	protected $primaryKey = "history_id";
    public $timestamps = false;
}