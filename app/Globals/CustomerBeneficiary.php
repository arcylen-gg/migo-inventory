<?php
namespace App\Globals;

use App\Models\Tbl_customer_beneficiary;
use DB;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * 
 *
 * @author Arcylen
 */

class CustomerBeneficiary
{
	public static function create($customer_id , $data)
	{
		$check = Tbl_customer_beneficiary::where('customer_id',$customer_id)->first();
		$return = null;
		if($check)
		{
			$return = Tbl_customer_beneficiary::where('customer_id',$customer_id)->update($data);
		}
		if(!$check)
		{	
			$data['customer_id'] = $customer_id;
			$return = Tbl_customer_beneficiary::insertGetId($data);			
		}

		return $return;
	}
	public static function first($customer_id = 0)
	{
		return Tbl_customer_beneficiary::where('customer_id',$customer_id)->first();
	}
}