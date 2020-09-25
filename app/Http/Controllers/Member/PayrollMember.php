<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_user;
use App\Models\Tbl_user_position;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_warehouse;

use App\Globals\Account;
use App\Globals\Warehouse;
use App\Globals\Seed_manual;
use App\Globals\Utilities;
use App\Globals\Payroll;
use App\Globals\Settings;

use Crypt;
use Redirect;
use Request;
use View;
use Session;
use Carbon\Carbon;
use App\Globals\Mlm_seed;
class PayrollMember extends Controller
{
	public $employee_info;

	public function __construct()
	{
		
		$this->middleware(function ($request, $next)
		{
			if(!session('employee_email') || !session('employee_password'))
			{
				return Redirect::to("/employee_login")->send();
			}
			else
			{
				$employee_email 	= session('employee_email');
				$employee_password	= session('employee_password');
				$employee_info	= Tbl_payroll_employee_basic::where('payroll_employee_email',$employee_email)->where('payroll_employee_tin',$employee_password)->first();
				$this->employee_info = $employee_info;

				if(isset($employee_info->payroll_employee_id))
				{
					View::share('employee_id', $employee_info->payroll_employee_id);
				}
				else
				{
					View::share('employee_id', null);
				}
			}

			//dd($employee_info);
			return $next($request);
		});
	}
}