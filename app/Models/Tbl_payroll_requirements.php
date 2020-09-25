<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_requirements extends Model
{
	protected $table = 'tbl_payroll_requirements';
	protected $primaryKey = "payroll_requirements_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_requirements_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		payroll_requirements_path
	// [VARCHAR] 		payroll_requirements_original_name
	// [VARCHAR] 		payroll_requirements_extension_name
	// [VARCHAR] 		payroll_requirements_mime_type
	// [DATE] 			payroll_requirements_date_upload
}
