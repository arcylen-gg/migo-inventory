<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_user;
use App\Models\Tbl_user_position;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_warehouse;

use App\Globals\Account;
use App\Globals\Warehouse;
use App\Globals\Seed_manual;
use App\Globals\Utilities;
use App\Globals\Payroll;
use App\Globals\Settings;
use App\Globals\Warehouse2;
use App\Globals\Purchasing_inventory_system;
use App\Globals\AdminNotification;
use App\Globals\AccountingTransaction;

use Crypt;
use Redirect;
use Request;
use View;
use Session;
use Carbon\Carbon;
use App\Globals\Mlm_seed;
class Member extends Controller
{
	public $user_info;
	public $current_warehouse;  
	function __construct()
	{
		$this->middleware(function ($request, $next)
		{
			/* IF SESSION FOR EMAIL OR PASSWORD DOESN'T EXIST - REDIRECT TO FRONTPAGE */
			if(!session('user_email') || !session('user_password'))
			{
				return Redirect::to("/")->send();
			}
			else
			{

				/* CHECK IF USERNAME DOESN'T EXIST IN DB - REDIRECT TO FRONTPAGE */
				$user_info = Tbl_user::position()->where("user_email", session('user_email'))->shop()->first();
				// dd($user_info);
				if(!$user_info)
				{
					return Redirect::to('/')->send();
				}
				else
				{
					/* CHECK IF PASSWORD IS NOT CORRECT - REDIRECT TO FRONTPAGE */
					$decrypted_session_password = Crypt::decrypt(session("user_password"));
					$decrypted_db_password = Crypt::decrypt($user_info->user_password);

					if($decrypted_db_password != $decrypted_session_password)
					{
						return Redirect::to('/')->send();
					}
					else
					{
						$this->user_info = $user_info;


						/* INSERT DEFAULT WAREHOUSE */
						Warehouse::put_default_warehouse($this->user_info->shop_id);
						/* Seed MLM Email */
						Mlm_seed::seed_mlm($this->user_info->shop_id);
						/* Seed Settings Currency convertion */
						Settings::set_currency_default($this->user_info->shop_id);

						$shop_id_used    = $user_info->shop_id;
						$check_if_dev    = Tbl_user_position::where("position_id",$this->user_info->user_level)->first();
						$is_dev          = 0;
						if($check_if_dev)
						{
							if($check_if_dev->position_rank == 0)
							{
								$is_dev = 1;
							}
						}


						$check_warehouse = Tbl_user_warehouse_access::where("user_id",$user_info->user_id)->where("warehouse_id",session("warehouse_id_".$shop_id_used))->first();
						if($is_dev == 1)
						{   
							$check_session = session("warehouse_id_".$shop_id_used);
							$check_exist   = Tbl_warehouse::where("warehouse_shop_id",$shop_id_used)->where("warehouse_id",$check_session)->first();
							if(!$check_exist)
							{
								$current_warehouse = Tbl_warehouse::where("warehouse_shop_id",$shop_id_used)->orderBy("warehouse_id","ASC")->first();
							}
							else
							{
								$current_warehouse = $check_exist;
							}
							Session::put('warehouse_id_'.$shop_id_used, $current_warehouse->warehouse_id);
						}
						else if($check_warehouse)
						{
							$current_warehouse = Tbl_warehouse::where("warehouse_shop_id",$shop_id_used)->where("warehouse_id",$check_warehouse->warehouse_id)->first();
							
							if(!$current_warehouse)
							{
								$check_if_got_one  = Tbl_user_warehouse_access::where("user_id",$user_info->user_id)->first();
								if($check_if_got_one)
								{
									$current_warehouse = Tbl_warehouse::where("warehouse_shop_id",$shop_id_used)->where("warehouse_id",$check_if_got_one->warehouse_id)->first();
								}
							}
						}
						else
						{
							$current_warehouse = null;
							$check_if_got_one  = Tbl_user_warehouse_access::where("user_id",$user_info->user_id)->first();
							if($check_if_got_one)
							{
								$current_warehouse = Tbl_warehouse::where("warehouse_shop_id",$shop_id_used)->where("warehouse_id",$check_if_got_one->warehouse_id)->first();

								Session::put('warehouse_id_'.$shop_id_used, $current_warehouse->warehouse_id);
							}
						}

						$this->current_warehouse = $current_warehouse;
						// if($is_dev == 1)
						// {
						// 	$warehouse_list  = Tbl_warehouse::inventory()->orderBy("main_warehouse","ASC")->select_info($user_info->shop_id, 0)->groupBy("tbl_warehouse.warehouse_id")->where('tbl_warehouse.archived',0)->get(); 
						// }
						// else
						// {
						// 	$warehouse_list  = Tbl_warehouse::inventory()->join("tbl_user_warehouse_access","tbl_user_warehouse_access.warehouse_id","=","tbl_warehouse.warehouse_id")->where("tbl_user_warehouse_access.user_id",$user_info->user_id)->where('tbl_warehouse.archived',0)->select_info($user_info->shop_id, 0)->groupBy("tbl_warehouse.warehouse_id")->get(); 
						// }
						$warehouse_list = null;
						if(isset($this->current_warehouse->warehouse_id))
						{
							$warehouse_list = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, 0,$this->current_warehouse->warehouse_id);
						}
						View::share('user_info', $user_info);
						View::share('current_warehouse', $current_warehouse);
						View::share('_warehouse_list_shortcut', $warehouse_list);
						//View::share('_for_pis_only', Purchasing_inventory_system::check());


						/* CHECK IF SHOP STATUS IS INITIAL - REDIRECT TO INITIAL PAGE */
						if($user_info->shop_status == "initial" && Request::segment(2) != "setup")
						{
							return Redirect::to('/member/setup')->send();
						}
					}
				}
			}

			View::share("_page", Utilities::filterPageList());

			View::share("migo_customization", AccountingTransaction::settings($this->user_info->shop_id, "migo_customization"));
			View::share("range_sales_discount", AccountingTransaction::settings($this->user_info->shop_id, "range_sales_discount"));
			View::share('carbon_now', Carbon::now()->format('Y-m-d'));
			View::share("count_notification", AdminNotification::countNoti($this->user_info->shop_id, Warehouse2::get_current_warehouse($this->user_info->shop_id)));
			View::share("_notification", AdminNotification::get($this->user_info->shop_id, Warehouse2::get_current_warehouse($this->user_info->shop_id)));

			/* Seeding */
			Seed_manual::auto_seed();
			Seed_manual::insert_default_landing_cost($this->user_info->shop_id);
			Seed_manual::insert_umtypes($this->user_info->shop_id);

			/* Set Email Configuration */
			Settings::set_mail_setting($this->user_info->shop_id);
			
			/* GET CURRENT DOMAIN FOR FRONTEND */
			if($this->user_info->shop_domain != "unset_yet")
			{
				$data["frontend_domain"] = $this->user_info->shop_domain;
			}
			else
			{
				$data["frontend_domain"] = $this->user_info->shop_key . "." . get_domain();
			}

			View::share("frontend_domain", $data["frontend_domain"]);
			View::share("accounting_module", AccountingTransaction::settings($this->user_info->shop_id, "accounting_module"));

			/*TRANSACTION REFERENCE NUMBER*/
			Seed_manual::put_transaction_reference_number($this->user_info->shop_id);
			//dd($this->user_info->shop_id);
			/* INSERT TAX PERIOD */
			Payroll::generate_tax_period($this->user_info->shop_id);
			/* INSERT DEFAULT CHART OF ACCOUNT */
			Account::put_default_account($this->user_info->shop_id);
			/* INSERT TAX TABLE PER SHOP */
			Payroll::tax_reference($this->user_info->shop_id);
			/* INSERT SSS TABLE PER SHOP */
			Payroll::generate_sss($this->user_info->shop_id);
			/* INSERT PHILHEALTH TABLE PER SHOP */
			Payroll::generate_philhealth($this->user_info->shop_id);
			/* INSERT PAGIBIG TABLE PER SHOP */
			Payroll::generate_pagibig($this->user_info->shop_id);
			/* INSERT PAPER SIZE FOR PAYSLIP [PAYROLL] */
			Payroll::generate_paper_size($this->user_info->shop_id);
			/* INSERT DEFAULT TERMS */
			Seed_manual::put_default_tbl_terms($this->user_info->shop_id);
			/* INSERT DEFAULT PAYMENT METHOD */
			Seed_manual::put_default_tbl_payment_method($this->user_info->shop_id);
			/* INSERT DEFAULT INVENTORY PREFIX */
			Seed_manual::put_inventory_prefix($this->user_info->shop_id);
			/* INSERT DEFAULT MLM PIN PREFIX */
			Seed_manual::put_mlm_pin_prefix($this->user_info->shop_id, $this->user_info->shop_key);
			/* INSERT DEFAULT NAME FOR SOCIAL NETWORKING SITE */
			Seed_manual::put_name_social_networking_site($this->user_info->shop_id);
			/* INSERT MAIN WAREHOUSE */
			/* INSERT DEFAULT OFFLINE WAREHOUSE */
			Seed_manual::insert_brown_offline_warehouse($this->user_info->shop_id);
			
			Warehouse::mainwarehouse_for_developer($this->user_info->user_id, $this->user_info->shop_id);
			// dd($this->user_info);
			

			return $next($request);
		});
	}
	
	public function show_no_access()
	{
		return view('member.no_access');
	}
	public function show_no_access_modal()
	{
		return view('member.no_access_modal');
	}
	public function getShop_Id()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}
	public function save_warehouse_id($warehouse_id)
	{
		$user 			  = Tbl_user::where("user_email", session('user_email'))->shop()->first();
		$check_warehouse  = Tbl_user_warehouse_access::where("user_id",$user->user_id)->where("warehouse_id",$warehouse_id)->first();
		$shop_id_used     = $user->shop_id;
		if($check_warehouse)
		{
			$data["response"] = "success";
			$warehouse_id = $warehouse_id;
		}
		else
		{
			$data["response"] = "fail";
			$warehouse_id = null;
		}

		Session::put('warehouse_id_'.$shop_id_used, $warehouse_id);

		return $data;
	}
}