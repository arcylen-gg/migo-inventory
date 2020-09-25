<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_tablet_data;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_manual_credit_memo;
use App\Models\Tbl_manual_receive_payment;
use App\Models\Tbl_sir_inventory;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_sir;

use App\Globals\Invoice;
use App\Globals\Accounting;
use App\Globals\CreditMemo;
use App\Globals\ReceivePayment;
use Carbon\carbon;
use DB;

/**
 * Syncing data for tablet
 *
 * @author Arcylen
 */

class tablet_sync
{
	/**
     * Create a Default Chart of Account General
     *
     * @return  status
     */

	public static function sync1($data_id = 0)
	{
		dd('test syntax');
	}
	public static function sync($data_id = 0, $sync_type = '')
    {
    	if($data_id)
    	{
    		$tablet_data = Tbl_tablet_data::where("data_id",$data_id)->first();
    		// dd(unserialize($tablet_data->sir_data));
    		if($tablet_data)
    		{
    			$all_transaction = unserialize($tablet_data->sir_data);

    			$sir_id = $tablet_data->sir_id;
    			$sir_data = $all_transaction['sir_data'];
    			$agent_data = $all_transaction['agent_data'];
    			$all_inv = $all_transaction['invoice'];
    			$all_rp = $all_transaction['receive_payment'];
    			$all_cm = $all_transaction['credit_memo'];
    			$all_sir_inventory = $all_transaction['sir_inventory'];
    			$all_manual_inv = $all_transaction['manual_inv'];
    			$all_manual_rp = $all_transaction['manual_rp'];
    			$all_manual_cm = $all_transaction['manual_cm'];

    			$all_customer = $all_transaction['customer'];
    			$all_customer_address = $all_transaction['customer_address'];
    			/*FOR INVOICE*/
    			foreach ($all_inv as $key => $value)
    			{
    				$customer_info                      = [];
					$customer_info['customer_id']       = $value->inv->inv_customer_id;
					$customer_info['customer_email']    = $value->inv->inv_customer_email;

					$invoice_info                       = [];
					$invoice_info['invoice_terms_id']   = $value->inv->inv_terms_id;
			        $invoice_info['new_inv_id']         = $value->inv->new_inv_id;
					$invoice_info['invoice_date']       = datepicker_input($value->inv->inv_date);
					$invoice_info['invoice_due']        = datepicker_input($value->inv->inv_due_date);
					$invoice_info['billing_address']    = $value->inv->inv_customer_billing_address;

					$invoice_other_info                 = [];
					$invoice_other_info['invoice_msg']  = $value->inv->inv_message;
					$invoice_other_info['invoice_memo'] = $value->inv->inv_memo;

					$total_info                         = [];
					$total_info['total_subtotal_price'] = $value->inv->inv_subtotal_price;
					$total_info['ewt']                  = $value->inv->ewt;
					$total_info['total_discount_type']  = $value->inv->inv_discount_type;
					$total_info['total_discount_value'] = $value->inv->inv_discount_value;
					$total_info['taxable']              = $value->inv->taxable;
					$total_info['total_overall_price']  = $value->inv->inv_overall_price;

					$item_info                          = [];
					$_itemline                          = $value->invline;

					foreach($_itemline as $key_item => $item_line)
			        {
			            if($item_line)
			            {
			                $item_info[$key_item]['item_service_date']  = $item_line->invline_service_date;
			                $item_info[$key_item]['item_id']            = $item_line->invline_item_id;
			                $item_info[$key_item]['item_description']   = $item_line->invline_description;
			                $item_info[$key_item]['um']                 = $item_line->invline_um;
			                $item_info[$key_item]['quantity']           = $item_line->invline_qty;
			                $item_info[$key_item]['rate']               = $item_line->invline_rate;
			                $item_info[$key_item]['discount']           = $item_line->invline_discount;
			                $item_info[$key_item]['discount_remark']    = $item_line->invline_discount_remark;
			                $item_info[$key_item]['amount']             = $item_line->invline_amount;
			                $item_info[$key_item]['taxable']            = $item_line->taxable;
			                $item_info[$key_item]['ref_name']           = $item_line->invline_ref_name;
			                $item_info[$key_item]['ref_id']             = $item_line->invline_ref_id;
						}
					}

					$inv_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info, $value->inv->is_sales_receipt, true);

					foreach ($all_cm as $key_cm => $value_cm)
					{
						if($value->inv->credit_memo_id == $key_cm)
						{
            				$cm_item_info = [];
							$cm_customer_info["cm_customer_id"] = $value_cm->cm->cm_customer_id;
				            $cm_customer_info["cm_customer_email"] = $value_cm->cm->cm_customer_email;
				            $cm_customer_info["cm_date"] = datepicker_input($value_cm->cm->cm_date);
				            $cm_customer_info["cm_message"] = "";
				            $cm_customer_info["cm_memo"] = "";
				            $cm_customer_info["cm_amount"] = $value_cm->cm->cm_amount;

				            $_cm_items = $value_cm->cmline;
				            foreach ($_cm_items as $keys_cmline => $cmline) 
			                { 
			                    if($cmline)
			                    {
			                    	$cm_item_info[$keys_cmline]['item_service_date']  = $cmline->cmline_service_date;
			                        $cm_item_info[$keys_cmline]['item_id']            = $cmline->cmline_item_id;
			                        $cm_item_info[$keys_cmline]['item_description']   = $cmline->cmline_description;
			                        $cm_item_info[$keys_cmline]['um']                 = $cmline->cmline_um;
			                        $cm_item_info[$keys_cmline]['quantity']           = $cmline->cmline_qty;
			                        $cm_item_info[$keys_cmline]['rate']               = $cmline->cmline_rate;
			                        $cm_item_info[$keys_cmline]['amount']             = $cmline->cmline_amount;
			                    }
			            	}

			            	$cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $inv_id, true);
			            	unset($all_cm->$key_cm);

			            	foreach ($all_sir_inventory as $key_inventory => $value_inventory) 
							{
								if($value_inventory->sir_inventory_ref_name == 'credit_memo' && $value_inventory->sir_inventory_ref_id == $key_cm)
								{
									$ins['sir_item_id'] = $value_inventory->sir_item_id;
									$ins['inventory_sir_id'] = $value_inventory->inventory_sir_id;
									$ins['sir_inventory_ref_name'] = $value_inventory->sir_inventory_ref_name;
									$ins['sir_inventory_count'] = $value_inventory->sir_inventory_count;
									$ins['sir_inventory_ref_id'] = $cm_id;
									$ins['created_at'] = $value_inventory->created_at;
									$ins['is_bundled_item'] = $value_inventory->is_bundled_item;

									Tbl_sir_inventory::insert($ins);
									unset($all_sir_inventory->$key_inventory);
								}
							}

						}
					}

					foreach ($all_manual_inv as $key_manual => $manual_inv) 
					{
						if($manual_inv->inv_id == $key)
						{
							$ins_manual_inv['sir_id'] = $sir_id;
							$ins_manual_inv['inv_id'] = $inv_id;
							$ins_manual_inv['manual_invoice_date'] = $manual_inv->manual_invoice_date;
							$ins_manual_inv['created_at'] = $manual_inv->created_at;

							Tbl_manual_invoice::insert($ins_manual_inv);
							unset($all_manual_inv->$key_manual);
						}
					}

					foreach ($all_sir_inventory as $key_inventory => $value_inventory) 
					{
						if($value_inventory->sir_inventory_ref_name == 'invoice' && $value_inventory->sir_inventory_ref_id == $key)
						{
							$ins['sir_item_id'] = $value_inventory->sir_item_id;
							$ins['inventory_sir_id'] = $value_inventory->inventory_sir_id;
							$ins['sir_inventory_ref_name'] = $value_inventory->sir_inventory_ref_name;
							$ins['sir_inventory_count'] = $value_inventory->sir_inventory_count;
							$ins['sir_inventory_ref_id'] = $inv_id;
							$ins['created_at'] = $value_inventory->created_at;
							$ins['is_bundled_item'] = $value_inventory->is_bundled_item;
							Tbl_sir_inventory::insert($ins);
							unset($all_sir_inventory->$key_inventory);
						}
					}

					foreach ($all_rp as $key_rp => $value_rp)
					{
						$txn_line = $value_rp->rpline;
				        foreach($txn_line as $key_rp_inv => $txn)
				        {
				        	if($txn->rpline_reference_name == 'invoice' && $key == $txn->rpline_reference_id)
				        	{
				        		// dd(Request::input());
						        $insert["rp_shop_id"]           = $value_rp->rp->rp_shop_id;
						        $insert["rp_customer_id"]       = $value_rp->rp->rp_customer_id;
						        $insert["rp_ar_account"]        = $value_rp->rp->rp_ar_account or 0;
						        $insert["rp_date"]              = datepicker_input($value_rp->rp->rp_date);
						        $insert["rp_total_amount"]      = $value_rp->rp->rp_total_amount;
						        $insert["rp_payment_method"]    = $value_rp->rp->rp_payment_method;
						        $insert["rp_memo"]              = $value_rp->rp->rp_memo;
						        $insert["date_created"]         = $value_rp->rp->date_created;

					            $insert["rp_ref_name"]        	= "";
					            $insert["rp_ref_id"]          	= 0;
						        if($value_rp->rp->rp_ref_name)
						        {
						            $insert["rp_ref_name"]        = $value_rp->rp->rp_ref_name;
						            $insert["rp_ref_id"]          = $value_rp->rp->rp_ref_id;
						        }

						        $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

						        $txn_line = $value_rp->rpline;
						        $cm_amt = 0;
						        foreach($txn_line as $key => $txn)
						        {
						            if($txn)
						            {
						                $insert_line["rpline_rp_id"]            = $rcvpayment_id;
						                $insert_line["rpline_reference_name"]   = $txn->rpline_reference_name;
						                $insert_line["rpline_reference_id"]     = $inv_id;

						                $cm_amount = CreditMemo::cm_amount($txn->inv_id);
						                $cm_amt += $cm_amount;
						                $insert_line["rpline_amount"]           = $txn->rpline_amount + $cm_amount;

						                Tbl_receive_payment_line::insert($insert_line);

						                if($insert_line["rpline_reference_name"] == 'invoice')
						                {
						                    Invoice::updateAmountApplied($insert_line["rpline_reference_id"], true);
						                }
						            }
						        }
						        /* Transaction Journal */
						        $entry["reference_module"]      = "receive-payment";
						        $entry["reference_id"]          = $rcvpayment_id;
						        $entry["name_id"]               = $insert["rp_customer_id"];
						        $entry["total"]                 = $insert["rp_total_amount"] + $cm_amt;
						        $entry_data[0]['account_id']    = $insert["rp_ar_account"];
						        $entry_data[0]['vatable']       = 0;
						        $entry_data[0]['discount']      = 0;
						        $entry_data[0]['entry_amount']  = $insert["rp_total_amount"] + $cm_amt;
						        $inv_journal = Accounting::postJournalEntry($entry, $entry_data,'',true);

						        foreach ($all_manual_rp as $key_manual => $manual_rp) 
								{
									if($manual_rp->rp_id == $key_rp)
									{
										$ins_manual_rp['sir_id'] = $sir_id;
										$ins_manual_rp['agent_id'] = $manual_rp->agent_id;
										$ins_manual_rp['rp_id'] = $rcvpayment_id;
										$ins_manual_rp['rp_date'] = $manual_rp->rp_date;
										$ins_manual_rp['created_at'] = $manual_rp->created_at;

										Tbl_manual_receive_payment::insert($ins_manual_rp);
										unset($all_manual_rp->$key_manual);
									}
								}

								unset($all_rp->$key_rp);
				        	}
				        }
					}
					unset($all_inv->$key);
    			}  

	   			/*FOR CM*/
    			foreach ($all_cm as $key_cm => $value_cm)
				{
    				$cm_item_info = [];
					$cm_customer_info["cm_customer_id"] = $value_cm->cm->cm_customer_id;
		            $cm_customer_info["cm_customer_email"] = $value_cm->cm->cm_customer_email;
		            $cm_customer_info["cm_date"] = datepicker_input($value_cm->cm->cm_date);
		            $cm_customer_info["cm_message"] = $value_cm->cm->cm_message;
		            $cm_customer_info["cm_memo"] = $value_cm->cm->cm_memo;
		            $cm_customer_info["cm_amount"] = $value_cm->cm->cm_amount;
		            $cm_customer_info["cm_type"] = $value_cm->cm->cm_type;
		            $cm_customer_info["cm_used_ref_name"] = $value_cm->cm->cm_used_ref_name;
		            $cm_customer_info["cm_used_ref_id"] = $value_cm->cm->cm_used_ref_id;

				    $_cm_items = $value_cm->cmline;
		            foreach ($_cm_items as $keys_cmline => $cmline) 
	                {
	                    if($cmline)
	                    {
	                    	$cm_item_info[$keys_cmline]['item_service_date']  = $cmline->cmline_service_date;
	                        $cm_item_info[$keys_cmline]['item_id']            = $cmline->cmline_item_id;
	                        $cm_item_info[$keys_cmline]['item_description']   = $cmline->cmline_description;
	                        $cm_item_info[$keys_cmline]['um']                 = $cmline->cmline_um;
	                        $cm_item_info[$keys_cmline]['quantity']           = $cmline->cmline_qty;
	                        $cm_item_info[$keys_cmline]['rate']               = $cmline->cmline_rate;
	                        $cm_item_info[$keys_cmline]['amount']             = $cmline->cmline_amount;
	                    }
	            	}

	            	$cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, 0, true);

	            	foreach ($all_manual_cm as $key_manual => $manual_cm) 
					{
						if($manual_cm->cm_id == $key_cm)
						{
							$ins_manual_cm['sir_id'] = $sir_id;
							$ins_manual_cm['cm_id'] = $cm_id;
							$ins_manual_cm['manual_cm_date'] = $manual_cm->manual_cm_date;
							$ins_manual_cm['created_at'] = $manual_cm->created_at;

							Tbl_manual_credit_memo::insert($ins_manual_cm);
							unset($all_manual_cm->$key_manual);
						}
					}

					foreach ($all_sir_inventory as $key_inventory => $value_inventory) 
					{
						if($value_inventory->sir_inventory_ref_name == 'credit_memo' && $value_inventory->sir_inventory_ref_id == $key_cm)
						{
							$ins['sir_item_id'] = $value_inventory->sir_item_id;
							$ins['inventory_sir_id'] = $value_inventory->inventory_sir_id;
							$ins['sir_inventory_ref_name'] = $value_inventory->sir_inventory_ref_name;
							$ins['sir_inventory_count'] = $value_inventory->sir_inventory_count;
							$ins['sir_inventory_ref_id'] = $cm_id;
							$ins['created_at'] = $value_inventory->created_at;
							$ins['is_bundled_item'] = $value_inventory->is_bundled_item;

							Tbl_sir_inventory::insert($ins);
							unset($all_sir_inventory->$key_inventory);
						}
					}

					foreach ($all_rp as $key_rp => $value_rp)
					{
						if($value_rp->rp->rp_ref_id == $key_cm && $value_rp->rp->rp_ref_name == 'credit_memo')
						{
							$insert["rp_shop_id"]           = $value_rp->rp->rp_shop_id;
					        $insert["rp_customer_id"]       = $value_rp->rp->rp_customer_id;
					        $insert["rp_ar_account"]        = $value_rp->rp->rp_ar_account or 0;
					        $insert["rp_date"]              = datepicker_input($value_rp->rp->rp_date);
					        $insert["rp_total_amount"]      = $value_rp->rp->rp_total_amount;
					        $insert["rp_payment_method"]    = $value_rp->rp->rp_payment_method;
					        $insert["rp_memo"]              = $value_rp->rp->rp_memo;
					        $insert["date_created"]         = $value_rp->rp->date_created;

				            $insert["rp_ref_name"]        = "";
				            $insert["rp_ref_id"]          = 0;
					        if($value_rp->rp->rp_ref_name)
					        {
					            $insert["rp_ref_name"]        = $value_rp->rp->rp_ref_name;
					            $insert["rp_ref_id"]          = $cm_id;
					        }

					        $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

					        $txn_line = $value_rp->rpline;
					        $cm_amt = 0;
					        foreach($txn_line as $key => $txn)
					        {
					            if($txn)
					            {
					                $insert_line["rpline_rp_id"]            = $rcvpayment_id;
					                $insert_line["rpline_reference_name"]   = $txn->rpline_reference_name;
					                $insert_line["rpline_reference_id"]     = $txn->rpline_reference_id;

					                $cm_amount = CreditMemo::cm_amount($txn->rpline_reference_id);
					                $cm_amt += $cm_amount;
					                $insert_line["rpline_amount"]           = $txn->rpline_amount + $cm_amount;

					                Tbl_receive_payment_line::insert($insert_line);

					                if($insert_line["rpline_reference_name"] == 'invoice')
					                {
					                    Invoice::updateAmountApplied($insert_line["rpline_reference_id"], true);
					                }
					            }
					        }
					        /* Transaction Journal */
					        $entry["reference_module"]      = "receive-payment";
					        $entry["reference_id"]          = $rcvpayment_id;
					        $entry["name_id"]               = $insert["rp_customer_id"];
					        $entry["total"]                 = $insert["rp_total_amount"] + $cm_amt;
					        $entry_data[0]['account_id']    = $insert["rp_ar_account"];
					        $entry_data[0]['vatable']       = 0;
					        $entry_data[0]['discount']      = 0;
					        $entry_data[0]['entry_amount']  = $insert["rp_total_amount"] + $cm_amt;
					        $inv_journal = Accounting::postJournalEntry($entry, $entry_data, '',true);

		  	 	          	foreach ($all_manual_rp as $key_manual => $manual_rp) 
							{
								if($manual_rp->rp_id == $key_rp)
								{
									$ins_manual_rp['sir_id'] = $sir_id;
									$ins_manual_rp['agent_id'] = $manual_rp->agent_id;
									$ins_manual_rp['rp_id'] = $rcvpayment_id;
									$ins_manual_rp['rp_date'] = $manual_rp->rp_date;
									$ins_manual_rp['created_at'] = $manual_rp->created_at;

									Tbl_manual_receive_payment::insert($ins_manual_rp);
									unset($all_manual_rp->$key_manual);
								}
							}
							
					        unset($all_rp->$key_rp);
						}
					}	 
					unset($all_cm->key_cm);
				}  		

				/*FOR RECEIVE PAYMENT*/
				foreach ($all_rp as $key_rp => $value_rp)
				{  
			        // dd(Request::input());
			        $insert["rp_shop_id"]           = $value_rp->rp->rp_shop_id;
			        $insert["rp_customer_id"]       = $value_rp->rp->rp_customer_id;
			        $insert["rp_ar_account"]        = $value_rp->rp->rp_ar_account or 0;
			        $insert["rp_date"]              = datepicker_input($value_rp->rp->rp_date);
			        $insert["rp_total_amount"]      = $value_rp->rp->rp_total_amount;
			        $insert["rp_payment_method"]    = $value_rp->rp->rp_payment_method;
			        $insert["rp_memo"]              = $value_rp->rp->rp_memo;
			        $insert["date_created"]         = $value_rp->rp->date_created;


		            $insert["rp_ref_name"]        = "";
		            $insert["rp_ref_id"]          = 0;
			        if($value_rp->rp->rp_ref_name)
			        {
			            $insert["rp_ref_name"]        = $value_rp->rp->rp_ref_name;
			            $insert["rp_ref_id"]          = $value_rp->rp->rp_ref_id;
			        }

			        $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

			        $txn_line = $value_rp->rpline;
			        $cm_amt = 0;
			        foreach($txn_line as $key => $txn)
			        {
			            if($txn)
			            {
			                $insert_line["rpline_rp_id"]            = $rcvpayment_id;
			                $insert_line["rpline_reference_name"]   = $txn->rpline_reference_name;
			                $insert_line["rpline_reference_id"]     = $txn->rpline_reference_id;

			                $cm_amount = CreditMemo::cm_amount($txn->rpline_reference_id);
			                $cm_amt += $cm_amount;
			                $insert_line["rpline_amount"]           = $txn->rpline_amount + $cm_amount;

			                Tbl_receive_payment_line::insert($insert_line);

			                if($insert_line["rpline_reference_name"] == 'invoice')
			                {
			                    Invoice::updateAmountApplied($insert_line["rpline_reference_id"], true);
			                }
			            }
			        }
			        /* Transaction Journal */
			        $entry["reference_module"]      = "receive-payment";
			        $entry["reference_id"]          = $rcvpayment_id;
			        $entry["name_id"]               = $insert["rp_customer_id"];
			        $entry["total"]                 = $insert["rp_total_amount"] + $cm_amt;
			        $entry_data[0]['account_id']    = $insert["rp_ar_account"];
			        $entry_data[0]['vatable']       = 0;
			        $entry_data[0]['discount']      = 0;
			        $entry_data[0]['entry_amount']  = $insert["rp_total_amount"] + $cm_amt;
					$inv_journal = Accounting::postJournalEntry($entry, $entry_data,'',true);

			        foreach ($all_manual_rp as $key_manual => $manual_rp) 
					{
						if($manual_rp->rp_id == $key_rp)
						{
							$ins_manual_rp['sir_id'] = $sir_id;
							$ins_manual_rp['agent_id'] = $manual_rp->agent_id;
							$ins_manual_rp['rp_id'] = $rcvpayment_id;
							$ins_manual_rp['rp_date'] = $manual_rp->rp_date;
							$ins_manual_rp['created_at'] = $manual_rp->created_at;

							Tbl_manual_receive_payment::insert($ins_manual_rp);
							unset($all_manual_rp->$key_manual);
						}
					}
				}

				if($sync_type == 'close')
				{
					Purchasing_inventory_system::close_sir($sir_id);	
				}
				else if($sync_type == 'reload')
				{
					$update['reload_sir'] = 1;
					Tbl_sir::where('sir_id',$sir_id)->update($update);
				}
				else if($sync_type == 'reject')
				{
					$update['lof_status'] = 3;
					$update['rejection_reason'] = $sir_data->rejection_reason;
					Tbl_sir::where('sir_id',$sir_id)->update($update);
				}
				if($agent_data)
				{
					$agent_update['email'] = $agent_data->email;
					$agent_update['username'] = $agent_data->username;
					$agent_update['password'] = Crypt::encrypt($agent_data->password);

					Tbl_employee::where('employee_id',$agent_data->employee_id)->update($agent_update);
				}
				if($all_customer)
				{
					foreach ($all_customer as $key_customer => $value_customer) 
					{
						$ins_customer['shop_id'] = $value_customer->shop_id;
						$ins_customer['country_id'] = $value_customer->country_id;
						$ins_customer['title_name'] = $value_customer->title_name;
						$ins_customer['first_name'] = $value_customer->first_name;
						$ins_customer['middle_name'] = $value_customer->shop_id;
						$ins_customer['last_name'] = $value_customer->shop_id;
						$ins_customer['suffix_name'] = $value_customer->shop_id;
						$ins_customer['email'] = $value_customer->shop_id;
						$ins_customer['company'] = $value_customer->shop_id;
						$ins_customer['approved'] = $value_customer->approved;

						$new_customer_id = Tbl_customer::insertGetId($ins_customer);

						foreach($all_customer_address as $key_address => $value_address) 
						{
							foreach ($value_address as $key_add => $value_add) 
							{
								foreach ($value_add as $key_adds => $value_adds) 
								{
									if($value_adds->customer_id == $value_customer->customer_id)
									{
										$ins_add['customer_id'] = $new_customer_id;
										$ins_add['country_id'] = $value_adds->country_id;
										$ins_add['customer_state'] = $value_adds->customer_state;
										$ins_add['customer_city'] = $value_adds->customer_city;
										$ins_add['customer_zipcode'] = $value_adds->customer_zipcode;
										$ins_add['customer_street'] = $value_adds->customer_street;
										$ins_add['created_at'] = $value_adds->created_at;
										$ins_add['updated_at'] = $value_adds->updated_at;
										$ins_add['purpose'] = $value_adds->purpose;

										Tbl_customer_address::insert($ins_add);
									}
								}
							}
						}

						$ins_other_info['customer_id'] = $new_customer_id;
						$ins_other_info['customer_phone'] = $value_customer->customer_phone != null ? $value_customer->customer_phone : '';
						$ins_other_info['customer_mobile'] =$value_customer->customer_mobile != null ? $value_customer->customer_mobile : '';
						$ins_other_info['customer_fax'] =$value_customer->customer_fax != null ? $value_customer->customer_fax : '';

						Tbl_customer_other_info::insert($ins_other_info);
					}
				}
				
				return "success";	      
    		}
    	}
    }

}