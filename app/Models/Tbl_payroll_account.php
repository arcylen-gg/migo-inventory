<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_account extends Model
{
    protected $table = 'tbl_payroll_account';
	protected $primaryKey = "payroll_account_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] payroll_account_id
	// [INTEGER] shop_id
	// [INTEGER] account_id
	// [VARCHAR] column_name
}
