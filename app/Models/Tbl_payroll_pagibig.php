<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_pagibig extends Model
{
     protected $table = 'tbl_payroll_pagibig';
	protected $primaryKey = "payroll_pagibig_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_pagibig_id
	// [INTEGER]		shop_id
	// [DOUBLE] 		payroll_pagibig_percent
}
