<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Response;
use View;
use Crypt;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_user;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_binary_setttings;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_mlm_slot_wallet_log_refill;
use App\Models\Tbl_mlm_slot_wallet_log_transfer;
use App\Models\Tbl_mlm_slot_wallet_log_refill_settings;

use App\Globals\AuditTrail;
use App\Globals\Item;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_tree;
use App\Globals\Membership_code;
use App\Globals\Settings;
use App\Globals\Mlm_voucher;
use App\Globals\Mlm_slot_log;
use App\Globals\Item_code;
use App\Globals\Mlm_complan_manager_repurchase;
use App\Globals\Mlm_member;
use App\Globals\Mlm_pre;
use App\Globals\Pdf_global;
use App\Globals\Utilities;

use Input;
use File;
class MLM_WalletController extends Member
{
    public function index()
    {
        $access = Utilities::checkAccess('mlm-wallet-refill', 'access_page');
        if($access == 0)
        {
        return $this->show_no_access(); 
        }

        $data = [];
        $wallet_log = Tbl_mlm_slot_wallet_log::where('tbl_mlm_slot_wallet_log.shop_id', $this->user_info->shop_id)
        ->orderBy('wallet_log_date_created', 'DESC')
        ->groupBy('wallet_log_slot')
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=','tbl_mlm_slot_wallet_log.wallet_log_slot')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=','tbl_mlm_slot.slot_owner')
        ->selectRaw('sum(wallet_log_amount) as sum_wallet, tbl_mlm_slot_wallet_log.*, tbl_mlm_slot.*, tbl_customer.*');

        if(Request::input('search'))
        {
            $wallet_log = $wallet_log->where('tbl_mlm_slot.slot_no', 'like', '%'.Request::input('search').'%'); 
        }
        $data['wallet_log'] = $wallet_log->paginate(20);
        
        // dd($data);
        return view('member.mlm_wallet.index', $data);
    }
    public function refill()
    {
        $access = Utilities::checkAccess('mlm-wallet-refill', 'wallet_refill');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }
        $data = [];
        $shop_id = $this->user_info->shop_id;
        Mlm_pre::pre_req($shop_id);
        $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->first();
        $request = Tbl_mlm_slot_wallet_log_refill::where('tbl_mlm_slot_wallet_log_refill.shop_id', $shop_id)
        ->orderBy('wallet_log_refill_id', 'DESC')
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id','=', 'tbl_mlm_slot_wallet_log_refill.slot_id');
        $search = Request::input('search');
        if($search != null)
        {
            $request = $request->where('tbl_mlm_slot.slot_no', 'like', '%' . $search . '%');
        }
        $filter = Request::input('filter');
        if($filter != null)
        {
            $request = $request->where('tbl_mlm_slot_wallet_log_refill.wallet_log_refill_approved', $filter);
        }
        $data['request'] = $request->paginate(10);;;
        return view('member.mlm_wallet.refill', $data);
    }

    public function refill_settings()
    {
        $shop_id = $this->user_info->shop_id;

        $old_data = AuditTrail::get_table_data("tbl_mlm_slot_wallet_log_refill_settings",'shop_id', $shop_id);

        $update['wallet_log_refill_settings_processings_fee'] = Request::input('wallet_log_refill_settings_processings_fee');
        $update['wallet_log_refill_settings_processings_max_request'] = Request::input('wallet_log_refill_settings_processings_max_request');
        Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->update($update);
        $data['success'] = 'success';

        //audit trail here
        $new_data = AuditTrail::get_table_data("tbl_mlm_slot_wallet_log_refill_settings",'shop_id', $shop_id);
        AuditTrail::record_logs("Edited Process","mlm_slot_wallet_refill_settings",$old_data["wallet_log_refill_settings_id"],serialize($old_data),serialize($new_data));

        return json_encode($data);
    }
    public function refill_id($wallet_log_refill_id)
    {
        $shop_id = $this->user_info->shop_id;
        $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->first();

        $data['request'] = Tbl_mlm_slot_wallet_log_refill::where('shop_id', $shop_id)->where('wallet_log_refill_id', $wallet_log_refill_id)->first();
        $slot_id = $data['request']->slot_id;
        $data['slot_info'] = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
        $data['customer_view'] = Mlm_member::get_customer_info_w_slot($data['slot_info']->slot_owner, $slot_id);
        return view('member.mlm_wallet.refill_view', $data);
    }
    public function refill_process()
    {
        $var = [];
        foreach($_POST as $key => $value)
        {
            $var[$key] = $value;
        }
        if($var['submit'] =='Process')
        {
            $update['wallet_log_refill_date_approved'] = Carbon::now();
            $update['wallet_log_refill_amount'] = $var['wallet_log_refill_amount'];
            $update['wallet_log_refill_amount_paid'] = $var['wallet_log_refill_amount_paid'];
            $update['wallet_log_refill_processing_fee'] = $var['wallet_log_refill_processing_fee'];
            $update['wallet_log_refill_approved'] = 1;
            $update['wallet_log_refill_remarks'] = $var['wallet_log_refill_remarks'];
            $update['wallet_log_refill_remarks_admin'] = $var['wallet_log_refill_remarks_admin'];
            $warehouse_id = $this->current_warehouse->warehouse_id;
            $update['wallet_log_refill_attachment_warehouse'] = $warehouse_id;

            Tbl_mlm_slot_wallet_log_refill::where('wallet_log_refill_id', $var['wallet_log_refill_id'])->update($update);

            $slot_info = Tbl_mlm_slot::where('slot_id', $var['slot_id'])->first();
            $insert['shop_id'] = $slot_info->shop_id;
            $insert['wallet_log_slot'] = $slot_info->slot_id; 
            $insert['wallet_log_slot_sponsor'] = $slot_info->slot_id; 
            $insert['wallet_log_date_created'] = Carbon::now(); 
            $insert['wallet_log_details'] = 'Your wallet request has been approved, your wallet now has an additional ' . $var['wallet_log_refill_amount']; 
            $insert['wallet_log_amount'] = $var['wallet_log_refill_amount'];
            $insert['wallet_log_plan'] = 'WALLET_REFILL'; 
            $insert['wallet_log_status'] = 'released'; 
            $insert['wallet_log_claimbale_on'] = Carbon::now(); 
            Mlm_slot_log::slot_array($insert);

            return Redirect::back();
        }
        else if($var['submit'] =='Deny')
        {
            $update['wallet_log_refill_date_approved'] = Carbon::now();
            $update['wallet_log_refill_amount'] = $var['wallet_log_refill_amount'];
            $update['wallet_log_refill_amount_paid'] = $var['wallet_log_refill_amount_paid'];
            $update['wallet_log_refill_processing_fee'] = $var['wallet_log_refill_processing_fee'];
            $update['wallet_log_refill_approved'] = 2;
            $update['wallet_log_refill_remarks'] = $var['wallet_log_refill_remarks'];
            $update['wallet_log_refill_remarks_admin'] = $var['wallet_log_refill_remarks_admin'];
            Tbl_mlm_slot_wallet_log_refill::where('wallet_log_refill_id', $var['wallet_log_refill_id'])->update($update);
            return Redirect::back();
        }
        else if($var['submit'] == 'PDF')
        {
            $wallet_log_refill_id = $var['wallet_log_refill_id'];
            $shop_id = $this->user_info->shop_id;
            $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->first();

            $data['request'] = Tbl_mlm_slot_wallet_log_refill::where('shop_id', $shop_id)->where('wallet_log_refill_id', $wallet_log_refill_id)->first();
            $slot_id = $data['request']->slot_id;
            $data['slot_info'] = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
            $data['customer_view'] = Mlm_member::get_customer_info_w_slot($data['slot_info']->slot_owner, $slot_id);
            $view = view('member.mlm_wallet.refill_pdf', $data);
            return Pdf_global::show_pdf($view);
        }
    }
    public function refill_change()
    {
        $shop_id = $this->user_info->shop_id;;
        $shop_key = $this->user_info->shop_key;;

        $file               = Input::file('upload_picture');
        $fileArray = array('image' => $file);
        $rules = array(
          'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
        );
        $validator = Validator::make($fileArray, $rules);
        if ($validator->fails())
        {
            $data['status'] = 'warning';
            $data['message'] = 'file size exceeded/ or invalid file type';
            return json_encode($data);
        }

        $extension          = $file->getClientOriginalExtension();
        $filename           = str_random(15).".".$extension;
        $destinationPath    = 'uploads/refill/attachment/'.$shop_key."-".$shop_id;
        if(!File::exists($destinationPath)) 
        {
            $create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
        }

        $upload_success    = Input::file('upload_picture')->move($destinationPath, $filename);

        /* SAVE THE IMAGE PATH IN THE DATABASE */
        $image_path = $destinationPath."/".$filename;

        $data['status'] = 'success';
        $data['image_path'] = $image_path;

        $update['wallet_log_refill_attachment'] = $image_path;


        Tbl_mlm_slot_wallet_log_refill::where('shop_id', $shop_id)->where('wallet_log_refill_id', Request::input('wallet_log_refill_id'))->update($update);
        return json_encode($data);
    }
    public function adjust()
    {
        $access = Utilities::checkAccess('mlm-wallet-refill', 'wallet_adjust');
        if($access == 0)
        {
        return $this->show_no_access(); 
        }

        $shop_id = $this->user_info->shop_id;
        $data = [];
        $data['_slots'] = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)->customer()->get();
        return view('member.mlm_wallet.adjust', $data);
    }

    public function adjust_submit()
    {
        // return $_POST;
        $user = Tbl_user::where('user_id', $this->user_info->user_id)->first();
        $slot_id = Request::input('slot_id');
        $wallet_log_amount = Request::input('wallet_log_amount');
        $wallet_log_details = Request::input('wallet_log_details');
        $password = Request::input('password');

        if(
            $slot_id != null &&
            $wallet_log_amount != null &&
            $wallet_log_details != null &&
            $password != null
        )
        {
            $password2 = Crypt::decrypt($user->user_password);
            if($password == $password2)
            {
                    $arr['shop_id'] = $this->user_info->shop_id;
                    $arr['wallet_log_slot'] = $slot_id;
                    $arr['wallet_log_slot_sponsor'] = null;
                    $arr['wallet_log_details'] = $wallet_log_details;
                    $arr['wallet_log_amount'] = $wallet_log_amount;
                    $arr['wallet_log_plan'] = 'ADMIN_REFILL';
                    $arr['wallet_log_status'] = 'released';
                    $arr['wallet_log_claimbale_on'] = Carbon::now();
                    Mlm_slot_log::slot_array($arr);
                    $data['status'] = 'success';
                    $data['message'] = 'Wallet Reffilled.';
                    $data['slot_id'] = $slot_id;

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
            $data['message'] = 'All fields are required.';
        }
        return json_encode($data);    
    }
    public function breakdown_wallet($slot_id)
    {
        $shop_id = $this->user_info->shop_id;
        return Mlm_member::breakdown_wallet($slot_id);
    }
    public  function transfer()
    {   
        $access = Utilities::checkAccess('mlm-wallet-refill', 'wallet_transfer');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }
        $data = [];
        $shop_id = $this->user_info->shop_id;
        $data['settings'] = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->first();
        $data['logs_transfer'] = Tbl_mlm_slot_wallet_log_transfer::where('tbl_mlm_slot_wallet_log_transfer.shop_id', $shop_id)
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id','=', 'tbl_mlm_slot_wallet_log_transfer.wallet_log_transfer_slot_recieve')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->paginate(20);
        $data['logs_recieve'] = Tbl_mlm_slot_wallet_log_transfer::where('tbl_mlm_slot_wallet_log_transfer.shop_id', $shop_id)
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id','=', 'tbl_mlm_slot_wallet_log_transfer.wallet_log_transfer_slot_trans')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->paginate(20);
        return view('member.mlm_wallet.transfer', $data);
    }
    public function transfer_change_settings()
    {
        $shop_id = $this->user_info->shop_id;

        $old_data = AuditTrail::get_table_data("tbl_mlm_slot_wallet_log_refill_settings",'shop_id', $shop_id);

        $update['wallet_log_refill_settings_transfer_processing_fee'] = Request::input('wallet_log_refill_settings_transfer_processing_fee');
        Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->update($update);
        $data['status'] = 'success';

        $new_data = AuditTrail::get_table_data("tbl_mlm_slot_wallet_log_refill_settings",'shop_id', $shop_id);
        AuditTrail::record_logs("Edited transfer","mlm_slot_wallet_refill_settings",$old_data["wallet_log_refill_settings_id"],serialize($old_data),serialize($new_data));

        return json_encode($data);
    }
}