<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_register_column extends Model
{
    //
        protected $table = 'tbl_payroll_register_columns';
	protected $primaryKey = "payroll_register_columns_id";
    public $timestamps = false;
}
