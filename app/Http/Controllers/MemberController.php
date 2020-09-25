<?php
namespace App\Http\Controllers;
use Request;
use App\Models\Tbl_mlm_slot;
use App\Globals\Pdf_global;
use App\Globals\Ecom_Product;
use PDF;
use App;
use Carbon\Carbon;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_country;
use App\Models\Tbl_shop;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_membership_package_has;
use App\Models\Tbl_locale;
use App\Models\Tbl_online_pymnt_method;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_ec_variant;
use App\Models\Tbl_settings;

use Validator;
use Session;
use Redirect;
use App\Globals\Mlm_member;
use App\Globals\Item;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_gc;
use App\Globals\Dragonpay2\Dragon_RequestPayment;
use App\Globals\Cart;

class MemberController extends Controller
{
    public static $shop_id;
    public static $lead;

    public function __construct()
    {   
        $domain = Request::url();
        $check_expole = explode('//', $domain);
        if(count($check_expole) == 2 )
        {
            $check_expole_2 = explode('.', $check_expole[1]);
            $key = $check_expole_2[0];
            $check_domain = Tbl_shop::where('shop_key', $key)->first();
            $lead_e = null;
            if($check_domain == null)
            {
                $check_domain = Tbl_customer::where('mlm_username', $key)->first();
                $lead_e = $check_domain;

                if($check_domain == null)
                {
                    $key = 'myphone';
                    $check_domain = Tbl_shop::where('shop_key', $key)->first();
                }
            }
        }
        if($check_domain != null)
        {
             Self::$shop_id = $check_domain->shop_id;
             if($lead_e != null)
             {
                Self::$lead = $lead_e;
             }
             else
             {
                Self::$lead = null;
             }
        }
        else
        {
            $domain = Request::url();
            $check_expole = explode('.', $domain);
            if(isset($check_expole[2]))
            {
                $check_expole_2 = explode('/', $check_expole[2]);
                if(isset($check_expole_2[0]))
                {
                    $shop_domain = $check_expole[1] . '.' . $check_expole_2[0];
                    $shop = Tbl_shop::where('shop_domain', $shop_domain)->first();
                    if($shop != null)
                    {
                        Self::$shop_id = $shop->shop_id;
                    }

                }
            }
            else
            {
                if(isset($check_expole[1]))
                {

                    $check_expole_2 = explode('/', $check_expole[1]);
                    if(isset($check_expole_2[0]))
                    {
                        $check_expole_slash = explode('//', $check_expole[0]);
                        if(count($check_expole_slash) >= 2)
                        {
                          $check_expole[0] = $check_expole_slash[1];  
                        }
                        $shop_domain = $check_expole[0] . '.' . $check_expole_2[0];
                        $shop = Tbl_shop::where('shop_domain', $shop_domain)->first();
                        if($shop != null)
                        {
                            Self::$shop_id = $shop->shop_id;
                        }
                    }   
                }
            }
        }
    }

    public function index()
    {
        echo "hello world";
    }

    public function generate_username($first_name, $last_name)
    {
        $f_name = $first_name;
        $l_name = $last_name;
        $last_name = str_replace(' ', '', $last_name);
        $first_name = str_replace(' ', '', $first_name);
        $last_name = strtolower(substr($last_name, 0, 3));
        $first_name = strtolower(substr($first_name, 0, 6));
        $nickname = $first_name . '.' . $last_name;
        $count_username = Tbl_customer::where('first_name', $f_name)
        ->where('last_name', $l_name)
        ->count();
        if($count_username == 0)
        {
            return $nickname;
        }
        else
        {
            $nickname = $nickname . ($count_username + 1);
            return $nickname;
        }
    }

    public function locale_id_to_name($locale_id)
    {
        return Tbl_locale::where("locale_id", $locale_id)->value("locale_name");
    }

    public function register()
    {
        // $customer_session = Session::get('mlm_member');
        // if($customer_session)
        // {
        //     return $this->register_logged_in();
        // }
        
        // $data['country'] = Tbl_country::get();
        // $data['current'] = Cart::get_info(Self::$shop_id);
        // $data['sponsor_r'] = $this->check_if_required_sponsor(Self::$shop_id);
        // $data['terms_and_agreement'] = Tbl_settings::where('shop_id', Self::$shop_id)->where('settings_key', 'terms_and_agreement')->first();
        // return view("mlm.register.register", $data);

        return Redirect::to("/members/register");
    }
    
    public function login()
    {
        return Redirect::to("/members/login");
    }

    public function register_logged_in()
    {

        $data = [];

        $data['sponsor_r'] = $this->check_if_required_sponsor(Self::$shop_id);

        $customer_session = Session::get('mlm_member');

        if(isset($customer_session['customer_info']->mlm_username))
        {
            $data['sponsor'] =  $customer_session['customer_info']->mlm_username;
        }
        else
        {
            $data['sponsor'] =  $customer_session['customer_info']->mlm_username;
        }

        return view('mlm.register.logged_in', $data);
    }
    public function register_logged_in_post()
    {

        // Check if is sponsor is required and existing
        $info['sponsor'] = Request::input('sponsor');
        $info['account_use'] = Request::input('account_use');
        $sponsor_r = $this->check_if_required_sponsor(Self::$shop_id);
        if($sponsor_r == 1)
        {   
            if($info['sponsor'] != null)
            {
                $count_slot = Tbl_mlm_slot::where('slot_nick_name', $info['sponsor'])->count();
                if($count_slot == 0)
                {
                    $data['status'] = 'warning';
                    $data['message'][0] = 'Sponsor does not exist';
                    return $data;
                }
            }
        }
        else
        {
            if($info['sponsor'] != null)
            {
                $count_slot = Tbl_mlm_slot::where('slot_nick_name', $info['sponsor'])->count();
                if($count_slot == 0)
                {
                    $data['status'] = 'warning';
                    $data['message'][0] = 'Sponsor does not exist';
                    return $data;
                }
            }
        }  
        if($info['account_use'] == 1)
        {
            Session::forget('mlm_member');
            $data['status'] = 'success';
            $data['link']   =  '/member/register';
            return $data;
        }
        else
        {
            $customer_session = Session::get('mlm_member');
            $customer_id = $customer_session['customer_info']->customer_id;

            $customer_info["current_user"] = Tbl_customer::where('customer_id', $customer_id)->first();
            $slot = Tbl_customer::where('mlm_username', $info['sponsor'])
                    ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_owner', '=', 'tbl_customer.customer_id')
                    ->where('slot_defaul', 1)
                    ->first();
            if($slot)
            {
                $customer_info["slot_sponsor"]     = $info['sponsor'];
            }
            

            /* Set Product Temporarily */
            $product = Tbl_ec_product::variant()->where("eprod_shop_id", Self::$shop_id)->where("tbl_ec_product.archived", 0)->first();
            Cart::add_to_cart($product->evariant_id, 1, Self::$shop_id, true);

            $customer_set_info_response        = Cart::customer_set_info(Self::$shop_id, $customer_info);

            if($customer_set_info_response["status"] == "error")
            { 
                $data['status'] = 'warning';
                $data['message'][0] = $customer_set_info_response["status_message"];

                return $data;
            }
            else
            {
                /* Redirect */
                Session::put('mlm_register_step_1', $customer_session['customer_info']);
                $data['status'] = 'success';
                $data['message'][0] = 'Sucess!';
                $data['link'] = '/member/register/package';

                return $data;
            }
        }
    }
    public function check_if_required_sponsor($shop_id)
    {
        $settings = Tbl_settings::where('settings_key', 'myphone_require_sponsor')->where('shop_id', Self::$shop_id)->first();
        if($settings)
        {
            return $settings->settings_value;
        }
        else
        {
            return 0;
        }
    }
    public function register_post()
    {
        $info['is_corporate']      = Request::input('customer_type');
        $info['company']           = Request::input('customer_type') == 1 ? Request::input('company') : "";
        $info['country']           = Request::input('country');
        $info['email']             = Request::input('email');
        $info['first_name']        = Request::input('first_name');
        $info['last_name']         = Request::input('last_name');
        // 
        $info['middle_name']       = Request::input('middle_name');
        $info['permanent_address'] = Request::input('permanent_address');
        $info['date_of_birth']     = Request::input('date_of_birth');
        $info['gender']            = Request::input('gender');
        $info['confirm_email']     = Request::input('confirm_email');
        $info['confirm_tin_number'] = Request::input('confirm_tin_number');
        // 
        $info['password']          = randomPassword();
        $info['password_confirm']  = $info['password'];
        $info['tin_number']        = Request::input('tin_number');
        $info['mlm_username']      = $this->generate_username($info["first_name"], $info["last_name"]);
        $info['sponsor']           = Request::input('sponsor');
        $info['customer_phone']    = Request::input('contact_number');
        $info['customer_mobile']   = Request::input('contact_number');

        $rules['first_name']       = 'required';
        $rules['last_name']        = 'required';
        $rules['password']         = 'required|min:6';
        $rules['password_confirm'] = 'required|min:6';
        $rules['email']            = 'required';
        // 
        $rules['middle_name'] = 'required';
        $rules['permanent_address'] = 'required';
        $rules['date_of_birth'] = 'required';
        $rules['gender'] = 'required';
        $rules['confirm_email'] = 'required|same:email';
        $rules['confirm_tin_number'] = 'required|same:tin_number';
        // 
        $sponsor_r = $this->check_if_required_sponsor(Self::$shop_id);
        if($info['is_corporate'] == 1)
        {
            $rules['company'] = 'required';
        }

        if(!isset($_POST['terms']))
        {
            $data['status'] = 'warning';
            $data['message'][0] = 'Terms and Agreement is required';
            return $data;
        }
         $validator = Validator::make($info, $rules);
         if ($validator->passes()) 
         {
            $count_email = Tbl_customer::where('email', $info['email'])->count();
            if($count_email == 0)
            {
                $count_username = Tbl_customer::where('mlm_username', $info['mlm_username'])->count();
                if($count_username == 0)
                {
                    if($sponsor_r == 1)
                    {
                        if($info['sponsor'] != null)
                        {
                            $count_slot = Tbl_mlm_slot::where('slot_nick_name', $info['sponsor'])->count();
                            if($count_slot == 0)
                            {
                                $data['status'] = 'warning';
                                $data['message'][0] = 'Sponsor does not exist';
                                return $data;
                            }
                        }
                    }
                    if($info['password'] == $info['password_confirm'])
                    {

                        /* Set Product Temporarily */
                        $product = Tbl_ec_product::variant()->where("eprod_shop_id", Self::$shop_id)->where("tbl_ec_product.archived", 0)->first();
                        Cart::add_to_cart($product->evariant_id, 1, Self::$shop_id, true);

                        /* Set Customer Info */
                        $customer_info["new_account"]      = true;
                        $customer_info["first_name"]       = $info["first_name"];
                        $customer_info["last_name"]        = $info["last_name"];
                        $customer_info["email"]            = $info["email"];
                        $customer_info["password"]         = $info["password"];
                        $customer_info["tin_number"]       = $info["tin_number"];
                        $customer_info["mlm_username"]     = $info["mlm_username"];
                        $customer_info["slot_sponsor"]     = $info["sponsor"];
                        $customer_info["customer_contact"] = $info["customer_mobile"];
                        $customer_info["is_corporate"]     = $info["is_corporate"];
                        $customer_info["company"]          = $info["company"];
                        // 
                        $customer_info['middle_name']                = Request::input('middle_name');
                        $customer_info['customer_full_address']      = Request::input('permanent_address');
                        $customer_info['b_day']                      = Request::input('date_of_birth');
                        $customer_info['customer_gender']            = Request::input('gender');
                        // 
                        $customer_set_info_response        = Cart::customer_set_info(Self::$shop_id, $customer_info);

                        if($customer_set_info_response["status"] == "error")
                        { 
                            $data['status'] = 'warning';
                            $data['message'][0] = $customer_set_info_response["status_message"];
                        }
                        else
                        {
                            /* Redirect */
                            Session::put('mlm_register_step_1', $info);
                            $data['status'] = 'success';
                            $data['message'][0] = 'Sucess!';
                            $data['link'] = '/member/register/package';
                        }
                    }
                    else
                    {
                        $data['status'] = 'warning';
                        $data['message'][0] = "password did not match.";
                    }
                }
                else
                {
                    $data['status'] = 'warning';
                    $data['message'][0] = 'Username already used.';
                }
            }
            else
            {
                $data['status'] = 'warning';
                $data['message'][0] = 'Email already used.';
            }
         }
         else
         {
            $data['status'] = "warning";
            $data['message'] = $validator->messages();
         }
         return json_encode($data);
    }

    public function payment()
    {
        $customer_information["new_account"] = true;
        $cart_info = Cart::get_info(Self::$shop_id);

        Cart::customer_set_info_ec_order(Self::$shop_id, $cart_info, $customer_information);

        // $register_session = Session::get('mlm_register_step_1');
        // if($register_session == null)
        // {
        //     return Redirect::to('/member/register');
        // }
        // // return $register_session;
        // $register_session_2 = Session::get('mlm_register_step_2');
        // if($register_session_2 == null)
        // {
        //     return Redirect::to('/member/register/package');
        // }

        // $register_session_3 = Session::get('mlm_register_step_3');
        // if($register_session_3 == null)
        // {
        //     return Redirect::to('/member/register/shipping');
        // }

        //ONLINE PAYMENT
        $data["_payment_method"] = Tbl_online_pymnt_method::link(Self::$shop_id)->where("method_shop_id", Self::$shop_id)->get();

        $data["_product"]        =  Cart::get_info(Self::$shop_id)["tbl_ec_order_item"];
        foreach($data["_product"] as $key=>$product)
        {
            $data["_product"][$key]["variant_name"] = Tbl_ec_variant::where("evariant_id", $product["item_id"])->value("evariant_item_label");
        }
        $data["order"]           =  Cart::get_info(Self::$shop_id)["tbl_ec_order"];

        return view("mlm.register.payment", $data);
    }

    public function payment_post()
    {
        $customer_info['method_id'] = Request::input('payment_method_id');
        $customer_set_info_response = Cart::customer_set_info(Self::$shop_id, $customer_info);

        if($customer_set_info_response["status"] == "error")
        { 
            $data['status'] = 'warning';
            return Redirect::back()->with("error", $customer_set_info_response["status_message"])->send();
        }
        else
        {
            if (isset(Cart::get_info(Self::$shop_id)["tbl_ec_order"]["payment_method_id"])) 
            {
                return Cart::process_payment(Self::$shop_id, "register");
            }
            else
            {
                $data['status'] = 'warning';
                return Redirect::to("/member/register")->send();
            }
        }
    }

    public function package()
    {
        // $register_session = Session::get('mlm_register_step_1');
        // if($register_session == null)
        // {
        //     return Redirect::to('/member/register');
        // }

        $warehouse_id = Ecom_Product::getWarehouseId(Self::$shop_id);
        $data['_product'] = Tbl_ec_product::itemVariant()->inventory($warehouse_id)
        ->where('ec_product_membership', '!=', 0)
        ->price()->where("eprod_shop_id",  Self::$shop_id)->where("tbl_ec_product.archived", 0)->get();

        return view("mlm.register.package", $data);
    }

    public function package_post()
    {
        $info['variant_id'] = Request::input('variant_id');
        $product_stocks = Request::input('product_stocks');
        if($product_stocks[$info['variant_id']] > 0)
        {
            if(isset($info['variant_id']))
            {
                /* Add Package */
                Cart::add_to_cart($info["variant_id"], 1, Self::$shop_id, true);

                /* Redirect */
                $d['variant_id'] = $info['variant_id'];
                Session::put('mlm_register_step_2', $d);
                $data['status'] = 'success';
                $data['message'][0] = 'Success!';
                $data['link'] = '/member/register/shipping';
            }
            else
            {
                $data['status'] = 'warning';
                $data['message'][0] = 'Invalid Package.';
            }
        }
        else
        {
            $data['status'] = 'warning';
            $data['message'][0] = 'Out of Stock';
        }


        return json_encode($data);
    }

    public function package_get_details_product()
    {
        
    }
    public function shipping()
    {
        // $register_session = Session::get('mlm_register_step_1');
        // if($register_session == null)
        // {
        //     return Redirect::to('/member/register');
        // }
        // // return $register_session;
        // $register_session_2 = Session::get('mlm_register_step_2');
        // if($register_session_2 == null)
        // {
        //     return Redirect::to('/member/register/package');
        // }


        $data["_province"]  = Tbl_locale::where("locale_parent", 0)->get();
        $city_parent        = Request::input("city_parent") ? Request::input("city_parent") : $data["_province"][0]->locale_id; 
        $data["_city"]      = Tbl_locale::where("locale_parent", $city_parent)->get();
        $barangay_parent    = Request::input("barangay_parent") ? Request::input("barangay_parent") : $data["_city"][0]->locale_id; 
        $data["_barangay"]  = Tbl_locale::where("locale_parent", $barangay_parent)->get();

        return view("mlm.register.shipping", $data);
    }

    public function shipping_post()
    {
        $s['customer_state'] = $this->locale_id_to_name(Request::input('customer_state'));
        $s['customer_city'] = $this->locale_id_to_name(Request::input('customer_city'));
        $s['customer_zip'] = $this->locale_id_to_name(Request::input('customer_zip'));
        $s['customer_street'] = Request::input('customer_street');
    
        $rules['customer_state'] = 'required';
        $rules['customer_city'] = 'required';
        $rules['customer_zip'] = 'required';
        $rules['customer_street'] = 'required';

        $validator = Validator::make($s, $rules);
        if (!$validator->passes()) 
         {
            $data['status'] = "warning";
            $data['message'] = $validator->messages();
            return json_encode($data);
         }
         else
         {
            $customer_info["shipping_state"]   = $s["customer_state"];
            $customer_info["shipping_city"]    = $s["customer_city"];
            $customer_info["shipping_zip"]     = $s["customer_zip"];
            $customer_info["shipping_street"]  = $s["customer_street"];
            $customer_set_info_response        = Cart::customer_set_info(Self::$shop_id, $customer_info);
            
            if($customer_set_info_response["status"] == "error")
            { 
                $data['status'] = 'warning';
                $data['message'][0] = $customer_set_info_response["status_message"];
            }
            else
            {
                /* Redirect */
                Session::put('mlm_register_step_3', $s);
                $data['status'] = 'success';
                $data['message'][0] = 'Sucess!';
                $data['link'] = '/member/register/payment';
            }

            return json_encode($data);
         }
    }

    public function getLocale($id)
    {
        $json["status"] = "success";
        $json["locale"] = Tbl_locale::where("locale_parent", $id)->get();

        return json_encode($json);
    }

    public function session()
    {
        dd(Cart::get_info(Self::$shop_id));
    }

    public function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor=1 ) 
    {
        $code_string = "";
        // Translate the $text into barcode the correct $code_type
        if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
            $chksum = 104;
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211214" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code128a" ) {
            $chksum = 103;
            $text = strtoupper($text); // Code 128A doesn't support lower case
            // Must not change order of array elements as the checksum depends on the array's key to validate final code
            $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
            $code_keys = array_keys($code_array);
            $code_values = array_flip($code_keys);
            for ( $X = 1; $X <= strlen($text); $X++ ) {
                $activeKey = substr( $text, ($X-1), 1);
                $code_string .= $code_array[$activeKey];
                $chksum=($chksum + ($code_values[$activeKey] * $X));
            }
            $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

            $code_string = "211412" . $code_string . "2331112";
        } elseif ( strtolower($code_type) == "code39" ) {
            $code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                $code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
            }

            $code_string = "1211212111" . $code_string . "121121211";
        } elseif ( strtolower($code_type) == "code25" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0");
            $code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

            for ( $X = 1; $X <= strlen($text); $X++ ) {
                for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
                    if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
                        $temp[$X] = $code_array2[$Y];
                }
            }

            for ( $X=1; $X<=strlen($text); $X+=2 ) {
                if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
                    $temp1 = explode( "-", $temp[$X] );
                    $temp2 = explode( "-", $temp[($X + 1)] );
                    for ( $Y = 0; $Y < count($temp1); $Y++ )
                        $code_string .= $temp1[$Y] . $temp2[$Y];
                }
            }

            $code_string = "1111" . $code_string . "311";
        } elseif ( strtolower($code_type) == "codabar" ) {
            $code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
            $code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

            // Convert to uppercase
            $upper_text = strtoupper($text);

            for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
                    if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
                        $code_string .= $code_array2[$Y] . "1";
                }
            }
            $code_string = "11221211" . $code_string . "1122121";
        }

        // Pad the edges of the barcode
        $code_length = 20;
        if ($print) {
            $text_height = 30;
        } else {
            $text_height = 0;
        }
        
        for ( $i=1; $i <= strlen($code_string); $i++ ){
            $code_length = $code_length + (integer)(substr($code_string,($i-1),1));
            }

        if ( strtolower($orientation) == "horizontal" ) {
            $img_width = $code_length*$SizeFactor;
            $img_height = $size;
        } else {
            $img_width = $size;
            $img_height = $code_length*$SizeFactor;
        }

        $image = imagecreate($img_width, $img_height + $text_height);
        $black = imagecolorallocate ($image, 0, 0, 0);
        $white = imagecolorallocate ($image, 255, 255, 255);

        imagefill( $image, 0, 0, $white );
        if ( $print ) {
            imagestring($image, 5, 31, $img_height, $text, $black );
        }

        $location = 10;
        for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
            $cur_size = $location + ( substr($code_string, ($position-1), 1) );
            if ( strtolower($orientation) == "horizontal" )
                imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
            else
                imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
            $location = $cur_size;
        }
        
        // Draw barcode to the screen or save in a file
        if ( $filepath=="" ) {
            header ('Content-type: image/png');
            imagepng($image);
            imagedestroy($image);
        } else {
            imagepng($image,$filepath);
            imagedestroy($image);       
        }
    }

    public function barcodes()
    {
        /*
         *  Author  David S. Tufts
         *  Company davidscotttufts.com
         *    
         *  Date:   05/25/2003
         *  Usage:  <img src="/barcode.php?text=testing" alt="testing" />
         */

        // For demonstration purposes, get pararameters that are passed in through $_GET or set to the default value
        $filepath = (isset($_GET["filepath"])?$_GET["filepath"]:"");
        $text = (isset($_GET["text"])?$_GET["text"]:"0");
        $size = (isset($_GET["size"])?$_GET["size"]:"20");
        $orientation = (isset($_GET["orientation"])?$_GET["orientation"]:"horizontal");
        $code_type = (isset($_GET["codetype"])?$_GET["codetype"]:"code128");
        $print = (isset($_GET["print"])&&$_GET["print"]=='true'?true:false);
        $sizefactor = (isset($_GET["sizefactor"])?$_GET["sizefactor"]:"1");

        // This function call can be copied into your project and can be made from anywhere in your code
        $this->barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
    }

    public function all_slot()
    {
        $all_slot = Tbl_mlm_slot::membership()->customer()->get();
        $card = null;
        foreach($all_slot as $key => $value)
        {
            if($value->membership_name == 'V.I.P Silver')
            {
                $color = 'silver';
            }
            else if($value->membership_name == 'V.I.P Gold')
            {
                $color = 'gold';
            }
            else if($value->membership_name == 'V.I.P Platinum ')
            {
                $color = 'red';
            }
            else
            {
                $color = 'discount';
            }
            $name = name_format_from_customer_info($value);
            $membership_code = $value->slot_no;
            $card .= $this->card_all($color, $name,  $membership_code);
        }
        if(Request::input('pdf') == 'true')
        {
            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadHTML($card);
            return $pdf->inline();
            return Pdf_global::show_pdf($card);
        }
        else
        {
            return $card;
        }
    }

    public function card_all($color, $name,  $membership_code)
    {
        $data['color'] = $color;
        $data['name'] = $name;
        $data['membership_code'] = $membership_code;

        return view("card", $data);
    }

    public function card()
    {
        $data['color'] = Request::input("color");
        $data['name'] = Request::input("name");
        $data['membership_code'] = Request::input("membership_code");

        return view("card", $data);
    }
    public function message()
    {
        $data["message"] = Request::input("message");
        return view("member.message", $data);
    }
}