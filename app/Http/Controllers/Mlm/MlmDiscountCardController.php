<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Validator;
use Carbon\Carbon;
use App\Models\Tbl_customer;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_discount_card_log;
use App\Globals\Mlm_member;
use App\Globals\Mail_global;
class MlmDiscountCardController extends Mlm
{
    public function index()
    {
    	return Self::show_maintenance();
        $data["page"] = "Cheque";
        return view("mlm.cheque", $data);
    }
    public static function use_discount()
    {
    	$shop_id = Self::$shop_id;
    	$customers = Tbl_customer::where('shop_id', $shop_id)
    	->where('ismlm', 1)
    	->get();

        // $with_discount = Tbl_mlm_discount_card_log::whereNotNull('discount_card_customer_holder')->pluck('discount_card_customer_holder')->toArray();
        $with_slot = Tbl_mlm_slot::where('shop_id', $shop_id)->pluck('slot_owner')->toArray();
        
        $customers = Tbl_customer::whereNotIn('customer_id', $with_slot)
        // ->whereNotIn('customer_id', $with_discount)
        ->where('shop_id', $shop_id)
        ->where('ismlm', 1)
        ->get();
        $data['expiry'] = Carbon::now()->addYear(1);
        $data['discount_card_log_id'] = Request::input('discount_card_log_id');
    	$data['customers'] = $customers;
    	return view('mlm.discount_card.discount_card',$data);
    }
    public static function get_customer_info($customer_id)
    {
    	return Mlm_member::get_customer_info($customer_id);
    }
    public static function submit_use_discount_card()
    {
        $discount_card_log_id = Request::input('discount_card_log_id_a');
        $update['discount_card_log_date_used'] = Carbon::now();
        $update['discount_card_customer_holder'] =Self::$customer_id;
        $update['discount_card_log_date_expired'] = Carbon::now()->addYear(1);
        Tbl_mlm_discount_card_log::where('discount_card_log_id', $discount_card_log_id)->update($update);
        $data['response_status'] = 'success';
        $data['message'] = 'Success';
        return json_encode($data);
    }
    public static function submit_use_discount_card_2()
    {
        // return $_POST;
         // return Request::input('discount_card_log_id');
        $validate['customer_id'] = Request::input('customer_id');
        $rules['customer_id'] = 'required';
        $discount_card_log_id = Request::input('discount_card_log_id_a');
        $validator = Validator::make($validate,$rules);
        if ($validator->passes())
        {
            
            $count = Tbl_mlm_slot::where('slot_owner', $validate['customer_id'])->count();
            if($count === 0)
            {
                if($discount_card_log_id != null)
                {
                    $count_discount_holder = Tbl_mlm_discount_card_log::where('discount_card_customer_holder', $validate['customer_id'])
                    ->count();
                    
                    if($count_discount_holder == 0)
                    {
                        $update['discount_card_log_date_used'] = Carbon::now();
                        $update['discount_card_customer_holder'] = $validate['customer_id'];
                        $update['discount_card_log_date_expired'] = Carbon::now()->addYear(1);
                        Tbl_mlm_discount_card_log::where('discount_card_log_id', $discount_card_log_id)->update($update);
                        $data['response_status'] = 'success';
                        $data['message'] = 'Success';

                        Mail_global::mail_discount_card($discount_card_log_id);
                    }
                    else
                    {
                       $data['response_status'] = "warning";
                        $data['message'] = "This account already has a discount card.";  
                    }
                }
                else
                {
                    $data['response_status'] = "warning";
                    $data['message'] = "Invalid. Discount Card.";   
                }
            }
            else
            {
                $data['response_status'] = "warning";
                $data['message'] = "Invalid. This Account Already Has Slot.";
            }

        }
         else
        {
            $data['response_status'] = "warning_2";
            $data['warning_validator'] = $validator->messages();
        }
        return json_encode($data);
    }
}