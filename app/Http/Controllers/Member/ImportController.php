<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_chart_account_type;
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
use App\Models\Tbl_user;
use App\Models\Tbl_country;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_attachment;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_vendor;
use App\Models\Tbl_vendor_address;
use App\Models\Tbl_vendor_other_info;

use App\Globals\AccountingTransaction;
use App\Globals\Category;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\DigimaTable;
use App\Globals\Item;
use App\Globals\Vendor;
use App\Globals\UnitMeasurement;
use App\Globals\Utilities;
use App\Globals\Warehouse;
use App\Globals\Warehouse2;

use Carbon\Carbon;
use Request;
use Image;
use Validator;
use Redirect;
use File;
use URL;
use DB;
use Crypt;
use Session;
use Excel;

/**
 * Import Item Module - all importation related module
 * 
 * @author Bryan Kier Aradanas
 */

class ImportController extends Member
{
	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	/**
	 * Start of Item Module
	 * 
	 */

	public function getItem()
	{

		return view('member.import.item_import');
	}
	public function getInventoryAdjustment()
	{
		$data['_warehouse'] = Tbl_warehouse::where("warehouse_shop_id", $this->getShopId())->where("archived",0)->get()->toArray();
		return view('member.import.inventory_adjustment', $data);
	}

	public function getItemBundle()
	{
		Session::forget("bundle_id");
		Session::forget("item_bundle_id");
		return view('member.import.item_import_bundle');
	}
	public function getItemBundleTemplate()
	{
		Excel::create("ItemBundleTemplate", function($excel)
		{
			// Set the title
		    $excel->setTitle('Digimahouse');

		    // Chain the setters
		    $excel->setCreator('DigimaWebSolutions')
		          ->setCompany('DigimaWebSolutions');

		    $excel->sheet('Template', function($sheet) {
		    	$header = [
		    				'Type',
		    				'Name',
		    				'Sku',
		    				'UM',
		    				'Qty In Bundle',
		    				'Category',
		    				'Sales Information',
		    				'Sales Price',
		    				'Income Account',
		    				'Sale to Customer',
		    				'Purchase From Supplier',
		    				'Purchasing Information',
		    				'Purchase Cost',
		    				'Expense Account',
		    				'Barcode',
		    				'Qty on Hand',
		    				'Reorder Point',
		    				'As of Date',
		    				'Asset Account',
		    				'Packing Size',
		    				'Manufacturer'
		    				];
		    	$sheet->freezeFirstRow();
		        $sheet->row(1, $header);

		    });


		})->download('csv');
	}

	public function getItemTemplate()
	{
		Excel::create("ItemTemplate", function($excel)
		{
			// Set the title
		    $excel->setTitle('Digimahouse');

		    // Chain the setters
		    $excel->setCreator('DigimaWebSolutions')
		          ->setCompany('DigimaWebSolutions');

		    $excel->sheet('Template', function($sheet) {
		    	$header = [
		    				'Type',
		    				'Name',
		    				'Sku',
		    				'UM',
		    				'Category',
		    				'Sales Information',
		    				'Sales Price',
		    				'Income Account',
		    				'Sale to Customer',
		    				'Purchase From Supplier',
		    				'Purchasing Information',
		    				'Purchase Cost',
		    				'Expense Account',
		    				'Barcode',
		    				'Qty on Hand',
		    				'Reorder Point',
		    				'As of Date',
		    				'Asset Account',
		    				'Packing Size',
		    				'Manufacturer'
		    				];
		    	$sheet->freezeFirstRow();
		        $sheet->row(1, $header);

		    });


		})->download('csv');
	}
	public function getInventoryAdjustmentTemplate()
	{
		Excel::create("InventoryAdjustment", function($excel)
		{
			// Set the title
		    $excel->setTitle('Digimahouse');

		    // Chain the setters
		    $excel->setCreator('DigimaWebSolutions')
		          ->setCompany('DigimaWebSolutions');

		    $excel->sheet('Template', function($sheet) {
				$get = Tbl_warehouse::where("warehouse_shop_id", $this->getShopId())->where("archived",0)->get()->toArray();
		    	$header[0] = 'Name';
				$header[1] = 'Sku';
				$header[2] = 'Um';

				foreach ($get as $key => $value) 
				{
					$header[$key+3] = $value['warehouse_name'];
				}
		    	$sheet->freezeFirstRow();
		        $sheet->row(1, $header);

		        $_get_item = Tbl_item::where("shop_id", $this->getShopId())->where("archived",0)->whereIn("item_type_id",[1,4,5])->get();
				foreach ($_get_item as $key => $value) 
				{
					$row_item	 = null;
					$row_item[0] = $value->item_name;
					$row_item[1] = $value->item_sku;
					$row_item[2] = '';
					foreach ($get as $keywh => $valuewh) 
					{
						$row_item[$keywh+3] = Tbl_item::inventorynew($valuewh['warehouse_id'])->where("item_id", $value->item_id)->value("inventory_count");
					}
		        	$sheet->row($key+2, $row_item);
				}		        
		    });


		})->download('csv');
	}

	public function getAdjustmentExportError()
	{
		$_value = Session::get("import_adjustment_error");

		if($_value)
		{
			Excel::create("AdjsutmentImportError", function($excel) use($_value)
			{
				// Set the title
			    $excel->setTitle('Digimahouse');

			    // Chain the setters
			    $excel->setCreator('DigimaWebSolutions')
			          ->setCompany('DigimaWebSolutions');

			    $excel->sheet('Template', function($sheet) use($_value) {
				   	$get = Tbl_warehouse::where("warehouse_shop_id", $this->getShopId())->where("archived",0)->get()->toArray();
			    	$header[0] = 'Name';
					$header[1] = 'Sku';
					$header[2] = 'Um';
					foreach ($get as $key => $value) 
					{
						$header[$key+3] = $value['warehouse_name'];
					}
			    	$sheet->freezeFirstRow();
			        $sheet->row(1, $header);
			        foreach($_value as $key => $value)
			        {
			        	$sheet->row($key+2, $value);
			        }

			    });


			})->download('csv');
		}
		else
		{
			return Redirect::back();
		}
	}

	public function postInventoryAdjustmentReadFile()
	{		
		Session::forget("import_adjustment_error");

		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$data['name']	= isset($value["Name"])	? $value["Name"] : '';
			$data['sku'] 	= isset($value["Sku"]) ? $value["Sku"] : '';
			$data['um'] 	= isset($value["Um"]) ? $value["Um"] : '';
			$get = Tbl_warehouse::where("warehouse_shop_id", $this->getShopId())->where("archived",0)->get()->toArray();
			$warehouse_qty = [];
			foreach ($get as $keywh => $valuewh) 
			{
				$warehouse_qty[$valuewh['warehouse_id']] = isset($value[$valuewh['warehouse_name']]) ? $value[$valuewh['warehouse_name']] : '';
			}

			$chck_item = Tbl_item::where("shop_id", $this->getShopId())
								 ->where("item_name", $data['name'])
								 ->where("item_sku", $data['sku'])
								 ->first();

			if($chck_item)
			{
				$ret_um = true;
				if($data['um'])
				{
					$chck_um = Tbl_unit_measurement::multi()->where("um_shop", $this->getShopId())
												   ->where("um_id", $chck_item->item_measurement_id)
												   ->where("multi_name", $data['um'])
												   ->first();
					if(!$chck_um)
					{
						$ret_um = false;
					}
				}
				if($ret_um)
				{
					foreach ($warehouse_qty as $keywh_qty => $new_qty)
					{
						if($new_qty != '')
						{
							$curr_qty = Warehouse2::get_item_qty($keywh_qty, $chck_item->item_id);
							$qty = doubleval($new_qty) - $curr_qty;
							$_item[0]['item_um'] = $data['um'];
							$_item[0]['item_id'] = $chck_item->item_id;
					        $_item[0]['item_qty'] = $qty;
					        $_item[0]['item_description'] = 'Adjust Inventory through Importation';
					        $_item[0]['item_rate'] = $chck_item->item_cost;
					        $_item[0]['bin_location'] = 0;
					        $_item[0]['item_sub_warehouse'] = 0;
					        
							if($qty > 0)
							{
								AccountingTransaction::refill_inventory($this->getShopId(), $keywh_qty, $_item, 'import_adjust_inventory', $chck_item->item_id, 'Adjust Inventory through Importation');
							}
							else
							{
								AccountingTransaction::consume_inventory($this->getShopId(), $keywh_qty, $_item, 'import_adjust_inventory', $chck_item->item_id, 'Adjust Inventory through Importation');
							}
						}
					}
					$json["status"]		= "success";
					$json["message"]	= "Item Successfully adjusted";
				}
				else
				{
					$json["status"]		= "warning";
					$json["message"]	= "Unit of Measurement Not Found";
				}
			}
			else
			{
				$json["status"]		= "warning";
				$json["message"]	= "Item Not Found";
			}

			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['name']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['sku']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['um']."</td>";
			foreach ($warehouse_qty as $keywh_qty => $new_qty)
			{
				$json["tr_data"]   .= "<td nowrap>".$new_qty."</td>";
			}
			$json["tr_data"]	.= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}
		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_adjustment_error", $error_data);
			$json["status"] = "end";
		}
		return json_encode($json);
	}
	public function get_string($string)
	{
		return str_replace(" ", "_", strtolower($string));
	}
	public function postItemBundleReadFile()
	{
		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$data['type']					= isset($value["Type"]) 				? strToUpper($value["Type"]) : '';			//1,2,3
			$data['name'] 					= isset($value["Name"]) 				? $value["Name"] : '';					  	//1,2,3
			$data['sku'] 					= isset($value["Sku"]) 					? $value["Sku"] : '';				      	//1,2,3
			$data['um'] 					= isset($value["UM"]) 					? $value["UM"] : '';	

			$data['qty_in_bundle']			= isset($value["Qty In Bundle"])		? $value["Qty In Bundle"] : '';					  	//1,2,3
			$data['category'] 				= isset($value["Category"]) 			? $value["Category"] : '';				  	//1,2,3
			$data['sales_information'] 		= isset($value["Sales Information"]) 	? $value["Sales Information"] : '';		  	//1,2,3
			$data['sales_price'] 			= isset($value["Sales Price"]) 			? $value["Sales Price"] : 0;			  	//1,2,3
			$data['income_account'] 		= isset($value["Income Account"]) 		? $value["Income Account"] : '';		  	//1,2,3
			$data['sale_to_customer'] 		= isset($value["Sale to Customer"]) 	? $value["Sale to Customer"] : '';        	//2,3
			$data{'purchase_from_supplier'} = isset($value["Purchase From Supplier"]) ? $value["Purchase From Supplier"] : '';	//2,3
			$data['purchasing_information'] = isset($value["Purchasing Information"]) ? $value["Purchasing Information"] : '';	//3
			$data['purchase_cost'] 			= isset($value["Purchase Cost"]) 		? $value["Purchase Cost"] : 0; 			//3
			$data['expense_account'] 		= isset($value["Expense Account"]) 		? $value["Expense Account"] : '';			//3
			$data['barcode'] 				= isset($value["Barcode"]) 				? $value["Barcode"] : '';					//3
			$data['qty_on_hand'] 			= isset($value["Qty on Hand"]) 			? $value["Qty on Hand"] : 0;				//3
			$data['reorder_point'] 			= isset($value["Reorder Point"]) 		? $value["Reorder Point"] : 0;				//3
			$data['as_of_date'] 			= isset($value["As of Date"]) 			? $value["As of Date"] : '';				//3
			$data['asset_account'] 			= isset($value["Asset Account"]) 		? $value["Asset Account"] : '';				//3
			$data['packing_size'] 			= isset($value["Packing Size"]) 		? $value["Packing Size"] : '';				//3
			$data['manufacturer'] 			= isset($value["Manufacturer"]) 		? $value["Manufacturer"] : '';				//3

			$arr = ['10g','15g','25g','90g','135g','150g','500g','1kg','60ml','1L','120ml','30ml','50ml','100ml','5g'
		,'15ml','5ml','8ml','1gal','100ml','100g','200ml','300g','20ml'];
			$name_explode = explode(" ", $data['name']);
			if(isset($name_explode[1]))
			{
				$res = in_array($name_explode[0], $arr);
				if($res)
				{
					$data['name'] = ucwords(str_replace($name_explode[0], "", $data['name'])) . " " .$name_explode[0];
				}
			}
			
			/* Import Setting */
			$auto_category			= isset($input["category"]) 		? $input["category"] : NULL;
			$auto_income_account	= isset($input["income_account"]) 	? $input["income_account"] : NULL;
			$auto_expense_account	= isset($input["expense_account"]) 	? $input["expense_account"] : NULL;
			$auto_asset_account		= isset($input["asset_account"]) 	? $input["asset_account"] : NULL;
			$auto_manufacturer		= isset($input["manufacturer"]) 	? $input["manufacturer"] : NULL;

			/* Validation */
			$duplicate_item		= Tbl_item::where("shop_id", $this->getShopId())->where("item_name", $data['name'])->first();
			$has_Category 		= Tbl_category::where("type_name", $data['category'])->where("type_shop", $this->getShopId())->first();
			$has_Income_Account = Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['income_account'])->first();
			$has_Expense_Account= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['expense_account'])->first();
			$has_Asset_Account 	= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['asset_account'])->first();
			$has_UM 			= Tbl_unit_measurement::where("um_shop", $this->getShopId())->where("um_name", $data['um'])->first();
			$has_Manufacturer 	= Tbl_manufacturer::where("manufacturer_shop_id", $this->getShopId())->where("manufacturer_name", $data['manufacturer'])->first();

	
			if($has_Category || $auto_category)
			{
				if($has_Manufacturer || $auto_manufacturer || $data['manufacturer'] == '')
				{
					/* CHECK CATEGORY */
					if($has_Category == null && $auto_category && $data['category'])
					{
						$data['category'] = $this->create_category($data['category']);
					}
					else
					{
						$data['category'] = $has_Category->type_id;
					}

					/* CHECK INCOME ACCOUNT */
					if($data['income_account'] == '') // DEFAULT
					{
						$data['income_account'] = Tbl_chart_of_account::where("account_code", "accounting-sales")
								->where("account_shop_id", $this->getShopId())->value("account_id");
					}
					elseif(!$has_Income_Account && $auto_income_account && $data['income_account'] != '')
					{
						$data['income_account'] = $this->create_income_account($data['income_account']);
					}
					else
					{
						$data['income_account'] = $has_Income_Account->account_id;
					}
					if($duplicate_item)
					{
						// $item_id 		 = Tbl_item::insertGetId($insert);
						// if($data['type'] == "BUNDLE")
						// {
							$json["status"]		= "error";
							$json["message"]	= "Duplicate Item";

							$item_array = Session::get("item_bundle_id");
							$item_array[$duplicate_item->item_id] = $data['qty_in_bundle'];
							Session::put("item_bundle_id", $item_array);
						// }
						// else
						// {
							// /*CHECK IF HAVE CURRENT SESSIONS*/
							// if(Session::get("item_bundle_id"))
							// {
							// 	/*INSERT BUNDLE HERE*/
							// 	Item::import_create_bundle(Session::get("bundle_id"),Session::get("item_bundle_id"));
							// 	Session::put("item_bundle_id",null);
							// }

							// $json["status"]		= "success";
							// $json["message"]	= "Success";
						// }
					}
					else
					{
						if($data['type'] == "BUNDLE" || $data['type'] == "INVENTORY" || $data['type'] == "NON-INVENTORY" || $data['type'] == "SERVICE")
						{
							/** INSERT ITEM HERE **/
							$insert["shop_id"]				      	  = $this->getShopId();
							$insert["item_name"]				      = $data['name'];
							$insert["item_sku"]					      = strtoupper($data['sku']);
							$insert["item_category_id"]			      = $data['category'];
							$insert["item_sales_information"] 	      = $data['sales_information'];
							$insert["item_price"] 				      = $data['sales_price'];
							$insert["item_income_account_id"]         = $data['income_account'];
							$insert["item_date_created"]         	  = Carbon::now();

							$message = [];

							if($data['type'] == "INVENTORY")
							{
								/*  Check Expense Account */
								if($data['expense_account'] == '') // DEFAULT
								{
									$data['expense_account'] = Tbl_chart_of_account::where("account_code", "accounting-expense")
									->where("account_shop_id", $this->getShopId())->value("account_id");
								}
								elseif(!$has_Expense_Account && $auto_expense_account && $data['expense_account'] != '')
								{
									$data['expense_account'] = $this->create_expense_account($data['expense_account']);
								}
								else
								{
									$data['expense_account'] = $has_Expense_Account->account_id;
								}

								/* Check Asset Account */
								if($data['asset_account'] == '') // DEFAULT
								{
									$data['asset_account'] = Tbl_chart_of_account::where("account_code", "accounting-inventory-asset")
									->where("account_shop_id", $this->getShopId())->value("account_id");
								}
								elseif(!$has_Asset_Account && $auto_asset_account && $data['asset_account'] != '')
								{
									$data['asset_account'] = $this->create_asset_account($data['asset_account']);
								}
								elseif($has_Asset_Account)
								{
									$data['asset_account'] = $has_Asset_Account->account_id;
								}

								/* Check Manufacturer Account */
								if(!$has_Manufacturer && $auto_manufacturer && $data['manufacturer'] != '')
								{
									$data['manufacturer'] = $this->create_manufacturer($data['manufacturer']);
								}
								elseif($has_Manufacturer)
								{
									$data['manufacturer'] = $has_Manufacturer->manufacturer_id;
								}

								$insert["item_type_id"]				      = 1;
								$insert["item_purchasing_information"]    = $data['purchasing_information'];
								$insert["item_cost"]				      = $data['purchase_cost'];
								$insert["item_expense_account_id"]	      = $data['expense_account'];
								$insert["item_barcode"]				      = $data['barcode'] != '' ? $data['barcode'] : $insert["item_sku"];
								$insert["item_quantity"]		          = $data['qty_on_hand'] == '' ? 0 : $data['qty_on_hand'];
								$insert["item_reorder_point"] 		      = $data['reorder_point'];
								$insert["item_date_tracked"]	          = datepicker_input($data['as_of_date']);
								$insert["item_asset_account_id"]          = $data['asset_account'];
								$insert["packing_size"]				      = $data['packing_size'];
								$insert["item_manufacturer_id"]	      	  = $data['manufacturer'];

								$rules["item_quantity"]				      = 'required|numeric';
								$rules["item_cost"]					      = 'required|numeric';
								$rules["item_expense_account_id"]		  = 'required';
								$rules["item_asset_account_id"]			  = 'required';
								
								if($data['qty_on_hand'] > 0)
								{
									$rules["item_date_tracked"] 		  = 'required|date';
									$message["item_date_tracked.required"]= "The As of Date field is required when Qty on Hand is not empty";
									$message["item_date_tracked.date"]	  = "The As of Date field should be in Date format";
								}
							}
							elseif($data['type'] == "NON-INVENTORY")
							{
								$insert["item_type_id"]				      = 2;
								$insert["item_sale_to_customer"]		  = isset($data['sale_to_customer']) ? $data['sale_to_customer'] : 0;
								$insert["item_purchase_from_supplier"]	  = isset($data{'purchase_from_supplier'}) ? $data{'purchase_from_supplier'} : 0;
								
								$rules["item_sale_to_customer"]		  	  = 'required|numeric|min:0|max:1';
								$rules["item_purchase_from_supplier"]  	  = 'required|numeric|min:0|max:1';
				
							}
							elseif($data['type'] == "SERVICE")
							{
								$insert["item_type_id"]				      = 3;
								$insert["item_sale_to_customer"]		  = isset($data['sale_to_customer']) ? $data['sale_to_customer'] : 0;
								$insert["item_purchase_from_supplier"]	  = isset($data{'purchase_from_supplier'}) ? $data{'purchase_from_supplier'} : 0;
								
								$rules["item_sale_to_customer"]		      = 'required|numeric|min:0|max:1';
								$rules["item_purchase_from_supplier"]     = 'required|numeric|min:0|max:1';
							}
							elseif($data['type'] == "BUNDLE")
							{
								$insert["item_type_id"]				      = 4;
								$insert["item_barcode"]				      = $data['barcode'];

							}

							$rules["item_name"]					      = 'required';
							$rules["item_sku"]					      = 'required';
							$rules["item_price"]				      = 'required|numeric';
							$rules["item_income_account_id"]		  = 'required';

							$validator = Validator::make($insert, $rules, $message);
							if ($validator->fails())
							{
								$json["status"] 	= "error";
								$json["message"]  	= $validator->errors()->first();
							}
							else
							{
								/* INSERT ITEM */ 
								$item_id 		 = Tbl_item::insertGetId($insert);
								if($data['type'] == "BUNDLE")
								{
									Session::put("bundle_id", $item_id);
								}
								else
								{
									$item_array = Session::get("item_bundle_id");
									$item_array[$item_id] = $data['qty_in_bundle'];
									Session::put("item_bundle_id", $item_array);
								}

								/*CHECK IF HAVE CURRENT SESSIONS*/
								if(Session::get("item_bundle_id"))
								{
									/*INSERT BUNDLE HERE*/
									Item::import_create_bundle(Session::get("bundle_id"),Session::get("item_bundle_id"));
									Session::put("item_bundle_id",null);
								}
								AuditTrail::record_logs("Added", "item", $item_id , "", serialize($insert));
								$json["status"]		= "success";
								$json["message"]	= "Success";
							}
						}
						else
						{
							$json["status"]		= "error";
							$json["message"]	= "Item Type Unknown";
						}
					}
				}
				else
				{
					$json["status"]		= "error";
					$json["message"]	= "Manufacturer Not Found";
				}
			}
			else
			{
				$json["status"]		= "error";
				$json["message"]	= "Category Not Found";
			}
			
			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['type']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['name']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['sku']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['qty_in_bundle']."</td>";
			$json["tr_data"]   .= "<td>".$data['um']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['category']."</td>";
			$json["tr_data"]   .= "<td>".$data['sales_information']."</td>";
			$json["tr_data"]   .= "<td>".$data['sales_price']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['income_account']."</td>";
			$json["tr_data"]   .= "<td>".$data['sale_to_customer']."</td>";
			$json["tr_data"]   .= "<td>".$data{'purchase_from_supplier'}."</td>";
			$json["tr_data"]   .= "<td>".$data['purchasing_information']."</td>";
			$json["tr_data"]   .= "<td>".$data['purchase_cost']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['expense_account']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['barcode']."</td>";
			$json["tr_data"]   .= "<td>".$data['qty_on_hand']."</td>";
			$json["tr_data"]   .= "<td>".$data['reorder_point']."</td>";
			$json["tr_data"]   .= "<td>".$data['as_of_date']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['asset_account']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['packing_size']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['manufacturer']."</td>";
			$json["tr_data"]   .= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}

		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_item_error", $error_data);
			$json["status"] = "end";
		}

        return json_encode($json);
	}
	public function postItemBundleReadFile_old()
	{
		Session::forget("import_item_error");

		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$data['type']					= isset($value["Type"]) 				? strToUpper($value["Type"]) : '';			//1,2,3
			$data['name'] 					= isset($value["Name"]) 				? $value["Name"] : '';					  	//1,2,3
			$data['sku'] 					= isset($value["Sku"]) 					? $value["Sku"] : '';				      	//1,2,3
			$data['um'] 					= isset($value["UM"]) 					? $value["UM"] : '';	

			$data['qty_in_bundle']			= isset($value["Qty In Bundle"])		? $value["Qty In Bundle"] : '';					  	//1,2,3
			$data['category'] 				= isset($value["Category"]) 			? $value["Category"] : '';				  	//1,2,3
			$data['sales_information'] 		= isset($value["Sales Information"]) 	? $value["Sales Information"] : '';		  	//1,2,3
			$data['sales_price'] 			= isset($value["Sales Price"]) 			? $value["Sales Price"] : '';			  	//1,2,3
			$data['income_account'] 		= isset($value["Income Account"]) 		? $value["Income Account"] : '';		  	//1,2,3
			$data['sale_to_customer'] 		= isset($value["Sale to Customer"]) 	? $value["Sale to Customer"] : '';        	//2,3
			$data{'purchase_from_supplier'} = isset($value["Purchase From Supplier"]) ? $value["Purchase From Supplier"] : '';	//2,3
			$data['purchasing_information'] = isset($value["Purchasing Information"]) ? $value["Purchasing Information"] : '';	//3
			$data['purchase_cost'] 			= isset($value["Purchase Cost"]) 		? $value["Purchase Cost"] : ''; 			//3
			$data['expense_account'] 		= isset($value["Expense Account"]) 		? $value["Expense Account"] : '';			//3
			$data['barcode'] 				= isset($value["Barcode"]) 				? $value["Barcode"] : '';					//3
			$data['qty_on_hand'] 			= isset($value["Qty on Hand"]) 			? $value["Qty on Hand"] : 0;				//3
			$data['reorder_point'] 			= isset($value["Reorder Point"]) 		? $value["Reorder Point"] : 0;				//3
			$data['as_of_date'] 			= isset($value["As of Date"]) 			? $value["As of Date"] : '';				//3
			$data['asset_account'] 			= isset($value["Asset Account"]) 		? $value["Asset Account"] : '';				//3
			$data['packing_size'] 			= isset($value["Packing Size"]) 		? $value["Packing Size"] : '';				//3
			$data['manufacturer'] 			= isset($value["Manufacturer"]) 		? $value["Manufacturer"] : '';				//3


			/* Import Setting */
			$auto_category			= isset($input["category"]) 		? $input["category"] : NULL;
			$auto_income_account	= isset($input["income_account"]) 	? $input["income_account"] : NULL;
			$auto_expense_account	= isset($input["expense_account"]) 	? $input["expense_account"] : NULL;
			$auto_asset_account		= isset($input["asset_account"]) 	? $input["asset_account"] : NULL;
			$auto_manufacturer		= isset($input["manufacturer"]) 	? $input["manufacturer"] : NULL;

			/* Validation */
			$duplicate_item		= Tbl_item::where("shop_id", $this->getShopId())->where("item_name", $data['name'])->first();
			$has_Category 		= Tbl_category::where("type_name", $data['category'])->where("type_shop", $this->getShopId())->first();
			$has_Income_Account = Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['income_account'])->first();
			$has_Expense_Account= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['expense_account'])->first();
			$has_Asset_Account 	= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['asset_account'])->first();
			$has_UM 			= Tbl_unit_measurement::where("um_shop", $this->getShopId())->where("um_name", $data['um'])->first();
			$has_Manufacturer 	= Tbl_manufacturer::where("manufacturer_shop_id", $this->getShopId())->where("manufacturer_name", $data['manufacturer'])->first();

			if(!$duplicate_item)
			{
				if($has_Category || $auto_category)
				{
					if($has_Manufacturer || $auto_manufacturer || $data['manufacturer'] == '')
					{
						/* CHECK CATEGORY */
						if(!$has_Category && $auto_category && $data['category'] != '')
						{
							$data['category'] = $this->create_category($data['category']);
						}
						else
						{
							$data['category'] = $has_Category->type_id;
						}

						/* CHECK INCOME ACCOUNT */
						if($data['income_account'] == '') // DEFAULT
						{
							$data['income_account'] = Tbl_chart_of_account::where("account_code", "accounting-sales")
									->where("account_shop_id", $this->getShopId())->value("account_id");
						}
						elseif(!$has_Income_Account && $auto_income_account && $data['income_account'] != '')
						{
							$data['income_account'] = $this->create_income_account($data['income_account']);
						}
						else
						{
							$data['income_account'] = $has_Income_Account->account_id;
						}

						/* IF TYPE IS ACCEPTED */
						if($data['type'] == "INVENTORY" || $data['type'] == "NON-INVENTORY" || $data['type'] == "SERVICE")
						{
							$insert["shop_id"]				      	  = $this->getShopId();
							$insert["item_name"]				      = $data['name'];
							$insert["item_sku"]					      = $data['sku'];
							$insert["item_category_id"]			      = $data['category'];
							$insert["item_sales_information"] 	      = $data['sales_information'];
							$insert["item_price"] 				      = $data['sales_price'];
							$insert["item_income_account_id"]         = $data['income_account'];
							$insert["item_date_created"]         	  = Carbon::now();

							$message = [];

							if($data['type'] == "INVENTORY")
							{
								/*  Check Expense Account */
								if($data['expense_account'] == '') // DEFAULT
								{
									$data['expense_account'] = Tbl_chart_of_account::where("account_code", "accounting-expense")
									->where("account_shop_id", $this->getShopId())->value("account_id");
								}
								elseif(!$has_Expense_Account && $auto_expense_account && $data['expense_account'] != '')
								{
									$data['expense_account'] = $this->create_expense_account($data['expense_account']);
								}
								else
								{
									$data['expense_account'] = $has_Expense_Account->account_id;
								}

								/* Check Asset Account */
								if($data['asset_account'] == '') // DEFAULT
								{
									$data['asset_account'] = Tbl_chart_of_account::where("account_code", "accounting-inventory-asset")
									->where("account_shop_id", $this->getShopId())->value("account_id");
								}
								elseif(!$has_Asset_Account && $auto_asset_account && $data['asset_account'] != '')
								{
									$data['asset_account'] = $this->create_asset_account($data['asset_account']);
								}
								elseif($has_Asset_Account)
								{
									$data['asset_account'] = $has_Asset_Account->account_id;
								}

								/* Check Manufacturer Account */
								if(!$has_Manufacturer && $auto_manufacturer && $data['manufacturer'] != '')
								{
									$data['manufacturer'] = $this->create_manufacturer($data['manufacturer']);
								}
								elseif($has_Manufacturer)
								{
									$data['manufacturer'] = $has_Manufacturer->manufacturer_id;
								}

								$insert["item_type_id"]				      = 1;
								$insert["item_purchasing_information"]    = $data['purchasing_information'];
								$insert["item_cost"]				      = $data['purchase_cost'];
								$insert["item_expense_account_id"]	      = $data['expense_account'];
								$insert["item_barcode"]				      = $data['barcode'];
								$insert["item_quantity"]		          = $data['qty_on_hand'] == '' ? 0 : $data['qty_on_hand'];
								$insert["item_reorder_point"] 		      = $data['reorder_point'];
								$insert["item_date_tracked"]	          = datepicker_input($data['as_of_date']);
								$insert["item_asset_account_id"]          = $data['asset_account'];
								$insert["packing_size"]				      = $data['packing_size'];
								$insert["item_manufacturer_id"]	      	  = $data['manufacturer'];

								$rules["item_quantity"]				      = 'required|numeric';
								$rules["item_cost"]					      = 'required|numeric';
								$rules["item_expense_account_id"]		  = 'required';
								$rules["item_asset_account_id"]			  = 'required';
								
								if($data['qty_on_hand'] > 0)
								{
									$rules["item_date_tracked"] 		  = 'required|date';
									$message["item_date_tracked.required"]= "The As of Date field is required when Qty on Hand is not empty";
									$message["item_date_tracked.date"]	  = "The As of Date field should be in Date format";
								}
							}
							elseif($data['type'] == "NON-INVENTORY")
							{
								$insert["item_type_id"]				      = 2;
								$insert["item_sale_to_customer"]		  = isset($data['sale_to_customer']) ? $data['sale_to_customer'] : 0;
								$insert["item_purchase_from_supplier"]	  = isset($data{'purchase_from_supplier'}) ? $data{'purchase_from_supplier'} : 0;
								
								$rules["item_sale_to_customer"]		  	  = 'required|numeric|min:0|max:1';
								$rules["item_purchase_from_supplier"]  	  = 'required|numeric|min:0|max:1';
				
							}
							elseif($data['type'] == "SERVICE")
							{
								$insert["item_type_id"]				      = 3;
								$insert["item_sale_to_customer"]		  = isset($data['sale_to_customer']) ? $data['sale_to_customer'] : 0;
								$insert["item_purchase_from_supplier"]	  = isset($data{'purchase_from_supplier'}) ? $data{'purchase_from_supplier'} : 0;
								
								$rules["item_sale_to_customer"]		      = 'required|numeric|min:0|max:1';
								$rules["item_purchase_from_supplier"]     = 'required|numeric|min:0|max:1';
							}

							$rules["item_name"]					      = 'required';
							$rules["item_sku"]					      = 'required';
							$rules["item_price"]				      = 'required|numeric';
							$rules["item_income_account_id"]		  = 'required';

							$validator = Validator::make($insert, $rules, $message);
							if ($validator->fails())
							{
								$json["status"] 	= "error";
								$json["message"]  	= $validator->errors()->first();
							}
							else
							{
								/* INSERT ITEM */ 
								$item_id 		= Tbl_item::insertGetId($insert);

								/* INSERT WAREHOUSE SLIP AND INVENTORY */
								$insert_sub["warehouse_id"] = $this->current_warehouse->warehouse_id;
								$insert_sub["item_id"] 		= $item_id;
								$insert_sub["item_reorder_point"] = $data['reorder_point'];
								Tbl_sub_warehouse::insert($insert_sub);

								$ins_slip["inventory_reason"] 		= "insert_item";
								$ins_slip["warehouse_id"] 			= $this->current_warehouse->warehouse_id;
								$ins_slip["inventory_remarks"] 		= "Insert Item";
								$ins_slip["inventory_slip_date"] 	= Carbon::now();
								$ins_slip["inventory_slip_shop_id"] = $this->getShopId();
								$ins_slip["inventroy_source_reason"]= "item";
								$ins_slip["inventory_source_id"] 	= $item_id;
								$slip_id = Tbl_inventory_slip::insertGetId($ins_slip);

								$ins_inven["inventory_item_id"] = $item_id;
								$ins_inven["warehouse_id"] 		= $this->current_warehouse->warehouse_id;
								$ins_inven["inventory_created"] = Carbon::now();
								$ins_inven["inventory_count"] 	= $data['qty_on_hand'];
								$ins_inven["inventory_slip_id"] = $slip_id;
								$inventory_id = Tbl_warehouse_inventory::insertGetId($ins_inven);

								Warehouse::insert_item_to_all_warehouse($item_id, $data['reorder_point']);

								$json["status"]		= "success";
								$json["message"]	= "Success";
								$json["item_id"]	= $item_id;
							}
						}
						else
						{
							$json["status"]		= "error";
							$json["message"]	= "Item Type Unknown";
						}
					}
					else
					{
						$json["status"]		= "error";
						$json["message"]	= "Manufacturer Not Found";
					}
				}
				else
				{
					$json["status"]		= "error";
					$json["message"]	= "Category Not Found";
				}
			}
			else
			{
				$json["status"]		= "error";
				$json["message"]	= "Duplicate Item Name";
			}

			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['type']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['name']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['sku']."</td>";
			$json["tr_data"]   .= "<td>".$data['um']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['category']."</td>";
			$json["tr_data"]   .= "<td>".$data['sales_information']."</td>";
			$json["tr_data"]   .= "<td>".$data['sales_price']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['income_account']."</td>";
			$json["tr_data"]   .= "<td>".$data['sale_to_customer']."</td>";
			$json["tr_data"]   .= "<td>".$data{'purchase_from_supplier'}."</td>";
			$json["tr_data"]   .= "<td>".$data['purchasing_information']."</td>";
			$json["tr_data"]   .= "<td>".$data['purchase_cost']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['expense_account']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['barcode']."</td>";
			$json["tr_data"]   .= "<td>".$data['qty_on_hand']."</td>";
			$json["tr_data"]   .= "<td>".$data['reorder_point']."</td>";
			$json["tr_data"]   .= "<td>".$data['as_of_date']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['asset_account']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['packing_size']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['manufacturer']."</td>";
			$json["tr_data"]   .= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}
		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_item_error", $error_data);
			$json["status"] = "end";
		}

        return json_encode($json);
	}
	public function postItemReadFile()
	{
		Session::forget("import_item_error");

		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$data['type']					= isset($value["Type"]) 				? strToUpper($value["Type"]) : '';			//1,2,3
			$data['name'] 					= isset($value["Name"]) 				? $value["Name"] : '';					  	//1,2,3
			$data['sku'] 					= isset($value["Sku"]) 					? $value["Sku"] : '';				      	//1,2,3
			$data['um'] 					= isset($value["UM"]) 					? $value["UM"] : '';					  	//1,2,3
			$data['category'] 				= isset($value["Category"]) 			? $value["Category"] : '';				  	//1,2,3
			$data['sales_information'] 		= isset($value["Sales Information"]) 	? $value["Sales Information"] : '';		  	//1,2,3
			$data['sales_price'] 			= isset($value["Sales Price"]) 			? str_replace(",", "", $value["Sales Price"]) : 0;			  	//1,2,3
			$data['income_account'] 		= isset($value["Income Account"]) 		? $value["Income Account"] : '';		  	//1,2,3
			$data['sale_to_customer'] 		= isset($value["Sale to Customer"]) 	? $value["Sale to Customer"] : '';        	//2,3
			$data{'purchase_from_supplier'} = isset($value["Purchase From Supplier"]) ? $value["Purchase From Supplier"] : '';	//2,3
			$data['purchasing_information'] = isset($value["Purchasing Information"]) ? $value["Purchasing Information"] : '';	//3
			$data['purchase_cost'] 			= isset($value["Purchase Cost"]) 		? str_replace(",", "", $value["Purchase Cost"]) : 0; 			//3
			$data['expense_account'] 		= isset($value["Expense Account"]) 		? $value["Expense Account"] : '';			//3
			$data['barcode'] 				= isset($value["Barcode"]) 				? $value["Barcode"] : '';					//3
			$data['qty_on_hand'] 			= isset($value["Qty on Hand"]) 			?  str_replace(",", "",$value["Qty on Hand"]) : 0;				//3
			$data['reorder_point'] 			= isset($value["Reorder Point"]) 		? $value["Reorder Point"] : 0;				//3
			$data['as_of_date'] 			= isset($value["As of Date"]) 			? $value["As of Date"] : '';				//3
			$data['asset_account'] 			= isset($value["Asset Account"]) 		? $value["Asset Account"] : '';				//3
			$data['packing_size'] 			= isset($value["Packing Size"]) 		? $value["Packing Size"] : '';				//3
			$data['manufacturer'] 			= isset($value["Manufacturer"]) 		? $value["Manufacturer"] : '';				//3

			$arr = ['10g','15g','25g','90g','135g','150g','500g','1kg','60ml','1L','120ml','30ml','50ml','100ml','5g'
		,'15ml','5ml','8ml','1gal','100ml','100g','200ml','300g','20ml'];
			$name_explode = explode(" ", $data['name']);
			if(isset($name_explode[1]))
			{
				$res = in_array($name_explode[0], $arr);
				if($res)
				{
					// $data['name'] = ucwords(str_replace($name_explode[0], "", $data['name'])) . " " .$name_explode[0];
				}
			}

			/* Import Setting */
			$auto_category			= isset($input["category"]) 		? $input["category"] : NULL;
			$auto_income_account	= isset($input["income_account"]) 	? $input["income_account"] : NULL;
			$auto_expense_account	= isset($input["expense_account"]) 	? $input["expense_account"] : NULL;
			$auto_asset_account		= isset($input["asset_account"]) 	? $input["asset_account"] : NULL;
			$auto_manufacturer		= isset($input["manufacturer"]) 	? $input["manufacturer"] : NULL;

			/* Validation */
			$duplicate_item		= Tbl_item::where("shop_id", $this->getShopId())->where("item_sku", $data['sku'])->first();
			$has_Category 		= Tbl_category::where("type_name", $data['category'])->where("type_shop", $this->getShopId())->first();
			$has_Income_Account = Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['income_account'])->first();
			$has_Expense_Account= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['expense_account'])->first();
			$has_Asset_Account 	= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())
								->where("account_name", $data['asset_account'])->first();
			$has_UM 			= Tbl_unit_measurement::where("um_shop", $this->getShopId())->where("um_name", $data['um'])->first();
			$has_Manufacturer 	= Tbl_manufacturer::where("manufacturer_shop_id", $this->getShopId())->where("manufacturer_name", $data['manufacturer'])->first();

			if(!$duplicate_item)
			{
				if($data['category'] != '')
				{
					if($has_Category || $auto_category)
					{
						if($has_Manufacturer || $auto_manufacturer || $data['manufacturer'] == '')
						{
							/* CHECK CATEGORY */
							if(!$has_Category && $auto_category && $data['category'] != '')
							{
								$data['category'] = $this->create_category($data['category']);
							}
							else
							{
								$data['category'] = $has_Category->type_id;
							}

							/* CHECK INCOME ACCOUNT */
							if($data['income_account'] == '') // DEFAULT
							{
								$data['income_account'] = Tbl_chart_of_account::where("account_code", "accounting-sales")
										->where("account_shop_id", $this->getShopId())->value("account_id");
							}
							elseif(!$has_Income_Account && $auto_income_account && $data['income_account'] != '')
							{
								$data['income_account'] = $this->create_income_account($data['income_account']);
							}
							else
							{
								$data['income_account'] = $has_Income_Account->account_id;
							}

							/* IF TYPE IS ACCEPTED */
							if($data['type'] == "INVENTORY" || $data['type'] == "NON-INVENTORY" || $data['type'] == "SERVICE")
							{
								$insert["shop_id"]				      	  = $this->getShopId();
								$insert["item_name"]				      = $data['name'];
								$insert["item_sku"]					      = $data['sku'];
								$insert["item_category_id"]			      = $data['category'];
								$insert["item_sales_information"] 	      = $data['sales_information'];
								$insert["item_price"] 				      = $data['sales_price'];
								$insert["item_measurement_id"]		      = $this->create_um($data['um']);
								$insert["item_income_account_id"]         = $data['income_account'];
								$insert["item_date_created"]         	  = Carbon::now();

								$message = [];

								if($data['type'] == "INVENTORY")
								{
									/*  Check Expense Account */
									if($data['expense_account'] == '') // DEFAULT
									{
										$data['expense_account'] = Tbl_chart_of_account::where("account_code", "accounting-expense")
										->where("account_shop_id", $this->getShopId())->value("account_id");
									}
									elseif(!$has_Expense_Account && $auto_expense_account && $data['expense_account'] != '')
									{
										$data['expense_account'] = $this->create_expense_account($data['expense_account']);
									}
									else
									{
										$data['expense_account'] = $has_Expense_Account->account_id;
									}

									/* Check Asset Account */
									if($data['asset_account'] == '') // DEFAULT
									{
										$data['asset_account'] = Tbl_chart_of_account::where("account_code", "accounting-inventory-asset")
										->where("account_shop_id", $this->getShopId())->value("account_id");
									}
									elseif(!$has_Asset_Account && $auto_asset_account && $data['asset_account'] != '')
									{
										$data['asset_account'] = $this->create_asset_account($data['asset_account']);
									}
									elseif($has_Asset_Account)
									{
										$data['asset_account'] = $has_Asset_Account->account_id;
									}

									/* Check Manufacturer Account */
									if(!$has_Manufacturer && $auto_manufacturer && $data['manufacturer'] != '')
									{
										$data['manufacturer'] = $this->create_manufacturer($data['manufacturer']);
									}
									elseif($has_Manufacturer)
									{
										$data['manufacturer'] = $has_Manufacturer->manufacturer_id;
									}

									$insert["item_type_id"]				      = 1;
									$insert["item_purchasing_information"]    = $data['purchasing_information'];
									$insert["item_cost"]				      = $data['purchase_cost'];
									$insert["item_expense_account_id"]	      = $data['expense_account'];
									$insert["item_barcode"]				      = $data['barcode'];
									$insert["item_quantity"]		          = $data['qty_on_hand'] == '' ? 0 : $data['qty_on_hand'];
									$insert["item_reorder_point"] 		      = $data['reorder_point'];
									$insert["item_date_tracked"]	          = datepicker_input($data['as_of_date']);
									$insert["item_asset_account_id"]          = $data['asset_account'];
									$insert["packing_size"]				      = $data['packing_size'];
									$insert["item_manufacturer_id"]	      	  = $data['manufacturer'];

									$rules["item_quantity"]				      = 'required|numeric';
									$rules["item_cost"]					      = 'required|numeric';
									$rules["item_expense_account_id"]		  = 'required';
									$rules["item_asset_account_id"]			  = 'required';
									
									if($data['qty_on_hand'] > 0)
									{
										$rules["item_date_tracked"] 		  = 'required|date';
										$message["item_date_tracked.required"]= "The As of Date field is required when Qty on Hand is not empty";
										$message["item_date_tracked.date"]	  = "The As of Date field should be in Date format";
									}
								}
								elseif($data['type'] == "NON-INVENTORY")
								{
									$insert["item_type_id"]				      = 2;
									$insert["item_sale_to_customer"]		  = isset($data['sale_to_customer']) ? $data['sale_to_customer'] : 0;
									$insert["item_purchase_from_supplier"]	  = isset($data{'purchase_from_supplier'}) ? $data{'purchase_from_supplier'} : 0;
									
									$rules["item_sale_to_customer"]		  	  = 'required|numeric|min:0|max:1';
									$rules["item_purchase_from_supplier"]  	  = 'required|numeric|min:0|max:1';
					
								}
								elseif($data['type'] == "SERVICE")
								{
									$insert["item_type_id"]				      = 3;
									$insert["item_sale_to_customer"]		  = isset($data['sale_to_customer']) ? $data['sale_to_customer'] : 0;
									$insert["item_purchase_from_supplier"]	  = isset($data{'purchase_from_supplier'}) ? $data{'purchase_from_supplier'} : 0;
									
									$rules["item_sale_to_customer"]		      = 'required|numeric|min:0|max:1';
									$rules["item_purchase_from_supplier"]     = 'required|numeric|min:0|max:1';
								}

								$rules["item_name"]					      = 'required';
								$rules["item_sku"]					      = 'required';
								$rules["item_price"]				      = 'required|numeric';
								$rules["item_income_account_id"]		  = 'required';

								$validator = Validator::make($insert, $rules, $message);
								if ($validator->fails())
								{
									$json["status"] 	= "error";
									$json["message"]  	= $validator->errors()->first();
								}
								else
								{
									/* INSERT ITEM */ 
									$item_id 		= Tbl_item::insertGetId($insert);

									/* INSERT WAREHOUSE SLIP AND INVENTORY */
									$insert_sub["warehouse_id"] = $this->current_warehouse->warehouse_id;
									$insert_sub["item_id"] 		= $item_id;
									$insert_sub["item_reorder_point"] = $data['reorder_point'];
									Tbl_sub_warehouse::insert($insert_sub);

									// $ins_slip["inventory_reason"] 		= "insert_item";
									// $ins_slip["warehouse_id"] 			= $this->current_warehouse->warehouse_id;
									// $ins_slip["inventory_remarks"] 		= "Insert Item";
									// $ins_slip["inventory_slip_date"] 	= Carbon::now();
									// $ins_slip["inventory_slip_shop_id"] = $this->getShopId();
									// $ins_slip["inventroy_source_reason"]= "item";
									// $ins_slip["inventory_source_id"] 	= $item_id;
									// $slip_id = Tbl_inventory_slip::insertGetId($ins_slip);

									// $ins_inven["inventory_item_id"] = $item_id;
									// $ins_inven["warehouse_id"] 		= $this->current_warehouse->warehouse_id;
									// $ins_inven["inventory_created"] = Carbon::now();
									// $ins_inven["inventory_count"] 	= $data['qty_on_hand'];
									// $ins_inven["inventory_slip_id"] = $slip_id;
									// $inventory_id = Tbl_warehouse_inventory::insertGetId($ins_inven);

									if($data['qty_on_hand'] > 0)
									{
										$source['name'] = "initial_qty";
										$source['id'] = $item_id;
										$source['item_cost'] = $insert["item_cost"];
										$source['item_price'] = $insert["item_price"];

										Warehouse2::refill($this->user_info->shop_id, $this->current_warehouse->warehouse_id, $item_id, $data['qty_on_hand'], 'Initial quantity from imporation',$source);
									}

									Warehouse::insert_item_to_all_warehouse($item_id, $data['reorder_point']);
									AuditTrail::record_logs("Added", "item", $item_id , "", serialize($insert));

									$json["status"]		= "success";
									$json["message"]	= "Success";
									$json["item_id"]	= $item_id;
								}
							}
							else
							{
								$json["status"]		= "error";
								$json["message"]	= "Item Type Unknown";
							}
						}
						else
						{
							$json["status"]		= "error";
							$json["message"]	= "Manufacturer Not Found";
						}
					}
					else
					{
						$json["status"]		= "error";
						$json["message"]	= "Category Not Found";
					}
				}
				else
				{
					$json["status"]		= "error";
					$json["message"]	= "Category field is required";
				}
			}
			else
			{
				if($data['type'] == 'NON-INVENTORY')
				{
					Tbl_item::where("shop_id", $this->getShopId())->where("item_sku", $data['sku'])->update(['item_type_id' => 2]);
					$get =  Tbl_item::where("shop_id", $this->getShopId())->where("item_sku", $data['sku'])->first();
					$json["status"]		= "success";
					$json["message"]	= "Success";
					$json["item_id"]	= $get->item_id;
				}
				else
				{
					$json["status"]		= "error";
					$json["message"]	= "Duplicate Item Name";
				}
			}

			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['type']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['name']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['sku']."</td>";
			$json["tr_data"]   .= "<td>".$data['um']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['category']."</td>";
			$json["tr_data"]   .= "<td>".$data['sales_information']."</td>";
			$json["tr_data"]   .= "<td>".$data['sales_price']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['income_account']."</td>";
			$json["tr_data"]   .= "<td>".$data['sale_to_customer']."</td>";
			$json["tr_data"]   .= "<td>".$data{'purchase_from_supplier'}."</td>";
			$json["tr_data"]   .= "<td>".$data['purchasing_information']."</td>";
			$json["tr_data"]   .= "<td>".$data['purchase_cost']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['expense_account']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['barcode']."</td>";
			$json["tr_data"]   .= "<td>".$data['qty_on_hand']."</td>";
			$json["tr_data"]   .= "<td>".$data['reorder_point']."</td>";
			$json["tr_data"]   .= "<td>".$data['as_of_date']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['asset_account']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['packing_size']."</td>";
			$json["tr_data"]   .= "<td nowrap>".$data['manufacturer']."</td>";
			$json["tr_data"]   .= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}
		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_item_error", $error_data);
			$json["status"] = "end";
		}

        return json_encode($json);
	}

	public function getItemExportError()
	{
		$_value = Session::get("import_item_error");

		if($_value)
		{
			Excel::create("ItemImportError", function($excel) use($_value)
			{
				// Set the title
			    $excel->setTitle('Digimahouse');

			    // Chain the setters
			    $excel->setCreator('DigimaWebSolutions')
			          ->setCompany('DigimaWebSolutions');

			    $excel->sheet('Template', function($sheet) use($_value) {
			    	$header = [
			    				'Type',
			    				'Name',
			    				'Sku',
			    				'UM',
			    				'Category',
			    				'Sales Information',
			    				'Sales Price',
			    				'Income Account',
			    				'Sale to Customer',
			    				'Purchase From Supplier',
			    				'Purchasing Information',
			    				'Purchase Cost',
			    				'Barcode',
			    				'Qty on Hand',
			    				'Reorder Point',
			    				'As of Date',
			    				'Asset Account',
			    				'Packing Size',
			    				'Manufacturer',
			    				'Error_Description'
			    				];
			    	$sheet->freezeFirstRow();
			        $sheet->row(1, $header);
			        foreach($_value as $key=>$value)
			        {
			        	$sheet->row($key+2, $value);
			        }

			    });


			})->download('csv');
		}
		else
		{
			return Redirect::back();
		}
	}

	public function create_um($name)
	{
		$return = null;
		$check = Tbl_unit_measurement::where('um_name', $name)->value('um_id');
		if(!$check)
		{
			if($name)
			{
				$insert["um_name"] = $name;
				$insert["um_shop"] = $this->getShopId();
				$return = Tbl_unit_measurement::insertGetId($insert);		

				if($return)
				{
					$insert_multi['multi_name'] = $name;
					$insert_multi['multi_um_id'] = $return;
					$insert_multi['unit_qty'] = 1;
					$insert_multi['multi_abbrev'] = $name;
					$insert_multi['is_base'] = 1;
					Tbl_unit_measurement_multi::insert($insert_multi);
				}
			}
		}
		else
		{
			$return = $check;
		}
		return $return;
	}

	public function create_category($name)
	{
		$return = 0;
		$multi = explode("/", $name);
		if(count($multi) > 1)
		{
			$category_id = null;
			foreach ($multi as $key => $value) 
			{
				$type_parent_id = 0;

				$has_Category 		= Tbl_category::where("type_name", $value)->where("type_shop", $this->getShopId())->value("type_id");
				$sub_level = 0;
				if(isset($category_id[$key-1]))
				{
					$type_parent_id = $category_id[$key-1];
					$sub_level = Tbl_category::where('type_id', $type_parent_id)->value('type_sub_level');
					$sub_level++;
				}
				if($has_Category)
				{
					$return = $has_Category;
					$category_id[$key] = $return;
				}
				else
				{
					$insert["type_parent_id"] = $type_parent_id;
					$insert["type_name"] = $value;
					$insert["type_shop"] = $this->getShopId();
					$insert["type_sub_level"] = $sub_level;
					$return = Tbl_category::insertGetId($insert);
					$category_id[$key] = $return;
				}
			}
		}
		else
		{
			$insert["type_name"] = $name;
			$insert["type_shop"] = $this->getShopId();
			$return = Tbl_category::insertGetId($insert);
		}

		return $return;
	}

	public function create_income_account($name)
	{
		$insert["account_shop_id"] 	= $this->getShopId();
		$insert["account_type_id"] 	= 11;
		$insert["account_number"] 	= 00000;
		$insert["account_name"] 	= $name;
		return Tbl_chart_of_account::insertGetId($insert);
	}

	public function create_expense_account($name)
	{
		$insert["account_shop_id"] 	= $this->getShopId();
		$insert["account_type_id"] 	= 13;
		$insert["account_number"] 	= 00000;
		$insert["account_name"] 	= $name;
		return Tbl_chart_of_account::insertGetId($insert);
	}

	public function create_asset_account($name)
	{
		$insert["account_shop_id"] 	= $this->getShopId();
		$insert["account_type_id"] 	= 5;
		$insert["account_number"] 	= 00000;
		$insert["account_name"] 	= $name;
		return Tbl_chart_of_account::insertGetId($insert);
	}

	public function create_manufacturer($name)
	{
		$insert["manufacturer_name"] 	= $name;
		$insert["manufacturer_shop_id"] = $this->getShopId();
		$insert["date_created"] 		= Carbon::now();
		return Tbl_manufacturer::insertGetId($insert);
	}

	/**
	 * Start of Customer Module
	 * 
	 */
	public function getCustomer()
	{
		return view('member.import.customer_import');
	}

	public function getCustomerTemplate()
	{
		Excel::create("CustomerTemplate", function($excel)
		{
			// Set the title
		    $excel->setTitle('Digimahouse');

		    // Chain the setters
		    $excel->setCreator('DigimaWebSolutions')
		          ->setCompany('DigimaWebSolutions');

		    $excel->sheet('Template', function($sheet) {
		    	$header = [
		    				'Username',
		    				'Password',
		    				'Title Name',
		    				'First Name',
		    				'Middle Name',
		    				'Last Name',
		    				'Suffix Name',
		    				'Email',
		    				'Company',
		    				'Birth Date',
		    				'Tin Number',
		    				'Phone Number',
		    				'Mobile Number',
		    				'Opening Balance',
		    				'Balance Date',
		    				'Billing Country',
		    				'Billing State',
		    				'Billing City',
		    				'Billing Zipcode',
		    				'Billing Address',
		    				'Shipping Country',
		    				'Shipping State',
		    				'Shipping City',
		    				'Shipping Zipcode',
		    				'Shipping Address',
		    				'Other Contact',
		    				'Website',
		    				'Fax',
		    				'Display Name',
		    				'Print Name',
		    				'Tax Resale No',
		    				'Note', 
		    				'Fix Monthly Budget',
		    				'Adjusted Monthly Budget'
		    				];
		    	$sheet->freezeFirstRow();
		        $sheet->row(1, $header);

		    });


		})->download('csv');
	}

	public function postCustomerReadFile()
	{
		Session::forget("import_customer_error");

		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$username		= isset($value["Username"])			? $value["Username"] : '' ;
			$password		= isset($value["Password"])			? $value["Password"] : '' ;
			$title_name		= isset($value["Title Name"])		? $value["Title Name"] : '' ;
			$first_name		= isset($value["First Name"])		? $value["First Name"] : '' ;
			$middle_name	= isset($value["Middle Name"])		? $value["Middle Name"] : '' ;
			$last_name		= isset($value["Last Name"])		? $value["Last Name"] : '' ;
			$suffix_name	= isset($value["Suffix Name"])		? $value["Suffix Name"] : '' ;
			$email			= isset($value["Email"])			? $value["Email"] : '' ;
			$company		= isset($value["Company"])			? $value["Company"] : '' ;
			$birth_date		= isset($value["Birth Date"])		? $value["Birth Date"] : '' ;
			$tin_number		= isset($value["Tin Number"])		? $value["Tin Number"] : '' ;
			$phone			= isset($value["Phone Number"])		? $value["Phone Number"] : '' ;
			$mobile			= isset($value["Mobile Number"])	? $value["Mobile Number"] : '' ;
			$open_balance	= isset($value["Opening Balance"])	? $value["Opening Balance"] : '' ;
			$balance_date	= isset($value["Balance Date"])		? $value["Balance Date"] : '' ;
			$bill_country	= isset($value["Billing Country"]) && $value["Billing Country"] != '' ? $value["Billing Country"] : "Philippines";
			$bill_state		= isset($value["Billing State"])	? $value["Billing State"] : '' ;
			$bill_city		= isset($value["Billing City"])		? $value["Billing City"] : '' ;
			$bill_zip_code	= isset($value["Billing Zipcode"])	? $value["Billing Zipcode"] : '' ;
			$bill_address	= isset($value["Billing Address"])	? $value["Billing Address"] : '' ;
			$ship_country	= isset($value["Shipping Country"]) && $value["Shipping Country"] != '' ? $value["Shipping Country"] : "Philippines";
			$ship_state		= isset($value["Shipping State"])	? $value["Shipping State"] : '' ;
			$ship_city		= isset($value["Shipping City"])	? $value["Shipping City"] : '' ;
			$ship_zip_code	= isset($value["Shipping Zipcode"])	? $value["Shipping Zipcode"] : '' ;
			$ship_address	= isset($value["Shipping Address"])	? $value["Shipping Address"] : '' ;
			$other_contact	= isset($value["Other Contact"])	? $value["Other Contact"] : '' ;
			$website		= isset($value["Website"])			? $value["Website"] : '' ;
			$fax			= isset($value["Fax"])				? $value["Fax"] : '' ;
			$display_name	= isset($value["Display Name"])		? $value["Display Name"] : '' ;
			$print_name		= isset($value["Print Name"])		? $value["Print Name"] : '' ;
			$tax_resale_no	= isset($value["Tax Resale No"])	? $value["Tax Resale No"] : '' ;
			$notes			= isset($value["Note"])				? $value["Note"] : '' ;

			$fix_monthly	 = isset($value["Fix Monthly Budget"])	? $value["Fix Monthly Budget"] : '' ;
			$adjusted_monthly= isset($value["Adjusted Monthly Budget"])	? $value["Adjusted Monthly Budget"] : '' ;

			/* Validation */
			$duplicate_customer	= Tbl_customer::where("shop_id", $this->getShopId())->where("first_name", $first_name)->where("middle_name", $middle_name)->where("last_name", $last_name)->first();
			
			$duplicate_email = null;
			if($email)
			{
				$duplicate_email 	= Tbl_customer::where("shop_id", $this->getShopId())->where("email", $email)->first();
			}
			$has_bill_country 	= Tbl_country::where("country_name", $bill_country)->first();
			$has_ship_country 	= Tbl_country::where("country_name", $bill_country)->first();

			// if(!$duplicate_customer)
			// {
				if(!$duplicate_email)
				{
					if($has_bill_country || $bill_country == '')
					{
						if($has_ship_country || $ship_country == '')
						{

							$insertcustomer['shop_id'] 		= $this->getShopId();
							$insertcustomer['mlm_username'] = $username;
				            $insertcustomer['password'] 	= Crypt::encrypt($password);
				            $insertcustomer['title_name'] 	= $title_name;
				            $insertcustomer['first_name'] 	= $first_name;
				            $insertcustomer['middle_name'] 	= $middle_name;
				            $insertcustomer['last_name'] 	= $last_name;
				            $insertcustomer['suffix_name'] 	= $suffix_name;
				            $insertcustomer['email'] 		= $email;
				            $insertcustomer['company'] 		= $company;
				            $insertcustomer['created_date'] = Carbon::now();
				            $insertcustomer['IsWalkin'] 	= 0;
				            $insertcustomer['tin_number']	= $tin_number;
				            $insertcustomer['fix_monthly_budget'] 	= str_replace(",", '', $fix_monthly);
				            $insertcustomer['adjusted_monthly_budget']	= str_replace(",", '', $adjusted_monthly);

				            // BILL COUNTRY ID
				            if($has_bill_country) $insertcustomer['country_id'] = $has_bill_country->country_id;
				           	else $insertcustomer['country_id'] 					= $bill_country;
	  						
				   //          $rules["email"] 				= 'required|email';

							// $validator = Validator::make($insertcustomer, $rules);
							// if ($validator->fails())
							// {
							// 	$json["status"] 	= "error";
							// 	$json["message"]  	= $validator->errors()->first();
							// }
							// else
							// {
								$customer_id = Tbl_customer::insertGetId($insertcustomer);
	            
					            $insertSearch['customer_id'] = $customer_id;
					            $insertSearch['body'] = $title_name.' '.$first_name.' '.$middle_name.' '.$last_name.' '.$suffix_name.' '.$email.' '.$company;
					            Tbl_customer_search::insert($insertSearch);
				            
					            $insertInfo['customer_id'] 				= $customer_id;
					            $insertInfo['customer_phone'] 			= $phone;
					            $insertInfo['customer_mobile'] 			= $mobile;
					            $insertInfo['customer_fax'] 			= $fax;
					            $insertInfo['customer_other_contact'] 	= $other_contact;
					            $insertInfo['customer_website'] 		= $website;
					            $insertInfo['customer_display_name'] 	= $display_name;
					            $insertInfo['customer_print_name']		= $print_name;
					            $insertInfo['customer_billing'] 		= "";
					            $insertInfo['customer_tax_resale_no'] 	= $tax_resale_no;
					            $insertInfo['customer_opening_balance'] = $open_balance;
					            $insertInfo['customer_balance_date'] 	= $balance_date;
					            $insertInfo['customer_notes'] 			= $notes;
					            
					            Tbl_customer_other_info::insert($insertInfo);
					            
					            $insertAddress[0]['customer_id'] 		= $customer_id;
					            $insertAddress[0]['customer_state'] 	= $bill_state;
					            $insertAddress[0]['customer_city'] 		= $bill_city;
					            $insertAddress[0]['customer_zipcode'] 	= $bill_zip_code;
					            $insertAddress[0]['customer_street'] 	= $bill_address;
					            $insertAddress[0]['purpose'] 			= 'billing';
					            if($has_bill_country) $insertAddress[0]['country_id'] 	= $has_bill_country->country_id;
				           		else 				  $insertAddress[0]['country_id'] 	= $bill_country;

					            $insertAddress[1]['customer_id'] 		= $customer_id;
					            $insertAddress[1]['country_id'] 		= $ship_country;
					            $insertAddress[1]['customer_state'] 	= $ship_state;
					            $insertAddress[1]['customer_city'] 		= $ship_city;
					            $insertAddress[1]['customer_zipcode'] 	= $ship_zip_code;
					            $insertAddress[1]['customer_street'] 	= $ship_address;
					            $insertAddress[1]['purpose'] 			= 'shipping';
					            if($has_bill_country) $insertAddress[1]['country_id'] 	= $has_ship_country->country_id;
				           		else 				  $insertAddress[1]['country_id'] 	= $ship_country;
					            
					            Tbl_customer_address::insert($insertAddress);

					            $customer_data = Tbl_customer::info()->where("tbl_customer.customer_id",$customer_id)->first()->toArray();
					            AuditTrail::record_logs('Added', 'customer',$customer_id ,"", serialize($customer_data));

					            $json["status"]		= "success";
								$json["message"]	= "Success";
								$json["item_id"]	= $customer_id;
				        	// }
				        }
				        else
				        {
				        	$json["status"]		= "error";
							$json["message"]	= "Shipping Country doesn't Exist";
						}
		        	}
		        	else
		        	{
		        		$json["status"]		= "error";
						$json["message"]	= "Billing Country doesn't Exist";
		        	}
		        }
		        else
		        {
		        	$json["status"]		= "error";
					$json["message"]	= "Email Already Exist";
		        }
			// }
			// else
			// {
			// 	$json["status"]		= "error";
			// 	$json["message"]	= "Duplicate Customer Name";
			// }

			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$username."</td>";
			$json["tr_data"]   .= "<td nowrap>".$password."</td>";
			$json["tr_data"]   .= "<td nowrap>".$title_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$first_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$middle_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$last_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$suffix_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$email."</td>";
			$json["tr_data"]   .= "<td nowrap>".$company."</td>";
			$json["tr_data"]   .= "<td nowrap>".$birth_date."</td>";
			$json["tr_data"]   .= "<td nowrap>".$tin_number."</td>";
			$json["tr_data"]   .= "<td nowrap>".$phone."</td>";
			$json["tr_data"]   .= "<td nowrap>".$mobile."</td>";
			$json["tr_data"]   .= "<td nowrap>".$open_balance."</td>";
			$json["tr_data"]   .= "<td nowrap>".$balance_date."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_country."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_state."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_city."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_zip_code."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_address."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_country."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_state."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_city."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_zip_code."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_address."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_state."</td>";
			$json["tr_data"]   .= "<td nowrap>".$other_contact."</td>";
			$json["tr_data"]   .= "<td nowrap>".$website."</td>";
			$json["tr_data"]   .= "<td nowrap>".$fax."</td>";
			$json["tr_data"]   .= "<td nowrap>".$display_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$tax_resale_no."</td>";
			$json["tr_data"]   .= "<td nowrap>".$notes."</td>";
			$json["tr_data"]   .= "<td nowrap>".$fix_monthly."</td>";
			$json["tr_data"]   .= "<td nowrap>".$adjusted_monthly."</td>";
			$json["tr_data"]   .= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}
		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_customer_error", $error_data);
			$json["status"] = "end";
		}

        return json_encode($json);
	}

	public function getCustomerExportError()
	{
		$_value = Session::get("import_customer_error");

		if($_value)
		{
			Excel::create("CustomerImportError", function($excel) use($_value)
			{
				// Set the title
			    $excel->setTitle('Digimahouse');

			    // Chain the setters
			    $excel->setCreator('DigimaWebSolutions')
			          ->setCompany('DigimaWebSolutions');

			    $excel->sheet('Template', function($sheet) use($_value) {
			    	$header = [
			    				'Username',
								'Password',
			    				'Title Name',
								'First Name',
								'Middle Name',
								'Last Name',
								'Suffix Name',
								'Email',
								'Company',
								'Birth Date',
								'Tin Number',
								'Phone Number',
								'Mobile Number',
								'Opening Balance',
								'Balance Date',
								'Billing Country',
								'Billing State',
								'Billing City',
								'Billing Zipcode',
								'Billing Address',
								'Shipping Country',
								'Shipping State',
								'Shipping City',
								'Shipping Zipcode',
								'Shipping Address',
								'Other Contact',
								'Website',
								'Fax',
								'Display Name',
								'Print Name',
								'Tax Resale No',
								'Note',
			    				'Error_Description'
			    				];
			    	$sheet->freezeFirstRow();
			        $sheet->row(1, $header);
			        foreach($_value as $key=>$value)
			        {
			        	$sheet->row($key+2, $value);
			        }

			    });


			})->download('csv');
		}
		else
		{
			return Redirect::back();
		}
	}

	/**
	 * Start of Vendor Module
	 * 
	 */

	public function getVendor()
	{
		return view('member.import.vendor_import');
	}

	public function getVendorTemplate()
	{
		Excel::create("VendorTemplate", function($excel)
		{
			// Set the title
		    $excel->setTitle('Digimahouse');

		    // Chain the setters
		    $excel->setCreator('DigimaWebSolutions')
		          ->setCompany('DigimaWebSolutions');

		    $excel->sheet('Template', function($sheet) {
		    	$header = [
		    				'Title Name',
		    				'First Name',
		    				'Middle Name',
		    				'Last Name',
		    				'Suffix Name',
		    				'Email',
		    				'Company',
		    				'Phone Number',
		    				'Mobile Number',
		    				'Opening Balance',
		    				'Balance Date',
		    				'Billing Country',
		    				'Billing State',
		    				'Billing City',
		    				'Billing Zipcode',
		    				'Billing Address',
		    				'Shipping Country',
		    				'Shipping State',
		    				'Shipping City',
		    				'Shipping Zipcode',
		    				'Shipping Address',
		    				'Other Contact',
		    				'Website',
		    				'Fax',
		    				'Display Name',
		    				'Print Name',
		    				'Tax Resale No',
		    				'Note'
		    				];
		    	$sheet->freezeFirstRow();
		        $sheet->row(1, $header);
		    });


		})->download('csv');
	}

	public function postVendorReadFile()
	{
		Session::forget("import_vendor_error");

		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$title_name		= isset($value["Title Name"])		? $value["Title Name"] : '' ;
			$first_name		= isset($value["First Name"])		? $value["First Name"] : '' ;
			$middle_name	= isset($value["Middle Name"])		? $value["Middle Name"] : '' ;
			$last_name		= isset($value["Last Name"])		? $value["Last Name"] : '' ;
			$suffix_name	= isset($value["Suffix Name"])		? $value["Suffix Name"] : '' ;
			$email			= isset($value["Email"])			? $value["Email"] : '' ;
			$company		= isset($value["Company"])			? $value["Company"] : '' ;
			$phone			= isset($value["Phone Number"])		? $value["Phone Number"] : '' ;
			$mobile			= isset($value["Mobile Number"])	? $value["Mobile Number"] : '' ;
			$open_balance	= isset($value["Opening Balance"])	? $value["Opening Balance"] : '' ;
			$balance_date	= isset($value["Balance Date"])		? $value["Balance Date"] : '' ;
			$bill_country	= isset($value["Billing Country"])	? $value["Billing Country"] : '' ;
			$bill_state		= isset($value["Billing State"])	? $value["Billing State"] : '' ;
			$bill_city		= isset($value["Billing City"])		? $value["Billing City"] : '' ;
			$bill_zip_code	= isset($value["Billing Zipcode"])	? $value["Billing Zipcode"] : '' ;
			$bill_address	= isset($value["Billing Address"])	? $value["Billing Address"] : '' ;
			$ship_country	= isset($value["Shipping Country"])	? $value["Shipping Country"] : '' ;
			$ship_state		= isset($value["Shipping State"])	? $value["Shipping State"] : '' ;
			$ship_city		= isset($value["Shipping City"])	? $value["Shipping City"] : '' ;
			$ship_zip_code	= isset($value["Shipping Zipcode"])	? $value["Shipping Zipcode"] : '' ;
			$ship_address	= isset($value["Shipping Address"])	? $value["Shipping Address"] : '' ;
			$other_contact	= isset($value["Other Contact"])	? $value["Other Contact"] : '' ;
			$website		= isset($value["Website"])			? $value["Website"] : '' ;
			$fax			= isset($value["Fax"])				? $value["Fax"] : '' ;
			$display_name	= isset($value["Display Name"])		? $value["Display Name"] : '' ;
			$print_name		= isset($value["Print Name"])		? $value["Print Name"] : '' ;
			$tax_resale_no	= isset($value["Tax Resale No"])	? $value["Tax Resale No"] : '' ;

			/* Validation */
			$duplicate_vendor = null;
			$duplicate_email = null;
			if($first_name && $middle_name && $last_name)
			{
				$duplicate_vendor	= Tbl_vendor::where("vendor_shop_id", $this->getShopId())->where("vendor_first_name", $first_name)->where("vendor_middle_name", $middle_name)->where("vendor_last_name", $last_name)->first();

			}
			if($email)
			{
				$duplicate_email 	= Tbl_vendor::where("vendor_shop_id", $this->getShopId())->where("vendor_email", $email)->first();	
			}
			$has_bill_country 	= Tbl_country::where("country_name", $bill_country)->first();
			$has_ship_country 	= Tbl_country::where("country_name", $ship_country)->first();

			if(!$duplicate_vendor)
			{
				if(!$duplicate_email)
				{
					if($has_bill_country || $bill_country == '')
					{
						if($has_ship_country || $ship_country == '')
						{

							$insertvendor['vendor_shop_id'] 	= $this->getShopId();
				            $insertvendor['vendor_title_name'] 	= $title_name;
				            $insertvendor['vendor_first_name'] 	= $first_name;
				            $insertvendor['vendor_middle_name'] = $middle_name;
				            $insertvendor['vendor_last_name'] 	= $last_name;
				            $insertvendor['vendor_suffix_name'] = $suffix_name;
				            $insertvendor['vendor_email'] 		= $email;
				            $insertvendor['vendor_company'] 	= $company;
				            $insertvendor['created_date'] 		= Carbon::now();

				            $insertInfo['ven_info_phone'] 			= $phone;
				            $insertInfo['ven_info_mobile'] 			= $mobile;
				            $insertInfo['ven_info_fax'] 			= $fax;
				            $insertInfo['ven_info_other_contact'] 	= $other_contact;
				            $insertInfo['ven_info_website'] 		= $website;
				            $insertInfo['ven_info_display_name'] 	= $display_name;
				            $insertInfo['ven_info_print_name']		= $print_name;
				            $insertInfo['ven_info_tax_no'] 			= $tax_resale_no;
				            $insertInfo['ven_info_opening_balance'] = $open_balance;
				            $insertInfo['ven_info_balance_date'] 	= $balance_date;

			           		$insert_addr["ven_billing_city"]		= $ship_state;
							$insert_addr["ven_billing_city"]		= $bill_city;
							$insert_addr["ven_billing_zipcode"]		= $bill_zip_code;
							$insert_addr["ven_billing_street"]		= $bill_address;
							if($has_bill_country) $insert_addr["ven_billing_country_id"] 	= $has_bill_country->country_id;
			           		else 				  $insert_addr["ven_billing_country_id"] 	= NULL;

							$insert_addr["ven_shipping_state"]		= $ship_state;
							$insert_addr["ven_shipping_city"]		= $ship_city;
							$insert_addr["ven_shipping_zipcode"]	= $ship_zip_code;
							$insert_addr["ven_shipping_street"]		= $ship_address;
							if($has_ship_country) $insert_addr["ven_shipping_country_id"]	= $has_ship_country->country_id;
			           		else 				  $insert_addr["ven_shipping_country_id"]	= NULL;
	  
				            $rules["vendor_email"] 				= 'email';

							$validator = Validator::make($insertvendor, $rules);
							if ($validator->fails())
							{
								$json["status"] 	= "error";
								$json["message"]  	= $validator->errors()->first();
							}
							else
							{
								$vendor_id = Tbl_vendor::insertGetId($insertvendor);
					            
					            $insertInfo['ven_info_vendor_id'] = $vendor_id;
					            Tbl_vendor_other_info::insert($insertInfo);

					            $insert_addr["ven_addr_vendor_id"] = $vendor_id;
					            Tbl_vendor_address::insert($insert_addr);

					            $vendor_data = Tbl_vendor::info()->where("vendor_id",$vendor_id)->first()->toArray();
					            AuditTrail::record_logs('Added', 'vendor',$vendor_id ,"", serialize($vendor_data));
					            $json["status"]		= "success";
								$json["message"]	= "Success";
								$json["item_id"]	= $vendor_id;
				        	}
				        }
				        else
				        {
				        	$json["status"]		= "error";
							$json["message"]	= "Shipping Country doesn't Exist";
						}
		        	}
		        	else
		        	{
		        		$json["status"]		= "error";
						$json["message"]	= "Billing Country doesn't Exist";
		        	}
		        }
		        else
		        {
		        	$json["status"]		= "error";
					$json["message"]	= "Email Already Exist";
		        }
			}
			else
			{
				$json["status"]		= "error";
				$json["message"]	= "Duplicate vendor Name";
			}

			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$title_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$first_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$middle_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$last_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$suffix_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$email."</td>";
			$json["tr_data"]   .= "<td nowrap>".$company."</td>";
			$json["tr_data"]   .= "<td nowrap>".$phone."</td>";
			$json["tr_data"]   .= "<td nowrap>".$mobile."</td>";
			$json["tr_data"]   .= "<td nowrap>".$open_balance."</td>";
			$json["tr_data"]   .= "<td nowrap>".$balance_date."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_country."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_state."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_city."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_zip_code."</td>";
			$json["tr_data"]   .= "<td nowrap>".$bill_address."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_country."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_state."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_city."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_zip_code."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_address."</td>";
			$json["tr_data"]   .= "<td nowrap>".$ship_state."</td>";
			$json["tr_data"]   .= "<td nowrap>".$other_contact."</td>";
			$json["tr_data"]   .= "<td nowrap>".$website."</td>";
			$json["tr_data"]   .= "<td nowrap>".$fax."</td>";
			$json["tr_data"]   .= "<td nowrap>".$display_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$tax_resale_no."</td>";
			$json["tr_data"]   .= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}
		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_vendor_error", $error_data);
			$json["status"] = "end";
		}

        return json_encode($json);
	}

	public function getVendorExportError()
	{
		$_value = Session::get("import_vendor_error");

		if($_value)
		{
			Excel::create("VendorImportError", function($excel) use($_value)
			{
				// Set the title
			    $excel->setTitle('Digimahouse');

			    // Chain the setters
			    $excel->setCreator('DigimaWebSolutions')
			          ->setCompany('DigimaWebSolutions');

			    $excel->sheet('Template', function($sheet) use($_value) {
			    	$header = [
			    				'Title Name',
								'First Name',
								'Middle Name',
								'Last Name',
								'Suffix Name',
								'Email',
								'Company',
								'Phone Number',
								'Mobile Number',
								'Opening Balance',
								'Balance Date',
								'Billing Country',
								'Billing State',
								'Billing City',
								'Billing Zipcode',
								'Billing Address',
								'Shipping Country',
								'Shipping State',
								'Shipping City',
								'Shipping Zipcode',
								'Shipping Address',
								'Other Contact',
								'Website',
								'Fax',
								'Display Name',
								'Print Name',
								'Tax Resale No',
			    				'Error_Description'
			    				];
			    	$sheet->freezeFirstRow();
			        $sheet->row(1, $header);
			        foreach($_value as $key=>$value)
			        {
			        	$sheet->row($key+2, $value);
			        }

			    });


			})->download('csv');
		}
		else
		{
			return Redirect::back();
		}
	}

	/**
	 * Start of Chart of Account Module
	 * 
	 */

	public function getCoa()
	{
		return view('member.import.coa_import');
	}

	public function getCoaTemplate()
	{
		Excel::create("COATemplate", function($excel)
		{
			// Set the title
		    $excel->setTitle('Digimahouse');

		    // Chain the setters
		    $excel->setCreator('DigimaWebSolutions')
		          ->setCompany('DigimaWebSolutions');

		    $excel->sheet('Template', function($sheet) {
		    	$header = [
		    				'Account Type',
							'Account Number',
							'Account Name',
							'Account Description',
							'Sub Account Of',
							'Opening Balance',
							'Balance As of'
		    				];
		    	$sheet->freezeFirstRow();
		        $sheet->row(1, $header);

		    });


		})->download('csv');
	}

	public function postCoaReadFile()
	{
		Session::forget("import_coa_error");

		$value     = Request::input('value');
		$input     = Request::input('input');

		$ctr 	   		= Request::input('ctr');
		$data_length 	= Request::input('data_length');
		$error_data 	= Request::input('error_data');

		if($ctr != $data_length)
		{
			$account_type		= isset($value["Account Type"])			? $value["Account Type"] : '' ;
			$account_number		= isset($value["Account Number"])		? $value["Account Number"] : '' ;
			$account_name		= isset($value["Account Name"])			? $value["Account Name"] : '' ;
			$acount_description	= isset($value["Account Description"])	? $value["Account Description"] : '' ;
			$sub_account_of		= isset($value["Sub Account Of"])		? $value["Sub Account Of"] : '' ;
			$open_balance		= isset($value["Opening Balance"])		? $value["Opening Balance"] : '' ;
			$balance_date		= isset($value["Balance As of"])		? $value["Balance As of"] : '' ;

			
			/* Validation */
			$duplicate_account	= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())->where("account_name", $account_name)->first();
			$duplicate_acc_no	= Tbl_chart_of_account::where("account_shop_id", $this->getShopId())->where("account_number", $account_number)->first();
			$sub_account_exist	= Tbl_chart_of_account::type()->where("account_shop_id", $this->getShopId())->Where("account_name", $sub_account_of)->orWhere("account_number", $sub_account_of)->first();
			$account_type_exist = Tbl_chart_account_type::where("chart_type_name", $account_type)->first();

			if(!$duplicate_account)
			{
				if(!$duplicate_acc_no || $account_number == '')
				{
					if($sub_account_exist || $sub_account_of == '')
					{
						if($account_type_exist)
						{
		  					$insert['account_shop_id']            = $this->getShopId();
					        $insert['account_type_id']            = $account_type_exist->chart_type_id;
					        $insert['account_number']             = $account_number;
					        $insert['account_name']               = $account_name;
					        $insert['account_description']        = $acount_description;
					        $insert['account_parent_id']          = null;
					        $insert['account_sublevel']           = 0;
					        $insert['account_timecreated']        = Carbon::now();

					        if($sub_account_exist)
					        {
					        	$insert['account_parent_id']      = $sub_account_exist->account_id;
					        	$insert['account_sublevel']       = $sub_account_exist->account_sublevel+1;
					        }

					        /* IF THE ACCOUNT TYPE HAS OPEN BALANCE FIELD */
					        if($account_type_exist->value("has_open_balance") == 1)
					        {
					            $insert['account_open_balance']       = $open_balance;
					            $insert['account_open_balance_date']  = datepicker_input($balance_date);
					        }

				            $rules["account_name"] = 'required';

							$validator = Validator::make($insert, $rules);
							if ($validator->fails())
							{

								$json["status"] 	= "error";
								$json["message"]  	= $validator->errors()->first();
							}
							elseif($sub_account_exist && strtoupper($account_type) != strtoupper($sub_account_exist->chart_type_name))
							{
								$json["status"] 	= "error";
								$json["message"]  	= "Sub Account Type doesn't match";
							}
							else
							{
								$account_id = Tbl_chart_of_account::insertGetId($insert);
								$coa = Tbl_chart_of_account::where("account_id",$account_id)->first()->toArray();
        						AuditTrail::record_logs('Added', 'coa', $account_id ,"", serialize($coa));
        						
					            $json["status"]		= "success";
								$json["message"]	= "Success";
								$json["item_id"]	= $account_id;
				        	}
				        }
				        else
				        {
				        	$json["status"]		= "error";
							$json["message"]	= "Account Type doesn't recognize";
				        }
		        	}
		        	else
		        	{
		        		$json["status"]		= "error";
						$json["message"]	= "Sub Account doesn't Exist";
		        	}
		        }
		        else
		        {
		        	$json["status"]		= "error";
					$json["message"]	= "Duplicate Account Number";
		        }
			}
			else
			{
				$json["status"]		= "error";
				$json["message"]	= "Duplicate Account Name";
			}

			$status_color 		= $json["status"] == 'success' ? 'green' : 'red';
			$json["tr_data"]	= "<tr>";
			$json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
			$json["tr_data"]   .= "<td nowrap>".$account_type."</td>";
			$json["tr_data"]   .= "<td nowrap>".$account_number."</td>";
			$json["tr_data"]   .= "<td nowrap>".$account_name."</td>";
			$json["tr_data"]   .= "<td nowrap>".$acount_description."</td>";
			$json["tr_data"]   .= "<td nowrap>".$sub_account_of."</td>";
			$json["tr_data"]   .= "<td nowrap>".$open_balance."</td>";
			$json["tr_data"]   .= "<td nowrap>".$balance_date."</td>";
			$json["tr_data"]   .= "</tr>";

			$json["value_data"] = $value;
			$length 			= sizeOf($json["value_data"]);

			foreach($json["value_data"] as $key=>$value)
			{
				$json["value_data"]['Error Description'] = $json["message"];
			}
		}
		else /* DETERMINE IF LAST IN CSV */
		{
			Session::put("import_coa_error", $error_data);
			$json["status"] = "end";
		}

        return json_encode($json);
	}

	public function getCoaExportError()
	{
		$_value = Session::get("import_coa_error");

		if($_value)
		{
			Excel::create("COAImportError", function($excel) use($_value)
			{
				// Set the title
			    $excel->setTitle('Digimahouse');

			    // Chain the setters
			    $excel->setCreator('DigimaWebSolutions')
			          ->setCompany('DigimaWebSolutions');

			    $excel->sheet('Template', function($sheet) use($_value) {
			    	$header = [
			    				'Account Type',
								'Account Number',
								'Account Name',
								'Account Description',
								'Sub Account Of',
								'Opening Balance',
								'Balance As of'
			    				];
			    	$sheet->freezeFirstRow();
			        $sheet->row(1, $header);
			        foreach($_value as $key=>$value)
			        {
			        	$sheet->row($key+2, $value);
			        }

			    });


			})->download('csv');
		}
		else
		{
			return Redirect::back();
		}
	}

	/* Do not Remove */
	public function postUrl()
	{

	}
}
 