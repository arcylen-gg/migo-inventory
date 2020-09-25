<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_membership_code_transfer_log extends Model
{
    protected $table = 'tbl_membership_code_transfer_log';
	protected $primaryKey = "membership_code_transfer_log_id";
    public $timestamps = false;
}
