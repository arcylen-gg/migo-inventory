<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_code_transfer_log extends Model
{
    protected $table = 'tbl_item_code_transfer_log';
	protected $primaryKey = "item_code_transfer_log_id";
    public $timestamps = false;
}
