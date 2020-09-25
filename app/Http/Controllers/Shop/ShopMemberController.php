<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Request as Request2;
use Crypt;
use Redirect;
use View;
use Input;
use File;
use Image;
use Mail;
use DB;
use URL;
use Session;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Globals\Payment;
use App\Globals\ShopEvent;
use App\Globals\CustomerBeneficiary;
use App\Globals\Customer;
use App\Globals\MemberSlotGenealogy;
use App\Rules\Uniqueonshop;
use App\Globals\MLM2;
use App\Globals\FacebookGlobals;
use App\Globals\SocialNetwork;
use App\Globals\GoogleGlobals;
use App\Globals\EmailContent;
use App\Globals\Mail_global;
use App\Globals\Transaction;
use App\Globals\Warehouse2;
use App\Globals\Ecom_Product;
use App\Globals\abs\AbsMain;
use App\Models\Tbl_customer;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_recaptcha_setting;
use App\Models\Tbl_recaptcha_pool_amount;
use App\Models\Tbl_item_token;
use App\Models\Tbl_item_token_log;
use App\Models\Tbl_token_list;

use App\Models\Tbl_image;
// use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_item_redeemable_report;

//mark
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_wallet_log_refill;
use App\Models\Tbl_mlm_slot_wallet_log_refill_settings;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_email_template;
use App\Models\Tbl_transaction_list;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_item;
use App\Models\Tbl_mlm_slot_bank;
use App\Models\Tbl_mlm_slot_coinsph;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_slot_money_remittance;
use App\Models\Tbl_country;
use App\Models\Tbl_locale;
use App\Models\Tbl_vmoney_wallet_logs;
use App\Models\Tbl_mlm_encashment_settings;
use App\Models\Tbl_payout_bank;
use App\Models\Tbl_online_pymnt_api;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_membership;
use App\Models\Tbl_vmoney_settings;
use App\Models\Tbl_slot_notification;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_tour_wallet_slot;
use App\Models\Tbl_tour_wallet;

use App\Models\Tbl_press_release_recipient;
use App\Models\Tbl_pressiq_press_releases;
use App\Models\Tbl_pressiq_user;
use App\Models\Tbl_pressiq_demo;

use App\Models\Tbl_item_redeemable_points;
use App\Models\Tbl_item_redeemable_request;

use App\Globals\Currency;
use App\Globals\Cart2;
use App\Globals\Item;
use App\Globals\Mlm_tree;
use Jenssegers\Agent\Agent;
use App\Globals\Mlm_slot_log;
use Validator;
use Google_Client; 
use Google_Service_Drive;
use Google_Service_Plus;
use stdClass;

// PAYMAYA
use App\Globals\PayMaya\PayMayaSDK;
use App\Globals\PayMaya\API\Checkout;
use App\Globals\PayMaya\API\Customization;
use App\Globals\PayMaya\API\Webhook;
use App\Globals\PayMaya\Core\CheckoutAPIManager;
use App\Globals\PayMaya\Checkout\User;
use App\Globals\PayMaya\Model\Checkout\ItemAmountDetails;
use App\Globals\PayMaya\Model\Checkout\ItemAmount;
use App\Globals\PayMaya\Core\Constants;


use App\Models\Tbl_item;
use App\Tbl_item_redeemable;
//for image upload
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class ShopMemberController extends Shop
{
    public function getIndex()
    {
        $data["page"] = "Dashboard";
        $data['shop_id'] = $this->shop_info->shop_id;
        $data["mode"] = session("get_success_mode");
        $data["zero_currency"] = Currency::format(0);
        session()->forget("get_success_mode");
        
        $data["item_kit_id"] = Item::get_first_assembled_kit($this->shop_info->shop_id);
        $data["item_kit"]    = Item::get_all_assembled_kit($this->shop_info->shop_id);
        if(Self::$customer_info)
        {
            $data["customer_summary"]   = MLM2::customer_income_summary($this->shop_info->shop_id, Self::$customer_info->customer_id);
            $data["wallet"]             = $data["customer_summary"]["_wallet"];
            $data["points"]             = $data["customer_summary"]["_points"];
            $data["_wallet_plan"]       = $data["customer_summary"]["_wallet_plan"];
            $data["_point_plan"]        = $data["customer_summary"]["_point_plan"];
            $data["_slot"]              = $_slot = MLM2::customer_slots($this->shop_info->shop_id, Self::$customer_info->customer_id);
            $data["_recent_rewards"]    = MLM2::customer_rewards($this->shop_info->shop_id, Self::$customer_info->customer_id, 5);
            $data["_direct"]            = MLM2::customer_direct($this->shop_info->shop_id, Self::$customer_info->customer_id, 0,5);
            $data['allow_multiple_slot'] = Self::$customer_info->allow_multiple_slot;
            $data['mlm_pin'] = '';
            $data['mlm_activation'] = '';            
            $data["first_slot"]         = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->membership()->first();
           
            if($this->shop_info->shop_theme == 'philtech')
            {
                $data["travel_and_tours"] = false;

                foreach($_slot as $slot)
                {
                    // 4 = V.I.P Platinum && 65 = V.I.P Platinum (FS)
                    if($slot->slot_membership == 4 || $slot->slot_membership == 65 || $slot->slot_membership == 3)
                    {
                        $data["travel_and_tours"] = true;
                        if($slot->slot_membership == 3) 
                        {
                            $data['link'] = 'http://tour.philtechglobalinc.com/auth/login';
                        }
                        else
                        {
                            $data['link'] = 'http://202.54.157.7/PhilTechInc/BKWLTOlogin.aspx';
                        }
                    }
                }
            }

            if(Self::$customer_info->customer_id != 12) //SIR ARNOLD
            {
                if(MLM2::check_unused_code($this->shop_info->shop_id, Self::$customer_info->customer_id) && $this->mlm_member == false)
                {
                    $data['check_unused_code'] = MLM2::check_unused_code($this->shop_info->shop_id, Self::$customer_info->customer_id);
                    $data['mlm_pin'] = MLM2::get_code($data['check_unused_code'])['mlm_pin'];
                    $data['mlm_activation'] = MLM2::get_code($data['check_unused_code'])['mlm_activation'];
                    
                    $store["temp_pin"] = $data['mlm_pin'];
                    $store["temp_activation"] = $data['mlm_activation'];
                    $store["online_transaction"] = true;
                    session($store);
                }
            }
   
            $data["not_placed_slot"] = new stdClass();
            $data["not_placed_slot"]->slot_id = 0;
            $data["not_placed_slot"]->slot_no = 0;
            $data["company_head_id"]  = Tbl_mlm_slot::where("shop_id",$this->shop_info->shop_id)->orderBy("slot_id","ASC")->first();
   
            $data["_unplaced"] = MLM2::unplaced_slots($this->shop_info->shop_id, Self::$customer_info->customer_id);
            if(isset($data["_unplaced"][0]))
            {
                if($data["_unplaced"][0]->slot_placement == 0)
                {
                    $data["not_placed_yet"] = true;
                    $data["not_placed_slot"] = $data["_unplaced"][0];
                }
            }
            $data['_event'] = ShopEvent::get($this->shop_info->shop_id ,0 ,3 ,Carbon::now(), Self::$customer_info->customer_id, ['all','members']);
            $data['_notification'] = Tbl_slot_notification::where('shop_id',$this->shop_info->shop_id)->where('customer_id',Self::$customer_info->customer_id)->where('has_been_seen',0)->first();

            if($this->shop_info->shop_theme == 'philtech')
            {
                if(MLM2::is_privilage_card_holder($this->shop_info->shop_id, Self::$customer_info->customer_id))
                {
                    return Self::load_view_for_members('member.privilage_card_holder_dashboard',$data);
                }                   
            }
            //total points
            $slots = Tbl_mlm_slot::where('slot_owner',Self::$customer_info->customer_id)->get();
            $total_points = 0;
            foreach($slots as $s)
            {
                $total_points += $this->redeem_points_sum($s->slot_id);
            }
            $data['total_points'] = currency("",$total_points)." POINT(S)";

            // for shift only
            if($this->shop_info->shop_id == 54)
            {
                $slot_id = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id);
                $data['reward_point_redemption'] = Tbl_mlm_slot_points_log::Slot()->where('tbl_mlm_slot.slot_owner',Self::$customer_info->customer_id)->where("points_log_complan","PURCHASE_GC")->sum('points_log_points');
            }
            // dd($slot_id." ; ".$data['reward_point_redemption']);
            // dd($data['wallet']);
            $data["krops_gc"] = Tbl_mlm_slot_points_log::Slot()->where('points_log_type',"GC")->where('slot_owner',Self::$customer_info->customer_id)->sum('points_log_points');
            $token_list_titles = Tbl_token_list::get();
            $token_titles = array();
            $token_amounts = array();
            foreach($token_list_titles as $title)
            {
                array_push($token_titles, $title->token_name);
                $total_token = Tbl_item_token_log::where('token_log_slot_owner',Self::$customer_info->customer_id)->where('token_id',$title->token_id)->sum('amount');
                array_push($token_amounts, $total_token);
            }
            $data['token_titles']   = $token_titles;
            $data['token_amounts']  = $token_amounts;
            
            return Self::load_view_for_members("member.dashboard", $data);
        }
        else
        {
            return Redirect::to('/members/login');
        }

    }
    public function getDirectReferrals()
    {
        if (Self::$customer_info) 
        {
            $data["_direct"]            = MLM2::customer_direct($this->shop_info->shop_id, Self::$customer_info->customer_id, 0,5);
            return view('member.newest_direct_referrals',$data);
        }
        else
        {
            return Redirect::to('/members/login');
        }
    }
    public function getKit()
    {
        $data["item_kit"] = Item::get_all_assembled_kit_v2($this->shop_info->shop_id);

        return view("member.kit_modal", $data);
    }
    public function getReturnpolicy()
    {
        $data = [];
        return Self::load_view_for_members('member.return_policy', $data);
    }
    public function getDirect()
    {
        $data["_direct"] = MLM2::customer_direct($this->shop_info->shop_id, Self::$customer_info->customer_id);
        return Self::load_view_for_members('member.direct', $data);
    }
    public function getProducts()
    {
        $data = [];
        return Self::load_view_for_members('member.products', $data);
    }
    public function getCertificate()
    {
        $data = [];
        return Self::load_view_for_members('member.certificate', $data);
    }
    public function getVideos()
    {
        $data = [];
        return Self::load_view_for_members('member.videos', $data);
    }
    public function getEbooks()
    {
        $data = [];
        return Self::load_view_for_members('member.ebooks', $data);
    }

    /*--------------------------------------------------------------------------Press Release*/
    // public function press_email()
    // {
    //     $data["pr"]='$pr';
    //     $data["page"] = "Email";
    //     return view("emails.press_email", $data);
    // }
    public function logout()
    {
        Session::flush();

       
        return Redirect::to("/");
    }
    public function pressuser()
    {
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                $data["page"] = "Press Release";
                return view("press_user.member", $data);
           }
           else
           {
                return Redirect::to("/pressadmin");
           }
        }
        else
        {
            return Redirect::to("/"); 
        }   
    }


     public function pressuser_dashboard()
    {
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                $data['pr']     = DB::table('tbl_pressiq_press_releases')
                                    ->where('pr_from', session('user_email'))
                                    ->orderByRaw('pr_date_sent DESC')
                                    ->get();
                $data["page"] = "Press Release - Dashboard";
                return view("press_user.press_user_dashboard", $data);
           }
           else
           {
                return Redirect::to("/pressadmin/dashboard");
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }
    public function pressuser_delete_draft($pid)
    {
        Tbl_pressiq_press_releases::where('pr_id',$pid)->delete();
        Session::forget('pr_edit');
        Session::flash('delete', "Draft Already Deleted!");
        return  redirect::back();
    } 

    public function pressuser_edit_draft($pid)
    {
        Session::put('pr_edit',$pid);
        return Redirect::to("/pressuser/pressrelease");
    }

    public function pressuser_pressrelease()
    {  
        $data['_country']              = Tbl_press_release_recipient::distinct()->get(['country']);
        $data['_industry_type']        = Tbl_press_release_recipient::distinct()->get(['industry_type']);
        $data['_title_of_journalist']  = Tbl_press_release_recipient::distinct()->get(['title_of_journalist']);
        $data['_media_type']           = Tbl_press_release_recipient::distinct()->get(['media_type']);

        $data['drafts']     = DB::table('tbl_pressiq_press_releases')
                            ->where('pr_from', session('user_email'))
                            ->where('pr_status','draft')
                            ->orderByRaw('pr_date_sent DESC')
                            ->get();

        $data['edit']     = DB::table('tbl_pressiq_press_releases')
                            ->where('pr_id',session('pr_edit'))
                            ->get();
        
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           { 
                $data["page"] = "Press Release - Press Release";
                return view("press_user.press_user_pressrelease", $data);
           }
           else
           {
                return Redirect::to("/pressadmin/dashboard");
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }
    
    public function send_pr()
    {
        $pr_info["pr_type"]         =request('pr_type');
        $pr_info["pr_headline"]     =request('pr_headline');
        $pr_info["pr_content"]      =request('pr_content');
        $pr_info["pr_boiler_content"]=request('pr_boiler_content');
        $pr_info["pr_from"]         =session('user_email');
        $pr_info["pr_to"]           =request('pr_to');
        $pr_info["pr_status"]       ="Sent";
        $pr_info["pr_date_sent"]    =Carbon::now();
        $pr_info["pr_sender_name"]  =session('user_first_name').' '.session('user_last_name');
        $pr_info["pr_receiver_name"]=request('pr_receiver_name');
        $pr_info["pr_co_name"]      =session('user_company_name');
        $pr_info["pr_co_img"]       =session('user_company_image');
        
        //dd(session('user_company_image'));
        $pr_rules["pr_type"]       =['required'];
        $pr_rules["pr_headline"]   =['required'];
        $pr_rules["pr_content"]    =['required'];
        $pr_rules["pr_boiler_content"] =['required'];
        $pr_rules["pr_to"]         =['required'];
        
        $validator = Validator::make($pr_info, $pr_rules);

        if ($validator->fails()) 
        {
            return Redirect::to("/pressuser/pressrelease")->with('message', $validator->errors()->first())->withInput();
        }
        else
        {    
            $this->send($pr_info);

            if( count(Mail::failures()) > 0 ) 
                {

               Session::flash('message', "Error in sending the release!");

               foreach(Mail::failures as $email_address) 
                {
                   echo " - $email_address <br />";
                }
                return Redirect::back()->with('message');

            }
            else 
            {
                Session::flash('message', "Release Successfully Sent!");

                if(Session::has('pr_edit'))
                {
                    $date=Carbon::now();
                    DB::table('tbl_pressiq_press_releases')
                        ->where('pr_id', session('pr_edit'))
                        ->update([
                            'pr_type'         =>request('pr_type'),
                            'pr_headline'     =>request('pr_headline'),
                            'pr_content'      =>request('pr_content'),
                            'pr_boiler_content'=>request('pr_boiler_content'),
                            'pr_from'         =>session('user_email'),
                            'pr_to'           =>request('pr_to'),
                            'pr_status'       =>"sent",
                            'pr_date_sent'    =>$date,
                            'pr_sender_name'  =>session('user_first_name').' '.session('user_last_name'),
                            'pr_receiver_name'=>request('pr_receiver_name'),
                            'pr_co_name'      =>session('user_company_name'),
                            'pr_co_img'       =>session('user_company_image'),


                            ]);
                    Session::forget('pr_edit');
                     Session::flash('email_sent', 'Email Successfully Sent!');
                    return Redirect::to("/pressuser/mypressrelease");

                }
                else
                {
                    $pr_id = tbl_pressiq_press_releases::insertGetId($pr_info);
                     Session::flash('email_sent', 'Press Release Successfully Sent!');
                    return Redirect::to("/pressuser/mypressrelease");
                }
                
            }
            $data["page"] = "Press Release - Press Release";
            return view("press_user.press_user_pressrelease", $data);
        }
    }
    public function pressuser_pressrelease_recipient_search(Request $request)
    {  
     
      $search_key = $request->search_key;
      $data['_recipient'] = Tbl_press_release_recipient::where('name','like','%'.$search_key.'%')
                            ->Orwhere('company_name','like','%'.$search_key.'%')
                            ->Orwhere('position','like','%'.$search_key.'   %')
                            ->get();
      return view("press_user.search_recipient", $data);
    }

    public function send($pr_info)
    {
        $to  = explode(",", $pr_info['pr_to']);
        $pr_info["explode_email"] = explode("@", $pr_info['pr_from']);

        foreach ($to as $pr_info['pr_to']) 
        {
            Mail::send('emails.press_email',$pr_info, function($message) use ($pr_info)
            {
                $message->from($pr_info["explode_email"][0] . '@press-iq.com', $pr_info['pr_sender_name']);
                $message->to($pr_info['pr_to']);
                $message->subject($pr_info["pr_headline"]);
            });
        }
    }
   
    public function send_contact_us()
    {
        $contactus_info["contactus_first_name"]         =request('first_name');
        $contactus_info["contactus_last_name"]          =request('contactus_last_name');
        $contactus_info["contactus_phone_number"]       =request('contactus_phone_number');
        $contactus_info["contactus_subject"]            =request('contactus_subject');
        $contactus_info["contactus_email"]              =request('contactus_email');
        $contactus_info["contactus_message"]            =request('contactus_message');
        $contactus_info["contactus_to"]                 =request('contactus_to');

        $contactus_info["explode_email"] = explode("@", $contactus_info['contactus_email']);
        
        Mail::send('emails.Contact_us',$contactus_info, function($message) use ($contactus_info)
        {
            $message->from($contactus_info["explode_email"][0] . '@press-iq.com',$contactus_info['contactus_email']);
            $message->to("marketing@press-iq.com");  
            $message->subject($contactus_info['contactus_subject']);
           
        });
        Session::flash('message_concern', 'Message Successfully Sent!');
        return Redirect::back();
    }

    public function send_demo()     
    {
        $demo_info["demo_name"]             =request('name');
        $demo_info["demo_company"]          =request('company');
        $demo_info["demo_email"]            =request('email');
        $demo_info["demo_phone_number"]     =request('number');
        $demo_info["demo_message"]          =request('message');
    
        $demo_info["explode_email"] = explode("@", $demo_info['demo_email']);

        Mail::send('emails.demo_request',$demo_info, function($message) use ($demo_info)
        {
           $message->from($demo_info["explode_email"][0] . '@press-iq.com',$demo_info['demo_email']);
           $message->to("marketing@press-iq.com");  
           
        });
        Session::flash('Demo_message', 'Demo Request Successfully Sent!');
        return Redirect::back();  
    }

    public function press_release_save_as_draft(Request $request)
    {  
        $pr_info["pr_type"]         =$request->pr_type; 
        $pr_info["pr_headline"]     =$request->pr_headline;
        $pr_info["pr_content"]      =$request->pr_content;
        $pr_info["pr_boiler_content"]=$request->pr_boiler_content;
        $pr_info["pr_from"]         =session('user_email');
        $pr_info["pr_to"]           =$request->pr_to;
        $pr_info["pr_status"]       ="Draft";
        $pr_info["pr_date_sent"]    =Carbon::now();
        $pr_info["pr_sender_name"]  =session('user_first_name').' '.session('user_last_name');
        $pr_info["pr_receiver_name"]=request('pr_receiver_name');
        $pr_info["pr_co_name"]      =session('user_company_name');
        $pr_info["pr_co_img"]       =session('user_company_image');
        $pr_info["pr_type"]         =$request->pr_type;

        if(Session::has('pr_edit'))
        {
            $date=Carbon::now();
            DB::table('tbl_pressiq_press_releases')
                ->where('pr_id', session('pr_edit'))
                ->update([
                    'pr_type'         =>request('pr_type'),
                    'pr_headline'     =>request('pr_headline'),
                    'pr_content'      =>request('pr_content'),
                    'pr_boiler_content'=>request('pr_boiler_content'),
                    'pr_from'         =>session('user_email'),
                    'pr_to'           =>request('pr_to'),
                    'pr_status'       =>"draft",
                    'pr_date_sent'    =>$date,
                    'pr_sender_name'  =>session('user_first_name').' '.session('user_last_name'),
                    'pr_receiver_name'=>request('pr_receiver_name'),
                    'pr_co_name'      =>session('user_company_name'),
                    'pr_co_img'       =>session('user_company_image'),
                    ]);
            Session::forget('pr_edit');
        }
        else
        {
            $pr_id = tbl_pressiq_press_releases::insertGetId($pr_info);
        }
         
        $data["page"] = "Press Release - My Press Release";
        return redirect::to("/pressuser/drafts");
    }

    public function pressuser_my_pressrelease()
    {
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                $pr = DB::table('tbl_pressiq_press_releases')
                    ->where('pr_from', session('user_email'))
                    ->where('pr_status','sent')
                    ->orderByRaw('pr_date_sent DESC')
                    ->paginate(5);
                $data["page"] = "Press Release - My Press Release";
                $data["pr"]=$pr;
                return view("press_user.press_user_my_pressrelease",$data);
           }
           else
           {
                return Redirect::to("/pressadmin/pressreleases");
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }
    
    public function press_user_drafts()
    {
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                $data['drafts'] = DB::table('tbl_pressiq_press_releases')
                                ->where('pr_from', session('user_email'))
                                ->where('pr_status','Draft')
                                ->orderByRaw('pr_date_sent DESC')
                                ->get();
                $data["page"] = "Drafts";
                return view("press_user.press_user_drafts", $data);
            }
            else
           {
                return Redirect::to("/pressadmin/pressreleases");
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }
    public function pressuser_view($pid)
    {
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                $pr = DB::table('tbl_pressiq_press_releases')
                    ->where('pr_id', $pid)
                    ->where('pr_from', session('user_email'))
                    ->paginate(5);
                $data["page"] = "Press Release - View";
                $data["pr"]=$pr;

                $pr = DB::table('tbl_pressiq_press_releases')
                    ->where('pr_id','!=', $pid)
                    ->where('pr_from', session('user_email'))
                    ->orderByRaw('pr_date_sent DESC')
                    ->paginate(5);
                $data["page"] = "Press Release - View";
                $data["opr"]=$pr;

                return view("press_user.pressrelease_view", $data);
           }
           else
           {
                return Redirect::to("/pressadmin/pressreleases");
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }

    public function press_release_analytics()
    {

        if (Session::exists('user_email')) 
        {
           
            $explode_email = explode("@", Session::get('user_email'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mandrillapp.com/api/1.0/senders/info.json?key=UWTLQzFotM-rRUyOJqlvjw&address=" . $explode_email[0] . '@press-iq.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "postman-token: c2fb288c-3f82-02af-4779-e0f682f5f8a8"
                ) ,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err)
            {
                echo "cURL Error #:" . $err;
            }
            else
            {
                $share_analytics = json_decode($response);
                Session::put('share_analytics', $share_analytics);
                $data["page"] = "Analytics";
                return view("press_user.press_user_analytics",$data);
            }
        }
        else
        {
             return Redirect::to("/"); 
        }
    }

    public function press_release_analytics_campaign()
    {
         if (Session::exists('user_email')) 
         {
            $explode_email = explode("@", Session::get('user_email'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mandrillapp.com/api/1.0/messages/search.json?key=UWTLQzFotM-rRUyOJqlvjw&email:gmail.com=" . $explode_email[0] . '@press-iq.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "postman-token: c2fb288c-3f82-02af-4779-e0f682f5f8a8"
                ) ,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err)
            {
                echo "cURL Error #:" . $err;
            }
            else
            {
                
                $analytics_view = json_decode($response);
                $new_analytic_view = [];
                foreach ($analytics_view as $key => $value) 
                {
                    if ($value->sender == $explode_email[0] . '@press-iq.com') 
                    {
                        $new_analytic_view[$value->subject] = $value;
                    }
                }
                $analytics_view = $new_analytic_view;
                $data["analytics_view"] = $analytics_view;
                $data["page"] = "Campaign Detail";
                return view("press_user.press_user_analytics_view",$data);
            }
        }
         else
        {
           return Redirect::to("/"); 
        }
    }

    public function press_release_analytics_view_all()
    {
        if (Session::exists('user_email')) 
         {
            $explode_email = explode("@", Session::get('user_email'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mandrillapp.com/api/1.0/messages/search.json?key=UWTLQzFotM-rRUyOJqlvjw&email:gmail.com=" . $explode_email[0] . '@press-iq.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "postman-token: c2fb288c-3f82-02af-4779-e0f682f5f8a8"
                ) ,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err)
            {
                echo "cURL Error #:" . $err;
            }
            else
            {
                $analytics_view = json_decode($response);
                foreach ($analytics_view as $key => $value) 
                {
                    if ($value->sender != $explode_email[0] . '@press-iq.com') 
                    {
                        unset($analytics_view[$key]);
                    }

                    if ($value->subject != Crypt::decrypt(Request2::input("subject"))) 
                    {
                        unset($analytics_view[$key]);
                    }
                }

                $data["analytics_view"] = $analytics_view;
                $data["page"] = "Analytics Details";
                return view("press_user.press_user_analytics_view_all",$data); 
            }
        }
         else
        {
           return Redirect::to("/"); 
        }  
    }

    /* Tracking Press Release */
    public function press_release_track_open()
    {
        dd(header('Content-Type: image/gif'));
        //THIS RETURNS THE IMAGE
        header('Content-Type: image/gif');
        readfile(public_path() . '/email-tracker/tracking.gif');

        //THIS IS THE SCRIPT FOR THE ACTUAL TRACKING
        // $date = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        // $txt = $date.",". $_SERVER['REMOTE_ADDR'];
        // $myfile = file_put_contents('log.txt', $txt.PHP_EOL , FILE_APPEND);
        exit; 
    }

    public function pressuser_media_contacts()   
    {
        $data['user_media_contacts'] = Tbl_press_release_recipient::where('user_id',session('pr_user_id'))->get();

        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                $data["page"] = "Media Contacts";
                return view("press_user.press_user_media_contacts", $data);
            }
            else
           {
                $data["page"] = "Media Contacts";
                return view("press_user.press_user_media_contacts", $data);
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }  

    public function pressuser_media_contacts_add(Request $request)
    {
      $data["name"]                      = $request->name;
      $data["position"]                  = $request->position;
      $data["company_name"]              = $request->company_name;
      $data["country"]                   = $request->country;
      $data["research_email_address"]    = $request->contact_email;
      $data["website"]                   = $request->contact_website;
      $data["media_type"]                = $request->media_type;
      $data["industry_type"]             = $request->industry_type;
      $data["title_of_journalist"]       = $request->title_journalist;
      $data["description"]               = $request->description;
      $data["user_id"]                   = session('pr_user_id');
      Tbl_press_release_recipient::insert($data);
      Session::flash('success_user', 'Recipient Successfully Added!');
      return redirect::back();
    }

    public function pressuser_media_contacts_delete($id)
    {
      Tbl_press_release_recipient::where('recipient_id',$id)->delete();
       Session::flash('delete_user', 'Recipient Successfully Deleted!');
      return  redirect::back();
    }

     public function pressadmin()
    {
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                return Redirect::to("/pressuser");
           }
           else
           {                
                $data["page"] = "Press Release";
                return view("press_admin.admin", $data);
           } 
        }
        else
        {
            return Redirect::to("/"); 
        }
    }
     public function pressadmin_dashboard()
    {
        if (Session::exists('user_email')) 
         {
            $explode_email = explode("@", Session::get('user_email'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mandrillapp.com/api/1.0/messages/search.json?key=UWTLQzFotM-rRUyOJqlvjw&email:gmail.com=" . $explode_email[0] . '@press-iq.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "postman-token: c2fb288c-3f82-02af-4779-e0f682f5f8a8"
                ) ,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err)
            {
                echo "cURL Error #:" . $err;
            }
            else
            {
                $analytics_view = json_decode($response);
                $new_analytic_view = [];
                foreach ($analytics_view as $key => $value) 
                {
                    if ($value->sender != $explode_email[0] . '@press-iq.com') 
                    {
                        $new_analytic_view[$value->subject] = $value;
                    }
                }
                $analytics_view = $new_analytic_view;
                $data["analytics_view"] = $analytics_view;
                $data["page"] = "Press Release - Dashboard";
                return view("press_admin.press_admin_dashboard",$data);
            }
        }
         else
        {
           return Redirect::to("/"); 
        }
    }

     public function pressadmin_dashboard_view()   
    {
        if (Session::exists('user_email')) 
         {
            $explode_email = explode("@", Session::get('user_email'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mandrillapp.com/api/1.0/messages/search.json?key=UWTLQzFotM-rRUyOJqlvjw&email:gmail.com=" . $explode_email[0] . '@press-iq.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "postman-token: c2fb288c-3f82-02af-4779-e0f682f5f8a8"
                ) ,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err)
            {
                echo "cURL Error #:" . $err;
            }
            else
            {
                $analytics_view = json_decode($response);
                foreach ($analytics_view as $key => $value) 
                {
                    if ($value->subject != Crypt::decrypt(Request2::input("subject"))) 
                    {
                        unset($analytics_view[$key]);
                    }
                }
                $data["analytics_view"] = $analytics_view;
                $data["page"] = "Press Release - Details";
                return view("press_admin.press_admin_dashboard_view",$data); 
            }
        }
         else
        {
           return Redirect::to("/"); 
        }
    }

    public function pressadmin_media_contacts()
    {

        $data['_media_contacts'] = Tbl_press_release_recipient::get();
        $data['edit']     = DB::table('tbl_press_release_recipients')
                            ->where('recipient_id',session('r_edit'))
                            ->get();

        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                return Redirect::to("/pressuser/mypressrelease");
           }
           else
           {
                $data["page"] = "Press Release - Press Release";
                return view("press_admin.press_admin_media_contacts", $data);
           }
        }

        else
        {   
            return Redirect::to("/"); 
        }
      
    }

    public function mediacontacts_search(Request $request)
    {  
      $search_media = $request->search_media;
      $data["_media_contacts"] = Tbl_press_release_recipient::where('name','like','%'.$search_media.'%')
                                 ->Orwhere('company_name','like','%'.$search_media.'%')
                                 ->Orwhere('country','like','%'.$search_media.'%')
                                 ->Orwhere('research_email_address','like','%'.$search_media.'%')
                                 ->get();
      return view("press_admin.search_press_admin_media_contacts", $data);
    }

    public function manage_user()
    {
        // dd(session("edit_user"));
        $data['_user'] = Tbl_pressiq_user::where('user_level',2)->get();
        $data['_admin'] = Tbl_pressiq_user::where('user_level',1)->get();
        $data['_user_edit'] = Tbl_pressiq_user::where('user_id',session('edit_user'))->get();
        $data['_admin_edit'] = Tbl_pressiq_user::where('user_id',session('edit_admin'))->get();
        
        
        $data['_edit'] = Tbl_pressiq_user::where('user_id',session('u_edit'))->get();
        

        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                return Redirect::to("/pressuser/mypressrelease");
           }
           else
           {
                $data["page"] = "Press Release - Press Release";
                return view("press_admin.press_admin_manage_user", $data);
           }
        }
        else
        {
            return Redirect::to("/");   
        }
    }         

    public function manage_user_search(Request $request)
    {   
      $search_user = $request->search_user;
      $data["_user"] = Tbl_pressiq_user::where('user_level',2)
                        ->where('user_first_name','like','%'.$search_user.'%')
                        ->Orwhere('user_last_name','like','%'.$search_user.'%')
                        ->Orwhere('user_email','like','%'.$search_user.'%')
                        ->Orwhere('user_company_name','like','%'.$search_user.'%')
                        ->get();
      return view("press_admin.search_press_admin_manage_user", $data);
    }

    public function manage_user_add_admin(Request $request)
    {
      $data["user_first_name"]                 = $request->user_first_name;
      $data["user_last_name"]                  = $request->user_last_name;
      $data["user_email"]                      = $request->user_email;
      $data["user_password"]                   = Crypt::encrypt(request('user_password'));
      $data["user_level"]                      = "1";
      Tbl_pressiq_user::insert($data);
      Session::flash('success_admin', 'New Admin Successfully Added!');
      return  redirect::back();
    }

    public function pressadmin_manage_user_edit()
    {
        DB::table('tbl_pressiq_user')
                        ->where('user_id', session('edit_user'))
                        ->update([
                            'user_first_name'     =>request('first_name'),
                            'user_last_name'      =>request('last_name'),
                            'user_email'          =>request('email'),
                            'user_company_name'   =>request('company_name'),
                            ]);
        Session::forget('edit_user');
        Session::flash('success_user', 'User Successfully Updated!');
        return redirect()->back();
    }

    public function pressadmin_manage_force_login($id)
    {

        session::flush();
        $_user_data = DB::table('tbl_pressiq_user')->where('user_id',$id)->get();
        
        foreach ($_user_data as $user_data) {
            # code...
        }
        Session::put('user_email', $user_data->user_email);
        Session::put('user_first_name',$user_data->user_first_name);
        Session::put('user_last_name',$user_data->user_last_name);
        Session::put('user_company_name',$user_data->user_company_name);
        Session::put('user_company_image',$user_data->user_company_image);
        Session::put('pr_user_level',$user_data->user_level);
        Session::put('pr_user_id',$user_data->user_id);

        return Redirect::to("/signin"); 

    }

    public function pressadmin_manage_admin_edit()
    {
        DB::table('tbl_pressiq_user')
                        ->where('user_id', session('edit_admin'))
                        ->update([
                            'user_first_name'     =>request('first_name'),
                            'user_last_name'      =>request('last_name'),
                            'user_email'          =>request('email'),
                            'user_company_name'   =>request('company_name')
                            ]);
        Session::forget('edit_admin');
         Session::flash('success_admin', 'Admin Successfully Updated!');
        return redirect()->back();
    }
    public function edit_user($id)
    {
        Session::put('edit_user',$id);
        
        return redirect()->back();
    }
    public function edit_admin($id)
    {
        Session::put('edit_admin',$id);
        
        return redirect()->back();
    }

    public function manage_user_delete_admin($id)
    {
      Tbl_pressiq_user::where('user_id',$id)->delete();
      Session::flash('delete_admin', "Admin Already Deleted!");
      return  redirect::back();
    }

    public function manage_user_delete_user($id)
    {
      Tbl_pressiq_user::where('user_id',$id)->delete();
      Session::flash('delete_user', "User Already Deleted!");
      return  redirect::back();
    }
   
    public function pressadmin_email()
    {   

        $data['_email'] = Tbl_pressiq_press_releases::paginate(5);

        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                return Redirect::to("/pressuser/mypressrelease");
           }
           else
           {
                $data["page"] = "Press Release - Press Release";
                return view("press_admin.press_admin_email", $data);
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }

    public function pressadmin_email_edit($id)
    {   
        if(Session::exists('user_email'))
        {
           $level=session('pr_user_level');
           if($level!="1")
           {
                return Redirect::to("/pressuser/mypressrelease");
           }
           else
           {
                Session::put('e_edit',$id);
                $data['edit']     = DB::table('tbl_pressiq_press_releases')
                            ->where('pr_id',session('e_edit'))
                            ->get();

                $data["page"] = "Press Release - Press Release";
                return view("press_admin.press_admin_email_edit", $data);
           }
        }
        else
        {
            return Redirect::to("/"); 
        }
    }
    public function pressadmin_email_save(Request $request)
    {   
        DB::table('tbl_pressiq_press_releases')
                        ->where('pr_id', session('e_edit'))
                        ->update([
                            'pr_headline'     =>request('pr_headline'),
                            'pr_content'      =>request('pr_content'),
                            'pr_boiler_content'=>request('pr_boiler_content')
                            ]);
        Session::forget('e_edit');
        return redirect::to("/pressadmin/email");
    }

    public function email_delete($id)
    {
      Tbl_pressiq_press_releases::where('pr_id',$id)->delete();
      Session::flash('delete_email', "Email Already Deleted!");
      return  redirect::back();
    }

    public function pressadmin_pressrelease_addrecipient(Request $request)
    {
        
      $data["name"]                      = $request->name;
      $data["position"]                  = $request->position;
      $data["company_name"]              = $request->company_name;
      $data["country"]                   = $request->country;
      $data["research_email_address"]    = $request->contact_email;
      $data["website"]                   = $request->contact_website;
      $data["media_type"]                = $request->media_type;
      $data["industry_type"]             = $request->industry_type;
      $data["title_of_journalist"]       = $request->title_journalist;
      $data["description"]               = $request->description;

        if(session::has('r_edit'))
        {
            DB::table('tbl_press_release_recipients')
                        ->where('recipient_id', session('r_edit'))
                        ->update([
                            'name'                  =>$data["name"],
                            'position'              =>$data["position"],
                            'company_name'          =>$data["company_name"],
                            'country'               =>$data["country"],
                            'research_email_address'=>$data["research_email_address"],
                            'website'               =>$data["website"],
                            'media_type'            =>$data["media_type"],
                            'industry_type'         =>$data["industry_type"],
                            'title_of_journalist'   =>$data["title_of_journalist"],
                            'description'           =>$data["description"]
                            ]);
                    Session::forget('r_edit');
                    Session::flash('success_merchant', 'Recipient Successfully Updated!');
        }
        else
        {
            Tbl_press_release_recipient::insert($data);
            Session::flash('success_merchant', 'Recipient Successfully Added!');
        }

      return  redirect::back();
    }

    public function pressreleases_deleterecipient($id)
    {
      Tbl_press_release_recipient::where('recipient_id',$id)->delete();
      Session::flash('delete', "Recipient Already Deleted!");
      return  redirect::back();
    }
    public function pressreleases_edit_recipient($id)
    {
        Session::put('r_edit',$id);
        return Redirect::back();
    }

    public function pressuser_choose_recipient(Request $request)
    {
        $filter["country"]             = $request->choose_country;
        $filter["industry_type"]       = $request->industry_type;
        $filter["media_type"]          = $request->media_type;
        $filter["title_of_journalist"] = $request->title_of_journalist;

        
        if($filter["country"]=="" && $filter["industry_type"]=="" && $filter["media_type"]=="" && $filter["title_of_journalist"]=="")
        {
            dd("Select a filter data");
        }
        if ($filter["country"]!="" && $filter["industry_type"]=="" && $filter["media_type"]=="" && $filter["title_of_journalist"]=="")
        {
            //dd($request->choose_country);
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('country', $filter["country"])
                                 ->get();
        }
        elseif ($filter["country"]!="" && $filter["industry_type"]!="" && $filter["media_type"]=="" && $filter["title_of_journalist"]=="") 
        {   
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('country', $filter["country"])
                                ->whereIn('industry_type', $filter["industry_type"])
                                 ->get();
        }
        elseif ($filter["country"]!="" && $filter["industry_type"]!="" && $filter["media_type"]!="" && $filter["title_of_journalist"]=="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('country', $filter["country"])
                                ->whereIn('industry_type', $filter["industry_type"])
                                ->whereIn('media_type', $filter["media_type"])
                                 ->get();
        }
        elseif ($filter["country"]!="" && $filter["industry_type"]=="" && $filter["media_type"]!="" && $filter["title_of_journalist"]=="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('country', $filter["country"])
                                ->whereIn('media_type', $filter["media_type"])
                                ->get();
        }
        elseif ($filter["country"]!="" && $filter["industry_type"]=="" && $filter["media_type"]=="" && $filter["title_of_journalist"]!="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('country', $filter["country"])
                                ->whereIn('title_of_journalist', $filter["title_of_journalist"])
                                ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]!="" && $filter["media_type"]=="" && $filter["title_of_journalist"]=="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('industry_type', $filter["industry_type"])
                                 ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]!="" && $filter["media_type"]!="" && $filter["title_of_journalist"]=="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('industry_type', $filter["industry_type"])
                                ->whereIn('media_type', $filter["media_type"])
                                 ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]!="" && $filter["media_type"]=="" && $filter["title_of_journalist"]!="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('industry_type', $filter["industry_type"])
                                ->whereIn('title_of_journalist', $filter["title_of_journalist"])
                                ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]!="" && $filter["media_type"]!="" && $filter["title_of_journalist"]!="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('industry_type', $filter["industry_type"])
                                ->whereIn('media_type', $filter["media_type"])
                                ->whereIn('title_of_journalist', $filter["title_of_journalist"])
                                 ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]=="" && $filter["media_type"]!="" && $filter["title_of_journalist"]=="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('media_type', $filter["media_type"])
                                 ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]=="" && $filter["media_type"]!="" && $filter["title_of_journalist"]!="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('media_type', $filter["media_type"])
                                ->whereIn('title_of_journalist', $filter["title_of_journalist"])
                                ->get();
        }
        elseif ($filter["country"]=="" && $filter["industry_type"]=="" && $filter["media_type"]=="" && $filter["title_of_journalist"]!="") 
        {
            $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('title_of_journalist', $filter["title_of_journalist"])
                                ->get();
        }

        else
        {
        $data['_recipient']   = Tbl_press_release_recipient::
                                whereIn('country', $filter["country"])
                                ->whereIn('industry_type', $filter["industry_type"])
                                ->whereIn('media_type', $filter["media_type"])
                                ->whereIn('title_of_journalist', $filter["title_of_journalist"])
                                ->get();
        }


        $data['total_query'] = $data['_recipient']->count();
        
        return view("press_user.choose_recipient", $data);
    }

    
    public function pressreleases_image_upload()
    {
        $shop_id    = $this->shop_info->shop_id;
        $shop_key   = $this->shop_info->shop_key;

        /* SAVE THE IMAGE IN THE FOLDER */
        $file               = Input::file('file');
        $extension          = $file->getClientOriginalExtension();
        $filename           = str_random(15).".".$extension;
        $destinationPath    = 'uploads/'.$shop_key."-".$shop_id;

        $image_path = Storage::putFile($destinationPath, Input::file('file'));

        if ($image_path) 
        {
            $upload_success = true;
        }
        else
        {
            $upload_success = false;
        }

        $insert_image["image_path"]         = "/" . $image_path; 
        $insert_image["image_shop"]         = $this->shop_info->shop_id;
        $insert_image["image_reason"]       = "product";
        $insert_image["image_reason_id"]    = 0;
        $insert_image["image_date_created"] = Carbon::now();
        $insert_image["image_key"]          = uniqid();
        $image_id = Tbl_image::insertGetId($insert_image);

        if( $upload_success ) 
        {
           return json_encode(array('location' => "/" . $image_path));
        } 
        else 
        {
           return Response::json('error', 400);
        }
    }

    public function thank_you()
    {
        
        $data["page"] = "Thank You";
        return view("press_user.thank_you", $data);
    }
    /*Press Release*/


    public function getEventDetails(Request $request)
    {
        $data['event'] = ShopEvent::first($this->shop_info->shop_id, $request->id);

        return Self::load_view_for_members('member.view_events', $data);
    }
    public function getEventReserve(Request $request)
    {
        $data['page'] = "Reserve a Seat";
        $data['event'] = ShopEvent::first($this->shop_info->shop_id, $request->id);
        $data['action'] = '/members/event-reserve-submit';

        $data['customer_details'] = null;
        $data['customer_address'] = null;
        if(Self::$customer_info)
        {
            $customer = Customer::info(Self::$customer_info->customer_id, $this->shop_info->shop_id);
            $data['customer_details'] = $customer['customer'];
            $data['customer_address'] = $customer['shipping'];
        }
        return Self::load_view_for_members('member.event_popup_form', $data);
    }
    public function postEventReserveSubmit(Request $request)
    {
        $insert['reservee_fname']           = $request->reservee_fname;
        $insert['reservee_mname']           = $request->reservee_mname;
        $insert['reservee_lname']           = $request->reservee_lname;
        $insert['reservee_address']         = $request->reservee_address;
        $insert['reservee_contact']         = $request->reservee_contact;
        $insert['reservee_enrollers_code']  = $request->reservee_enrollers_code;

        $validate['reservee_fname']             = 'required';
        $validate['reservee_mname']             = 'required';
        $validate['reservee_lname']             = 'required';
        $validate['reservee_address']           = 'required';
        $validate['reservee_contact']           = 'required';
        $validate['reservee_enrollers_code']    = 'required';

        $validator = Validator::make($insert, $validate);

        $insert['reserve_date']  = Carbon::now();
        
        $return['status'] = null;
        $return['status_message'] = null;
        if(!$validator->fails()) 
        {
            $return_id = ShopEvent::reserved_seat($request->event_id, Self::$customer_info->customer_id, $insert);

            if(is_numeric($return_id))
            {
                $return['status'] = 'success';
                $return['call_function'] = 'success_reserve';
            }
            else
            {                
                $return['status'] = 'error_status';
                $return['call_function'] = 'success_reserve';
                $return['status_message'] = $return_id;
            }
        }
        else
        {
            $message = null;
            foreach($validator->errors()->all() as $error)
            {
                $message .= "<div>" . $error . "</div>";
            }
            $return['status'] = 'error_status';
            $return['call_function'] = 'success_reserve';
            $return['status_message'] = $message;
        }

        return json_encode($return);
    }
    public function getLead()
    {
        $data["page"] = "LEAD";
        $data["url"] = "http://" . $_SERVER["SERVER_NAME"];
        return view('member2.lead', $data);
    }
    public function getSlotInfo()
    {
        try 
        {
            $slot_id            = Crypt::decrypt(request("slot_no"));
            $key                = request("key");
            $data["slot_info"]  = $slot_info = Tbl_mlm_slot::where("slot_id", $slot_id)->customer()->first();

            if(md5($slot_info->slot_id . $slot_info->slot_no) == $key)
            {
                return Self::load_view_for_members('member.slot_info', $data);   
            }
            else
            {
                return "ERROR OCCURRED";
            }
        } 
        catch (DecryptException $e) 
        {
            return "ERROR OCCURRED";
        }
    }
    public function getAutologin()
    {
        $data["force_login"] = true;

        try 
        {
            $data["password"] = Crypt::decrypt(request()->password);
            return view("member.autologin", $data);
        }
        catch (DecryptException $e) 
        {
            return Redirect::back();
        }
    }
    public function request_payout_allow()
    {
        $settings = Tbl_mlm_encashment_settings::where("shop_id", $this->shop_info->shop_id)->first();
        $allow = false;
        if($settings)
        {
            if ($settings->encashment_settings_schedule_type != "none") 
            {
                if (is_serialized($settings->encashment_settings_schedule)) 
                {
                    foreach (unserialize($settings->encashment_settings_schedule) as $key => $value) 
                    {
                        if ($value == "true") 
                        {
                            if ($settings->encashment_settings_schedule_type == "weekly") 
                            {
                                if ($key == date("w")) 
                                {
                                    $allow = true;
                                }
                            }
                            elseif($settings->encashment_settings_schedule_type == "monthly")
                            {
                                if ($key == date("j")) 
                                {
                                    $allow = true;
                                }
                            }
                            else
                            {
                                $allow = true;
                            }
                        }
                    }
                }
            }  
        
            if (!$allow) 
            {
                if (is_serialized($settings->encashment_settings_schedule)) 
                {
                    if ($settings->encashment_settings_schedule_type == "weekly") 
                    {
                        echo '<div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title"><i class="fa fa-money"></i> REQUEST PAYOUT</h4>
                            </div>';

                        echo "<h3 class='text-center' style='margin: 25px 0;'>You can only request payout every ";
                        foreach (unserialize($settings->encashment_settings_schedule) as $key0 => $value0) 
                        {
                            if ($value0 == "true") 
                            {
                                echo date('l', strtotime("Sunday +{$key0} days")) . " ";
                            }
                        }
                        echo ".</h3>";
                        
                        echo '<div class="modal-footer">
                                <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                            </div>';

                        die();
                    }
                    elseif($settings->encashment_settings_schedule_type == "monthly")
                    {
                        echo '<div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title"><i class="fa fa-money"></i> REQUEST PAYOUT</h4>
                            </div>';

                        echo "<h3 class='text-center' style='margin: 25px 0;'>You can only request payout every ";
                        foreach (unserialize($settings->encashment_settings_schedule) as $key0 => $value0) 
                        {
                            if ($value0 == "true") 
                            {
                                echo ordinal($key0) . " ";
                            }
                        }
                        echo "day of the month.</h3>";
                        
                        echo '<div class="modal-footer">
                                <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                            </div>';

                        die();
                    }
                }
                else
                {
                    echo '<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title"><i class="fa fa-money"></i> REQUEST PAYOUT</h4>
                        </div>';

                    echo "<h3 class='text-center' style='margin: 25px 0;'>You are not allowed to request payout right now.</h3>";
                    
                    echo '<div class="modal-footer">
                            <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        </div>';

                    die();
                }
            }
        }
        else
        {
            echo "<h3 class='text-center' style='margin: 25px 0;'> Contact Administrator to create Encashment Settings. </h3>";
        }
    }
    public function getRequestPayout()
    {
        $this->request_payout_allow();

        $data["page"] = "Request Payout";
        $data["_slot"] = MLM2::customer_slots($this->shop_info->shop_id, Self::$customer_info->customer_id);
        return view("member2.request_payout", $data);
    }
    public function postRequestPayout()
    {
        $store["request_wallet"] = request("request_wallet");
        session($store);
        return Self::payout_validation();
    }
    public function payout_validation()
    {
        $return = "";
        $_slot = MLM2::customer_slots($this->shop_info->shop_id, Self::$customer_info->customer_id);
        $payout_setting = Tbl_mlm_encashment_settings::where("shop_id", $this->shop_info->shop_id)->first();
        $minimum = doubleval(isset($payout_setting->enchasment_settings_minimum) ? $payout_setting->enchasment_settings_minimum : 0);

        if($this->shop_info->shop_id != 60) //no neet to setup for JCA - temporary only
        {
            if(Self::$customer_info->customer_payout_method == 'unset')
            {
                $return .= "<div>Please setup your payout settings first.</div>";
            }
        }

        // Validate Email V-Money
        $customer_info = Self::$customer_info;

        if($customer_info->customer_payout_method == "unset")
        {
            $method     = "cheque";
        }
        else
        {
            $method     = $customer_info->customer_payout_method;
        }

        $request_wallet = session("request_wallet");

        foreach($_slot as $key => $slot)
        {
            // Method V-Money
            if ($method == "vmoney") 
            {
                $get_email = Tbl_vmoney_settings::where("slot_id", $slot->slot_id)->value("vmoney_email");

                if (!$get_email || $get_email == "" ) 
                {
                    $return .= "<div>Please set your vmoney e-mail for <b>" . $slot->slot_no . "</b> in payout settings.</div>";
                }
            }

            // Method Airline Wallet
            if ($method == "airline") 
            {
                $tour_wallet = Tbl_tour_wallet_slot::where("slot_id", $slot->slot_id)->value("tour_wallet_account_id");

                if (!$tour_wallet || $tour_wallet == "" ) 
                {
                    $return .= "<div>Please set your Airline Wallet Account ID for <b>" . $slot->slot_no . "</b> in payout settings.</div>";
                }
            }

            $request_amount = $request_wallet[$key];

            if ($request_amount != 0) 
            {
                if(doubleval($slot->current_wallet) < doubleval($request_amount))
                {
                    $return .= "<div>The amount you are trying to request for <b>" . $slot->slot_no . "</b> is more than the amount you currently have.</div>";
                }

                if($request_amount < 0)
                {
                    $return .= "<div>The amount you are trying to request for <b>" . $slot->slot_no . "</b> is less than zero.</div>";
                }
                
                if($minimum > doubleval($request_amount))
                {
                    $return .= "<div>The amount you are trying to request for <b>" . $slot->slot_no . "</b> is less than the limit for encashment.</div>";                
                }
            }
        }

        return $return;
    }
    public function anyVerifyPayout()
    {
        $data["page"] = "Verify Payout";
        $_slot = MLM2::customer_slots($this->shop_info->shop_id, Self::$customer_info->customer_id);
        
        $payout_setting = Tbl_mlm_encashment_settings::where("shop_id", $this->shop_info->shop_id)->first();

        $tax = $payout_setting->enchasment_settings_tax;
        $service_charge = $payout_setting->enchasment_settings_p_fee;
        $service_charge_type = $payout_setting->enchasment_settings_p_fee_type;
        $other_charge = $payout_setting->encashment_settings_o_fee;
        $minimum = $payout_setting->enchasment_settings_minimum;

        $total_payout = 0;

        if(request()->isMethod("post"))
        {
            $request_method = "post";
        }
        else
        {
            $request_method = "get";
        }
        if(Self::payout_validation() == "")
        {
            $request_wallet = session("request_wallet");

            foreach($_slot as $key => $slot)
            {
                $amount = doubleval($request_wallet[$key]);

                $_slot[$key]->request_amount = $amount;
                $_slot[$key]->display_request_amount = Currency::format($amount);

                $tax_amount = ($tax / 100) * $amount;
                $take_home = $amount - ($tax_amount);
                if($service_charge_type == 1)
                {
                    $service_charge = $take_home * ($service_charge /100);
                }
                $take_home = $take_home - ($service_charge + $other_charge);
                

                if($take_home < 0)
                {
                    $take_home = 0;
                }

                $_slot[$key]->tax_amount = $tax_amount;
                $_slot[$key]->service_charge = $service_charge;
                $_slot[$key]->other_charge = $other_charge;
                $_slot[$key]->take_home = $take_home;

                $_slot[$key]->display_tax_amount = Currency::format($tax_amount);
                $_slot[$key]->display_service_charge = Currency::format($service_charge);
                $_slot[$key]->display_other_charge = Currency::format($other_charge);
                $_slot[$key]->display_take_home = Currency::format($take_home);

                if($take_home == 0)
                {
                    unset($_slot[$key]);
                }
                else
                {
                    if($request_method == "post")
                    {
                        $shop_id    = $this->shop_info->shop_id;
                        $customer_info = Self::$customer_info;

                        if($customer_info->customer_payout_method == "unset")
                        {
                            $method     = "cheque";
                        }
                        else
                        {
                            $method     = $customer_info->customer_payout_method;
                        }

                        $slot_id    = $slot->slot_id;
                        $remarks    = "Request by Customer";
                        $other      = 0;
                        $date       = date("m/d/Y");
                        $status     = "PENDING";

                        /* V-MONEY */
                        if ($method == "vmoney") 
                        {
                            /* API */
                            $post = 'mxtransfer.svc';
                            
                            if (get_domain() == "philtechglobalinc.com") 
                            {
                                $environment = 1;
                            }
                            else
                            {
                                $environment = 0;
                            }

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

                            $get_email = Tbl_vmoney_settings::where("slot_id", $slot_id)->first();

                            if ($get_email) 
                            {
                                $pass["recipient"] = $get_email->vmoney_email; // Recipient's email address
                                $pass["merchantRef"] = Self::$customer_info->customer_id . time(); // Merchant reference number
                                $pass["amount"] = $take_home; // Amount of the transaction
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
                                        $status = "DONE"; // ASK IF RELEASED OR DONE
                                        $remarks = "Request Payout via V-Money";

                                        $slot_payout_return = MLM2::slot_payout($shop_id, $slot_id, $method, $remarks, $take_home, $tax_amount, $service_charge, $other_charge, $date, $status);
                                    }
                                    else
                                    {
                                        // TBD
                                    }
                                } 
                                catch (\Exception $e) 
                                {
                                    dd($e->getMessage());
                                }
                            }
                        }
                        elseif($method == "airline")
                        {
                            $wallet_amount = $take_home;

                            $tour = Tbl_tour_wallet_slot::where('slot_id', $slot_id)
                                                        ->leftJoin('tbl_tour_wallet', 'tbl_tour_wallet.tour_wallet_id', '=', 'tbl_tour_wallet_slot.tour_wallet_id')
                                                        ->first();

                            $host = Tbl_tour_wallet::where('tour_wallet_shop', $this->shop_info->shop_id)
                                                   ->where('tour_wallet_main', 1)
                                                   ->first();
                            
                            $base_uri = $this->shop_info->shop_wallet_tours_uri;

                            $airline_result            = AbsMain::transfer_wallet($base_uri, $host->tour_Wallet_a_account_id, $host->tour_wallet_a_username, $host->tour_wallet_a_base_password, $tour->tour_Wallet_a_account_id, $wallet_amount);
                            $airline_result['message'] = 'Wallet Transfer Success';

                            if($airline_result['status'] == 1)
                            {
                                $wallet_nega                              = $wallet_amount * (-1);

                                $log['shop_id']                           = $this->shop_info->shop_id;
                                $log['wallet_log_slot']                   = $slot_id;
                                $log['wallet_log_slot_sponsor']           = $slot_id;
                                $log['wallet_log_details']                = 'You have transferred ' . $wallet_amount . ' To your tours wallet. ' . $wallet_amount . ' is deducted to your wallet.' ;
                                $log['wallet_log_amount']                 = $wallet_nega;
                                $log['wallet_log_plan']                   = 'TOURS_WALLET';
                                $log['wallet_log_status']                 = 'released';
                                $log['wallet_log_claimbale_on']           = Carbon::now();

                                $insert['tour_wallet_logs_wallet_amount'] = $wallet_amount;
                                $insert['tour_wallet_logs_date']          = Carbon::now(); 
                                $insert['tour_wallet_logs_tour_id']       = $tour->tour_wallet_id; 
                                $insert['tour_wallet_logs_account_id']    = $tour->tour_Wallet_a_account_id;
                                $insert['tour_wallet_logs_customer_id']   = Self::$customer_info->customer_id;
                                $insert['tour_wallet_logs_accepted']      = 1;
                                $insert['tour_wallet_logs_points']        = 0;

                                // Get Balance
                                $host_update = AbsMain::get_balance($base_uri, $host->tour_Wallet_a_account_id, $host->tour_wallet_a_username, $host->tour_wallet_a_base_password);
                                
                                if($host_update['status'] == 1)
                                {
                                    $update['tour_wallet_a_current_balance'] = $host_update['result'];

                                    Tbl_tour_wallet::where('tour_wallet_shop', $this->shop_info->shop_id)
                                                   ->where('tour_wallet_main', 1)
                                                   ->update($update);
                                }

                                $update['tour_wallet_a_current_balance'] = $tour->tour_wallet_a_current_balance + $wallet_amount;
                                
                                Tbl_tour_wallet::where('tour_wallet_customer_id', Self::$customer_info->customer_id)
                                               ->where('tour_wallet_main', 0)
                                               ->update($update);

                                Mlm_slot_log::slot_array($log);

                                $tour_wallet_convertion = $host->tour_wallet_convertion;

                                if($tour_wallet_convertion != 0)
                                {
                                    $points                            = ($wallet_amount/100) * $tour_wallet_convertion;
                                    $insert['tour_wallet_logs_points'] = $points;
                                    $l                                 = "You have earned " . $points . " Repurchase Points. From  tours wallet."; 
                                    $log['shop_id']                    = $this->shop_info->shop_id;
                                    $log['wallet_log_slot']            = $slot_id;
                                    $log['wallet_log_slot_sponsor']    = $slot_id;
                                    $log['wallet_log_details']         = $l ;
                                    $log['wallet_log_amount']          = 0;
                                    $log['wallet_log_plan']            = 'TOURS_WALLET_POINTS';
                                    $log['wallet_log_status']          = 'released';
                                    $log['wallet_log_claimbale_on']    = Carbon::now();

                                    Mlm_slot_log::slot_array($log);

                                    $array['points_log_complan']        = "REPURCHASE_POINTS";
                                    $array['points_log_level']          = 0;
                                    $array['points_log_slot']           = $slot_id;
                                    $array['points_log_Sponsor']        = $slot_id;
                                    $array['points_log_date_claimed']   = Carbon::now();
                                    $array['points_log_converted']      = 0;
                                    $array['points_log_converted_date'] = Carbon::now();
                                    $array['points_log_type']           = 'PV';
                                    $array['points_log_from']           = 'Wallet Tours';
                                    $array['points_log_points']         = $points;

                                    Mlm_slot_log::slot_log_points_array($array);
                                }

                                DB::table('tbl_tour_wallet_logs')->insert($insert);
                            }
                        }
                        else
                        {
                            $slot_payout_return = MLM2::slot_payout($shop_id, $slot_id, $method, $remarks, $take_home, $tax_amount, $service_charge, $other_charge, $date, $status);
                        }
                    }
                }

                $total_payout += $take_home;
            }

            if($request_method == "get")
            {
                $data["total_payout"] = $total_payout;
                $data["display_total_payout"] = Currency::format($total_payout);

                $data["_slot"] = $_slot;

                return view("member2.verify_payout", $data); 
            }
        }
        else
        {
            echo "SERVER ERROR";
        }
    }
    public function getPayoutSetting()
    {
        if (Self::$customer_info) 
        {
            $data["page"] = "Payout";
            $data['_slot'] = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->coinsph()->money_remittance()->bank()->vmoney()->airline()->get();
            $data["_method"] = unserialize($this->shop_info->shop_payout_method);

            // Philtech
            if ($this->shop_theme == "philtech") 
            {
                foreach ($data["_slot"] as $key => $value) 
                {
                    $membership_name = DB::table("tbl_membership")->where("membership_id", $value->slot_membership)->value("membership_name");
                    
                    if ($membership_name == "V.I.P Gold") 
                    {
                        $data["_airline_slot"][$key] = $value;
                    }
                }

                if (!isset($data["_airline_slot"])) 
                {
                    foreach ($data["_method"] as $key => $value) 
                    {
                        if ($value == "airline") 
                        {
                            unset($data["_method"][$key]);
                        }
                    }
                }
            }

            $data["_bank"] = Tbl_payout_bank::shop($this->shop_info->shop_id)->get();
            $data["tin_number"] = Self::$customer_info->tin_number;
            return view("member2.payout_settings", $data);
        }
        else
        {
            echo "Please login first.";
        }
    }
    public function postPayoutSetting()
    {
        $shop_id = $this->shop_info->shop_id;
        $customer = Self::$customer_info;

        /* UPDATE CUSTOMER PAYOUT METHOD */
        $update_customer["customer_payout_method"] = request("customer_payout_method");
        $update_customer["tin_number"] = request("tin_number");
        Tbl_customer::where("customer_id", Self::$customer_info->customer_id)->update($update_customer);

        $json["status"] = "success";
        $json["message"] = "";

        /* UPDATE  METHOD */
        if (request("customer_payout_method") == "airline") 
        {
            if (request("airline")) 
            {
                foreach(request("airline") as $key => $value)
                {
                    $slot_info = Tbl_mlm_slot::where("slot_no", $value)->where("shop_id", $this->shop_info->shop_id)->first();

                    if ($slot_info) 
                    {
                        if ($customer) 
                        {
                            $airline_result = AbsMain::update_info($customer->customer_id, $slot_info->slot_id, request("tour_wallet_account_id")[$key], $this->shop_info->shop_id); 
                            
                            if ($airline_result["status"] != 1) 
                            {
                                $json["status"] = "error";
                                $json["message"] = "Your Airline Ticketing Account ID is incorrect.";
                            }
                        }
                    }
                }
            }
            else
            {
                $json["status"] = "error";
                $json["message"] = "Your Airline Ticketing Account ID Field is empty.";
            }
        }

        /* UPDATE VMONEY METHOD */
        foreach(request("vmoney") as $key => $value)
        {
            $slot_info = Tbl_mlm_slot::where("slot_no", $value)->where("shop_id", $this->shop_info->shop_id)->first();

            if ($slot_info) 
            {
                $update_vmoney["slot_id"] = $slot_info->slot_id;
                $update_vmoney["vmoney_email"] = request("vmoney_email")[$key];
                
                $exist = Tbl_vmoney_settings::where("slot_id", $slot_info->slot_id)->first();

                if ($exist) 
                {
                    Tbl_vmoney_settings::where("slot_id", $slot_info->slot_id)->update($update_vmoney);
                }
                else
                {
                    Tbl_vmoney_settings::insert($update_vmoney);
                }            
            }
        }

        /* UPDATE EON METHOD */
        foreach(request("eon_slot_code") as $key => $eon_slot_no)
        {
            $update_mlm_slot["slot_eon_account_no"] = request("eon_account_no")[$key];
            $update_mlm_slot["slot_eon_card_no"] = request("eon_card_no")[$key];
            Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $eon_slot_no)->update($update_mlm_slot);
        }

        /* UPDATE BANK DETAILS */
        foreach(request("bank_slot_no") as $key => $bank_slot_no)
        {
            $slotinfo = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $bank_slot_no)->first();

            if(request("bank_id")[$key] != "")
            {
                $check_bank_exist = Tbl_mlm_slot_bank::where("slot_id", $slotinfo->slot_id)->first();

                if($check_bank_exist)
                {
                    $update_bank["payout_bank_id"] = request("bank_id")[$key];
                    $update_bank["bank_account_number"] = request("bank_account_no")[$key];
                    $update_bank["bank_account_type"] = request("bank_account_type")[$key];
                    Tbl_mlm_slot_bank::where("slot_id", $slotinfo->slot_id)->update($update_bank);
                }
                else
                {
                    $insert_bank["slot_id"] = $slotinfo->slot_id;
                    $insert_bank["payout_bank_id"] = request("bank_id")[$key];
                    $insert_bank["bank_account_number"] = request("bank_account_no")[$key];
                    $insert_bank["bank_account_type"] = request("bank_account_type")[$key];
                    Tbl_mlm_slot_bank::insert($insert_bank);
                }
            }
        }

        /*UPDATE MONER REMITTANCE DETAILS*/
        foreach (request('remittance_slot_no') as $key => $remittance_slot_no) 
        { 
            $slotinfo = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $remittance_slot_no)->first();
            if(request("first_name")[$key] != "" && request("middle_name")[$key] != "" && request("last_name")[$key] != "" && request("contact_number")[$key] != "")
            {
                $check_money_remittance = Tbl_mlm_slot_money_remittance::where('slot_id',$slotinfo->slot_id)->first();

                if($check_money_remittance)
                {
                    $update_remittance["money_remittance_type"] = request("money_remittance_type")[$key];
                    $update_remittance["first_name"] = request("first_name")[$key];
                    $update_remittance["middle_name"] = request("middle_name")[$key];
                    $update_remittance["last_name"] = request("last_name")[$key];
                    $update_remittance["contact_number"] = request("contact_number")[$key];
                    Tbl_mlm_slot_money_remittance::where("slot_id", $slotinfo->slot_id)->update($update_remittance);
                }
                else
                {
                    $insert_remittance["money_remittance_type"] = request("money_remittance_type")[$key];
                    $insert_remittance["slot_id"] = $slotinfo->slot_id;
                    $insert_remittance["first_name"] = request("first_name")[$key];
                    $insert_remittance["middle_name"] = request("middle_name")[$key];
                    $insert_remittance["last_name"] = request("last_name")[$key];
                    $insert_remittance["contact_number"] = request("contact_number")[$key];
                    Tbl_mlm_slot_money_remittance::insert($insert_remittance);
                }

            }
        }

        /* UPDATE EON METHOD */
        foreach(request("coinsph_slot_no") as $key => $coinsph_slot_no)
        {
            $slotinfo = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $coinsph_slot_no)->first();
            if(request('wallet_address')[$key] != '')
            {
                $check_coinsph = Tbl_mlm_slot_coinsph::where('slot_id',$slotinfo->slot_id)->first();
                if($check_coinsph)
                {
                    $update_coinsph["wallet_address"] = request("wallet_address")[$key];
                    Tbl_mlm_slot_coinsph::where("slot_id", $slotinfo->slot_id)->update($update_coinsph);
                }
                else
                {
                    $insert_coins["slot_id"] = $slotinfo->slot_id;
                    $insert_coins["wallet_address"] = request("wallet_address")[$key];
                    Tbl_mlm_slot_coinsph::insert($insert_coins);
                }
            }
        }


        echo json_encode($json);
    }

    public function getPayoutSettingSuccess()
    {
        $data["title"] = "Success!";
        $data["message"] = "Payout details has been successfully updated.";
        return view("member2.success", $data);
    }
    public static function store_login_session($email, $password)
    {
        $store["email"]         = $email;
        $store["auth"]          = $password;
        $sess["mlm_member"]     = $store;

        session($sess);
    }
    public function getLogin()
    {
        $data["page"] = "Login";
        $get_fb = FacebookGlobals::check_app_key($this->shop_info->shop_id);
        
        if($get_fb)
        {
            $data['fb_login_url'] = FacebookGlobals::get_link($this->shop_info->shop_id);
        }
        
        $get_google = GoogleGlobals::check_app_key($this->shop_info->shop_id);
        
        if($get_google)
        {
            $data['google_app_id'] = SocialNetwork::get_keys($this->shop_info->shop_id, 'googleplus')['app_id'];
        }
        $data['show_fb'] = null;
        if(request("pass") == "123")
        {
            $data['show_fb'] = 123;
        }

        return Self::load_view_for_members("member.login", $data, false);
    }
    public function postAuthCallback(Request $request)
    {
        session_start();

        $pass = isset($request->id) ? $request->id : null;
        $email = isset($request->email) ? $request->email : null;
        $client_id = '431988284265-f8brg2nuvhmmgs3l5ip8bdogj62jkidp.apps.googleusercontent.com';
        $client_secret = '1hArs-eRANIXj1uaubhajbu8';
        $redirect_uri = 'http://myphone.digimahouse.dev/member';

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope("https://www.googleapis.com/auth/plus/login");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");

        $plus = new Google_Service_Plus($client);
        if (isset($_REQUEST['logout'])) 
        {
            unset($_SESSION['access_token']);
        }
        if (isset($_POST['code'])) 
        {
            $client->authenticate($_POST['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) 
        {
            $client->setAccessToken($_SESSION['access_token']);
            $_SESSION['token'] = $client->getAccessToken();

        } else 
        {
            $authUrl = $client->createAuthUrl();
        }

        // if (isset($authUrl)) 
        // {
        //     print "<a class='login' href='$authUrl'><img src='logogoo/Red-signin-Medium-base-32dp.png'></a>";
        // } 
        // else 
        // {
        //     print "<a class='logout' href='pruebas.php?logout'>Cerrar:</a>";
        // }
        $correo = null;
        $me = $plus->people->get("me");
    }
    public function getSigninGoogle()
    {
        return view('signin_google');
    }
    public function postLoginGoogleSubmit(Request $request)
    {
        $pass = isset($request->id) ? $request->id : null;
        $email = isset($request->email) ? $request->email : null;

        session_start();
        $_SESSION['access_token'] = $request->access_token;

        $check = Tbl_customer::where('email',$email)->where('shop_id',$this->shop_info->shop_id)->first();
        if($check && $pass)
        {
            Self::store_login_session($email,$pass);
        }
        else
        {
            $ins['email']           = $request->email;
            $ins['first_name']      = ucfirst($request->first_name);
            $ins['last_name']       = ucfirst($request->last_name);
            $ins['password']        = Crypt::encrypt($request->id);
            $ins['mlm_username']    = $request->email;
            $ins['ismlm']           = 1;
            $ins['created_at']      = Carbon::now();
            $ins['signup_with']     = 'googleplus';

            $reg = Customer::register($this->shop_info->shop_id, $ins);  
            if($reg)
            {
                $email = $request->email;
                $pass = $request->id;
                Self::store_login_session($email,$pass);
            }             
        }

        echo json_encode("success");
    }
    public function getLoginSubmit()
    {
        $user_profile = FacebookGlobals::user_profile($this->shop_info->shop_id);
        $email = isset($user_profile['email']) ? $user_profile['email'] : null;
        $check = Tbl_customer::where('email',$email)->where('shop_id', $this->shop_info->shop_id)->first();

        $data = collect($user_profile)->toArray();

        if(count($user_profile) > 0 && $check && isset($data['email']) && isset($data['id']))
        {
            Self::store_login_session($data['email'],$data['id']);

            return Redirect::to("/members")->send();
        }
        else
        {
            return Redirect::to('/members/register');                
        }
    }
    public function postLogin(Request $request)
    {
        $validate["email"]      = ["required"];
        $validate["password"]   = ["required"];
        $data                   = $this->validate(request(), $validate);
        
        $email = Tbl_mlm_slot::where("slot_no", $data["email"])->customer()->value('email');
        
        if($email)
        {
            $data["email"] = $email;
        }

        if(!Customer::check_account($this->shop_info->shop_id, $data["email"],$data["password"]))
        {
            return Redirect::to("/members/login")->send()->with('error', 'Incorrect email or password.');
        }
        else
        {        
            Self::store_login_session($data["email"], $data["password"]);
            return Redirect::to("/members")->send();
        }
    }
    public function getLogout()
    {
        session()->forget("mlm_member");
        GoogleGlobals::revoke_access($this->shop_info->shop_id);

        return Redirect::to("/members/login");
    }
    public function getRegister()
    {
        $data["page"] = "Register";
        $get = FacebookGlobals::check_app_key($this->shop_info->shop_id);
        
        if($get)
        {
            $data['fb_login_url'] = FacebookGlobals::get_link_register($this->shop_info->shop_id);
        }
        
        if(request("d") == 1)
        {
            $dummy["email"] = strtolower(randomPassword()) . "@gmail.com";
            $dummy["first_name"] = ucfirst(randomPassword());
            $dummy["last_name"] = ucfirst(randomPassword());
            $dummy["middle_name"] = ucfirst(randomPassword());
            $dummy["contact"] = ucfirst(randomPassword()) . ucfirst(randomPassword());
            $dummy["password"] = ucfirst(randomPassword());
            $data["dummy"] = $dummy;
        }
        
        return Self::load_view_for_members("member.register", $data, false);
    }

    public function getRegisterSubmit()
    {
        $user_profile = FacebookGlobals::user_profile($this->shop_info->shop_id);

        if(count($user_profile) > 0)
        {
            $data = collect($user_profile)->toArray();
            $email = isset($data['email']) ? $data['email'] : '';
            $pass = $data['id'];
            $check = Tbl_customer::where('email',$email)->where('shop_id',$this->shop_info->shop_id)->first();

            $ins['email'] = $email;

            $rules['email'] = 'required';

            $validator = Validator::make($ins, $rules);

            if($validator->fails()) 
            {
                $messages = $validator->messages();
                return Redirect::to('/members/register')->with('error', 'We need your email address for you to register.');
            }
            else
            {    
                if(!$check)
                {
                    $ins['first_name']      = $data['first_name'];
                    $ins['last_name']       = $data['last_name'];
                    $ins['middle_name']     = $data['middle_name'] == null ? '' : $data['middle_name'] ;
                    $ins['gender']          = $data['gender'] == null ? 'male' : $data['gender'];
                    $ins['password']        = Crypt::encrypt($data['id']);
                    $ins['mlm_username']    = $data['email'];
                    $ins['ismlm']           = 1;
                    $ins['created_at']      = Carbon::now();
                    $ins['signup_with']     = 'facebook';
                    
                    Customer::register($this->shop_info->shop_id, $ins);  
                }
                else
                {
                    $email = $check->email;
                    $pass = Crypt::decrypt($check->password);
                }

                if($email && $pass)
                {
                    Self::store_login_session($email,$pass);

                    return Redirect::to("/members")->send();
                }
            }   
        }
        else
        {
            return Redirect::to("/members/register")->send();               
        }
    }
    public function postRegister(Request $request)
    {
        $shop_id                                = $this->shop_info->shop_id;
        $validate["first_name"]                 = ["required", "string", "min:2"];
        $validate["middle_name"]                = "";
        $validate["last_name"]                  = ["required", "string", "min:2"];
        $validate["gender"]                     = ["required"];
        $validate["contact"]                    = ["required", "string", "min:10"];
        $validate["email"]                      = ["required","min:5","email", new Uniqueonshop("tbl_customer", $shop_id)];
        $validate["b_day"]                      = ["required","integer"];
        $validate["b_month"]                    = ["required","integer"];
        $validate["b_year"]                     = ["required","integer"];
        $validate["password"]                   = ["required", "confirmed","min:5"];
        $validate["password_confirmation "]     = [];

        $insert                                 = $this->validate(request(), $validate);
        $raw_password                           = $insert["password"];
        $insert["birthday"]                     = $insert["b_month"] . "/" . $insert["b_day"] . "/" . $insert["b_year"];
        $insert["password"]                     = Crypt::encrypt($insert["password"]);
        $insert["created_at"]                   = Carbon::now();

        unset($insert["b_month"]);
        unset($insert["b_year"]);
        unset($insert["b_day"]);

        // dd($insert["birthday"]);

        if(Customer::register($this->shop_info->shop_id, $insert))
        {
            Self::store_login_session($insert["email"], $raw_password);
        }

        if(session("checkout_after_register"))
        {
            session()->forget("checkout_after_register");
            return Redirect::to("/members/checkout")->send();
        }
        else
        {
            return Redirect::to("/members")->send();
        }
    }

    public function getForgotPassword()
    {
        $data["page"] = "Forgot Password";
        return view("member.forgot_password");
    }
    public function postForgotPasswordSubmit()
    {
        $shop_id = $this->shop_info->shop_id;
        $validate = Customer::check_email($shop_id, Request2::input('email'));
        
        $return_data = null;
        if($validate)
        {
            if($validate->signup_with != 'member_register')
            {
                $return_data['status'] = 'danger';
                $return_data['status_message'] = "We're not able forgot password when your account was sign up with Facebook or Google+";
            }
            else
            {               
                $content_key = "front_forgot_password";
                if(EmailContent::checkIfexisting($content_key, $shop_id) != 0)
                {
                    $email_content["subject"] = EmailContent::getSubject($content_key, $shop_id);
                    $email_content["shop_key"] = $this->shop_info->shop_key;
                    $data["email"] = $validate->email;
                    $new_password = Crypt::decrypt($validate->password);

                    $txt[0]["txt_to_be_replace"] = "[name]";
                    $txt[0]["txt_to_replace"] = $validate->first_name." ".$validate->middle_name." ".$validate->last_name;

                    $txt[1]["txt_to_be_replace"] = "[domain_name]";
                    $txt[1]["txt_to_replace"] = $_SERVER["SERVER_NAME"];

                    $txt[2]["txt_to_be_replace"] = "[password]";
                    $txt[2]["txt_to_replace"] = $new_password;

                    $change_content = $txt;

                    $email_content["content"] = EmailContent::email_txt_replace($content_key, $change_content, $shop_id);

                    $data["template"] = Tbl_email_template::where("shop_id", $shop_id)->first();
                    if(isset($data['template']->header_image))
                    {
                        if (!File::exists(public_path() . $data['template']->header_image))
                        {
                            $data['template']->header_image = null;
                        }
                    }   

                    Mail_global::send_email($data['template'], $email_content, $shop_id, $validate->email);


                    $return_data['status'] = 'success';
                    $return_data['status_message'] = "Successfully Sent Email.";
                }
                else
                {
                    $return_data['status'] = 'danger';
                    $return_data['status_message'] = "Something wen't wrong please contact your admin.";
                } 
            }
        }
        else
        {
            $return_data['status'] = 'danger';
            $return_data['status_message'] = "Can't find your record.";
        }

        return Redirect::back()->with($return_data['status'], $return_data['status_message']);
    }
    /* LOGIN AND REGISTRATION - END */
    public function getProfile()
    {
        if (Self::$customer_info) 
        {
            $data["page"]                = "Profile";
            $data["mlm"]                 = isset(Self::$customer_info->ismlm) ? Self::$customer_info->ismlm : 0;
            $data["profile"]             = Tbl_customer::shop(Self::$customer_info->shop_id)->where("tbl_customer.customer_id", Self::$customer_info->customer_id)->first();
            $data["profile_address"]     = Tbl_customer_address::where("customer_id", Self::$customer_info->customer_id)->where("purpose", "permanent")->first();
            $data["profile_info"]        = Tbl_customer_other_info::where("customer_id", Self::$customer_info->customer_id)->first();
            $data["_country"]            = Tbl_country::get();
            $data["_locale"]             = Tbl_locale::where("locale_parent", 0)->orderBy("locale_name", "asc")->get();
            $data["allowed_change_pass"] = isset(Self::$customer_info->signup_with) ? (Self::$customer_info->signup_with == "member_register" ? true : false) : false;

            $data['beneficiary'] = null;
            if(Self::$customer_info)
            {
                $data["customer_summary"]   = MLM2::customer_income_summary($this->shop_info->shop_id, Self::$customer_info->customer_id);
                $data["wallet"]             = $data["customer_summary"]["_wallet"];

                $data['beneficiary'] = CustomerBeneficiary::first(Self::$customer_info->customer_id);
            }


            // $data["allowed_change_pass"] = true;

            return (Self::load_view_for_members("member.profile", $data));
        }
        else
        {
            return Redirect::to("/members/login");
        }
    }
    public function postProfileUpdateInfo(Request $request)
    {
        $form = $request->all();
        // $validate['first_name'] = 'required';
        // $validate['middle_name'] = 'required';
        // $validate['last_name'] = 'required';
        // $validate['b_month'] = 'required';
        // $validate['b_day'] = 'required';
        // $validate['b_year'] = 'required';
        $validate['country_id'] = 'required';
        // $validate['customer_state'] = 'required';
        // $validate['customer_city'] = 'required';
        // $validate['customer_zipcode'] = 'required';
        $validate['customer_street'] = 'required';
        $validate['contact'] = 'required';

        $validator = Validator::make($form, $validate);
        
        if (!$validator->fails()) 
        {           
            /* Birthday Fix */
            if ($request->birthdate) 
            {
                $birthday = date("Y-m-d", strtotime($request->birthdate));
            }
            else
            {
                $birthday = date("Y-m-d", strtotime($request->b_month . "/" . $request->b_day . "/" . $request->b_year)); 
            }
            
            /* Customer Data */
            if($request->first_name)
            {
                $insert_customer["first_name"]  = $request->first_name;
            }
            
            if ($request->middle_name) 
            {
                $insert_customer["middle_name"] = $request->middle_name;
            }

            if ($request->last_name) 
            {
                $insert_customer["last_name"]   = $request->last_name;
            }
            
            $insert_customer["b_day"]       = $birthday;
            $insert_customer["birthday"]    = $birthday;
            $insert_customer["country_id"]  = $request->country_id;
            $insert_customer["updated_at"]  = Carbon::now();
            $insert_customer['contact']     = $request->contact;

            Tbl_customer::where("customer_id", Self::$customer_info->customer_id)
                        ->shop(Self::$customer_info->shop_id)
                        ->update($insert_customer);

            /* Customer Address */
            $state_name    = Tbl_locale::where("locale_id", $request->customer_state)->first();
            $city_name     = Tbl_locale::where("locale_id", $request->customer_city)->first();
            $barangay_name = Tbl_locale::where("locale_id", $request->customer_zipcode)->first();

            $insert_customer_address["country_id"] = $request->country_id;
            $insert_customer_address["customer_state"] = isset($state_name) ? $state_name->locale_name : '';
            $insert_customer_address["customer_city"] = isset($city_name)  ? $city_name->locale_name : '';
            $insert_customer_address["customer_zipcode"] = isset($barangay_name) ? $barangay_name->locale_name : '';
            $insert_customer_address["customer_street"] = $request->customer_street;
            $insert_customer_address["state_id"] = $request->customer_state;
            $insert_customer_address["city_id"] = $request->customer_city;
            $insert_customer_address["barangay_id"] = $request->customer_zipcode;
            
            $exist = Tbl_customer_address::where("customer_id", Self::$customer_info->customer_id)->where("purpose", "permanent")->first();
            
            if ($exist) 
            {
                /* Update */
                $insert_customer_address["updated_at"] = Carbon::now();
                
                Tbl_customer_address::where("customer_id", Self::$customer_info->customer_id)->where("purpose", "permanent")->update($insert_customer_address);
            }
            else
            {
                /* Insert */
                $insert_customer_address["created_at"] = Carbon::now();
                $insert_customer_address["customer_id"] = Self::$customer_info->customer_id;
                $insert_customer_address["purpose"] = "permanent";
                
                Tbl_customer_address::insert($insert_customer_address);
            }
            
            echo json_encode("success");
        }
        else
        {
            $result = $validator->errors();
            echo json_encode($result);
        }
    }
    public function postProfileUpdateBeneficiary(Request $request)
    {
        $form = $request->all();
        $validate['beneficiary_fname']       = 'required';
        $validate['beneficiary_mname']       = 'required';
        $validate['beneficiary_lname']       = 'required';
        $validate['beneficiary_contact_no']  = 'required|numeric';
        $validate['beneficiary_email']       = 'required|email';

        $validator = Validator::make($form, $validate);
        
        if(!$validator->fails()) 
        {
            $update['beneficiary_fname']       = $request->beneficiary_fname;
            $update['beneficiary_mname']       = $request->beneficiary_mname;
            $update['beneficiary_lname']       = $request->beneficiary_lname;
            $update['beneficiary_contact_no']  = $request->beneficiary_contact_no;
            $update['beneficiary_email']       = $request->beneficiary_email;

            $beneficiary_id = CustomerBeneficiary::create(Self::$customer_info->customer_id, $update);

            if(is_numeric($beneficiary_id))
            {
                echo json_encode("success");
            }
        }
        else
        {
            $result = $validator->errors();
            echo json_encode($result);
        }
    }
    public function postProfileUpdateReward(Request $request)
    {
        $update_customer["downline_rule"] = $request->downline_rule;
        Tbl_customer::where("customer_id", Self::$customer_info->customer_id)->update($update_customer);
        echo json_encode("success");
    }
    public function postProfileUpdatePicture(Request $request)
    {
        $customer_id = $request->customer_id;
        $input = $request->all();
        $rules = array('profile_image' => 'required|mimes:jpeg,png,gif,bmp');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } 
        else 
        {
            $file = Input::file("profile_image");
            /* SAVE THE IMAGE IN THE FOLDER */
            if ($file) 
            {
                $extension          = $file->getClientOriginalExtension();
                $filename           = str_random(15).".".$extension;
                $destinationPath    = 'uploads/'.$this->shop_info->shop_key."-".$this->shop_info->shop_id.'/ecommerce-upload';
                
                if(!File::exists($destinationPath)) 
                {
                    $create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
                }

                /* RESIZE IMAGE */
                $upload_success    = Image::make(Input::file('profile_image'))->fit(250, 250)->save($destinationPath."/".$filename);;

                /* SAVE THE IMAGE PATH IN THE DATABASE */
                $image_path = $destinationPath."/".$filename;

                if( $upload_success ) 
                {
                   $exist = Tbl_customer::where("customer_id", $customer_id)->first();
                   if (isset($exist->profile) && $exist->profile) 
                   {
                       $delete_file = $exist->profile;
                       File::delete($delete_file);
                   }

                   $update['profile'] = $image_path;
                   Tbl_customer::where("customer_id", $customer_id)->where("shop_id", $this->shop_info->shop_id)->update($update);

                   echo json_encode("success");
                } 
                else 
                {
                   echo json_encode("failed");
                }
            }
        }
    }
    public function postProfileUpdatePassword(Request $request)
    {
        $form = $request->all();

        $old = $request->old_password;
        $new = Crypt::decrypt(Tbl_customer::where("customer_id", Self::$customer_info->customer_id)->where("shop_id", $this->shop_info->shop_id)->value("password"));

        if ($old == $new) 
        {
            $validate['password'] = 'required|confirmed|min:6';
            $validator = Validator::make($form, $validate);
            
            if (!$validator->fails()) 
            {           
                $insert_customer["password"]  = Crypt::encrypt($request->password);

                Tbl_customer::where("customer_id", Self::$customer_info->customer_id)
                            ->shop(Self::$customer_info->shop_id)
                            ->update($insert_customer);

                $email = Tbl_customer::where("customer_id", Self::$customer_info->customer_id)
                            ->shop(Self::$customer_info->shop_id)
                            ->value('email');

                $pass = $request->password;

                Self::store_login_session($email,$pass);
                
                echo json_encode("success");
            }
            else
            {
                $result = $validator->errors();
                echo json_encode($result);
            }
        }
        else
        {
            $result[0] = "Old password mismatched.";
            echo json_encode($result);
        }
    }
    public function getNotification()
    {
        $data["page"] = "Notification";
        $data["_rewards"]    = MLM2::customer_rewards($this->shop_info->shop_id, Self::$customer_info->customer_id, 5);
        return (Self::load_view_for_members("member.notification", $data));
    }
    public function getGenealogy(Request $request)
    {
        if (isset(Self::$customer_info->customer_id)) 
        {
            $data["page"] = "Genealogy";
            $data['_slot'] = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->get();
            $slot = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->first();
            $data['slot_no'] = 0;
            $data['mode'] = 'sponsor';
            
            if($slot)
            {
                $data['slot_no'] = $slot->slot_no;
                $data['mode'] = $request->mode;
            }

            return (Self::load_view_for_members("member.genealogy", $data));
        }
        else
        {
            return Redirect::to("/members/login");
        }
    }
    public function getGenealogyTree(Request $request)
    {
        if (isset(Self::$customer_info->customer_id)) 
        {
            $slot_no  = $request->slot_no;
            $shop_id  = $this->shop_info->shop_id;
            $mode = $request->mode;
            $check = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->where('slot_no',$slot_no)->where('shop_id',$shop_id)->first();

            if($check)
            {
                $data = MemberSlotGenealogy::tree($shop_id, $check->slot_id, $mode);
                return Self::load_view_for_members('member.genealogy_tree', $data);            
            }
            else
            {
                die('Invalid slot!');
            }
        }
        else
        {
            die('Invalid slot!');
        }
    }
    public function getGenealogyDownline(Request $request)
    {
        $data = MemberSlotGenealogy::downline($request->x, $request->mode);
        return $data;
    }
    public function getNetwork()
    {
        if (Self::$customer_info) 
        {
            $data["page"] = "Network List";
            $data['_slot'] = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->get();

            if(request()->input("slot_no") == "")
            {
                $slot_no = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->value("slot_no");
                return Redirect::to("/members/network?slot_no=" . $slot_no);
            }
            else
            {
                $data["_tree_level"] = MLM2::get_sponsor_network_tree($this->shop_info->shop_id, request()->input("slot_no"));
                return (Self::load_view_for_members("member.network", $data));
            }
        }
        else
        {
            return Redirect::to("/members/login");
        }
    }
    public function getNetworkSlot()
    {
        $data["page"] = "Network List";
        $data['_slot'] = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->get();

        if(request()->input("slot_no") == "")
        {
            $slot_no = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->value("slot_no");
            return Redirect::to("/members/network?slot_no=" . $slot_no);
        }
        else
        {
            $data["_tree"] = MLM2::get_sponsor_network($this->shop_info->shop_id, request()->input("slot_no"), request('level'));
            return (Self::load_view_for_members("member.network_slot", $data));
        }
    }
    public function getRedeemable()
    {
        $sort_by = 0;
        $data['page'] = "Redeemable";
        $data['_redeemable'] = Tbl_item_redeemable::where("archived",0)->whereColumn("quantity",">","number_of_redeem")->get();

        $slot_info           = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->membership()->first();
        $data['slot'] = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id)->get();

        $data["_points"]     = $this->redeem_points_sum($slot_info->slot_id);
        // dd($data);
        return (Self::load_view_for_members("member.redeemable",$data));
    }
    public function getSlotPoints()
    {
        $slot = request('slot_no');
        $slot_info           = Tbl_mlm_slot::where("slot_no", $slot)->membership()->first();
        return  currency('',$this->redeem_points_sum($slot_info->slot_id));
    }
    public function postRedeemItem(Request $request)
    {

        $slot_info          = Tbl_mlm_slot::where("slot_no", $request->slot_no)->membership()->first();
        $item_redeemable_id = $request->item_id;
        $redeemable_item    = Tbl_item_redeemable::where("item_redeemable_id",$item_redeemable_id)->where("shop_id",$this->shop_info->shop_id)->first();
        if($redeemable_item)
        {
            $stock = ($redeemable_item->quantity)-($redeemable_item->number_of_redeem);
            if($stock>0)
            {
                $remaining_points = $this->redeem_points_sum($slot_info->slot_id);
                $compute_points   = $remaining_points - $redeemable_item->redeemable_points;
                if($compute_points >= 0)
                {
                    $insert["amount"]       = -1 * $redeemable_item->redeemable_points;
                    $insert["shop_id"]      = $this->shop_info->shop_id;
                    $insert["slot_id"]      = $slot_info->slot_id;
                    $insert["date_created"] = Carbon::now();
                    Tbl_item_redeemable_points::insert($insert);

                    //not yet done
                    $insert_report['slot_id'] = $slot_info->slot_id;
                    $insert_report["shop_id"] = $this->shop_info->shop_id;
                    $insert_report["amount"] = -1 * $redeemable_item->redeemable_points;
                    $insert_report['log_type'] = 'Request';
                    // you redeem <item> for <cost>. Please wait for admin's approval.
                    $insert_report['log'] = 'You redeemed '.$redeemable_item->item_name.' for '.currency("",$redeemable_item->redeemable_points)." POINTS. Please wait for admin's approval.";
                    $insert_report['date_created'] = Carbon::now();
                    $insert_report['slot_owner'] = Self::$customer_info->customer_id;
                    Tbl_item_redeemable_report::insert($insert_report);
                    Tbl_item_redeemable::where("item_redeemable_id",$item_redeemable_id)->where("shop_id",$this->shop_info->shop_id)->increment('number_of_redeem');

                    $insert_request["item_redeemable_id"]    = $redeemable_item->item_redeemable_id;
                    $insert_request["amount"]                = $redeemable_item->redeemable_points;
                    $insert_request["shop_id"]               = $this->shop_info->shop_id;
                    $insert_request["slot_id"]               = $slot_info->slot_id;
                    $insert_request["status"]                = "PENDING";
                    $insert_request["date_created"]          = Carbon::now();
                    Tbl_item_redeemable_request::insert($insert_request);
                    $response='success';
                    return Redirect::back()->with("response",$response);
                }
            }
            else
            {
                $response = "error";
                return Redirect::back()->with('response',$response);
            }
            
        }
        // return (Self::load_view_for_members("member.redeemable",$data));
    }
    public function redeem_points_sum($slot_id)
    {
        $points = Tbl_mlm_slot_points_log::where(function($query)
        {
            $query->where('points_log_complan','DIRECT_POINTS');
            $query->orWhere('points_log_complan','INDIRECT_POINTS');
            $query->orWhere('points_log_complan','REPURCHASE_POINTS');
            $query->orWhere('points_log_complan','UNILEVEL_REPURCHASE_POINTS');
            $query->orWhere('points_log_complan','INITIAL_POINTS');
        })->where("points_log_slot",$slot_id)->sum("points_log_points");
        $used_points = Tbl_item_redeemable_points::where("slot_id",$slot_id)->sum("amount");

        return $points + $used_points;
    }
    public function getRedeemHistory()
    {
        $sort_by = 0;
        $data['page'] = "Redeem History";
        $data['redeem_history'] = Tbl_item_redeemable_report::Slot()->where('tbl_item_redeemable_report.slot_owner',Self::$customer_info->customer_id)->paginate(10);
        // dd($data['redeem_history']);
        return (Self::load_view_for_members("member.redeem_history",$data));
    }
    public function getCodevault()
    {
        $data['page'] = "Code Vault";
        $query = Tbl_transaction_list::CodeVaultTransaction();

        $data['customer_id'] = Self::$customer_info->customer_id;

        $q = $query->where("tbl_transaction.transaction_reference_id",Self::$customer_info->customer_id);
        $data['_codes'] = $q->where("transaction_reference_table","tbl_customer")->where("item_in_use","unused")->where("item_type_id",5)->where("tbl_transaction.shop_id",$this->shop_info->shop_id)->paginate(10);
        return (Self::load_view_for_members("member.code-vault",$data));
    }
    public function getUsecode()
    {
        $data['page'] = "Use Code";
        $data['pin'] = request("pin");
        $data['activation'] = request("activation");
        return view("member.use_code",$data);
    }
    public function getCaptcha()
    {
        // unitywealth100
        if($this->shop_info->shop_id == 90)
        {
            $point = Tbl_recaptcha_setting::where('shop_id',$this->shop_info->shop_id)->first();
            if($point)
            {
                $explode_schedule = explode("-", $point->schedule);

                if (isset($explode_schedule[0]) && isset($explode_schedule[1])) 
                {
                    $current_time = date('h:i A');
                    $sunrise      = $explode_schedule[0];
                    $sunset       = $explode_schedule[1];
                    $date1        = \DateTime::createFromFormat('H:i a', $current_time);
                    $date2        = \DateTime::createFromFormat('H:i a', $sunrise);
                    $date3        = \DateTime::createFromFormat('H:i a', $sunset);

                    if ($date1 > $date2 && $date1 < $date3)
                    {
                        // Allow
                    }
                    else
                    {
                        dd('You can only access this module every ' . str_replace("-", " to ", $point->schedule));
                    }
                }

                $data['page'] = "Captcha";
                $data['slot'] = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id)->get();
                return view('member.captcha',$data);
            }
            else
            {
                dd("Some error occurred. Please contact the administrator.");
            }
        }
        
    }
    public function postSubmitcaptcha(Request $request)
    {
        $point = Tbl_recaptcha_setting::where('shop_id',$this->shop_info->shop_id)->first();
        if($point)
        {
            $point = $point->point;
        }
        else
        {
            $point = 0;
        }
        $insert['shop_id']                  = $this->shop_info->shop_id;
        $insert['wallet_log_slot']          = Tbl_mlm_slot::where('slot_no',$request->slot_no)->first()->slot_id;
        $insert['wallet_log_date_created']  = Carbon::now();
        $insert['wallet_log_amount']        = $point;
        $insert['wallet_log_plan']          = "RECAPTCHA";
        $insert['wallet_log_claimbale_on']  = Carbon::now();
        $insert['encashment_process_type']  = 0;

        $pool_amount        = Tbl_recaptcha_pool_amount::where('shop_id',$this->shop_info->shop_id)->sum('amount');
        $acquired_points    = Tbl_mlm_slot_wallet_log::where('wallet_log_plan','RECAPTCHA')
                                ->where('shop_id',$this->shop_info->shop_id)
                                ->sum('wallet_log_amount');
        $remaining_points   = $pool_amount-$acquired_points;
        if($remaining_points>=$point)
        {
            if (Session::get("captcha_limit")) 
            {
                $timeFirst  = strtotime(Session::get("captcha_limit"));
                $timeSecond = strtotime(date('Y-m-d H:i:s'));
                $differenceInSeconds = $timeSecond - $timeFirst;

                if ($differenceInSeconds >= 20) 
                {
                    $query = Tbl_mlm_slot_wallet_log::insert($insert);

                    if($query)
                    {
                        $response = 'success';

                        Session::put("captcha_limit", date('Y-m-d H:i:s'));
                    }
                    else
                    {
                        $response = 'error';
                    }
                }
                else
                {
                    $response = 'error';
                }
            }
            else
            {
                $query = Tbl_mlm_slot_wallet_log::insert($insert);

                if($query)
                {
                    $response = 'success';

                    Session::put("captcha_limit", date('Y-m-d H:i:s'));
                }
                else
                {
                    $response = 'error';
                }
            }
        }
        else
        {
            $response = 'no_points';
        }

        
        return Redirect::back()->with('response',$response);
    }
    public function getReport()
    {
        if (isset($this->shop_info->shop_id) && isset(Self::$customer_info->customer_id)) 
        {
            $data["page"]               = "Report";

            if(request("sort_by"))
            {
                $sort_by = request("sort_by");
            }
            else
            {
                $sort_by = 0;
            }

            $data["_rewards"]           = MLM2::customer_rewards($this->shop_info->shop_id, Self::$customer_info->customer_id, 0,$sort_by);
            $data["_codes"]             = MLM2::check_purchased_code($this->shop_info->shop_id, Self::$customer_info->customer_id);
            
            return (Self::load_view_for_members("member.report", $data));
        }
        else
        {
            return Redirect::to("/members/login");
        }
    }
    public function getReportPoints()
    {
        $data["page"]               = "Report";
        if(request("sort_by"))
        {
            $sort_by = request("sort_by");
        }
        else
        {
            $sort_by = 0;
        }

        $data["_rewards_points"]    = MLM2::customer_rewards_points($this->shop_info->shop_id, Self::$customer_info->customer_id, 0, $sort_by);

        // return MLM2::customer_rewards_points($this->shop_info->shop_id, Self::$customer_info->customer_id, 0, $sort_by)->first();
        
        return (Self::load_view_for_members("member.report_points", $data));
    }
    public function getLeadList()
    {
        if (Self::$customer_info) 
        {
            $data["page"]       = "Lead List";
            $shop_id            = $this->shop_info->shop_id;
            $_slot              = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->get();
            
            $query              = Tbl_customer::where("shop_id", $shop_id);

            if(count($_slot) > 0)
            {
                $query->where(function($q) use ($_slot)
                {
                    foreach($_slot as $slot)
                    {
                        $q->orWhere("customer_lead", $slot->slot_id);
                    }
                });
            }
            else
            {
                $query->where("customer_lead", "-1");
            }



            $_lead      = $query->get();

            foreach($_lead as $key => $lead)
            {
                $slot_owned = Tbl_mlm_slot::where("slot_owner", $lead->customer_id)->first();
                
                if($slot_owned)
                {
                    $_lead[$key]->slot_owned = $slot_owned->slot_no;
                }
                else
                {
                    $_lead[$key]->slot_owned = "NONE";
                }

                $_lead[$key]->date_created = date("F d, Y", strtotime($lead->created_at)) . "<br>" . date("h:i A", strtotime($lead->created_at));
            }

            $data["_lead"] = $_lead;

            return (Self::load_view_for_members("member.lead", $data)); 
        }
        else
        {
            return Redirect::to("/members/login");
        }
    }
    public function getWalletLogs()
    {
        $data["page"] = "Wallet Logs";
        return (Self::load_view_for_members("member.wallet_logs", $data));
    }
    public function getWalletEncashment()
    {
        if (isset($this->shop_info->shop_id) && isset(Self::$customer_info->customer_id)) 
        {
            $data["page"]           = "Wallet Encashment";
            $data["_encashment"]    = MLM2::customer_payout($this->shop_info->shop_id, Self::$customer_info->customer_id, 0);
            $total_payout           = MLM2::customer_total_payout(Self::$customer_info->customer_id);
            $data["total_payout"]   = Currency::format($total_payout);
            return (Self::load_view_for_members("member.wallet_encashment", $data));
        }
        else
        {
            return Redirect::to("/members/login");
        }
    }
    public function getWalletTransfer()
    {
        // dd("This page is under maintenance");
        $data['page'] = "Wallet Transfer";
        $data['customer_id'] = Self::$customer_info->customer_id;
        // $slot_no = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id)->get();
        // $id = $slot_no->slot_id;
        // $data['transfer_history'] = Tbl_mlm_slot_wallet_log::where("wallet_log_plan","wallet_transfer")->where("wallet_log_slot",$id)->paginate(8);
        $data['transfer_history'] = Tbl_mlm_slot_wallet_log::Slot()->where("wallet_log_plan","wallet_transfer")->where("slot_owner",Self::$customer_info->customer_id)->orderBy('wallet_log_date_created','DESC')->paginate(8);
        return (Self::load_view_for_members("member.wallet_transfer", $data));
    }
    public function postWalletTransfer(Request $request)
    {
        // dd("This page is under maintenance");

        $data['slot'] = $request->slot;
        // check if slot is belong to this user
        $data['amount'] = $request->amount;
        // validate if amount+transfer fee is greater than validated slot
        $data['recipient'] = $request->recipient;
        // check if the recipient is not himself 

        $rules['slot'] = 'required';
        $rules['amount'] = 'required';
        $rules['recipient'] = 'required';

        $validator = Validator::make($data,$rules);

        $response['status_message'] = "";

        if($validator->fails())
        {
            $response["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $response["status_message"] .= $message;
            }
        }
        else
        {
            $count = count(Tbl_mlm_slot::where("slot_no",$request->recipient)->get());
            if($count<1)
            {
                $response['call_function'] = "error";
            }
            else
            {
                $response['call_function'] = "confirm_transfer";
            }
        }

        return json_encode($response);
    }
    public function getSendTransfer()
    {
        $shop_id = $this->shop_info->shop_id;
        $transaction_fee =  -1*Tbl_mlm_slot_wallet_log_refill_settings::where("shop_id",$shop_id)->first()->wallet_log_refill_settings_transfer_processing_fee;

        $isSlotValid = false;
        $isRecipientValid = false;
        $isAmountValid = false;

        $recipient_slot_no = request("recipient");
        $sender_slot_no = request("slot");
        $amount = request("amount");

        $logged_in_account = Self::$customer_info->customer_id;

        $slots = Tbl_mlm_slot::where('slot_owner',$logged_in_account)->get();
        foreach ($slots as $validSlot) {
            if($validSlot->slot_no == $sender_slot_no)
            {
                $isSlotValid = true;
            }
        }


        if($sender_slot_no != $recipient_slot_no)
        {
            $isRecipientValid = true;
        }

        $slot_no = $sender_slot_no;
        $query = Tbl_mlm_slot::where('slot_no',$slot_no)->first();
        $wallet = 0;
        if(count($query))
        {
            $slot_id = $query->slot_id;
            $q = Tbl_mlm_slot_wallet_log::where('wallet_log_slot',$slot_id)->get();
            $counter = count($q);
            if($counter>0)
            {
                $current=0;
                foreach($q as $a)
                {
                    $current+=$a->wallet_log_amount;
                }
                $wallet=$current;
            }
        }
        
        $minAmount = $amount+(-1*$transaction_fee);
        if($wallet>=$minAmount)
        {
            $isAmountValid = true;
        }



        // $log_slot = Tbl_mlm_slot::where('slot_no',$recipient_slot_no)->first()->slot_id;
        // $log_slot_sponsor = Tbl_mlm_slot::where('slot_no',$sender_slot_no)->first()->slot_id;
        

        if(!$isSlotValid)
        {
            $response = "error_slot";
        }
        else if(!$isAmountValid)
        {
            $response = "error_amount";
        }
        else if(!$isRecipientValid)
        {
            $response = "error_recipient";
        }
        else
        {
            //transfer
            $arry_log['wallet_log_slot']         = Tbl_mlm_slot::where('slot_no',$sender_slot_no)->first()->slot_id;
            $arry_log['shop_id']                 = $shop_id;
            $arry_log['wallet_log_slot_sponsor'] = Tbl_mlm_slot::where('slot_no',$recipient_slot_no)->first()->slot_id;
            $arry_log['wallet_log_details']      = Mlm_slot_log::log_constructor_wallet_transfer("transfer",$amount,$recipient_slot_no);
            $arry_log['wallet_log_amount']       = ($amount*-1);
            $arry_log['wallet_log_plan']         = "WALLET_TRANSFER";
            $arry_log['wallet_log_status']       = "released";   // tatanong ko pa
            $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
            Mlm_slot_log::slot_array($arry_log);

            //recieved 
            $arry_log['wallet_log_slot']         = Tbl_mlm_slot::where('slot_no',$recipient_slot_no)->first()->slot_id;
            $arry_log['shop_id']                 = $shop_id;
            $arry_log['wallet_log_slot_sponsor'] = Tbl_mlm_slot::where('slot_no',$sender_slot_no)->first()->slot_id;
            $arry_log['wallet_log_details']      = Mlm_slot_log::log_constructor_wallet_transfer("recieved",$amount,$sender_slot_no);
            $arry_log['wallet_log_amount']       = $amount;
            $arry_log['wallet_log_plan']         = "WALLET_TRANSFER";
            $arry_log['wallet_log_status']       = "released";   // tatanong ko pa
            $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
            Mlm_slot_log::slot_array($arry_log);

            // fee
            $arry_log['wallet_log_slot']         = Tbl_mlm_slot::where('slot_no',$sender_slot_no)->first()->slot_id;
            $arry_log['shop_id']                 = $shop_id;
            $arry_log['wallet_log_slot_sponsor'] = Tbl_mlm_slot::where('slot_no',$recipient_slot_no)->first()->slot_id;
            $arry_log['wallet_log_details']      = Mlm_slot_log::log_constructor_wallet_transfer("fee",$transaction_fee,$recipient_slot_no);
            $arry_log['wallet_log_amount']       = $transaction_fee;
            $arry_log['wallet_log_plan']         = "WALLET_TRANSFER";
            $arry_log['wallet_log_status']       = "released";   // tatanong ko pa
            $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
            Mlm_slot_log::slot_array($arry_log);

            $response = "success";

        }
        return $response;
        
    }
    public function getWalletTransferRequest()
    {
        $data['page'] = "Request Wallet Transfer";
        $data['customer_id'] = Self::$customer_info->customer_id;
        $data['slot_owner'] = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id)->get();
        return Self::load_view_for_members('member.wallet_transfer_request', $data);
    }
    public function getWalletTransferPrediction()
    {
        $data['page'] = "";

        $slot_no = request('keyword');
        if($slot_no!="")
        {
            $data['names'] = Tbl_customer::search()->where('tbl_mlm_slot.slot_no',"LIKE",$slot_no."%")->limit(3)->get();
        }
        else
        {
            $data['names'] = null;
        }
        return Self::load_view_for_members('member.wallet_transfer_prediction', $data);
    }
    public function getCurrentWallet()
    {
        $slot_no = request('slot_owner');
        $query = Tbl_mlm_slot::where('slot_no',$slot_no)->first();
        $slot_id = $query->slot_id;
        $q = Tbl_mlm_slot_wallet_log::where('wallet_log_slot',$slot_id)->get();
        $counter = count($q);
        $wallet = 0;
        if($counter>0)
        {
            $current=0;
            foreach($q as $amount)
            {
                $current+=$amount->wallet_log_amount;
            }
            $wallet=$current;
        }
        return currency("P",$wallet);
    }
    public function getWalletTransferFee()
    {
        $shop_id = $this->shop_info->shop_id;
        $transaction_fee =  Tbl_mlm_slot_wallet_log_refill_settings::where("shop_id",$shop_id)->first()->wallet_log_refill_settings_transfer_processing_fee;
        return currency('P',$transaction_fee);
    }
    public function getWalletRefill()
    {
        // dd("This page is under maintenance");
        $data['page'] = 'Wallet Refill';
        $data['slot_owner'] = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id)->get();
        return Self::load_view_for_members('member.wallet_refill', $data);
    }
    public function getWalletRefillRequest()
    {
        // dd("This page is under maintenance");
        $shop_id = $this->shop_info->shop_id;
        $data['page'] = "Request Wallet Refill";
        $data['slot_owner'] = Tbl_mlm_slot::where("slot_owner",Self::$customer_info->customer_id)->get();
        $data['fee'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id',$shop_id)->first()->wallet_log_refill_settings_processings_fee;
        return Self::load_view_for_members('member.wallet_refill_request', $data);
    }
    public function postWalletRefillRequest(Request $request)
    {
        // dd("This page is under maintenance");
        $path_prefix = 'http://digimaweb.solutions/public/uploadthirdparty/';
        $path ="";
        if($request->hasFile('attachment'))
        {
            $path = Storage::putFile('payment-proof', $request->file('attachment'));
        }
        $shop_id = $this->shop_info->shop_id;
        $fee = Tbl_mlm_slot_wallet_log_refill_settings::where("shop_id",$shop_id)->first()->wallet_log_refill_settings_processings_fee;
        $amount = $request->amount;

        $logged_in_account = Self::$customer_info->customer_id;
        $isSlotValid = false;
        $slots = Tbl_mlm_slot::where('slot_owner',$logged_in_account)->get();
        foreach ($slots as $validSlot) {
            if($validSlot->slot_no == $request->slot)
            {
                $isSlotValid = true;
            }
        }

        if($isSlotValid)
        {
            $slot_id = Tbl_mlm_slot::where("slot_no",$request->slot)->first()->slot_id;

            $insert['wallet_log_refill_date'] = Carbon::now();
            $insert['wallet_log_refill_amount'] = $amount;
            $insert['wallet_log_refill_processing_fee'] = $fee;
            $insert['wallet_log_refill_amount_paid'] = $amount+$fee;
            $insert['wallet_log_refill_approved'] = 0;
            $insert['wallet_log_refill_remarks'] = $request->remarks;
            if($path!="")
            {
                $insert['wallet_log_refill_attachment'] = $path_prefix.$path;
            }
            $insert['shop_id'] = $shop_id;
            $insert['slot_id'] = $slot_id;
            if($query = Tbl_mlm_slot_wallet_log_refill::insert($insert))
            {
                $response = "success";
            }
            else
            {
                $response = "error";
            }
        }
        else
        {
            $response = "invalid_slot";
        }

        
        return Redirect::back()->with("response",$response);
    }
    public function getUploadAttachment()
    {
        $shop_id = $this->shop_info->shop_id;
        $data['page'] = "Upload Attachment";
        $data['id'] = request("id");
        return Self::load_view_for_members('member.wallet_refill_upload_attachment', $data);
    }
    public function postUploadAttachment(Request $request)
    {
        $path_prefix = 'http://digimaweb.solutions/public/uploadthirdparty/';
        $path ="";
        $response = 'error_upload';
        if($request->hasFile('attachment'))
        {
            if($path = Storage::putFile('payment-proof', $request->file('attachment')))
                {
                    $update['wallet_log_refill_attachment'] = $path_prefix.$path;
                    if(Tbl_mlm_slot_wallet_log_refill::where('wallet_log_refill_id',$request->id)->update($update))
                        {
                            $response = 'success_upload';
                        }
                }
        }

        return Redirect::back()->with("response",$response);
    }
    public function getWalletRefillTable()
    {
        // dd("This page is under maintenance");
        $data['page'] = "Wallet Refill Table";
        $status = request("activetab");
        $slot_no = request("slotno");
        $slot_id = Tbl_mlm_slot::where('slot_no',$slot_no)->first()->slot_id;
        $data['refills'] = Tbl_mlm_slot_wallet_log_refill::where("slot_id",$slot_id)->where('wallet_log_refill_approved',$status)->get();
        $data['status'] = $status;
        return Self::load_view_for_members('member.wallet_refill_table', $data);
    }
    public function getSlot()
    {
        $data["page"] = "Slot";
        return (Self::load_view_for_members("member.slot", $data));
    }
    public function getSlots()
    {
        $data['page'] = "YOUR SLOT(S)";
        $data['slots'] = Tbl_mlm_slot::where('slot_owner',Self::$customer_info->customer_id)->get();
        return view('member.userslots',$data);
    }
    public function getEonCard()
    {
        $data["page"] = "Eon Card";
        return (Self::load_view_for_members("member.eon_card", $data));
    }
    public function getOrder()
    {
        $data["page"] = "Orders";
        $shop_id = $this->shop_info->shop_id;
        Transaction::get_transaction_filter_customer(Self::$customer_info->customer_id);
        $data["_order"] = Transaction::get_transaction_list($shop_id, 'ORDER', '', 20);
        return (Self::load_view_for_members("member.order", $data));
    }
    public function getOrderDetails(Request $request, $transaction_list_id)
    {
        $data['shop_key'] = strtoupper($this->shop_info->shop_key);
        $data['shop_address'] = ucwords($this->shop_info->shop_street_address.' '.$this->shop_info->shop_city.', '.$this->shop_info->shop_zip);
        Transaction::get_transaction_filter_customer(Self::$customer_info->customer_id);
        $data['list'] = Tbl_transaction_list::transaction()->where('transaction_list_id',$transaction_list_id);
        
        if(session('get_transaction_filter_customer_id'))
        {
            $data['list']->where('transaction_reference_table', 'tbl_customer')->where('tbl_transaction.transaction_reference_id', session('get_transaction_filter_customer_id'));
            $data['list']->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id');
        }
        $data['list'] = $data['list']->first();
        $data['_item'] = Tbl_transaction_item::where('transaction_list_id',$transaction_list_id)->get();
        $data['customer_name'] = Transaction::getCustomerNameTransaction($data['list']->transaction_id);
        
        return (Self::load_view_for_members("member.order_details", $data));
    }
    public function getWishlist()
    {
        $data["page"] = "Wishlist";
        return (Self::load_view_for_members("member.wishlist", $data));
    }
    public function getCheckout()
    {
        $data["page"]       = "Checkout";
        $shop_id            = $this->shop_info->shop_id;
        $data["_payment"]   = $_payment = Payment::get_list($shop_id);
        $data["_locale"]    = Tbl_locale::where("locale_parent", 0)->orderBy("locale_name", "asc")->get();
        $data["cart"]       = Cart2::get_cart_info(isset(Self::$customer_info->customer_id) ? Self::$customer_info->customer_id : null);
        
        if(!Self::$customer_info)
        {
            $store["checkout_after_register"] = true;
            session($store);
            return redirect("/members/register");
        }
        else
        {
            return (Self::load_view_for_members("member.checkout", $data)); 
        }

        
    }
    public function postCheckout()
    {
        $shop_id  = $this->shop_info->shop_id;
        $warehouse_id = Warehouse2::get_main_warehouse($shop_id);
        $cart = Cart2::get_cart_info(isset(Self::$customer_info->customer_id) ? Self::$customer_info->customer_id : null);
        $validate = null;
        if($cart)
        {   
            foreach ($cart["_item"] as $key => $value)
            {
                $item_type = Item::get_item_type($value->item_id);
                if($item_type == 1 || $item_type == 5)
                {
                    $validate .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value->item_id, $value->quantity,'Consume');
                }
            }
        }

        if(!$validate)
        {
            /* Update Address */
            $exist_address = Tbl_customer_address::where("customer_id", Self::$customer_info->customer_id)->first();
            if ($exist_address) 
            {
                $update["customer_street"] = request('customer_street');
                Tbl_customer_address::where("customer_id", Self::$customer_info->customer_id)->update($update);
            }
            else
            {
                $insert["customer_street"] = request('customer_street');
                $insert["customer_id"] = Self::$customer_info->customer_id;
                $insert["country_id"] = 420;
                $insert["purpose"] = "billing";
                $insert["archived"] = 0;
                Tbl_customer_address::insert($insert);
                $insert["purpose"] = "shipping";
                Tbl_customer_address::insert($insert);
            }
            $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
            $return = null;
            $validate = null;


            

            $method = request('method');
            $method_id = 0;
            if($shop_id == 55)
            {
                $method                                             = "manual1";
                $method_id                                          = request('method');            
            }

            $transaction_new["transaction_reference_table"]     = "tbl_customer";
            $transaction_new["transaction_reference_id"]        = Self::$customer_info->customer_id;
            $transaction_type                                   = "ORDER";
            $transaction_date                                   = Carbon::now();
            
            Transaction::create_set_method($method);
            Transaction::create_set_method_id($method_id);
            $transaction_list_id                                = Transaction::create($shop_id, $transaction_new, $transaction_type, $transaction_date, "-");

            if(is_numeric($transaction_list_id))
            {
                $method_id  = $method_id;
                $success    = "/members?success=1"; //redirect if payment success
                $failed     = "/members?failed=1"; //redirect if payment failed
                $error      = Payment::payment_redirect($shop_id, $method, $transaction_list_id, $success, $failed, false, $method_id);
            }
            else
            {
                return Redirect::to("/members/checkout")->with("error", "Your cart is empty.");
            }            
        }
        else
        {
            return Redirect::to("/members/checkout")->with("error", $validate);
        }
    }
    public function getNonMember()
    {
        $data["page"] = "NonMember";
        return (Self::load_view_for_members("member.nonmember", $data));
    }
    public function getTest($method)
    {
        $shop_id    = $this->shop_info->shop_id; //tbl_shop
        $key        = $method; //link reference name
        $success    = "/members?success=1"; //redirect if payment success
        $failed     = "/members?failed=1"; //redirect if payment failed
        $debug      = true;

        $error = Payment::payment_redirect($shop_id, $key, $success, $failed, $debug);
    }
    public function getVmoney()
    {
        $slot = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->first();
        $data["page"] = "V-Money";

        if ($slot) 
        {
            $data["wallet"] = Mlm_slot_log::get_sum_wallet(isset($slot->slot_id) ? $slot->slot_id : 0);
        }
        else
        {
            $data["wallet"] = 0;
        }

        $data["minimum"] = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value : 0;
        $data["percent"] = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value : 0;
        $data["fixed"] = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value : 0;

        return (Self::logged_in_member_only() ? Self::logged_in_member_only() : view("member.vmoney", $data));
    }
    public function postVmoney(Request $request)
    {
        $slot_get = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->first();
        $slot_id  = isset($slot_get->slot_id) ? $slot_get->slot_id : 0;

        if ($slot_id) 
        {
            /* Fee */
            $current_wallet     = $request->wallet_amount;
            $fixed              = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_fixed_fee")->first()->settings_value : 0;
            $percent            = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_percent_fee")->first()->settings_value : 0;
            $percent_value      = ($percent / 100) * $current_wallet;
            $convenience_fee    = $fixed + $percent_value; 
            $total_fee          = $current_wallet + $convenience_fee;
            $slot               = DB::table("tbl_mlm_slot")->where("slot_id", $slot_id)->first();
            $wallet             = Mlm_slot_log::get_sum_wallet($slot->slot_id);
            $minimum_encashment = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_minimum_encashment")->first()->settings_value : 0;
            
            if (isset($slot) && $slot) 
            {
                if ($minimum_encashment <= $request->wallet_amount) 
                {
                    if ($wallet > $request->wallet_amount) 
                    {
                        if ($request->vmoney_email) 
                        {
                            if($request->wallet_amount)
                            {   
                                /* API */
                                $post = 'mxtransfer.svc';
                                /* $environment = isset(DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_environment")->first()->settings_value) ? DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "vmoney_environment")->first()->settings_value : 0; */
                                if (get_domain() == "philtechglobalinc.com") 
                                {
                                    $environment = 1;
                                }
                                else
                                {
                                    $environment = 0;
                                }

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

                                $pass["recipient"] = $request->vmoney_email; // Recipient's email address
                                $pass["merchantRef"] = Self::$customer_info->customer_id . time(); // Merchant reference number
                                $pass["amount"] = $request->wallet_amount; // Amount of the transaction
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
                                    $logs["vmoney_wallet_logs_email"] = $request->vmoney_email;
                                    $logs["vmoney_wallet_logs_amount"] = $current_wallet;
                                    $logs["customer_id"] = Self::$customer_info->customer_id;
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
        
            return Redirect::back()->with("result", $data_a);
        }
    }

    /* AJAX */
    public function postVerifySponsor(Request $request)
    {
        $shop_id                = $this->shop_info->shop_id;
        $sponsor                = MLM2::verify_sponsor($shop_id, $request->verify_sponsor);
        if(!$sponsor)
        {
            if($request->verify_sponsor == "")
            {
                $return = "<div class='error-message'>The sponsor you entered is <b>BLANK</b>.</div>";
            }
            else
            {
                $return = "<div class='error-message'>We can't find sponsor \"<b>" . $request->verify_sponsor . "</b>\".<br>Please check carefully if you have the right details.</div>";
            }
        }
        else
        {
            $sponsor_have_placement = MLM2::check_sponsor_have_placement($shop_id,$sponsor->slot_id);
            
            if($sponsor_have_placement == 0)
            {
                $return = "<div class='error-message'>Sponsor \"<b>" . $request->verify_sponsor . "</b>\".<br>should have a placement first.</div>";
            }
            else
            {    
                $data["page"]                   = "CARD";
                $data["sponsor"]                = $sponsor; 
                $data["sponsor_customer"]       = Customer::get_info($shop_id, $sponsor->slot_owner);
                $data["sponsor_profile_image"]  = $data["sponsor_customer"]->profile == "" ? "/themes/brown/img/user-placeholder.png" : $data["sponsor_customer"]->profile;
                
                $store["sponsor"] = $sponsor->slot_no;
                
                session($store);

                $return = (Self::load_view_for_members("member.card", $data));
            }
        }

        return $return;
    }
    public function postVerifyCode(Request $request)
    {
        $shop_id                                = $this->shop_info->shop_id;
        $validate["pin"]                        = ["required", "string", "alpha_dash"];
        $validate["activation"]                 = ["required", "string", "alpha_dash"];
        $validator                              = Validator::make($request->all(), $validate);

        $message = "";

        if($validator->fails())
        {
            foreach($validator->errors()->all() as $error)
            {
                $message .= "<div>" . $error . "</div>";
            }
        }
        else
        {
            $activation             = request("activation");
            $pin                    = request("pin");
            $check_membership_code  = MLM2::check_membership_code($shop_id, $pin, $activation);

            if(!$check_membership_code)
            {
                $message = "Invalid PIN / ACTIVATION!";
            }
            else
            {
                if($check_membership_code->mlm_slot_id_created != "")
                {
                    $message = "PIN / ACTIVATION ALREADY USED";
                }
                else
                {
                    $store["temp_pin"] = $pin;
                    $store["temp_activation"] = $activation;
                    session($store);
                }
            }
        }

        echo $message;
    }

    public function code_verification()
    {
        $shop_id                    = $this->shop_info->shop_id;
        $data["sponsor"]            = session('sponsor');
        $data["sponsor"]            = $sponsor = MLM2::verify_sponsor($shop_id, $data["sponsor"]);
        $data["sponsor_customer"]   = Customer::get_info($shop_id, $sponsor->slot_owner);
        $data["pin"]                = session('temp_pin');
        $data["activation"]         = session('temp_activation');
        $data["membership_code"]    = MLM2::check_membership_code($shop_id, $data["pin"], $data["activation"]);

        if($data["sponsor"] && $data["membership_code"])
        {
            if($data["membership_code"]->mlm_slot_id_created != "")
            {
                return false;
            }
            else
            {
                return $data;
            }
        }
        else
        {
            return false;
        }
    }
    public function postVerifySlotPlacement(Request $request)
    {
        $shop_id         = $this->shop_info->shop_id;
        $slot_id         = $request->slot_id;
        $slot_placement  = $request->slot_placement;
        $slot_position   = $request->slot_position;

        $slot_placement  = Tbl_mlm_slot::where("slot_no",$slot_placement)->where("shop_id",$shop_id)->first() ? Tbl_mlm_slot::where("slot_no",$slot_placement)->where("shop_id",$shop_id)->first()->slot_id : null;


        $data            = $this->check_placement($slot_id,$slot_placement,$slot_position);
        $procceed        = $data["procceed"];
        $message         = $data["message"];

        if($procceed == 1)
        {
            echo json_encode('success');
        }
        else
        {
            echo json_encode($message); 
        }
    }
    public function getFinalVerifyPlacement(Request $request)
    {
        $shop_id         = $this->shop_info->shop_id;
        $slot_id         = $request->slot_id;
        $slot_placement  = $request->slot_placement;
        $slot_position   = $request->slot_position;

        $slot_placement  = Tbl_mlm_slot::where("slot_no",$slot_placement)->where("shop_id",$shop_id)->first() ? Tbl_mlm_slot::where("slot_no",$slot_placement)->where("shop_id",$shop_id)->first()->slot_id : null;

        $data            = $this->check_placement($slot_id,$slot_placement,$slot_position);
        $procceed        = $data["procceed"];
        
        if($procceed == 1)
        {
            return Self::load_view_for_members('member2.final_verification_placement', $data);
        }
    }    
    public function postFinalVerifyPlacement(Request $request)
    {
        $shop_id         = $this->shop_info->shop_id;
        $slot_id         = $request->slot_id;
        $slot_placement  = $request->slot_placement;
        $slot_position   = $request->slot_position;
        $customer_id     = Self::$customer_info->customer_id;
        $data            = $this->check_placement($slot_id,$slot_placement,$slot_position);
        $procceed        = $data["procceed"];
        
        if($procceed == 1)
        {
            $return = MLM2::matrix_position($shop_id, $slot_id, $slot_placement, $slot_position);
            
            if($return == "success")
            {
                $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
                
                Mlm_tree::insert_tree_sponsor($slot_info_e, $slot_info_e, 1); 
                Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
                MLM2::entry($shop_id,$slot_id);
                
                echo json_encode("success");
            }
            else if($return == "Placement Error")
            {
               echo json_encode("Target placement cannot be used.");
            }
            else
            {
               echo json_encode($return);  
            }
        }
        else
        {
            echo json_encode("Placement does not exists.");
        }
    }
    public function getEnterCode()
    {
        $data["page"] = "Enter Sponsor";
        $data["message"] = "Enter <b>Slot Code</b> of your <b>Sponsor</b>";
        return Self::load_view_for_members('member2.enter_code', $data);
    }
    public function getEnterSponsor()
    {
        $data["page"] = "Enter Code";
        $data["message"] = "Enter <b>Slot Code</b> of your <b>Sponsor</b>";
        $data["lock_sponsor"] = false;
        
        if(!$this->mlm_member && Self::$customer_info->customer_lead != "")
        {
            $sponsor_no = Tbl_mlm_slot::where("slot_id", Self::$customer_info->customer_lead)->value("slot_no");
            $data["lock_sponsor"] = $sponsor_no;
        }
        
        return Self::load_view_for_members('member2.enter_sponsor', $data);
    }
    public function getEnterPlacement()
    {
        if (Self::$customer_info) 
        {
            $data["page"] = "Enter Code";
            $data["message"] = "Enter <b>Slot Code</b> of your <b>Sponsor</b>";
            

            $slot_id            = Crypt::decrypt(request("slot_no"));
            $key                = request("key");
            $data["slot_info"]  = $slot_info = Tbl_mlm_slot::where("slot_id", $slot_id)->customer()->first();

            if($slot_info->slot_owner == Self::$customer_info->customer_id)
            {
                $data["iamowner"] = true;
            }
            else
            {
                $data["iamowner"] = false;
            }

            if(md5($slot_info->slot_id . $slot_info->slot_no) == $key)
            {
                return Self::load_view_for_members('member2.enter_placement', $data);
            }
            else
            {
                return "ERROR OCCURRED";
            }
        }
        else
        {
            return "ERROR OCCURRED";
        }
    }
    public function getFinalVerify()
    {
        $data = $this->code_verification();

        if($data)
        {
            return Self::load_view_for_members('member2.final_verify', $data);
        }
    }
    public function generate_slot_no_based_on_name($first_name, $last_name)
    {
        if($this->shop_theme == "3xcell")
        {
            $name = "3X";

            $count_exist      = 1;
            $loop             = 1;
            $return           = "";
            $count_exist_slot = Tbl_mlm_slot::where("shop_id",$this->shop_info->shop_id)->count();
            while($count_exist != 0)
            {
                if($loop >= 999999 || $count_exist_slot >= 999999)
                {
                    $suffix_number  = rand(999999,$loop);
                    $return         = $name . $suffix_number;
                }
                else
                {
                    $return         = $name . rand(0,9). rand(0,9). rand(0,9). rand(0,9). rand(0,9). rand(0,9);
                }

                $count_exist    = Tbl_mlm_slot::where("shop_id",$this->shop_info->shop_id)->where("slot_no", $return)->count();
                $loop++;
            }
            
            return $return;
        }
        else if($this->shop_theme == "brown")
        {
            $name = strtoupper(substr($first_name, 0, 3));

            $count_exist      = 1;
            $loop             = 1;
            $return           = "";
            while($count_exist != 0)
            {
                if($loop >= 9999)
                {
                    $suffix_number  = rand(9999,$loop);
                    $return         = $name . $suffix_number;
                }
                else
                {
                    $return         = $name . rand(0,9). rand(0,9). rand(0,9). rand(0,9);
                }

                $count_exist    = Tbl_mlm_slot::where("shop_id",$this->shop_info->shop_id)->where("slot_no", $return)->count();
                $loop++;
            }
            
            return $return;
        }
        else
        {  
            $name = $first_name . substr($last_name, 0, 1);
            $name = preg_replace("/[^A-Za-z0-9]/", "", $name);
            $name = strtolower($name);

            $count_exist = 1;
            $loop = 1;
            $return = "";

            while($count_exist != 0)
            {
                $suffix_number  = str_pad($loop, 2, '0', STR_PAD_LEFT);
                $return         = $name . $suffix_number;
                $count_exist    = Tbl_mlm_slot::where("slot_no", $return)->count();
                $loop++;
            }
            
            return $return;
        }
    }    
    public function postFinalVerify()
    {
        $data = $this->code_verification();

        if($data)
        {
            $shop_id        = $this->shop_info->shop_id;
            $customer_id    = Self::$customer_info->customer_id;
            $membership_id  = $data["membership_code"]->membership_id;
            $sponsor        = $data["sponsor"]->slot_id;

            $slot_no_based_on_name = Self::generate_slot_no_based_on_name(Self::$customer_info->first_name, Self::$customer_info->last_name);
            
            $new_slot_no    = $data["pin"];
            $new_slot_no    = str_replace("MYPHONE", "BROWN", $new_slot_no);
            $new_slot_no    = str_replace("JCAWELLNESSINTCORP", "JCA", $slot_no_based_on_name);
            
            $return = Item::check_unused_product_code($shop_id, $data["pin"], $data["activation"]);

            if($return)
            {
                $create_slot    = MLM2::create_slot($shop_id, $customer_id, $membership_id, $sponsor, $new_slot_no);

                if(is_numeric($create_slot))
                {
                    $remarks = "Code used by " . $data["sponsor_customer"]->first_name . " " . $data["sponsor_customer"]->last_name;
                    MLM2::use_membership_code($shop_id, $data["pin"], $data["activation"], $create_slot, $remarks);

                    $setting = Tbl_mlm_plan_setting::where("shop_id",$shop_id)->first();
                    $slot_id = $create_slot;

                    if($setting->plan_settings_placement_required == 0)
                    {
                        $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
                        Mlm_tree::insert_tree_sponsor($slot_info_e, $slot_info_e, 1);
                        MLM2::entry($shop_id, $slot_id);
                    }
                    
                    $store["get_success_mode"] = "success";
                    session($store);
                    echo json_encode("success");
                }
                else
                {
                    echo json_encode($create_slot);
                }                
            }
            else
            {
                echo json_encode('Item Code already used');
            }
        }
    }
    public function check_placement($slot_id, $slot_placement, $slot_position)
    {
        $shop_id       = $this->shop_info->shop_id;
        $check_sponsor = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
        $this_slot_owner     = $check_sponsor->slot_owner;
        $this_slot_placement = $check_sponsor->slot_placement;
        $procceed      = 0;
        
        if($this_slot_placement == 0)
        {
            if($check_sponsor)
            {
                $check_sponsor = Tbl_mlm_slot::where("slot_id",$check_sponsor->slot_sponsor)->where("shop_id",$shop_id)->first();
                if($check_sponsor->slot_owner == Self::$customer_info->customer_id || $this_slot_owner == Self::$customer_info->customer_id)
                {
                    $check_placement = MLM2::check_placement_exist($shop_id,$slot_placement,$slot_position,1,$check_sponsor->slot_id);
                    
                    if($check_placement == 0)
                    {
                        $sponsor_have_placement = MLM2::check_sponsor_have_placement($shop_id,$check_sponsor->slot_id);
    
                        if($sponsor_have_placement == 1)
                        {
                            $data["target_slot"] = Tbl_mlm_slot::where("slot_id",$slot_id)->customer()->first();
                            $data["placement"]   = Tbl_mlm_slot::where("slot_id",$slot_placement)->customer()->first();
                            $data["position"]    = $slot_position;
                            $data["message"]     = "success";
                            $procceed            = 1;
                        }
                        else
                        {
                            $data["message"]   = "Your upline should placed you first.  (ERROR182)";
                        }
                    }
                    else
                    {
                        $data["message"]   = "Placement not available. (ERROR391)";
                    }
                }
                else
                {
                    $data["message"]   = "Some error occurred please try again. (ERROR659)";
                }
            }
            else
            {
                $data["message"]   = "Some error occurred please try again. (ERROR388)";
            }
        }
        else
        {
            $data["message"]   = "This slot is already placed. (ERROR111)";
        }
        
        $data["procceed"] = $procceed;
        return $data;
    }
    

    public function load_view_for_members($view, $data, $memberonly = true)
    {
        if ($this->shop_theme == "brown") 
        {
            $agent = new Agent();

            if($agent->isMobile())
            {
                if (strpos($view, 'member2.') !== false)
                {
                    $new_view = str_replace("member2.", "member2.mobile.", $view);
                }
                else
                {
                    $new_view = str_replace("member.", "member.mobile.", $view);
                }

                if(view()->exists($new_view))
                {
                    $view = $new_view;
                }
            }

            if ($memberonly) 
            {
                return Self::logged_in_member_only() ? Self::logged_in_member_only() : view($view, $data);
            }
            else
            {
                return view($view, $data);
            }
        }
        else
        {
            return view($view, $data);
        }
    }
    public function getSlotUseproductcode()
    {
        $data['action'] = '/members/slot-validate';
        $data['confirm_action'] = '/members/slot-toslot';
        return view('mlm.slots.use_product_code',$data);
    }
    public function postSlotValidate()
    {
        $mlm_pin = Request2::input('mlm_pin');
        $mlm_activation = Request2::input('mlm_activation');

        $shop_id = $this->shop_info->shop_id;

        $check = Item::check_unused_product_code($shop_id, $mlm_pin, $mlm_activation);
        $return = [];
        if($check)
        {
            $return['status'] = 'success';
            $return['mlm_pin'] = $mlm_pin;
            $return['mlm_activation'] = $mlm_activation;
            $return['call_function'] = 'success_validation';
        }
        else
        {
            $return['status'] = 'error';
            $return['message'] = "Pin number and activation code doesn't exist.";
        }

        return json_encode($return);
    }
    public function getSlotToslot()
    {
        $data['mlm_pin'] = Request2::input('mlm_pin');
        $data['mlm_activation'] = Request2::input('mlm_activation');
        $data["_slot"]    = Tbl_mlm_slot::where('slot_owner', Self::$customer_info->customer_id)->membership()->get();
        $data['action'] = '/members/slot-confirmation';
        $data['confirm_action'] = '/members/slot-confirmation-submit';

        if ($this->shop_theme == "3xcell") 
        {
            return view('member.choose_slot', $data);
        }
        else
        {
            return view('mlm.slots.choose_slot', $data);
        }
    }
    public function postSlotConfirmation()
    {
        $data['mlm_pin'] = Request2::input('mlm_pin');
        $data['mlm_activation'] = Request2::input('mlm_activation');
        $data['slot_no'] = Request2::input('slot_no');

        $data['status'] = 'success';
        $data['call_function'] = 'success_slot';

        return json_encode($data);
    }
    public function getSlotConfirmationSubmit()
    {
        $data['mlm_pin'] = Request2::input('mlm_pin');
        $data['mlm_activation'] = Request2::input('mlm_activation');
        $data['slot_no'] = Request2::input('slot_no');
        
        if ($this->shop_theme == "3xcell") 
        {
            $data['message'] = "&nbsp; &nbsp; Are you sure you wan't to use this PIN (<b>".$data['mlm_pin']."</b>) and Activation code (<b>".$data['mlm_activation']."</b>) ?";
        }
        else
        {
            $data['message'] = "&nbsp; &nbsp; Are you sure you wan't to use this PIN (<b>".$data['mlm_pin']."</b>) and Activation code (<b>".$data['mlm_activation']."</b>) in your Slot No <b>".$data['slot_no']."</b> ?";
        }

        $data['action'] = '/members/slot-use-product-code';

        return view('mlm.slots.confirm_product_code',$data);
    }
    public function postSlotUseProductCode()
    {
        $mlm_pin = Request2::input('mlm_pin');
        $mlm_activation = Request2::input('mlm_activation');
        $slot_no = Request2::input('slot_no');

        $slot_id    = Tbl_mlm_slot::where('slot_no', $slot_no)->where('slot_owner',Self::$customer_info->customer_id)->value('slot_id');

        $shop_id = $this->shop_info->shop_id;
        $consume['name'] = 'customer_product_code';
        $consume['id'] =Self::$customer_info->customer_id;
        $val = Warehouse2::consume_product_codes($shop_id, $mlm_pin, $mlm_activation, $consume);
        
        if(is_numeric($val))
        {
            MLM2::purchase($shop_id, $slot_id, $val);
            $return['status'] = 'success';
            $return['call_function'] = 'success_used';


            $item = Tbl_warehouse_inventory_record_log::where('mlm_activation',$mlm_activation)
                                                 ->where('mlm_pin',$mlm_pin)
                                                 ->first();
            // patrick
            $item_token = Tbl_item_token::where('item_id',$item->record_item_id)->first();
            $token_log['shop_id']                   = $this->shop_info->shop_id;
            $token_log['token_log_slot_owner']      = Self::$customer_info->customer_id;
            $token_log['token_log_date_created']    = Carbon::now();
            $token_log['token_id']                  = $item_token->token_id;
            $token_log['amount']              = $item_token->amount;
            Tbl_item_token_log::insert($token_log);

        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $val;
        }

        return json_encode($return);
    }
    public function getReadNotification()
    {
        $notif_id = Request2::input('notif_id');
        $update['has_been_seen'] = 1;
        Tbl_slot_notification::where('notification_id', $notif_id)->update($update);
    }
    public function getWebhook()
    {
        /* API Details */
        $shop_id = $this->shop_info->shop_id;
        $api = Tbl_online_pymnt_api::where('api_shop_id', $shop_id)
                                   ->join("tbl_online_pymnt_gateway", "tbl_online_pymnt_gateway.gateway_id", "=", "tbl_online_pymnt_api.api_gateway_id")
                                   ->where("gateway_code_name", "paymaya")
                                   ->first();
                                   
        /* Init Paymaya */
        if (get_domain() == "c9users.io" || get_domain() == "digimahouse.dev") 
        {
            $environment = "SANDBOX";
            PayMayaSDK::getInstance()->initCheckout("pk-sEt9FzRUWI2PCBI2axjZ7xdBHoPiVDEEWSulD78CW9c", "sk-cJFYCGhH4stZZTS52Z3dpNbrpRyu6a9iJaBiVlcIqZ5", $environment);
        }
        else
        {
            $environment = "PRODUCTION";
            PayMayaSDK::getInstance()->initCheckout($api->api_client_id, $api->api_secret_id, $environment);
        }

        /* Set Webhook */
        $webhook = Webhook::retrieve();

        $webhook_success = "/payment/paymaya/webhook/success";
        $webhook_failure = "/payment/paymaya/webhook/failure";
        $webhook_cancel = "/payment/paymaya/webhook/cancel";

        if (isset($webhook) && $webhook && count($webhook) > 0) 
        {
            foreach ($webhook as $value) 
            {
                if ($value->name == "CHECKOUT_SUCCESS") 
                {
                    $updateWebhook = new Webhook();
                    $updateWebhook->name = Webhook::CHECKOUT_SUCCESS;
                    $updateWebhook->id = $value->id;
                    $updateWebhook->callbackUrl = URL::to($webhook_success);
                    $updateWebhook->delete();
                }
                elseif($value->name == "CHECKOUT_FAILURE")
                {
                    $updateWebhook = new Webhook();
                    $updateWebhook->name = Webhook::CHECKOUT_FAILURE;
                    $updateWebhook->id = $value->id;
                    $updateWebhook->callbackUrl = URL::to($webhook_failure);
                    $updateWebhook->delete();
                }
                elseif($value->name == "CHECKOUT_DROPOUT")
                {
                    $updateWebhook = new Webhook();
                    $updateWebhook->name = Webhook::CHECKOUT_DROPOUT;
                    $updateWebhook->id = $value->id;
                    $updateWebhook->callbackUrl = URL::to($webhook_cancel);
                    $updateWebhook->delete();
                }
            }
        }
        
        $successWebhook = new Webhook();
        $successWebhook->name = Webhook::CHECKOUT_SUCCESS;
        $successWebhook->callbackUrl = URL::to($webhook_success);
        $successWebhook->register();
        
        $failureWebhook = new Webhook();
        $failureWebhook->name = Webhook::CHECKOUT_FAILURE;
        $failureWebhook->callbackUrl = URL::to($webhook_failure);
        $failureWebhook->register();
        
        $cancelWebhook = new Webhook();
        $cancelWebhook->name = Webhook::CHECKOUT_DROPOUT;
        $cancelWebhook->callbackUrl = URL::to($webhook_cancel);
        $cancelWebhook->register();

        dd(Webhook::retrieve());
    }
    public function postSlotUpgradeCode(Request $request)
    {
        $shop_id                                = $this->shop_info->shop_id;
        $validate["pin"]                        = ["required", "string", "alpha_dash"];
        $validate["activation"]                 = ["required", "string", "alpha_dash"];
        $validator                              = Validator::make($request->all(), $validate);
        $slot                                   = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->first();
        $message = "";

        if($slot)
        {
            $check_membership = Tbl_membership::where("membership_id",$slot->slot_membership)->first();
            if($check_membership->membership_restricted == 1)
            {
                if($validator->fails())
                {
                    foreach($validator->errors()->all() as $error)
                    {
                        $message .= "<div>" . $error . "</div>";
                    }
                }
                else
                {
                    $activation             = request("activation");
                    $pin                    = request("pin");
                    $check_membership_code  = MLM2::check_membership_code($shop_id, $pin, $activation);

                    if(!$check_membership_code)
                    {
                        $message = "Invalid PIN / ACTIVATION!";
                    }
                    else
                    {
                        if($check_membership_code->mlm_slot_id_created != "")
                        {
                            $message = "PIN / ACTIVATION ALREADY USED";
                        }
                        else
                        {
                            if($check_membership_code->membership_restricted == 0)
                            {
                                $remarks = "Code used for upgrading by " . Self::$customer_info->first_name . " " . Self::$customer_info->last_name;
                                MLM2::use_membership_code($slot->shop_id, $pin, $activation, $slot->slot_id, $remarks);

                                $update_warelog["used_for_upgrade"] = 1;
                                Tbl_warehouse_inventory_record_log::where("record_log_id",$check_membership_code->record_log_id)->where("record_shop_id",$shop_id)->update($update_warelog);
                                
                                $update_slot_mem["slot_membership"]         = $check_membership_code->membership_id;
                                $update_slot_mem["upgraded"]                = 1;
                                $update_slot_mem["upgrade_from_membership"] = $slot->slot_membership;
                                Tbl_mlm_slot::where("slot_id",$slot->slot_id)->where("shop_id",$shop_id)->update($update_slot_mem);

                                MLM2::entry($shop_id,$slot->slot_id);

                            }
                            else
                            {      
                                $message = "<div>Membership Code is not available for upgrade</div>";                     
                            }
                        }
                    }
                }
            }
            else
            {
                $message = "<div>Your slot membership is not available for upgrade</div>";
            }
        }
        else
        {
            $message = "<div>You have no slot to upgrade</div>";
        }

        echo $message;
    }
}
