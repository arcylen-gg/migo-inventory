<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use App\Models\Tbl_voucher;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_membership_code;
use Crypt;
use Redirect;
use DB;
use Request;
use View;
use App\Globals\abs\AbsMain;
use App\Models\Tbl_mlm_gc;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Request2;
use App\Models\Tbl_tour_wallet;
use App\Models\Tbl_vmoney_wallet_logs;
use App\Globals\Mlm_slot_log;
use Carbon\Carbon;
use App\Globals\Dragonpay2\RequestForm;
class MlmWalletVMoneyController extends Mlm
{
    public function index()
    {
        if (Self::$slot_id) 
        {
            $data["_logs"] = DB::table("tbl_vmoney_wallet_logs")->where("customer_id", Self::$customer_id)->get();
            $data["wallet"] = Mlm_slot_log::get_sum_wallet(Self::$slot_id);
            $data["minimum"] = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value : 0;
            $data["percent"] = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value : 0;
            $data["fixed"] = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value : 0;
            
            return view('mlm.vmoney.index', $data);
        }
        else
        {
            dd("Slot not found");
        }
    }
    
    public function transfer()
    {
        /* Fee */
        $current_wallet = Request::input("wallet_amount");
        $fixed = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value : 0;
        $percent = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value : 0;
        $percent_value = ($percent / 100) * $current_wallet;
        $convenience_fee = $fixed + $percent_value; 
        $total_fee = $current_wallet + $convenience_fee;
        
        $slot = DB::table("tbl_mlm_slot")->where("slot_id", Self::$slot_id)->first();
        $wallet = Mlm_slot_log::get_sum_wallet($slot->slot_id);
        $minimum_encashment = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value : 0;
        
        if (isset($slot) && $slot) 
        {
            if ($minimum_encashment <= Request::input("wallet_amount")) 
            {
                if ($wallet > Request::input('wallet_amount')) 
                {
                    if (Request::input('vmoney_email')) 
                    {
                        if(Request::input('wallet_amount'))
                        {   
                            /* API */
                            $post = 'mxtransfer.svc';
                            $environment = isset(DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_environment")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", Self::$shop_id)->where("settings_key", "vmoney_environment")->first()->settings_value : 0;

                            /* Sandbox */
                            if ($environment == 0) 
                            {
                                $pass["apiKey"] = 'Vqzs90pKLb6iwsGQhnRS'; // Vendor API Key issued by VMoney
                                $pass["merchantId"] = 'M239658948226'; // Merchant ID registered within VMoney
                                /* Set URL Sandbox or Live */
                                $url = "http://test.vmoney.com/gtcvbankmerchant/";
                            }
                            /* Production */
                            else
                            {
                                $pass["apiKey"] = 'z9Gy1dBbnyj9cxMqXSKF'; // Vendor API Key issued by VMoney
                                $pass["merchantId"] = 'M132582139240'; // Merchant ID registered within VMoney
                                /* Set URL Sandbox or Live */
                                $url = "https://philtechglobalinc.vmoney.com/gtcvbankmerchant/";
                            }

                            $pass["recipient"] = Request::input("vmoney_email"); // Recipient's email address
                            $pass["merchantRef"] = Self::$customer_id . time(); // Merchant reference number
                            $pass["amount"] = Request::input("wallet_amount"); // Amount of the transaction
                            $pass["currency"] = 'PHP'; // Currency being transferred (ie PHP)
                            $pass["message"] = 'Philtech VMoney Wallet Transfer'; // Memo or notes for transaction

                            $post_params = $url . $post . "?" . http_build_query($pass);

                            try 
                            {
                                $client = new Client();
                                $response = $client->post($post_params, $pass);
                                $stream = $response->getBody();
                                $contents = $stream->getContents(); // returns all the contents
                                $contents = $stream->getContents(); // empty string
                                $stream->rewind(); // Seek to the beginning
                                $contents = $stream->getContents(); // returns all the contents
                                $data_decoded = json_decode($contents);

                                /* Result */
                                if ($data_decoded->resultCode == "000") 
                                {   
                                    $data_a['status'] = "success";
                                    $logs["status"] = 1;

                                    $arry_log['wallet_log_slot'] = $slot->slot_id;
                                    $arry_log['shop_id'] = $slot->shop_id;
                                    $arry_log['wallet_log_slot_sponsor'] = $slot->slot_id;
                                    $arry_log['wallet_log_details'] = 'You have transferred ' . $current_wallet . ' To your E-Money. ' . $total_fee . ' is deducted to your wallet including tax and convenience fee.';
                                    $arry_log['wallet_log_amount'] = -($total_fee);
                                    $arry_log['wallet_log_plan'] = "E_MONEY";
                                    $arry_log['wallet_log_status'] = "released";   
                                    $arry_log['wallet_log_claimbale_on'] = Carbon::now();

                                    Mlm_slot_log::slot_array($arry_log);
                                }
                                else
                                {
                                    $data_a['status'] = "error";
                                    $logs["status"] = 0;
                                }
                                
                                $data_a['message'] = $data_decoded->resultMsg;

                                $logs["vmoney_wallet_logs_date"] = Carbon::now();
                                $logs["vmoney_wallet_logs_email"] = Request::input("vmoney_email");
                                $logs["vmoney_wallet_logs_amount"] = $current_wallet;
                                $logs["customer_id"] = Self::$customer_id;
                                $logs["txnId"] = isset($data_decoded->txnId) ? $data_decoded->txnId : "None";
                                $logs["merchantRef"] = isset($data_decoded->merchantRef) ? $data_decoded->merchantRef : "None";
                                $logs["message"] = isset($data_decoded->resultMsg) ? $data_decoded->resultMsg : "None";
                                $logs["fee"] = $fixed;
                                $logs["tax"] = $percent_value;
                                Tbl_vmoney_wallet_logs::insert($logs);
                            } 
                            catch (\Exception $e) 
                            {
                                $data_a['status'] = "error";
                                $data_a['message'] = 'Caught exception: ' .  $e->getMessage();    
                            }
                        }
                        else
                        {
                            $data_a['status'] = "error";
                            $data_a['message'] = "Wallet Amount is required";  
                        }
                    }
                    else
                    {
                        $data_a['status'] = "error";
                        $data_a['message'] = "Email Recipient is required";   
                    }
                }
                else
                {
                    $data_a['status'] = "error";
                    $data_a['message'] = "Not enough wallet";   
                }
            }
            else
            {
                $data_a['status'] = "error";
                $data_a['message'] = "The minimum_encashment is PHP. " . number_format($minimum_encashment, 2);   
            }
        }
        else
        {
            $data_a['status'] = "error";
            $data_a['message'] = "No slot";   
        }
    
        return Redirect::to("/mlm/wallet/vmoney")->with($data_a["status"], $data_a["message"]);
    }
}