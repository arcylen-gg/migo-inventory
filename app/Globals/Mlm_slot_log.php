<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_encashment_process;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\AuditTrail;

class Mlm_slot_log
{   
	public static function slot($wallet_log_slot, $wallet_log_slot_sponsor, $wallet_log_details, $wallet_log_amount, $wallet_log_plan, $wallet_log_status,   $wallet_log_claimbale_on, $wallet_log_remarks = '')
	{
		$check_slot = Tbl_mlm_slot::where("slot_id",$wallet_log_slot)->first();
		if($check_slot)
		{
			$insert['shop_id'] = $check_slot->shop_id;
		}
		$insert['wallet_log_slot'] = $wallet_log_slot; 
		$insert['wallet_log_slot_sponsor'] = $wallet_log_slot_sponsor; 
		$insert['wallet_log_date_created'] = Carbon::now(); 
		$insert['wallet_log_details'] = $wallet_log_details; 
		$insert['wallet_log_amount'] = $wallet_log_amount; 
		$insert['wallet_log_plan'] = $wallet_log_plan; 
		$insert['wallet_log_status'] = $wallet_log_status; 
		$insert['wallet_log_claimbale_on'] = $wallet_log_claimbale_on; 
		Tbl_mlm_slot_wallet_log::insert($insert);
	}
	public static function point_slot($points_info = array())
	{
		Tbl_mlm_slot_points_log::insert($points_info);
	}
	public static function slot_array($arry_log)
	{
		//n_ready if not ready
		$check_if_fs = Mlm_slot_log::check_if_fs($arry_log['wallet_log_slot']);
		$check_if_fs = 1;
		if($check_if_fs == 1)
		{
			$check_if_fs_sponsor = Mlm_slot_log::check_if_fs($arry_log['wallet_log_slot_sponsor']);
			$check_if_fs_sponsor = 1;
			if($check_if_fs_sponsor == 1)
			{
				$insert['shop_id'] = $arry_log['shop_id'];
				$insert['wallet_log_slot'] = $arry_log['wallet_log_slot']; 
				$insert['wallet_log_slot_sponsor'] = $arry_log['wallet_log_slot_sponsor']; 
				$insert['wallet_log_date_created'] = Carbon::now(); 
				$insert['wallet_log_details'] = $arry_log['wallet_log_details']; 
				$insert['wallet_log_amount'] = $arry_log['wallet_log_amount']; 
				$insert['wallet_log_plan'] = $arry_log['wallet_log_plan']; 
				$insert['wallet_log_status'] = $arry_log['wallet_log_status']; 
				$insert['wallet_log_claimbale_on'] = $arry_log['wallet_log_claimbale_on']; 

				if(isset($arry_log['encashment_process']))
				{
					$insert['encashment_process'] = $arry_log['encashment_process'];
				}
				if(isset($arry_log['encashment_process_taxed']))
				{
					$insert['encashment_process_taxed'] = $arry_log['encashment_process_taxed'];
				}
				if(isset($arry_log['wallet_log_membership_filter']))
				{
					$insert['wallet_log_membership_filter'] = $arry_log['wallet_log_membership_filter'];
				}
				if(isset($arry_log['wallet_log_matrix_triangle']))
				{
					$insert['wallet_log_matrix_triangle'] = $arry_log['wallet_log_matrix_triangle'];
				}

				$wallet_log_id = Tbl_mlm_slot_wallet_log::insertGetId($insert);
				// $wallet_log_data = AuditTrail::get_table_data("tbl_mlm_slot_wallet_log","wallet_log_id",$wallet_log_id);
				// AuditTrail::record_logs("Added","mlm_wallet_log_slot",$wallet_log_id,"",serialize($wallet_log_data));

				$slot_wallet_all = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $arry_log['wallet_log_slot'])->sum('wallet_log_amount');
				$slot_wallet_current = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $arry_log['wallet_log_slot'])
				->where('wallet_log_status', 'released')
				->sum('wallet_log_amount');
				$update['slot_wallet_all'] = $slot_wallet_all;
				$update['slot_wallet_current'] = $slot_wallet_current;
				Tbl_mlm_slot::where('slot_id', $arry_log['wallet_log_slot'])->update($update);
			}
		}	
	}
	public static function slot_array_with_return($arry_log)
	{
		$returned_id = null;
		//n_ready if not ready
		$check_if_fs = Mlm_slot_log::check_if_fs($arry_log['wallet_log_slot']);
		$check_if_fs = 1;
		if($check_if_fs == 1)
		{
			$check_if_fs_sponsor = Mlm_slot_log::check_if_fs($arry_log['wallet_log_slot_sponsor']);
			$check_if_fs_sponsor = 1;
			if($check_if_fs_sponsor == 1)
			{
				$insert['shop_id'] = $arry_log['shop_id'];
				$insert['wallet_log_slot'] = $arry_log['wallet_log_slot']; 
				$insert['wallet_log_slot_sponsor'] = $arry_log['wallet_log_slot_sponsor']; 
				$insert['wallet_log_date_created'] = Carbon::now(); 
				$insert['wallet_log_details'] = $arry_log['wallet_log_details']; 
				$insert['wallet_log_amount'] = $arry_log['wallet_log_amount']; 
				$insert['wallet_log_plan'] = $arry_log['wallet_log_plan']; 
				$insert['wallet_log_status'] = $arry_log['wallet_log_status']; 
				$insert['wallet_log_claimbale_on'] = $arry_log['wallet_log_claimbale_on']; 

				if(isset($arry_log['encashment_process']))
				{
					$insert['encashment_process'] = $arry_log['encashment_process'];
				}
				if(isset($arry_log['encashment_process_taxed']))
				{
					$insert['encashment_process_taxed'] = $arry_log['encashment_process_taxed'];
				}
				if(isset($arry_log['wallet_log_membership_filter']))
				{
					$insert['wallet_log_membership_filter'] = $arry_log['wallet_log_membership_filter'];
				}
				if(isset($arry_log['wallet_log_matrix_triangle']))
				{
					$insert['wallet_log_matrix_triangle'] = $arry_log['wallet_log_matrix_triangle'];
				}

				$wallet_log_id = Tbl_mlm_slot_wallet_log::insertGetId($insert);
				// $wallet_log_data = AuditTrail::get_table_data("tbl_mlm_slot_wallet_log","wallet_log_id",$wallet_log_id);
				// AuditTrail::record_logs("Added","mlm_wallet_log_slot",$wallet_log_id,"",serialize($wallet_log_data));
				$returned_id = $wallet_log_id;
				$slot_wallet_all = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $arry_log['wallet_log_slot'])->sum('wallet_log_amount');
				$slot_wallet_current = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $arry_log['wallet_log_slot'])
				->where('wallet_log_status', 'released')
				->sum('wallet_log_amount');
				$update['slot_wallet_all'] = $slot_wallet_all;
				$update['slot_wallet_current'] = $slot_wallet_current;
				Tbl_mlm_slot::where('slot_id', $arry_log['wallet_log_slot'])->update($update);
			}
		}	

		return $returned_id;
	}
	public static function check_if_fs($slot_id)
	{
		$slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
		if($slot)
		{
			if($slot->memory_limit != 'FS')
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}	
		else
		{
			return 0;
		}
	}
	public static function update_all_released()
	{
		$slots = Tbl_mlm_slot::get();
		foreach($slots as $key => $value)
		{
			$slot_wallet_all = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $value->slot_id)->sum('wallet_log_amount');
			$slot_wallet_current = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $value->slot_id)
			->where('wallet_log_status', 'released')
			->sum('wallet_log_amount');
			$update['slot_wallet_all'] = $slot_wallet_all;
			$update['slot_wallet_current'] = $slot_wallet_current;
			Tbl_mlm_slot::where('slot_id', $value->slot_id)->update($update);
		}
	}
	public static function slot_log_points_array($array)
	{
		$insert['points_log_complan'] = $array['points_log_complan'];
		$insert['points_log_level'] = $array['points_log_level'];
		$insert['points_log_slot'] = $array['points_log_slot'];
		$insert['points_log_Sponsor'] = $array['points_log_Sponsor'];
		$insert['points_log_date_claimed'] = $array['points_log_date_claimed'];
		$insert['points_log_converted'] = $array['points_log_converted'];
		$insert['points_log_converted_date'] = $array['points_log_converted_date'];
		$insert['points_log_type'] = $array['points_log_type'];
		$insert['points_log_from'] = $array['points_log_from'];
		$insert['points_log_points'] = $array['points_log_points'];

		if(isset($array['points_log_leve_start']))
		{
			$insert['points_log_leve_start'] = $array['points_log_leve_start'];
		}
		if(isset($array['points_log_leve_end']))
		{
			$insert['points_log_leve_end'] = $array['points_log_leve_end'];
		}		
		if(isset($array['points_log_leve_end']))
		{
			$insert['points_log_leve_end'] = $array['points_log_leve_end'];
		}		
		if(isset($array['original_from_complan']))
		{
			$insert['original_from_complan'] = $array['original_from_complan'];
		}
		if($insert['points_log_type'] == "SGPV" || $insert['points_log_type'] == "SPV" || $insert['points_log_type'] == "RPV" || $insert['points_log_type'] == "RGPV")
		{
			return Tbl_mlm_slot_points_log::insertGetId($insert);
		}
		else
		{
			Tbl_mlm_slot_points_log::insert($insert);
		}
	}
	public static function log_constructor($earner, $sponsor,  $log_array)
	{
		$label = Mlm_slot_log::get_complan_label($log_array['complan'], $sponsor->shop_id);
		$message = "Your slot " . $earner->slot_no;
		$message .= ", earned " . $log_array['earning'];
		$message .= " from " .  $label;
		$message .= " in level " . $log_array['level'];
		$message .= " of " . $log_array['level_tree'];
		$message .= ". Sponsor : " . $sponsor->slot_no;
		$message .= "(" .name_format_from_customer_info($sponsor). ")";

		return $message;
	}
	public static function log_constructor_wallet_transfer($action,$amount,$from)
	{
		$message = "";
		if($amount<0)
		{
			$amount *= -1;
		}
		switch ($action) 
		{
			case 'recieved':
				$message.="You have successfully received ";
				break;

			case 'transfer':
				$message.="You have successfully transfer ";
				break;
			
			case 'fee':
				$message.="Your wallet transfer to ".$from." has processing fee of ";
				break;
		}
		$message.=currency('₱',$amount)." ";
		switch ($action) 
		{
			case 'recieved':
				$message.="from slot ".$from;
				break;

			case 'transfer':
				$message.="to slot ".$from;
				break;
		}
		return $message;
	}
	public static function log_constructor_gc($earner, $sponsor,  $log_array)
	{
		$label = Mlm_slot_log::get_complan_label($log_array['complan'], $sponsor->shop_id);
		$message = "Your slot " . $earner->slot_no;
		$message .= ", earned " . $log_array['earning'];
		$message .= " GC from " .  $label;
		$message .= " in level " . $log_array['level'];
		$message .= " of " . $log_array['level_tree'];
		$message .= ". Sponsor : " . $sponsor->slot_no;
		$message .= "(" .name_format_from_customer_info($sponsor). ")";

		return $message;
	}
	public static function get_complan_label($complan, $shop_id)
	{
		$label = Tbl_mlm_plan::where('marketing_plan_code', $complan)
					->where('shop_id', $shop_id)
					->value('marketing_plan_label');
		return $label;			
	}

	public static function encash_all($encashment_process)
	{
		# code...
		$encashment = Tbl_mlm_encashment_process::where('encashment_process', $encashment_process)->first();

		if($encashment != null)
		{
			$all_log = Tbl_mlm_slot_wallet_log::where('wallet_log_status', 'released')
			->where('wallet_log_claimbale_on', '>=',  $encashment->enchasment_process_from)
			->where('wallet_log_claimbale_on', '<=',  $encashment->enchasment_process_to)
			->where('wallet_log_plan','!=', 'ENCASHMENT')
			->whereNull('encashment_process');
			// $data['all'] = $all_log->get()->toArray();
			$all_log_get = $all_log->get()->toArray();
			$all_log_structured = [];
			$all_log_per_slot = [];
			$sum = 0;
			// dd($all_log_get);
			foreach($all_log_get as $key => $value)
			{
				if(isset($all_log_structured[$value['wallet_log_id']]))
				{
					$all_log_structured[$value['wallet_log_id']] += $value['wallet_log_amount'];
				}
				else
				{
					$all_log_structured[$value['wallet_log_id']] = $value['wallet_log_amount'];
				}

				if(isset($all_log_per_slot[$value['wallet_log_slot']]))
				{
					$all_log_per_slot[$value['wallet_log_slot']] += $value['wallet_log_amount'];
				}
				else
				{
					$all_log_per_slot[$value['wallet_log_slot']] = $value['wallet_log_amount'];
				}
				// $update = Tbl_mlm_slot_wallet_log::find($value['wallet_log_id']);
				// $update->encashment_process = $encashment_process;
				// $update->save();
			}
			foreach ($all_log_per_slot as $key => $value) {
				# code...
				// Mlm_slot_log
				// 0 = fixed
				// 1 = percentage
				$value2 = $value;
				$tax = $encashment->enchasment_process_tax;
				$tax_p = $encashment->enchasment_process_tax_type;
				$p_fee = $encashment->enchasment_process_p_fee;
				$p_fee_type = $encashment->enchasment_process_p_fee_type;

				if($p_fee_type == 0)
				{
					$value2 = $value2 - $p_fee;
				}
				else
				{
					$p_fee = ($value2 * $p_fee)/100;
					$value2 = $value2 - $p_fee;
				}

				if($tax_p == 0)
				{
					$value2 = $value2 - $tax;
				}
				else
				{
					$tax = ($value2 * $tax)/100;
					$value2 = $value2-$tax;
				}
				
				$sum += $value;
				if($value >= $encashment->enchasment_process_minimum)
				{
					$up['encashment_process'] = $encashment_process;
					Tbl_mlm_slot_wallet_log::where('wallet_log_status', 'released')
					->where('wallet_log_claimbale_on', '>=',  $encashment->enchasment_process_from)
					->where('wallet_log_claimbale_on', '<=',  $encashment->enchasment_process_to)
					->where('wallet_log_plan','!=', 'ENCASHMENT')
					->where('wallet_log_slot', $key)
					->whereNull('encashment_process')
					->update($up);

					$arr['shop_id'] = $encashment->shop_id;
					$arr['wallet_log_slot'] = $key;
					$arr['wallet_log_slot_sponsor'] = null;
					$arr['wallet_log_details'] = 'Your ' . $value . ' income is ready for encashment';
					$arr['wallet_log_amount'] = $value * (-1); 
					$arr['wallet_log_plan'] = 'ENCASHMENT';
					$arr['wallet_log_status'] = 'released';
					$arr['wallet_log_claimbale_on'] = Carbon::now();
					$arr['encashment_process_taxed'] = $value2;
					$arr['encashment_process'] = $encashment_process; 
					Mlm_slot_log::slot_array($arr);



				}
			}
			$data['per_slot'] = $all_log_per_slot;
			$data['structured'] = $all_log_structured;
			$update_sum['encashment_process_sum'] = $sum;

			
			Tbl_mlm_encashment_process::where('encashment_process', $encashment_process)->update($update_sum);
			// $encash_data = AuditTrail::get_table_data("tbl_mlm_encashment_process","encashment_process",$encashment_process);
			// AuditTrail::record_logs("Added","mlm_encash",$encashment_process,"",serialize($encash_data));
		}
		$data['status'] = 'success';$data['message'] = 'Encashment Process';
	}
	public static function encash_single($encashment_process, $all_log)
	{
		# code...
		$encashment = Tbl_mlm_encashment_process::where('encashment_process', $encashment_process)->first();
		if($encashment != null)
		{
			$data['all'] = $all_log;
			$all_log_get = $all_log;
			$all_log_structured = [];
			$all_log_per_slot = [];
			$sum = 0;
			foreach($all_log_get as $key => $value)
			{
				if(isset($all_log_structured[$value['wallet_log_id']]))
				{
					$all_log_structured[$value['wallet_log_id']] += $value['wallet_log_amount'];
				}
				else
				{
					$all_log_structured[$value['wallet_log_id']] = $value['wallet_log_amount'];
				}

				if(isset($all_log_per_slot[$value['wallet_log_slot']]))
				{
					$all_log_per_slot[$value['wallet_log_slot']] += $value['wallet_log_amount'];
				}
				else
				{
					$all_log_per_slot[$value['wallet_log_slot']] = $value['wallet_log_amount'];
				}
				$update = Tbl_mlm_slot_wallet_log::find($value['wallet_log_id']);
				$update->encashment_process = $encashment_process;
				$update->save();
			}
			foreach ($all_log_per_slot as $key => $value) {
				# code...
				// Mlm_slot_log
				// 0 = fixed
				// 1 = percentage
				$value2 = $value;
				$tax = $encashment->enchasment_process_tax;
				$tax_p = $encashment->enchasment_process_tax_type;
				$p_fee = $encashment->enchasment_process_p_fee;
				$p_fee_type = $encashment->enchasment_process_p_fee_type;

				if($p_fee_type == 0)
				{
					$value2 = $value2 - $p_fee;
				}
				else
				{
					$p_fee = ($value2 * $p_fee)/100;
					$value2 = $value2 - $p_fee;
				}

				if($tax_p == 0)
				{
					$value2 = $value2 - $tax;
				}
				else
				{
					$tax = ($value2 * $tax)/100;
					$value2 = $value2-$tax;
				}
				
				$sum += $value;
				if($value >= $encashment->enchasment_process_minimum)
				{
					$arr['shop_id'] = $encashment->shop_id;
					$arr['wallet_log_slot'] = $key;
					$arr['wallet_log_slot_sponsor'] = null;
					$arr['wallet_log_details'] = 'Your ' . $value . ' income is ready for encashment';
					$arr['wallet_log_amount'] = $value * (-1); 
					$arr['wallet_log_plan'] = 'ENCASHMENT';
					$arr['wallet_log_status'] = 'released';
					$arr['wallet_log_claimbale_on'] = Carbon::now();
					$arr['encashment_process_taxed'] = $value2;
					$arr['encashment_process'] = $encashment_process; 
					Mlm_slot_log::slot_array($arr);
				}
			}
			$data['per_slot'] = $all_log_per_slot;
			$data['structured'] = $all_log_structured;
			$update_sum['encashment_process_sum'] = $sum;
			Tbl_mlm_encashment_process::where('encashment_process', $encashment_process)->update($update_sum);

			// $encash_data = AuditTrail::get_table_data("tbl_mlm_encashment_process","encashment_process",$encashment_process);
			// AuditTrail::record_logs("Added","mlm_encash",$encashment_process,"",serialize($encash_data));
		}
	}
	public static function get_sum_wallet($slot_id)
	{
		$active_wallet = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $slot_id)->whereNull('encashment_process')->where('wallet_log_status', 'released')->sum('wallet_log_amount');
		if($active_wallet == null)
		{
			$active_wallet = 0;
		}
		return $active_wallet;
	}
}