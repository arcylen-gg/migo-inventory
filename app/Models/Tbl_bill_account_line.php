<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_bill_account_line extends Model
{
    protected $table = 'tbl_bill_account_line';
	protected $primaryKey = "accline_id";
    public $timestamps = false;
}
