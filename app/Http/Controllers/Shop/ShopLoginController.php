<?php
namespace App\Http\Controllers\Shop;
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
use App\Models\Tbl_pressiq_user;

use App\Globals\Mlm_member;
use App\Globals\Settings;
use App\Globals\Cart;
class ShopLoginController extends Shop
{
    public function index()
    {
        $data["page"] = "Login";
        return view("login", $data);
    }

    public function signin()
    {
        Session::forget('message');
        $user_password=request("user_password");
        $user_email=request('user_email');
        $data["page"] = "Login";
        if(Session::exists('user_email'))
        {
            return Redirect::to("/");
        }
        else
        {
            $user_data = DB::table('tbl_pressiq_user')->where('user_email', $user_email)->first();
            if(request()->isMethod("post"))
            {   
                if($user_email != null && $user_password != null)
                {   
                    /* CHECK E-MAIL EXIST */
                    if($user_data)
                    {
                        $password = Crypt::decrypt($user_data->user_password);
                        /* CHECK IF PASSWORD IS CORRECT */
                        if($user_password == $password)
                        {
                            Session::put('user_email', $user_data->user_email);
                            Session::put('user_first_name',$user_data->user_first_name);
                            Session::put('user_last_name',$user_data->user_last_name);
                            Session::put('user_company_name',$user_data->user_company_name);
                            Session::put('user_company_image',$user_data->user_company_image);
                            Session::put('pr_user_level',$user_data->user_level);
                            Session::put('pr_user_id',$user_data->user_id);
                            
                            $level=session('pr_user_level');
                           if($level!="1")
                           {
                                return Redirect::to("/pressuser/dashboard");
                           }
                           else
                           {
                                return Redirect::to("/pressadmin/dashboard");
                           }
                        }
                        else
                        {
                            Session::put('message',"The Email / Password is incorrect");
                            return view("signin",$data);
                        }        
                    }
                    else
                {
                    Session::put('message',"The Email / Password is incorrect");
                    return view("signin",$data);
                }
                }
                else
                {
                    Session::put('message',"Input your Email and Password");
                    return view("signin",$data);
                }
                                 
            }
            else
            { 
                return view("signin",$data);
            }  
        }
    }

      public function thank_you()
    {
        
        $data["page"] = "Thank You";
        return view("press_user.thank_you", $data);
    }

    
    public function submit()
    {
    	$email = Request::input('email');
		$password = Request::input('password');
		if($email != null || $password != null)
		{
			$data['type'] = 'error';
			$data['message'] = 'Invalid Username/Password';
			$count = Tbl_customer::where('email', $email)->count();
			if($count >= 1)
			{
				$enc_pass = Crypt::encrypt($password);
				$user = Tbl_customer::where('email', $email)->first();
				$user_pass = Crypt::decrypt($user->password);
                if($user->archived == 0)
                {
                    if($password == $user_pass)
                    {
                        $shop_id = $user->shop_id;
                        Mlm_member::add_to_session($shop_id, $user->customer_id);

                        $customer_info["email"] = trim(Request::input("email"));
			            $customer_info["new_account"] = false;
			            $customer_info["password"]= Request::input("password");
			            $customer_set_info_response = Cart::customer_set_info($this->shop_info->shop_id, $customer_info, array("check_account"));

                        $data['type'] = 'success';
                        $data['message'] = 'You will be redirected.';
                    }
                    else
                    {
                        $data['type'] = 'error';
                        $data['message'] = 'Invalid Username/Password';
                    }
                }
				else
                {
                    $data['type'] = 'error';
                    $data['message'] = 'Sorry Your Account is Disabled, Please Contact the Administrator.';
                }
			}
			else
			{
				$data['type'] = 'error';
				$data['message'] = 'Username Does Not Exist';
			}
		}
		else
		{
			$data['type'] = 'error';
			$data['message'] = 'Invalid Username/Password';
		}

		if (Request::input("global")) 
		{
			$data["from"] = "global_login";
		}
		else
		{
        	$data['from'] = "account_login";
		}
    
    	return json_encode($data);
    }
}