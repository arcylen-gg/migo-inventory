<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_employee_requirements extends Model
{
    protected $table = 'tbl_payroll_employee_requirements';
	protected $primaryKey = "payroll_employee_requirements_id";
    public $timestamps = false;

    /* COLUMN REFERENCES */
    // [PRIMARY] 		payroll_employee_requirements_id 
    // [INTEGER] 		payroll_employee_id
    // [TINYINTEGER] 	has_resume
	// [INTEGER] 		resume_requirements_id
	// [TINYINTEGER] 	has_police_clearance
	// [INTEGER] 		police_clearance_requirements_id
	// [TINYINTEGER] 	has_nbi
	// [INTEGER] 		nbi_payroll_requirements_id
	// [TINYINTEGER] 	has_health_certificate
	// [INTEGER] 		health_certificate_requirements_id
	// [TINYINTEGER] 	has_school_credentials
	// [INTEGER] 		school_credentials_requirements_id
	// [TINYINTEGER] 	has_valid_id
	// [INTEGER] 		valid_id_requirements_id

	public function scopeselrequirements($query, $payroll_employee_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)
			  ->select(DB::raw(Tbl_payroll_employee_requirements::query_string_path('resume_requirements_id','resume').','.Tbl_payroll_employee_requirements::query_string_path('police_clearance_requirements_id','police_clearance').','.Tbl_payroll_employee_requirements::query_string_path('nbi_payroll_requirements_id','nbi_clearance').','.Tbl_payroll_employee_requirements::query_string_path('health_certificate_requirements_id','heatlh_certificate').','.Tbl_payroll_employee_requirements::query_string_path('school_credentials_requirements_id','school_credentials').','.Tbl_payroll_employee_requirements::query_string_path('valid_id_requirements_id','valid_id').', tbl_payroll_employee_requirements.*'));

		return $query;
	}

	public function query_string_path($payroll_requirements_id = 0, $alias)
	{
		$string = '(select payroll_requirements_path from tbl_payroll_requirements where payroll_requirements_id = '.$payroll_requirements_id.') as '.$alias;
		$string .= ',(select payroll_requirements_original_name from tbl_payroll_requirements where payroll_requirements_id = '.$payroll_requirements_id.') as '.$alias.'_name';
		return $string;
	}
}
