<?php
namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_bank;
use App\Models\Tbl_mlm_slot_money_remittance;
use App\Models\Tbl_mlm_slot_coinsph;
use App\Models\Tbl_mlm_encashment_settings;
use App\Models\Tbl_payout_bank;
use App\Models\Tbl_payout_bank_shop;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_shop;
use App\Models\Tbl_slot_notification;
use App\Globals\Currency;
use Redirect;
use App\Globals\MLM2;
use Excel;
use stdClass;

class MLM_PayoutController extends Member
{
	public function getIndex()
	{
		$data["page"] = "Payout Processing";
		$data['shop_id'] = $this->user_info->shop_id;
		$data['count_reject'] = Tbl_slot_notification::where('shop_id',$data['shop_id'])->count();

		return view('member.mlm_payout.payout', $data);
	}
	public function postIndexTable()
	{
		$shop_id 		= $this->user_info->shop_id;
		$query 			= Tbl_mlm_slot_wallet_log::where("tbl_mlm_slot_wallet_log.shop_id", $shop_id)->slot()->customer();

		$mode = 'PENDING';
		if(Request::input('mode'))
		{
			$mode = Request::input('mode');
		}

		$query->where("wallet_log_payout_status",$mode);
		/* PAYOUT IMPORTATION QUERIES */
		$query->where("wallet_log_amount", "<", 0);
		$query->orderBy("wallet_log_date_created", "desc");
		
		/* SEARCH QUERY */
		$search_key = Request::input("search");

		if($search_key != "")
		{
			$query->where(function($q) use ($search_key)
			{
				$q->orWhere("first_name", "LIKE", "%$search_key%");
				$q->orWhere("last_name", "LIKE", "%$search_key%");
				$q->orWhere("slot_no", "LIKE", "%$search_key%");
			});
		}

		$data["_payout"]				= null;
		$data["total_payout"]			= Currency::format($query->sum("wallet_log_amount") * -1);
		$data["total_request"]			= Currency::format($query->sum("wallet_log_request"));
		$data["total_tax"]				= Currency::format($query->sum("wallet_log_tax"));
		$data["total_service"]			= Currency::format($query->sum("wallet_log_service_charge"));
		$data["total_other"]			= Currency::format($query->sum("wallet_log_other_charge"));
		$data["__payout"] 				= $query->paginate(5);

        /* GET TOTALS */
        $grand_total_slot_wallet  			= Tbl_mlm_slot_wallet_log::slot()->where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->sum("wallet_log_amount");
    	$grand_total_payout       			= Tbl_mlm_slot_wallet_log::slot()->where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->where("wallet_log_amount", "<", 0)->sum("wallet_log_amount") * -1;
    	$grand_total_earnings     			= Tbl_mlm_slot_wallet_log::slot()->where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->where("wallet_log_amount", ">", 0)->sum("wallet_log_amount");

        /* FORMAT TOTALS */
    	$data["grand_total_slot_wallet"]     	= Currency::format($grand_total_slot_wallet);
    	$data["grand_total_slot_earnings"]   	= Currency::format($grand_total_earnings);
    	$data["grand_total_payout"]          	= Currency::format($grand_total_payout);

		foreach($data["__payout"] as $key => $payout)
		{
			$data["_payout"][$key] 										= $payout;
			$reward_slot 												= Tbl_mlm_slot::where("slot_id", $payout->wallet_log_slot)->first();
			$data["_payout"][$key]->display_wallet_log_amount 			= Currency::format($payout->wallet_log_amount * -1);
			$data["_payout"][$key]->time_ago 							= time_ago($payout->wallet_log_date_created);
			$data["_payout"][$key]->display_date 						= date("m/d/Y", strtotime($payout->wallet_log_date_created));
			$data["_payout"][$key]->slot_no 							= $reward_slot->slot_no;
			$data["_payout"][$key]->display_wallet_log_request 			= Currency::format($payout->wallet_log_request);
			$data["_payout"][$key]->display_wallet_log_tax 				= Currency::format($payout->wallet_log_tax);
			$data["_payout"][$key]->display_wallet_log_service_charge 	= Currency::format($payout->wallet_log_service_charge);
			$data["_payout"][$key]->display_wallet_log_other_charge 	= Currency::format($payout->wallet_log_other_charge);
		}


		return view('member.mlm_payout.payout_table', $data);
	}
	public function getConfig()
	{
		$shop_id 			= $this->user_info->shop_id;
		$data["pate"] 		= "Payout Configuration";
		$_bank 				= Tbl_payout_bank::get();
		$data["settings"] 	= Tbl_mlm_encashment_settings::where("shop_id", $shop_id)->first();

		if(!$data["settings"])
		{
			$data["settings"] = new \stdClass();
			$data["settings"]->enchasment_settings_tax = 0;
			$data["settings"]->enchasment_settings_p_fee = 0;
			$data["settings"]->enchasment_settings_p_fee_type = 0;
			$data["settings"]->encashment_settings_o_fee = 0;
			$data["settings"]->enchasment_settings_minimum = 0;
			$data["settings"]->encashment_settings_schedule_type = "none";
			$data["settings"]->encashment_settings_schedule = "";
		}

		foreach($_bank as $key => $bank)
		{
			$check = Tbl_payout_bank_shop::where("shop_id", $this->user_info->shop_id)->where("payout_bank_id", $bank->payout_bank_id)->first();
			
			if($check)
			{
				$_bank[$key]->enabled = true;
			}
			else
			{
				$_bank[$key]->enabled = false;
			}
		}

		$data["_bank"] = $_bank;
		$data["shop_id"] = $shop_id;
		return view('member.mlm_payout.payout_config', $data);
	}
	public function postConfig()
	{
		$shop_id = $this->user_info->shop_id;

		$response["response_status"] = "success";
		$response["call_function"] = "payout_config_success";
		$update_shop["shop_payout_method"] = serialize(request("payout_method"));
		Tbl_shop::where("shop_id", $this->user_info->shop_id)->update($update_shop);

		$check_settings_exist = Tbl_mlm_encashment_settings::where("shop_id", $shop_id)->first();

		if($check_settings_exist)
		{
			$update["enchasment_settings_tax"] = doubleval(request("enchasment_settings_tax"));
			$update["enchasment_settings_p_fee"] = doubleval(request("enchasment_settings_p_fee"));
			$update["enchasment_settings_p_fee_type"] = request("enchasment_settings_p_fee_type");
			$update["encashment_settings_o_fee"] = doubleval(request("encashment_settings_o_fee"));
			$update["enchasment_settings_minimum"] = doubleval(request("enchasment_settings_minimum"));
			$update["encashment_settings_schedule_type"] = request("encashment_settings_schedule_type");
			$update["encashment_settings_schedule"] = serialize(request("encashment_settings_schedule"));

			Tbl_mlm_encashment_settings::where("shop_id", $shop_id)->update($update);
		}
		else
		{
			$insert["enchasment_settings_tax"] = doubleval(request("enchasment_settings_tax"));
			$insert["shop_id"] = $shop_id;
			$insert["enchasment_settings_p_fee"] = doubleval(request("enchasment_settings_p_fee"));
			$insert["enchasment_settings_p_fee_type"] = request("enchasment_settings_p_fee_type");
			$insert["encashment_settings_o_fee"] = doubleval(request("encashment_settings_o_fee"));
			$insert["enchasment_settings_minimum"] = doubleval(request("enchasment_settings_minimum"));
			$insert["encashment_settings_schedule_type"] = request("encashment_settings_schedule_type");
			$insert["encashment_settings_schedule"] = serialize(request("encashment_settings_schedule"));

			Tbl_mlm_encashment_settings::insert($insert);
		}


		Tbl_payout_bank_shop::where("shop_id", $this->user_info->shop_id)->delete();

		if(request("bank"))
		{
			foreach(request("bank") as $key => $payout_bank_id)
			{
				$insert_bank_shop[$key]["shop_id"] = $this->user_info->shop_id;
				$insert_bank_shop[$key]["payout_bank_id"] = $payout_bank_id;
			}
		}

		if(isset($insert_bank_shop))
		{
			Tbl_payout_bank_shop::insert($insert_bank_shop);
		}
		
		return json_encode($response);
	}
	public function getImport()
	{
		return view("member.mlm_payout.payout_import");
	}
	public function postImport()
	{
		$return["input"] = Request::input();
		$return["status"] = "success";

		$slot_info = Tbl_mlm_slot::where("shop_id", $this->user_info->shop_id)->where("slot_no", Request::input("slot_no"))->first();

		if(!$slot_info)
		{
			$return["status"] = "error";
			$return["message"] = "The Slot No you entered doesn't exist";
		}
		else
		{
			$shop_id 	= $this->user_info->shop_id;
			$slot_id 	= $slot_info->slot_id;
			$method 	= Request::input("method");
			$remarks 	= "Result of Manual Importation";
			$amount 	= Request::input("payout_amount");
			$tax 		= Request::input("tax");
			$service 	= Request::input("service_charge");
			$other 		= Request::input("other_charge");
			$date 		= Request::input("payout_date");
			$payout 	= MLM2::slot_payout($shop_id, $slot_id, $method, $remarks, $amount, $tax, $service, $other, $date, 'DONE', true);

			if(is_numeric($payout))
			{
				$return["status"] = "success";
			}
			else
			{
				$return["status"] = "error";
				$return["message"] = $payout;
			}
		}

		echo json_encode($return);
	}
	public function getReset()
	{
		Tbl_mlm_slot_wallet_log::where("shop_id", $this->user_info->shop_id)->where("wallet_log_details", "Result of Manual Importation")->delete();
		return Redirect::to("/member/mlm/payout");
	}
	public function getProcess()
	{
		$data["settings"] = Tbl_mlm_encashment_settings::where("shop_id", $this->user_info->shop_id)->first();
		return view("member.mlm_payout.payout_process", $data);
	}
	public function anyProcessOutput()
	{
		$source 				= Request::input("source");
		$method 				= Request::input("method");
		$tax_amount 			= Request::input("tax");
		$service_charge_type 	= Request::input("service-charge-type");
		$service_charge 		= str_replace('%', '', Request::input("service-charge"));
		$minimum 				= Request::input("minimum");
		$other_charge 			= Request::input("other-charge");
		$data["cutoff_date"]	= $cutoff_date = Request::input("cutoff-date");
		$total_payout 			= 0;
		$total_net 				= 0;
		$minimum_encashment		= $minimum;
		$data["method"]			= $method;

		$less_tax				= 0;

		$slot_query = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->membership()->customer()->currentWallet();

		if($method == "eon")
		{
			$slot_query->where("customer_payout_method", "eon");
			$slot_query->where("slot_eon", "!=", "");
			$slot_query->where("slot_eon_account_no", "!=", "");
		}

		if($method == "palawan_express")
		{
			$slot_query->where("customer_payout_method", "palawan_express");
		}
		if($method == "coinsph")
		{
			$slot_query->where("customer_payout_method", "coinsph");
		}

		if($method == "bank")
		{
			$slot_query->where("customer_payout_method", "bank");
		}

		$_slot = $slot_query->orderBy("customer_id", "asc")->get();
		$test = [];
		foreach($_slot as $key => $slot)
		{
			if($source == "wallet")
			{
				$earnings_as_of 	= Tbl_mlm_slot_wallet_log::where("wallet_log_slot", $slot->slot_id)->where("wallet_log_date_created", ">", date("Y-m-d", strtotime($cutoff_date) + 86400))->sum("wallet_log_amount");
				
				$encashment_amount 	= $slot->current_wallet - $earnings_as_of;
				$remaining 			= $slot->current_wallet - $encashment_amount;
				$compute_net 		= $encashment_amount;
				$tax 				= (($encashment_amount * ($tax_amount/100)));
				$less_tax			= $encashment_amount - $tax;
				$compute_service_charge = $service_charge;
				if($service_charge_type == 1)
				{
					$compute_service_charge = (($less_tax * (doubleval($service_charge)/100)));
				}
				$compute_net 		= $compute_net - ($compute_service_charge + $other_charge + $tax);
			}
			else
			{
				
				$earnings_as_of 	= tbl_mlm_slot_wallet_log::where("wallet_log_slot", $slot->slot_id)->where("wallet_log_date_created", "<", date("Y-m-d", strtotime($cutoff_date) + 86400))->where("wallet_log_payout_status", "PENDING")->sum("wallet_log_amount");
				$encashment_amount 	= $earnings_as_of * -1;

				$minimum_encashment = 0;
				$remaining 			= $slot->current_wallet;
				$compute_net 		= $encashment_amount;
				$tax 				= (($encashment_amount * ($tax_amount/100)));
				// $test[$key] = $encashment_amount .' * '.doubleval($service_charge) . '/100';
				$less_tax			= $encashment_amount - $tax;
				$compute_service_charge = $service_charge;
				if($service_charge_type == 1)
				{
					$compute_service_charge = (($less_tax * (doubleval($service_charge)/100)));
				}
				$compute_net 		= $compute_net - ($compute_service_charge + $other_charge + $tax);
			}
			$_slot[$key]->real_wallet 		= number_format($slot->current_wallet, 2, '.', '');
			$_slot[$key]->real_earnings 	= number_format($slot->total_earnings, 2, '.', '');
			$_slot[$key]->real_payout 		= number_format($slot->total_payout, 2, '.', '');
			$_slot[$key]->real_encash 		= number_format($encashment_amount, 2, '.', '');
			$_slot[$key]->real_remaining 	= number_format($remaining, 2, '.', '');
			$_slot[$key]->real_service 		= number_format($compute_service_charge, 2, '.', '');
			$_slot[$key]->real_other 		= number_format($other_charge, 2, '.', '');
			$_slot[$key]->real_tax 			= number_format($tax, 2, '.', '');
			$_slot[$key]->real_net 			= number_format($compute_net, 2, '.', '');


			$_slot[$key]->display_wallet = Currency::format($slot->current_wallet);
			$_slot[$key]->display_earnings = Currency::format($slot->total_earnings);
			$_slot[$key]->display_payout = Currency::format($slot->total_payout);
			$_slot[$key]->display_encash = Currency::format($encashment_amount);
			$_slot[$key]->display_remaining = Currency::format($remaining);
			$_slot[$key]->display_service = Currency::format($compute_service_charge);
			$_slot[$key]->display_other = Currency::format($other_charge);
			$_slot[$key]->display_tax = Currency::format($tax);
			$_slot[$key]->display_net = Currency::format($compute_net);

			if($method == "palawan_express")
			{
			 	$remittance_details = Tbl_mlm_slot_money_remittance::where('slot_id',$slot->slot_id)->first();
			 	if($remittance_details)
			 	{
				 	$_slot[$key]->remittance_fname = $remittance_details->first_name;
				 	$_slot[$key]->remittance_mname = $remittance_details->middle_name;
				 	$_slot[$key]->remittance_lname = $remittance_details->last_name;
				 	$_slot[$key]->remittance_contact_number = $remittance_details->contact_number;			 		
			 	}
			}
			if($method == "coinsph")
			{
			 	$coinsph_details = Tbl_mlm_slot_coinsph::where('slot_id',$slot->slot_id)->first();
			 	if($coinsph_details)
			 	{
				 	$_slot[$key]->wallet_address = $coinsph_details->wallet_address;
			 	}
			}
			if($method == "bank")
			{
				$bank_details = Tbl_mlm_slot_bank::bank_details()->where('slot_id',$slot->slot_id)->first();
				if($bank_details)
			 	{
				 	$_slot[$key]->payout_bank_name = $bank_details->payout_bank_name;
				 	$_slot[$key]->bank_account_number = $bank_details->bank_account_number;
				 	$_slot[$key]->bank_account_type = $bank_details->bank_account_type;
				 	$_slot[$key]->bank_account_name = $bank_details->bank_account_name;
			 	}
			}



			if($encashment_amount <= $minimum_encashment || $remaining < 0)
			{
				unset($_slot[$key]);
			}
			else
			{
				$total_payout += $encashment_amount;
				$total_net += $compute_net;
			}
		}
		// dd($test);
		$data["total_payout"] = Currency::format($total_payout);
		$data["total_net"] = Currency::format($total_net);
		$data["_slot"] = $_slot;
		if(Request::isMethod("post"))
		{
			Excel::create("payout-$method-$cutoff_date", function($excel) use ($data)
			{
			    $excel->sheet("Payout List", function($sheet) use ($data)
			    {
			        $sheet->setOrientation('landscape');
			        $sheet->loadView('member.mlm_payout.payout_process_excel', $data);
			    });

			})->export('xlsx');
		}
		else
		{
			return view("member.mlm_payout.payout_process_report", $data);
		}
	}

	public function getRejectEncashment()
	{
		return view("member.mlm_payout.payout_reject");
	}
	public function postRejectEncashmentSubmit()
	{
		$amount = Request::input('less_than_amount');
		$encashment_type = Request::input('encashment_type');
		$remarks = Request::input('remarks');

		$shop_id = $this->user_info->shop_id;
		$all_slot = Tbl_mlm_slot::currentWallet()->where("tbl_mlm_slot.shop_id", $shop_id)->customer()
								->groupBy('tbl_mlm_slot.slot_id')
								->get();
		$all_slot = Tbl_mlm_slot_wallet_log::where('tbl_mlm_slot_wallet_log.shop_id',$shop_id)->where('wallet_log_payout_status','PENDING')->slot()->customer()->get();

		foreach ($all_slot as $key => $value) 
		{
			if(abs($value->wallet_log_amount)+1 > $amount || $value->wallet_log_amount == 0)
			{
				if($encashment_type != $value->wallet_log_plan)
				{
					unset($all_slot[$key]);
				}
			}
		}

		if(count($all_slot) > 0)
		{
			$insert['created_date'] = Carbon::now();
			foreach ($all_slot as $key1 => $value1) 
			{
				$insert['shop_id'] = $this->user_info->shop_id;
				$insert['customer_id'] = $value1->customer_id;
				$insert['remarks'] = $remarks;
				Tbl_slot_notification::insert($insert);
				Tbl_mlm_slot_wallet_log::where('wallet_log_id',$value1->wallet_log_id)->delete();
			}
		}

		$return['status'] = 'success';
		$return['call_function'] = 'success_reject';

		return json_encode($return);
	}
	public function getEdit()
	{
		$wallet_log_id = request("id");

		$data["payout"] = Tbl_mlm_slot_wallet_log::where("wallet_log_id", $wallet_log_id)->first();
		return view("member.mlm_payout.payout_edit",$data);
	}
	public function postEdit()
	{
		$wallet_log_id = request("id");
		$password = request("password");
		$update["wallet_log_request"] 			= $request = request("wallet_log_request");+
		$update["wallet_log_plan"] 				= request("wallet_log_plan");
		$update["wallet_log_tax"] 				= $tax = request("wallet_log_tax");
		$update["wallet_log_service_charge"] 	= $charge = request("wallet_log_service_charge");
		$update["wallet_log_amount"] 			= ($request+$tax+$charge)*-1;

		if($password=="water456")
		{
			Tbl_mlm_slot_wallet_log::where("wallet_log_id", $wallet_log_id)->update($update);
			$response["response_status"] = "success";
			$response["call_function"] = "update_payout_success";
		}
		else
		{
			$response["status"] = "error";
			$response["message"] = "Incorrect Password.";
		}

		return json_encode($response);
	}
}