<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_time_sheet_record_approved extends Model
{
    protected $table = 'tbl_payroll_time_sheet_record_approved';
	protected $primaryKey = "payroll_time_sheet_record_id";
    public $timestamps = false;

    public function scopeTimesheetPeriod()
    {

    }



}
