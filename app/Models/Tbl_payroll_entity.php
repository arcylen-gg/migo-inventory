<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_entity extends Model
{
    protected $table = 'tbl_payroll_entity';
	protected $primaryKey = "payroll_entity_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] payroll_entity_id
	// [VARCHAR] entity_name
	// [VARCHAR] entity_category
}
