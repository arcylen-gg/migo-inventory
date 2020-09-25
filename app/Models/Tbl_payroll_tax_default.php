<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_tax_default extends Model
{
    protected $table = 'tbl_payroll_tax_default';
	protected $primaryKey = "payroll_tax_default_id";
    public $timestamps = false;
}
