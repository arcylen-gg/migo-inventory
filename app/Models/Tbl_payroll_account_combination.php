<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_account_combination extends Model
{
    protected $table = 'tbl_payroll_account';
	protected $primaryKey = "payroll_account_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */

	// [INTEGER] payroll_account_combination_id
	// [INTEGER] payroll_account_id
	// [INTEGER] payroll_employee_id
}
