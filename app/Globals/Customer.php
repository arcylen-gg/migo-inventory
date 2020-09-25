<?php
namespace App\Globals;
use App\Models\Tbl_variant;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_order;
use App\Models\Tbl_order_item;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_attachment;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_terms;
use App\Globals\Tablet_global;
use App\Globals\Mlm_plan;
use App\Globals\CommissionCalculator;
use DB;
use Carbon\Carbon;
use Request;
use Crypt;
class Customer
{
	public static function register($shop_id, $info)
	{
		$info["shop_id"] = $shop_id;
		$plan_settings = Mlm_plan::get_settings($shop_id);
		$info["downline_rule"] = $plan_settings->plan_settings_default_downline_rule;
		
		if(session("lead_sponsor"))
		{
			$slot_info = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", session("lead_sponsor"))->first();
			
			if($slot_info)
			{
				$info["customer_lead"] = $slot_info->slot_id;
			}
			
			session()->forget("lead_sponsor");
		}
		

		//dd($info);
		Tbl_customer::insert($info);
		return true;	
	}
	public static function getTerms($shop_id, $archived = 0)
	{
		return Tbl_terms::where("archived", $archived)->where("terms_shop_id", $shop_id)->get();
	}
	public static function scan_customer($shop_id, $id = 0)
	{
		$return = null;
		if($id)
		{
			$check_slot = Tbl_mlm_slot::where('slot_no',$id)->where('shop_id',$shop_id)->value('slot_owner');

			if($check_slot)
			{
				$return = Customer::get_info_membership($shop_id, $check_slot);
			}
			else
			{
				$check_customer = Customer::get_info_membership($shop_id, $id);

				if($check_customer)
				{
					$return = $check_customer;
				}
			}

		}

		return $return;
	}
	public static function get_info_membership($shop_id, $customer_id)
	{
		return Tbl_customer::membership()->where("customer_id", $customer_id)->where("tbl_customer.shop_id", $shop_id)->first();
	}
	public static function check_account($shop_id, $email, $password = '')
	{
		$check_account =  Tbl_customer::where("shop_id", $shop_id)->where("email", $email)->first();
		//dd(Crypt::decrypt($check_account->password));
		if($check_account)
		{
	        if(Crypt::decrypt($check_account->password) != $password)
	        {
	            return false;
	        }
	        else
	        {
	        	return $check_account;
	        }
		}
		else
		{
			return false;
		}

	}

	public static function check_email($shop_id, $email)
	{
		$check_account =  Tbl_customer::where("shop_id", $shop_id)->where("email", $email)->first();
		//dd(Crypt::decrypt($check_account->password));
		if($check_account)
		{
	        return $check_account;
		}
		else
		{
			return false;
		}
	}
	public static function get_info($shop_id, $customer_id)
	{
		return Tbl_customer::where("customer_id", $customer_id)->where("shop_id", $shop_id)->first();
	}
	public static function get_name($shop_id, $customer_id)
	{
		$name = "No Customer Found";
		$customer = Self::get_info($shop_id, $customer_id);
		if($customer)
		{
			$name = $customer->company != "" ? $customer->company : ucwords($customer->first_name.' '.$customer->middle_name.' '.$customer->last_name);
		}
		return $name;
	}
	public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    
	public static function info($id = 0, $shop_id = 0, $order_id = 0)
	{
        $data['customer'] = Tbl_customer::membership()->leftjoin('tbl_country','tbl_country.country_id','=','tbl_customer.country_id')->where('tbl_customer.customer_id',$id)->first();
        $data['_slot'] = Tbl_mlm_slot::where('slot_owner',$id)->get();
        $data['shipping'] = Tbl_customer_address::leftjoin('tbl_country','tbl_country.country_id','=','tbl_customer_address.country_id')->where('tbl_customer_address.customer_id',$id)->where('tbl_customer_address.purpose','shipping')->where('tbl_customer_address.archived',0)->first();
        $data['other'] = Tbl_customer_other_info::where('customer_id',$id)->first();
        // $tax_exempt = $data['customer']->taxt_exempt;
        $tax_exempt = 0;
        $count = Tbl_order::where('shop_id', $shop_id)->where('status','new')->count();
        $order['customer_id'] = $id;
        $order['isTaxExempt'] = $tax_exempt;
        $data['order_id'] = $order_id;
        return $data;
	}
	public static function get_points_wallet($customer_id = 0)
	{
		$data['total_wallet'] = Tbl_mlm_slot::where("slot_owner", $customer_id)->currentWallet()->value('current_wallet');
		$data['total_gc'] = Tbl_mlm_slot::mlm_points()->where("slot_owner",$customer_id)->where("points_log_type","GC")->sum("points_log_points");
		return $data;
	}	
	public static function get_current_slot_gc()
	{
		$slot_no = request('slot_no');
		$current_slot_gc = Tbl_mlm_slot::mlm_points()->where("slot_no",$slot_no)->where("points_log_type","GC")->sum("points_log_points");
		// dd($current_slot_gc);
		return currency('',$current_slot_gc);
	}

	public static function get_points_wallet_per_slot($slot_id = 0)
	{
		$data['total_wallet'] = Tbl_mlm_slot::where("slot_id", $slot_id)->currentWallet()->value('current_wallet');
		$data['total_gc'] = Tbl_mlm_slot::mlm_points()->where("slot_id",$slot_id)->where("points_log_type","GC")->sum("points_log_points");
		return $data;
	}
	public static function slot_info($slot_id)
	{
		return Tbl_mlm_slot::where('slot_id',$slot_id)->first();
	}
	public static function search($str = '', $shop_id = 0, $archived = 0)
	{
		if($str != '' && $str != null){
    		$data['_customer'] = Tbl_customer_search::join('tbl_customer','tbl_customer.customer_id','=','tbl_customer_search.customer_id')
    						->join('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')
    						->whereRaw("MATCH(tbl_customer_search.body) AGAINST('*".$str."*' IN BOOLEAN MODE)")
    						->where('tbl_customer.archived',$archived)
    						->where('tbl_customer.IsWalkin',0)
    						->where('tbl_customer.shop_id',$shop_id)
    						->select(DB::raw('tbl_customer.*'))
    						->orderBy('tbl_customer.first_name','asc')
    						->get();
    	}
    	else{
    		$data['_customer'] = Tbl_customer::where('archived',0)->where('shop_id',$shop_id)->where('tbl_customer.IsWalkin',0)->orderBy('first_name','asc')->get();
    	}
    	// dd($shop_id);
    	return $data;
	}
	public static function search_get($shop_id, $keyword = null, $paginate = 0, $archived = 0, $category = 'all', $type = 'all')
	{
		$return = Tbl_customer::selectRaw("*, tbl_customer.archived as customer_archived")->address()->where('shop_id', $shop_id);

		// if($keyword != '')
		// {
		// 	$return->leftjoin("tbl_customer_search","tbl_customer_search.customer_id","=","tbl_customer.customer_id")
		// 		   ->where("tbl_customer_search.body", "LIKE", "%" . $keyword . "%");
		// }
		// $query = $return;
		if($keyword)
		{
			$return->where(function($q) use ($keyword)
            {
                $q->orWhere("tbl_customer.first_name", "LIKE", "%$keyword%");
                $q->orWhere("tbl_customer.last_name", "LIKE", "%$keyword%");
                $q->orWhere("tbl_customer.middle_name", "LIKE", "%$keyword%");
                $q->orWhere("tbl_customer.company", "LIKE", "%$keyword%");
            });
		}
		if($category != 'all')
		{
			$return = $return->where("customer_category",$category);
		}
		if($type != 'all')
		{
			$return = $return->where("customer_category_type",$type);			
		}
		if($paginate != 0)
		{
			$return = $return->groupBy('tbl_customer.customer_id')->where('tbl_customer.archived',$archived)->orderBy("tbl_customer.created_date",'DESC')->paginate($paginate);
		}
		else
		{
			$return = $return->groupBy('tbl_customer.customer_id')->where('tbl_customer.archived',$archived)->orderBy("tbl_customer.created_date",'DESC')->get();
		}
		return $return;
	}
	public static function getAllCustomer($for_tablet = false)
	{
		$shop_id = Customer::getShopId();
		if($for_tablet == true)
		{
			$shop_id = Tablet_global::getShopId();
		}
		$check_settings = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);

		$customer = Tbl_customer::selectRaw("*, tbl_customer.customer_id as customer_id")->info()->where("tbl_customer.archived", 0)->where("tbl_customer.shop_id", $shop_id)->groupBy("tbl_customer.customer_id")->orderBy("tbl_customer.first_name","ASC")->orderBy("tbl_customer.company","ASC");
		// if(CommissionCalculator::check_settings($shop_id) == 1)
		// {
		// 	$customer = $customer->selectRaw('*, tbl_employee.first_name as salesrep_fname, tbl_employee.middle_name as salesrep_mname,tbl_employee.last_name as salesrep_lname')->commission()->salesrep();
		// }
		if($check_settings == 1)
        {
            $customer = $customer->whereNull('customer_warehouse_id')->orWhere('customer_warehouse_id', $warehouse_id)/*->get()*/;
        }
        $_customer = null;
        foreach ($customer->get() as $key => $value) 
        {
        	$_customer[$key] = $value;
        	$_customer[$key]->ctr_si = TransactionSalesInvoice::CountTransaction($shop_id, $value->customer_id);
        	$_customer[$key]->ctr_sr = TransactionSalesReceipt::CountTransaction($shop_id, $value->customer_id);
        	$_customer[$key]->ctr_wis = CustomerWIS::countTransaction($shop_id, $value->customer_id);
        	$_customer[$key]->ctr_rp = TransactionReceivePayment::countAvailableCredit($shop_id, $value->customer_id);
        	$_customer[$key]->ctr_rp_refnum = TransactionReceivePayment::getReferenceNumber($shop_id, $value->customer_id);
        	$_customer[$key]->ctr_so_refnum = TransactionSalesOrder::getReferenceNumber($shop_id, $value->customer_id);
        	$_customer[$key]->ctr_si_refnum = TransactionSalesInvoice::getReferenceNumber($shop_id, $value->customer_id);

        	$_customer[$key]->previous_budget = CustomerWIS::get_previous_budget($shop_id, $value->customer_id);
        }
		return $_customer;
	}
	public static function countAllCustomer($for_tablet = false)
	{
		$shop_id = Customer::getShopId();
		if($for_tablet == true)
		{
			$shop_id = Tablet_global::getShopId();
		}
		$count = Tbl_customer::where("tbl_customer.archived", 0)->where("shop_id", $shop_id)->count();
		return $count;
	}

	public static function createCustomer($shop_id, $customer_info)
	{
		$insert["shop_id"] 		= $shop_id;
		$insert["title_name"] 	= isset($customer_info['customer_title_name']) 
								       ? $customer_info['customer_title_name'] : '';
		$insert["first_name"] 	= isset($customer_info['customer_first_name'])
								      ? $customer_info['customer_first_name'] : '';
		$insert["middle_name"]  = isset($customer_info['customer_middle_name'])
								      ? $customer_info['customer_middle_name'] : '';
		$insert["last_name"]    = isset($customer_info['customer_last_name'])
									  ? $customer_info['customer_last_name'] : '';
		$insert["suffix_name"]  = isset($customer_info['customer_suffix_name'])
									  ? $customer_info['customer_suffix_name'] : '';
		$insert["email"]        = isset($customer_info['customer_email'])
									  ? $customer_info['customer_email'] : '';
		$insert["password"]     = isset($customer_info['customer_password'])
									  ? Crypt::encrypt($customer_info['customer_password']) : '';
		$insert["company"]      = isset($customer_info['customer_company'])
									  ? $customer_info['customer_company'] : '';
		$insert["b_day"]        = isset($customer_info['customer_birthdate'])
									  ? datepicker_input($customer_info['customer_birthdate']) : '';
		$insert["IsWalkin"]     = isset($customer_info['customer_iswalkin'])
									  ? $customer_info['customer_iswalkin'] : 0;
		$insert["created_date"] = Carbon::now();

		$customer_id = Tbl_customer::insertGetId($insert);

		$insertAddress[0]['customer_id'] 		= $customer_id;
		$insertAddress[0]['country_id'] 		= isset($customer_info['customer_country_id'])
									  				  ? $customer_info['customer_country_id'] : 420;
		$insertAddress[0]['customer_state'] 	= isset($customer_info['customer_state'])
									  				  ? $customer_info['customer_state'] : '';
		$insertAddress[0]['customer_city'] 		= isset($customer_info['customer_city'])
									  				  ? $customer_info['customer_city'] : '';
		$insertAddress[0]['customer_zipcode'] 	= isset($customer_info['customer_zipcode'])
									  				  ? $customer_info['customer_zipcode'] : '';
		$insertAddress[0]['customer_street'] 	= isset($customer_info['customer_street'])
									  				  ? $customer_info['customer_street'] : '';
		$insertAddress[0]['purpose'] 			= 'billing';

		$insertAddress[1]['customer_id'] 		= $customer_id;
		$insertAddress[1]['country_id'] 		=isset($customer_info['customer_country_id_ship'])
									  				  ? $customer_info['customer_country_id_ship'] : 420;
		$insertAddress[1]['customer_state'] 	= isset($customer_info['customer_state_ship'])
									  				  ? $customer_info['customer_state_ship'] : '';
		$insertAddress[1]['customer_city'] 		= isset($customer_info['customer_city_ship'])
									  				  ? $customer_info['customer_city_ship'] : '';
		$insertAddress[1]['customer_zipcode'] 	= isset($customer_info['customer_zipcode_ship'])
									  				  ? $customer_info['customer_zipcode_ship'] : '';
		$insertAddress[1]['customer_street'] 	= isset($customer_info['customer_street_ship'])
									  				  ? $customer_info['customer_street_ship'] : '';
		$insertAddress[1]['purpose'] 			= 'shipping';

		Tbl_customer_address::insert($insertAddress);

		$insertOtherInfo['customer_id'] 	= $customer_id;
		$insertOtherInfo['customer_phone'] 	= isset($customer_info['customer_phone'])
									  			  ? $customer_info['customer_phone'] : '';
		$insertOtherInfo['customer_mobile'] = isset($customer_info['customer_mobile'])
									  			  ? $customer_info['customer_mobile'] : '';
		$insertOtherInfo['customer_notes'] 	= isset($customer_info['customer_notes'])
									  			  ? $customer_info['customer_notes'] : '';

		Tbl_customer_other_info::insert($insertOtherInfo);

		return $customer_id;
	}
	public static function delete_customer($shop_id)
	{
		Tbl_customer_address::customer()->where("shop_id", $shop_id)->delete();
		Tbl_customer_other_info::customer()->where("shop_id", $shop_id)->delete();
		Tbl_customer_attachment::customer()->where("shop_id", $shop_id)->delete();
		Tbl_customer_search::customer()->where("shop_id", $shop_id)->delete();
		Tbl_customer::where("shop_id", $shop_id)->delete();
	}
}