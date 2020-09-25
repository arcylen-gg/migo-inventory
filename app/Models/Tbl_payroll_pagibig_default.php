<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_pagibig_default extends Model
{
    protected $table = 'tbl_payroll_pagibig_default';
	protected $primaryKey = "payroll_pagibig_default_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_pagibig_default_id
	// [DOUBLE] 		payroll_pagibig_percent
}
