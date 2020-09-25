<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_project_type extends Model
{
   	protected $table = 'tbl_project_type';
	protected $primaryKey = "project_type_id";
    public $timestamps = false;


    public function scopeAccount_line($query)
    {
    	 return $query->join('tbl_bill_account_line', 'tbl_bill_account_line.accline_bill_id', '=', 'tbl_bill.bill_id');
    }
}