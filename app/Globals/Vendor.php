<?php
namespace App\Globals;

use App\Models\Tbl_vendor;
use App\Models\Tbl_vendor_address;
use App\Models\Tbl_vendor_other_info;
use App\Models\Tbl_vendor_item;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use DB;
use Carbon\Carbon;

/**
 * Vendor Globals - all vendor related module
 *
 * @author Bryan Kier Aradanas
 */


class Vendor
{
	public static function vendor_data($vendor_id)
	{
		return Tbl_vendor::info()->where('vendor_id', $vendor_id)->first();
	}
	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	/**
	 * Getting all the list of vendor
	 *
	 * @param string  	$filter		(all, active, inactive)
	 * @param integer  	$parent_id  Id of the Chart of Accoutn where will it start
	 * @param array  	$type      	Filter of type of Chart of Account (eg: Accounta Payable)
	 * @param boolean  	$balance    If it will show total balance of each account (true, false)
	 */

	public static function getAllVendor($archived = 'active', $item_id = 0)
	{
		$warehouse_id = Warehouse2::get_current_warehouse(Vendor::getShopId());
		$check_settings = AccountingTransaction::settings(Vendor::getShopId(), 'allow_transaction');

		$data = Tbl_vendor::info()->where("vendor_shop_id",Vendor::getShopId());

		switch($archived)
		{
			case 'active':
				$data->where("archived", 0);
				break;
			case 'inactive':
				$data->where("archived", 1);
				break;
		}
		if($check_settings == 1)
        {
            $data = $data->whereNull('vendor_warehouse_id')->orWhere('vendor_warehouse_id', $warehouse_id);
        }

		$data = $data->groupBy("vendor_id")->get()->toArray();
		foreach ($data as $key => $value) 
		{
			$data[$key] = $value;
			$data[$key]['count_tag_item'] = 0;
			if($item_id)
			{
				$data[$key]['count_tag_item'] = Tbl_vendor_item::where("tag_item_id", $item_id)->where("tag_vendor_id", $value['vendor_id'])->count();
			}
			$data[$key]['tag_item'] = Tbl_vendor_item::where("tag_vendor_id", $value['vendor_id'])->get();
			$data[$key]['orig_item'] = Tbl_item::where("shop_id", Vendor::getShopId())->get();

			/*CTR Open Transaction*/
			$data[$key]['ctr_ri'] = TransactionReceiveInventory::countTransaction(Vendor::getShopId(), $value['vendor_id']);
			$data[$key]['ctr_eb'] = TransactionEnterBills::countTransaction(Vendor::getShopId(), $value['vendor_id']);
			$data[$key]['ctr_wc'] = TransactionPurchaseOrder::countOpenPOTransaction(Vendor::getShopId(), $value['vendor_id']);
			$data[$key]['ctr_db'] = TransactionPurchaseOrder::countOpenPOTransaction(Vendor::getShopId(), $value['vendor_id']);
			$data[$key]['ctr_pb_refnum'] = TransactionPayBills::getReferenceNumber(Vendor::getShopId(), $value['vendor_id']);
		}
		if($item_id)
		{
			usort($data, function($a, $b) {
	            if($a['count_tag_item'] == $b['count_tag_item']) return 0;
	            return $a['count_tag_item'] < $b['count_tag_item']?1:-1;
			});
		}
		return $data;
	}
	public static function getTagVendor($archived = 'active')
	{
		$data = Tbl_vendor_item::vendor()->where("tbl_vendor.vendor_shop_id",Vendor::getShopId());
		//$data = Self::item_per_vendor();
		switch($archived)
		{
			case 'active':
				$data->where("archived", 0);
				break;
			case 'inactive':
				$data->where("archived", 1);
				break;
		}
		return $data->get()->toArray();

	}
	/*public static function item_per_vendor()
	{
		$vendor = Self::getAllVendor('active');
		foreach ($vendor as $key => $value) 
		{
			$vendor_item = Tbl_vendor_item::where('tag_vendor_id', $value['vendor_id'])->get();
			foreach ($vendor_item as $key_vendor_item => $value_vendor_item)
			{
				
			}
		}

		return $data;
	}*/
	public static function ins_vendor($info)
	{
		$ins["vendor_shop_id"] = Vendor::getShopId();
		$ins["vendor_first_name"] = $info["manufacturer_fname"];
		$ins["vendor_middle_name"] = $info["manufacturer_mname"];
		$ins["vendor_last_name"] = $info["manufacturer_lname"];
		$ins["vendor_email"] = $info["email_address"];
		$ins["vendor_company"] = $info["manufacturer_name"];
		$ins["created_date"] = Carbon::now();

		$vendor_id = Tbl_vendor::insertGetId($ins);

		$ins_add["ven_addr_vendor_id"] = $vendor_id;

		Tbl_vendor_address::insert($ins_add);

		$ins_info["ven_info_vendor_id"] = $vendor_id;
		$ins_info["ven_info_phone"] = $info["phone_number"];

		Tbl_vendor_other_info::insert($ins_info);
	}

	public static function getVendor($shop_id, $vendor_id)
	{
		return Tbl_vendor::where('vendor_shop_id', $shop_id)->where('vendor_id', $vendor_id)->first();
	}
	public static function getBank($shop_id, $vendor_id)
	{
		return Tbl_vendor::bank()->where('vendor_shop_id', $shop_id)->where('tbl_vendor.vendor_id', $vendor_id)->get();
	}
	public static function search_get($shop_id, $keyword = '')
	{
		$return = Tbl_vendor::where('shop_id', $shop_id);

		if($keyword != '')
		{
			$return = Tbl_vendor::where('shop_id', $shop_id);

			$return->where(function($q) use ($keyword)
            {
                $q->orWhere("tbl_vendor.vendor_first_name", "LIKE", "%$keyword%");
                $q->orWhere("tbl_vendor.vendor_last_name", "LIKE", "%$keyword%");
                $q->orWhere("tbl_vendor.vendor_middle_name", "LIKE", "%$keyword%");
                $q->orWhere("tbl_vendor.vendor_company", "LIKE", "%$keyword%");
            });
		}
		
		$return = $return->groupBy('tbl_vendor.vendor_id')->orderBy("tbl_vendor.vendor_company",'ASC')->get();

		return $return;
	}

	public static function delete_vendor($shop_id)
	{
		Tbl_vendor::where("vendor_shop_id", $shop_id)->delete();
	}

}