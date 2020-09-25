<?php
namespace App\Http\Controllers\Member;
use App\Globals\Item;
use App\Globals\Category;
use App\Globals\Manufacturer;
use App\Globals\Vendor;
use App\Globals\Accounting;
use App\Globals\Warehouse2;
use App\Globals\Columns;
use App\Globals\Utilities;
use App\Globals\LandingCost;
use App\Globals\UnitMeasurement;
use App\Globals\Settings;
use App\Globals\AccountingTransaction;
use Request;
use Session;


use App\Models\Tbl_token_list;
use App\Models\Tbl_item_token;
use App\Models\Tbl_item;
use App\Models\Tbl_columns;
use App\Models\Tbl_warehouse_reorder;

class ItemControllerV2 extends Member
{
	public function list()
	{
        $access = Utilities::checkAccess('item-list-v2', 'access_page');
        if($access == 1)
        { 
	 		$data["page"] 		 	= "Item List";
			$data["_item_type"]     = Item::get_item_type_list();
			$data["_item_category"] = Item::getItemCategory($this->user_info->shop_id);
			$data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');
			$data['perwarehouse_reorder'] = AccountingTransaction::settings($this->user_info->shop_id, 'perwarehouse_reorder');
			$data['enable_print_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_print_barcode');
			
			$data['shop_id']		= $this->user_info->shop_id;
			return view("member.itemv2.list_item", $data);
		}
        else
        {
            return $this->show_no_access();
        }
	}

	public function submit_checked_to_edit()
	{
		$access = Utilities::checkAccess('item-list-v2', 'edit_bulk_item');
        if($access == 1)
        { 
        	$data['ids'] = Request::input("ids");
        	if($data['ids'] != null)
        	{
	        	$applied_transaction = explode(',', $data['ids']);
	        	if(count($applied_transaction) > 0)
	        	{
	        		$data['get_info'] = null;
	        		foreach ($applied_transaction as $key => $value)
	        		{
	        			if($value != '')
	        			{
	  		      			$data['get_info'][$key] = Item::get_item_info($this->user_info->shop_id, $value);
	        			}
	        		}
	        	}
        	}
        	$data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');
        	$data["_item_category"] = Item::getItemCategory($this->user_info->shop_id);
        	$data['_um']        = UnitMeasurement::load_um();
			$data['action'] = "/member/item/v2/submit_bulk_item";
			
 			return view("member.itemv2.edit_bulk_item", $data);
		}
		else
		{
            return $this->show_no_access_modal();
		}
	}
	public function submit_edit_bulk_item()
	{
		$item_ids = Request::input("item_id");
		$item_info = null;
		$return = null;

		foreach ($item_ids as $key => $value)
		{
			if($value)
			{
				$update_bulk[$key]['item_id'] 		= Request::input("item_id")[$key];
				$update_bulk[$key]['item_sku'] 		= Request::input("item_sku")[$key];
				$update_bulk[$key]['item_barcode'] 	= Request::input("item_barcode")[$key];
				$update_bulk[$key]['item_name'] 	= Request::input("item_description")[$key];
				$update_bulk[$key]['item_price'] 	= str_replace(",","",Request::input("item_price")[$key]);
				$update_bulk[$key]['item_cost'] 	= str_replace(",","",Request::input("item_cost")[$key]);
				$update_bulk[$key]['item_type_id'] 	= Request::input("item_type_id")[$key];
				$update_bulk[$key]['item_mark_up'] 	= Request::input("item_price")[$key] - Request::input("item_cost")[$key];
				$update_bulk[$key]['item_category_id'] 		= Request::input("item_category")[$key];
				$update_bulk[$key]['item_measurement_id'] 	= Request::input("item_measurement_id")[$key];
				$update_bulk[$key]['item_reorder_point'] 	= Request::input("item_reorder_point")[$key];
				$update_bulk[$key]['item_sales_information']= Request::input("item_sales_information")[$key];
			}
		}
		$validate = Item::edit_bulk_validation($this->user_info->shop_id, $update_bulk);

        if(!$validate)
        {
        	Item::update_bulk_items($this->user_info->shop_id, $update_bulk);
        	$return['status'] = "success";	
			$return['message'] = "Success updating items";
			$return['call_function'] = "success_update_bulk_item";

			Session::forget('applied_transaction');
        }
        else
        {
            $return['status'] = 'error';
            $return['message'] = $validate;
        }
		return json_encode($return);
	}
	public function print_barcode()
	{
		$archived 			= Request::input("archived")/* ? 1 : 0*/;
		$item_type_id 		= Request::input("item_type_id");
		$item_category_id   = Request::input("item_category_id");
		$search				= Request::input("search");
		$warehouse_id 		= Warehouse2::get_current_warehouse($this->user_info->shop_id);

		Item::get_add_markup(); 
		Item::get_add_display();
		Item::get_filter_type($item_type_id);
		Item::get_filter_category($item_category_id);
		Item::get_search($search);
		Item::get_inventory($warehouse_id);
		Item::get_markup($this->user_info->shop_id);
		
		$data["archive"]	= $archived == 1 ? "restore" : "archive";

		
		$data["_item"]	= Item::get($this->user_info->shop_id, false, $archived);

		return view('member.itemv2.print_barcode', $data);
	}
	public function list_table()
	{
		$data['_applied'] = Session::forget('applied_transaction');
		
		$data["page"]		= "Item List - Table";

		$archived 			= Request::input("archived")/* ? 1 : 0*/;
		$item_type_id 		= Request::input("item_type_id");
		$item_category_id   = Request::input("item_category_id");
		$search				= Request::input("search");
		$warehouse_id 		= Warehouse2::get_current_warehouse($this->user_info->shop_id);

		Item::get_add_markup(); 
		Item::get_add_display();
		Item::get_filter_type($item_type_id);
		Item::get_filter_category($item_category_id);
		Item::get_search($search);
		Item::get_inventory($warehouse_id);
		Item::get_markup($this->user_info->shop_id);
		
		$data["archive"]	= $archived == 1 ? "restore" : "archive";

		
		$default = Tbl_columns::where('shop_id',$this->user_info->shop_id)->where('user_id',$this->user_info->user_id)->where('columns_from','item')->first();
		
		if(!$default)
		{
			$default[]   	 	= ["Item ID","item_id", true];
			$default[]   	 	= ["Item Thumbnail","item_img", true];
			$default[]   	 	= ["Item Name","item_name", true];
			$default[]   	 	= ["SKU", "item_sku", true];
			$default[]	  		= ["Price", "item_price", true];
			$default[]	  		= ["Cost", "item_cost", true];
			$default[]	  		= ["Markup", "item_mark_up", true];//
			$default[]	  		= ["Inventory", "inventory_count", true];
			$default[]	  		= ["U/M", "item_measurement_id", true];
		}
		else
		{
			$default = unserialize(($default->columns_data));
		}	
		$order_by 		= Request::input("order_by");
		$header_name 	= Request::input("header_name");

		$data["_item"]	= Item::get($this->user_info->shop_id, 10, $archived, true, $header_name, $order_by);
		
		$data["pagination"] = Item::get_pagination();
		$data["_item"]	  = Columns::filterColumns($this->user_info->shop_id, $this->user_info->user_id, "item", $data["_item"], $default);
		$data['user'] = $this->user_info;
		/*GET THE KEYS OF THE ARRAY*/
		// dd($data["_item"]);
		$data["item_key"] = array_keys($data["_item"]);
		return view("member.itemv2.list_item_table", $data);
	}
	public function get_item()
	{
		Session::forget('choose_item');
		/*$data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');*/
		$data['_service']  		 = Category::getAllCategory();
		$data['_inventory']  	 = Category::getAllCategory();
		$data['_noninventory']   = Category::getAllCategory();
		$data['_bundle']         = Category::getAllCategory();
		$data['_other_charge']   = Category::getAllCategory();
		$data["_income"] 		 = Accounting::getAllAccount('all',null,['Income','Other Income']);
		$data["_asset"] 		 = Accounting::getAllAccount('all', null, ['Other Current Asset','Fixed Asset','Other Asset']);
		$data["_expense"] 		 = Accounting::getAllAccount('all',null,['Expense','Other Expense','Cost of Goods Sold']);
		$data['default_income']  = Accounting::get_default_coa("accounting-sales");
		$data['default_asset']   = Accounting::get_default_coa("accounting-inventory-asset");
		$data['default_expense'] = Accounting::get_default_coa("accounting-expense");
		$data['_membership']	 = Item::get_membership();
		$data["_manufacturer"]   = Manufacturer::getAllManufaturer();
		//$data["_vendor"]   		 = Vendor::getAllVendor('active');
		$data["_um"] 			 = UnitMeasurement::load_um();
		$data['item_info'] 	     = [];
		$id 					 = Request::input('item_id');

		if($id)
		{
			$data['page_title'] 	  = "EDIT ITEM";
			$data['item_info'] 	      = Item::info($id);
			$data["link_submit_here"] = "/member/item/v2/edit_submit?item_id=" . $id;
			$data["item_picker"]	  = "hide";
			$data["item_button"]	  = "";
			$data['item_type']		  = Item::get_item_type_modify($data['item_info']->item_type_id);
			$data['_choose_item']	  = Item::get_choose_item($id);
			// patrick
			// for icoinsshop
			$token_item = Tbl_item_token::Token()->where('item_id',$id)->first();
			if($token_item)
			{
				$data['token_name']	= $token_item->token_name;
				$data['amount']		= $token_item->amount;
			}
			else
			{
				$data['token_name']	= '';
				$data['amount']		= '0';
			}
		}
		else
		{
			$data['page_title'] 	  = "CREATE NEW ITEM";
			$data["page"]			  = "Item Add";
			$data["link_submit_here"] = "/member/item/v2/add_submit";
			$data["item_picker"]	  = "";
			$data["item_button"]	  = "disabled";
			$data['item_type']		  = Item::get_item_type_modify();	
		}
		$data['shop_id']	= $this->user_info->shop_id;
		$data['tokens']		= Tbl_token_list::where('shop_id',$this->user_info->shop_id)->get();
		$data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');
		
		return $data;
	}
	public function submit_item($from)
	{
		$insert['item_name'] 				   = Request::input('item_description');
		$insert['item_sku'] 				   = Request::input('item_sku');
		$insert['item_barcode'] 			   = Request::input('item_barcode');
		$insert['item_category_id']			   = Request::input('item_category');
		$insert['item_img']				 	   = Request::input('item_img')  == null ? '' : Request::input('item_img');
		$insert['item_measurement_id'] 	 	   = Request::input('item_measurement_id')  == null ? '' : Request::input('item_measurement_id');
		$insert['item_price'] 				   = str_replace(",", "", Request::input('item_price'));
		$insert['item_income_account_id'] 	   = Request::input('item_income_account_id');
		$insert['item_sales_information']      = Request::input('item_sales_information') == null ? '' : Request::input('item_sales_information');
		$insert['item_manufacturer_id']        = Request::input('item_manufacturer_id') == null ? '' : Request::input('item_manufacturer_id');

		$insert['item_cost'] 				   = Request::input('item_cost') == null ? 0 : str_replace(",", "", Request::input('item_cost'));

		$insert['item_expense_account_id']	   = Request::input('item_expense_account_id');
		$insert['item_purchasing_information'] = Request::input('item_purchasing_information') == null ? '' : Request::input('item_purchasing_information');
		$insert['item_asset_account_id']       = Request::input('item_asset_account_id');
		$insert['has_serial_number']           = Request::input('item_has_serial') == null ? 0 : Request::input('item_has_serial');
		$insert['membership_id']       		   = Request::input('membership_id') == null ? 0 : Request::input('membership_id');
		$insert['gc_earning']         		   = Request::input('gc_earning')  == null ? 0 : Request::input('gc_earning');
		
		/*For inventory refill*/
		$insert['item_quantity'] 		  	   = Request::input('item_initial_qty') == null ? 0 : str_replace(",","",Request::input('item_initial_qty'));
		$insert['item_date_tracked'] 		   = Request::input('item_date_track') == null ? '' : Request::input('item_date_track');
		$insert['item_reorder_point'] 		   = Request::input('item_reorder_point') == null ? 0 : str_replace(",","",Request::input('item_reorder_point'));


		$shop_id = $this->user_info->shop_id;
		
		$item_type_id = Item::get_item_type_id(Request::input('item_type_id'));
		$item_type_id = Request::input("orig_item_type_id");
		// patrick
		// for icoinsshop
		if($shop_id == 87)
		{
			$token['token_id'] 	= Request::input('token_type');
			$token['amount']	= Request::input('token_amount');
		}
		if($from == "add")
		{
			$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
			$arr[0] = $warehouse_id;
			$insert['item_warehouse_id'] = $warehouse_id > 0 ? serialize($arr) : null;

			if($item_type_id <= 3)
			{
				$validate = Item::create_validation($shop_id, $item_type_id, $insert);

				if(!$validate)
				{
					if($shop_id == 87)
					{
						$return = Item::create($shop_id, $item_type_id, $insert, $token);
					}
					else
					{
						$return = Item::create($shop_id, $item_type_id, $insert);
					}
					
				}
				else
				{
					$return['message'] = $validate;
					$return['status'] = 'error';
				}
			}
			else
			{
				$_item = Session::get('choose_item');
				$validate = Item::create_bundle_validation($shop_id, $item_type_id, $insert, $_item);
				if(!$validate)
				{
					$return = Item::create_bundle($shop_id, $item_type_id, $insert, $_item);
				}
				else
				{
					$return['message'] = $validate;
					$return['status'] = 'error';
				}	
			}
		}
		elseif($from == "edit")
		{
			$get_item = Item::get_item_info($this->user_info->shop_id,Request::input("item_id"));
    		$get_old = $get_item->item_warehouse_id;

    		$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
    		
    		$count_wh = 0;
    		if($get_old)
    		{
    			$arr = unserialize($get_old);
    			$count_wh = count($arr);
    		}
			//$arr[$count_wh+1] = $warehouse_id;
			
			$arr[0] = $warehouse_id;
			$insert['item_warehouse_id'] = count($warehouse_id) > 0 ? serialize($arr) : null;
			$item_id 	  = Request::input("item_id");
			if($item_type_id <= 3 || $item_type_id == 6)
			{
				$validate = Item::create_validation($shop_id, $item_type_id, $insert, $item_id);
				if(!$validate)
				{
            		$get_old_data = Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->first();
					if($shop_id == 87)
					{
						$return = Item::modify($shop_id, $item_id, $insert, $token);
					}
					else
					{
						$return = Item::modify($shop_id, $item_id, $insert, null, $this->user_info->user_id);
					}

            		if($get_old_data)
            		{
            			$old['item_price'] = $get_old_data->item_price;
            			$old['item_cost'] = $get_old_data->item_cost;
            			$new['item_price'] =$insert['item_price']; 
            			$new['item_cost'] = $insert['item_cost'];
            			Item::record_item_pricing_history($this->user_info->shop_id, $this->user_info->user_id, $item_id, $old, $new);
            		}
				}
				else
				{
					$return['message'] = $validate;
					$return['status'] = 'error';
				}
			}
			else
			{
				$_item = Session::get('choose_item');
				$validate = Item::create_bundle_validation($shop_id, $item_type_id, $insert, $_item);
				if(!$validate)
				{
            		$get_old_data = Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->first();
					$return = Item::modify_bundle($shop_id, $item_id, $insert, $_item);
            		if($get_old_data)
            		{
            			$old['item_price'] = $get_old_data->item_price;
            			$old['item_cost'] = $get_old_data->item_cost;
            			$new['item_price'] =$insert['item_price']; 
            			$new['item_cost'] = $insert['item_cost'];
            			Item::record_item_pricing_history($this->user_info->shop_id, $this->user_info->user_id, $item_id, $old, $new);
            		}
				}
				else
				{
					$return['message'] = $validate;
					$return['status'] = 'error';
				}
			}
		}

		return $return;
	}
	public function add_item()
	{
		$access = Utilities::checkAccess('item-list-v2', 'add');
        if($access == 1)
        { 
			$data = $this->get_item();
			$data['_settings'] = Settings::load_settings($this->user_info->shop_id);
			$data['_shop_id'] = $this->user_info->shop_id;
			return view("member.itemv2.add_item",$data);
		}
		else
		{

            return $this->show_no_access_modal();
		}
	}
	public function add_item_submit()
	{
		$return = $this->submit_item("add");		

		return json_encode($return);
	}
	public function edit_item()
	{
		$access = Utilities::checkAccess('item-list-v2', 'edit');
        if($access == 1)
        { 
			$data = $this->get_item();

			$data['_settings'] = Settings::load_settings($this->user_info->shop_id);
			return view("member.itemv2.add_item",$data);
		}
		else
		{
            return $this->show_no_access_modal();
		}
	}
	
	public function edit_item_submit()
	{
		$return = $this->submit_item("edit");

		return json_encode($return);
	}
	public function item_cost_change()
	{
		$data['new_cost'] = Request::input("new_cost") != null ? round(Request::input("new_cost"),2) : 0;
		$data['item_data'] = Item::info(Request::input("d"));
		$data['action'] = "/member/item/v2/submit_item_cost_change";
		$data['item_id'] = Request::input("d");

		return view("member.itemv2.item_change_cost", $data);
	}
	public function submit_item_cost_change()
	{
		$item_id = Request::input("item_id");

		$new_price = round(Request::input("new_price"),2);
		$new_cost = round(Request::input("new_cost"),2);
		$change_price = Request::input("change_price");
		$change_cost = Request::input("change_cost");
		$update = null;
		if($change_cost == 'yes')
		{
			$update['item_cost'] = $new_cost;

			if($change_price == 'yes' )
			{
				$update['item_price'] = $new_price;
			}
		}
		if($update)
		{
			Item::update_item_price($this->user_info->shop_id, $this->user_info->user_id, $item_id, $update);
		}
		$return['call_function'] = "success_update_price";
		$return['status'] = "success";

		return json_encode($return);
	}
	public function cost()
	{
		$data["page"]		= "Item Cost";
		$data["_landing_cost"] = LandingCost::get($this->user_info->shop_id);
		$data['_created_cost'] = null;
		$data['new_cost'] = Request::input("new_cost") != null ? round(Request::input("new_cost"),2) : 0;
		if(count(session('landing_cost')) > 0)
		{
			$data["_created_cost"] = session('landing_cost');
		}
		elseif(Request::input('d'))
		{
			$data['_created_cost'] = LandingCost::get_cost($this->user_info->shop_id, Request::input('d'));
		}

		$data["action"] = "/member/item/v2/create_cost";
		return view("member.itemv2.cost", $data);
	}
	public function create_cost()
	{
		$cost_name   = Request::input("cost_name");
		$cost_type   = Request::input("cost_type");
		$cost_rate   = Request::input("cost_rate");
		$cost_amount = Request::input("cost_amount");

		$data = null;
		$return = null;
		$total_amount = 0;
		foreach ($cost_name as $key => $value)
		{
			if($value)
			{
				$data[$key]['landing_cost_name']    = $value;
				$data[$key]['landing_cost_shop_id'] = $this->user_info->shop_id;
				$data[$key]['landing_cost_type']    = $cost_type[$key];
				$data[$key]['landing_cost_rate'] 	= $cost_rate[$key];
				$data[$key]['landing_cost_amount']  = str_replace(',', '', $cost_amount[$key]);
				$total_amount += $data[$key]['landing_cost_amount'];
			}
		}
		if(count($data) > 0)
		{
			session(['landing_cost' => $data]);
			$return['status'] = "success";
			$return['call_function'] = "success_landing_cost";
			$return['total_amount'] = $total_amount;
		}

		return json_encode($return);
	}
	public function price_level()
	{
		$data["page"]		= "Item Price Level";
		return view("member.itemv2.price_level");
	}
	public function archive()
	{
		$item_id = Request::input("item_id");
		if ($item_id) 
		{
			$return = null;
			$qty = Warehouse2::get_item_qty(null, $item_id);
			if($qty > 0)
			{
				$return['status'] = "error";
				$return['status_message'] = "You have quantity for this item. Kindly zero out the item's quantity in Adjust Inventory";
			}
			else
			{
				Item::archive($this->user_info->shop_id, $item_id);
				$return = "success";
			}
		}

		return json_encode($return);
	}
	public function restore()
	{
		$item_id = Request::input("item_id");
		if ($item_id) 
		{
			Item::restore($this->user_info->shop_id, $item_id);
		}

		echo json_encode("success");
	}
	public function columns()
	{
		if (Request::isMethod('post'))
		{
			$shop_id = $this->user_info->shop_id;
			$user_id = $this->user_info->user_id;
			$from	 = "item";
			$column  = Request::input("column");
			$result = Columns::submitColumns($shop_id, $user_id, $from, $column);
			if($result)
			{
				$response["response_status"] = "success";
				$response["message"] = "Column has been saved.";
				$response["call_function"] = "columns_submit_done";
			}
			else
			{
				$response["response_status"] = "error";
				$response["message"] = "Some error occurred.";
			}

			return json_encode($response);
		}
		else
		{
			$data["page"] 	 = "Item Columns";
			$shop_id 	  	 = $this->user_info->shop_id;
			$user_id	  	 = $this->user_info->user_id;
			$from    	  	 = "item";
			$data["_column"] = Columns::getColumns($shop_id, $user_id, $from);
			$data['count_column'] = count($data["_column"]);
			return view("member.itemv2.columns_item", $data);
		}
	}
	public function choose()
	{
		$data['_item_to_bundle']	= Item::get_all_category_item([1,2,3]);
		$data['choose_item_submit']	 = "/member/item/choose/submit";
		return view("member.itemv2.choose",$data);
	}
	public function choose_submit()
	{
		$id = Request::input("item_id");
		$qty = Request::input("quantity");
		$info = Item::info($id);

		$return['status'] = null;
		$return['message'] = null;

		$data = Session::get('choose_item'); 
		if($info)
		{
			$data[$id]['item_id'] = $id;
			$data[$id]['item_sku'] = $info->item_sku;
			$data[$id]['item_price'] = $info->item_price;
			$data[$id]['item_cost'] = $info->item_cost;
			$data[$id]['quantity'] = $qty;

			Session::put('choose_item',$data);

			$return['status'] = 'success';
			$return['call_function'] = 'success_choose_item';
		}
		else
		{
			$return['status'] = 'error';
			$return['message'] = "Item doesn't exist";
		}

		return json_encode($return);
	}
	public function load_item()
	{
		$data['_choose_item'] = Session::get('choose_item');

		return view('member.load_ajax_data.load_choose_item',$data);
	}
	public function remove_item()
	{
		$id = Request::input('item_id');

		$data = Session::get('choose_item');
		unset($data[$id]);
		Session::put('choose_item',$data);

		return 'success';
	}
	public function refill_item()
	{		
		$access = Utilities::checkAccess('item-list-v2', 'refill-item');
        if($access == 1)
        { 
			$item_id = Request::input('item_id');
			$data['item'] = Item::info($item_id);
			$data['refill_submit'] = '/member/item/v2/refill_submit';

			return view('member.itemv2.refill_item',$data);
		}
		else
		{

            return $this->show_no_access_modal();
		}
	}
	public function refill_submit()
	{
		$item_id = Request::input('item_id');
		$quantity = Request::input('quantity');
		$remarks = Request::input('remarks');
		$shop_id = $this->user_info->shop_id;
		$warehouse_id = Warehouse2::get_current_warehouse($shop_id);

		$validate = Warehouse2::refill_validation($shop_id, $warehouse_id, $item_id, $quantity, $remarks);
		$itemdata = Item::info($item_id);
    	if(!$validate && $itemdata)
    	{
    		$source['name'] = 'initial_qty';
            $source['id'] = $item_id;
            $source['item_cost'] = $itemdata->item_cost;
            $source['item_price'] = $itemdata->item_price;

    		$return = Warehouse2::refill($shop_id, $warehouse_id, $item_id, $quantity, $remarks, $source);
    		$return['call_function'] = 'success_refill';
    		$return['status'] = 'success';
    	}
    	else
    	{
    		$return['status'] = 'error';
    		$return['message'] = $validate;
    	}

    	return json_encode($return);
	}
	public function warehouse_reorder()
	{
		$warehouse_id 			= Request::input('w_id');
		$data['page'] 			= 'Reorder point per warehouse';
        $data['_item']      	= Item::get_item_warehouse_reorder($this->user_info->shop_id,[1], $warehouse_id);
		$data["_item_category"] = Item::getItemCategory($this->user_info->shop_id);		
        $data['warehouse']		= Warehouse2::get_info($warehouse_id);
	    $data["action"]			= "/member/item/v2/warehouse_reorder_submit";

		return view('member.itemv2.warehouse_reorder', $data);
	}
	public function warehouse_reorder_submit()
	{
		$return = null;
		$ins = array();
		$warehouse_id 	= Request::input('warehouse_id');
        $warehouse		= Warehouse2::get_info($warehouse_id);
        if($warehouse)
        {
			foreach (Request::input('reorder') as $key => $value) 
			{
				$ins[$key]['wr_item_id'] = $key;
				$ins[$key]['wr_warehouse_id'] = $warehouse_id;
				$ins[$key]['warehouse_reorder'] = $value;
			}
			if(count($ins) > 0)
			{
				Tbl_warehouse_reorder::where('wr_warehouse_id', $warehouse_id)->delete();
				Tbl_warehouse_reorder::insert($ins);
				$return['status'] = 'success';
	            $return['status_message'] = 'Success setting reorder point per warehouse';
	            $return['call_function'] = 'success_warehouse_reorder';
			}
        }
        else
        {
			$return['status'] = 'error';
            $return['status_message'] = 'Warehouse not found!';
        }
        return json_encode($return);
	}
	public function add_token()
	{
		$data['page'] = 'Add Token';
		return view('member.itemv2.add_token',$data);
	}
	public function add_token_submit()
	{
		$insert['token_name'] 	= Request::input('token_name');
		$insert['shop_id']		= $this->user_info->shop_id;
		if($insert['token_name'] != '')
		{
			$query = Tbl_token_list::insert($insert);
			$response['call_function'] = 'success';
		}
		else
		{
			$response['call_function'] = 'error_name';
		}
		return json_encode($response);
	}
	public function update_token()
	{
		$data['page'] = 'Update Token';
		$token = Tbl_token_list::where('token_id',request('id'))->first();
		$data['token_name'] = $token->token_name;
		$data['token_id']	= $token->token_id;
		return view('member.itemv2.update_token',$data);
	}
	public function update_token_submit()
	{
		$update['token_name'] = Request::input('token_name');
		if($update['token_name'] != '')
		{
			$query = Tbl_token_list::where('token_id',Request::input('token_id'))->update($update);
			$response['call_function'] = 'success';
		}
		else
		{
			$response['call_function'] = 'error_name';
		}
		return json_encode($response);
	}
	public function get_token_list()
	{
		$data['page'] = 'Token List';
		return view('member.itemv2.token_list',$data);
	}
	public function token_list_table()
	{
		$activetab = request('activetab');
		$data['tokens'] = Tbl_token_list::where('archived',$activetab)->get();
		return view('member.itemv2.token_list_table',$data);
	}
	public function token_list_archived()
	{
		$update['archived'] = request('archived');
		Tbl_token_list::where('token_id',request('token_id'))->update($update);
	}
}