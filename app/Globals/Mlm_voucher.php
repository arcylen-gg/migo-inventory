<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_item;
use App\Models\Tbl_item_code;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_membership_package_has;
use App\Models\Tbl_voucher;
use App\Models\Tbl_voucher_item;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use App\Globals\Membership_code;
use App\Globals\Item;
use Schema;
use Session;
use DB;

class Mlm_voucher
{
	public static function give_voucher_mem_code($invoice_id)
	{
		$voucher_invoice = Tbl_membership_code_invoice::where('membership_code_invoice_id', $invoice_id)->first();
		if($voucher_invoice != null)
		{

			// if($voucher_invoice->membership_code_product_issued == 0)
			// {
				$membership_codes = Tbl_membership_code::where('membership_code_invoice_id', $invoice_id)->package()->get();
				$items= [];
				$insert_voucher = 0;
				foreach($membership_codes as $value)
				{
					$package_product = Tbl_membership_package_has::where('membership_package_id', $value->membership_package_id)->get();

					foreach($package_product as $key2 => $value2)
					{
						$package_a  = Tbl_membership_package::where('membership_package_id', $value->membership_package_id)->first();
						if($package_a->membership_package_is_gc == 0)
						{

							if(isset($items[$value2->item_id]))
							{
								$items[$value2->item_id] += $value2->membership_package_has_quantity;
							}
							else
							{
								$items[$value2->item_id] = $value2->membership_package_has_quantity;
							}
							$insert_voucher = 1;
						}
					}
				}
				if($insert_voucher == 1)
				{
					$insert['voucher_code'] = Membership_code::random_code_generator(6);
					$insert['voucher_code_type'] = 0;
					$insert['voucher_invoice_membership_id'] = $invoice_id;
					$insert['voucher_customer'] = $voucher_invoice->customer_id;
					$insert['voucher_claim_status'] = $voucher_invoice->membership_code_product_issued;

					$id = Tbl_voucher::insertGetId($insert);
					foreach($items as $key => $item)
					{
						$insert_item[$key]['voucher_id'] = $id;
						$insert_item[$key]['item_id'] = $key;
						$insert_item[$key]['voucher_item_quantity'] = $item;
						
					}
					if(isset($insert_item))
					{
						Tbl_voucher_item::insert($insert_item);
						Mlm_voucher::give_item_name_add_item($invoice_id);
					}
				}
			// }
			
			
		}
	}
	public static function give_item_name_add_item($invoice_id)
	{
		$voucher = Tbl_voucher::where('voucher_invoice_membership_id', $invoice_id)->get();
		if($voucher)
		{
			foreach($voucher as  $key => $value)
			{
				$voucher_bundle = Tbl_voucher_item::where('voucher_id', $value->voucher_id)->get();

				if($voucher_bundle)
				{
					foreach ($voucher_bundle as $key2 => $value2) 
					{
						if($value2->voucher_is_bundle == 1)
						{
							# code...
							$item_info = Tbl_item::where('item_id', $value2->item_id)->first();
							if($item_info)
							{
								$update_item['item_name']  = $item_info->item_name;
								$update_item['item_price']  = $item_info->item_price;
								$update_item['item_quantity'] = $value2->voucher_item_quantity;
								$update_item['voucher_is_bundle'] = 1;
								Tbl_voucher_item::where('voucher_item_id', $value2->voucher_item_id)->update($update_item);
								$item_bundle =Item::get_item_bundle($item_info->item_id);
								if($item_bundle)
								{
									foreach($item_bundle['bundle'] as $key3 => $value3)
									{
										$insert_item_n_bundle['voucher_id'] = $value->voucher_id;
										$insert_item_n_bundle['item_id'] = $value3['item_id'];
										$insert_item_n_bundle['voucher_item_quantity'] = $value3['bundle_qty']; 
										$insert_item_n_bundle['voucher_is_bundle'] = 0;
										$insert_item_n_bundle['item_name'] = $value3['item_name'];
										$insert_item_n_bundle['item_price'] = $value3['item_price'];
										$insert_item_n_bundle['item_quantity'] = $value3['bundle_qty'];
										Tbl_voucher_item::insert($insert_item_n_bundle);
									}
								}
							}
						}
					}
				}
			}
		}

	}
	public static function give_item_name_add_item_2($invoice_id)
	{
		$voucher = Tbl_voucher::where('voucher_invoice_product_id', $invoice_id)->get();
		if($voucher)
		{
			foreach($voucher as  $key => $value)
			{
				$voucher_bundle = Tbl_voucher_item::where('voucher_id', $value->voucher_id)->get();

				if($voucher_bundle)
				{
					foreach ($voucher_bundle as $key2 => $value2) 
					{
						if($value2->voucher_is_bundle == 1)
						{
							# code...
							$item_info = Tbl_item::where('item_id', $value2->item_id)->first();
							if($item_info)
							{
								$update_item['item_name']  = $item_info->item_name;
								$update_item['item_price']  = $item_info->item_price;
								$update_item['item_quantity'] = $value2->voucher_item_quantity;
								$update_item['voucher_is_bundle'] = 1;
								Tbl_voucher_item::where('voucher_item_id', $value2->voucher_item_id)->update($update_item);
								if($item_info->item_type_id == 4)
								{
									$item_bundle = Item::get_item_bundle($item_info->item_id);
									if($item_bundle)
									{
										foreach($item_bundle['bundle'] as $key3 => $value3)
										{
											$insert_item_n_bundle['voucher_id'] = $value->voucher_id;
											$insert_item_n_bundle['item_id'] = $value3['item_id'];
											$insert_item_n_bundle['voucher_item_quantity'] = $value3['bundle_qty']; 
											$insert_item_n_bundle['voucher_is_bundle'] = 0;
											$insert_item_n_bundle['item_name'] = $value3['item_name'];
											$insert_item_n_bundle['item_price'] = $value3['item_price'];
											$insert_item_n_bundle['item_quantity'] = $value3['bundle_qty'];
											Tbl_voucher_item::insert($insert_item_n_bundle);
										}
									}
								}
								else
								{
									$insert_item_n_bundle['voucher_id'] = $value->voucher_id;
									$insert_item_n_bundle['item_id'] = $item_info->item_id;
									$insert_item_n_bundle['voucher_item_quantity'] = $value2->voucher_item_quantity; 
									$insert_item_n_bundle['voucher_is_bundle'] = 0;
									$insert_item_n_bundle['item_name'] = $item_info->item_name;
									$insert_item_n_bundle['item_price'] = $item_info->item_price;
									$insert_item_n_bundle['item_quantity'] = 1;
									Tbl_voucher_item::insert($insert_item_n_bundle);
								}
								
							}
						}
					}
				}
			}
		}

	}
	public static function give_voucher_prod_code($invoice_id)
	{
		$voucher_invoice = Tbl_item_code_invoice::where('item_code_invoice_id', $invoice_id)->first();
		if($voucher_invoice != null)
		{
			if($voucher_invoice->item_code_product_issued == 0)
			{
				$product_codes = Tbl_item_code::where('item_code_invoice_id', $invoice_id)->item()->get();
				$items= [];
				foreach($product_codes as $key2 => $value2)
				{
					if(isset($items[$value2->item_id]))
					{
						$items[$value2->item_id] += 1;
					}
					else
					{
						$items[$value2->item_id] = 1;
					}
				}

				$insert['voucher_code'] = Membership_code::random_code_generator(6);
				$insert['voucher_code_type'] = 0;
				$insert['voucher_invoice_product_id'] = $invoice_id;
				$insert['voucher_customer'] = $voucher_invoice->customer_id;
				$insert['voucher_claim_status'] = 0;
				$id = Tbl_voucher::insertGetId($insert);
				foreach($items as $key => $item)
				{
					$insert_item[$key]['voucher_id'] = $id;
					$insert_item[$key]['item_id'] = $key;
					$insert_item[$key]['voucher_item_quantity'] = $item;
				}
				if(isset($insert_item))
				{
					Tbl_voucher_item::insert($insert_item);

					Mlm_voucher::give_item_name_add_item_2($invoice_id);
				}
			}
		}
	}
	public static function give_voucher_binary_promotions($item, $customer_id, $slot_id)
	{
		$insert['voucher_code'] = Membership_code::random_code_generator(6);
		$insert['voucher_code_type'] = 0;
		$insert['voucher_customer'] = $customer_id;
		$insert['voucher_claim_status'] = 0;

		$id = Tbl_voucher::insertGetId($insert);

		$insert_item['voucher_id'] = $id;
		$insert_item['item_id'] = $item->item_id;
		$insert_item['voucher_item_quantity'] = 1;
		$insert_item['voucher_is_bundle'] = 0;
		$insert_item['item_price'] = $item->item_price;
		$insert_item['item_quantity'] = 1;
		$insert_item['item_name'] = $item->item_name;
		Tbl_voucher_item::insert($insert_item);
	}
}