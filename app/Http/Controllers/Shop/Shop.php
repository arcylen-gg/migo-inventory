<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;
use App\Models\Tbl_shop;
use App\Models\Tbl_content;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_country;
use App\Models\Tbl_customer;
use App\Models\Tbl_recaptcha_setting;
use App\Globals\Ecom_Product;
use App\Globals\SocialNetwork;
use App\Globals\Cart;
use App\Globals\Settings;
use App\Models\Tbl_membership_code;
use App\Globals\Mlm_member;
use App\Globals\Customer;
use App\Globals\MLM2;
use App\Globals\Item;

class Shop extends Controller
{
	public $shop_info;
    public $shop_theme;
    public $shop_theme_color;
    public $mlm_member;
    public $privilage_card_holder;
    public static $customer_info;
    public static $slot_now;

    public static $lead;

    public function __construct()
    {
    	$domain = get_domain();
        $data['lead'] = null;
        $data['lead_code'] = null;
        $data['customer_info'] = null;
    	$check_domain = Tbl_shop::where("shop_domain", $domain)->first();

        if(hasSubdomain())
        {
            if (isset($_SERVER['HTTP_HOST'])) 
            {
                $url = $_SERVER['HTTP_HOST'];
            }
            else
            {
                $url = $_SERVER['SERVER_NAME'];
            }
            
			$host = explode('.', $url);
			$subdomains = array_slice($host, 0, count($host) - 2 );
			$subdomain = $subdomains[0];
        	$this->shop_info = $shop_info = Tbl_shop::where("shop_key", $subdomain)->first();

            if(!$this->shop_info)
            {
                $check_subdomain = Tbl_customer::where('mlm_username', $subdomain)->first();
                $lead_e = $check_subdomain;
                if($lead_e)
                {
                    $shop_id = $lead_e->shop_id;    
                    $this->shop_info = $shop_info = Tbl_shop::where("shop_id", $shop_id)->first();
                    Self::$lead = $lead_e;

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
                }
                elseif($check_domain)
                {
                    $this->shop_info = $check_domain;
                }
                else
                {
                    die("Page not found.");
                }
            }
        }
        elseif($check_domain)
        {
        	$this->shop_info = $check_domain;
        }
        else
        {
        	die("Page not found.");
        }

        $shop_theme_info        = $this->get_shop_theme_info();
        $this->shop_theme       = $this->shop_info->shop_theme;
        $this->shop_theme_color = $this->shop_info->shop_theme_color;

        $this->shop_theme_info  = $shop_theme_info;

        if ($this->shop_theme == "ecommerce-1" || $this->shop_theme == "intogadgets")
        {
            $this->middleware(function ($request, $next)
            {  
                $this->get_account_logged_in();
                
                return $next($request);
            });
        }

        $company_column         = array('company_name', 'company_acronym', 'company_logo', 'receipt_logo', 'company_address', 'company_email', 'company_mobile', 'company_hour');
        $company_info           = collect(Tbl_content::where("shop_id", $this->shop_info->shop_id)->whereIn('key', $company_column)->get())->keyBy('key');
        $global_cart            = Cart::get_cart($this->shop_info->shop_id);
        $country                = Tbl_country::get();
        
        if ($this->shop_theme == "sovereign") 
        {
            $products = Ecom_Product::getAllProduct($this->shop_info->shop_id);
            View::share("global_product", $products);
        }
        elseif ($this->shop_theme == "intogadgets") 
        {
            $popular_tags = DB::table("tbl_ec_popular_tags")->where("shop_id", $this->shop_info->shop_id)->where("tag_approved",1)->orderBy("count", "DESC")->get();
            View::share("_popular_tags", $popular_tags);

            $product_category       = Ecom_Product::getAllCategory($this->shop_info->shop_id);
            View::share("_categories", $product_category);
        }
        elseif ($this->shop_theme == "ecommerce-1" || $this->shop_theme == "philtech")
        {
            $product_category       = Ecom_Product::getAllCategory($this->shop_info->shop_id);
            View::share("_categories", $product_category);
        }
        elseif ($this->shop_theme == "3xcell") 
        {
            $product_category       = Ecom_Product::getAllCategory($this->shop_info->shop_id);
            View::share("_categories", $product_category);
        }
        
        if ($this->shop_theme != "ecommerce-1" && $this->shop_theme != "intogadgets")
        {
            $this->middleware(function ($request, $next)
            {  
                /* FOR NEW VERSION MEMBER'S AREA */
                $account                = session("mlm_member");

                if (isset($account["email"])) 
                {
                    $check_account      = Customer::check_account($this->shop_info->shop_id, $account["email"], $account["auth"]);
                }
                else
                {
                    $check_account      = null;
                }
                
                Self::$customer_info    = $check_account;
                $mlm_member             = false;
                $privilage_card_holder  = false;
                
                if(Self::$customer_info)
                {
                    $mlm_member        = MLM2::is_mlm_member($this->shop_info->shop_id, Self::$customer_info->customer_id);
                    $this->mlm_member   = $mlm_member;
                    $privilage_card_holder = MLM2::is_privilage_card_holder($this->shop_info->shop_id, Self::$customer_info->customer_id);
                    $this->privelege_card_holder   = $privilage_card_holder;
                }

                /* Set Profile Image */
                if(isset(Self::$customer_info->profile)) 
                {
                    if(Self::$customer_info->profile) 
                    {
                        $profile_image = "/" . Self::$customer_info->profile;
                        $profile_image = str_replace("//", "/", $profile_image);
                    }
                    else
                    {
                        $profile_image = "/themes/brown/img/user-placeholder.png";
                    }
                }
                else
                {
                    $profile_image = "/themes/brown/img/user-placeholder.png";
                }

                View::share("customer", Self::$customer_info);
                View::share("mlm_member", $mlm_member);
                View::share("profile_image", $profile_image);
                View::share("privilage_card_holder", $privilage_card_holder);

                return $next($request);
            });
        }
        else
        {
            $this->middleware(function ($request, $next)
            {  
                View::share("customer", Self::$customer_info);
                View::share("customer_info_a", Self::$customer_info);
                
                return $next($request);
            });
        }

        Settings::set_mail_setting($this->shop_info->shop_id);

        $data['google_app_id'] = SocialNetwork::get_keys($this->shop_info->shop_id, 'googleplus')['app_id'];

        // Unity
        if ($this->shop_info->shop_id == "90") 
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
                        $allow_captcha = true;
                    }
                    else
                    {
                        $allow_captcha = false;
                    }
                }
                else
                {
                    $allow_captcha = false;
                }
            }
            else
            {
                $allow_captcha = false;
            }

            View::share("allow_captcha", $allow_captcha);
        }

        View::share("slot_now", Self::$slot_now);
        
        View::addLocation(base_path() . '/public/themes/' . $this->shop_theme . '/views/');
        View::share("shop_info", $this->shop_info);
        View::share("shop_theme", $this->shop_info->shop_theme);
        View::share("shop_theme_color", $this->shop_info->shop_theme_color);
        View::share("shop_theme_info", $shop_theme_info);
        View::share("company_info", $company_info);
        View::share("shop_id", $this->shop_info->shop_id);
        View::share("global_cart", $global_cart);
        View::share("country", $country);
        View::share("lead", $data['lead']);
        View::share("google_app_id", $data['google_app_id']);
        View::share("customer_info", $data['customer_info']);
        View::share("lead_code", $data['lead_code']);
    }
    public function file($theme, $type, $filename)
    {
        echo require_once(base_path("resources/views/themes/" . $theme . "/" . $type . "/" . $filename));
    }
    public function guest_only()
    {
        if(session("mlm_member"))
        {
            return Redirect::to("/members")->send();
        }
    }
    public function logged_in_member_only()
    {

        if(!session("mlm_member"))
        {
            return Redirect::to("/members/login")->with("error", "<b>Session Expired</b><br>Try loggin in again.");
        }
        else
        {
            $account        = session("mlm_member");
            $check_account  = Customer::check_account($this->shop_info->shop_id, $account["email"], $account["auth"]);
            
            if(!$check_account)
            {
                return Redirect::to("/members/login")->with("error", "<b>Authentication Problem</b><br>The email/password you entered doesn't exist.");
            }
        }
    }
    public function get_account_logged_in()
    {
        if(Session::get('mlm_member') != null)
        {
            $session = Session::get('mlm_member');
            Self::$customer_info = isset($session['customer_info']) ? $session['customer_info'] : null;
            if(isset($session['slot_now']) && $session['slot_now'])
            {
                Self::$slot_now = $session['slot_now'];
            }
        }
        else
        {
            Self::$customer_info = null;
            Self::$slot_now = null;
        }
    }
    public function get_shop_theme_info()
    {
        $shop_theme_info = $string = file_get_contents("../public/themes/" . $this->shop_info->shop_theme . "/page.json");
        $shop_theme_info = json_decode($string);
        foreach ($shop_theme_info as $key => $value) 
        {
            foreach ($value as $keys => $values) 
            {
                $get = Tbl_content::where("key", $keys)->where("type", $values->type)->where("shop_id", $this->shop_info->shop_id)->first();

                if ($get) 
                {
                    $shop_theme_info->$key->$keys->type = $get->type;
                    $shop_theme_info->$key->$keys->default = $get->value;
                }
            }
        }

        return $shop_theme_info;
    }
}