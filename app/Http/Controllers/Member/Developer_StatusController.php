<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use DB;
use Request;
use App\Globals\Item_code;
use App\Globals\Payment;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_payment_logs;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_tree;
use App\Globals\Mlm_complan_manager;
use App\Models\Tbl_item_code;
use App\Globals\Mlm_complan_manager_repurchase;
use App\Models\Tbl_mlm_slot;
use Carbon\Carbon;
class Developer_StatusController extends Member
{
	public function index()
	{
		
		return view('member.developer.developer_status');
	}
	public function reset_slot()
	{
		return view('member.developer.mlm.resetslot');
	}
	public function reset_slot_submit()
	{
		$password = Request::input('password');
		if($password == 'water123')
		{
			$shop_id = $this->user_info->shop_id;

			$slots =  DB::table('tbl_mlm_slot')->where('tbl_mlm_slot.shop_id', $shop_id)->get();
			$slots_where_in = [];
			foreach($slots as $key => $value)
			{
				$slots_where_in[$key] = $value->slot_id;
			}

			
			$encashment_process = DB::table('tbl_mlm_encashment_process')->where('shop_id', $shop_id)->get();
			$encashment_process_filtered = [];
			foreach ($encashment_process as $key => $value) 
			{
			 	# code...
			 	$encashment_process[$key] = $value->encashment_process;
			 } 

			
			$tbl_membership_code_invoice = DB::table('tbl_item_code_invoice')->where('shop_id', $shop_id)->get();
			$tbl_membership_code_invoice_filtered = [];

			foreach ($tbl_membership_code_invoice as $key => $value) 
			{
				$tbl_membership_code_invoice_filtered[$key] = $value->item_code_invoice_id;
			}
			
			DB::table('tbl_mlm_encashment_process_details')->whereIn('encashment_process', $encashment_process)->delete();
			DB::table('tbl_mlm_slot_wallet_log')->whereIn('wallet_log_slot', $slots_where_in)->delete();
			DB::table('tbl_tree_placement')->whereIn('placement_tree_parent_id', $slots_where_in)->delete();
			DB::table('tbl_tree_sponsor')->whereIn('sponsor_tree_parent_id', $slots_where_in)->delete();
			DB::table('tbl_mlm_slot_points_log')->whereIn('points_log_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_plan_binary_promotions_log')->whereIn('promotions_request_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_lead')->whereIn('lead_slot_id_sponsor', $slots_where_in)->delete();
			DB::table('tbl_mlm_gc')->whereIn('mlm_gc_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_binary_report')->whereIn('binary_report_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_binary_pairing_log')->whereIn('pairing_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_slot')->where('tbl_mlm_slot.shop_id', $shop_id)->delete();
			DB::table('tbl_membership_code')->where('shop_id', $shop_id)->delete();
			DB::table('tbl_membership_code_invoice')->where('shop_id', $shop_id)->delete();
			DB::table('tbl_membership_code_item_has')->where('shop_id', $shop_id)->delete();
			DB::table('tbl_mlm_encashment_process')->where('shop_id', $shop_id)->delete();
			DB::table('tbl_item_code')->where('shop_id', $shop_id)->delete();
			DB::table('tbl_item_code_item')->whereIn('item_code_invoice_id', $tbl_membership_code_invoice_filtered)->delete();
			DB::table('tbl_item_code_invoice')->where('shop_id', $shop_id)->delete();

			DB::table('tbl_mlm_triangle_repurchase_slot')->whereIn('repurchase_slot_slot_id', $slots_where_in)
			->join('tbl_mlm_triangle_repurchase_tree', 'tbl_mlm_triangle_repurchase_tree.tree_repurchase_slot_child', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_id')
			->truncate();
			DB::statement('SET FOREIGN_KEY_CHECKS=0');
		}
		else
		{
			die('Wrong Password');
		}
		die('Success');
	}
	public function recompute()
	{
		$shop_id = $this->user_info->shop_id;
		$password = Request::input('password');
		if($password == 'water123')
		{
			$shop_id = $this->user_info->shop_id;

			$slots =  DB::table('tbl_mlm_slot')->where('tbl_mlm_slot.shop_id', $shop_id)->get();
			$slots_where_in = [];
			foreach($slots as $key => $value)
			{
				$slots_where_in[$key] = $value->slot_id;
			}

			
			$encashment_process = DB::table('tbl_mlm_encashment_process')->where('shop_id', $shop_id)->get();
			$encashment_process_filtered = [];
			foreach ($encashment_process as $key => $value) 
			{
			 	# code...
			 	$encashment_process[$key] = $value->encashment_process;
			 } 

			
			$tbl_membership_code_invoice = DB::table('tbl_item_code_invoice')->where('shop_id', $shop_id)->get();
			$tbl_membership_code_invoice_filtered = [];

			foreach ($tbl_membership_code_invoice as $key => $value) 
			{
				$tbl_membership_code_invoice_filtered[$key] = $value->item_code_invoice_id;
			}
			
			// DB::table('tbl_mlm_encashment_process_details')->whereIn('encashment_process', $encashment_process)->delete();
			DB::table('tbl_mlm_slot_wallet_log')->whereIn('wallet_log_slot', $slots_where_in)->delete();
			DB::table('tbl_tree_placement')->whereIn('placement_tree_parent_id', $slots_where_in)->delete();
			DB::table('tbl_tree_sponsor')->whereIn('sponsor_tree_parent_id', $slots_where_in)->delete();
			// DB::table('tbl_mlm_slot_points_log')->whereIn('points_log_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_plan_binary_promotions_log')->whereIn('promotions_request_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_lead')->whereIn('lead_slot_id_sponsor', $slots_where_in)->delete();
			DB::table('tbl_mlm_gc')->whereIn('mlm_gc_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_binary_report')->whereIn('binary_report_slot', $slots_where_in)->delete();
			DB::table('tbl_mlm_binary_pairing_log')->whereIn('pairing_slot', $slots_where_in)->delete();
			// DB::table('tbl_mlm_slot')->where('tbl_mlm_slot.shop_id', $shop_id)->delete();
			// DB::table('tbl_membership_code')->where('shop_id', $shop_id)->delete();
			// DB::table('tbl_membership_code_invoice')->where('shop_id', $shop_id)->delete();
			// DB::table('tbl_membership_code_item_has')->where('shop_id', $shop_id)->delete();
			// DB::table('tbl_mlm_encashment_process')->where('shop_id', $shop_id)->delete();
			// DB::table('tbl_item_code')->where('shop_id', $shop_id)->delete();
			// DB::table('tbl_item_code_item')->whereIn('item_code_invoice_id', $tbl_membership_code_invoice_filtered)->delete();
			// DB::table('tbl_item_code_invoice')->where('shop_id', $shop_id)->delete();

			foreach($slots as $key => $value)
			{
				$update['slot_binary_left'] = 0;
				$update['slot_binary_right'] = 0;
				$update['slot_wallet_all'] = 0;
				$update['slot_wallet_current'] = 0;
				$update['slot_pairs_per_day_date'] = Carbon::now()->addDays(-2);
				Tbl_mlm_slot::where('slot_id', $value->slot_id)->update($update);

				Mlm_compute::entry($value->slot_id);
			}
		}
		else
		{
			die('Wrong Password');
		}
		die('Success');
	}
	public function give_points_ec_order()
	{
		$password = Request::input('password');
		if($password == 'water123')
		{
			$id = Request::input('id');
			Item_code::use_item_code_all_ec_order($id);
			die('success');
		}
		die('wrong password');
	}
	public function retro_product_sales()
	{
		$shop_id = $this->user_info->shop_id;
		$sales = Tbl_item_code_invoice::where('shop_id', $shop_id)->get();
		$password = Request::input('password');
		if($password == 'water123')
		{
			foreach($sales as $key => $value)
			{
				$count = Tbl_journal_entry::where('je_reference_module','=', 'mlm-product-repurchase')
				->where('je_reference_id', $value->item_code_invoice_id)
				->count();
				if($count == 0)
				{
					Item_code::add_journal_entry($value->item_code_invoice_id);
				}
			}
			return 'Success';
		}

	}
	public function re_tree()
	{

		$slot_id = Request::input('id');
		$slot_info = Mlm_compute::get_slot_info($slot_id);
		if($slot_info)
		{
			Tbl_tree_sponsor::where('sponsor_tree_child_id', $slot_id)->delete();
			return Mlm_tree::insert_tree_sponsor($slot_info, $slot_info, 1);
		}
	}
	public function re_com_phil_lost()
	{
		$slot_id = Request::input('id');
		$slot_info = Mlm_compute::get_slot_info($slot_id);
		if($slot_info)
		{
			Mlm_complan_manager::indirect($slot_info);
			Mlm_complan_manager::membership_matching($slot_info);
			Mlm_complan_manager::indirect_points($slot_info);
		}
	}
	public function re_com_phil_uni()
	{
		$member_ship = 2;
		$invoice = Tbl_item_code_invoice::customer()
		->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_owner', '=', 'tbl_customer.customer_id')
		->where('slot_membership', 2)
		->get();
		foreach($invoice as $key => $value)
		{
			$slot_info = Mlm_compute::get_slot_info($value->slot_id);
			$item_code = Tbl_item_code::where("item_code_invoice_id",$value->item_code_invoice_id)->get(); 
			foreach($item_code as $key2 => $value2)
			{
				Mlm_complan_manager_repurchase::unilevel_repurchase_points($slot_info, $value2->item_code_id);
			}
			
		}
		dd($invoice);
	}
	public function recompute_membership_matching()
	{
		$shop_id = $this->user_info->shop_id;

		$platinum = Tbl_mlm_slot::where('slot_membership', 4)->where('tbl_mlm_slot.shop_id', $shop_id)
		->where('slot_matched_membership', 0)
		->membership()->membership_points()->customer()->get();

		foreach ($platinum as $key => $value) {
			Mlm_complan_manager::membership_matching($value);
		}

		dd($platinum);
	}
	public function payment_logs()
	{
		$data["page"]			= "Paymnet Logs";
		$shop_id				= $this->user_info->shop_id;
		$data["_payment_logs"]	=  Payment::logs($shop_id, 100);
		return view('member.developer.payment_logs', $data);
	}
	public function payment_logs_data($payment_log_id)
	{
		$data = Tbl_payment_logs::where("payment_log_id", $payment_log_id)->value('payment_log_data');
		$data = unserialize($data);
		dd($data);
	}
}