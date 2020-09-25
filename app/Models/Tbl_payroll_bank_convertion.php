<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_bank_convertion extends Model
{
    protected $table = 'tbl_payroll_bank_convertion';
	protected $primaryKey = "payroll_bank_convertion_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] payroll_bank_convertion_id
	// [VARCHAR]	 bank_name
}
