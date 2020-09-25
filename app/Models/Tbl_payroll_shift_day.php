<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_shift_day extends Model
{
    protected $table = 'tbl_payroll_shift_day';
	protected $primaryKey = "shift_day_id";
    public $timestamps = false;
}
