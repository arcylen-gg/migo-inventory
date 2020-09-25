<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DB;
use Validator;
use App\Globals\Mlm_member;
use App\Models\Tbl_customer;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_shop;
use App\Models\Tbl_mlm_encashment_settings;
use App\Globals\Cards;
use App\Models\Tbl_mlm_slot;
use App\Globals\Pdf_global;
use Input;
use File;
use App\Globals\Mlm_repurchase_member;
use App\Models\Tbl_tree_sponsor;
use App\Globals\Mlm_slot_log;
class MlmProfileController extends Mlm
{
	public function index()
	{
		$data['customer_info'] = Tbl_customer::where('tbl_customer.customer_id', Self::$customer_id)
		// ->info()
		->first();

		$data['slot_info'] = Tbl_mlm_slot::where('slot_id', Self::$slot_id)
		->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
		->first();

		$data['direct_count'] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
		->where('sponsor_tree_level', 1)
		->count();

		$data['current_wallet'] = Mlm_slot_log::get_sum_wallet(Self::$slot_id);

		$data['country'] = DB::table('tbl_country')->get();
		$data['customer_address'] = DB::table('tbl_customer_address')->where('customer_id', Self::$customer_id)->first();
    	$data['other_info'] = DB::table('tbl_customer_other_info')->where('customer_id', Self::$customer_id)->first();
    	$data['cus_info'] = Mlm_member::get_customer_info(Self::$customer_id);

    	if(Self::$slot_id != null)
    	{
    		$access['PhilTECH'] = 'PhilTECH';
    		$access['sovereign'] = 'sovereign';
    		$access['alphaglobal'] = 'alphaglobal';
    		if(isset($access[Self::$shop_info->shop_key]))
    		{
    			$shop_id = Self::$shop_id;
    			$data['cus_info'] = Mlm_member::get_customer_info_w_slot(Self::$customer_id, Self::$slot_id);
	    		$data['card'] = $this->card(Self::$slot_id);
	    		$data['bank'] = DB::table('tbl_encashment_bank_deposit')->where('shop_id', Self::$shop_id)->where('encashment_bank_deposit_archive', 0)->get();
	    		$data['customer_payout'] = DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->first();
	    		$data['encashment_settings'] = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();

	    		$data['encashment'] =  view('mlm.profile.encashment', $data);
	    		if(Request::input('pdf') == 'true')
	    		{
	    			return Pdf_global::show_image($data['card']);
	    		}
    		}
    	}
    	$data['new_member'] = Tbl_mlm_slot::where('slot_sponsor', Self::$slot_id)
    	->customer()
    	->orderBy('slot_id', 'DESC')
    	->take(6)
    	->get();

		return view('mlm.profile.profilev2', $data);
	}
    public function index2()
    {
    	$data = [];
    	$data['country'] = DB::table('tbl_country')->get();
    	$data['customer_address'] = DB::table('tbl_customer_address')->where('customer_id', Self::$customer_id)->first();
    	$data['other_info'] = DB::table('tbl_customer_other_info')->where('customer_id', Self::$customer_id)->first();
    	$data['cus_info'] = Mlm_member::get_customer_info(Self::$customer_id);
    	if(Self::$slot_id != null)
    	{
    		if(Self::$shop_id == 1)
    		{
    			$shop_id = Self::$shop_id;
    			$data['cus_info'] = Mlm_member::get_customer_info_w_slot(Self::$customer_id, Self::$slot_id);
	    		$data['card'] = $this->card(Self::$slot_id);
	    		$data['bank'] = DB::table('tbl_encashment_bank_deposit')->where('shop_id', Self::$shop_id)->where('encashment_bank_deposit_archive', 0)->get();
	    		$data['customer_payout'] = DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->first();
	    		$data['encashment_settings'] = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();

	    		$data['encashment'] =  view('mlm.profile.encashment', $data);
	    		if(Request::input('pdf') == 'true')
	    		{
	    			return Pdf_global::show_image($data['card']);
	    		}
    		}
    	}

    	return view('mlm.profile.profilev2', $data);
    }
    public function password()
    {
    	$customer_id = Self::$customer_id;
    	$password_o	= Request::input('password_o');
		$password_n	= Request::input('password_n');
		$password_n_c	= Request::input('password_n_c');
		$old_password = Crypt::decrypt(Self::$customer_info->password);

		if($password_o == $old_password)
		{
			if($password_n == $password_n_c)
			{
				$update['password'] = Crypt::encrypt($password_n);
				Tbl_customer::where('customer_id', Self::$customer_id)->update($update);
				$data['status'] = 'success';
				$data['message'] = 'Success';
				Mlm_member::add_to_session_edit(Self::$shop_id, Self::$customer_id, Self::$slot_id);
			}
			else
			{
				$data['status'] = 'warning';
				$data['message'] = 'Wrong Password Confirmation';
			}
		}
		else
		{
			$data['status'] = 'warning';
			$data['message'] = 'Wrong Password';
		}
		return json_encode($data);
    }
    public function contact()
    {
    	$customer_id = Self::$customer_id;
    	$email = Request::input('email');
		$customer_phone = Request::input('customer_phone');
		$customer_mobile = Request::input('customer_mobile');
		$customer_fax = Request::input('customer_fax');

		$validate['email'] = $email;
		$validate['customer_phone'] = $customer_phone;
		$validate['customer_mobile'] = $customer_mobile;
		$validate['customer_fax'] = $customer_fax;

		$rules['email'] = 'required|email';
		$rules['customer_phone'] = 'required';
		$rules['customer_mobile'] = 'required';
		$rules['customer_fax'] = 'required';
	    $validator = Validator::make($validate,$rules);
	    if ($validator->passes())
	    {
	    	$count_other = DB::table('tbl_customer_other_info')->where('customer_id', $customer_id)->count();
	    	if($count_other >= 1)
	    	{
	    		$update['customer_phone'] = $customer_phone;
				$update['customer_mobile'] = $customer_mobile;
				$update['customer_fax'] = $customer_fax;
	    		DB::table('tbl_customer_other_info')->where('customer_id', $customer_id)->update($update);
	    	}
	    	else
	    	{
	    		$update['customer_id'] = $customer_id;
	    		$update['customer_phone'] = $customer_phone;
				$update['customer_mobile'] = $customer_mobile;
				$update['customer_fax'] = $customer_fax;
	    		DB::table('tbl_customer_other_info')->insert($update);
	    	}
	    	

	    	$update_2['email'] = $email;
	    	DB::table('tbl_customer')->where('customer_id', $customer_id)->update($update_2);

	    	$data['status'] = 'success';
			$data['message'] = 'Success';
			Mlm_member::add_to_session_edit(Self::$shop_id, Self::$customer_id, Self::$slot_id);
	    }
	    else
	    {
	    	$data['response_status'] = "warning_2";
    	    $data['warning_validator'] = $validator->messages();
	    }
	    return json_encode($data);
    }
    public function basic()
    {
    	
    	$customer_id = Self::$customer_id;
    	// tbl_customer
    	$b_day = Request::input('b_day');
		$country_id = Request::input('country_id');
    	// tbl_customer_address
    	$customer_state = Request::input('customer_state');
		$customer_city = Request::input('customer_city');
		$customer_zipcode = Request::input('customer_zipcode');
		$customer_street = Request::input('customer_street');

		$validate['b_day'] = Request::input('b_day');
		$validate['country_id'] = Request::input('country_id');
		$validate['customer_state'] = Request::input('customer_state');
		$validate['customer_city'] = Request::input('customer_city');
		$validate['customer_zipcode'] = Request::input('customer_zipcode');
		$validate['customer_street'] = Request::input('customer_street');

		$rules['b_day'] = 'required';
		$rules['country_id'] = 'required';
		$rules['customer_state'] = 'required';
		$rules['customer_city'] = 'required';
		$rules['customer_zipcode'] = 'required';
		$rules['customer_street'] = 'required';
		$validator = Validator::make($validate,$rules);
		if ($validator->passes())
	    {
	    	$count_address = DB::table('tbl_customer_address')->where('customer_id', $customer_id)->count();
	    	if($count_address >= 1)
	    	{
	    		$update['country_id'] = $country_id;
	    		$update['customer_state'] = $customer_state;
				$update['customer_city'] = $customer_city;
				$update['customer_zipcode'] = $customer_zipcode;
				$update['customer_street'] = $customer_street;
				$update['purpose'] = 'billing';
				DB::table('tbl_customer_address')->where('customer_id', $customer_id)->update($update);
	    	}
	    	else
	    	{
	    		$update['country_id'] = $country_id;
	    		$update['customer_id'] = $customer_id;
	    		$update['customer_state'] = $customer_state;
				$update['customer_city'] = $customer_city;
				$update['customer_zipcode'] = $customer_zipcode;
				$update['customer_street'] = $customer_street;
				$update['purpose'] = 'billing';
				DB::table('tbl_customer_address')->insert($update);
	    	}
	    	

			$update_2['b_day'] = $b_day;
			$update_2['country_id'] = $country_id;
			DB::table('tbl_customer')->where('customer_id', $customer_id)->update($update_2);

			$data['status'] = 'success';
			$data['message'] = 'Success';
			Mlm_member::add_to_session_edit(Self::$shop_id, Self::$customer_id, Self::$slot_id);
	    }
	    else
	    {
	    	$data['response_status'] = "warning_2";
    	    $data['warning_validator'] = $validator->messages();
	    }
	    return json_encode($data);
    }
    public function card($slot_id)
    {
    	$slot = Tbl_mlm_slot::where('slot_id', $slot_id)->membership()->customer()
    	->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_slot.slot_owner')
    	->first();
    	if(Self::$shop_id == 1)
    	{
    		
    		$a = Cards::show_card($slot);
    		return $a;
    		return Pdf_global::show_image($a);
    	}
    }
    public function card_discount($discount_card_log_id)
    {
    	$card_info = Tbl_mlm_discount_card_log::membership()
    	->where('discount_card_log_id', $discount_card_log_id)
        ->whereNotNull('tbl_mlm_discount_card_log.discount_card_customer_holder')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
        ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
        ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
        ->first();
        // dd($card_info);
       	return Cards::discount_card($card_info);
    }
    public function profile_picture_upload()
    {
    	# code...
    	if (Input::hasFile('profile_picture'))
		{
    	$shop_key = Tbl_shop::where('shop_id', Self::$shop_id)->value('shop_key');
    	$shop_id = Self::$shop_id;
    	$file               = Input::file('profile_picture');

        $fileArray = array('image' => $file);
        $rules = array(
          'image' => 'mimes:jpeg,jpg,png,gif|required|max:1000' // max 10000kb
        );
        $validator = Validator::make($fileArray, $rules);
        if ($validator->fails())
        {
            $data['status'] = 'warning';
            $data['message'] = 'file size exceeded';
            return Redirect::back();
        }
        // else
        // {
        // 	dd($validator->messages());
        // 	return Redirect::back();
        // }
        $extension          = $file->getClientOriginalExtension();
        $filename           = str_random(15).".".$extension;
        $destinationPath    = 'uploads/mlm/profile/'.$shop_key."-".$shop_id;

        if(!File::exists($destinationPath)) 
        {
            $create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
        }
        $upload_success    = Input::file('profile_picture')->move($destinationPath, $filename);
        /* SAVE THE IMAGE PATH IN THE DATABASE */
        $dis = '/uploads/mlm/profile/'.$shop_key."-".$shop_id;
        $image_path = $dis."/".$filename;
        $update['profile'] = $image_path;
        Tbl_customer::where('customer_id', Self::$customer_id)->update($update);

    	return Redirect::back();
    	}
    }
    public function update_encashment()
    {
    	// return $_POST;
    	$enchasment_settings_type = Request::input('enchasment_settings_type');
    	if($enchasment_settings_type  == 0)
    	{
    		$update['customer_payout_bank_account_name'] = Request::input('customer_payout_bank_account_name');
    		$update['customer_payout_bank_account_number'] = Request::input('customer_payout_bank_account_number');
    		$update['customer_payout_bank_branch'] = Request::input('customer_payout_bank_branch');
    		$update['encashment_bank_deposit_id'] = Request::input('encashment_bank_deposit_id');
    		DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->update($update);	
    		$data['status'] = 'success';
			$data['message'] = 'Encashment Details Updated';
    	}
    	else if($enchasment_settings_type == 1)
    	{
    		$update['customer_payout_name_on_cheque'] = Request::input('customer_payout_name_on_cheque');
    		DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->update($update);	
    		$data['status'] = 'success';
			$data['message'] = 'Encashment Details Updated';

    	}
    	# code...
    	return json_encode($data);
    }
}