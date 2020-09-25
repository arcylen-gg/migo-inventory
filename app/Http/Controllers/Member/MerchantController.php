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
use App\Models\Tbl_merchant_commission_report_setting;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_warehouse_inventory_record_log;

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
// use Request;
use Illuminate\Http\Request;
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
use Excel;
use App\Globals\Merchant;
class MerchantController extends Member
{
	public function index()
	{
		// Initialize Required variables
		$shop_id 	= $this->user_info->shop_id;
		$user_id 	= Request::input('user_id');
		$data 		= [];
		$data['selected_merchant']  = null;

		// Select Inventory and non inventory items only.
		$item_type[0] = 1;
		$item_type[1] = 2;
		
		if($user_id)
		{
			$data['selected_merchant'] 	= Tbl_user::where('user_shop', $shop_id)->where('user_id', $user_id)->where('user_is_merchant', 1)->first();					 	
			$data['items'] 				= Tbl_item::whereIn('item_type_id', $item_type)
										->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
	                					->where('item_merchant_requested_by', $user_id)
										->where('tbl_item.shop_id', $shop_id)->get();		

			$markup 					= Tbl_merchant_markup::where('user_id', $user_id)->get()->keyBy('item_id');

			foreach ($data['items']  as $key => $value) 
			{
				if(isset($markup[$value->item_id]))
				{
					$data['items'][$key]->item_markup_percentage 	= $markup[$value->item_id]->item_markup_percentage;
					$data['items'][$key]->item_markup_value 		= $markup[$value->item_id]->item_markup_value;
					$data['items'][$key]->item_after_markup 		= $markup[$value->item_id]->item_after_markup;
				}
				else
				{
					$data['items'][$key]->item_markup_percentage 	= 0;
					$data['items'][$key]->item_markup_value 		= $value->item_price;
					$data['items'][$key]->item_after_markup 		= $value->item_price;
				}
			}
							 						 
		}
		else
		{
			$data['items'] 				= Tbl_item::where('shop_id', $shop_id)->whereIn('item_type_id', $item_type)->get();
			$data['selected_merchant'] 	= null;

		}

		$data['merchants'] 				= $this->list_all_merchnat();

		$data['item_count'] 			= Tbl_item::where('shop_id', $shop_id)->whereIn('item_type_id', $item_type)->count();

		foreach ($data['merchants'] as $key => $value) 
		{
			$data['merchant_count_item'][$key] = Tbl_merchant_markup::where('user_id', $value->user_id)->where('item_markup_percentage', '!=', 0)->count();
			$data['merchant_count_item_over'][$key] 				= Tbl_item::whereIn('item_type_id', $item_type)
										->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
	                					->where('item_merchant_requested_by', $value->user_id)
										->where('tbl_item.shop_id', $shop_id)
										->count();		
		}

		return view('member.merchant.markup', $data);
	}
	public function update()
	{
		$user_id 				= Request::input('user_id');
		$user_mark_up_default 	= Request::input('user_mark_up_default');
		$user_mark_up_lowest 	= Request::input('user_mark_up_lowest');

		$update['user_mark_up_default'] = $user_mark_up_default;
		$update['user_mark_up_lowest'] 	= $user_mark_up_lowest;

		if($user_mark_up_default < $user_mark_up_lowest)
		{
			$data['status'] 	= 'warning';
			$data['message'] 	= 'Default must be greater or equal to lowest markup';
			return json_encode($data);
		}

		Tbl_user::where('user_id', $user_id)->update($update);

		$data = $this->set_all_mark_up($user_id, $update);


		if(!isset($data['status']))
		{
			$data['status'] 	= 'success';
			$data['message'] 	= 'Markup Updated';
		}

		return json_encode($data);
	}

	public function set_all_mark_up($user_id, $markup)
	{
		if(isset($markup['user_mark_up_default']) && isset($markup['user_mark_up_lowest']))
		{
			$shop_id 	= $this->user_info->shop_id;
			$user = Tbl_user::where('user_id', $user_id)->first();

			$item_type[0] = 1;
			$item_type[1] = 2;

			$items 		= Tbl_item::where('shop_id', $shop_id)->whereIn('item_type_id', $item_type)
							->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
	                		->where('item_merchant_requested_by', $user_id)
							->get(); 

			foreach ($items as $key => $value)
			{
				$this->set_per_piece_mark_up($value, $user);
			}
		}
	}
	public function set_per_piece_mark_up($item, $user, $mark_up_percentage = null)
	{
		if($item && $user)
		{
			$shop_id 	= $this->user_info->shop_id;
			if($mark_up_percentage != null)
			{
				$user->user_mark_up_default = $mark_up_percentage;
			}

			$check_mark_up = Tbl_merchant_markup::where('item_id', $item->item_id)->where('user_id', $user->user_id)->count();
			if($check_mark_up >= 1)
			{
				$update['item_price'] 				= $item->item_price;
				$update['item_markup_percentage'] 	= $user->user_mark_up_default;
				$update['item_markup_value'] 		= ($user->user_mark_up_default/100) * $item->item_price;
				$update['item_after_markup'] 		= $item->item_price - $update['item_markup_value'];
				Tbl_merchant_markup::where('item_id', $item->item_id)->where('user_id', $user->user_id)->update($update);
			}
			else
			{
				$insert['user_id'] 					= $user->user_id;
				$insert['shop_id'] 					= $shop_id;
				$insert['item_id'] 					= $item->item_id;
				$insert['item_price'] 				= $item->item_price;
				$insert['item_markup_percentage'] 	= $user->user_mark_up_default;
				$insert['item_markup_value'] 		= ($user->user_mark_up_default/100) * $item->item_price;
				$insert['item_after_markup'] 		= $item->item_price - $insert['item_markup_value'];
				$insert['merchant_markup_date_created'] = Carbon::now();
				
				Tbl_merchant_markup::insert($insert);
			}
		}
	}
	public function update_per_piece()
	{
		$item_markup_percentage = Request::input('item_markup_percentage');
		$user_id 				= Request::input('user_id');
		if($item_markup_percentage)
		{
			foreach ($item_markup_percentage as $key => $value) 
			{
				$whereIn_item[$key] = $key;
			}

			$items 	= Tbl_item::whereIn('item_id', $whereIn_item)->get()->keyBy('item_id');
			$user 	= Tbl_user::where('user_id', $user_id)->first();
			foreach ($items as $key => $value) 
			{
				if(isset($item_markup_percentage[$key]))
				{
					$this->set_per_piece_mark_up($value, $user, $item_markup_percentage[$key]);		
				}
			}	
		}

		$data['status'] 	= 'success';
		$data['message'] 	= 'Markup Updated';
		return json_encode($data);
	}


	public function report()
	{

	}
	public function commission()
	{
		$user_is_merchant  = $this->user_info->user_is_merchant;
		if($user_is_merchant == 0)
		{
			return $this->commission_admin();
		}
		else
		{
			return $this->commission_merchant();
		}
	}
	public function commission_admin()
	{
		$data = [];
		$data['merchant'] = $this->list_all_merchnat();
		return view('member.merchant.commission.admin', $data);
	}
	public function commission_merchant()
	{
		$user_id = $this->user_info->user_id;
		$data = [];
		$data['payable'] 	= $this->commission_collectables($user_id, 'collectable', 'sum');
		$data['requested'] 	= $this->commission_collectables($user_id, 'requested', 'sum');
		$data['collected'] 	= $this->commission_collectables($user_id, 'collected', 'sum');
		$data['paid'] 		= $this->commission_collectables($user_id, 'paid', 'sum');
		$data['user_id'] 	= $this->user_info->user_id;
		return view('member.merchant.commission.merchant', $data);
	}
	public function list_all_merchnat()
	{
		$shop_id = $this->user_info->shop_id;
		$merchant = Tbl_user::where('user_shop', $shop_id)->where('user_is_merchant', 1)->get();

		foreach ($merchant as $key => $value) 
		{
			$merchant[$key]->collectable 			= $this->commission_collectables($value->user_id, 'collectable', 'sum');
			$merchant[$key]->collectable_requested 	= $this->commission_collectables($value->user_id, 'requested', 'sum');
			$merchant[$key]->collectable_paid		= $this->commission_collectables($value->user_id, 'paid', 'sum');	
			$merchant[$key]->collectable_approved 	= $this->commission_collectables($value->user_id, 'collected', 'sum');
													
		}

		return $merchant;
	}
	public function commission_user($user_id, $commission_o = 'collectable')
	{
		$commission = Request::input('commission');
		if(!$commission)
		{
			$commission = $commission_o;	
		}

		$merchant_commission_id  = Request::input('merchant_commission_id');
		if($user_id && $commission)
		{
			$headers['collectable'] = 'Collectable Commission';
			$headers['requested'] 	= 'Requested Commission';
			$headers['collected'] 	=  'Collected Commission';	 
			$headers['paid'] 		=  'Paid (Need to verify) Commission';	

			$stat['requested'] 	= 'Requested';
			$stat['collected'] 	= 'Approved';
			$stat['paid'] 		= 'Paid';

			$data['header'] 			= $headers[$commission];
			$data['request_commission'] = $commission;
			$data['current_user'] 		= $this->user_info->user_id;
			$data['commission'] 		= $commission;
			if(isset($stat[$commission]) && !$merchant_commission_id)
			{
				$data['merchant_commission'] = Tbl_merchant_commission::where('merchant_commission_user_send_request', $user_id)->where('merchant_commission_status', $stat[$commission])->get();
				return view('member.merchant.user.request', $data);									
			}

			
			$data['back'] 			= $merchant_commission_id;
			$data['back_request'] 	= $commission;
			$data['user_id'] 		= $user_id;
			$data['commission'] 	= $this->commission_collectables($user_id, $commission);
			return view('member.merchant.user.collectibles', $data);
		}
		else
		{
			return 'Invalid Request';
		}
	}
	public function commission_user_request_update($user_id)
	{
		$data = [];
		$data['commission'] = Request::input('commission');
			
		if($data['commission'] == 'requested')
		{

			$data['user_id'] 				= $user_id;
			$data['back'] 					= $user_id;
			$data['merchant_commission_id'] = Request::input('merchant_commission_id');
			$data['sum'] 					= $this->commission_collectables($user_id, 'requested', $get ='sum');	
			return view('member.merchant.user.request_update_request', $data);
		}
		elseif($data['commission'] =='paid')
		{
			$data['user_id'] 				= $user_id;
			$data['back'] 					= $user_id;
			$data['merchant_commission_id'] = Request::input('merchant_commission_id');
			$data['commission_data'] 		= Tbl_merchant_commission::where('merchant_commission_id', $data['merchant_commission_id'])->first();
			$data['sum'] 					= $this->commission_collectables($user_id, 'paid', $get ='sum');	
			return view('member.merchant.user.request_update_paid', $data);
		}
	}
	public function commission_user_request_update_submit(Request2 $request) 
	{ 
		$proof 	= null;
		$update = [];
		if($request->hasFile('merchant_commission_request_proof'))
		{ 
			$avatar 			= $request->file('merchant_commission_request_proof'); 
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

		$merchant_commission_id = Request::input('merchant_commission_id');
		
		if(Request::input('merchant_commission_status')) 
		{ 
			$update['merchant_commission_status'] = Request::input('merchant_commission_status'); 
			if($update['merchant_commission_status'] == 'Denied')
			{
				$update['merchant_commission_deny_date'] = Carbon::now();

				$update_invoice['merchant_commission_id'] = 0;

				Tbl_item_code_invoice::where('merchant_commission_id', $merchant_commission_id)->update($update_invoice);
			} 
			elseif($update['merchant_commission_status'] == 'Approved')
			{
				$update['merchant_commission_approve_date'] = Carbon::now();
			}
		}
		if(Request::input('merchant_commission_request_remarks')){ $update['merchant_commission_request_remarks'] = Request::input('merchant_commission_request_remarks'); }
		if($proof){ $update['merchant_commission_request_proof'] = $proof; }
		if(Request::input('merchant_commission_remarks')) { $update['merchant_commission_remarks'] = Request::input('merchant_commission_remarks'); }
		
		
		Tbl_merchant_commission::where('merchant_commission_id', $merchant_commission_id)->update($update);

		return Redirect::back();
	}
	public function commission_request()
	{
		$user_id = Request::input('user_id');

		$data['user'] = Tbl_user::where('user_id', $user_id)->first();

		return view('member.merchant.commission.request', $data);
	}
	public function commission_range_verify()
	{
		$user_id = Request::input('user_id');

		$data['user'] = Tbl_user::where('user_id', $user_id)->first();

		$data['collectable'] =  $this->commission_collectables($user_id, 'collectable');
		$data['item_subtotal'] = 0;
		$data['item_discount'] = 0;
		$data['merchant_markup_value'] = 0;
		foreach($data['collectable'] as $key => $value)
		{
			$data['item_subtotal'] += $value->item_subtotal;
			$data['item_discount'] += $value->item_discount;
			$data['merchant_markup_value'] += $value->merchant_markup_value;
		}

		$data['request_from']	= Request::input('request_from');
		$data['request_to']	= Request::input('request_to');

		return view('member.merchant.user.sum', $data);

	}
	public function commission_request_submit()
	{
		$user_id = Request::input('user_id');

		$user = Tbl_user::where('user_id', $user_id)->first();
		if(!$user){ $r['status'] = 'warning'; $r['message'] = 'User does not exists'; return json_encode($r); }

		$commission_collectables = $this->commission_collectables($user_id, 'collectable');
		if(count($commission_collectables) <= 0){ $r['status'] = 'warning'; $r['message'] = 'Data does not exist'; return json_encode($r); }

		$merchant_commission_amount = 0;
		$commission_ids 			= [];
		foreach($commission_collectables as $key => $value)
		{
			$commission_ids[$value->item_code_invoice_id] = $value->item_code_invoice_id;
			$merchant_commission_amount 			     += $value->merchant_markup_value;
		}

		$date['request_from'] 	= Request::input('request_from');
		$date['request_to'] 	= Request::input('request_to');

		$insert['merchant_commission_user_request'] 		= $this->user_info->user_id;
		$insert['merchant_commission_user_send_request'] 	= $user_id;
		$insert['merchant_commission_amount'] 				= $merchant_commission_amount;
		$insert['merchant_commission_remarks'] 				= 'Request Date Range [' . $date['request_from']. ']-[' . $date['request_to'] . ']' ;
		$insert['merchant_commission_request_date'] 		= Carbon::now();
		$insert['merchant_commission_request_from'] 		= $date['request_from'];
		$insert['merchant_commission_request_to'] 			= $date['request_to'];

		$id = Tbl_merchant_commission::insertGetId($insert);


		$update['merchant_commission_id'] = $id;
		Tbl_item_code_invoice::whereIn('item_code_invoice_id', $commission_ids)->update($update);

		$r['status'] = 'success'; $r['message'] = 'Request Sent.'; return json_encode($r);
	}
	public function commission_collectables($user_id, $status ='collectable', $get ='get')
	{
		$item_code_payment_type[1] = 1; // cash
		$item_code_payment_type[2] = 2; // GC
		if($status == 'collectable')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)->where('merchant_markup_value', '!=', 0)->where('merchant_commission_id', 0)->whereIn('item_code_payment_type', $item_code_payment_type);

		}
		else if($status == 'requested')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
					->where('merchant_markup_value', '!=', 0)
					->where('tbl_item_code_invoice.merchant_commission_id', '!=',0)
					->join('tbl_merchant_commission', 'tbl_merchant_commission.merchant_commission_id', '=', 'tbl_item_code_invoice.merchant_commission_id')
					->where('merchant_commission_status', 'Requested')->whereIn('item_code_payment_type', $item_code_payment_type);
		}
		else if($status == 'collected')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
					->where('tbl_item_code_invoice.merchant_commission_id', '!=',0)
					->where('merchant_markup_value', '!=', 0)
					->join('tbl_merchant_commission', 'tbl_merchant_commission.merchant_commission_id', '=', 'tbl_item_code_invoice.merchant_commission_id')
					->where('merchant_commission_status', 'Approved')->whereIn('item_code_payment_type', $item_code_payment_type);
		}
		else if($status == 'paid')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
					->where('tbl_item_code_invoice.merchant_commission_id', '!=',0)
					->where('merchant_markup_value', '!=', 0)
					->join('tbl_merchant_commission', 'tbl_merchant_commission.merchant_commission_id', '=', 'tbl_item_code_invoice.merchant_commission_id')
					->where('merchant_commission_status', 'Paid')->whereIn('item_code_payment_type', $item_code_payment_type);
		}
		else if($status == 'all')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
					->where('tbl_item_code_invoice.merchant_commission_id', '!=',0)
					->where('merchant_markup_value', '!=', 0)
					->whereIn('item_code_payment_type', $item_code_payment_type)
					->join('tbl_merchant_commission', 'tbl_merchant_commission.merchant_commission_id', '=', 'tbl_item_code_invoice.merchant_commission_id');
		}

		$request_from	= Request::input('request_from');
		$request_to	= Request::input('request_to');

		if($request_to && $request_from)
		{ 
			$request_to = Carbon::parse($request_to)->endOfDay(); 
			$collectable = $collectable->where('item_code_date_created', '>=', $request_from)->where('item_code_date_created', '<=', $request_to);
		}

		$merchant_commission_id = Request::input('merchant_commission_id');
		if($merchant_commission_id != null)
		{
			$collectable = $collectable->where('tbl_merchant_commission.merchant_commission_id', $merchant_commission_id);
		}
		if($get == 'sum')
		{
			return $collectable = $collectable->sum('merchant_markup_value');	
		}
		return $collectable->get();														
	}
	public function commission_collectables_sum($user_id, $status ='collectable')
	{
		if($status == 'collectable')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
																->where('merchant_commission_id', 0)
																->sum('merchant_markup_value');											
		}
		else if($status == 'requested')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
					->where('tbl_item_code_invoice.merchant_commission_id', '!=',0)
					->join('tbl_merchant_commission', 'tbl_merchant_commission.merchant_commission_id', '=', 'tbl_item_code_invoice.merchant_commission_id')
					->where('merchant_commission_status', 'Requested')
					->sum('merchant_markup_value');
		}
		else if($status == 'collected')
		{
			$collectable = Tbl_item_code_invoice::where('user_id', $user_id)
					->where('tbl_item_code_invoice.merchant_commission_id', '!=',0)
					->join('tbl_merchant_commission', 'tbl_merchant_commission.merchant_commission_id', '=', 'tbl_item_code_invoice.merchant_commission_id')
					->where('merchant_commission_status', 'Approved')
					->sum('merchant_markup_value');	
		}
		


		return $collectable;														
	}

	public function commission_report()
	{
		$data['page'] = "Commission Report";
		$id = $this->current_warehouse->warehouse_id;
		$q = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$id)->first();
		if(count($q)>0)
		{
			$data['percentage'] = $q->merchant_commission_percentage;
		}
		else
		{
			$data['percentage'] = 0;
		}

		Tbl_warehouse_receiving_report::where('warehouse_id',$id)->get();


		return view('member.merchant.commission_report.commission_report',$data);
	}
	public function submit_report_setting()
	{
		$pass = request('password');
		if($pass == Crypt::decrypt($this->user_info->user_password))
		{
			$warehouse_id = $this->current_warehouse->warehouse_id;
			$data['merchant_commission_warehouse_id'] = $warehouse_id;
			$data['merchant_commission_warehouse_name'] = $this->current_warehouse->warehouse_name;
			$data['merchant_commission_shop_id'] = $this->current_warehouse->warehouse_shop_id;
			$data['merchant_commission_percentage'] = Request('percentage');

			$count = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$warehouse_id)->get();

			if(count($count)>0)
			{
				Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$warehouse_id)->update($data);
			}
			else
			{
				Tbl_merchant_commission_report_setting::insert($data);
			}

			$response['call_function']='success';
		}
		else
		{
			$response['call_function']='invalid_password';
		}
		
		return json_encode($response);

	}
	public function password()
	{
		$data['page'] = "Enter Password";
		return view('member.merchant.commission_report.commission_report_pass',$data);
	}
	public function get_percentage()
	{
		$id = request('warehouse_id');
		$q = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$id)->first();
		if(count($q)>0)
		{
			return $q->merchant_commission_percentage;
		}
		else
		{
			return 0;
		}
	}
	public function table()
	{
		$warehouse_id = $this->current_warehouse->warehouse_id;
		$data['page'] = 'Commission Report Table';
		$data['table'] = Tbl_warehouse_inventory_record_log::ReceivingReport()->where('tbl_warehouse_inventory_record_log.record_warehouse_id',$warehouse_id)->where('record_source_ref_name','rr')->where('item_in_use','used')->where('tbl_warehouse_inventory_record_log.commission_report',0)->paginate(10);
		$total = Tbl_warehouse_inventory_record_log::ReceivingReport()->where('tbl_warehouse_inventory_record_log.record_warehouse_id',$warehouse_id)->where('item_in_use','used')->where('tbl_warehouse_inventory_record_log.commission_report',0)->sum('item_price');
		
		$commission = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$warehouse_id);
		$q = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$warehouse_id)->first();
		if(count($q)>0)
		{
			$commission = $q->merchant_commission_percentage;
		}
		else
		{
			$commission = 0;
		}

		$data['totalcommission'] = $total*($commission/100);
		// dd($data['table']);
		// dd($warehouse_id);
		return view('member.merchant.commission_report.commission_report_table',$data);
	}
	public function export()
	{
		$warehouse_id = $this->current_warehouse->warehouse_id;
		
		$data['table'] = Tbl_warehouse_inventory_record_log::ReceivingReport()->where('tbl_warehouse_inventory_record_log.record_warehouse_id',$warehouse_id)->where('record_source_ref_name','rr')->where('item_in_use','used')->where('tbl_warehouse_inventory_record_log.commission_report',0)->get();
		$total = Tbl_warehouse_inventory_record_log::ReceivingReport()->where('tbl_warehouse_inventory_record_log.record_warehouse_id',$warehouse_id)->where('item_in_use','used')->where('tbl_warehouse_inventory_record_log.commission_report',0)->sum('item_price');
		
		$commission = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$warehouse_id);
		$q = Tbl_merchant_commission_report_setting::where('merchant_commission_warehouse_id',$warehouse_id)->first();
		if(count($q)>0)
		{
			$commission = $q->merchant_commission_percentage;
		}
		else
		{
			$commission = 0;
		}

		$data['totalcommission'] = $total*($commission/100);
		$data['warehouse_name'] = $this->current_warehouse->warehouse_name;

		Excel::create('Commission Report', function($excel) use ($data)
        {
            $excel->sheet('Commission', function($sheet) use ($data)
            {
                $sheet->loadView('member.merchant.commission_report.export', $data);
            });
        })
        ->export('xls');
	}
	public function import()
	{
		$data['page'] = 'Import Excel File';
		return view('member.merchant.commission_report.import',$data);
	}
	public function import_submit(Request $request)
	{
		
		if($request->hasFile('excel_file'))
		{
			$file = $request->file('excel_file');
			// dd($file);
			// ini_set('memory_limit', '-1');
			$data = array();
			$report = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('record_id'));
			// dd($report);
			$response = 'success';
			if(isset($report[0]['record_id']))
			{
				// foreach ($report as $r) 
				// {
				// 	if($r['pin'] != '' && $r['activation'] != '')
				// 	{
				// 		$update['commission_report'] = 1;
				// 		Tbl_warehouse_inventory_record_log::
				// 		where('mlm_pin',$r['pin'])
				// 			->where('mlm_activation',$r['activation'])
				// 			->update($update);
				// 	}
				// }

				$update['commission_report'] = 1;
				$query = Tbl_warehouse_inventory_record_log::where('record_log_id',$report[0]['record_id']);
				foreach($report as $r)
				{
					$query->orWhere('record_log_id',$r['record_id']);
				}
				$query->update($update);
			}
			else
			{
				$response = 'no_data';
			}
		}
		else
		{
			$response = 'error';
		}
		
		return Redirect::back()->with("response",$response);
		
	}

}	