<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;
use App\Models\Tbl_customer;
use App\Models\Tbl_shop;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_mlm_encashment_settings;

use App\Globals\Mlm_member;
use App\Globals\Settings;
class Mlm extends Controller
{
    public static $customer_id;
    public static $customer_info;
    public static $shop_id;
    public static $shop_info;
    public static $slot_now;
    public static $slot_id;
    public static $discount_card_log_id;
    public static $discount_card_log;
    
    public function __construct()
    {	
        $this->middleware(function ($request, $next)
        {
            Settings::set_mail_setting(Self::$shop_id);

            if(Session::get('mlm_member') != null)
            {
                $session = Session::get('mlm_member');
                Self::$customer_id = $session['customer_info']->customer_id;
                Self::$customer_info = $session['customer_info'];

                Self::$shop_id = $session['shop_info']->shop_id;
                Self::$shop_info = $this->get_new_session_shop(Self::$shop_id);

                if($session['slot_now'])
                {
                    Self::$slot_id = $session['slot_now']->slot_id;
                    Self::$slot_now = $session['slot_now'];

                    $check_slot = Tbl_mlm_slot::where("slot_id",Self::$slot_id)->first();
                    if($check_slot)
                    {
                        if($check_slot->slot_owner != Self::$customer_id)
                        {
                            $count_all_slot = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)
                            ->membershipcode()->count();
                            if($count_all_slot >= 1)
                            {
                                $count_all_slot = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)
                                ->membershipcode()->first();
                                $new_slot_id = $count_all_slot->slot_id;
                                Mlm_member::add_to_session_edit(Self::$shop_id, Self::$customer_id, $new_slot_id);
                            }
                            Self::$slot_id = null;
                            Self::$slot_now = null;
                        }
                    }
                }
                else
                {
                    $count_all_slot = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->membershipcode()->count();
                    if($count_all_slot >= 1)
                    {
                        $count_all_slot = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->membershipcode()->first();
                        $new_slot_id = $count_all_slot->slot_id;
                        Mlm_member::add_to_session_edit(Self::$shop_id, Self::$customer_id, $new_slot_id);
                    }
                    Self::$slot_id = null;
                    Self::$slot_now = null;
                }
                if(isset($session['discount_card']))
                {
                    Self::$discount_card_log_id = null;
                    Self::$discount_card_log = null;
                }
                else
                {
                    Self::$discount_card_log_id = null;
                    Self::$discount_card_log = null;
                }

                $all_slot = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)
                ->membershipcode()
                ->membership()
                ->take(10)
                ->get();

                $plan_settings = Tbl_mlm_plan::where('shop_id', Self::$shop_id)
                ->where('marketing_plan_enable', 1)
                ->where('marketing_plan_trigger', 'Slot Creation')
                ->get();

                $plan_settings_repurchase = Tbl_mlm_plan::where('shop_id', Self::$shop_id)
                ->where('marketing_plan_enable', 1)
                ->where('marketing_plan_trigger', 'Product Repurchase')
                ->where('marketing_plan_code', '!=', 'DISCOUNT_CARD_REPURCHASE')
                ->get();  

                $notification_s = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', Self::$slot_id)
                ->orderBy('wallet_log_notified', 'ASC')
                ->orderBy('wallet_log_id', 'DESC')
                ->sponsorslot()
                ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                ->where('wallet_log_notified', 0)
                ->take(10)
                ->get();

                $noti_count = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', Self::$slot_id)
                ->where('wallet_log_notified', 0)->count();

                $content = DB::table('tbl_content')->where('shop_id', Self::$shop_id)->get();
                $content_a = [];
                foreach($content as $c => $value)
                {
                    $content_a[$value->key] = $value->value;
                }
                $customer = Tbl_customer::where('customer_id', Self::$customer_id)->first();
                $customer->profile != null ? $profile = $customer->profile :  $profile = '/assets/mlm/default-pic.png';

                $check_owned_slot = Tbl_mlm_slot::where("slot_owner",Self::$customer_id)->count();
                if($check_owned_slot == 0 && Request::segment(2) != "login" && Request::segment(2) != "process_order_queue" && Self::$shop_info->member_layout == "myphone")
                {
                    return Redirect::to("mlm/process_order_queue")->send();
                }

                $this->seed();
                
                View::share("profile", $profile);
                View::share("content", $content_a);
                View::share("complan", $plan_settings);
                View::share("complan_repurchase", $plan_settings_repurchase);
                View::share("customer_info", Self::$customer_info);
                View::share("shop_info", Self::$shop_info);
                View::share("slot_now", Self::$slot_now);
                View::share("slot", $all_slot);
                View::share("notification", $notification_s);
                View::share("notification_count", $noti_count);
                View::share('discount_card_log', Self::$discount_card_log );
            }
            else
            {
                return Redirect::to("/mlm/login")->send();
            }

            return $next($request);
        });
    }
    public static function show_maintenance()
    {
        return view('mlm.maintenance');
    }
    public function get_new_session_shop($shop_id)
    {
        $shop = Tbl_shop::where('shop_id', $shop_id)->first();

        return $shop;
    }
    public static function no_access()
    {

    }
    public static function changeslot()
    {
        $customer_id = Self::$customer_id;
        $new_slot_id = Request::input('slot_id'); 
        $count = Tbl_mlm_slot::where('slot_owner', $customer_id)->where('slot_id', $new_slot_id)->count();   
        if($count == 0)
        {
            $data['response_status'] = "warning";
            $data['message'] = "You don't own the slot";
            return Redirect::back();
        }
        else
        {
            Mlm_member::add_to_session_edit(Self::$shop_id, $customer_id, $new_slot_id);
            $data['response_status'] = "success";
            $data['message'] = "Slot Changed";
            return Redirect::to('/mlm');
        }
        return json_encode($data);
    }
    public static function get_customer_info($customer_id)
    {
        $data = [];
        return Mlm_member::get_customer_info($customer_id);
    }
    public static function show_no_access()
    {
        return view('mlm.no_access');
    }
    public static function seed()
    {
        // Session::flash('success', "Membership Saved");
        $customer_id = Self::$customer_id;
        $shop_id = Self::$shop_id;
        $encashment_settings = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();
        $count = DB::table('tbl_customer_payout')->where('customer_id', $customer_id)->count();
        if($count == 0)
        {
            if($encashment_settings)
            {
                $insert['shop_id'] = $shop_id;
                $insert['customer_id'] = $customer_id;
                $insert['customer_payout_type'] = $encashment_settings->enchasment_settings_type;
                $insert['customer_payout_name_on_cheque'] = name_format_from_customer_info(Self::$customer_info);
                $insert['encashment_bank_deposit_id'] = '';
                $insert['customer_payout_bank_branch'] = '';
                $insert['customer_payout_bank_account_number'] = '';
                $insert['customer_payout_bank_account_name'] = name_format_from_customer_info(Self::$customer_info);

                DB::table('tbl_customer_payout')->insert($insert);
            }
            
        }
        else
        {
            $customer_payout  = DB::table('tbl_customer_payout')->where('customer_id', $customer_id)->first();
            if($encashment_settings->enchasment_settings_type == 0)
            {
                if($encashment_settings->enchasment_settings_cheque_edit == 0)
                {
                    //encashment_bank_deposit_id
                    
                    if(Self::$slot_id != null)
                    {
                        if($customer_payout->encashment_bank_deposit_id == 0)
                        {
                            Session::flash('warning', "Please set your encashment settings at the profile tab.");
                        }
                        else
                        {
                            $bank_details = DB::table('tbl_encashment_bank_deposit')->where('encashment_bank_deposit_id', $customer_payout->encashment_bank_deposit_id)->where('encashment_bank_deposit_archive', 0)->count();
                            if($bank_details >= 1)
                            {

                            }
                            else
                            {
                                Session::flash('warning', "Please set your encashment settings at the profile tab.");
                            }
                        }
                    }
                    
                    $update['customer_payout_bank_account_name'] = name_format_from_customer_info(Self::$customer_info);
                    DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->update($update);
                }
            }
            else if($encashment_settings->enchasment_settings_type == 1)
            {
                //cheque
                // dd(1);
                if($encashment_settings->enchasment_settings_cheque_edit == 0)
                {
                    $update['customer_payout_name_on_cheque'] = name_format_from_customer_info(Self::$customer_info);
                    DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->update($update);
                }
            }
        }
    }

}