<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Carbon\Carbon;
use DB;
use App\Models\Tbl_customer;
use App\Models\Tbl_country;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_mlm_lead;
use App\Globals\Mlm_member;
use App\Globals\Sms;
use App\Models\Tbl_email_template;
use App\Models\Tbl_shop;
use App\Globals\EmailContent;
use App\Globals\Settings;
use Mail;
use App\Globals\Mail_global;
use Config;
use Validator;
use Session;
//use App\Globals\Mlm_member;
class MlmRegisterController extends MlmLoginController
{
    public function index()
    {
        $data["page"] = "register";
        $data['lead'] = Self::$lead;
        if($data['lead'] != null)
        {
            $data['lead_code'] = Tbl_membership_code::where('tbl_membership_code.customer_id', $data['lead']->customer_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_membership_code.slot_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->whereNotNull('tbl_membership_code.slot_id')
            ->get();
            $data['customer_info'] = Mlm_member::get_customer_info($data['lead']->customer_id);
        } 
        else
        {
            $check_shop = Tbl_shop::where("shop_id",Self::$shop_id)->first();
            if($check_shop)
            {
                if($check_shop->shop_key == "alphaglobal")
                {
                    return Redirect::to("/");
                }
            }
        }
        
        $data['country'] = Tbl_country::get();

        /* Check Shop Theme Register */
        $shop = DB::table("tbl_shop")->where("shop_id", Self::$shop_id)->first();
        if ($shop) 
        {
            switch ($shop->shop_theme) 
            {
                case '3xcell':
                    View::addLocation(base_path() . '/public/themes/' . $shop->shop_theme . '/views/');
                    $data["shop_theme"] = $shop->shop_theme;
                    return view("register", $data);
                break;
                
                default:
                    return view("mlm.register", $data);
                break;
            }
        }
        else
        {
            return view("mlm.register", $data);
        }
    }
    public function post_register()
    {   

        $i['password'] = Request::input('pass');
        $i['password_2'] = Request::input('pass2');
        if($i['password'] == $i['password_2'])
        {
            
            $insert['shop_id'] = Self::$shop_id;
            $insert['first_name'] = Request::input('first_name');
            $insert['last_name'] = Request::input('last_name');
            $insert['email'] = Request::input('email');
            $insert['password'] = $i['password'];
            $insert['company'] = Request::input('company');
            $insert['created_date'] = Carbon::now();
            $insert['IsWalkin'] = 0; 
            $insert['mlm_username'] = Request::input('username');
            $insert['country_id'] = Request::input('country');
            $insert['tin_number'] = Request::input('tinnumber');
            $insert_address['customer_state'] = Request::input('customer_state');
            $insert_address['customer_city'] = Request::input('customer_city');
            $insert_address['customer_zipcode'] = Request::input('customer_zipcode');
            $insert_address['customer_street'] = Request::input('customer_street');

            $insert['ismlm'] = 1;


            $data['type']   = "Success";
            $data['message'] = "Password Matched";

            $vali['mlm_username'] = $insert['mlm_username'];

            $rules = array(
                'mlm_username'=> 'regex:/(^[A-Za-z0-9 ]+$)+/'
            );
            $validator = Validator::make($vali,$rules);
            if ($validator->passes())
            {
                if(strlen($insert['mlm_username']) >= 6)
                {
                    $count_username = Tbl_customer::where('mlm_username', $insert['mlm_username'])->count();
                    if($count_username == 0)
                    {
                        if(strlen($insert['password']) >= 6)
                        {
                            $check_email = Tbl_customer::where('shop_id',Self::$shop_id)->where('email',$insert['email'])->count();
                            if($check_email == 0)
                            {

                                // leads
                                $lead_customer = Self::$lead;
                                $continue = 1;
                                $lead = 0;
                                $membership_code = Request::input('membership_code');
                                if($lead_customer != null)
                                {
                                    if($membership_code == null)
                                    {
                                        $continue = 0;
                                        $data['type']   = "error";
                                        $data['message'] = "Membership Code Required.";
                                    }
                                    else
                                    {
                                        $sponsor_info = Tbl_membership_code::where('membership_activation_code', $membership_code)
                                        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_membership_code.slot_id')
                                        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                                        ->first();  
                                        if($sponsor_info != null)
                                        {
                                            $insert_lead['lead_join_date'] = Carbon::now();
                                            $insert_lead['lead_customer_id_sponsor'] = $sponsor_info->customer_id;
                                            $insert_lead['lead_slot_id_sponsor'] = $sponsor_info->slot_id;
                                            $insert_lead['lead_sponsor_membership_code'] = $membership_code;
                                            $lead = 1;
                                        } 
                                        else
                                        {
                                            $continue = 0;
                                            $data['type']   = "error";
                                            $data['message'] = "Member not found.";
                                        }
                                    }
                                }
                                else
                                {
                                    if($membership_code != null)
                                    {
                                        $sponsor_info = Tbl_membership_code::where('membership_activation_code', $membership_code)
                                        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_membership_code.slot_id')
                                        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                                        ->first(); 
                                        if($sponsor_info != null)
                                        {
                                            $insert_lead['lead_join_date'] = Carbon::now();
                                            $insert_lead['lead_customer_id_sponsor'] = $sponsor_info->customer_id;
                                            $insert_lead['lead_slot_id_sponsor'] = $sponsor_info->slot_id;
                                            $insert_lead['lead_sponsor_membership_code'] = $membership_code;
                                            $lead = 1;
                                        } 
                                        else
                                        {
                                            $continue = 0;
                                            $data['type']   = "error";
                                            $data['message'] = "Member not found.";
                                        } 
                                    }
                                }
                                if($continue == 1)
                                {
                                    // if($insert['tin_number'] != null)
                                    // {
                                        $insert['password'] = Crypt::encrypt($i['password']);
                                        $cus_id = Tbl_customer::insertGetId($insert);

                                        $insert_other['customer_mobile'] = Request::input('customer_mobile');
                                        $insert_other['customer_id'] = $cus_id;
                                        // $insert_other['shop_id'] = Self::$shop_id;
                                        DB::table('tbl_customer_other_info')->insert($insert_other);

                                        $updatetSearch['customer_id'] = $cus_id;
                                        $updatetSearch['body'] = $insert['first_name'].' '.$insert['last_name'].' '.$insert['email'].' '.$insert['mlm_username'];
                                        $updatetSearch['created_at'] = Carbon::now();
                                        $updatetSearch['updated_at'] = Carbon::now();
                                        DB::table('tbl_customer_search')->insert($updatetSearch);

                                        $insert_address['customer_id'] = $cus_id;
                                        $insert_address['customer_state'] = Request::input('customer_state');
                                        $insert_address['customer_city'] = Request::input('customer_city');
                                        $insert_address['customer_zipcode'] = Request::input('customer_zipcode');
                                        $insert_address['customer_street'] = Request::input('customer_street');
                                        $insert_address['purpose'] = 'billing';
                                        $insert_address['country_id'] = Request::input('country');
                                        DB::table('tbl_customer_address')->insert($insert_address);

                                        Mlm_member::add_to_session(Self::$shop_id, $cus_id);
                                        if($lead == 1)
                                        {
                                            $insert_lead['lead_customer_id_lead'] = $cus_id;
                                            Tbl_mlm_lead::insert($insert_lead);
                                        }
                                        // $data['type']   = "success";
                                        // $data['message'] = "Success! you will be redirected";
                                        // return $data;
                                        $this->mail_customer_success_register(Self::$shop_id, $cus_id);
                                        /* Sms Notification */
                                        $txt[0]["txt_to_be_replace"]    = "[name]";
                                        $txt[0]["txt_to_replace"]       = $insert['first_name'];
                                        $result  = Sms::SendSms($insert_other['customer_mobile'], "success_register", $txt, Self::$shop_id);

                                        $data['type']   = "success";
                                        $data['message'] = "Success! you will be redirected";
                                    // }
                                    // else
                                    // {
                                    //  $data['type']   = "error";
                                    //  $data['message'] = "Tin Number Is Required";
                                    // }
                                }
                            }
                            else
                            {
                                $data['type']   = "error";
                                $data['message'] = "Email Already Taken";
                            }
                        }
                        else
                        {
                            $data['type']   = "error";
                            $data['message'] = "Password length is too short";
                        }
                    }
                    else
                    {
                        $data['type']   = "error";
                        $data['message'] = "Username Already Exist";
                    }
                }
                else
                {
                    $data['type']   = "error";
                    $data['message'] = "Username length is too short";
                }
            }
            else
            {
                $data['type'] = "error";
                $data['message'] = $validator->messages()->first();
            }    
        }
        else
        {
            $data['type']   = "error";
            $data['message'] = "Password didn't match.";
        }

        $data['from'] = "register";

        return json_encode($data);
    }
    public function mail_customer_success_register($shop_id, $customer_id)
    {
                $data["template"] = Tbl_email_template::where("shop_id",$shop_id)->first();
                $data['customer'] = Tbl_customer::where('customer_id', $customer_id)->first();

                $change_content[0]["txt_to_be_replace"] = "[name_of_registrant]";
                $change_content[0]["txt_to_replace"] = name_format_from_customer_info($data['customer']);


                $change_content[1]["txt_to_be_replace"] = "[tin_of_registrant]";
                $change_content[1]["txt_to_replace"] = $data['customer']->tin_number;

                $change_content[2]["txt_to_be_replace"] = "[user_name]";
                $change_content[2]["txt_to_replace"] = $data['customer']->mlm_username;

                $change_content[3]["txt_to_be_replace"] = "[pass_word]";
                $change_content[3]["txt_to_replace"] = Crypt::decrypt($data['customer']->password);

                $content_key = 'success_register';
                $data['body'] = EmailContent::email_txt_replace($content_key, $change_content, $shop_id);

                $data['company']['email'] = DB::table('tbl_content')->where('shop_id', $shop_id)->value('value');

                // ----------------------------------------------------------
                $data['mail_to'] = $data['customer']->email;
                $data['mail_username'] = Config::get('mail.username');
                $data['mail_subject'] = 'SUCCESS REGISTRATION';
                Mail_global::mail($data, $shop_id);
    }
    public static function view_customer_info_via_mem_code($membership_activation_code)
    {
        $data['customer_info'] = Tbl_membership_code::where('membership_activation_code', $membership_activation_code)
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_membership_code.slot_id')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->first();  
        if(isset($data['customer_info']->customer_id))
        {
            $data['customer_view'] = Mlm_member::get_customer_info($data['customer_info']->customer_id);
        }
        return view('mlm.pre.view_customer_v_mem', $data);
    }
    public function package()
    {
        return view("mlm.register.package");
    }
    public function payment()
    {

    }

    /* Start of E-commerce registration --Brain*/
    public function register_ecomm()
    {
        $i['password'] = Request::input('pass');
        $i['password_2'] = Request::input('pass2');
        $i['customer_mobile'] = Request::input('customer_mobile');

        if ($i['customer_mobile'] != null) 
        {
            if($i['password'] == $i['password_2'])
            {
                if(strlen($i['password']) >= 6)
                {     
                    $check_email = Tbl_customer::where('shop_id',Self::$shop_id)->where('email', Request::input('email'))->count();  
                    //dd($check_email);
                    //$check_email = Tbl_customer::where('shop_id', Self::$shop_id)->where('email', Request::input('email')->count();
                
                    if($check_email == 0)
                    {
                        $insert_customer = array(
                            'shop_id'       => Self::$shop_id,
                            'first_name'    => Request::input('first_name'),
                            'last_name'     => Request::input('last_name'),
                            'email'         => Request::input('email'),
                            'password'      => Crypt::encrypt($i['password']),

                            'IsWalkin'      => 0,
                            'ismlm'         => 2,
                        );

                        $cus_id = Tbl_customer::insertGetId($insert_customer);
                    
                        $data["customer_info"] = Tbl_customer::where("customer_id",$cus_id)->first();
                        Mail_global::create_email_content($data,Self::$shop_id,"success_register_intogadgets");

                        if ($cus_id)
                        {
                            $insert_address = array(  
                                'customer_id'       => $cus_id,
                                'country_id'        => 420, //default ph
                                'customer_state'    => Request::input('customer_state'),
                                'customer_city'     => Request::input('customer_city'),
                                'customer_street'   => Request::input('customer_street'),
                                'purpose'           => "billing",
                                //'customer_mobile'   => Request::input('customer_mobile'),
                            );

                            DB::table('tbl_customer_address')->insert($insert_address);  

                            $insert_other = array(  
                                'customer_id'       => $cus_id,                                
                                'customer_mobile'   => Request::input('customer_mobile'),
                            );   

                            DB::table('tbl_customer_other_info')->insert($insert_other); 
                            
                            Mlm_member::add_to_session(Self::$shop_id, $cus_id);     

                            //echo "Registration successful! You will be redirect to your account page.";    
                            return Redirect::to('/account');         

                        }
                        else
                        {
                            Session::flash('warning', 'Error! Please contact administrator(Err-Customer Model).');
                            return Redirect::back()->withInput();   
                        }                     
                    }
                    else
                    {
                        Session::flash('warning', 'Email is already exist in the record.');
                        return Redirect::back()->withInput();  
                    }      
                }
                else
                {
                    Session::flash('warning', 'Password length is mainimum of 6 character.');
                    return Redirect::back()->withInput();
                }                
            }
            else
            {   
                Session::flash('warning', 'Password do not match!');
                return Redirect::back()->withInput();
            }
        }
        else
        {
            Session::flash('warning', 'Contact Number is required!');
            return Redirect::back()->withInput();
        }
    }
    /*End of E-commerce Registration*/
}