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
use App\Models\Tbl_item_price_history;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_item_multiple_price;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_um;
use App\Models\Tbl_item_merchant_request;
use App\Models\Tbl_user;
use App\Models\Tbl_audit_trail;

use App\Globals\Category;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\DigimaTable;
use App\Globals\ItemSerial;
use App\Globals\Warehouse;
use App\Globals\Item;
use App\Globals\Vendor;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Utilities;
use App\Globals\Pdf_global;

use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;
use Input;
use Validator;
use Carbon\Carbon;
use App\Globals\Merchant;

class ItemController extends Member
{
	public function index()
	{	
        $access = Utilities::checkAccess('item-list', 'access_page');
        $data['can_approve_item_request'] = Utilities::checkAccess('item-list', 'can_approve_item_request');
		$data['can_edit_other_item'] = Utilities::checkAccess('item-list', 'can_edit_other_item');
		$data['user_id'] = $this->user_info->user_id;
		$data['is_merchant'] = $this->user_info->user_is_merchant;
        if($access == 1)
        {
			$shop_id        		   = $this->user_info->shop_id;
			$warehouse_id 			   = Tbl_warehouse::where("main_warehouse", 1)->where("warehouse_shop_id", $this->user_info->shop_id)->value("warehouse_id");
	        
			if($data['is_merchant'] == 1)
			{
				$item 		    		   = Tbl_item::inventory()
		        							->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
		        							->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')
		        							// ->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
		        							// ->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')  
		        							->where("tbl_item.archived",0)->where("shop_id",$shop_id)
		        							->where(function($query){
										         $query->where('item_merchant_request_status',"=","Approved");
											})
		        							->where('item_merchant_requested_by', $data['user_id'])
											->type()->category();

		        /* CHECK IF THE ITEM IS ON MERCHANT REQUESTED ITEM THEN IT WOULDN'T BE INCLUDED ON ARCHIVED ITEM WHEN IT IS STILL ON PENDING STATUS */
		        $item_archived  		   = Tbl_item::where("tbl_item.archived",1)
		        								     ->where("shop_id",$shop_id)
		        								     ->type()
		        								     ->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
		        								     ->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')
		        								     ->category()
		        								     ->where('item_merchant_requested_by', $data['user_id'])
												     ->where(function($query)
												     {
												         $query->where('item_merchant_request_status',"!=","PENDING");
												         $query->orWhereNull('item_merchant_request_status');
												     });

		        $item_pending  		       = Tbl_item::where("tbl_item.archived",1)
	        								         ->where("tbl_item.shop_id",$shop_id)
	        								         ->type()
	        								         ->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
	        								         ->leftJoin("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_item_merchant_request.merchant_warehouse_id")
	        								         ->leftJoin("tbl_customer","tbl_customer.customer_id","=","tbl_item_merchant_request.item_merchant_requested_by")
	        								         ->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')
	        								         ->category()
	        								         ->where('item_merchant_requested_by', $data['user_id'])
											         ->where(function($query)
											         {
											             $query->where('item_merchant_request_status',"PENDING");
											         });
			}
			else
			{
		        $item 		    		   = Tbl_item::inventory()
		        							->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
		        							->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')
		        							->where("tbl_item.archived",0)->where("shop_id",$shop_id)->type()->category();

		        /* CHECK IF THE ITEM IS ON MERCHANT REQUESTED ITEM THEN IT WOULDN'T BE INCLUDED ON ARCHIVED ITEM WHEN IT IS STILL ON PENDING STATUS */
		        $item_archived  		   = Tbl_item::where("tbl_item.archived",1)
		        								     ->where("shop_id",$shop_id)
		        								     ->type()
		        								     ->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
		        								     ->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')
		        								     ->category()
												     ->where(function($query)
												     {
												         $query->where('item_merchant_request_status',"!=","PENDING");
												         $query->orWhereNull('item_merchant_request_status');
												     });

		        $item_pending  		       = Tbl_item::where("tbl_item.archived",1)
	        								         ->where("tbl_item.shop_id",$shop_id)
	        								         ->type()
	        								         ->leftJoin("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
	        								         ->leftJoin("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_item_merchant_request.merchant_warehouse_id")
	        								         ->leftJoin("tbl_customer","tbl_customer.customer_id","=","tbl_item_merchant_request.item_merchant_requested_by")
	        								         ->leftjoin('tbl_user', 'tbl_user.user_id','=', 'tbl_item_merchant_request.item_merchant_requested_by')
	        								         ->category()
											         ->where(function($query)
											         {
											             $query->where('item_merchant_request_status',"PENDING");
											         });

			}							     
	        $item_type				   = Request::input("item_type");
	        $search_name			   = Request::input("search_name");

	        $user_is_merchant = $this->user_info->user_is_merchant;
	       	if($user_is_merchant == 1)
	       	{
	       		
	       	}
	       	
	        if($item_type != null && $item_type != "All")
	        {
	    		$item		   = $item->where("tbl_item.item_type_id",$item_type);
	    		$item_archived = $item_archived->where("tbl_item.item_type_id",$item_type);
	        } 
	        
	        if($search_name != "")
	        {
	    		$item		   = $item->where("item_name","LIKE","%".$search_name."%");
	    		$item_archived = $item_archived->where("item_name","LIKE","%".$search_name."%");
	        }

	        $column_name = Request::input("column_name");
	        $in_order = Request::input("in_order");
	        if($column_name && $in_order)
	        {
	        	$item = $item->orderBy($column_name,$in_order);
	        }
	        
			$data["_item"]			   = $item->paginate(30);

			foreach ($data["_item"] as $key => $value) 
			{
				$data["_item"][$key]->item_price_history = Item::get_item_price_history($value->item_id);

				if($value->bundle_group == 1)
				{
					$data["_item"][$key]->item_type_name = "Group";
				}
				if($value->item_type_id == 1)
				{
					$data["_item"][$key]->inventory_count_um_view = "";
					$data["_item"][$key]->item_whole_price = 0;
					$data["_item"][$key]->um_whole = "";
					$data["_item"][$key]->inventory_count_um = UnitMeasurement::um_convert($value->inventory_count, $value->item_measurement_id);
					// dd($data["_item"][$key]->inventory_count_um);

					$um = Tbl_unit_measurement_multi::where("multi_um_id",$value->item_measurement_id)->where("is_base",0)->first();
					if($um)
					{
						$data["_item"][$key]->inventory_count_um_view = UnitMeasurement::um_view($value->inventory_count,$value->item_measurement_id,$um->multi_id);
						
						$data["_item"][$key]->item_whole_price = $um->unit_qty * $value->item_price;
						$data["_item"][$key]->um_whole = $um->multi_abbrev;
					}
				}
				$um_base = Tbl_unit_measurement_multi::where("multi_um_id",$value->item_measurement_id)->where("is_base",1)->first();
				if($um_base)
				{
					$data["_item"][$key]->multi_abbrev = $um_base->multi_abbrev;
				}
				$data["_item"][$key]->conversion = UnitMeasurement::um_convertion($value->item_id);
				if($value->item_type_id == 4)
				{
					$data["_item"][$key]->item_price = Item::get_item_bundle_price($value->item_id);
				}
			}
			$data["_item_archived"]	   = $item_archived->paginate(30);
			$data["_item_pending"]	   = $item_pending->get();

			$data['pis'] = Purchasing_inventory_system::check();

		    return view('member.item.list',$data);
        }
        else
        {
            return $this->show_no_access();
        }
	}
	public function view_item_receipt($item_id)
	{
		$data["invoice"] = Item::view_item_receipt($item_id);
		// dd($data);
		return view("member.item.pis.item_view_receipt",$data);
	}
	public function view_item_history($item_id)
	{

		$data["text"] = Item::get_item_price_history($item_id, true);

		return view("member.item.pis.item_price_history",$data);
	}
	public function delete_item_history()
	{
		$history_id = Request::input("history_id");
		Tbl_item_price_history::where("item_price_history_id",$history_id)->delete();
		echo json_encode('success');

	}
	public function load_item()
    {
        $data["_item"]  = Item::get_all_item();
        return view('member.load_ajax_data.load_item', $data);
    }
    public function load_all_um()
    {    	
		$data["_um"] 		 = UnitMeasurement::load_um();

	    return view('member.load_ajax_data.load_unit_measurement',$data);
    }
    public function load_item_category()
    {
        $data['_item']			= Item::get_all_category_item();
        $data['add_search']		= "";
        return view('member.load_ajax_data.load_item_category', $data);
    }

	public function data()
	{
		$data["table_name"] = "tbl_item";
		$data["column"]		= Request::input("column");

		$data["status"]     = "success";

		return json_encode($data);
	}

	public function add()
	{   
        $access = Utilities::checkAccess('item-list', 'access_page');
        if($access == 1)
        {

			$shop_id          = $this->user_info->shop_id;
			$data["data"]	  = Session::get("item_temporary_data");

			$data['_service']  		    = Category::getAllCategory(['services']);
			$data['_inventory']  		= Category::getAllCategory(['inventory']);
			$data['_noninventory']  	= Category::getAllCategory(['non-inventory']);
			$data['_bundle']        	= Category::getAllCategory(['bundles']);

			
			$data["_income"] 		= Accounting::getAllAccount('all',null,['Income','Other Income']);
			$data["default_income"] = Tbl_chart_of_account::where("account_code", "accounting-sales")
									->where("account_shop_id", $shop_id)->value("account_id");
			$data["_asset"] 		= Accounting::getAllAccount('all', null, ['Other Current Asset','Fixed Asset','Other Asset']);
			$data["default_asset"] 	= Tbl_chart_of_account::where("account_code", "accounting-inventory-asset")
									->where("account_shop_id", $shop_id)->value("account_id");
			$data["_expense"] 		= Accounting::getAllAccount('all',null,['Expense','Other Expense','Cost of Goods Sold']);
			$data["default_expense"] = Tbl_chart_of_account::where("account_code", "accounting-expense")
									->where("account_shop_id", $shop_id)->value("account_id");

			$data['_item']  			= Item::get_all_category_item();
			$data['_item_to_bundle']	= Item::get_all_category_item([1,2,3]);
			$data["_manufacturer"]    	= Tbl_manufacturer::where("manufacturer_shop_id",$shop_id)->get();
			$data["_um"] 				= UnitMeasurement::load_um();
			$data["_um_multi"]  		= UnitMeasurement::load_um_multi();
            $data["_vendor"]    		= Vendor::getAllVendor('active');


        	//check if_for PIS
        	$pis_check = Purchasing_inventory_system::check();
        	if($pis_check != 0)
        	{
        		$data['_um_n'] = Tbl_um::where("um_shop_id",$shop_id)->where("is_based",0)->get();
        		$data['_um_b'] = Tbl_um::where("um_shop_id",$shop_id)->where("is_based",1)->get();

        		$data['action'] = "/member/item/add_submit_pis";
		    	return view('member.item.pis.add_item',$data);        		
        	}
        	else
        	{
		    	return view('member.item.add',$data);
        	}
        }
        else
        {
            return $this->show_no_access();
        }
	}
	public function add_submit_pis()
	{
		$item_type = Request::input("item_type");
		$shop_id = $this->user_info->shop_id;

		$data['status'] = '';

		$item_name	 					= Request::input("item_name");
		$item_sku						= Request::input("item_sku");
		$item_category_id				= Request::input("item_category_id");
		$item_img 						= Request::input("item_img");
		$item_sales_information 		= Request::input("item_sales_information");
		$item_date_tracked 				= date("Y-m-d g:i:s",strtotime(Request::input("item_date_tracked")));
		// $item_purchasing_information 	= Request::input("item_purchasing_information");
		$item_barcode 					= Request::input("item_barcode");
		$item_manufacturer_id 			= Request::input("item_manufacturer_id");
		$packing_size 					= Request::input("packing_size");

		$item_sale_to_customer 			= Request::input("item_sale_to_customer") ? 1 : 0; 
		$item_purchase_from_supplier    = Request::input("item_purchase_from_supplier") ? 1 : 0;

		$unit_n_based 					= Request::input("unit_n_based");
		$unit_based 					= Request::input("unit_based");
		$qty 					        = Request::input("quantity") <= 0 ? 1 : Request::input("quantity");

		$item_price 					= str_replace(',','',Request::input("item_price"));
		$item_cost 						= str_replace(',','',Request::input("item_cost"));
		$item_reorder_point 			= Request::input("item_reorder_point");
		$item_quantity 					= Request::input("item_quantity");

		$promo_price 					= Request::input("promo_price");
		$start_promo_date 				= Request::input("start_promo_date");
		$end_promo_date 				= Request::input("end_promo_date");

		// if($item_type != "bundle")
		// {
		$unit_id = UnitMeasurement::create_um($unit_n_based, $unit_based, $qty);
		$item_measurement_id 			= $unit_id;			
		// }
			
		//Accounting part //DEFAULT VALUE
		$item_expense_account_id= Tbl_chart_of_account::where("account_code", "accounting-expense")->where("account_shop_id", $shop_id)->value("account_id");
		$item_income_account_id = Tbl_chart_of_account::where("account_code", "accounting-sales")->where("account_shop_id", $shop_id)->value("account_id");
		$item_asset_account_id 	= Tbl_chart_of_account::where("account_code", "accounting-inventory-asset")->where("account_shop_id", $shop_id)->value("account_id");

		$insert["item_date_created"]	    	  = Carbon::now();
		$insert["shop_id"]	    				  = $shop_id;

		$item_id = 0;
		if($item_type == "inventory")
		{			
			$insert["item_type_id"]				    = 1; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$insert["item_name"]	    	  		= $item_name;
			$insert["item_sku"]	    				= $item_sku;
			$insert["item_category_id"]	    	 	= $item_category_id;
			$insert["item_img"]	    				= $item_img;
			$insert["item_sales_information"]	    = $item_sales_information;
			$insert["item_date_tracked"]	    	= $item_date_tracked;
			$insert["item_barcode"]	    	  		= $item_barcode;
			$insert["item_manufacturer_id"]	    	= $item_manufacturer_id;
			$insert["packing_size"]	    	  		= $packing_size;
			$insert["item_sale_to_customer"]	    = $item_sale_to_customer;
			$insert["item_purchase_from_supplier"]	= $item_purchase_from_supplier;

			$insert["item_expense_account_id"]	    = $item_expense_account_id;
			$insert["item_income_account_id"]		= $item_income_account_id;
			$insert["item_asset_account_id"]		= $item_asset_account_id;

			$insert["item_price"]	    			= $item_price / $qty;
			$insert["item_cost"]	    	  		= $item_cost / $qty;
			$insert["item_reorder_point"]	 	    = $item_reorder_point * $qty;
			$insert["item_quantity"]				= $item_quantity * $qty;

			$insert["item_measurement_id"]			= $item_measurement_id;

			$rules["item_name"]	    	  			= 'required';
			$rules["item_sku"]	    				= 'required';
			$rules["item_category_id"]	    	 	= 'required';
			$rules["item_barcode"]	    	  		= 'required';
			$rules["item_price"]	    			= 'required';
			$rules["item_cost"]	    	  			= 'required';

			$validator = Validator::make($insert, $rules);

			$data["status_message"] = '';
			$data["status"] = '' ;
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}

			if($data['status'] == null)
			{
				$item_id = Tbl_item::insertGetId($insert);

				$warehouse = Tbl_warehouse::where("warehouse_id",$this->current_warehouse->warehouse_id)->first();

				Warehouse::insert_item_to_warehouse($warehouse, $item_id, $item_quantity * $qty, $item_reorder_point * $qty);
				Warehouse::insert_item_to_all_warehouse($item_id, $item_reorder_point * $qty);


                $data['status'] 	= 'success';
                $data['message'] 	= 'Success';
                $data["type"]		= "item";
	            $data['item_id'] 	= $item_id;
			}
		}
		elseif($item_type == "noninventory")
		{
			$insert["item_type_id"]				      = 2; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$insert["item_name"]	    	  		= $item_name;
			$insert["item_sku"]	    				= $item_sku;
			$insert["item_category_id"]	    	 	= $item_category_id;
			$insert["item_img"]	    				= $item_img;
			$insert["item_sales_information"]	    = $item_sales_information;
			$insert["item_date_tracked"]	    	= $item_date_tracked;
			$insert["item_sale_to_customer"]	    = $item_sale_to_customer;
			$insert["item_purchase_from_supplier"]	= $item_purchase_from_supplier;

			$insert["item_expense_account_id"]	    = $item_expense_account_id;
			$insert["item_income_account_id"]		= $item_income_account_id;

			$insert["item_price"]	    			= $item_price / $qty;
			$insert["item_cost"]	    	  		= $item_cost / $qty;
			$insert["item_reorder_point"]	 	    = $item_reorder_point * $qty;
			$insert["item_quantity"]				= $item_quantity * $qty;

			$insert["item_measurement_id"]			= $item_measurement_id;

			$rules["item_name"]	    	  			= 'required';
			$rules["item_sku"]	    				= 'required';
			$rules["item_category_id"]	    	 	= 'required';
			$rules["item_price"]	    			= 'required';

			$validator = Validator::make($insert, $rules);

			$data["status_message"] = '';
			$data["status"] = '' ;
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}

			if($data['status'] == null)
			{
				$item_id = Tbl_item::insertGetId($insert);

                $data['status'] 	= 'success';
                $data['message'] 	= 'Success';
                $data["type"]		= "item";
	            $data['item_id'] 	= $item_id;
			}

		}
		elseif($item_type == "service")
		{
			$insert["item_type_id"]				      = 3; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$insert["item_name"]	    	  		= $item_name;
			$insert["item_sku"]	    				= $item_sku;
			$insert["item_category_id"]	    	 	= $item_category_id;
			$insert["item_img"]	    				= $item_img;
			$insert["item_sales_information"]	    = $item_sales_information;
			$insert["item_date_tracked"]	    	= $item_date_tracked;
			$insert["item_sale_to_customer"]	    = $item_sale_to_customer;
			$insert["item_purchase_from_supplier"]	= $item_purchase_from_supplier;
			
			$insert["item_expense_account_id"]	    = $item_expense_account_id;
			$insert["item_income_account_id"]		= $item_income_account_id;

			$insert["item_price"]	    			= $item_price / $qty;
			$insert["item_cost"]	    	  		= $item_cost / $qty;
			$insert["item_reorder_point"]	 	    = $item_reorder_point * $qty;
			$insert["item_quantity"]				= $item_quantity * $qty;

			$insert["item_measurement_id"]			= $item_measurement_id;

			$rules["item_name"]	    	  			= 'required';
			$rules["item_sku"]	    				= 'required';
			$rules["item_category_id"]	    	 	= 'required';
			$rules["item_price"]	    			= 'required';

			$validator = Validator::make($insert, $rules);

			$data["status_message"] = '';
			$data["status"] = '' ;
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}

			if($data['status'] == null)
			{
				$item_id = Tbl_item::insertGetId($insert);

                $data['status'] 	= 'success';
                $data['message'] 	= 'Success';
                $data["type"]		= "item";
	            $data['item_id'] 	= $item_id;
			}
		
		}
		elseif($item_type == "bundle")
		{
			$insert["item_type_id"]				      = 4; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)
			
			if(Request::input("auto_generate_code") == "generate" || $item_barcode == "")
			{
				$num = str_shuffle("1234567890");
				$item_barcode = Item::generate_barcode($num);
			}

			$insert["item_name"]				= $item_name;
			$insert["item_sku"]					= $item_sku;
			$insert["item_category_id"]			= $item_category_id;
			$insert["item_img"]					= $item_img;
			$insert["item_sales_information"] 	= $item_sales_information;

			$insert["item_barcode"]			 	= $item_barcode;
			$insert["item_measurement_id"] 		= $item_measurement_id;

			$rules["item_name"]					= "required";
			$rules["item_sku"] 					= "required";

			$_item 	= Request::input('bundle_item_id');
			$_um 	= Request::input('bundle_um_id');
			$_qty 	= Request::input('bundle_qty');

			$item_id = "";
			$message   = [];
			$validator = Validator::make($insert, $rules, $message);
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}
			else
			{
				$item_id 			= Tbl_item::insertGetId($insert);

				$insert_item_discount["item_id"] = $item_id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				foreach($_item as $key=>$item)
				{
					if($item != '')
					{
						$insert_bundle["bundle_bundle_id"] 	= $item_id;
						$insert_bundle["bundle_item_id"] 	= $item;
						$insert_bundle["bundle_um_id"]		= isset($_um[$key]) ? $_um[$key] : 0;
						$insert_bundle["bundle_qty"]		= $_qty[$key];
						Tbl_item_bundle::insert($insert_bundle);
					}
				}
				
				$data["item_id"] 	= $item_id;
				$data["message"] 	= "Success";
				$data["type"]		= "item";

				$bundle_price = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
				$price = 0;
				foreach ($bundle_price as $key => $value) 
				{
					$item_price = Tbl_item::where("item_id",$value->bundle_item_id)->value("item_price");
                	$um = Tbl_unit_measurement_multi::where("multi_id",$value->bundle_um_id)->first();

					$qt = 1;
	                if($um != null)
	                {
	                    $qt = $um->unit_qty;
	                }
                	$issued_qty = $value->bundle_qty * $qt;

                	$price += $issued_qty * $item_price;
				}

				$insert["item_price"] = $price;

			}
		}
		elseif($item_type == "group")
		{
			$insert["item_type_id"]				      = 4; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)
			
			if(Request::input("auto_generate_code") == "generate" || $item_barcode == "")
			{
				$num = str_shuffle("1234567890");
				$item_barcode = Item::generate_barcode($num);
			}

			$insert["item_name"]				= $item_name;
			$insert["item_sku"]					= $item_sku;
			$insert["item_category_id"]			= $item_category_id;
			$insert["item_img"]					= $item_img;
			$insert["item_sales_information"] 	= $item_sales_information;
			$insert["bundle_group"]  			= 1;

			$insert["item_barcode"]			 	= $item_barcode;
			$insert["item_measurement_id"] 		= $item_measurement_id;

			$rules["item_name"]					= "required";
			$rules["item_sku"] 					= "required";

			$_item 	= Request::input('bundle_item_id');
			$_um 	= Request::input('bundle_um_id');
			$_qty 	= Request::input('bundle_qty');

			$item_id = "";
			$message   = [];
			$validator = Validator::make($insert, $rules, $message);
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}
			else
			{
				$item_id 			= Tbl_item::insertGetId($insert);

				foreach($_item as $key=>$item)
				{
					if($item != '')
					{
						$insert_bundle["bundle_bundle_id"] 	= $item_id;
						$insert_bundle["bundle_item_id"] 	= $item;
						$insert_bundle["bundle_um_id"]		= isset($_um[$key]) ? $_um[$key] : 0;
						$insert_bundle["bundle_qty"]		= $_qty[$key];
						Tbl_item_bundle::insert($insert_bundle);
					}
				}
				
				$data["item_id"] 	= $item_id;
				$data["message"] 	= "Success";
				$data["type"]		= "item";

				$bundle_price = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
				$price = 0;
				foreach ($bundle_price as $key => $value) 
				{
					$item_price = Tbl_item::where("item_id",$value->bundle_item_id)->value("item_price");
                	$um = Tbl_unit_measurement_multi::where("multi_id",$value->bundle_um_id)->first();

					$qt = 1;
	                if($um != null)
	                {
	                    $qt = $um->unit_qty;
	                }
                	$issued_qty = $value->bundle_qty * $qt;

                	$price += $issued_qty * $item_price;
				}

				$insert["item_price"] = $price;

			}
		}
		if($data['status'] == 'success')
		{
			$insert['item_id'] = $item_id;
	        AuditTrail::record_logs("Added","item",$item_id,"",serialize($insert));
		}
		return json_encode($data);
	}

	public function add_submit()
	{	  
		$price = "";

		$item_name	 					= Request::input("item_name");
		$item_sku						= Request::input("item_sku");
		$item_category_id				= Request::input("item_category_id");
		$item_img 						= Request::input("item_img");
		$item_price 					= Request::input("item_price");
		$item_sales_information 		= Request::input("item_sales_information");
		$item_asset_account_id 			= Request::input("item_asset_account_id");
		$item_quantity 					= Request::input("item_quantity") * (Request::input("initial_qty") != 0 ? Request::input("initial_qty") : 1 );
		$item_date_tracked 				= date("Y-m-d g:i:s",strtotime(Request::input("item_date_tracked")));
		$item_reorder_point 			= Request::input("item_reorder_point");
		$item_income_account_id 		= Request::input("item_income_account_id");
		$item_purchasing_information 	= Request::input("item_purchasing_information");
		$item_cost 						= Request::input("item_cost");
		$item_barcode 					= Request::input("item_barcode");
		$item_expense_account_id 		= Request::input("item_expense_account_id");
		$item_measurement_id 			= Request::input("item_measurement_id");
		$item_manufacturer_id 			= Request::input("item_manufacturer_id");
		$packing_size 					= Request::input("packing_size");

		$item_sale_to_customer 			= Request::input("item_sale_to_customer") ? 1 : 0; 
		$item_purchase_from_supplier    = Request::input("item_purchase_from_supplier") ? 1 : 0;


		$promo_price 			= Request::input("promo_price");
		$start_promo_date 		= Request::input("start_promo_date");
		$end_promo_date 		= Request::input("end_promo_date");

		$shop_id = $this->user_info->shop_id;
			
			$insert["item_date_created"]	    	  = Carbon::now();
			$insert["shop_id"]	    				  = $shop_id;

		$item_id = 0;
		if(Session::get("um_id") != null)
		{
			$item_measurement_id = Session::get("um_id");
		}
		if(Request::input("item_type") == "inventory")
		{
			$insert["item_type_id"]				      = 1; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$insert["item_name"]				      = $item_name ;
			$insert["item_sku"]					      = $item_sku ;
			$insert["item_category_id"]			      = $item_category_id ;
			$insert["item_img"]					      = $item_img ;
			$insert["item_price"] 				      = $item_price ;
			$insert["item_sales_information"] 	      = $item_sales_information ;
			$insert["item_asset_account_id"]          = $item_asset_account_id ;
			$insert["item_quantity"]		          = $item_quantity ;
			$insert["item_date_tracked"]	          = $item_date_tracked ;
			$insert["item_reorder_point"] 		      = $item_reorder_point ;
			$insert["item_income_account_id"] 	      = $item_income_account_id ;
			$insert["item_purchasing_information"]    = $item_purchasing_information ;
			$insert["item_cost"]				      = $item_cost ;
			$insert["item_expense_account_id"]	      = $item_expense_account_id ;
			$insert["item_barcode"]				      = $item_barcode ;
			$insert["item_measurement_id"]	      	  = $item_measurement_id ;
			$insert["item_manufacturer_id"]	      	  = $item_manufacturer_id ;
			$insert["packing_size"]				      = $packing_size;

			$rules["item_name"]					      = 'required';
			// $rules["item_barcode"]					  = 'required';
			// $rules["item_category_id"]			  = '';
			// $rules["item_img"]					  = '';
			// $rules["item_type_id"]				  = '';
			$rules["item_price"]				      = 'required|numeric';
			$rules["item_sales_information"]	      = '';
			$rules["item_asset_account_id"]		      = '';
			$rules["item_quantity"]				      = 'required|numeric';
			$rules["item_date_tracked"]			      = 'date';
			// $rules["item_reorder_point"]		      = 'required|numeric';
			// $rules["item_income_account_id"]	      = '';
			$rules["item_purchasing_information"]     = '';
			$rules["item_cost"]					      = 'required|numeric';
			// $rules["item_expense_account_id"]	  = '';
			$rules["item_date_tracked"] 			  = 'required|date';
			
			$message                            	  = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails()) //fail to add product
			{
				$return["error"]  	= $validator->errors()->all();
				$return["message"] 	= "Failed";
			}
			else
			{
				$item_id = Tbl_item::insertGetId($insert);

				$insert_item_discount["item_id"] = $item_id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;			

				Item::insert_item_discount($insert_item_discount);

				UnitMeasurement::update_um(Session::get("um_id"),$item_name,$item_id);

				$warehouse = Tbl_warehouse::where("warehouse_id",$this->current_warehouse->warehouse_id)->first();

				$inventory_id = Warehouse::insert_item_to_warehouse($warehouse, $item_id, $item_quantity, $item_reorder_point);
				Warehouse::insert_item_to_all_warehouse($item_id, $item_reorder_point);

				$for_serial_item[$item_id]["quantity"] = $item_quantity;
                $for_serial_item[$item_id]["product_id"] = $item_id;
                $for_serial_item[$item_id]["inventory_id"] = $inventory_id;

                //
                $items["item_id"] = $item_id;
                $items["item_list"] = $for_serial_item;

				$serial = Tbl_settings::where("settings_key","item_serial")->where("settings_value","enable")->where("shop_id",$shop_id)->first();

				if($item_quantity == 0)
				{
					$serial = null;
				}
	            if($serial != null)
	            {
	                $return['status'] 	= 'success-serial';
	                $return["message"] 	= "";
	                // $return["type"]	= "item";
	                Session::put("item", $items);
	            }
	            else
	            {
	                $return['status'] 	= 'success';
	                $return['message'] 	= 'Success';
	                $return["type"]		= "item";
	            }

	            $return['item_id'] = $item_id;
	            // $return["type"]		= "item";
			}
		}
		else if(Request::input("item_type") == "noninventory")
		{
			$insert["item_name"]				      = $item_name;
			$insert["item_sku"]					      = $item_sku;
			$insert["item_category_id"]			      = $item_category_id;
			$insert["item_img"]					      = $item_img;
			$insert["item_type_id"]				      = 2; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)
			$insert["item_price"] 				      = $item_price;
			$insert["item_sales_information"] 	      = $item_sales_information;
			$insert["item_asset_account_id"]          = $item_asset_account_id;
			$insert["item_sale_to_customer"]		  = $item_sale_to_customer;
			$insert["item_purchase_from_supplier"]	  = $item_purchase_from_supplier;
			$insert["item_date_tracked"]	    	  = $item_date_tracked;
			$insert["item_measurement_id"]	      	  = $item_measurement_id;
			$insert["item_income_account_id"] 		  = Request::input("item_income_account_id");
			$insert["item_purchasing_information"]    = $item_purchasing_information ;
			$insert["item_cost"]				      = $item_cost ;
			$insert["item_expense_account_id"]	      = $item_expense_account_id ;
			
			$rules["item_name"]					      = 'required';
			$rules["item_price"]				      = 'required|numeric';
			$rules["item_sales_information"]	      = '';
			$rules["item_asset_account_id"]		      = '';
			$rules["item_sale_to_customer"]		  = 'required|numeric|min:0|max:1';
			$rules["item_purchase_from_supplier"]  = 'required|numeric|min:0|max:1';
			

			
			$message                            	  = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails()) //fail to add item
			{
				$return["error"]  = $validator->errors()->all();
				$return["message"] = "Failed";
				$return["type"]		= "item";
			}
			else
			{
				$item_id = Tbl_item::insertGetId($insert);

				$insert_item_discount["item_id"] = $item_id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);
	

				$return["item_id"] = $item_id;
				$return["message"] = "Success";
				$return["type"]		= "item";
			}
		}
		else if(Request::input("item_type") == "service")
		{
			$insert["item_name"]				      = $item_name;
			$insert["item_sku"]					      = $item_sku;
			$insert["item_category_id"]			      = $item_category_id;
			$insert["item_img"]					      = $item_img;
			$insert["item_type_id"]				      = 3; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)
			$insert["item_price"] 				      = $item_price;
			$insert["item_sales_information"] 	      = $item_sales_information;
			$insert["item_asset_account_id"]          = $item_asset_account_id;
			$insert["item_sale_to_customer"]		  = $item_sale_to_customer;
			$insert["item_purchase_from_supplier"]	  = $item_purchase_from_supplier;
			$insert["item_measurement_id"]	      	  = $item_measurement_id;
			$insert["item_income_account_id"] 		  = Request::input("item_income_account_id");
			$insert["item_purchasing_information"]    = $item_purchasing_information ;
			$insert["item_cost"]				      = $item_cost ;
			$insert["item_expense_account_id"]	      = $item_expense_account_id ;
			
			$rules["item_name"]					      = 'required';
			$rules["item_sku"]					      = 'required';
			$rules["item_price"]				      = 'required|numeric';
			$rules["item_sales_information"]	      = '';
			$rules["item_asset_account_id"]		      = '';
			$rules["item_sale_to_customer"]		      = 'required|numeric|min:0|max:1';
			$rules["item_purchase_from_supplier"]     = 'required|numeric|min:0|max:1';

			
			$message                            	  = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails()) //fail to add item
			{
				$return["response_error"] = "error";
				$return["error"]  	= $validator->errors()->all();
				$return["message"] 	= "Failed";
				$return["item_id"] 	= null;
				$return["type"]		= "item";
			}
			else
			{
				$item_id = Tbl_item::insertGetId($insert);

				$insert_item_discount["item_id"] = $item_id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				$return["item_id"] = $item_id;
				$return["message"] = "Success";
				$return["type"]		= "item";
			}
		}
		else if(Request::input("item_type") == "bundle")
		{
			$insert["item_name"]				= $item_name;
			$insert["item_sku"]					= $item_sku;
			$insert["item_category_id"]			= $item_category_id;
			$insert["item_img"]					= $item_img;
			$insert["item_sales_information"] 	= $item_sales_information;
			$insert["item_type_id"]				= 4; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$rules["item_name"]					= "required";
			$rules["item_sku"] 					= "required";

			$_item 	= Request::input('bundle_item_id');
			$_um 	= Request::input('bundle_um_id');
			$_qty 	= Request::input('bundle_qty');

			$item_id = "";
			$message   = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails())
			{
				$return["error"]  	= $validator->errors()->all();
				$return["message"] 	= "Failed";
				$return["item_id"] 	= null;
				$return["type"]		= "item";
			}
			else
			{
				$item_id 			= Tbl_item::insertGetId($insert);

				$insert_item_discount["item_id"] = $item_id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				foreach($_item as $key=>$item)
				{
					if($item != '')
					{
						$insert_bundle["bundle_bundle_id"] 	= $item_id;
						$insert_bundle["bundle_item_id"] 	= $item;
						$insert_bundle["bundle_um_id"]		= isset($_um[$key]) ? $_um[$key] : 0;
						$insert_bundle["bundle_qty"]		= $_qty[$key];
						Tbl_item_bundle::insert($insert_bundle);
					}
				}
				
				$return["item_id"] 	= $item_id;
				$return["message"] 	= "Success";
				$return["type"]		= "item";

				$bundle_price = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
				foreach ($bundle_price as $key => $value) 
				{
					$item_price = Tbl_item::where("item_id",$value->bundle_item_id)->value("item_price");
                	$um = Tbl_unit_measurement_multi::where("multi_id",$value->bundle_um_id)->first();

					$qt = 1;
	                if($um != null)
	                {
	                    $qt = $um->unit_qty;
	                }
                	$issued_qty = $value->bundle_qty * $qt;

                	$price += $issued_qty * $item_price;
				}

				$insert["item_price"] = $price;

			}
		}
		else 
		{
			$return["error"][0] = "Please try again";
			$return["message"]   = "Failed";
			$return["item_id"] = null;
			$return["type"]		= "item";
		}
		if($return["message"] == "Success" || $return['status'] = 'success-serial')
		{
			// $access_warehouse = Utilities::checkAccess('item-warehouse', 'merchantwarehouse');
			// if($access_warehouse == 1)
			// {
				/* CHECK IF AUTO APPROVED OR NOT */
				$auto_approved = Utilities::checkAccess('item-list', 'add_auto_approve');
				if($auto_approved == 1)
				{
					/* SET THE ITEM AS APPROVED */
					// $insert_merchant["item_merchant_request_status"]	= "Accepted";			
					// $insert_merchant["item_merchant_requested_by"]	    = $this->user_info->user_id;			
					// $insert_merchant["item_merchant_accepted_by"]	    = $this->user_info->user_id;			
					// $insert_merchant["merchant_warehouse_id"]		    = $this->current_warehouse->warehouse_id;			
					// $insert_merchant["merchant_item_id"]			    = $item_id; 			
					// $insert_merchant["item_merchant_accepted_date"]	    = Carbon::now();			
					// $insert_merchant["date_created"]					= Carbon::now();

					// Tbl_item_merchant_request::insert($insert_merchant);
				}
				else
				{
					$insert_merchant["item_merchant_request_status"]	= "Pending";			
					$insert_merchant["item_merchant_requested_by"]	    = $this->user_info->user_id;			
					$insert_merchant["item_merchant_accepted_by"]	    = null;			
					$insert_merchant["merchant_warehouse_id"]		    = $this->current_warehouse->warehouse_id;			
					$insert_merchant["merchant_item_id"]			    = $item_id; 			
					$insert_merchant["item_merchant_accepted_date"]	    = null;			
					$insert_merchant["date_created"]					= Carbon::now();
								
					Tbl_item_merchant_request::insert($insert_merchant);


					/* SET ITEM ARCHIVED IF NOT AUTO APPROVED WHEN APPROVED IT SHOULD BE ARCHIVED TO ZERO */
					$update_item["archived"] = 1;
					Tbl_item::where("item_id",$item_id)->update($update_item);
				}
			// }

			Session::forget("item_temporary_data");
			$insert["item_id"] = $item_id;
	        AuditTrail::record_logs("Added","item",$item_id,"",serialize($insert));
		}
		// dd($return);
    	return json_encode($return);
	}	
	public function edit($id)
	{ 
        $access = Utilities::checkAccess('item-list', 'access_page');
        if($access == 1)
        {
			$shop_id          = $this->user_info->shop_id;
			$data["data"]	  = Tbl_item::um()->where("item_id",$id)->itemDiscount()->first()->toArray();
			$data["item_id"]  = $id;
			if($data["data"]["item_type_id"] == 1)
			{
				$data["data"]["type_of_item"] = "inventory_type";
			}
			else if($data["data"]["item_type_id"] == 2)
			{
				$data["data"]["type_of_item"] = "noninventory_type";
			}
			else if($data["data"]["item_type_id"] == 3)
			{
				$data["data"]["type_of_item"] = "service_type";
			}
			else if($data["data"]["item_type_id"] == 4 && $data["data"]["bundle_group"] == 1)
			{
				$data["data"]["type_of_item"] = "group_type";
				$data["data"]["bundle"]		  = Tbl_item_bundle::item()->um()->where("bundle_bundle_id", $id)->get()->toArray();
			}
			else if($data["data"]["item_type_id"] == 4)
			{
				$data["data"]["type_of_item"] = "bundle_type";
				$data["data"]["bundle"]		  = Tbl_item_bundle::item()->um()->where("bundle_bundle_id", $id)->get()->toArray();
			}

			if($data["data"]["parent_basis_um"] != 0)
			{
				$data["data"]["item_measurement_id"] = $data["data"]["parent_basis_um"];	
			}
			$data["data"]["item_date_tracked"] = date('m/d/Y',strtotime($data["data"]["item_date_tracked"]));
			
			$data["_income"] 	= Accounting::getAllAccount('all',null,['Income','Other Income']);
			$data["_asset"] 	= Accounting::getAllAccount('all', null, ['Other Current Asset','Fixed Asset','Other Asset']);
			$data["_expense"] 	= Accounting::getAllAccount('all',null,['Expense','Other Expense','Cost of Goods Sold']);

			// $data['_category']  = Category::getAllCategory();

			$data['_service']  		    = Category::getAllCategory(['services']);
			$data['_inventory']  		= Category::getAllCategory(['inventory']);
			$data['_noninventory']  	= Category::getAllCategory(['non-inventory']);
			$data['_bundle']        	= Category::getAllCategory(['bundles']);
			
			$data["_manufacturer"] = Tbl_manufacturer::where("manufacturer_shop_id",$shop_id)->get();
			$data["_um"] 	  	= UnitMeasurement::load_um();
			$data["_um_multi"] 	= UnitMeasurement::load_um_multi();
			$data['_item']  	= Item::get_all_category_item();
			$data['_item_to_bundle']	= Item::get_all_category_item([1,2,3]);
            $data["_vendor"]    = Vendor::getAllVendor('active');

			// dd($data);
		    //check if_for PIS
        	$pis_check = Purchasing_inventory_system::check();
        	if($pis_check != 0)
        	{
        		$data['_um_n'] = Tbl_um::where("um_shop_id",$shop_id)->where("is_based",0)->get();
        		$data['_um_b'] = Tbl_um::where("um_shop_id",$shop_id)->where("is_based",1)->get();

        		$um_info = Tbl_unit_measurement::where("um_id",$data["data"]["item_measurement_id"])->first();
        		$multi_info = Tbl_unit_measurement_multi::where("multi_um_id",$data["data"]["item_measurement_id"])->where("is_base",0)->first();
        			$data['um_n_id'] = '';
        			$data['um_n_b_id'] = '';
        			$data['quantity'] = 1;
        		if($um_info)
        		{
        			$data['um_n_id'] = $um_info->um_n_base;
        			$data['um_n_b_id'] = $um_info->um_base;
        			$qty =  isset($multi_info->unit_qty) ? $multi_info->unit_qty : 1;
	        		$data['quantity'] = $qty;

        			$data['data']['item_price'] = round($qty * $data['data']['item_price'],2);
        			$data['data']['item_cost'] = round($qty * $data['data']['item_cost'],2);
        		}
        		$data['action'] = "/member/item/edit_submit_pis";
		    	return view('member.item.pis.edit_item',$data);        		
        	}
        	else
        	{
		    		return view('member.item.edit',$data);
        	}
        }
        else
        {
            return $this->show_no_access_modal();
        }  
	}
	public function edit_submit_pis()
	{
		$item_type = Request::input("item_type");
		$shop_id = $this->user_info->shop_id;

		$item_id = Request::input("item_id");
		$price = 0;

		$old = Tbl_item::where("item_id",$item_id)->first()->toArray();

		$item_name	 					= Request::input("item_name");
		$item_sku						= Request::input("item_sku");
		$item_category_id				= Request::input("item_category_id");
		$item_img 						= Request::input("item_img");
		$item_sales_information 		= Request::input("item_sales_information");
		$item_date_tracked 				= date("Y-m-d g:i:s",strtotime(Request::input("item_date_tracked")));
		// $item_purchasing_information 	= Request::input("item_purchasing_information");
		$item_barcode 					= Request::input("item_barcode");
		$item_manufacturer_id 			= Request::input("item_manufacturer_id");
		$packing_size 					= Request::input("packing_size");

		$item_sale_to_customer 			= Request::input("item_sale_to_customer") ? 1 : 0; 
		$item_purchase_from_supplier    = Request::input("item_purchase_from_supplier") ? 1 : 0;

		$unit_n_based 					= Request::input("unit_n_based");
		$unit_based 					= Request::input("unit_based");
		$qty 					        = Request::input("quantity") or 1;

		$item_price 					= str_replace(',','',Request::input("item_price"));
		$item_cost 						= str_replace(',','',Request::input("item_cost"));
		$item_reorder_point 			= Request::input("item_reorder_point");
		$item_quantity 					= Request::input("item_quantity");

		$sales_price_change				= Request::input('sales_price_change');
		$cost_price_change				= Request::input('cost_price_change');
		
		if ($old["item_measurement_id"]) 
		{
			$unit_id = UnitMeasurement::update_um_v2($unit_n_based, $unit_based, $qty, $old["item_measurement_id"]);
		}
		else
		{
			$unit_id = UnitMeasurement::create_um($unit_n_based, $unit_based, $qty);
		}

		$item_measurement_id 			= $unit_id;			
			
		//Accounting part //DEFAULT VALUE
		$item_expense_account_id= Tbl_chart_of_account::where("account_code", "accounting-expense")->where("account_shop_id", $shop_id)->value("account_id");
		$item_income_account_id = Tbl_chart_of_account::where("account_code", "accounting-sales")->where("account_shop_id", $shop_id)->value("account_id");
		$item_asset_account_id 	= Tbl_chart_of_account::where("account_code", "accounting-inventory-asset")->where("account_shop_id", $shop_id)->value("account_id");

		if($sales_price_change)
		{
			Item::change_price($item_id,$sales_price_change,$item_price);
		}
		if($cost_price_change)
		{
			Item::change_price($item_id,$cost_price_change,$item_cost);
		}

		if($item_type == "inventory")
		{			
			$update["item_type_id"]				    = 1; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$update["item_name"]	    	  		= $item_name;
			$update["item_sku"]	    				= $item_sku;
			$update["item_category_id"]	    	 	= $item_category_id;
			$update["item_img"]	    				= $item_img;
			$update["item_sales_information"]	    = $item_sales_information;
			$update["item_date_tracked"]	    	= $item_date_tracked;
			$update["item_barcode"]	    	  		= $item_barcode;
			$update["item_manufacturer_id"]	    	= $item_manufacturer_id;
			$update["packing_size"]	    	  		= $packing_size;
			$update["item_sale_to_customer"]	    = $item_sale_to_customer;
			$update["item_purchase_from_supplier"]	= $item_purchase_from_supplier;

			$update["item_expense_account_id"]	    = $item_expense_account_id;
			$update["item_income_account_id"]		= $item_income_account_id;
			$update["item_asset_account_id"]		= $item_asset_account_id;

			$update["item_price"]	    			= $item_price / $qty;
			$update["item_cost"]	    	  		= $item_cost / $qty;
			$update["item_reorder_point"]	 	    = $item_reorder_point * $qty;
			$update["item_quantity"]				= $item_quantity * $qty;

			$update["item_measurement_id"]			= $item_measurement_id;

			$rules["item_name"]	    	  			= 'required';
			$rules["item_sku"]	    				= 'required';
			$rules["item_category_id"]	    	 	= 'required';
			$rules["item_barcode"]	    	  		= 'required';
			$rules["item_price"]	    			= 'required';
			$rules["item_cost"]	    	  			= 'required';

			$validator = Validator::make($update, $rules);

			$data["status_message"] = '';
			$data["status"] = '' ;
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}

			if($data['status'] == null)
			{
				Tbl_item::where("item_id",$item_id)->update($update);

                $data['status'] 	= 'success';
                $data['message'] 	= 'Success';
                $data["type"]		= "item";
	            $data['item_id'] 	= $item_id;
			}
		}
		elseif($item_type == "noninventory")
		{
			$update["item_type_id"]				      = 2; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$update["item_name"]	    	  		= $item_name;
			$update["item_sku"]	    				= $item_sku;
			$update["item_category_id"]	    	 	= $item_category_id;
			$update["item_img"]	    				= $item_img;
			$update["item_sales_information"]	    = $item_sales_information;
			$update["item_date_tracked"]	    	= $item_date_tracked;
			$update["item_sale_to_customer"]	    = $item_sale_to_customer;
			$update["item_purchase_from_supplier"]	= $item_purchase_from_supplier;

			$update["item_expense_account_id"]	    = $item_expense_account_id;
			$update["item_income_account_id"]		= $item_income_account_id;

			$update["item_price"]	    			= $item_price / $qty;
			$update["item_cost"]	    	  		= $item_cost / $qty;
			$update["item_reorder_point"]	 	    = $item_reorder_point * $qty;
			$update["item_quantity"]				= $item_quantity * $qty;

			$update["item_measurement_id"]			= $item_measurement_id;

			$rules["item_name"]	    	  			= 'required';
			$rules["item_sku"]	    				= 'required';
			$rules["item_category_id"]	    	 	= 'required';
			$rules["item_price"]	    			= 'required';

			$validator = Validator::make($update, $rules);

			$data["status_message"] = '';
			$data["status"] = '' ;
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}

			if($data['status'] == null)
			{
				$item_id = Tbl_item::where("item_id",$item_id)->update($update);

                $data['status'] 	= 'success';
                $data['message'] 	= 'Success';
                $data["type"]		= "item";
	            $data['item_id'] 	= $item_id;
			}

		}
		elseif($item_type == "service")
		{
			$update["item_type_id"]				      = 3; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)

			$update["item_name"]	    	  		= $item_name;
			$update["item_sku"]	    				= $item_sku;
			$update["item_category_id"]	    	 	= $item_category_id;
			$update["item_img"]	    				= $item_img;
			$update["item_sales_information"]	    = $item_sales_information;
			$update["item_date_tracked"]	    	= $item_date_tracked;
			$update["item_sale_to_customer"]	    = $item_sale_to_customer;
			$update["item_purchase_from_supplier"]	= $item_purchase_from_supplier;
			
			$update["item_expense_account_id"]	    = $item_expense_account_id;
			$update["item_income_account_id"]		= $item_income_account_id;

			$update["item_price"]	    			= $item_price / $qty;
			$update["item_cost"]	    	  		= $item_cost / $qty;
			$update["item_reorder_point"]	 	    = $item_reorder_point * $qty;
			$update["item_quantity"]				= $item_quantity * $qty;

			$update["item_measurement_id"]			= $item_measurement_id;

			$rules["item_name"]	    	  			= 'required';
			$rules["item_sku"]	    				= 'required';
			$rules["item_category_id"]	    	 	= 'required';
			$rules["item_price"]	    			= 'required';

			$validator = Validator::make($update, $rules);

			$data["status_message"] = '';
			$data["status"] = '' ;
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}

			if($data['status'] == null)
			{
				Tbl_item::where("item_id",$item_id)->update($update);

                $data['status'] 	= 'success';
                $data['message'] 	= 'Success';
                $data["type"]		= "item";
	            $data['item_id'] 	= $item_id;
			}
		
		}
		elseif($item_type == "bundle")
		{
			$update["item_type_id"]				      = 4; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)
			
			if(Request::input("auto_generate_code") == "generate" || $item_barcode == "")
			{
				$num = str_shuffle("1234567890");
				$item_barcode = Item::generate_barcode($num);
			}
			
			$update["item_name"]				= $item_name;
			$update["item_sku"]					= $item_sku;
			$update["item_category_id"]			= $item_category_id;
			$update["item_img"]					= $item_img;
			$update["item_sales_information"] 	= $item_sales_information;

			$update["item_barcode"]	    	  	= $item_barcode;
			$update["item_measurement_id"]	   	= $item_measurement_id;

			$rules["item_name"]					= "required";
			$rules["item_sku"] 					= "required";

			$_item 	= Request::input('bundle_item_id');
			$_um 	= Request::input('bundle_um_id');
			$_qty 	= Request::input('bundle_qty');

			$message   = [];
			$validator = Validator::make($update, $rules, $message);
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}
			else
			{
				Tbl_item::where("item_id",$item_id)->update($update);

				Tbl_item_bundle::where("bundle_bundle_id",$item_id)->delete();

				foreach($_item as $key=>$item)
				{
					if($item != '')
					{
						$insert_bundle["bundle_bundle_id"] 	= $item_id;
						$insert_bundle["bundle_item_id"] 	= $item;
						$insert_bundle["bundle_um_id"]		= isset($_um[$key]) ? $_um[$key] : 0;
						$insert_bundle["bundle_qty"]		= $_qty[$key];
						Tbl_item_bundle::insert($insert_bundle);
					}
				}
				
				$data["item_id"] 	= $item_id;
				$data["message"] 	= "Success";
				$data["status"] 	= "success";
				$data["type"]		= "item";

				$bundle_price = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
				foreach ($bundle_price as $key => $value) 
				{
					$item_price = Tbl_item::where("item_id",$value->bundle_item_id)->value("item_price");
                	$um = Tbl_unit_measurement_multi::where("multi_id",$value->bundle_um_id)->first();

					$qt = 1;
	                if($um != null)
	                {
	                    $qt = $um->unit_qty;
	                }
                	$issued_qty = $value->bundle_qty * $qt;

                	$price += $issued_qty * $item_price;
				}

				$update["item_price"] = $price;

			}
		}
		elseif($item_type == "group")
		{
			$update["item_type_id"]				      = 4; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service, 4 = Bundle)
			
			if(Request::input("auto_generate_code") == "generate" || $item_barcode == "")
			{
				$num = str_shuffle("1234567890");
				$item_barcode = Item::generate_barcode($num);
			}
			
			$update["item_name"]				= $item_name;
			$update["item_sku"]					= $item_sku;
			$update["item_category_id"]			= $item_category_id;
			$update["item_img"]					= $item_img;
			$update["item_sales_information"] 	= $item_sales_information;

			$update["item_barcode"]	    	  	= $item_barcode;
			$update["item_measurement_id"]	   	= $item_measurement_id;

			$rules["item_name"]					= "required";
			$rules["item_sku"] 					= "required";

			$_item 	= Request::input('bundle_item_id');
			$_um 	= Request::input('bundle_um_id');
			$_qty 	= Request::input('bundle_qty');

			$message   = [];
			$validator = Validator::make($update, $rules, $message);
			if($validator->fails())
			{
				$data["status"] = "error";
	            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
	            {
	                $data["status_message"] .= $message;
	            }
			}
			else
			{
				Tbl_item::where("item_id",$item_id)->update($update);

				Tbl_item_bundle::where("bundle_bundle_id",$item_id)->delete();

				foreach($_item as $key=>$item)
				{
					if($item != '')
					{
						$insert_bundle["bundle_bundle_id"] 	= $item_id;
						$insert_bundle["bundle_item_id"] 	= $item;
						$insert_bundle["bundle_um_id"]		= isset($_um[$key]) ? $_um[$key] : 0;
						$insert_bundle["bundle_qty"]		= $_qty[$key];
						Tbl_item_bundle::insert($insert_bundle);
					}
				}
				
				$data["item_id"] 	= $item_id;
				$data["message"] 	= "Success";
				$data["status"] 	= "success";
				$data["type"]		= "item";

				$bundle_price = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
				foreach ($bundle_price as $key => $value) 
				{
					$item_price = Tbl_item::where("item_id",$value->bundle_item_id)->value("item_price");
                	$um = Tbl_unit_measurement_multi::where("multi_id",$value->bundle_um_id)->first();

					$qt = 1;
	                if($um != null)
	                {
	                    $qt = $um->unit_qty;
	                }
                	$issued_qty = $value->bundle_qty * $qt;

                	$price += $issued_qty * $item_price;
				}

				$update["item_price"] = $price;

			}
		}
		if($data['status'] == 'success')
		{
			$update["item_id"] = $item_id;
	        AuditTrail::record_logs("Edited","item",$item_id,serialize($old),serialize($update));
		}
		return json_encode($data);

	}	
	public function edit_submit()
	{	
		$shop_id = $this->user_info->shop_id;
		$id		 = Request::input("item_id");

		$price = "";

		$old = Tbl_item::where("item_id",$id)->first()->toArray();
		// dd($id);
		$insert["item_name"]						  = null;
		$insert["item_sku"]							  = null;
		$insert["item_category_id"]					  = null;
		$insert["item_img"]							  = null;
		$insert["item_price"]						  = null;
		$insert["item_sales_information"]			  = null;
		$insert["item_asset_account_id"]			  = null;
		// $insert["item_quantity"]					  = null;
		$insert["item_date_tracked"]				  = null;
		$insert["item_reorder_point"]				  = null;
		$insert["item_income_account_id"]			  = null;
		$insert["item_purchasing_information"]		  = null;
		$insert["item_cost"]						  = null;
		$insert["item_expense_account_id"]			  = null;
		$insert["item_date_created"]				  = null;
		$insert["item_sale_to_customer"]			  = 0;
		$insert["item_purchase_from_supplier"]	      = 0;
		
		$promo_price 			= Request::input("promo_price");
		$start_promo_date 		= Request::input("start_promo_date");
		$end_promo_date 		= Request::input("end_promo_date");


		$item_measurement_id = Request::input("item_measurement_id");
		$check = UnitMeasurement::check();

        if($item_measurement_id == $old["item_measurement_id"])
		{
	        if($check != 0)
	        {
	        	$item_measurement_id = Tbl_unit_measurement::where("um_id",$old["item_measurement_id"])->value("um_id");
	        }			
		}
		if(Session::get("um_id") != null)
		{
			$item_measurement_id = Session::get("um_id");
		}

		$item_name = Request::input("item_name");
		$item_sku = Request::input("item_sku");

		if(Request::input("item_type") == "inventory")			
		{
			$insert["item_name"]				      = $item_name;
			$insert["item_sku"]					      = $item_sku;
			$insert["item_category_id"]			      = Request::input("item_category_id");
			$insert["item_img"]					      = Request::input("item_img") ? Request::input("item_img") : "";
			$insert["item_type_id"]				      = 1; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service)
			$insert["item_price"] 				      = Request::input("item_price");
			$insert["item_barcode"] 				      = Request::input("item_barcode");
			$insert["item_sales_information"] 	      = Request::input("item_sales_information") ? Request::input("item_sales_information") : "";
			$insert["item_asset_account_id"]          = Request::input("item_asset_account_id");
			// $insert["item_quantity"]		          = Request::input("item_quantity");
			$insert["item_date_tracked"]	          = date("Y-m-d g:i:s",strtotime(Request::input("item_date_tracked")));
			$insert["item_reorder_point"] 		      = Request::input("item_reorder_point");
			$insert["item_income_account_id"] 	      = Request::input("item_income_account_id");
			$insert["item_purchasing_information"]    = Request::input("item_purchasing_information") ? Request::input("item_purchasing_information") : "";
			$insert["item_cost"]				      = Request::input("item_cost");
			$insert["item_expense_account_id"]	      = Request::input("item_expense_account_id");
			$insert["item_date_created"]	    	  = Carbon::now();
			$insert["item_measurement_id"]	      	  = $item_measurement_id;
			$insert["item_manufacturer_id"]	      	  = Request::input("item_manufacturer_id");
			$insert["shop_id"]	    				  = $shop_id;
				
			$rules["item_name"]					      = 'required';
			$rules["item_sku"]					      = 'required';
			// $rules["item_barcode"]					      = 'alpha_num';
			// $rules["item_category_id"]			  = '';
			// $rules["item_img"]					  = '';
			// $rules["item_type_id"]				  = '';
			$rules["item_price"]				      = 'required|numeric';
			$rules["item_sales_information"]	      = '';
			$rules["item_asset_account_id"]		      = '';
			// $rules["item_quantity"]				      = 'required|numeric';
			$rules["item_date_tracked"]			      = 'date';
			$rules["item_reorder_point"]		      = 'required|numeric';
			// $rules["item_income_account_id"]	      = '';
			$rules["item_purchasing_information"]     = '';
			$rules["item_cost"]					      = 'required|numeric';
			// $rules["item_expense_account_id"]	  = '';
			
			$message                            	  = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails()) //fail to add product
			{
				$return["error"]  = $validator->errors()->all();
				$return["message"] = "Failed";
			}
			else
			{
				//update re-order point from MAIN WAREHOUSE
				$up_item["item_reorder_point"] = $insert["item_reorder_point"];
				$warehouse = Tbl_warehouse::where("warehouse_shop_id",$shop_id)->where("main_warehouse",1)->value("warehouse_id");
				Tbl_sub_warehouse::where("warehouse_id",$warehouse)->where("item_id",$id)->update($up_item);

				Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->update($insert);


				$insert_item_discount["item_id"] = $id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				UnitMeasurement::update_um(Session::get("um_id"),Request::input("item_name"),$id);

				$return["message"] = "Success";
			}
		}
		else if(Request::input("item_type") == "noninventory")
		{
			$insert["item_name"]				      = $item_name;
			$insert["item_sku"]					      = $item_sku;
			$insert["item_category_id"]			      = Request::input("item_category_id");
			$insert["item_img"]					      = Request::input("item_img") ? Request::input("item_img") : "";
			$insert["item_type_id"]				      = 2; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service)
			$insert["item_price"] 				      = Request::input("item_price");
			$insert["item_sales_information"] 	      = Request::input("item_sales_information") ? Request::input("item_sales_information") : "";
			$insert["item_asset_account_id"]          = Request::input("item_asset_account_id");
			$insert["item_sale_to_customer"]		  = Request::input("item_sale_to_customer") ? 1 : 0;
			$insert["item_purchase_from_supplier"]	  = Request::input("item_purchase_from_supplier") ? 1 : 0;
			$insert["item_date_tracked"]	          = date("Y-m-d g:i:s",strtotime(Request::input("item_date_tracked")));
			$insert["item_date_created"]	    	  = Carbon::now();
			$insert["shop_id"]	    				  = $shop_id;
			$insert["item_measurement_id"]	      	  = $item_measurement_id;
			$insert["item_income_account_id"] 		  = Request::input("item_income_account_id");
			$insert["item_purchasing_information"]    = Request::input("item_purchasing_information") ? Request::input("item_purchasing_information") : "";
			$insert["item_cost"]				      = Request::input("item_cost");
			$insert["item_expense_account_id"]	      = Request::input("item_expense_account_id");

			$rules["item_name"]					      = 'required';
			$rules["item_sku"]					      = 'required';
			// $rules["item_category_id"]			  = '';
			// $rules["item_img"]					  = '';
			// $rules["item_type_id"]				  = '';
			$rules["item_price"]				      = 'required|numeric';
			$rules["item_sales_information"]	      = '';
			$rules["item_asset_account_id"]		      = '';
			$rules["item_sale_to_customer"]		  = 'required|numeric|min:0|max:1';
			$rules["item_purchase_from_supplier"]  = 'required|numeric|min:0|max:1';

			
			$message                            	  = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails()) //fail to add item
			{
				$return["error"]  = $validator->errors()->all();
				$return["message"] = "Failed";
			}
			else
			{
				Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->update($insert);

				$insert_item_discount["item_id"] = $id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				$return["message"] = "Success";
			}
		}
		else if(Request::input("item_type") == "service")
		{
			$insert["item_name"]				      = $item_name;
			$insert["item_sku"]					      = $item_sku;
			$insert["item_category_id"]			      = Request::input("item_category_id");
			$insert["item_img"]					      = Request::input("item_img") ? Request::input("item_img") : "";
			$insert["item_type_id"]				      = 3; // TYPE (1 = Inventory , 2 = Non Inventory, 3 = Service)
			$insert["item_price"] 				      = Request::input("item_price");
			$insert["item_sales_information"] 	      = Request::input("item_sales_information") ? Request::input("item_sales_information") : "";
			$insert["item_asset_account_id"]          = Request::input("item_asset_account_id");
			$insert["item_sale_to_customer"]		  = Request::input("item_sale_to_customer") ? 1 : 0;
			$insert["item_purchase_from_supplier"]	  = Request::input("item_purchase_from_supplier") ? 1 : 0;
			$insert["item_date_created"]	    	  = Carbon::now();
			$insert["shop_id"]	    				  = $shop_id;
			$insert["item_measurement_id"]	      	  = $item_measurement_id;
			$insert["item_income_account_id"] 		  = Request::input("item_income_account_id");
			$insert["item_purchasing_information"]    = Request::input("item_purchasing_information") ? Request::input("item_purchasing_information") : "";
			$insert["item_cost"]				      = Request::input("item_cost");
			$insert["item_expense_account_id"]	      = Request::input("item_expense_account_id");
			
			$rules["item_name"]					      = 'required';
			$rules["item_sku"]					      = 'required';
			// $rules["item_category_id"]			  = '';
			// $rules["item_img"]					  = '';
			// $rules["item_type_id"]				  = '';
			$rules["item_price"]				      = 'required|numeric';
			$rules["item_sales_information"]	      = '';
			$rules["item_asset_account_id"]		      = '';
			$rules["item_sale_to_customer"]		  = 'required|numeric|min:0|max:1';
			$rules["item_purchase_from_supplier"]  = 'required|numeric|min:0|max:1';

			
			$message                            	  = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails()) //fail to add item
			{
				$return["error"]  = $validator->errors()->all();
				$return["message"] = "Failed";
			}
			else
			{
				Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->update($insert);
					$insert_item_discount["item_id"] = $id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				$return["message"] = "Success";
			}
		}
		else if(Request::input("item_type") == "bundle")
		{
			$insert["item_name"]				= $item_name;
			$insert["item_sku"]					= $item_sku;
			$insert["item_img"]					= Request::input("item_img");
			$insert["item_category_id"]			= Request::input("item_category_id");
			$insert["item_sales_information"] 	= Request::input("item_sales_information");

			$rules["item_name"]					= "required";
			$rules["item_sku"] 					= "required";

			$_item 	= Request::input('bundle_item_id');
			$_um 	= Request::input('bundle_um_id');
			$_qty 	= Request::input('bundle_qty');

			$message   = [];
			$validator = Validator::make($insert, $rules, $message);
			if ($validator->fails())
			{
				$return["error"]  = $validator->errors()->all();
				$return["message"] = "Failed";
			}
			else
			{
				Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->update($insert);

				$insert_item_discount["item_id"] = $id;
				$insert_item_discount["item_discount_value"] = $promo_price;
				$insert_item_discount["item_discount_date_start"] = $start_promo_date;
				$insert_item_discount["item_discount_date_end"]	 = $end_promo_date;	
				Item::insert_item_discount($insert_item_discount);

				Tbl_item_bundle::where("bundle_bundle_id",$id)->delete();

				foreach($_item as $key=>$item)
				{
					if($item != '')
					{
						$insert_bundle["bundle_bundle_id"] 	= $id;
						$insert_bundle["bundle_item_id"] 	= $item;
						$insert_bundle["bundle_um_id"]		= $_um[$key];
						$insert_bundle["bundle_qty"]		= $_qty[$key];
						Tbl_item_bundle::insert($insert_bundle);
					}
				}
				
				$return["message"] = "Success";

				$bundle_price = Tbl_item_bundle::where("bundle_bundle_id",$id)->get();
				foreach ($bundle_price as $key => $value) 
				{
					$item_price = Tbl_item::where("item_id",$value->bundle_item_id)->value("item_price");
                	$um = Tbl_unit_measurement_multi::where("multi_id",$value->bundle_um_id)->first();

					$qt = 1;
	                if($um != null)
	                {
	                    $qt = $um->unit_qty;
	                }
                	$issued_qty = $value->bundle_qty * $qt;

                	$price += $issued_qty * $item_price;
				}

				$insert["item_price"] = $price;
			}
		}
		else 
		{
			$return["error"][0] = "Please try again";
			$return["message"]   = "Failed";
		}
		if($return["message"] == "Success")
		{			
			$insert["item_id"] = $id;
	        AuditTrail::record_logs("Edited","item",$id,serialize($old),serialize($insert));
		}
    	
    	return json_encode($return);
	}	

	public function get_multiple_price_modal($item_id)
	{
		$shop_id = $this->user_info->shop_id;
		$data["_multiple_price"] = Tbl_item_multiple_price::item($shop_id)->get();
		$data["item_id"]		 = $item_id;

		return view('member/item/item_multiple_price_modal', $data);
	}

	public function update_multiple_price_modal()
	{
		$item_id = Request::input('item_id');
		$shop_id = $this->user_info->shop_id;

		Tbl_item_multiple_price::item($shop_id)->where("item_id", $item_id)->delete();

		foreach(Request::input('multiprice_qty') as $key=>$qty)
		{
			if($qty > 1)
			{
				$insert["multiprice_item_id"] = $item_id;
				$insert["multiprice_qty"]	  = $qty;
				$insert["multiprice_price"]	  = Request::input('multiprice_price')[$key];
				$insert["date_created"]		  = Carbon::now();

				Tbl_item_multiple_price::insert($insert);
			}
		}

		$json["response_status"] 	= "success";
		$json["type"] 				= "multiple_price";
		$json["message"]			= "Success ";
		return json_encode($json);
	}

	public function get_item_new_price($item_id, $qty)
	{
		return Tbl_item::newPrice($qty)->where("item_id", $item_id)->value("new_price");
	}

	public function insert_session()
	{
		$data								 = null;
		$data["type_of_item"]           	 = Request::input("type_of_item");
		$data["item_id"]					 = Request::input("item_id");
		$data["item_name"]					 = Request::input("item_name")."/".Request::input("item_sku");
		$data["item_sku"]					 = Request::input("item_name")."/".Request::input("item_sku");
		$data["item_sales_information"]		 = Request::input("item_sales_information");
		$data["item_purchasing_information"] = Request::input("item_purchasing_information");
		$data["item_img"]					 = Request::input("item_img");
		$data["item_quantity"]				 = Request::input("item_quantity");
		$data["item_reorder_point"]			 = Request::input("item_reorder_point");
		$data["item_price"]					 = Request::input("item_price");
		$data["item_cost"]					 = Request::input("item_cost");
		$data["item_sale_to_customer"]		 = Request::input("item_sale_to_customer");
		$data["item_purchase_from_supplier"] = Request::input("item_purchase_from_supplier");
		$data["item_type_id"]				 = Request::input("item_type_id");
		$data["item_category_id"]			 = Request::input("item_category_id");
		$data["item_asset_account_id"]		 = Request::input("item_asset_account_id");
		$data["item_income_account_id"]		 = Request::input("item_income_account_id");
		$data["item_expense_account_id"]	 = Request::input("item_expense_account_id");
		$data["item_date_tracked"]			 = date("m/d/Y",strtotime(Request::input("item_date_tracked")));
		
		Session::put("item_temporary_data",$data);
	}
	public function archive($id)
	{
        $shop_id               = $this->user_info->shop_id;
        $data["page"]          = "Item";
        $data["item"]          = Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->first();
        if(!$data["item"])
        {
            dd("Please try again.");
        }
        return view('member.item.item_archived_modal',$data);
	}
	public function archive_submit()
	{
        $shop_id    = $this->user_info->shop_id;
        $id         = Request::input("item_id");
        $code       = Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->first();
        $ctr_inventory = Tbl_item::leftjoin("tbl_warehouse_inventory","tbl_warehouse_inventory.inventory_item_id","=","tbl_item.item_id")->where("tbl_item.item_id",$id)->where("tbl_item.shop_id",$shop_id)->sum("inventory_count");

        $has_product = Tbl_item::product('0')->where("item_id", $id)->first();

        if(!$has_product)
        {
	        if($ctr_inventory <= 0)
	        {
		        if($code)
		        {
		            if($code->used == 0 && $code->blocked == 0)
		            {
		               $update["archived"] = 1;
		               Tbl_item::where("item_id",$id)->update($update);

		               ItemSerial::archived_item_serial($id);
		               
					   $return["error"][0]  = "Successfully archived";
					   $return["message"]   = "Sucess-archived";   
		            }
		            else if($code->archive == 1)
		            {
						$return["error"][0]  = "Already archived";
						$return["message"]   = "Failed";  
		            }

			        $item = Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->first()->toArray();

			        AuditTrail::record_logs("Archived","item",$id,"",serialize($item));
		        }
		        else
		        {
					$return["error"][0]  = "Please try again";
					$return["message"]   = "Failed";
		        }
	        }
	        else
	        {
	        	$return["error"][0]  = "You can't delete Item, ".$code->item_name." it has ".$ctr_inventory." quantity";
				$return["message"]   = "Failed";
	        }
    	}
    	else
    	{
    		$return["error"][0]  = "You can't delete Item ".$code->item_name.". It's being use in ecommerce products. ";
				$return["message"]   = "Failed";
    	}

        return json_encode($return);
	}
	public function restore($id)
	{
        $shop_id               = $this->user_info->shop_id;
        $data["page"]          = "Item";
        $data["item"]          = Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->first();
        if(!$data["item"])
        {
            dd("Please try again.");
        }
        return view('member.item.item_restore_modal',$data);
	}
	public function restore_submit()
	{
        $shop_id    = $this->user_info->shop_id;
        $id         = Request::input("item_id");
        $code       = Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->first();
        if($code)
        {

           $update["archived"] = 0;
           Tbl_item::where("item_id",$id)->update($update);
		   $return["error"][0]  = "Successfully restored";
		   $return["message"]   = "Success-restored";   

	        // $item = Tbl_item::where("item_id",$id)->where("shop_id",$shop_id)->first()->toArray();

	        AuditTrail::record_logs("Restored","item",$id,"",serialize(collect($code)->toArray()));
        }
        else
        {
			$return["error"][0]  = "Please try again";
			$return["message"]   = "Failed";
        }

        return json_encode($return);
	}
	public function show_mlm()
	{
		// return $_POST;
		$item_id = Request::input('item_id');
		$item_show_in_mlm = Request::input('item_show_in_mlm');
		if($item_show_in_mlm == null)
		{
			$item_show_in_mlm = 0;
		}
		$updata['item_show_in_mlm'] = $item_show_in_mlm;
		Tbl_item::where('item_id', $item_id)->update($updata);
		$data['response_status'] = 'success';
		$data['message'] = 'success';
		return $data;
	}

	public function merchant_approve_request($id)
	{
		$item 	 = Tbl_item::where("item_id",$id)->where("shop_id",$this->user_info->shop_id)->first();
		$data['can_approve_item_request'] = Utilities::checkAccess('item-list', 'can_approve_item_request');
		$data['can_edit_other_item'] = Utilities::checkAccess('item-list', 'can_edit_other_item');
		$data['user_info'] = $this->user_info;
		if($item)
		{
			$request = Tbl_item_merchant_request::where("merchant_item_id",$item->item_id)
					->join('tbl_warehouse', 'tbl_warehouse.warehouse_id', '=', 'tbl_item_merchant_request.merchant_warehouse_id')
					->join('tbl_user', 'tbl_user.user_id', '=', 'tbl_item_merchant_request.item_merchant_requested_by')
					->first();
			if(!$request)
			{
				dd("The item approve request does not exist");
			}
		}
		else
		{
			dd("The item you requested does not exist");
		}
		$data['item'] = $item;
		$data['request'] = $request;
		return view('member.item.item_approve',$data);
	}

	public function merchant_approve_request_post()
	{
		$item_merchant_request_id  = Request::input('item_merchant_request_id');

		$update['item_merchant_accepted_by'] = $this->user_info->user_id;
		$update['item_merchant_accepted_date'] = Carbon::now();
		$update['item_merchant_request_status'] = 'Approved'; 
		Tbl_item_merchant_request::where('item_merchant_request_id', $item_merchant_request_id)->update($update);

		$item_id = Request::input('item_id');

		$update_item['archived'] = 0;

		Tbl_item::where('item_id', $item_id)->update($update_item);

		$user_id = Tbl_item_merchant_request::where('item_merchant_request_id', $item_merchant_request_id)->value('item_merchant_requested_by');
		$item = Tbl_item::where('item_id', $item_id)->first();
		$user = Tbl_user::where('user_id', $user_id)->first();
		Merchant::set_per_piece_mark_up($item, $user);
		$data['response_status'] = 'success_approve';
		$data['message'] = 'success_approve';

		return json_encode($data);
	}

	public function merchant_decline_request($id)
	{
		
	}

	public function merchant_decline_request_post($id)
	{

	}
	public function print_new_item()
	{
		$data['owner'] = $this->user_info;
		$data['_new_item'] = Tbl_audit_trail::user()->where('audit_shop_id',Item::getShopId())->where('remarks','Added')->where('source','item')
									 ->whereBetween('tbl_audit_trail.created_at',[date('Y-m-d h:i:s',strtotime(Carbon::now())),date('Y-m-d h:i:s',strtotime(Carbon::now()->addDays(1)))])
									 ->leftjoin('tbl_item','item_id','=','source_id')
									 ->leftjoin('tbl_category','type_id','=','item_category_id')
									 ->leftjoin('tbl_item_type','tbl_item_type.item_type_id','=','tbl_item.item_type_id')
									 ->get();
		foreach ($data['_new_item'] as $key => $value) 
		{
			$data["_new_item"][$key]->conversion = UnitMeasurement::um_convertion($value->item_id);
			$um = Tbl_unit_measurement_multi::where("multi_um_id",$value->item_measurement_id)->where("is_base",0)->first();
			$data["_new_item"][$key]->inventory_count_um_view = 0;
			$data["_new_item"][$key]->item_whole_price = 0;
			$data["_new_item"][$key]->um_whole = "";
			if($um)
			{
				$data["_new_item"][$key]->inventory_count_um_view = UnitMeasurement::um_view($value->inventory_count,$value->item_measurement_id,$um->multi_id);
				
				$data["_new_item"][$key]->item_whole_price = $um->unit_qty * $value->item_price;
				$data["_new_item"][$key]->um_whole = $um->multi_abbrev;
			}
			if($value->item_type_id == 4)
			{
				$data["_new_item"][$key]->item_price = Item::get_item_bundle_price($value->item_id);
			}
			if($value->bundle_group == 1)
			{
				$data["_new_item"][$key]->item_type_name = "Group";
			}
		}

        $pdf = view('member.item.pis.print_new_item', $data);
        return Pdf_global::show_pdf($pdf);
	}
}