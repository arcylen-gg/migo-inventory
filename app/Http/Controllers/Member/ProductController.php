<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_image;
use App\Models\Tbl_product;
use App\Models\Tbl_category;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_option_name;
use App\Models\Tbl_option_value;
use App\Models\Tbl_variant;
use App\Models\Tbl_variant_name;
use App\Models\View_product_variant;
use App\Models\Tbl_collection_item;
use App\Models\Tbl_collection;
use App\Models\Tbl_product_search;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_item;
use App\Models\Tbl_ec_variant;

use App\Globals\Variant;

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

class ProductController extends Member
{
	public function index()
	{
		$data["page"] 		= "Product";
		$data["_product"]	= Tbl_product::infoitem($this->user_info->shop_id)->get();
		
		return view('member.product.product', $data);
	}

	public function add()
	{
		if(Request::isMethod("post"))
		{
			return $this->add_submit();
		}
		else
		{
			$data["page"]		= "Product Add";
			$data["_image"] 	= Tbl_image::where("image_shop", $this->user_info->shop_id)->where("image_reason", "product")->where("image_reason_id", 0)->get();
			$data["_vendor"]	= Tbl_product_vendor::where("vendor_shop", $this->user_info->shop_id)->get();
			$data["_type"]		= Tbl_category::where("type_shop", $this->user_info->shop_id)->get();
			$data["_um"]		= Tbl_unit_measurement::type($this->user_info->shop_id)->get();
			$data["_coa"]		= Tbl_chart_of_account::accountInfo($this->user_info->shop_id)->get();
			$data["_item"]  	= Tbl_item::where("archived", 0)->where("shop_id", $this->user_info->shop_id)->get();
			return view('member.product.product_add', $data);
		}
	}

	public function edit($product_id)
	{
		if(Request::isMethod("post"))
		{
			return $this->edit_submit($product_id);
		}
		else
		{
			$data["page"] 		= "Product Edit";
			$data["product"] 	= $product = Tbl_product::info($this->user_info->shop_id)->where("product_id", $product_id)->first();
			$data["_image"] 	= Tbl_image::where("image_shop", $this->user_info->shop_id)->where("image_reason", "product")->where("image_reason_id", $product->product_id)->get();
			$data["_vendor"] 	= Tbl_product_vendor::where("vendor_shop", $this->user_info->shop_id)->get();
			$data["_type"] 		= Tbl_category::where("type_shop", $this->user_info->shop_id)->get();
			$data["_um"]		= Tbl_unit_measurement::type($this->user_info->shop_id)->get();
			$data["_coa"]		= Tbl_chart_of_account::accountInfo($this->user_info->shop_id)->get();
			$data["_item"]  	= Tbl_item::where("archived", 0)->where("shop_id", $this->user_info->shop_id)->get();
			$data["_product_variant"] = Tbl_product::variant()->where("product_id",$product_id)->groupBy("tbl_variant.variant_id")->get();
			
			foreach($data["_product_variant"] as $key=>$product_variant)
			{                                                                                                                                                                                                       
				$data["_product_variant"][$key] = $product_variant;

				$explode_option_name  = explode(",", $product_variant->option_name);
				$explode_option_value = explode(",", $product_variant->option_value);
				
				foreach($explode_option_name as $key2=>$option_name)
				{
					$data["_product_variant"][$key]->$key2 = $explode_option_value[$key2];
					$data["_column"][$key2]				   = $option_name;
				}
			}

			// dd($data["_product_variant"]);

			return view('member.product.product_edit', $data);
		}
	}
	
	public function add_submit()
	{
		sleep(1);
		
		/* VALIDATION */
		$validate = $this->product_validate();
		
		if ($validate) //fail to add product
		{
			/* INSERT PRODUCT */
			$insert_product["product_name"] 			= "";
			$insert_product["product_shop"] 			= $this->user_info->shop_id;
			$insert_product["product_main"] 			= Request::input('product_main_image') != '' ? Request::input('product_main_image') : 0;
			$insert_product["product_description"]		= Request::input("product_description");
			$insert_product["product_search_keyword"] 	= "";
			$insert_product["product_vendor"]			= $this->add_submit_vendor(Request::input('product_vendor_list'), Request::input('product_vendor'));
			$insert_product["product_type"] 			= $this->add_submit_type(Request::input('product_type_list'), Request::input('product_type'));
			$insert_product["product_has_variations"]	= Request::input('variation') == "checked" ? 1 : 0;
			$insert_product["product_date_visible"] 	= Carbon::now();
			$insert_product["product_date_created"] 	= Carbon::now(); 
			$insert_product["item_id"]					= Request::input("item_id");
			$insert_product["product_image"]			= Request::input("product_image") ? serialize(Request::input("product_image")) : null;
			
			if(Request::input('has-purchase-info') == "checked")
			{
				$insert_product["product_cogs_exp_account"]		= Request::input('product_cogs_exp_account');
				$insert_product["product_pref_vendor_id"]		= Request::input('product_pref_vendor_id');
				$insert_product["product_purchase_description"]	= Request::input('product_purchase_description');
			}
			if(Request::input('has-sale-info') == "checked")
			{
				$insert_product["product_income_account"]		= Request::input('product_income_account');
				$insert_product["product_sale_description"]		= Request::input('product_sale_description');
			}
			if(Request::input('variant_track_inventory') == 1)
			{
				$insert_product["product_asset_account"]		= Request::input('product_asset_account');
			}
			
			$product_id = Tbl_product::insertGetId($insert_product);
			
			// if(Request::input('variation') == "checked")
			// {
			// 	/* INSERRT MULTIPLE VARIANTS */
			// 	$this->insert_variants($product_id, "");
			// }
			// else //SINGLE SIMPLE VARIATION
			// {
			// 	/* INSERT VARIANT */
			// 	$insert_variant["variant_product_id"] 			= $product_id;
			// 	$insert_variant["variant_single"] 				= 1;
				
			// 	$insert_variant["variant_compare_price"] 		= Request::input("variant_price") < Request::input("variant_compare_price") ?  Request::input("variant_compare_price") : 0;
			// 	$insert_variant["variant_charge_taxes"] 		= Request::input('variant_charge_taxes') == "checked" ? 1 : 0;
			// 	$insert_variant["variant_sku"] 					= $this->add_submit_sku(Request::input("variant_sku"),$insert_product["product_name"] );
			// 	// $insert_variant["variant_barcode"] 				= Request::input('variant_barcode');
			// 	// $insert_variant["variant_track_inventory"]		= Request::input('variant_track_inventory');
			// 	$insert_variant["variant_allow_oos_purchase"]	= Request::input('variant_allow_oos_purchase') == "checked" ? 1 : 0;
			// 	// $insert_variant["variant_weight"] 				= Request::input('variant_weight');
			// 	// $insert_variant["variant_weight_lbl"] 			= Request::input('variant_weight_lbl');
			// 	$insert_variant["variant_require_shipping"]		= Request::input('variant_require_shipping') == "checked" ? 1 : 0;
			// 	//$insert_variant['variant_fulfillment_service']	= Request::input('variant_fulfillment_service');
			// 	$insert_variant["variant_main_image"] 			= Request::input('product_main_image') != '' ? Request::input('product_main_image') : 0;
				
			// 	if(Request::input('has-sale-info') == "checked")
			// 	{
			// 		$insert_variant["variant_price"] 			= Request::input("variant_price");
			// 	}
			// 	if(Request::input('has-purchase-info') == "checked")
			// 	{
			// 		$insert_variant["variant_purchase_price"]	= Request::input('variant_purchase_price');
			// 	}
			// 	/* INVENTORY COLUMNS */
			// 	// if($insert_variant["variant_track_inventory"] == 1)
			// 	// {
			// 	// 	$insert_variant["variant_inventory_count"]	= Request::input('variant_inventory_count');
			// 	// 	$insert_variant["variant_inventory_date"]	= date_format(date_create(Request::input('variant_inventory_date')) ,"Y/m/d");
			// 	// 	$insert_variant["variant_reorder_min"]		= Request::input('variant_reorder_min');
			// 	// 	$insert_variant["variant_reorder_max"]		= Request::input('variant_reorder_max');
			// 	// }
				
			// 	$variant_id = Tbl_variant::insertGetId($insert_variant);
			// }

			/* UPDATE IMAGES INFORMATION IN DB */
			// $_image = Tbl_image::where('image_reason_id', 0)->where('image_reason', 'product')->where('image_shop', $this->user_info->shop_id)->get();
			
			/* UPDATE IMAGES FOLD */

			// $replace = "upload/member-" . $this->user_info->shop_id . "/product-0";
			// $replace_with = "upload/member-" . $this->user_info->shop_id . "/product-" . $product_id;

			// if($_image->count() > 0)
			// {
			// 	if(File::exists(public_path($replace_with)))
			// 	{
			// 		deleteDir(public_path($replace_with));
			// 	}
			// 	rename($replace, $replace_with);
			// 	foreach($_image as $image)
			// 	{
			// 		$update["image_reason_id"] = $product_id;
			// 		$update["image_path"] = str_replace("product-0", "product-" . $product_id, $image->image_path);
			// 		Tbl_image::where('image_id', $image->image_id)->update($update);
			// 	}
			// }
			
			/* UPDATE IMAGES INFORMATION */
			$json["mode"] = "success";
			$json["message"] = "/member/product/add?pid=" . $product_id;
			$json["product_id"] = $product_id;

			echo json_encode($json);
		}
	}

	public function edit_submit($product_id)
	{
		sleep(1);
		/* VALIDATION */
		$validate = $this->product_validate();
		$json = [];
		if($validate)
		{
			// dd(Request::input("product_description"));

			/* UPDATE PRODUCT INFO */
			$update_product["product_name"]				= Request::input("product_title");
			$update_product["product_description"]		= Request::input("product_description");
			// dd($update_product["product_description"]);
			$update_product["product_main"] 			= 1;
			$update_product["product_vendor"]			= $this->add_submit_vendor(Request::input('product_vendor_list'), Request::input('product_vendor'));
			$update_product["product_type"] 			= $this->add_submit_type(Request::input('product_type_list'), Request::input('product_type'));
			$update_product["item_id"]					= Request::input("item_id");

			if(Request::input('has-purchase-info') == "checked")
			{
				$insert_product["product_cogs_exp_account"]		= Request::input('product_cogs_exp_account');
				$insert_product["product_pref_vendor_id"]		= Request::input('product_pref_vendor_id');
				$insert_product["product_purchase_description"]	= Request::input('product_purchase_description');
				
			}
			if(Request::input('has-sale-info') == "checked")
			{
				$insert_product["product_income_account"]		= Request::input('product_income_account');
				$insert_product["product_sale_description"]		= Request::input('product_sale_description');
				
			}
			// if(Request::input('variant_track_inventory') == 1)
			// {
			// 	$insert_product["product_asset_account"]		= Request::input('product_asset_account');
			// }
			
			/* --------------------------------------------------------------------------------------------- */

			if(Request::input('variation') == "checked"){
				
				/* INSERRT MULTIPLE VARIANTS */
				$this->insert_variants($product_id, Request::input("product_title"));

				$update_product["product_has_variations"]	= Request::input('variation') == "checked" ? 1 : 0;
			}
			elseif(Request::input('variation')) //SINGLE SIMPLE VARIATION 
			{
				/* DELETE SINGLE VARIATION IF IT HAS */
				Tbl_variant::where("product_id", $product_id)->where("variant_single", 1)->delete();
				
				$update_variant["variant_charge_taxes"] 		= Request::input('variant_charge_taxes') == "checked" ? 1 : 0;
				$update_variant["variant_sku"]					= $this->add_submit_sku(Request::input("variant_sku"),$update_product["product_name"] );
				// $update_variant["variant_barcode"]				= Request::input('variant_barcode');
				// $update_variant["variant_track_inventory"]		= Request::input('variant_track_inventory');
				$update_variant["variant_allow_oos_purchase"]	= Request::input('variant_allow_oos_purchase') == "checked" ? 1 : 0;
				$update_variant["variant_inventory_count"]		= Request::input('variant_inventory_count');
				// $update_variant["variant_weight"]				= Request::input('variant_weight');
				// $update_variant['variant_weight_lbl']			= Request::input('variant_weight_lbl');
				$update_variant["variant_require_shipping"]		= Request::input('variant_require_shipping') == "checked" ? 1 : 0;
				$update_variant['variant_fulfillment_service']	= Request::input('variant_fulfillment_service');
				
				if(Request::input('has-sale-info') == "checked")
				{
					$insert_variant["variant_price"] 			= Request::input("variant_price");
				}
				if(Request::input('has-purchase-info') == "checked")
				{
					$insert_variant["variant_purchase_price"]	= Request::input('variant_purchase_price');
				}
				/* INVENTORY COLUMNS */
				// if($insert_variant["variant_track_inventory"] == 1)
				// {
				// 	$insert_variant["variant_inventory_count"]	= Request::input('variant_inventory_count');
				// 	$insert_variant["variant_inventory_date"]	= date_format(date_create(Request::input('variant_inventory_date')) ,"Y/m/d");
				// 	$insert_variant["variant_reorder_min"]		= Request::input('variant_reorder_min');
				// 	$insert_variant["variant_reorder_max"]		= Request::input('variant_reorder_max');
				// }
				

				Tbl_variant::where("variant_product_id", $product_id)->update($update_variant);
			}
			else
			{
				//IF THERE IS VARIATION CHECKBOX
			}

			Tbl_product::where("product_id", $product_id)->update($update_product);
			
			
			$json["mode"] 		= "success";
			$json["product_id"] = $product_id;

		}
		echo json_encode($json);
	}

	public function insert_variants($product_id, $product_name = '')
	{
		$inputOptionList= Request::input("option_list");
		$inputOption	= Request::input('option');
		$combination	= Request::input("variant_combination");
		$price			= Request::input("variant_price"); // SALES PRICE
		$cost			= Request::input("variant_cost"); // PURCHASE PRICE
		$sku			= Request::input('variant_sku');
		// $barcode		= Request::input('variant_barcode');
		$inventory		= Request::input('variant_inventory');
		
		foreach($combination as $key => $com){

			$insertVariant['variant_product_id']		= $product_id;
			$insertVariant['variant_sku']				= $sku[$key];
			// $insertVariant['variant_barcode']			= $barcode[$key];
			//$insertVariant['variant_inventory_count']	= $inventory[$key];

			$insertVariant["variant_charge_taxes"] 			= Request::input('variant_charge_taxes') == "checked" ? 1 : 0;
			// $insertVariant["variant_track_inventory"]		= Request::input('variant_track_inventory');
			$insertVariant["variant_allow_oos_purchase"]	= Request::input('variant_allow_oos_purchase') == "checked" ? 1 : 0;
			// $insertVariant["variant_weight"] 				= Request::input('variant_weight');
			// $insertVariant["variant_weight_lbl"] 			= Request::input('variant_weight_lbl');
			$insertVariant["variant_require_shipping"]		= Request::input('variant_require_shipping') == "checked" ? 1 : 0;
			$insertVariant['variant_fulfillment_service']	= Request::input('variant_fulfillment_service');
			$insertVariant["variant_main_image"] 			= Request::input('product_main_image') != '' ? Request::input('product_main_image') : $this->add_submit_main_image();
			$insertVariant["variant_date_visible"] 			= Carbon::now();
			$insertVariant["variant_date_created"] 			= Carbon::now();
			
			if(Request::input('has-sale-info') == "checked")
			{
				$insert_variant["variant_price"] 			= $price[$key];
			}
			if(Request::input('has-purchase-info') == "checked")
			{
				$insert_variant["variant_purchase_price"]	= $cost[$key];
			}
			/* INVENTORY COLUMNS */
			// if(Request::input('variant_track_inventory') == 1)
			// {
			// 	$insert_variant["variant_inventory_count"]	= $inventory[$key];
			// 	$insert_variant["variant_inventory_date"]	= date_format(date_create(Request::input('variant_inventory_date')) ,"Y/m/d");
			// 	$insert_variant["variant_reorder_min"]		= Request::input('variant_reorder_min');
			// 	$insert_variant["variant_reorder_max"]		= Request::input('variant_reorder_max');
			// }

			
			$variant_id = Tbl_variant::insertGetId($insertVariant);
			
			$excombination = explode(",", $combination[$key]);

			foreach($excombination as $key => $excom){
				
				$insert_option_value['option_value']	= $excom;
				$insert_option_name['option_name']		= $inputOption[$key];
													
				$option_value_id	= Tbl_option_value::insertGetId($insert_option_value);
				$option_name 		= Tbl_option_name::where("option_name",$insert_option_name['option_name'])->first();
				if($option_name)
				{
					$option_name_id 	= $option_name->option_name_id;
				}
				else
				{
					$option_name_id 	= Tbl_option_name::insertGetId($insert_option_name);
				}

				$insert_variant_name['variant_name_order']	= $key;
				$insert_variant_name['variant_id']			= $variant_id;
				$insert_variant_name['option_name_id']		= $option_name_id;
				$insert_variant_name['option_value_id']		= $option_value_id;
				
				Tbl_variant_name::insert($insert_variant_name);
			}
		}
	}
	
	public function edit_variant($product_id)
	{

		if(Request::isMethod("post"))
		{
			return $this->edit_variant_submit();
		}
		else
		{
			$data["page"] 				= "Product Edit";
			$data["product"] 			= $product = Tbl_product::where("product_id", $product_id)->first();
			$data["image"] 				= Tbl_image::where("image_shop", $this->user_info->shop_id)->where("image_reason", "product")->where("image_reason_id", $product_id)->first();
			$data["_image"] 				= Tbl_image::where("image_shop", $this->user_info->shop_id)->where("image_reason", "product")->where("image_reason_id", $product_id)->get();
			// dd($data["_image"] );
			$data["_product_variant"] 	= Tbl_product::variant()->where("product_id",$product_id)->get();
			// dd($data["_product_variant"]);
			return view('member.product.product_edit_variant', $data);
			
		}
	}

	public function get_variant_item_info()
	{
		$variant_id 			= Request::input("variant_id");
		$product_id 			= Request::input("product_id");

		$json["variant"] 		= Tbl_product::variant()->where("tbl_variant.variant_id",$variant_id)->first();
		$json["variant_column"]	= Tbl_product::variant_column()->where("product_id",$product_id)->get();

		$json["mode"] = "success";
		echo json_encode($json);
	}

	public function get_variant_options()
	{
		$product_id 			= Request::input("product_id");

		$json["variant_value"] = Tbl_product::variant_values()->where("product_id",$product_id)->get();
		//dd($json["variant"]);
		$json["variant_column"]	= Tbl_product::variant_column()->where("product_id",$product_id)->get();

		$json["mode"] = "success";
		echo json_encode($json);
	}

	//ADD OR UPDATE VARIANT ITEM
	public function edit_variant_submit()
	{
		$product_id 	= Request::input("product_id");
		$variant_id 	= Request::input("variant_id");
		$_option_name 	= Request::input("option_name");
		$_option_value 	= Request::input("option_value");
		$option_value 	= '';
		$product_name	= Tbl_product::where("product_id",$product_id)->value("product_name");

		$data_variant["variant_price"] 					= Request::input("variant_price");
		$data_variant["variant_compare_price"] 			= Request::input("variant_compare_price");

		//Check if variant Exist
		$exist_variant = $this->check_exist_variant($product_id, $variant_id, $_option_value);

		if($exist_variant)
		{
			$json["mode"] 		= "error";
			$json["message"] 	= "Variant \"". $exist_variant ."\" already exist";
		}
		elseif($variant_id <> '') //UPDATE VARIANT
		{
			
			$variant_details 		= Tbl_variant::variant_name()->where("tbl_variant.variant_id", $variant_id)->get();
			
			foreach($variant_details as $variant)
			{
				foreach($_option_name as $key=>$option_name)
				{
					if($variant->option_name == $option_name)
					{
						$update["option_value"]	= $_option_value[$key];
						Tbl_option_value::where("option_value_id",$variant->option_value_id)->update($update);
					}
				}
			}

			$update_variant["variant_price"]				= $data_variant["variant_price"];
			$update_variant["variant_compare_price"]		= $data_variant["variant_price"] < $data_variant["variant_compare_price"] ? $data_variant["variant_compare_price"]  : 0;
			$update_variant["variant_charge_taxes"] 		= Request::input('variant_charge_taxes') == "checked" ? 1 : 0;
			$update_variant["variant_sku"]					= $this->add_submit_sku(Request::input("variant_sku"),$product_name);
			// $update_variant["variant_barcode"]				= Request::input('variant_barcode');
			// $update_variant["variant_track_inventory"]		= Request::input('variant_track_inventory');
			$update_variant["variant_allow_oos_purchase"]	= Request::input('variant_allow_oos_purchase') == "checked" ? 1 : 0;
			$update_variant["variant_inventory_count"]		= Request::input('variant_inventory_count');
			// $update_variant["variant_weight"]				= Request::input('variant_weight');
			// $update_variant['variant_weight_lbl']			= Request::input('variant_weight_lbl');
			$update_variant["variant_require_shipping"]		= Request::input('variant_require_shipping') == "checked" ? 1 : 0;
			$update_variant['variant_fulfillment_service']	= Request::input('variant_fulfillment_service');

			Tbl_variant::where("variant_id",$variant_id)->update($update_variant);
			
			Request::session()->flash('success', 'Variant successfully updated');
			$json["mode"] = "success";
			$json["type"] = "update";
		}
		else //ADD VARIANT
		{
			$data_variant["variant_product_id"] 			= $product_id;
			$data_variant["variant_date_visible"] 			= Carbon::now();
			$data_variant["variant_date_created"] 			= Carbon::now();
			$data_variant["variant_price"]					= $data_variant["variant_price"];
			$data_variant["variant_compare_price"]			= $data_variant["variant_price"] < $data_variant["variant_compare_price"] ? $data_variant["variant_compare_price"]  : 0;
			$data_variant["variant_charge_taxes"] 			= Request::input('variant_charge_taxes') == "checked" ? 1 : 0;
			$data_variant["variant_sku"]					= Request::input("variant_sku");
			// $data_variant["variant_barcode"]				= Request::input('variant_barcode');
			// $data_variant["variant_track_inventory"]		= Request::input('variant_track_inventory');
			$data_variant["variant_allow_oos_purchase"]		= Request::input('variant_allow_oos_purchase') == "checked" ? 1 : 0;
			$data_variant["variant_inventory_count"]		= Request::input('variant_inventory_count');
			// $data_variant["variant_weight"]					= Request::input('variant_weight');
			// $data_variant['variant_weight_lbl']				= Request::input('variant_weight_lbl');
			$data_variant["variant_require_shipping"]		= Request::input('variant_require_shipping') == "checked" ? 1 : 0;
			$data_variant['variant_fulfillment_service']	= Request::input('variant_fulfillment_service');
			$data_variant['variant_main_image']				= 
			$data_variant['variant_main_image']				= Request::input('image_main_id');
			
			$get_variant_id = Tbl_variant::insertGetId($data_variant);

			foreach($_option_name as $key=>$option_name)
			{
				$insert_option_value['option_value']	= $_option_value[$key];

				$option_name_id 	= Tbl_option_name::where("option_name",$option_name)->value('option_name_id');
				$option_value_id	= Tbl_option_value::insertGetId($insert_option_value);

				$insert_variant_name['variant_name_order']	= $key;
				$insert_variant_name['variant_id']			= $get_variant_id;
				$insert_variant_name['option_name_id']		= $option_name_id;
				$insert_variant_name['option_value_id']		= $option_value_id;
				
				Tbl_variant_name::insert($insert_variant_name);
			}
			
			Request::session()->flash('success', 'Variant successfully added');
			$json["mode"] = "success";
			$json["type"] = "add";
			$variant_id   = $get_variant_id;
		}

		
		$json["product_id"] = $product_id;
		$json["variant_id"] = $variant_id;
		echo json_encode($json);
	}

	public function check_exist_variant($product_id, $variant_id, $_option_value)
	{
		$option_value = '';

		foreach($_option_value as $key=>$value)
		{
			$option_value = $option_value.$value;
			if(count($_option_value) > $key+1)
			{
				$option_value = $option_value." • ";
			}
		}
		$exist_variant 	= View_product_variant::where("product_id",$product_id)->where("variant_id","<>",$variant_id)->where("variant_name",$option_value)->first();

		if($exist_variant)
		{
			return $option_value;
		}
		else
		{
			return false;
		}
	}

	public function save_option_name()
	{
		$_option_name 		= Request::input('option_name');
		$_option_name_id 	= Request::input('option_name_id');
		$product_id 		= Request::input('product_id');
		
		foreach($_option_name as $key=>$option_name)
		{
			// $data[$option_name]		= $option_name;
			// $rules[$option_name]	= "required|different:";

			$option_name_id = Tbl_option_name::where("option_name", $option_name)->value("option_name_id");
			if($option_name_id)
			{
				$update["tbl_variant_name.option_name_id"] 	= $option_name_id;
			}
			else
			{
				$insert["option_name"]						= $option_name;
				$update["tbl_variant_name.option_name_id"] 	= Tbl_option_name::insertGetId($insert);
			}

			// $validator = Validator::make($data, $rules);
		
			// if ($validator->fails())
			// {
			// 	$json["mode"] = "error";
			// 	$json["message"] = $validator->errors()->first();
			// 	dd($json["message"]);
			// }

			Tbl_variant::variant_name()->where("variant_product_id", $product_id)->where("tbl_variant_name.option_name_id",$_option_name_id[$key])->update($update);
		}

		return Redirect::back();
	}

	public function remove_option_value()
	{
		$product_id 		= Request::input('product_id');
		$option_value		= Request::input('option_value');
		Tbl_variant::variant_name()->where("variant_product_id", $product_id)->where("option_value", $option_value)->delete();

		return Redirect::back();
	}


	public function delete_variant($variant_id)
	{
		/* REMOVE THE QUERY STRING IN THE URL */
		$getUrl = Redirect::back()->getTargetUrl();
		// $link	= reset((explode('?', $getUrl)));
		Tbl_ec_variant::where("evariant_id", $variant_id)->delete();
		
		Request::session()->flash('success', 'Variant successfully deleted');
		return Redirect::back();
	}

	public function save_image_variant()
	{
		$variant_id 					= Request::input("variant_id");
		$update["variant_main_image"]	= Request::input("image_id");
		$product_id 					= Tbl_variant::where("variant_id",$variant_id)->value("variant_product_id");

		Tbl_variant::where("variant_id", $variant_id)->update($update);

		return Redirect::to("/member/product/edit/variant/" .$product_id ."?variant_id=" .$variant_id);
	}

	public function add_submit_main_image($product_id = 0)
	{
		return Tbl_image::where('image_reason_id', $product_id)->where('image_reason', 'product')->where('image_shop', $this->user_info->shop_id)->value('image_id');
	}
	
	public function add_submit_vendor($select, $input)
	{
		if($select == "new")
		{
			$insert["vendor_name"] = $input;
			$insert["vendor_shop"] = $this->user_info->shop_id;
			$insert["vendor_date_created"] = Carbon::now();
			return Tbl_product_vendor::insertGetId($insert);
		}
		else
		{
			return $select;
		}
	}
	
	public function add_submit_type($select, $input)
	{
		if($select == "new")
		{
			$insert["type_name"] = $input;
			$insert["type_shop"] = $this->user_info->shop_id;
			$insert["type_date_created"] = Carbon::now();
			return Tbl_category::insertGetId($insert);
		}
		else
		{
			return $select;
		}
	}
	
	public function add_submit_sku($input, $name)
	{
		if(empty($input))
		{
			$sku = str_slug($name);
			return strtoupper(uniqid($sku . '-'));	
		}
		else
		{
			return $input;
		}
	}
	
	public function delete_image()
	{
		$image_id = Request::input("image_id");
		$shop_id = $this->user_info->shop_id;
		$product_id = Request::input("product_id");
		$image_info = Tbl_image::where("image_shop", $shop_id)->where("image_id", $image_id)->first();
		deleteDir("upload/member-" . $this->user_info->shop_id . "/product-" . $product_id . "/" . $image_info->image_key);
		Tbl_image::where("image_shop", $shop_id)->where("image_id", $image_id)->delete();
		return json_encode($image_id);
	}
	
	public function upload()
	{	
		
		$image_id = 0;
		$file_extension =  pathinfo($_FILES['SelectedFile']['name'], PATHINFO_EXTENSION);
		$file_name =  "image." . $file_extension;
		$product_id = Request::input("product_id");
		$key = uniqid();

		$upload_dir = "upload/member-" . $this->user_info->shop_id . "/product-" . $product_id . "/" . $key . "/original";
		$upload_path = $upload_dir . "/" . $file_name;
		createPath($upload_dir);

		$tiny_dir = str_replace("original","150x150",$upload_dir);
		$tiny_path =  $tiny_dir . "/" . $file_name;
		createPath($tiny_dir);

		$crop_dir = str_replace("original","300x300",$upload_dir);
		$crop_path =  $crop_dir . "/" . $file_name;
		createPath($crop_dir);


		$medium_dir = str_replace("original","medium",$upload_dir);
		$medium_path =  $medium_dir . "/" . $file_name;
		createPath($medium_dir);


		$optimize_dir = str_replace("original","optimize",$upload_dir);
		$optimize_path =  $optimize_dir . "/" . $file_name;
		createPath($optimize_dir);
		
		$status = "success";

		// Check for errors
		if($_FILES['SelectedFile']['error'] > 0)
		{
			$status = "error";
			$message = 'An error ocurred when uploading.';
		}

		if(!getimagesize($_FILES['SelectedFile']['tmp_name']))
		{
			$status = "error";
			$message = 'Please ensure you are uploading an image.';
		}

		// Check filetype
		if(($_FILES['SelectedFile']['type'] != 'image/jpeg')  && ($_FILES['SelectedFile']['type'] != 'image/png')  && ($_FILES['SelectedFile']['type'] != 'image/jpg'))
		{
			$status = "error";
			$message = 'Unsupported filetype uploaded.';
		}
		
		// Check filesize
		if($_FILES['SelectedFile']['size'] > 1000000)
		{
			$status = "error";
			$message = 'File uploaded exceeds maximum upload size (1 MB).';
		}

		// Upload file
		if(!move_uploaded_file($_FILES['SelectedFile']['tmp_name'], $upload_path))
		{
			$status = "error";
			$message = 'Error uploading file - check destination is writeable.';
		}

		if($status == "success")
		{
			$image_id = 0;
			/* Resize Images */
			Image::make($upload_path)->fit(300, 300)->save($crop_path, 60);
			Image::make($upload_path)->fit(150, 150)->save($tiny_path, 60);
			Image::make($upload_path)->resize(600, 600, function ($constraint)
			{
			    $constraint->aspectRatio();
			})->save($medium_path, 60);
			Image::make($upload_path)->save($optimize_path, 60);

			/* Save Image on DB */
			$insert_image["image_path"] = "/" . $crop_path; 
			$insert_image["image_shop"] = $this->user_info->shop_id;
			$insert_image["image_reason"] = "product";
			$insert_image["image_reason_id"] = $product_id;
			$insert_image["image_date_created"] = Carbon::now();
			$insert_image["image_key"] = $key;
			$image_id = Tbl_image::insertGetId($insert_image);
			$status = "success";
			$message = "/" . $crop_path;

			deleteDir($upload_dir);
		}

		$json["status"] = $status;
		$json["message"] = $message;
		$json["image_id"] = $image_id;
		echo json_encode($json);
	}
	
	
	//collection start
	
	public function collections(){
		$shop_id = $this->user_info->shop_id;
		$data['_collection'] = Tbl_collection::join('tbl_collection_item','tbl_collection_item.collection_id','=','tbl_collection.collection_id')
											->where('tbl_collection.shop_id',$shop_id)
											->where('tbl_collection.status','saved')
											->where('tbl_collection.archived',0)
											->where('tbl_collection_item.archived',0)
											->select(DB::raw('tbl_collection.*, count(tbl_collection_item.collection_item_id) as totalItem'))
											->groupBy('tbl_collection.collection_id')
											->get();
											
		return view('member.product.collection.index',$data);
		
	}
	
	public function collectionvisibility(){
		$id = Request::input('id');
		$check = Request::input('check');
		$arr['id'] = $id;
		$arr['check'] = $check;
		// dd($arr);
		$hide = 0;
		if($check == 'false'){
			$hide = 1;
		}
		$update['hide'] = $hide;
		Tbl_collection::where('collection_id',$id)->update($update);
	}
	
	public function createcollection(){
		$shop_id = $this->user_info->shop_id;
		$countCollection = Tbl_collection::where('shop_id',$shop_id)->where('status','new')->count();
		if($countCollection == 0){
			$insertCollection['shop_id'] = $shop_id;
			$insertCollection['status'] = 'new';
			$insertCollection['date_created'] = Carbon::now();
			$collection_id = Tbl_collection::insertGetId($insertCollection);
		}
		$collection = Tbl_collection::where('shop_id',$shop_id)->where('status','new')->first();
		$data['_item'] = $this->dataItem($collection->collection_id);
		// dd($_item);
									
		$data['collection']	= $collection;
		
		return view('member.product.collection.create',$data);
	}
	
	public function dataitem($collection_id = 0){
		$_item = Tbl_collection_item::join('view_product_variant','view_product_variant.variant_id','=','tbl_collection_item.variant_id')
											->join('tbl_image','tbl_image.image_id','=','view_product_variant.variant_main_image')
											->where('tbl_collection_item.archived',0)
											->where('tbl_collection_item.collection_id',$collection_id)
											->orderBy('product_name','asc')
											->get();
		$dataItem = '';
		foreach($_item as $key => $item){
			$dataItem[$key]['product_name'] = $item->product_name;
			$dataItem[$key]['variant_id'] = $item->variant_id;
			$dataItem[$key]['hide'] = $item->hide;
			$dataItem[$key]['collection_item_id'] = $item->collection_item_id;
			$dataItem[$key]['variant_price'] = $item->variant_price;
			$dataItem[$key]['variant_sku'] = $item->variant_sku;
			// $dataItem[$key]['variant_barcode'] = $item->variant_barcode;
			$dataItem[$key]['variant_inventory_count'] = $item->variant_inventory_count;
			// $dataItem[$key]['variant_track_inventory'] = $item->variant_track_inventory;
			$dataItem[$key]['variant_allow_oos_purchase'] = $item->variant_allow_oos_purchase;
			$dataItem[$key]['variant_main_image'] = $item->image_path;
			$ex_name = explode('•',$item->variant_name);
			$varnames = '';
			foreach($ex_name as $name){
				if($varnames != ''){
					$varnames.='/';
				}
				$varnames.=$name;
			}
			$dataItem[$key]['variant_name'] = $varnames;
		}	
		return $dataItem;
	}
	
	
	public function editcollection($id){
		$id = Crypt::decrypt($id);
		$data['collection'] = Tbl_collection::where('collection_id',$id)->first();
		$data['_item'] = $this->dataitem($id);
		return view('member.product.collection.edit', $data);
	}
	
	public function updatecollection(){
		$collection_name = Request::input('collection_name');
		$note = Request::input('notes');
		$id = Request::input('id');
		$update['collection_name'] = $collection_name;
		$update['note'] = $note;
		Tbl_collection::where('collection_id',$id)->update($update);
		return Redirect::to('/member/product/collection');
	}
	
	public function updateitemCOllection(){
		
	}
	
	public function additemcollection(){
		$shop_id = $this->user_info->shop_id;
		$countCollection = Tbl_collection::where('shop_id',$shop_id)->where('status','new')->count();
		$collection_id = 0;
		if($countCollection == 0){
			$insertCollection['shop_id'] = $shop_id;
			$insertCollection['status'] = 'new';
			$insertCollection['date_created'] = Carbon::now();
			$collection_id = Tbl_collection::insertGetId($insertCollection);
		}
		else{
			$col = Tbl_collection::where('shop_id',$shop_id)->where('status','new')->first();
			$collection_id = $col->collection_id;
		};
		
		
		$insert = $this->insertItem($collection_id);
		if(!empty($insert)){
			Tbl_collection_item::insert($insert);
		}
		$data['_item'] = $this->dataitem($collection_id);
		
		return view('member.product.collection.tbl', $data);
		
	}
	
	public function insertItem($collection_id){
		$shop_id = $this->user_info->shop_id;
		$_product = Tbl_product::where('product_shop',$shop_id)->get();
		$insert = array();
		$num = 0;
		$temp = 0;
		foreach($_product as $product){
			$product_id = $product->product_id;
			if(Request::has('main_check_box_'.$product_id)){
				$varq = Tbl_variant::where('variant_product_id',$product_id);
				$_variant = $varq->get();
				$count = $_variant->count();
				// dd($_variant);
				if($count == 1){
					$var = $varq->first(); 
					$countItem = Tbl_collection_item::where('collection_id',$collection_id)->where('variant_id',$var->variant_id)->count();
					if($countItem == 0){
						$insert[$num]['collection_id'] = $collection_id;
						$insert[$num]['variant_id'] = $var->variant_id;
						$insert[$num]['date_created'] =  Carbon::now();
						$num++;
					}
					else{
						$collectionItemId = Tbl_collection_item::where('collection_id',$collection_id)->where('variant_id',$var->variant_id)->first();
						$update['archived'] = 0;
						Tbl_collection_item::where('collection_item_id',$collectionItemId->collection_item_id)->update($update);
					}
					
				}
				
				
			}
			
		}
		$item_count = Request::input('item_count') - 1;
		for($i = 1; $i <= $item_count; $i++){
			if(Request::has('child_check_box_'.$i)){
				$countItem = Tbl_collection_item::where('collection_id',$collection_id)->where('variant_id',Request::input('child_check_box_'.$i))->count();
				if($countItem == 0){
					$insert[$num]['collection_id'] = $collection_id;
					$insert[$num]['variant_id'] = Request::input('child_check_box_'.$i);
					$insert[$num]['date_created'] =  Carbon::now();
					$num++;
				}
				else{
					$collectionItemId = Tbl_collection_item::where('collection_id',$collection_id)->where('variant_id',Request::input('child_check_box_'.$i))->first();
					$update['archived'] = 0;
					Tbl_collection_item::where('collection_item_id',$collectionItemId->collection_item_id)->update($update);
				}
				
			}
			
		}
		return $insert;
	}
	
	
	public function collectionitemvisibility(){
		$id = Request::input('id');
		$check = Request::input('check');
		$arr['id']= $id;
		$arr['check'] = $check;
		// dd($arr);
		$hide = 0;
		if($check == 'false'){
			$hide = 1;
		}
		$update['hide'] = $hide;
		Tbl_collection_item::where('collection_item_id',$id)->update($update);
		
	}
	public function removeitemcollection(){
		$id = Request::input('id');
		$update['archived'] = 1;
		Tbl_collection_item::where('collection_item_id',$id)->update($update);
	}
	public function saveCollection(){
		$collection_name = Request::input('collection_name');
		$notes = Request::input('notes');
		$shop_id = $this->user_info->shop_id;
		$count = Tbl_collection::where('shop_id',$shop_id)->where('status','new')->count();
		$update['collection_name'] = $collection_name;
		$update['note'] = $notes;
		$update['status'] = 'saved';
		if($count == 0){
			$update['date_created'] = Carbon::now();
			Tbl_collection::insert($update);
		}
		else{
			$collection = Tbl_collection::where('shop_id',$shop_id)->where('status','new')->first();
			Tbl_collection::where('collection_id',$collection->collection_id)->update($update);
		}
		return Redirect::to('/member/product/collection');
	}

	public function insertSearchTemp()
	{
		// $_data = DB::table('view_product_variant')->get();
		// $insert = '';
		// // dd($_data);
		// foreach($_data as $key => $data)
		// {
		// 	$insert[$key]['variant_id'] = $data->variant_id;
		// 	$insert[$key]['body'] = $data->product_name .' '. $data->variant_name .' '. $data->variant_sku .' '. $data->variant_barcode;
		// }
		// Tbl_product_search::insert($insert);
		return Redirect::to('/');
	}
	//collection end

	// public function searchproduct()
	// {	
	// 	$shop_id = $this->user_info->shop_id;
	// 	$search = Request::input('search');
	// 	$data['_item'] = Variant::search($shop_id, $search);
	// 	return view('');
	// }
}