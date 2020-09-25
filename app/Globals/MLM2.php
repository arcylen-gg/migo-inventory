<?php
namespace App\Globals;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_customer;
use App\Models\Tbl_brown_rank;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_item;
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_rank_repurchase_cashback_item;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_list;
use App\Models\Tbl_mlm_stairstep_settings;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_mlm_cashback_convert_history;
use App\Models\Rel_cashback_convert_history;
use App\Models\Tbl_mlm_encashment_settings;
use App\Globals\Mlm_tree;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_compute;
use App\Globals\Warehouse2;
use App\Globals\Mlm_slot_log;
use App\Models\Tbl_mlm_point_log_setting;


use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Validator;
use stdClass;
use DB;
use session;

class MLM2
{
	public static $shop_id;

	public static function current_wallet($shop_id, $slot_id, $ignore_pending = false)
	{
		$data = Tbl_mlm_slot_wallet_log::where("shop_id", $shop_id)->where("wallet_log_slot", $slot_id)->sum("wallet_log_amount");
		if($ignore_pending == true)
		{
			$data = Tbl_mlm_slot_wallet_log::where("shop_id", $shop_id)->where("wallet_log_slot", $slot_id)->where('wallet_log_payout_status','!=','PENDING')->sum("wallet_log_amount");
		}

		return $data;
	}
	public static function check_unused_code($shop_id, $customer_id)
	{
		$return = 0;
		$data = Tbl_transaction::where('transaction_reference_table','tbl_customer')->where('transaction_reference_id', $customer_id)->get();
	
		foreach($data as $key => $value)
		{
			$list = Tbl_transaction_list::where('transaction_id', $value->transaction_id)->get();


			foreach($list as $key2 => $value2)
			{
				$get_item_warehouse = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','transaction_list')
																	    ->where('record_consume_ref_id',$value2->transaction_list_id)
																	    ->where('item_in_use','unused')
																	    ->first();
			
				if($get_item_warehouse)
				{
					$return = $get_item_warehouse->record_log_id;
				}
			}
		}

		return $return;
	}
	public static function check_purchased_code($shop_id, $customer_id)
	{
		$return = null;
		
		$query = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','transaction_list');
		
		$_list = Tbl_transaction_list::
										join("tbl_transaction", "tbl_transaction.transaction_id", "=", "tbl_transaction_list.transaction_id")
										->where('transaction_reference_table','tbl_customer')
										->where('tbl_transaction.transaction_reference_id', $customer_id)
										->get();
										
		if(count($_list) > 0)
		{
			$query->where(function($q) use ($_list)
			{
				foreach($_list as $key2 => $list)
				{
					$q->orWhere("record_consume_ref_id", $list->transaction_list_id);
				}
			});
			
			$_codes = $query->get();
			
			foreach($_codes as $key => $code)
			{
				$slot = Tbl_mlm_slot::where("slot_id", $code->mlm_slot_id_created)->customer()->first();
				$transaction_list = Tbl_transaction_list::where("transaction_list_id", $code->record_consume_ref_id)->first();
				
				$_codes[$key]->transaction_number = $transaction_list->transaction_number;
				
				if($slot)
				{
					$_codes[$key]->used_by = ($slot ? $slot->slot_no : "-") . " (" . $slot->first_name . " " . $slot->last_name .")";
				}
				else
				{
					$_codes[$key]->used_by = "-";
				}
			}
		}
		else
		{
			$_codes = array();	
		}
			

		

		
		return $_codes;
	}
	public static function get_code($record_log_id)
	{
		$data =	Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)
										    		->first();
		$return['mlm_pin'] = $data->mlm_pin;
		$return['mlm_activation'] = $data->mlm_activation;
		
		return $return;
	}
	public static function slot_payout($shop_id, $slot_id, $method, $remarks, $amount, $tax = 0, $service = 0, $other = 0, $date = null, $status = "DONE", $ignore_pending = false)
	{
		$total = doubleval(str_replace(",","",$amount)) + doubleval(str_replace(",","",$tax)) + doubleval(str_replace(",","",$service)) + doubleval(str_replace(",","",$other));
		$current_wallet = Self::current_wallet($shop_id, $slot_id, $ignore_pending);

		if(doubleval(round($current_wallet, 0)) < doubleval(round($total, 0)))
		{
			return "The current wallet of slot is not enough.";
		}
		else
		{
			$insert["shop_id"] 						= $shop_id;
			$insert["wallet_log_slot"] 				= $slot_id;
			$insert["wallet_log_details"] 			= $remarks;
			$insert["wallet_log_amount"] 			= doubleval((str_replace(",","",$total))) * -1;
			$insert["wallet_log_plan"] 				= $method;
			$insert["wallet_log_request"] 			= doubleval((str_replace(",","",$amount)));
			$insert["wallet_log_tax"] 				= doubleval((str_replace(",","",$tax)));
			$insert["wallet_log_service_charge"] 	= doubleval((str_replace(",","",$service)));
			$insert["wallet_log_other_charge"] 		= doubleval((str_replace(",","",$other)));
			$insert["wallet_log_payout_status"] 	= $status;
			$insert["wallet_log_date_created"]		= ($date == null ? Carbon::now() : date("Y-m-d", strtotime($date)));

			if($ignore_pending == true)
			{
				Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $slot_id)->where('shop_id',$shop_id)->where('wallet_log_payout_status','PENDING')->delete();
			}
			return Tbl_mlm_slot_wallet_log::insertGetId($insert);
		}
	}
	public static function get_sponsor_network($shop_id, $slot_no, $level = null)
	{
		$slot_id = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $slot_no)->value("slot_id");
		$_tree_query = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot_id)->orderBy("sponsor_tree_level")->child_info()->customer();

		if($level)
		{
			$_tree_query->where("sponsor_tree_level", $level);
		}
		
		$_tree = $_tree_query->get();
		foreach($_tree as $key => $tree)
		{
			$_tree[$key]->ordinal_level = ordinal($tree->sponsor_tree_level) . " Level";
			$_tree[$key]->display_slot_date_created = date("F d, Y", strtotime($tree->slot_created_date));
		}

		return $_tree;
	}

	public static function get_sponsor_network_tree($shop_id, $slot_no)
	{
		$slot_id = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $slot_no)->value("slot_id");
		$_tree = Tbl_tree_sponsor::select(DB::raw("*, count(slot_id) AS slot_count"))->where("sponsor_tree_parent_id", $slot_id)->groupBy("sponsor_tree_level")->orderBy("sponsor_tree_level")->child_info()->customer()->get();
	
		foreach($_tree as $key => $tree)
		{
			$_tree[$key]->ordinal_level = ordinal($tree->sponsor_tree_level) . " Level";
			$_tree[$key]->display_slot_count = number_format($tree->slot_count) . " SLOT(S)";
		}

		return $_tree;
	}
	public static function verify_sponsor($shop_id, $sponsor_key)
	{

		$slot_info = Tbl_mlm_slot::shop($shop_id)->where("slot_nick_name", $sponsor_key)->where("slot_defaul", 1)->first();

		if(!$slot_info)
		{
			$slot_info = Tbl_mlm_slot::shop($shop_id)->where("slot_no", $sponsor_key)->first();
		}

		return $slot_info;
	}
	public static function unplaced_slots($shop_id, $customer_id)
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->where("slot_placement", 0)->where("slot_position", "")->get();
		return $_slot;
	}
	
	public static function customer_slots($shop_id, $customer_id)
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->currentWallet()->get();

		foreach($_slot as $key =>  $slot)
		{
			$_slot[$key]->display_total_earnings = Currency::format($slot->total_earnings);
			$_slot[$key]->display_current_wallet = Currency::format($slot->current_wallet);


			if($slot->brown_rank_id == null)
			{
				$slot->brown_rank_id = Tbl_brown_rank::where("rank_shop_id", $shop_id)->orderBy("required_slot", "asc")->value("rank_id");
			}

			/* BROWN RANKING - TODO: FOR MYPHONE ONLY */
            if($slot->brown_rank_id)
            {
                $brown_next_rank = Tbl_brown_rank::where("rank_id",">", $slot->brown_rank_id)->orderBy("rank_id")->first();
            }
            else
            {
                $brown_next_rank = null;
            }

            if($brown_next_rank)
            {
                $_slot[$key]->brown_next_rank = strtoupper($brown_next_rank->rank_name);
                $brown_rank_required_slots    = $brown_next_rank->required_slot;
                $brown_count_required         = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot->slot_id)->where("sponsor_tree_level", "<=", $brown_next_rank->required_uptolevel)->count();
                
                $_slot[$key]->brown_next_rank_requirements = $brown_rank_required_slots;
                $_slot[$key]->brown_next_rank_current      = $brown_count_required;

                $_slot[$key]->required_direct = $brown_next_rank->required_direct;
                $_slot[$key]->current_direct  = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot->slot_id)->where("sponsor_tree_level", "=", 1)->count();;

                $brown_direct_rank_percentage = @($_slot[$key]->current_direct / $_slot[$key]->required_direct);
                $brown_rank_rank_percentage   = @($brown_count_required / $brown_rank_required_slots);

                $_slot[$key]->brown_direct_rank_percentage = $brown_direct_rank_percentage * 100;
                $_slot[$key]->brown_rank_rank_percentage   = $brown_rank_rank_percentage * 100;
            }
            else
            {
                $_slot[$key]->brown_next_rank = strtoupper("NO NEXT RANK");
                $brown_rank_required_slots = "NO NEXT RANK";
                $brown_count_required = "NO NEXT RANK";
                $_slot[$key]->brown_next_rank_requirements = "NO NEXT RANK";
            }

		}

		return $_slot;
	}
	public static function customer_income_summary($shop_id, $customer_id)
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->currentWallet()->get();

		$return["_wallet"] = new stdClass();
		$return["_wallet"]->current_wallet = 0;
		$return["_wallet"]->total_earnings = 0;
		$return["_wallet"]->total_payout = 0;
		$return["_wallet"]->total_points = 0;


		$_plan = Tbl_mlm_slot_wallet_log::groupBy("wallet_log_plan")->where("shop_id", $shop_id)->get();
		$_plan_ignore = array("E Money", "Repurchase", "Tours Wallet Points", "Tours Wallet", "Encashment", "Cheque", "Undefined");

		foreach($_plan as $key => $plan)
		{
			$string_plan = "complan_" . strtolower($plan->wallet_log_plan);
			$label = Self::complan_to_label($shop_id, $string_plan);

			if(in_array($label, $_plan_ignore))
			{
				unset($_plan[$key]);
			}
			else
			{
				$_plan[$key]->string_plan			= $string_plan;
				$_plan[$key]->label 				= $label;
				$return["_wallet"]->$string_plan 	= 0;
			}
		}

		$return["_wallet"]->complan_triangle 					= 0;
		$return["_wallet"]->complan_direct 						= 0;
		$return["_wallet"]->complan_builder 					= 0;
		$return["_wallet"]->complan_leader 						= 0;
		$return["_wallet"]->complan_repurchase_cashback 		= 0;
		$return["_wallet"]->complan_rank_repurchase_cashback 	= 0;
		$return["_wallet"]->complan_stairstep 					= 0;

		$return["_wallet_plan"] = $_plan;
		
		$return["_points"] = new stdClass();




		$_plan_points = Tbl_mlm_slot_points_log::where("shop_id", $shop_id)->slot()->groupBy("points_log_complan")->get();

		foreach($_plan_points as $key=> $plan)
		{
			$string_plan 						= strtolower($plan->points_log_complan);
			$_plan_points[$key]->string_plan	= $string_plan;
			$_plan_points[$key]->label 			= Self::complan_to_label($shop_id, $string_plan);
			$return["_points"]->$string_plan 	= 0;
		}

		$return["_points"]->brown_builder_points 	= 0;
		$return["_points"]->brown_leader_points 	= 0;
		$return["_points"]->rank_pv 				= 0;
		$return["_points"]->rank_gpv 				= 0;
		$return["_points"]->stairstep_pv 			= 0;
		$return["_points"]->stairstep_gpv 			= 0;
		$return["_points"]->binary 					= 0;
		$return["_points"]->direct 					= 0;
		$return["_points"]->advertisement_bonus 	= 0;
		$return["_points"]->maintenance 			= 0;
		$return["_point_plan"] = $_plan_points;

		$return["slot_count"] = 0;

		foreach($_slot as $key =>  $slot)
		{
			$return["_wallet"]->current_wallet += $slot->current_wallet;
			$return["_wallet"]->total_earnings += $slot->total_earnings;
			$return["_wallet"]->total_payout += ($slot->total_payout) * -1;
			$return["slot_count"]++;	

			$_slot_wallet = Tbl_mlm_slot_wallet_log::where("wallet_log_slot", $slot->slot_id)->get();

			foreach($_slot_wallet as $slot_wallet)
			{
				if($slot_wallet->wallet_log_plan == "DIRECT_PASS_UP")
				{
					$proceed_passup_direct = 0;
					$check_level_tree = Tbl_tree_sponsor::where("sponsor_tree_child_id",$slot_wallet->wallet_log_slot_sponsor)->where("sponsor_tree_parent_id",$slot->slot_id)->first();
					if($check_level_tree)
					{
						if($check_level_tree->sponsor_tree_level == 1)
						{
							$proceed_passup_direct = 1;
						}
					}

					if($proceed_passup_direct == 1)
					{
						$wallet_plan = strtolower("complan_DIRECT");

						if(!isset($return["_wallet"]->$wallet_plan))
						{
							$return["_wallet"]->$wallet_plan = $slot_wallet->wallet_log_amount;
						}
						else
						{
							$return["_wallet"]->$wallet_plan += $slot_wallet->wallet_log_amount;
						}	
					}
					else
					{
						$wallet_plan = strtolower("complan_" .$slot_wallet->wallet_log_plan);

						if(!isset($return["_wallet"]->$wallet_plan))
						{
							$return["_wallet"]->$wallet_plan = $slot_wallet->wallet_log_amount;
						}
						else
						{
							$return["_wallet"]->$wallet_plan += $slot_wallet->wallet_log_amount;
						}	
					}
				}
				else
				{
					$wallet_plan = strtolower("complan_" .$slot_wallet->wallet_log_plan);

					if(!isset($return["_wallet"]->$wallet_plan))
					{
						$return["_wallet"]->$wallet_plan = $slot_wallet->wallet_log_amount;
					}
					else
					{
						$return["_wallet"]->$wallet_plan += $slot_wallet->wallet_log_amount;
					}	
				}
			}

			$_slot_points = Tbl_mlm_slot_points_log::where("points_log_slot", $slot->slot_id)->get();


			foreach($_slot_points as $slot_points)
			{
				$wallet_plan = strtolower($slot_points->points_log_complan);
				if(!isset($return["_points"]->$wallet_plan))
				{
					$return["_points"]->$wallet_plan = $slot_points->points_log_points;
				}
				else
				{
					$return["_points"]->$wallet_plan += $slot_points->points_log_points;
				}

				$return["_wallet"]->total_points += $slot_points->points_log_points;

				if($wallet_plan == "repurchase_cashback")
				{
					if($slot_points->points_log_converted == 1)
					{
						$return["_points"]->$wallet_plan -= $slot_points->points_log_points;
					}
					
					$return["_wallet"]->total_points -= $slot_points->points_log_points;
				}
			}
		}

		$_wallet = json_encode($return["_wallet"]);
		$_points = json_encode($return["_points"]);

		/* DISPLAY FORMAT */
		foreach(json_decode($_wallet) as $key => $wallet)
		{
			$display_string = "display_" . $key;
			$return["_wallet"]->$display_string = Currency::format($wallet);
		}

		/* DISPLAY FORMAT */
		foreach(json_decode($_points) as $key => $points)
		{
			$display_string = "display_" . $key;
			$return["_points"]->$display_string = number_format($points, 2) . " POINT(S)";
		}

		$return["_slot"] = $_slot;
		$return["display_slot_count"] = number_format($return["slot_count"], 0) . " SLOT(S)";
		$return["_wallet"]->display_total_points = number_format($return["_wallet"]->total_points, 2) . " POINT(S)";;
		
		return $return;
	}
	public static function complan_to_label($shop_id, $string)
	{
		switch ($string)
		{
			case "":
				$string = strtolower($string);
				$string = str_replace("complan_", "", $string);
				$string = ucwords($string);
			break;

			default:
				$string = strtolower($string);
				$string = str_replace("complan_", "", $string);
				$string = str_replace("_", " ", $string);
				$string = ucwords($string);
			break;
		}

		return $string;
	}
	public static function customer_direct($shop_id, $customer_id, $limit = 10,$paginate = 0)
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->get();

		$query = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id", $shop_id)->customer();

		$query->where(function($q) use ($_slot)
		{
			foreach($_slot as $slot)
			{
				$q->orWhere("slot_sponsor", $slot->slot_id);
			}
		});

		if($limit!=0)
		{
			$query->limit($limit);
		}
		if($paginate!=0)
		{
			$_direct = $query->orderBy("slot_id", "desc")->paginate($paginate);
		}
		else
		{
			$_direct = $query->orderBy("slot_id", "desc")->get();
		}
		

		foreach($_direct as $key => $direct)
		{
			$_direct[$key]->display_date = date("F d, Y", strtotime($direct->slot_created_date));
			$_direct[$key]->time_ago = time_ago($direct->slot_created_date);
			$_direct[$key]->profile_image = ($direct->profile == "" ? "/themes/brown/img/user-placeholder.png" : $direct->profile);
		}

		return $_direct;
	}
	public static function customer_rewards($shop_id, $customer_id, $limit = 10,$sort_by = "0")
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->get();
		$query = Tbl_mlm_slot_wallet_log::where("shop_id", $shop_id);

		if($sort_by != "0")
		{
			$sort_by = strtoupper($sort_by);
			$query = $query->where("wallet_log_plan",$sort_by);
		}

		$query->where(function($q) use ($_slot)
		{
			foreach($_slot as $slot)
			{
				$q->orWhere("wallet_log_slot", $slot->slot_id);
			}
		});

		if($shop_id != 52)
		{
			$query->where("wallet_log_amount", ">", 0);
		}

		

		if($limit == 0)
		{
			$_reward = $query->orderBy("wallet_log_id", "desc")->paginate(10);
			$store_pagine["notification_paginate"] = $_reward->appends(request()->except('page'))->render();
			session($store_pagine);
		}
		else
		{
			$query->limit($limit);
			$_reward = $query->orderBy("wallet_log_id", "desc")->get();
		}
		

		foreach($_reward as $key => $reward)
		{
			$reward_slot = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot)->first();
			$_reward[$key]->display_wallet_log_amount = Currency::format($reward->wallet_log_amount);
			$_reward[$key]->time_ago = time_ago($reward->wallet_log_date_created);
			$_reward[$key]->display_date = date("F d, Y", strtotime($reward->wallet_log_date_created));
			$_reward[$key]->log = Self::customer_rewards_contructor($reward);
			$_reward[$key]->slot_no = $reward_slot->slot_no;
		}

		return $_reward;
	}
	public static function customer_rewards_points($shop_id, $customer_id, $limit = 10,$sort_by = "0")
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->get();
		$query = Tbl_mlm_slot_points_log::slot()->where("shop_id", $shop_id)->where("slot_owner",$customer_id);
		
		if($sort_by != "0")
		{
			if($sort_by == 'RPV')
			{
				$query = $query->whereIn("points_log_type",array('RPV','RGPV'));
			}
			else
			{
				$query = $query->where("points_log_type",$sort_by);
			}
		}

		if($limit == 0)
		{
			$_reward = $query->orderBy("points_log_id", "desc")->paginate(10);
			$store_pagine["notification_paginate_points"] = $_reward->appends(request()->except('page'))->render();
			session($store_pagine);
		}
		else
		{
			$query->limit($limit);
			$_reward = $query->orderBy("points_log_id", "desc")->get();
		}
		

		foreach($_reward as $key => $reward)
		{
			$reward_slot = Tbl_mlm_slot::where("slot_id", $reward->points_log_slot)->first();
			$_reward[$key]->log_amount = number_format($reward->points_log_points,2);
			$_reward[$key]->time_ago = time_ago($reward->points_log_date_claimed);
			$_reward[$key]->display_date = date("F d, Y", strtotime($reward->points_log_date_claimed));
			$_reward[$key]->log = Self::customer_rewards_points_contructorV2($reward);
			$_reward[$key]->slot_no = $reward_slot->slot_no;
			$_reward[$key]->points_log_type = $reward->points_log_type;
		}

		return $_reward;
	}
	public static function customer_rewards_points_contructor($reward)
	{
		$from_slot = Tbl_mlm_slot::where("slot_id",$reward->points_log_Sponsor)->first();
		if($from_slot)
		{
			$message   = "Your slot no ".$reward->slot_no." earned ".$reward->log_amount." ".$reward->points_log_type." from slot no ".$from_slot->slot_no;
			return $message;
		}
		else
		{
			return "---";
		}
	}
	public static function customer_rewards_points_contructorV2($reward)
	{
		$from_slot = Tbl_mlm_slot::where("slot_id",$reward->points_log_Sponsor)->first();
		$query = Tbl_mlm_point_log_setting::where("point_log_setting_type",$reward->points_log_type)->first();
		$message="No Details";
		if(count($query)>0)
		{
			$message="";
			$template = $query->point_log_notification;
			$notif = explode('/', $template);
			foreach ($notif as $t)
			{
				if($t == '_slot_no')
				{
					$message.=$reward->slot_no;
				}
				else if($t == '_sponsor_slot_no')
				{
					$message.=$from_slot->slot_no;
				}
				else if($t == '_amount')
				{
					$message.=$reward->log_amount;
				}
				else
				{
					$message.=$t;
				}
			}
		}
		

		return $message;
	}
	public static function customer_total_payout($customer_id)
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->currentWallet()->get();
		$total_payout = 0;

		foreach($_slot as $key =>  $slot)
		{
			$total_payout += ($slot->total_payout) * -1;
		}

		return $total_payout;
	}
	public static function customer_payout($shop_id, $customer_id, $limit = 10)
	{
		$_slot = Tbl_mlm_slot::where("slot_owner", $customer_id)->get();
		$query = Tbl_mlm_slot_wallet_log::where("shop_id", $shop_id);

		$query->where(function($q) use ($_slot)
		{
			foreach($_slot as $slot)
			{
				$q->orWhere("wallet_log_slot", $slot->slot_id);
			}
		});

		$query->where("wallet_log_amount", "<", 0);	

		if($limit == 0)
		{
			$_reward = $query->orderBy("wallet_log_id", "desc")->paginate(50);
			$store_pagine["payout_paginate"] = $_reward->render();
			session($store_pagine);
		}
		else
		{
			$query->limit($limit);
			$_reward = $query->orderBy("wallet_log_id", "desc")->get();
		}

		foreach($_reward as $key => $reward)
		{
			$reward_slot = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot)->first();
			$_reward[$key]->display_wallet_log_amount = Currency::format($reward->wallet_log_amount * -1);
			$_reward[$key]->time_ago = time_ago($reward->wallet_log_date_created);
			$_reward[$key]->display_date = date("F d, Y", strtotime($reward->wallet_log_date_created));
			$_reward[$key]->log = Self::customer_rewards_contructor($reward);
			$_reward[$key]->slot_no = $reward_slot->slot_no;

			$_reward[$key]->display_wallet_log_request = Currency::format($reward->wallet_log_request);
			$_reward[$key]->display_wallet_log_tax = Currency::format($reward->wallet_log_tax);
			$_reward[$key]->display_wallet_log_service_charge = Currency::format($reward->wallet_log_service_charge);
			$_reward[$key]->display_wallet_log_other_charge = Currency::format($reward->wallet_log_other_charge);
		}

		return $_reward;
	}
	public static function customer_rewards_contructor($reward)
	{
		switch ($reward->wallet_log_plan)
		{
			case 'DIRECT':
				$sponsor = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot_sponsor)->customer()->first();
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>direct referral bonus</a></b> because of <a href='javascript:'><b>" . $sponsor->slot_no . " (" . $sponsor->first_name . " " . $sponsor->last_name . ")</b></a>.";
			break;
			
			case 'TRIANGLE':
				$sponsor = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot_sponsor)->first();
				$sponsor_sponsor = Tbl_mlm_slot::where("slot_id", $sponsor->slot_placement)->first();
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>pairing bonus</a></b> because of pairing under <a href='javascript:'><b>" . $sponsor_sponsor->slot_no . "</b></a>.";
			break;

			case 'BINARY':
				$sponsor = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot_sponsor)->first();
				$sponsor_sponsor = Tbl_mlm_slot::where("slot_id", $sponsor->slot_placement)->first();
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>binary pairing bonus</a></b></a>.";
			break;

			case 'REPURCHASE_CASHBACK':
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>repurchase cashback</a></b> because of your personal purchase.";
			break;

			case 'UNILEVEL':
				$sponsor = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot_sponsor)->customer()->first();
				$slot_sponsor = "<a href='javascript'><b>" . $sponsor->slot_no . " (" . $sponsor->first_name . " " . $sponsor->last_name . ")</b></a>";
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>unilevel cashback</a></b> because " . $slot_sponsor . " purchased something using her account.";
			break;

			case 'MEMBERSHIP_MATCHING':
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>matching bonus</a></b>.";
			break;

			case 'INDIRECT':
				$sponsor = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot_sponsor)->customer()->first();
				$slot_sponsor = "<a href='javascript'><b>" . $sponsor->slot_no . " (" . $sponsor->first_name . " " . $sponsor->last_name . ")</b></a>";
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>indirect referral bonus</a></b> because of " . $slot_sponsor . ".";
			break;

			case 'WALLET_TRANSFER':
				$sponsor = Tbl_mlm_slot::where("slot_id", $reward->wallet_log_slot_sponsor)->customer()->first();
				$slot_sponsor = "<a href='javascript'><b>" . $sponsor->slot_no . " (" . $sponsor->first_name . " " . $sponsor->last_name . ")</b></a>";
				$message = "You earned <b>" . Currency::format($reward->wallet_log_amount) . "</b> from <b><a href='javascript:'>wallet transfer</a></b> of " . $slot_sponsor . ".";
			break;


			default:
				$message = $reward->wallet_log_details;
			break;
		}

		return $message;
	}
	public static function membership($shop_id)
	{
		$return = Tbl_membership::where("membership_archive", 0)->where("shop_id", $shop_id)->get();
		return $return;
	}
	public static function is_mlm_member($shop_id, $customer_id)
	{
 		$count_slots 	= Tbl_mlm_slot::where("slot_owner", $customer_id)->where("shop_id", $shop_id)->count();
 		$mlm_member     = ($count_slots > 0 ? true : false);
 		return $mlm_member;
	}
	public static function is_privilage_card_holder($shop_id, $customer_id)
	{
 		$membership 	= Tbl_mlm_slot::where("slot_owner", $customer_id)->where("shop_id", $shop_id)->value('slot_membership');
 		$privilage_card_holder = ($membership == 1 ? true : false);
 		return $privilage_card_holder;
	}
	public static function membership_info($shop_id, $membership_id)
	{
		$return = Tbl_membership::where("membership_archive", 0)->where("shop_id", $shop_id)->where("membership_id", $membership_id)->first();
		return $return;
	}
	public static function check_membership_code($shop_id, $pin, $activation)
	{
		return Tbl_membership::shop($shop_id)->codes($pin, $activation)->first();
	}
	public static function use_membership_code($shop_id, $pin, $activation, $slot_id_created, $remarks = null, $consume = array())
	{
		$update["mlm_slot_id_created"] 		= $slot_id_created;
		$update["record_log_date_updated"]	= Carbon::now();
		$update["item_in_use"] 				= "used";
		$update["record_inventory_status"]	= 1;

		if($remarks)
		{
			$initial_record 					= Tbl_warehouse_inventory_record_log::codes($shop_id, $pin, $activation)->first();
			$update["record_item_remarks"]	 	= $initial_record->record_item_remarks . "\r\n" . $remarks;

			if($initial_record->record_warehouse_id != Warehouse2::get_main_warehouse($shop_id))
			{
				$consume['name'] = 'offline_transaction';
				$consume['id'] = $slot_id_created;
			}
			if(!session('online_transaction'))
			{
				$consume['name'] = 'offline_transaction';
				$consume['id'] = $slot_id_created;

            	Warehouse2::update_inventory_count($initial_record->record_warehouse_id, 0, $initial_record->record_item_id, -1);
			}
            Warehouse2::insert_item_history($initial_record->record_log_id);
		}
		
		if(count($consume) > 0)
		{
			$update["record_consume_ref_name"] 	= $consume['name'];
			$update["record_consume_ref_id"]	= $consume['id'];
		}

		Tbl_warehouse_inventory_record_log::codes($shop_id, $pin, $activation)->update($update);
	}
	public static function create_slot_no_rule()
	{
		$store["create_slot_no_rule"] = true;
		session($store);
	}
	public static function create_slot($shop_id, $customer_id, $membership_id, $sponsor, $slot_no = null, $slot_type = "PS")
	{
		$slot_creation_limit = Tbl_mlm_plan_setting::where("shop_id",$shop_id)->first()->max_slot_per_account;
		$slot_creation_count = Tbl_mlm_slot::where("slot_owner",$customer_id)->count();
		if($slot_no)
		{
			$insert["slot_no"] = $slot_no;
		}

		$insert["shop_id"] 				= $shop_id;
		$insert["slot_owner"] 			= $customer_id;
		$insert["slot_membership"] 		= $membership_id;
		$insert["slot_sponsor"]			= $sponsor;
		$insert["slot_created_date"]	= Carbon::now();
		$insert["slot_status"]			= $slot_type;
		$insert["slot_placement"]		= 0;
		$insert["slot_position"]		= "";
		$insert["distributed"]			= 0;

		$rules["shop_id"] 				= ["required","exists:tbl_shop"];
		$rules["slot_owner"] 			= ["required","exists:tbl_customer,customer_id"];
		$rules["slot_membership"] 		= ["required","exists:tbl_membership,membership_id"];
		
		if(!session("create_slot_no_rule"))
		{
			$rules["slot_sponsor"]			= ["required","exists:tbl_mlm_slot,slot_id"];
		}
	
		$rules["slot_created_date"]		= ["required"];
		$rules["slot_status"]			= ["required"];
		

		Self::$shop_id					= $shop_id;
		$rules["slot_no"]				= 	Rule::unique('tbl_mlm_slot')->where(function ($query)
											{
    											$query->where('shop_id', Self::$shop_id);
											});

		$check_restriction = MLM2::check_membership_restriction($membership_id,$sponsor);
        $validator = Validator::make($insert, $rules);
        if($slot_creation_limit != 0 && $slot_creation_count >= $slot_creation_limit)
        {
        	return "Your account cannot create more than ".$slot_creation_limit." slots";
        }
        else if($check_restriction == 1)
        {
        	return "Your sponsor cannot recruit this type of membership.";
        }
		else if ($validator->fails())
		{
			$errors = $validator->errors();
			foreach ($errors->all() as $message)
			{
				session()->forget("create_slot_no_rule");
				return $message;
			}
		}
		else
		{
			$sponsor_slot 	  = Tbl_mlm_slot::where("slot_id",$sponsor)->where("shop_id",$shop_id)->first();


			if(!session("create_slot_no_rule"))
			{
				$customer_sponsor = Tbl_customer::where("shop_id",$shop_id)->where("customer_id",$sponsor_slot->slot_owner)->first();
				$proceed_to_create = 0;
				
				if($customer_sponsor->downline_rule == "auto")
				{
					$proceed_to_create = MLM2::check_sponsor_have_placement($shop_id,$sponsor);
					if($proceed_to_create == 0)
					{
						$response = "Sponsor should have a placement";
					}
	
					if($proceed_to_create == 1)
					{
						$slot_id  = Tbl_mlm_slot::insertGetId($insert);
						$rules    = $customer_sponsor->autoplacement_rule;
						$response = MLM2::matrix_auto($shop_id,$slot_id,$rules);
						if($response != "success")
						{
							$slot_id = $response;
						}
					}
					else
					{
						$slot_id = $response;
					}
				}
				else
				{
					$slot_id  = Tbl_mlm_slot::insertGetId($insert);
				}
			}
			else
			{
				$slot_id  = Tbl_mlm_slot::insertGetId($insert);
			}
			
			session()->forget("create_slot_no_rule");
			return $slot_id;
		}
	}
	public static function matrix_position($shop_id, $slot_id, $placement, $position)
	{
    	$check_target_slot 			= Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
    	$check_target_slot_sponsor  = Tbl_mlm_slot::where("slot_id",$check_target_slot->slot_sponsor)->where("shop_id",$shop_id)->first();

    	if($placement == $slot_id)
    	{
    		$message = "Placement not available 001";
    	}

    	if($check_target_slot_sponsor->slot_placement == 0 || $check_target_slot_sponsor->slot_placement == null)
    	{
    		if($check_target_slot_sponsor->slot_sponsor != null)
    		{
    			$message = "Your upline should placed your first.";
    		}
    	}

    	if($check_target_slot->slot_placement == null && $check_target_slot->slot_placement == 0)
    	{
			if($position == "left" || $position == "right")
			{	
				$check_placement = MLM2::check_placement_exist($shop_id,$placement,$position);
				
				if($check_placement == 0)
				{
					Self::matrix_position_success($shop_id, $slot_id, $placement, $position);
					$message = "success";
				}
				else
				{
					$message = "Placement Already Taken";
				}
			}
			else
			{
				$message =  "Wrong position";
			}
		}
		else
		{
			$message = "Already placed";
		}
		
		
		return $message;
    }
    public static function matrix_position_success($shop_id, $slot_id, $placement, $position)
    {
		$update["slot_placement"]  = $placement;
		$update["slot_position"]   = strtolower($position);
		Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id",$slot_id)->update($update);
    }
    public static function matrix_auto($shop_id, $slot_id, $rule)
    {
    	if($rule == "random")
    	{
    		$response = MLM2::matrix_position_random($shop_id,$slot_id);
			if($response == "success")
			{
				MLM2::entry($shop_id,$slot_id);
			}
    	}
		else if($rule == "autofill")
		{
			$response = MLM2::matrix_position_auto_balance($shop_id,$slot_id);
			if($response == "success")
			{
				MLM2::entry($shop_id,$slot_id);
			}
		}
		else
		{
			$response = "Rule does not exists";
		}
		return $response;
    }
    public static function matrix_position_random($shop_id,$slot_id)
    {
    	$check_target_slot          = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
    	if($check_target_slot->slot_placement == null && $check_target_slot->slot_placement == 0)
    	{
	        $array_position             = array("left", "right");

	        /* INITIALIZE AND CAPTURE DATA */
	        $random_placement           = Tbl_mlm_slot::where("shop_id", $shop_id)->orderBy(DB::raw("rand()"))->value("slot_id");  
	        $random_position            = $array_position[array_rand($array_position)];

	        /* POSITIONING DATA */
	        $slot_placement             = $random_placement;
	        $slot_position              = $random_position;
	        $placement_exist            = MLM2::check_placement_exist($shop_id,$random_placement,$random_position);

	        if($random_placement == $slot_id)
	        {
	        	$placement_exist = 1;
	        }

	        /* RANDOM WHILE PLACEMENT IS STILL TAKEN */
	        while($placement_exist == 1)
	        {
	        	$random_position   = $array_position[array_rand($array_position)];
	            $random_placement  = Tbl_mlm_slot::where("shop_id", $shop_id)->orderBy(DB::raw("rand()"))->value("slot_id"); 
	            $placement_exist   = MLM2::check_placement_exist($shop_id,$random_placement,$random_position);
	            $slot_random       = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id",$random_placement)->first();

    	        if($random_placement == $slot_id)
		        {
		        	$placement_exist = 1;
		        }

		        if($slot_random->slot_placement == 0 || $slot_random->slot_placement == null)
		        {
		        	if($slot_random->slot_sponsor != null && $slot_random->slot_sponsor == 0)
		        	{
		        		$placement_exist = 1;
		        	}
		        }
	        }


	        $update["slot_placement"] = $random_placement;
	        $update["slot_position"]  = $random_position;
	        Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->update($update);

	        $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
	        Mlm_tree::insert_tree_sponsor($slot_info_e, $slot_info_e, 1);
	        Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);

	        return "success";
    	}
    	else
    	{
    		return "Already placed.";
    	}
    }
    public static function matrix_position_auto_balance($shop_id,$slot_id)
    {
    	$message   = "success";
    	$slot_info = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id",$slot_id)->first();
    	if($slot_info->slot_placement == null && $slot_info->slot_placement == 0)
    	{
	        if($slot_info->slot_sponsor != 0 && $slot_info->slot_sponsor != null)
	        {
	            $sponsor = Tbl_mlm_slot::where("slot_id",$slot_info->slot_sponsor)->first();
	            if($sponsor)
	            {
	            	$proceed_to_auto = MLM2::check_sponsor_have_placement($shop_id,$sponsor->slot_id);
	            	if($proceed_to_auto == 1)
	            	{
		                $condition_update = false;
		                $current_level = $sponsor->current_level;
		                while($condition_update == false)
		                {                 
		                    if($current_level == 0)
		                    {
		                        $check_placement = Tbl_mlm_slot::where("slot_placement",$slot_info->slot_sponsor)->where("slot_position","left")->first();
		                        if(!$check_placement)
		                        {
		                            $slot_placement   = $slot_info->slot_sponsor;
		                            $slot_position    = "left";
		                            $slot_level       = 0;
		                            $condition_update = true;
		                            break;
		                        }
		                        else
		                        {
		                           $check_placement = Tbl_mlm_slot::where("slot_placement",$slot_info->slot_sponsor)->where("slot_position","right")->first();
		                           if(!$check_placement)
		                           {
		                             $slot_placement   = $slot_info->slot_sponsor;
		                             $slot_position    = "right";
		                             $slot_level       = 1;
		                             $condition_update = true;
		                             break;
		                           }
		                        }
		                    }
		                    else
		                    {
		                        $placement_tree = Tbl_tree_placement::where("placement_tree_parent_id",$sponsor->slot_id)->where("placement_tree_level",$current_level)->childslot()->orderBy("tbl_mlm_slot.auto_balance_position","ASC")->get();
		                        $current_count  = Tbl_tree_placement::where("placement_tree_parent_id",$sponsor->slot_id)->where("placement_tree_level",$current_level + 1)->childslot()->orderBy("tbl_mlm_slot.auto_balance_position","ASC")->count();
		                        $max_count      = pow(2, $current_level + 1);
		                        if($current_count < $max_count)
		                        {   
		                            $condition_right = true;

		                            foreach($placement_tree as $placement)
		                            {
		                                $check_placement = Tbl_mlm_slot::where("slot_placement",$placement->placement_tree_child_id)->where("slot_position","left")->first();
		                                if(!$check_placement)
		                                {
		                                    $slot_placement   = $placement->placement_tree_child_id;
		                                    $slot_position    = "left";
		                                    $condition_update = true;
		                                    $condition_right  = false;
		                                    if(($current_count + 1) >= $max_count)
		                                    {
		                                        $slot_level = $current_level + 1;
		                                    }
		                                    else
		                                    {
		                                        $slot_level = $current_level;
		                                    }
		                                    break;
		                                }
		                            }


		                            if($condition_right == true)
		                            {
		                                foreach($placement_tree as $placement)
		                                {
		                                    $check_placement = Tbl_mlm_slot::where("slot_placement",$placement->placement_tree_child_id)->where("slot_position","right")->first();
		                                    if(!$check_placement)
		                                    {
		                                        $slot_placement   = $placement->placement_tree_child_id;
		                                        $slot_position    = "right";
		                                        $condition_update = true;
		                                        $condition_right  = false;
		                                        if(($current_count + 1) >= $max_count)
		                                        {
		                                            $slot_level = $current_level + 1;
		                                        }
		                                        else
		                                        {
		                                            $slot_level = $current_level;
		                                        }
		                                        break;
		                                    }
		                                }
		                            }
		                        }
		                    }

		                    $current_level++;
		                }

		                if($condition_update == true)
		                {
		                    $update_sponsor['current_level']  = $slot_level;
		                    Tbl_mlm_slot::where('slot_id', $slot_info->slot_sponsor)->update($update_sponsor);

		                    // got the slot
		                    $update['slot_placement'] = $slot_placement;
		                    $update['slot_position']  = $slot_position;

		                    Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);

		                    $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
		                    Mlm_tree::insert_tree_sponsor($slot_info_e, $slot_info_e, 1);
		                    Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
		                }
		                else
		                {
		                	$message = "Some error occurred";
		                }
	            	}

	            }
	        }
	        else
	        {
	        	$message = "Sponsor does not exists.";
	        }
    	}
    	else
    	{
    		$message = "Already placed.";
    	}

        return $message;
    }
    public static function check_placement_exist($shop_id,$placement,$position,$self_downline = 0,$self_owner = null)
    {
        $count_tree_if_exist 		= Tbl_tree_placement::where('placement_tree_position', $position)
                        								->where('placement_tree_parent_id', $placement)
                        								->where('shop_id', $shop_id)
                        								->count();

		

	    if($count_tree_if_exist != 0 || !$placement ||  !$position)
	    {
	    	return 1;
	    } 
	    else if ($self_downline == 1)
	    {
	    	
	    	/* IF DOWNLINE ONLY OF $SELF_OWNER*/
		    $check_shop_slot            = Tbl_mlm_slot::where("slot_id",$placement)->where("shop_id",$shop_id)->first();
		    
		   
		    
		    if($check_shop_slot)
		    {	
		    	if($check_shop_slot->slot_placement != 0 && $check_shop_slot->slot_placement != null)
		    	{
			    	if($placement == $self_owner)
			    	{
			    		return 0;
			    	}
			    	else
			    	{
			    		$owned = Tbl_tree_placement::where('placement_tree_parent_id', $self_owner)
			    								   ->where('placement_tree_child_id',$placement)
	                							   ->where('shop_id', $shop_id)
	                							   ->first();
	                							   
	                							   
	                    if($owned)
	                    {
	                    	return 0;
	                    }            
	                    else
	                    {
	                    	return 1;
	                    }    							   
			    	}
		    	}
		    	else
		    	{
		    		if($check_shop_slot->slot_sponsor == null && $check_shop_slot->slot_placement == null)
		    		{
		    			return 0;
		    		}
		    		else
		    		{
		    			return 1;
		    		}
		    	}
		    }
		    else
		    {	
	    		return 1;
		    }
	    }  
	    else
	    {
	    	
		    $check_shop_slot            = Tbl_mlm_slot::where("slot_id",$placement)->where("shop_id",$shop_id)->first();
		    if($check_shop_slot)
		    {	
		    	if($check_shop_slot->slot_placement != 0 && $check_shop_slot->slot_placement != null)
		    	{
		    		return 0;
		    	}
		    	else
		    	{
		    		if($check_shop_slot->slot_sponsor == null && $check_shop_slot->slot_placement == null)
		    		{
		    			return 0;
		    		}
		    		else
		    		{
		    			return 1;
		    		}
		    	}
		    }
		    else
		    {	
	    		return 1;
		    }
	    }                  								
    }
	public static function entry($shop_id,$slot_id)
	{
        $slot_info = Tbl_mlm_slot::where('slot_id', $slot_id)->where("tbl_mlm_slot.shop_id",$shop_id)
        						 ->membership()
        						 ->membership_points()
        						 ->customer()
        						 ->first();

            // Mlm Computation Plan
            $plan_settings = Tbl_mlm_plan::where('shop_id', $shop_id)
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->get();
            // dd($slot_info,$slot_id);
            
            if($slot_info->slot_status == 'PS')
            {
                foreach($plan_settings as $key => $value)
                {
                    $plan = strtolower($value->marketing_plan_code);
                    $a = Mlm_complan_manager::$plan($slot_info);
                }
            }
            else if($slot_info->slot_status == 'CD')
            {
                $a = Mlm_complan_manager_cd::enter_cd($slot_info);

            }
            else if($slot_info->slot_status == 'FS')
            {
                // no income for fs.
            }



            // check if there are cd graduate
            $b = Mlm_complan_manager_cd::graduate_check($slot_info);
            // $c = Mlm_gc::slot_gc($slot_id);

            Mlm_compute::set_slot_nick_name_2($slot_info);
            if($slot_info->slot_date_computed == "0000-00-00 00:00:00")
            {
            	$update_slot["slot_date_computed"] = Carbon::now();
            }

            $update_slot["distributed"] = 1;
            Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->update($update_slot);
            // End Computation Plan
	}
	public static function purchase($shop_id, $slot_id, $item_id)
	{
		$data = MLM2::item_points($shop_id,$item_id,$slot_id);
		if($data)
		{
			MLM_compute::repurchasev2($slot_id,$shop_id,$data);
		}
	}	
	public static function item_points($shop_id,$item_id,$slot_id = null)
	{
        $item          = Tbl_item::where("item_id",$item_id)->where("shop_id",$shop_id)->first();
        $membership_id = null;
        if($slot_id)
        {
        	$membership_id = Tbl_mlm_slot::where("shop_id",$shop_id)->where('slot_id',$slot_id)->value('slot_membership');
        }
        $item_points   = Tbl_mlm_item_points::joinItem()->where("tbl_item.item_id",$item_id)->where('shop_id',$shop_id)->first();
        if($membership_id)
        {
        	$item_points   = Tbl_mlm_item_points::joinItem()->where("tbl_item.item_id",$item_id)->where('shop_id',$shop_id)->where('tbl_mlm_item_points.membership_id',$membership_id)->first();
        }
        if($item)
        {
	        $data["UNILEVEL"]					= isset($item_points->UNILEVEL) ? $item_points->UNILEVEL : 0;
			$data["REPURCHASE_POINTS"]			= isset($item_points->REPURCHASE_POINTS) ? $item_points->REPURCHASE_POINTS : 0;
			$data["UNILEVEL_REPURCHASE_POINTS"]	= isset($item_points->UNILEVEL_REPURCHASE_POINTS) ? $item_points->UNILEVEL_REPURCHASE_POINTS : 0;
			$data["REPURCHASE_CASHBACK"]		= isset($item_points->REPURCHASE_CASHBACK) ? $item_points->REPURCHASE_CASHBACK : 0;
			$data["REPURCHASE_CASHBACK_POINTS"]	= isset($item_points->REPURCHASE_CASHBACK_POINTS) ? $item_points->REPURCHASE_CASHBACK_POINTS : 0;
			$data["DISCOUNT_CARD_REPURCHASE"]	= isset($item_points->DISCOUNT_CARD_REPURCHASE) ? $item_points->DISCOUNT_CARD_REPURCHASE : 0;
			$data["STAIRSTEP"]					= isset($item_points->STAIRSTEP) ? $item_points->STAIRSTEP : 0;
			$data["BINARY_REPURCHASE"]			= isset($item_points->BINARY_REPURCHASE) ? $item_points->BINARY_REPURCHASE : 0;
			$data["STAIRSTEP_GROUP"]			= isset($item_points->STAIRSTEP_GROUP) ? $item_points->STAIRSTEP_GROUP : 0;
			$data["RANK"]						= isset($item_points->RANK) ? $item_points->RANK : 0;
			$data["RANK_GROUP"]					= isset($item_points->RANK_GROUP) ? $item_points->RANK_GROUP : 0;
			$data["price"]						= isset($item->item_price) ? $item->item_price : 0;
			$data["RANK_REPURCHASE_CASHBACK"]   = MLM2::rank_cashback_points($shop_id,$slot_id,$item_id);
			return $data;
        }
        else
        {
        	return null;
        }
	}
	public static function check_sponsor_have_placement($shop_id,$slot_id)
	{
		$setting = Tbl_mlm_plan_setting::where("shop_id",$shop_id)->first();
		if($setting->plan_settings_placement_required == 1)
		{
			$sponsor = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
			if($sponsor)
			{	
		    	if(($sponsor->slot_placement == 0 || $sponsor->slot_placement == null) && ($sponsor->slot_sponsor != null || $sponsor->slot_sponsor != 0))
		    	{
		    		$proceed = 0;
		    	}
		    	else
		    	{
		    		$proceed = 1;
		    	}
			}
			else
			{
				$proceed = 0;
			}
		}
		else
		{
			$proceed = 1;
		}

        // if($check_sponsor->slot_placement == 0 || $check_sponsor->slot_placement == null)
        // {
        //     if($check_sponsor->slot_sponsor != null)
        //     {
        //         $sponsor_have_placement = 0;
        //     }
        // }
    	return $proceed;
	}
	public static function rank_cashback_points($shop_id,$slot_id,$item_id)
	{
		$data["RANK_REPURCHASE_CASHBACK"] = 0;
		
		if($slot_id)
		{
			$slot = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
			if($slot)
			{
				$current_rank = Tbl_mlm_stairstep_settings::where("stairstep_id",$slot->stairstep_rank)->first();
				if($current_rank)
				{
					$rank_cashback_points = Tbl_rank_repurchase_cashback_item::where("item_id",$item_id)->where("rank_id",$current_rank->stairstep_id)->first();
					if($rank_cashback_points)
					{
						$data["RANK_REPURCHASE_CASHBACK"] = $rank_cashback_points->amount;
					}
				}
			}
		}

		return $data["RANK_REPURCHASE_CASHBACK"];
	}
	public static function check_membership_restriction($membership_id,$sponsor_id)
	{
		$return 	= 0;
		$membership = Tbl_membership::where("membership_id",$membership_id)->first();
		if($membership)
		{
			$sponsor_slot = Tbl_mlm_slot::where("slot_id",$sponsor_id)->first();
			if($sponsor_slot)
			{
				$sponsor_membership = Tbl_membership::where("membership_id",$sponsor_slot->slot_membership)->first();
				if($sponsor_membership)
				{
					if($membership->membership_restricted == 0 && $sponsor_membership->membership_restricted == 1)
					{
						$return = 1;
					}
				}
			}
		}

		return $return;
	}
	public static function convert_repurchase_cashback_points($shop_id)
	{
		$get_cashback 	  						 = Tbl_mlm_slot_points_log::slot()->where("shop_id",$shop_id)->where("points_log_type","RCP")->where("points_log_converted",0)->get();
		$converted_points 						 = null;
		// dd($get_cashback);
		$insert["cashback_convert_history_date"] = Carbon::now();
		$insert["shop_id"] 						 = $shop_id;
		$history_id 							 = Tbl_mlm_cashback_convert_history::insertGetId($insert);
		foreach($get_cashback as $cashback)
		{
			if(!isset($converted_points[$cashback->points_log_slot]))
			{
				$converted_points[$cashback->points_log_slot]  = $cashback->points_log_points;
			}
			else
			{
				$converted_points[$cashback->points_log_slot] += $cashback->points_log_points;
			}

			$rel_insert["rel_points_log_id"] 			   = $cashback->points_log_id;
			$rel_insert["rel_cashback_convert_history_id"] = $history_id;
			$rel_insert["shop_id"] 						   = $history_id;
			Rel_cashback_convert_history::insert($rel_insert);
		}	

		if($converted_points != null)
		{
			foreach($converted_points as $key => $convert)
			{
				$slot_info               = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id",$key)->first();
                $log_array['earning']    = $convert;
                $log_array['level']      = 0;
                $log_array['level_tree'] = 'Binary Tree';
                $log_array['complan']    = 'REPURCHASE_CASHBACK_POINTS';

                $log = Mlm_slot_log::log_constructor($slot_info, $slot_info,  $log_array);

                $arry_log['wallet_log_slot']         = $slot_info->slot_id;
                $arry_log['shop_id']                 = $slot_info->shop_id;
                $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
                $arry_log['wallet_log_details']      = $log;
                $arry_log['wallet_log_amount']       = $convert;
                $arry_log['wallet_log_plan']         = "REPURCHASE_CASHBACK_POINTS";
                $arry_log['wallet_log_status']       = "n_ready";   
                $arry_log['wallet_log_claimbale_on'] = Mlm_complan_manager::cutoff_date_claimable('REPURCHASE_CASHBACK_POINTS', $slot_info->shop_id); 
                Mlm_slot_log::slot_array($arry_log);
			}


			Tbl_mlm_slot_points_log::slot()->where("shop_id",$shop_id)->where("points_log_type","RCP")->where("points_log_converted",0)->update(['points_log_converted' => 1]);
		}

	}
}