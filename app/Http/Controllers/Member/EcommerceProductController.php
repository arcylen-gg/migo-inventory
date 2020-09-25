<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_image;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_ec_variant;
use App\Models\Tbl_ec_variant_image;
use App\Models\Tbl_variant_name;
use App\Models\Tbl_option_name;
use App\Models\Tbl_option_value;
use App\Models\Tbl_item;
use App\Models\Tbl_product;
use App\Models\Tbl_user;

use App\Globals\Variant;
use App\Globals\Item;
use App\Globals\Ecom_Product;
use App\Globals\Category;
use App\Globals\Utilities;
use App\Globals\Warehouse;

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

/**
 * Ecommerce Product Module - all product related module
 *
 * @author Bryan Kier Aradanas
 */

class EcommerceProductController extends Member
{
	public function hasAccess($page_code, $acces)
    {
        $access = Utilities::checkAccess($page_code, $acces);
        if($access == 1) return true;
        else return false;
    }

	public function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	public function load_product_category()
	{
		$data["_product"] 	= Ecom_Product::getProductList();
		$data["add_search"]	= "";

		return view('member.load_ajax_data.load_product_category', $data);
	}

	public function getList()
	{
		if($this->hasAccess("product-list","access_page"))
        {	

        	$warehouse_id = Ecom_Product::getWarehouseId();
        	
        	$active_product 	= Tbl_ec_product::itemVariant()->inventory($warehouse_id)->where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 0)->orderBy("inventory_count","DESC");
        	$sort_as = Request::input("in_order");
        	$column_name = Request::input("column_name");
        	if($column_name && $sort_as)
        	{
	        	$active_product 	= Tbl_ec_product::itemVariant()->inventory($warehouse_id)->where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 0)->orderBy($column_name,$sort_as);
        	}

        	$data['active_tab'] = 'active';
        	$data['inactive_tab'] = '';
        	
			$inactive_product	= Tbl_ec_product::where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 1);
        	if(Request::input('filter') == "inactive")
        	{
				$inactive_product	= Tbl_ec_product::where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 1)->orderBy($column_name,$sort_as);

	        	$data['active_tab'] = '';
	        	$data['inactive_tab'] = 'active';
        	}

			$search = Request::input('search');
			if($search)
			{
				$active_product 	= $active_product->where("eprod_name","like","%$search%");
				$inactive_product 	= $inactive_product->where("eprod_name","like","%$search%");
			}

			$active_product 	= $active_product->paginate(10);
			$inactive_product 	= $inactive_product->paginate(10);

	        $data["_product"]			= $active_product;
	        $data["_product_archived"]	= $inactive_product;

			return view('member.ecommerce_product.ecom_product', $data);
		}
        else
        {
            return $this->show_no_access();
        }
	}
	public function ecom_load_product_table()
	{	
		$data["filter"] = 'active';

        $warehouse_id = Ecom_Product::getWarehouseId();
    	$active_product 	= Tbl_ec_product::itemVariant()->inventory($warehouse_id)->where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 0)->orderBy("inventory_count","DESC");
    	$sort_as = Request::input("sort_as");
    	$column_name = Request::input("column_name");
    	if($column_name && $sort_as)
    	{
        	$active_product 	= Tbl_ec_product::itemVariant()->inventory($warehouse_id)->where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 0)->orderBy($column_name,$sort_as);
    	}

		$inactive_product	= Tbl_ec_product::where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived", 1);

		$search = Request::input('search');
		if($search)
		{
			$active_product 	= $active_product->where("eprod_name","like","%$search%");
			$inactive_product 	= $inactive_product->where("eprod_name","like","%$search%");
		}

		$active_product 	= $active_product->paginate(10);
		$inactive_product 	= $inactive_product->paginate(10);

        $data["_product"]			= $active_product;
        $data["_product_archived"]	= $inactive_product;


		return view('member.ecommerce_product.ecom_load_product_tbl', $data);
	}
	public function getAdd()
	{
        if($this->hasAccess("product-list","add"))
        {
			$data["page"]		= "Product Add";
			$data["_image"] 	= Tbl_image::where("image_shop", $this->user_info->shop_id)->where("image_reason", "product")->where("image_reason_id", 0)->get();
			$data["_item"]  	= Item::get_all_item();
			$data['_category']  = Category::getAllCategory();

			return view('member.ecommerce_product.ecom_product_add', $data);
		}
		else
		{
			return $this->show_no_access();
		}
	}

	public function postAdd()
	{
		// dd(Session::get('product_info'));
		$button_action 	= Request::input('button_action');

		/* START OF VALIDATION */
		$message = [];

		$variant_checked			= Request::input('variant_checked');
		$value["product_label"] 	= Request::input('eprod_name');
		$value["product_category"] 	= Request::input('eprod_category_id');

		$rules["product_label"] 	= "required";
		$rules["product_category"] 	= "required";

		/* validation of the item */
		$item_id		= Request::input("evariant_item_id"); 
		$variant_price	= Request::input("evariant_price"); 
		$item_code		= Request::input("item_code");

		$custom_validation_fails = false;
		
		// dd($this->hasDuplicate($item_id));

		foreach($item_id as $key => $item)
		{
			$item_data		= Tbl_item::where("item_id", $item)->first();
			$product_info 	= $this->session_product_info($item_code[$key]);
			if($variant_checked[$key] == 'true')
			{
				/* Custom Validation For Unique Items in 1 Product */
				if($this->hasDuplicate($item_id) != false)
				{
					$custom_validation_fails = true;
					$custom_message = "The item ".$item_data->item_name ." cannot be duplicate";
				}

				if($item_data)
				{

					$value["evariant_price"] = $variant_price[$key];
					$rules["evariant_price"] = 'required';
					$message["evariant_price.required"] = 'You have to set a price for product '.$item_data->item_name;

					/* Custom Validation For 2 Unique Columns */
					$item_exist = Tbl_ec_variant::product()->where("evariant_item_id", $item_id[$key])->where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived",0)->first();
					if($item_exist) 
					{
						$custom_validation_fails = true;
						$custom_message = "Item ".$item_data->item_name." is already used";
					}
				}
				else
				{
					$value["variant_item"] = $item;
					$rules["variant_item"] = "required";
					$message["variant_item.required"] = "The variant item field is required on checked";
				}
			}
		}

		if(Request::input('product_variant_type') == "multiple") //RULES FOR PRODUCT WITH VARIATION
		{
			/* OPTION RULES */
			foreach(Request::input("option_name") as $key => $option_list) 
			{
				$value["option_" . $key] = $option_list;
				$rules["option_" . $key] = "required";
				$message["option_" . $key . ".required"] = "You need to add option for your variation.";
			}
		}

		/* END OF VALIDATION */

		$validator = Validator::make($value, $rules, $message);
		
		if ($validator->fails()) //fail to add product
		{
			$json["status"] 	= "error";
			$json["message"] 	= $validator->errors()->first(); 
		}
		else if($custom_validation_fails)
		{
			$json["status"] 	= "error";
			$json["message"] 	= $custom_message; 
		}
		else
		{
			/* INSERT PRODUCT */
			$insert_product["eprod_shop_id"] 		= $this->getShopId();
			$insert_product["date_created"] 		= Carbon::now(); 
			$insert_product["eprod_is_single"]		= Request::input('product_variant_type') == 'single' ? 1 : 0;
			$insert_product["eprod_name"]			= Request::input('eprod_name');
			$insert_product["eprod_category_id"]	= Request::input('eprod_category_id');
			$insert_product["eprod_details"]		= Request::input('eprod_details');
			
			$product_id = Tbl_ec_product::insertGetId($insert_product);

			$this->InsertVariantInfo($product_id, $insert_product["eprod_is_single"]);

			$json["status"] 	= "success";
			$json["call_function"] 	= "success_product_list";
			$json["type"]	 	= "add";
			$json["product_id"] = $product_id;
			if($button_action == "save-and-edit")
			{
				if($this->hasAccess("product-list","edit"))
				{
					$json["redirect"]	= '/member/ecommerce/product/edit/'.$product_id;
				}
				else
				{
					$json["redirect"]	= '/member/ecommerce/product/list';
				}
			}
			elseif($button_action == "save-and-new")
			{
				$json["redirect"]	= '/member/ecommerce/product/add';
			}
			else
			{
				$json["redirect"]	= '/member/ecommerce/product/edit/'.$product_id;
			}
			Request::session()->flash('success', 'Product Successfully Added');
		}

		return json_encode($json);
	}

	public function InsertVariantInfo($product_id, $is_single)
	{
		$option_name	= Request::input("option_name");
		$option_value	= Request::input('option_value');
		$combination	= Request::input("variant_combination");
		$variant_checked= Request::input("variant_checked");
		$item_id		= Request::input("evariant_item_id"); 
		$variant_price	= Request::input("evariant_price"); 
		$item_code		= Request::input("item_code"); 
		$item_qty		= Request::input("item_qty_refill");
		
		foreach($item_id as $key => $item){
			
			/* Product Information */

			$product_info = $this->session_product_info($item_code[$key]);
			$item_data		= Tbl_item::where("item_id", $item)->first();

			if($variant_checked[$key] == 'true')
			{
				$insertVariant['evariant_prod_id']		= $product_id;
				$insertVariant['evariant_item_id']		= $item;
				$insertVariant["evariant_item_label"] 	= $product_info ? $product_info["product_label"] : $item_data->item_name;
				$insertVariant["evariant_description"] 	= $product_info ? $product_info["product_description"] : '';
				$insertVariant["evariant_price"] 		= convertToNumber($variant_price[$key]);
				$insertVariant["date_created"] 			= Carbon::now();
				$insertVariant["date_visible"] 			= Carbon::now();

				$variant_id = Tbl_ec_variant::insertGetId($insertVariant);

				/* PRODUCT IMAGE */
				if($product_info["product_image"])
				{
					foreach($product_info["product_image"] as $image)
					{
						$insert_image["eimg_variant_id"]	= $variant_id;
						$insert_image["eimg_image_id"]		= $image;
						Tbl_ec_variant_image::insert($insert_image);
					}
				}
			
				if($is_single == 0)
				{
					$excombination = explode(",", $combination[$key]);

					foreach($excombination as $key2 => $excom){
						
						$insert_option_value['option_value']	= $excom;
						$insert_option_name['option_name']		= strtolower($option_name[$key2]);
															
						$option_value_id	= Tbl_option_value::insertGetId($insert_option_value);
						$option_name_data 	= Tbl_option_name::where("option_name",$insert_option_name['option_name'])->first();
						if($option_name_data)
						{
							$option_name_id 	= $option_name_data->option_name_id;
						}
						else
						{
							$option_name_id 	= Tbl_option_name::insertGetId($insert_option_name);
						}

						$insert_variant_name['variant_name_order']	= $key2;
						$insert_variant_name['variant_id']			= $variant_id;
						$insert_variant_name['option_name_id']		= $option_name_id;
						$insert_variant_name['option_value_id']		= $option_value_id;
						
						Tbl_variant_name::insert($insert_variant_name);
					}
				}

				/* ITEM REFILL */
				$this->refillItemInventory($item, $item_qty[$key]); 
			}
		}

		return null;
	}

	public function refillItemInventory($item_id, $item_qty) 
	{ 
		$warehouse_id 		= Ecom_Product::getWarehouseId();
		$warehouse_remarks 	= "Initial Quantity on Hand For Product";
		$return_type		= "array";

		$warehouse_refill_product[0]['product_id'] 	= $item_id;
		$warehouse_refill_product[0]['quantity'] 	= $item_qty;


		Warehouse::inventory_refill($warehouse_id, '', 0, $warehouse_remarks, $warehouse_refill_product, $return_type);
	}

	public function postSaveProductInfo()
	{
		/* SAVING IN SESSION */
		$code = Request::input("product_code");

		$session_data 								= Session::get("product_info");
		$session_data[$code]["item_code"] 			= $code;
		$session_data[$code]["item_id"] 			= Request::input("item_id");
		$session_data[$code]["product_label"] 		= Request::input("evariant_item_label");
		$session_data[$code]["product_description"]	= Request::input("evariant_description");
		$session_data[$code]["product_image"]		= Request::input("image_id");
		$session_data[$code]["product_image_path"]	= Request::input("product_image");

		Session::put("product_info", $session_data);
		$json["status"] = "success";
		$json["type"] 	= "product-info";
		$json["data"]	= Session::get("product_info");

		return json_encode($json);
	}

	public function getVariantModal($product_code, $item_id)
	{
		$data["item_info"] 		= Tbl_item::where("item_id", $item_id)->first();
		$data["product_code"] 	= $product_code;

		$session_code = Session::get("product_info.".$product_code);
		$data["product_data"] = $session_code;


		return view('member.ecommerce_product.ecom_variant_modal', $data);
	}

	public function ProductInfo($id,  $separator = ',', $type = 'product')
	{
		$data["_column"] = [];
		$warehouse_id	 = Ecom_Product::getWarehouseId();

		if($type == 'product')
		{
			$data["product"]	= Tbl_ec_product::where("eprod_id", $id)->first();
			$data["_variant"]	= Tbl_ec_variant::variantNameValue($separator)->item()->inventory($warehouse_id)->firstImage()->where("evariant_prod_id", $id)->get();
		
			foreach($data["_variant"] as $key=>$variant)
			{                                                                                                                                                                                                       
				$data["_variant"][$key] = $variant;

				$explode_option_name  = explode($separator, $variant->option_name);
				$explode_option_value = explode($separator, $variant->variant_name);
				
				foreach($explode_option_name as $key2=>$option_name)
				{
					$data["_variant"][$key]->variation 	= $explode_option_value;
					$data["_column"][$key2]		   		= $option_name;
				}
			}
		}
		elseif($type == 'variant')
		{
			$data["_variant"]	= Tbl_ec_variant::variantNameValue($separator)->item()->inventory($warehouse_id)->where("evariant_id", $id)->first();

			if($data["_variant"])
			{
				$explode_option_name  = explode($separator, $data["_variant"]->option_name);
				$explode_option_value = explode($separator, $data["_variant"]->variant_name);
				
				foreach($explode_option_name as $key2=>$option_name)
				{
					$data["_variant"]->variation 	= $explode_option_value;
					$data["_column"][$key2]		   		= $option_name;
				}

				$data["_variant"]->image = Tbl_ec_variant::imageOnly()->where("evariant_id", $id)->get();
			}
		}
		else
		{
			return "$type type is Invalid";
		}

		return $data;
	}

	public function getEdit($id)
	{
		if($this->hasAccess("product-list","edit"))
        {
			$product_data		= $this->ProductInfo($id);
			$data['_category']  = Category::getAllCategory();
			$data["product"]	= $product_data["product"];
			$data["_variant"]	= $product_data["_variant"];
			$data["_item"]  	= Item::get_all_item();
			$data["_column"]	= $product_data["_column"];

			/* IF PRODUCT IS SINGLE VARIANT */
			if($data["product"]->eprod_is_single == 1)
			{
				$variant_data   = collect($data["_variant"])->first();
				$product_variant_data	= $this->ProductInfo(collect($variant_data)->pull('evariant_id'), ' • ', 'variant');
				$data["variant"] 		= $product_variant_data["_variant"];;
				// dd($data["variant"]);
			}

			return view('member.ecommerce_product.ecom_product_edit', $data);
		}
		else
		{
			return $this->show_no_access();
		}
	}

	public function postEdit($product_id)
	{
		$button_action = Request::input('button_action');

		$update_product["eprod_name"] 			= Request::input('eprod_name');
		// $update_product["eprod_detail_image"]   = Request::input('eprod_detail_image');
		$update_product["eprod_category_id"] 	= Request::input('eprod_category_id');
		$update_product["eprod_details"] 		= Request::input('eprod_details');

		Tbl_ec_product::where("eprod_id", $product_id)->update($update_product);

		/* IF A VARIANT ID IS AVAILABLE  (FOR SINGLE VARIATION) */
		if(Request::input('variant_id'))
		{
			$this->postUpdateVariant();
		}

		$json["status"] 	= "success";
		$json["product_id"]	= $product_id;


		if($button_action == "save-and-close")
		{
			$json["redirect"]	= '/member/ecommerce/product/list';
		}
		elseif($button_action == "save-and-new")
		{
			$json["redirect"]	= '/member/ecommerce/product/add';
		}
		Request::session()->flash('success', 'Product Successfully Updated');

		return json_encode($json);
	}

	public function getEditOption($product_id)
	{
		$data["product_id"] = $product_id;

		$_option = Tbl_ec_product::where("eprod_id", $product_id)->variantOption()->get();
		foreach($_option as $key=>$option)
		{
			$data["_option"][$key]					= $option;
			$data["_option"][$key]->variant_values 	= explode(',', $option->variant_value);
		}

		return view('member.ecommerce_product.ecom_edit_options_modal', $data);
	}

	public function postChangeOption()
	{

		$product_id 		= Request::input('product_id');
		$action_type		= Request::input('action_type');

		if($action_type == 'update')
		{
			$_option_name 		= Request::input('option_name');
			$_option_name_id 	= Request::input('option_name_id');
			
			foreach($_option_name as $key=>$option_name)
			{

				$option_name_id = Tbl_option_name::where("option_name", $option_name)->value("option_name_id");
				if($option_name_id)
				{
					$update["option_name_id"] 	= $option_name_id;
				}
				else
				{
					$insert["option_name"]		= strtolower($option_name);
					$update["option_name_id"] 	= Tbl_option_name::insertGetId($insert);
				}

				Tbl_ec_variant::variantOnly()->where("evariant_prod_id", $product_id)->where("option_name_id",$_option_name_id[$key])->update($update);
			}
		}
		elseif($action_type == 'delete')
		{
			$option_value = Request::input('option_value');

			Tbl_ec_variant::optionValue()->where("evariant_prod_id", $product_id)->where("option_value", $option_value)->delete();
		}

		$json["status"]		= "success";
		$json["redirect"]	= '/member/ecommerce/product/edit/'.$product_id;

		return json_encode($json);
	}

	public function getEditVariant($product_id)
	{
		$product_data		= $this->ProductInfo($product_id, ' • ');
		$data["product"]	= $product_data["product"];
		$data["_variant"]	= $product_data["_variant"];
		$data["_item"]  	= Item::get_all_item();
		$data["_column"]	= $product_data["_column"];
		
		return view('member.ecommerce_product.ecom_product_edit_variant', $data);
	}

	/* FOR AJAX - INFORMATION BLADE OF VARIANT */
	public function getProductVariantInfo($variant_id, $default)
	{
		$data["_item"]  		= Item::get_all_item();
		$data["variant_id"] 	= $variant_id;
		$product_variant_data	= $this->ProductInfo($data["variant_id"], ' • ', 'variant');
		$data["variant"]		= $product_variant_data["_variant"];
		$data["_column"]		= $product_variant_data["_column"];
		$data["default"]		= $default;
		$data["product"]		= tbl_ec_product::where("eprod_id", $data["variant"]->evariant_prod_id)->first();

		return view('member.ecommerce_product.ecom_edit_variant', $data);
	}

	public function postUpdateVariant()
	{
		$product_id 	= Request::input("product_id");
		$is_single 		= Tbl_ec_product::where("eprod_id", $product_id)->value("eprod_is_single");
		$variant_id 	= Request::input("variant_id");
		$_option_name 	= Request::input("option_name");
		$_option_value 	= Request::input("option_value");

		/* VALIDATE */
		$validate = $this->VariantValidate($is_single);

		if($validate != 'validated')
		{
			$json["status"] 	= "error";
			$json["message"] 	= $validate;
			return json_encode($json);
		}

		if($variant_id == '')
		{
			$variant_id = $this->AddVariant($product_id);

			Request::session()->flash('success', 'Product Successfully Added');
			$json["redirect"] = "/member/ecommerce/product/edit-variant/".$product_id."?variant_id=".$variant_id;
		}
		else
		{
			/* UPDATE VARIANT */ 
			$update_variant["evariant_item_id"] 	= Request::input("evariant_item_id");
			$update_variant["evariant_item_label"] 	= Request::input("evariant_item_label");
			$update_variant["evariant_description"] = Request::input("evariant_description");
			$update_variant["evariant_price"] 		= convertToNumber(Request::input("evariant_price"));

			Tbl_ec_variant::where("evariant_id", $variant_id)->update($update_variant);

			$update_product["eprod_detail_image"]   = Request::input('eprod_detail_image');

			Tbl_ec_product::where("eprod_id", $product_id)->update($update_product);

			/* UPDATE IMAGE */
			$default_image = Request::input("product_main_image");

			$_image = Request::input("image_id");
			Tbl_ec_variant_image::where("eimg_variant_id", $variant_id)->delete();
			if($_image)
			{
				foreach($_image as $image)
				{
					$insert_img["eimg_variant_id"] 	= $variant_id;
					$insert_img["eimg_image_id"] 	= $image;
					$insert_img["default_image"] 	= 0;
					if($image == $default_image)
					{
						$insert_img["default_image"] 	= 1;
					}
					Tbl_ec_variant_image::insert($insert_img); 
				}
			}

			/* UPDATE OPTION VALUES */ 
			if($is_single == 0)
			{
				$variant_option = Tbl_ec_variant::where("evariant_id", $variant_id)->option()->get();
				foreach($variant_option as $option)
				{
					foreach($_option_name as $key=>$op_name)
					{
						if($op_name == $option->option_name)
						{
							$update_option["option_value"]	= $_option_value[$key];
							Tbl_option_value::where("option_value_id",$option->option_value_id)->update($update_option);
						}
					}
				}
				$json["redirect"] = "/member/ecommerce/product/edit-variant/".$product_id."?variant_id=".$variant_id;
			}
			
			Request::session()->flash('success', 'Product Successfully Updated');
		}

		$json["product_id"]	= $product_id;
		$json["variant_id"]	= $variant_id;
		$json["status"] = "success"; 

		return json_encode($json);
	}

	public function VariantValidate($is_single)
	{
		$value["evariant_item_label"] 	= Request::input('evariant_item_label');
		$rules["evariant_item_label"] 	= "required";
		$message						= [];

		if($is_single == 0)
		{
			$variant_exist 	= $this->checkExistVariant();

			if($variant_exist)
			{
				return "Variant \"". $variant_exist ."\" already exist";
			}

			$_option_name	= Request::input("option_name");
			$option_value	= Request::input('option_value');

			foreach($_option_name as $key=>$option_name)
			{
				$value["option_value_[$key]"] 				= $option_value[$key];
				$rules["option_value_[$key]"]				= "required";
				$message["option_value_[$key].required"]	= "Option name ".$option_name." is required";
			}
		}

		$validator = Validator::make($value, $rules, $message);

		if (!$validator->fails()) return "validated";
		else return $validator->errors()->first();

	}

	public function checkExistVariant()
	{
		$product_id 	= Request::input("product_id");
		$variant_id 	= Request::input("variant_id");
		$_option_value 	= Request::input("option_value");
		$option_value 	= '';

		foreach($_option_value as $key=>$value)
		{
			$option_value = $option_value.$value;
			if(count($_option_value) > $key+1)
			{
				$option_value = $option_value." • ";
			}
		}
		$exist_variant 	= Tbl_ec_product::Variant(" • ")->where("eprod_id", $product_id)->where("evariant_id","<>",$variant_id)->where("eprod_shop_id", $this->getShopId())->first();

		if($exist_variant)
		{
			if(strtolower($exist_variant->variant_name) == strtolower($option_value))
			{
				return $option_value;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function AddVariant($product_id)
	{
		$_option_name	= Request::input("option_name");
		$option_value	= Request::input('option_value');

		// dd(Request::input());
		
		/* Product Information */
		$insertVariant['evariant_prod_id']		= $product_id;
		$insertVariant['evariant_item_id']		= Request::input("evariant_item_id");
		$insertVariant["evariant_item_label"] 	= Request::input('evariant_item_label');
		$insertVariant["evariant_description"] 	= Request::input('evariant_description');
		$insertVariant["evariant_price"] 		= convertToNumber(Request::input('evariant_price'));
		$insertVariant["date_created"] 			= Carbon::now();
		$insertVariant["date_visible"] 			= Carbon::now();

		$variant_id = Tbl_ec_variant::insertGetId($insertVariant);

		/* PRODUCT IMAGE */
		if(Request::input("image_id"))
		{
			foreach(Request::input("image_id") as $image)
			{ 
				$insert_image["eimg_variant_id"]	= $variant_id;
				$insert_image["eimg_image_id"]		= $image;
				Tbl_ec_variant_image::insert($insert_image);
			}
		}

		foreach($_option_name as $key=>$option_name)
		{
			
			$insert_option_value['option_value']	= $option_value[$key];					
			$option_value_id						= Tbl_option_value::insertGetId($insert_option_value);

			$option_name_id = Tbl_option_name::where("option_name", $option_name)->value("option_name_id");

			$insert_variant_name['variant_name_order']	= $key;
			$insert_variant_name['variant_id']			= $variant_id;
			$insert_variant_name['option_name_id']		= $option_name_id;
			$insert_variant_name['option_value_id']		= $option_value_id;
			
			Tbl_variant_name::insert($insert_variant_name);
		}

		return $variant_id;
	}

	public function getDeleteVariant($variant_id)
	{
		Tbl_ec_variant::where("evariant_id")->delete();

		return Redirect::back();
	}

	public function getDeleteProduct($product_id)
	{
		$update["archived"] = 1;
		Tbl_ec_product::where("eprod_id", $product_id)->update($update);

		Request::session()->flash('success', 'Product Successfully Deleted');
		return Redirect::to('/member/ecommerce/product/list');
	}

	public function getProductArchiveRestore($action, $id)
	{
		$data["title"] 		= $action;
		$data["product"]	= Tbl_ec_product::where("eprod_id", $id)->first();

		return view('member.ecommerce_product.ecom_product_archive_restore_modal', $data);
	}

	public function postProductArchiveRestore($id)
	{
		$json["status"]		= "success";

		if(Request::input('action') == "restore")
		{
			$item_exist = Tbl_ec_variant::product()->where("evariant_item_id", $id)->where("eprod_shop_id", $this->getShopId())->where("tbl_ec_product.archived",0)->first();
			if($item_exist) 
			{
				$json["status"]		= "error";
				$json["message"] 	= "Item ".$item_exist->evariant_item_label." is already used in active";
			}
			else
			{
				Tbl_ec_product::where("eprod_id", $id)->update(['archived' => 0]);
				// Request::session()->flash('success', 'Product Successfully Restored');
				$json["message"] = "Product Successfully Restored";
			}
		}
		elseif(Request::input('action') == "archive")
		{
			Tbl_ec_product::where("eprod_id", $id)->update(['archived' => 1]);
			// Request::session()->flash('success', 'Product Successfully Archived');
			$json["message"] = "Product Successfully Archived
			";
		}

		$josn["product_id"] = $id;

		return json_encode($json);
	}

	public function getTest()
	{
		dd(Ecom_Product::getAllProductByCategory(3));
	}

	public function session_product_info($item_code)
	{
		$data = Session::get("product_info.".$item_code);
		return $data;
	}	

	public function hasDuplicate($array)
	{
		$dupe_array = array();
		foreach($array as $val)
		{
			if(collect($dupe_array)->contains($val)) return $val;
			array_push($dupe_array, $val);
		}
		return false;
	}

	public function getBulkEditPrice()
	{
		$data["_product"] = Tbl_ec_product::variant()->item()->itemDiscount()->where("tbl_ec_product.archived",0)->where("eprod_shop_id", $this->getShopId());

		$search = Request::input('search');
		if($search)
		{
			$data["_product"] = $data["_product"]->havingRaw("concat(eprod_name, IFNULL(variant_name, '')) like '%$search%'");
		}

		$data["_product"] = $data["_product"]->get()->toArray();
		
		foreach($data["_product"] as $key1=>$product)
		{
			$data["_product"][$key1]["product_new_name"] = $product["eprod_name"] . ($product["variant_name"] ? ' : '.$product["variant_name"] : '');
		}

		return view('member.ecommerce_product.ecom_bulk_edit_price', $data);
	}

	public function postBulkEditPrice()
	{
		$evariant_new_price = str_replace(",","",Request::input('evariant_new_price'));
		$evariant_id 		= Request::input('evariant_id');
		$promo_price 		= str_replace(",","",Request::input('item_promo_price'));
		$start_date 		= Request::input('item_start_date');
		$end_date 			= Request::input('item_end_date');
		
		foreach($evariant_new_price as $key=>$new_price)
		{
			if($new_price > 0)
			{
				Tbl_ec_variant::where("evariant_id", $evariant_id[$key])->update(['evariant_price' => $new_price]);
			}

			if($promo_price[$key] != ''	)
			{
				$item_id = Tbl_ec_variant::item()->where("evariant_id", $evariant_id[$key])->value("item_id");

				$item_info['item_id']					= $item_id;
				$item_info['item_discount_value']		= $promo_price[$key];
				$item_info['item_discount_date_start']	= $start_date[$key];
				$item_info['item_discount_date_end']	= $end_date[$key];
				Item::insert_item_discount($item_info);
			}
		}

		Request::session()->flash('success', 'Product Price Successfully updated');

		$json["status"] 	= "success";
		$json["message"]	= "Product Price Successfully updated";
		return json_encode($json);
	}
}