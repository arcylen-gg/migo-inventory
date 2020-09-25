<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Validator;
use Redirect;
use Request;
use View;
use Input;
use File;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_member;
use App\Globals\Mlm_pre;
use App\Globals\Mlm_slot_log;
use App\Globals\Pdf_global;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_mlm_slot_wallet_log_refill;
use App\Models\Tbl_mlm_slot_wallet_log_transfer;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_wallet_log_refill_settings;
use App\Models\Tbl_mlm_encashment_settings;
use App\Models\Tbl_mlm_encashment_process;
use App\Models\Tbl_mlm_encashment_currency;

class MlmTransferController extends Mlm
{
    public function index()
    {
    	if(Self::$slot_id != null)
    	{
    		$data["page"] = "Transfer";
    		$data['break_down'] = Mlm_member::breakdown_wallet(Self::$slot_id);
        	return view("mlm.wallet.index", $data);
    	}
        else
        {
        	return Self::show_no_access();
        }
    }
    public function refill($message = null)
    {
        if(Self::$slot_id != null && Self::$shop_info->shop_key == "PhilTECH")
        {
            $data = [];
            if($message != null)
            {
                $data = $message;
            }
            $data['request'] = Tbl_mlm_slot_wallet_log_refill::where('slot_id', Self::$slot_id)->get();
            return view("mlm.wallet.refill", $data);
        }
        else
        {
            return Self::show_no_access();
        }
    }
    public function request_refill()
    {
        if(Self::$slot_id != null && Self::$shop_info->shop_key == "PhilTECH")
        {
            $data = [];
            Mlm_pre::pre_req(Self::$shop_id);
            $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', Self::$shop_id)->first();
            return view("mlm.wallet.request", $data);
        }
        else
        {
            return Self::show_no_access();
        }
    }
    public function request_refill_post()
    {
        if(Request::input('wallet_log_refill_amount_paid') <= 0)
        {   
            $data['status'] = 'warning';
            $data['message'] = 'Invalid Amount';
            return $this->refill($data);
        }
        else
        {
            if(Request::input('wallet_log_refill_remarks') == null)
            {
                $data['status'] = 'warning';
                $data['message'] = 'Invalid Remarks';
                return $this->refill($data);
            }
        }
        $password = Request::input('password');
        $password_l = Crypt::decrypt(Self::$customer_info->password);
        if($password != $password_l)
        {
            $data['status'] = 'warning';
            $data['message'] = 'Invalid Password';
            return $this->refill($data);
        }
        $shop_id = Self::$shop_id;
        $shop_key = Self::$shop_info->shop_key;

        $file               = Input::file('wallet_log_refill_attachment');

        if($file)
        {
            $fileArray = array('image' => $file);
            $rules = array(
              'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
            );
            $validator = Validator::make($fileArray, $rules);
            if ($validator->fails())
            {
                $data['status'] = 'warning';
                $data['message'] = 'file size exceeded';
                return $this->refill($data);
            }
            $extension          = $file->getClientOriginalExtension();
            $filename           = str_random(15).".".$extension;
            $destinationPath    = 'uploads/refill/attachment/'.$shop_key."-".$shop_id;

            if(!File::exists($destinationPath)) 
            {
                $create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
            }

            $upload_success    = Input::file('wallet_log_refill_attachment')->move($destinationPath, $filename);

            /* SAVE THE IMAGE PATH IN THE DATABASE */
            $image_path = $destinationPath."/".$filename;
        }
        else
        {
            $image_path = null;
        }

        $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->first();

        $insert['wallet_log_refill_date'] = Carbon::now();
        $insert['wallet_log_refill_amount'] =    Request::input('wallet_log_refill_amount_paid') - $data['settings']->wallet_log_refill_settings_processings_fee;
        $insert['wallet_log_refill_amount_paid'] = Request::input('wallet_log_refill_amount_paid');
        $insert['wallet_log_refill_processing_fee'] = $data['settings']->wallet_log_refill_settings_processings_fee;
        $insert['wallet_log_refill_approved'] = 0;
        $insert['wallet_log_refill_remarks'] = Request::input('wallet_log_refill_remarks');
        $insert['wallet_log_refill_attachment'] = $image_path;
        $insert['shop_id'] = Self::$shop_id;
        $insert['slot_id'] = Self::$slot_id;

        Tbl_mlm_slot_wallet_log_refill::insert($insert);
        $data['status'] = 'success';
        $data['message'] = 'Wallet Request is Sent';
        return Redirect::to('/mlm/refill');
        // $this->refill($data);

    }
    public function transfer()
    {
        if(Self::$slot_id != null && Self::$shop_info->shop_key == "PhilTECH")
        {
            $data = [];
            Mlm_pre::pre_req(Self::$shop_id);
            $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', Self::$shop_id)->first();
            $data['logs_transfer'] = Tbl_mlm_slot_wallet_log_transfer::where('wallet_log_transfer_slot_trans', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id','=', 'tbl_mlm_slot_wallet_log_transfer.wallet_log_transfer_slot_recieve')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->get();
            $data['logs_recieve'] = Tbl_mlm_slot_wallet_log_transfer::where('wallet_log_transfer_slot_recieve', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id','=', 'tbl_mlm_slot_wallet_log_transfer.wallet_log_transfer_slot_trans')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->get();
            return view("mlm.wallet.transfer", $data);
        }
        else
        {
            return Self::show_no_access();
        }
    }
    public function transfer_get_customer($id)
    {
        $shop_id = Self::$shop_id;
        $count_slot = Tbl_mlm_slot::where('shop_id', $shop_id)->where('slot_no', $id)->count();
        if($count_slot == 0)
        {
            // return Mlm_member::get_customer_info_w_slot($slot->slot_owner, $slot->slot_id);
            $count_code = Tbl_membership_code::where('shop_id', $shop_id)->where('membership_activation_code', $id)->count();
            if($count_code == 0)
            {
                return "<div class='col-md-12 alert alert-warning'>Invalid Slot/Membership Code</div>";
            }
            else
            {
                $count_code = Tbl_membership_code::where('shop_id', $shop_id)->where('membership_activation_code', $id)->first();
                $slot = Tbl_mlm_slot::where('shop_id', $shop_id)->where('slot_id', $count_code->slot_id)->first();
                if(isset($slot->slot_id))
                {
                    return Mlm_member::get_customer_info_w_slot($slot->slot_owner, $slot->slot_id);
                }
                else
                {
                    return "<div class='col-md-12 alert alert-warning'>Invalid Slot/Membership Code</div>";
                }
            }
        }
        else
        {
            $slot = Tbl_mlm_slot::where('shop_id', $shop_id)->where('slot_no', $id)->first();
            if(isset($slot->slot_id))
            {
                return Mlm_member::get_customer_info_w_slot($slot->slot_owner, $slot->slot_id);
            }
            else
            {
                $count_code = Tbl_membership_code::where('shop_id', $shop_id)->where('membership_activation_code', $id)->count();
                if($count_code == 0)
                {
                    return "<div class='col-md-12 alert alert-warning'>Invalid Slot/Membership Code</div>";
                }
                else
                {
                    $count_code = Tbl_membership_code::where('shop_id', $shop_id)->where('membership_activation_code', $id)->first();
                    $slot = Tbl_mlm_slot::where('shop_id', $shop_id)->where('slot_id', $count_code->slot_id)->first();
                    if(isset($slot->slot_id))
                    {
                        return Mlm_member::get_customer_info_w_slot($slot->slot_owner, $slot->slot_id);
                    }
                    else
                    {
                        return "<div class='col-md-12 alert alert-warning'>Invalid Slot/Membership Code</div>";
                    }
                }
            }
            
        }
    }
    public function transfer_submit()
    {
        $var = [];
        foreach($_POST as $key => $value)
        {
            $var[$key] = $value;
        }
        if(isset($var['slot_id']))
        {
            $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', Self::$shop_id)->first(); 
            $fee = $data['settings']->wallet_log_refill_settings_transfer_processing_fee;
            if($var['wallet_log_transfer_amount'] >= 1)
            {
                $sum = $fee + $var['wallet_log_transfer_amount'];
                $sum_wallet = Tbl_mlm_slot_wallet_log::where('wallet_log_status', 'released')->where('wallet_log_slot', Self::$slot_id)->sum('wallet_log_amount');
                if($sum <= $sum_wallet)
                {
                    $enc_pass = Crypt::decrypt(Self::$customer_info->password);
                    if($var['password'] == $enc_pass)
                    { 
                        $slot_info = Tbl_mlm_slot::where('slot_id', $var['slot_id'])
                        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                        ->first();
                        $insert['shop_id'] = Self::$shop_id;
                        $insert['wallet_log_slot'] = Self::$slot_id; 
                        $insert['wallet_log_slot_sponsor'] = $var['slot_id']; 
                        $insert['wallet_log_date_created'] = Carbon::now(); 
                        $insert['wallet_log_details'] = 'You have successfully transfer ' . $var['wallet_log_transfer_amount'] . ' to slot ' . $slot_info->slot_no . '. With processing fee of ' . $fee;   ; 
                        $insert['wallet_log_amount'] = $sum * - 1; 
                        $insert['wallet_log_plan'] = 'WALLET_TRANSFER'; 
                        $insert['wallet_log_status'] = 'released'; 
                        $insert['wallet_log_claimbale_on'] = Carbon::now(); 
                        Mlm_slot_log::slot_array($insert);

                        $insert['shop_id'] = Self::$shop_id;
                        $insert['wallet_log_slot'] =  $var['slot_id']; 
                        $insert['wallet_log_slot_sponsor'] = Self::$slot_id; 
                        $insert['wallet_log_date_created'] = Carbon::now(); 
                        $insert['wallet_log_details'] = 'You have successfully recieved ' . $var['wallet_log_transfer_amount'] . ' from slot ' . Self::$slot_now->slot_no; 
                        $insert['wallet_log_amount'] = $var['wallet_log_transfer_amount']; 
                        $insert['wallet_log_plan'] = 'WALLET_TRANSFER'; 
                        $insert['wallet_log_status'] = 'released'; 
                        $insert['wallet_log_claimbale_on'] = Carbon::now(); 
                        Mlm_slot_log::slot_array($insert);

                        $insert_2['wallet_log_transfer_amount'] =$var['wallet_log_transfer_amount'];
                        $insert_2['wallet_log_transfer_fee'] = $fee;
                        $insert_2['wallet_log_transfer_slot_trans'] = Self::$slot_id;
                        $insert_2['wallet_log_transfer_slot_recieve'] = $var['slot_id'];
                        $insert_2['wallet_log_transfer_date'] = Carbon::now();
                        $insert_2['shop_id'] = Self::$shop_id;
                        Tbl_mlm_slot_wallet_log_transfer::insert($insert_2);
                        $data['status'] = 'success';
                        $data['message'] = 'Transfer Success';

                    }
                    else
                    {
                        $data['status'] = 'warning';
                        $data['message'] = 'Password Incorrect';
                    }
                    
                }
                else
                {
                    $data['status'] = 'warning';
                    $data['message'] = 'Invalid Amount';
                }
            }
            else
            {
                $data['status'] = 'warning';
                $data['message'] = 'Invalid Amount';
            }
        }
        else
        {
            $data['status'] = 'warning';
            $data['message'] = 'Invalid slot/membership code. Please use an active slot/member';
        }
        return json_encode($data);
    }
    public function encashment()
    {
        if(Self::$slot_id != null)
        {
            $data = [];
            $shop_id = Self::$shop_id;
            $data['encashment_settings'] = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();

            $data['history'] = Tbl_mlm_slot_wallet_log::where('wallet_log_plan', 'ENCASHMENT')
            ->where('wallet_log_slot', Self::$slot_id)
            ->slot()->customer()
            ->join('tbl_mlm_encashment_process', 'tbl_mlm_encashment_process.encashment_process', '=', 'tbl_mlm_slot_wallet_log.encashment_process')
            ->paginate(10);

            $data['unprocessed'] = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', Self::$slot_id)
            ->where('wallet_log_status', 'released')
            ->whereNull('encashment_process')
            ->get()->toArray();

            $data['bank'] = DB::table('tbl_encashment_bank_deposit')->where('shop_id', Self::$shop_id)->where('encashment_bank_deposit_archive', 0)->get();
            $data['customer_payout'] = DB::table('tbl_customer_payout')->where('customer_id', Self::$customer_id)->first();
            $data['encashment_settings'] = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();
            $data['encashment'] =  view('mlm.profile.encashment', $data);

            $data['currency_set'] = Tbl_mlm_encashment_currency::where('en_cu_shop_id', $shop_id)
            // ->join('currency', 'currency.iso', '=', 'tbl_mlm_encashment_currency.iso')
            ->get()->keyBy('iso');


            return view('mlm.wallet.encashment', $data);
        }
        else
        {
            return Self::show_no_access();
        }  
    }
    public function check_details()
    {
        // Session::flash('success', "Membership Saved");
        $data['status'] = 'success';
        $customer_id = Self::$customer_id;
        $shop_id = Self::$shop_id;
        $encashment_settings = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();
        $count = DB::table('tbl_customer_payout')->where('customer_id', $customer_id)->count();
        if($count == 0)
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

            $data['status'] = 'warning';
            $data['message'] = 'Please set your encashment settings first at the profile tab.';
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
                            $data['status'] = 'warning';
                            $data['message'] = 'Please set your encashment settings first at the profile tab.';
                        }
                        else
                        {
                            $bank_details = DB::table('tbl_encashment_bank_deposit')->where('encashment_bank_deposit_id', $customer_payout->encashment_bank_deposit_id)->where('encashment_bank_deposit_archive', 0)->count();
                            if($bank_details >= 1)
                            {

                            }
                            else
                            {
                                $data['status'] = 'warning';
                                $data['message'] = 'Please set your encashment settings first at the profile tab.';
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
        return $data;
    }
    public function encashment_request()
    {
        $a = $this->check_details();
        if($a['status'] == 'warning')
        {
            return json_encode($a);
        }
        if(isset($_POST['wallet_log_id']))
        {
            $shop_id = Self::$shop_id;
            $wallet_log_id = Request::input('wallet_log_id');
            $affected = [];
            foreach($wallet_log_id as $key => $value)
            {
                $affected[$key] = Tbl_mlm_slot_wallet_log::where('wallet_log_id', $key)->first();
            }
            $date1 = Carbon::now();
            $date2 = 0;
            $sum = 0;
            foreach($affected as $key => $value)
            {
                if($date2 == 0)
                {
                    $date2 = $value->wallet_log_date_created;
                } 
                if($value->wallet_log_date_created <= $date1)
                {
                    $date1 = $value->wallet_log_date_created;
                }
                if($value->wallet_log_date_created >= $date2)
                {
                    $date2 = $value->wallet_log_date_created;
                }
                $sum += $value->wallet_log_amount;
            }
            // return $from;

            $encashment_settings = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();
            if($sum >= $encashment_settings->enchasment_settings_minimum)
            {
                $customer_id = Self::$customer_id;
                $shop_id = Self::$shop_id;
                $encashment_settings = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();
                $count = DB::table('tbl_customer_payout')->where('customer_id', $customer_id)->count();
                $customer_payout  = DB::table('tbl_customer_payout')->where('customer_id', $customer_id)->first();
                $bank = DB::table('tbl_encashment_bank_deposit')->where('encashment_bank_deposit_id', $customer_payout->encashment_bank_deposit_id)->where('encashment_bank_deposit_archive', 0)->first();
                
                if($encashment_settings->enchasment_settings_type == 0)
                {
                    if(!isset($bank->encashment_bank_deposit_id))
                    {
                        $data['status'] = 'warning';
                        $data['message'] = 'Please Choose a bank in the encashment details tab.';
                        $data['bank'] = $bank;
                        return json_encode($data);
                    }
                }

                $currency_set = Request::input('currency_set');
                $insert['encashment_process_currency'] = 'PHP';
                $insert['encashment_process_currency_convertion'] = 1; 
                if($currency_set)
                {
                    $currency = Tbl_mlm_encashment_currency::where('en_cu_id', $currency_set)
                    ->first();
                    if($currency)
                    {
                        $insert['encashment_process_currency'] = $currency->iso;
                        $insert['encashment_process_currency_convertion'] = $currency->en_cu_convertion;
                    }
                }

                $insert['shop_id'] = $shop_id;
                $insert['enchasment_process_from'] = $date1;
                $insert['enchasment_process_to'] = $date2;
                $insert['enchasment_process_executed'] = Carbon::now();
                $insert['enchasment_process_tax'] = $encashment_settings->enchasment_settings_tax;
                $insert['enchasment_process_tax_type'] = $encashment_settings->enchasment_settings_tax_type;
                $insert['enchasment_process_p_fee'] = $encashment_settings->enchasment_settings_p_fee;
                $insert['enchasment_process_p_fee_type'] = $encashment_settings->enchasment_settings_p_fee_type;
                $insert['enchasment_process_minimum'] =  $encashment_settings->enchasment_settings_minimum;
                $insert['encashment_process_sum'] = $sum;
                $id = Tbl_mlm_encashment_process::insertGetId($insert);
                Mlm_slot_log::encash_single($id, $affected);

                $data['status'] = 'Success';
                $data['message'] = 'Encashment Requested.';
                $data['affected'] = $insert;

                
                
                
                // return $bank;
                if($encashment_settings->enchasment_settings_type == 0)
                {
                    $insert_d['encashment_process'] = $id;
                    $insert_d['encashment_bank_deposit_id'] = $customer_payout->encashment_bank_deposit_id; 
                    $insert_d['encashment_type'] = $encashment_settings->enchasment_settings_type;
                    $insert_d['bank_name'] = $bank->encashment_bank_deposit_name;
                    $insert_d['bank_account_branch'] = $customer_payout->customer_payout_bank_branch;
                    $insert_d['bank_account_name'] = $customer_payout->customer_payout_bank_account_name;
                    $insert_d['bank_account_number'] = $customer_payout->customer_payout_bank_account_number;
                    $insert_d['cheque_name'] = $customer_payout->customer_payout_name_on_cheque;
                }
                else if ($encashment_settings->enchasment_settings_type == 1)
                {
                    $insert_d['encashment_process'] = $id;
                    $insert_d['encashment_bank_deposit_id'] = $customer_payout->encashment_bank_deposit_id; 
                    $insert_d['encashment_type'] = $encashment_settings->enchasment_settings_type;
                    $insert_d['bank_name'] = '';
                    $insert_d['bank_account_branch'] = $customer_payout->customer_payout_bank_branch;
                    $insert_d['bank_account_name'] = $customer_payout->customer_payout_bank_account_name;
                    $insert_d['bank_account_number'] = $customer_payout->customer_payout_bank_account_number;
                    $insert_d['cheque_name'] = $customer_payout->customer_payout_name_on_cheque;
                }
                
                DB::table('tbl_mlm_encashment_process_details')->insert($insert_d);
            }
            else
            {
                $data['status'] = 'warning';
                $data['message'] = 'Your request is bellow minimum';
            }
            
        }
        else
        {
            $data['status'] = 'warning';
            $data['message'] = 'Atleast 1 Income Must Be Selected';
        }
        return json_encode($data);
    }
    public function breakdown_slot($encashment_process, $slot_id)
    {
        $shop_id = Self::$shop_id;
        $pdf= Request::input('pdf');
        $data['slot'] = Tbl_mlm_slot::where('slot_id', $slot_id)->customer()->first();
        $data['encashment_process'] = Tbl_mlm_encashment_process::where('shop_id', $shop_id)
        ->where('encashment_process', $encashment_process)
        ->orderBy('encashment_process', 'DESC')->first();

        $data['log'] = $data['slots'] = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $slot_id)
        ->where('wallet_log_plan', '!=', 'ENCASHMENT')
        ->orderBy('wallet_log_id', 'ASC')
        ->where('encashment_process', $encashment_process)
        ->slot()->customer()->get();

        $data['log_final'] = $data['slots'] = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $slot_id)
        ->where('wallet_log_plan','ENCASHMENT')
        ->orderBy('wallet_log_id', 'ASC')
        ->where('encashment_process', $encashment_process)
        ->slot()->customer()->first();

        $data['encashment_details'] = DB::table('tbl_mlm_encashment_process_details')->where('encashment_process', $encashment_process)->first();
        if(isset($data['slot']->customer_id))
        {
            $data['customer_view'] = Mlm_member::get_customer_info_w_slot($data['slot']->customer_id, $slot_id);
        }
        if($pdf == 'true'){
            $html_a = view('mlm.wallet.breakdown.breakdown', $data);
            // return $html_a;
            return Pdf_global::show_pdf($html_a);
        }
        else
        {
            return view('mlm.wallet.breakdown.breakdown', $data);
        }
    }
}