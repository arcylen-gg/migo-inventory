<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_category;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_settings;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_item_multiple_price;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_um;
use App\Models\Tbl_item_merchant_request;
use App\Models\Tbl_user;
use App\Models\Tbl_merchant_markup;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_merchant_commission;

use App\Globals\Category;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\DigimaTable;
use App\Globals\Warehouse;
use App\Globals\Item;
use App\Globals\Vendor;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Utilities;

use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;
use Input;
use Validator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request as Request2;
use Image;
use File;
use App\Models\Tbl_merchant_ewallet;

class MerchantewalletController extends Member
{
	public function index()
	{
		$user_is_merchant  = $this->user_info->user_is_merchant;
		if($user_is_merchant == 0)
		{
			return $this->index_admin();
		}
		else
		{
			return $this->index_merchant();
		}
	}
	public function index_admin()
	{
		$user_id = $this->user_info->user_id;
		$data = [];
		$data['merchant'] = $this->list_all_merchant();
		return view('member.merchant.ewallet.admin', $data);
	}
	public function index_merchant()
	{	
		$user_id 			= $this->user_info->user_id;

		$data = [];
		$data['user_id'] 	= $user_id; 
		$data['recievable'] = $this->get_all_payable($user_id,'payable','sum'); 
		$data['Requested'] 	= $this->get_all_payable($user_id,'Requested','sum'); 
		$data['Paid'] 	= $this->get_all_payable($user_id,'Paid','sum'); 
		$data['Completed'] 	= $this->get_all_payable($user_id,'Completed','sum'); 
		$data['Denied'] 	= $this->get_all_payable($user_id,'Denied','count'); 

		return view('member.merchant.ewallet.merchant', $data);
	}
	public function payable_list()
	{
		$user_id = Request::input('user_id');
		$find = Request::input('find');
        $list = Request::input('list');

        $data['user'] = Tbl_user::where('user_id', $user_id)->first();
        if($list == 2)
        {
        	$data['user2'] = $this->user_info;
        	$data['headers'] = $find;
        	$data['r_list'] = Tbl_merchant_ewallet::where('merchant_ewallet_status', $find)->where('merchant_ewallet_user_request', $user_id)->get();
        	return view('member.merchant.ewallet.list2', $data);
        }
        $merchant_ewallet_id = Request::input('merchant_ewallet_id');
        if($merchant_ewallet_id)
        {
        	$data['back'] = '/member/merchant/ewallet/list?user_id='.$user_id.'&find='.$find.'&list=2';
        }
		$data['headers'] = $this->headers($find);
		$data['payable'] = $this->get_all_payable($user_id, $find, 'get');
		return view('member.merchant.ewallet.list', $data);
	}
	public function request_update()
	{

		$data = [];

		$merchant_ewallet_id = Request::input('merchant_ewallet_id');
		$find = Request::input('find');
		$user_id = Request::input('user_id');

		// $data['back'] = null;
		$data['user'] = $this->user_info;
		$data['user_a'] = Tbl_user::where('user_id', $user_id)->first();
		$data['merchant_ewallet'] = Tbl_merchant_ewallet::where('merchant_ewallet_id', $merchant_ewallet_id)->first();
		$data['mode'] = Request::input('mode');
		$data['mode_a'] = Request::input('mode_a');
		return view('member.merchant.ewallet.request_paid', $data);
	}
	public function request_update_submit(Request2 $request)
	{
		$proof 	= null;
		$update = [];
		if($request->hasFile('merchant_ewallet_request_proof'))
		{ 
			$avatar 			= $request->file('merchant_ewallet_request_proof'); 
			$shop_id 			= $this->user_info->shop_id;
			$shop_key 			= $this->user_info->shop_key;
			$destinationPath 	= 'uploads/'.$shop_key."-".$shop_id;
			if(!File::exists($destinationPath)) 
			{
				$create_result 	= File::makeDirectory(public_path($destinationPath), 0775, true, true);
			}
			$filename 			= time() . '.' . $avatar->getClientOriginalExtension(); Image::make($avatar)->save( $destinationPath .'/' . $filename); 

			$proof 				= '/' . $destinationPath.'/'.$filename;
		} 
		$merchant_ewallet_id = Request::input('merchant_ewallet_id');

		$merchant_ewallet_status 			= Request::input('merchant_ewallet_status');
		$merchant_ewallet_request_remarks 	= Request::input('merchant_ewallet_request_remarks');
		$merchant_ewallet_remarks 			= Request::input('merchant_ewallet_remarks');
		if($merchant_ewallet_status 			!= null)
		{ 
			$update['merchant_ewallet_status'] 			= $merchant_ewallet_status; 
			if($merchant_ewallet_status == 'Completed'){ $update['merchant_ewallet_approve_date'] = Carbon::now(); }
			if($merchant_ewallet_status == 'Denied')
			{ 

				$update['merchant_ewallet_deny_date'] = Carbon::now();
				$update_invioce['merchant_ewallet_id'] = 0;
				Tbl_item_code_invoice::where('merchant_ewallet_id', $merchant_ewallet_id)->update($update_invioce);
			}
		}
		if($merchant_ewallet_request_remarks 	!= null){ $update['merchant_ewallet_request_remarks'] 	= $merchant_ewallet_request_remarks; }
		if($merchant_ewallet_remarks 			!= null){ $update['merchant_ewallet_remarks'] 			= $merchant_ewallet_remarks; }
		if($proof != null){ $update['merchant_ewallet_request_proof'] = $proof; }
		

		Tbl_merchant_ewallet::where('merchant_ewallet_id', $merchant_ewallet_id)->update($update);

		return Redirect::back();
	}
	public function headers($find)
	{
		$ismerchant = $this->user_info->user_is_merchant;
		if($ismerchant == 0){ $data['payable'] = 'Payable'; }
		else{ $data['payable'] = 'Recievable'; }
		
		if(!isset($data[$find])){ return $find; }

		return $data[$find];
	}
	public function request()
	{
		$data = [];
		$data['user'] = Tbl_user::where('user_id', Request::input('user_id'))->first();
		return view('member.merchant.ewallet.request', $data);
	}
	public function verify()
	{
		$data = [];
		$user_id = Request::input('user_id');
		$data['user'] = Tbl_user::where('user_id', $user_id)->first();
		$data['payable'] = $this->get_all_payable($user_id, 'payable', 'get');
		return view('member.merchant.ewallet.verfiy', $data);
	}
	public function verify_submit()
	{
		$user_id 				= Request::input('user_id');
		$data['user'] 			= Tbl_user::where('user_id', $user_id)->first();
		$data['payable'] 		= $this->get_all_payable($user_id, 'payable', 'get');
		$data['payable_sum'] 	= $this->get_all_payable($user_id, 'payable', 'sum');
		$where_in = [];

		foreach ($data['payable'] as $key => $value) 
		{
			$where_in[$key] = $value->item_code_invoice_id;
		}

		$insert['merchant_ewallet_user_request'] 		= $user_id;
		$insert['merchant_ewallet_user_send_request'] 	= $user_id;
		$insert['merchant_ewallet_amount'] 				= $data['payable_sum'];
		$insert['merchant_ewallet_status'] 				= 'Requested';
		$insert['merchant_ewallet_request_date'] 		= Carbon::now(); 
		$insert['merchant_ewallet_request_from'] 		= Request::input('request_from');
		$insert['merchant_ewallet_request_to'] 			= Request::input('request_to');

		$id = Tbl_merchant_ewallet::insertGetId($insert);

		$update['merchant_ewallet_id'] = $id;
		Tbl_item_code_invoice::whereIn('item_code_invoice_id', $where_in)->update($update);

		return Redirect::back();
	}
	public function list_all_merchant()
	{
		$shop_id = $this->user_info->shop_id;
		$merchant = Tbl_user::where('user_shop', $shop_id)->where('user_is_merchant', 1)->get();

		foreach ($merchant as $key => $value) 
		{
			$merchant[$key]->payable 	= $this->get_all_payable($value->user_id, 'payable', 'sum');
			$merchant[$key]->Requested 	= $this->get_all_payable($value->user_id, 'Requested', 'sum');
			$merchant[$key]->Paid 	= $this->get_all_payable($value->user_id, 'Paid', 'sum');
			$merchant[$key]->Completed 	= $this->get_all_payable($value->user_id, 'Completed', 'sum');
			$merchant[$key]->Denied 	= $this->get_all_payable($value->user_id, 'Denied', 'count');
		}

		return $merchant;
	}
	public function get_all_payable($user_id, $status ='payable', $get ='get')
	{
		$item_code_payment_type[3] = 3; // E-wallet
		$item_code_payment_type[4] = 4; // E-money

		$payable = Tbl_item_code_invoice::whereIn('item_code_payment_type', $item_code_payment_type)->where('user_id', $user_id);

		if($status == 'payable')
		{
			$payable = $payable->where('tbl_item_code_invoice.merchant_ewallet_id', 0);
		}
		else
		{
			$payable = $payable->join('tbl_merchant_ewallet', 'tbl_merchant_ewallet.merchant_ewallet_id', '=', 'tbl_item_code_invoice.merchant_ewallet_id')
							   ->where('tbl_item_code_invoice.merchant_ewallet_id', '!=', 0)
							   ->where('merchant_ewallet_status', $status);
		}

		$request_from = Request::input('request_from');
		$request_to = Request::input('request_to');

		if($request_from && $request_to)
		{
			$request_to = Carbon::parse($request_to)->endOfDay(); 
			$payable = $payable->where('item_code_date_created', '>=', $request_from)->where('item_code_date_created', '<=', $request_to);
		}

		$merchant_ewallet_id = Request::input('merchant_ewallet_id');
		if($merchant_ewallet_id)
		{
			$payable = $payable->where('tbl_merchant_ewallet.merchant_ewallet_id', $merchant_ewallet_id);
		}

		if($get == 'sum')
		{
			$deduct = $payable->sum('merchant_markup_value');
			$payable = $payable->sum('item_total');	

			$payable = $payable - $deduct;
			return $payable;
		}
		if($get =='count')
		{
			$count = Tbl_merchant_ewallet::where('merchant_ewallet_status', $status)->where('merchant_ewallet_user_request', $user_id)->count();
			return $count;
		}

		$payable = $payable->get();	
		return $payable;
	}
}	