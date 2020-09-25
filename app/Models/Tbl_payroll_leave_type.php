<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_leave_type extends Model
{
    //

    protected $table = 'tbl_payroll_leave_typev2';
	protected $primaryKey = "payroll_leave_type_id";
    public $timestamps = false;

    public function scopesel($query, $shop_id = 0, $archived = 0)
	{
		return $query->where('shop_id', $shop_id)->where('payroll_leave_type_archived', $archived);
	}

}
