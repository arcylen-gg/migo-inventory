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
use PDF;
use App;
use DB;
use App\Models\Currency;

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
use App\Models\Tbl_mlm_encashment_settings;
use App\Models\Tbl_mlm_encashment_process;
use App\Models\Tbl_mlm_encashment_currency;

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
use App\Globals\Mlm_encash;
use App\Globals\Mlm_member;
use App\Globals\Pdf_global;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\Mlm_report;

class Mlm_EncashmentController extends Member
{
    public function index()
    {
        $access = Utilities::checkAccess('mlm-wallet-encashment', 'access_page');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }

        $data = [];

        $shop_id = $this->user_info->shop_id;

        $count = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->count();

        $this->initialize_settings($shop_id);

        $data['encashment_settings'] = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();

        $data['payout_gateway'] = payout_getway();

        $data['not_encashed'] = $all_log = Tbl_mlm_slot_wallet_log::where('wallet_log_status', 'released')
            ->where('wallet_log_plan','!=', 'ENCASHMENT')
            ->where('shop_id', $shop_id)
            ->whereNull('encashment_process')
            ->sum('wallet_log_amount');
        $data['not_encashed_requested'] = $all_log = Tbl_mlm_slot_wallet_log::where('wallet_log_status', 'released')
            ->where('wallet_log_plan','ENCASHMENT')
            ->where('shop_id', $shop_id)
            ->where('encashment_process_type', 0)
            ->sum('wallet_log_amount');    

         $data['not_encashed_encashed'] = $all_log = Tbl_mlm_slot_wallet_log::where('wallet_log_status', 'released')
            ->where('wallet_log_plan','ENCASHMENT')
            ->where('shop_id', $shop_id)
            ->where('encashment_process_type', 1)
            ->sum('wallet_log_amount'); 

        if($data['encashment_settings']->enchasment_settings_auto == 0)
        {
            $data['encashment_process'] = Tbl_mlm_encashment_process::where('shop_id', $shop_id)
            ->orderBy('encashment_process', 'DESC')->get();
        }
        else
        {
            $history = Tbl_mlm_slot_wallet_log::where('wallet_log_plan', 'ENCASHMENT')
            ->slot()->customer()
            ->where('tbl_mlm_slot.shop_id', $shop_id);

            $request = Request::input('request');

            $slot = Request::input('slots');

            $customer = Request::input('customer');

            if($request != null)
            {
                $history = $history->where('encashment_process_type', $request);
            }
            else
            {
                $history = $history->where('encashment_process_type', 0);
            }
            if($slot != null)
            {
                $history = $history->where('slot_no', 'like', '%' . $slot . '%');
            }
            if($customer != null)
            {
                $history = $history->leftjoin('tbl_customer_search', 'tbl_customer_search.customer_id', '=', 'tbl_customer.customer_id')
                ->where('tbl_customer_search.body', 'like', '%' . $customer . '%');
            }

            $data['history'] = $history->join('tbl_mlm_encashment_process', 'tbl_mlm_encashment_process.encashment_process', '=', 'tbl_mlm_slot_wallet_log.encashment_process')
            ->paginate(10);

            $data['request'] = $request;
        }
        
        $data['from'] = $this->get_last_wallet($shop_id);

        $data["vmoney_enable"] = isset(DB::table("tbl_shop")->where("shop_id", $this->user_info->shop_id)->first()->shop_wallet_vmoney) ? DB::table("tbl_shop")->where("shop_id", $this->user_info->shop_id)->first()->shop_wallet_vmoney : 0;
        $data["vmoney_environment"] = isset(DB::table("tbl_settings")->where("settings_key", "vmoney_environment")->where("shop_id", $this->user_info->shop_id)->first()->settings_value) ? DB::table("tbl_settings")->where("settings_key", "vmoney_environment")->where("shop_id", $this->user_info->shop_id)->first()->settings_value : 0;
        $data["vmoney_minimum_encashment"] = isset(DB::table("tbl_settings")->where("settings_key", "vmoney_minimum_encashment")->where("shop_id", $this->user_info->shop_id)->first()->settings_value) ? DB::table("tbl_settings")->where("settings_key", "vmoney_minimum_encashment")->where("shop_id", $this->user_info->shop_id)->first()->settings_value : 0;
        $data["vmoney_percent_fee"] = isset(DB::table("tbl_settings")->where("settings_key", "vmoney_percent_fee")->where("shop_id", $this->user_info->shop_id)->first()->settings_value) ? DB::table("tbl_settings")->where("settings_key", "vmoney_percent_fee")->where("shop_id", $this->user_info->shop_id)->first()->settings_value : 0;
        $data["vmoney_fixed_fee"] = isset(DB::table("tbl_settings")->where("settings_key", "vmoney_fixed_fee")->where("shop_id", $this->user_info->shop_id)->first()->settings_value) ? DB::table("tbl_settings")->where("settings_key", "vmoney_fixed_fee")->where("shop_id", $this->user_info->shop_id)->first()->settings_value : 0;
        $data["shop_id"] = $this->user_info->shop_id;
        
        return view('member.mlm_encashment.index', $data);
    }
    
    public function get_last_wallet($shop_id)
    {
        $date_last = Tbl_mlm_slot_wallet_log::where('shop_id', $shop_id)->whereNull('encashment_process')->first();
        if($date_last == null)
        {
            return Carbon::now();
        }
        else
        {
            return $date_last->wallet_log_date_created;
        }
    }
    public static function initialize_settings($shop_id)
    {
        $count = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->count();
        if($count == 0)
        {
            $insert['shop_id'] = $shop_id;
            $insert['enchasment_settings_auto'] = 0;
            $insert['enchasment_settings_tax'] = 0;
            $insert['enchasment_settings_tax_type'] = 0;
            $insert['enchasment_settings_p_fee'] = 0;
            $insert['enchasment_settings_p_fee_type'] = 0;
            Tbl_mlm_encashment_settings::insert($insert);
        }
    }
    public function update_settings()
    {
        $enchasment_settings_id = Request::input('enchasment_settings_id');
        $old_data = AuditTrail::get_table_data("tbl_mlm_encashment_settings","enchasment_settings_id",$enchasment_settings_id);
        $update['enchasment_settings_auto'] = Request::input('enchasment_settings_auto');
        $update['enchasment_settings_tax'] = Request::input('enchasment_settings_tax');
        $update['enchasment_settings_tax_type'] = Request::input('enchasment_settings_tax_type');
        $update['enchasment_settings_p_fee'] = Request::input('enchasment_settings_p_fee');
        $update['enchasment_settings_p_fee_type'] = Request::input('enchasment_settings_p_fee_type');
        $update['enchasment_settings_minimum'] = Request::input('enchasment_settings_minimum');
        $update['enchasment_settings_type'] = Request::input('enchasment_settings_type');

        Tbl_mlm_encashment_settings::where('enchasment_settings_id', $enchasment_settings_id)->update($update);
        $data['status'] = 'success';
        $data['message'] = 'Encashment Settings Changed';

        //audit

        $new_data = AuditTrail::get_table_data("tbl_mlm_encashment_settings","enchasment_settings_id",$enchasment_settings_id);
        AuditTrail::record_logs("Edited","mlm_encashment_settings",$enchasment_settings_id,serialize($old_data),serialize($new_data));

        /* V Money Settings */
        $update_setting["vmoney_environment"] = Request::input("vmoney_environment");
        $update_setting["vmoney_minimum_encashment"] = Request::input("vmoney_minimum_encashment"); 
        $update_setting["vmoney_percent_fee"] = Request::input("vmoney_percent_fee");
        $update_setting["vmoney_fixed_fee"] = Request::input("vmoney_fixed_fee");
        $vmoney_environment = Request::input("vmoney_enable");
        $this->vmoney_update($update_setting, $vmoney_environment);
        
        return json_encode($data);
    }
    public function vmoney_update($update_setting, $vmoney_environment)
    {
        foreach ($update_setting as $key => $value) 
        {
            $exist = DB::table("tbl_settings")->where("settings_key", $key)->where("shop_id", $this->user_info->shop_id)->first();
            if ($exist) 
            {
                $set_update["settings_value"] = $value;
                DB::table("tbl_settings")->where("settings_key", $key)->where("shop_id", $this->user_info->shop_id)->update($set_update);
            }
            else
            {
                $set_insert["settings_key"] = $key;
                $set_insert["settings_value"] = $value;
                $set_insert["settings_setup_done"] = 1;
                $set_insert["shop_id"] = $this->user_info->shop_id;
                DB::table("tbl_settings")->insert($set_insert);
            }
        }

        $update_shop["shop_wallet_vmoney"] = $vmoney_environment;
        DB::table("tbl_shop")->where("shop_id", $this->user_info->shop_id)->update($update_shop);
    }
    public function process_all_encashment()
    {
        ini_set('max_execution_time', 60000);
        set_time_limit(10000);
        $shop_id = $this->user_info->shop_id;
        $enchasment_process_from = Request::input('enchasment_process_from');
        $enchasment_process_to = Request::input('enchasment_process_to');
        if($enchasment_process_from <= $enchasment_process_to)
        {
            $encashment_settings = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();

            $insert['shop_id'] = $shop_id;
            $insert['enchasment_process_from'] = Request::input('enchasment_process_from');
            $insert['enchasment_process_to'] = Request::input('enchasment_process_to');
            $insert['enchasment_process_executed'] = Carbon::now();
            $insert['enchasment_process_tax'] = $encashment_settings->enchasment_settings_tax;
            $insert['enchasment_process_tax_type'] = $encashment_settings->enchasment_settings_tax_type;
            $insert['enchasment_process_p_fee'] = $encashment_settings->enchasment_settings_p_fee;
            $insert['enchasment_process_p_fee_type'] = $encashment_settings->enchasment_settings_p_fee_type;
            $insert['enchasment_process_minimum'] =  $encashment_settings->enchasment_settings_minimum;
            $insert['encashment_process_sum'] = 0;
            $id = Tbl_mlm_encashment_process::insertGetId($insert);
            return Mlm_slot_log::encash_all($id);
            $data['status'] = 'success';
            $data['message'] = 'Encashment Process';
        }
        else
        {
            $data['status'] = 'warning';
            $data['message'] = 'Encashment date mismatch';
        }
        return json_encode($data);
    }
    public function view_process($encashment_process)
    {
        $shop_id = $this->user_info->shop_id;
        $data['encashment_process'] = Tbl_mlm_encashment_process::where('shop_id', $shop_id)
        ->where('encashment_process', $encashment_process)
        ->orderBy('encashment_process', 'DESC')->first();
        $data['slots'] = Tbl_mlm_slot_wallet_log::where('wallet_log_plan', 'ENCASHMENT')
        ->where('encashment_process', $encashment_process)
        ->slot()->customer()->get();
        return view('member.mlm_encashment.view', $data);
    }
    public function breakdown_slot($encashment_process, $slot_id, $pdf = null)
    {
        $shop_id = $this->user_info->shop_id;

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
        if($pdf == true){
            return view('member.mlm_encashment.pdf', $data);
        }
        else
        {
            return view('member.mlm_encashment.breakdown', $data);
        }
    }
    public function process_breakdown()
    {
        $update['encashment_process_type'] = 1;
        $update['wallet_log_remarks'] = Request::input('wallet_log_remarks');
        $wallet_log_id   = Request::input('wallet_log_id');
        $encashment_process = Request::input('encashment_process');
        $slot_id = Request::input('slot_id');
        Tbl_mlm_slot_wallet_log::where('wallet_log_id', $wallet_log_id)->update($update);
        $data['status'] = 'success_process';
        $data['message'] = 'Encashment Processed';
        $data['wallet_log_id'] =    $wallet_log_id;
        $data['encashment_process'] = $encashment_process;
        $data['slot_id'] = $slot_id;
        return json_encode($data);
    }
    public function show_pdf($encashment_process, $slot_id)
    {
        $html_a = $this->breakdown_slot($encashment_process, $slot_id, 'true');
        return Pdf_global::show_pdf($html_a);
    }
    public function show_type($type)
    {
        $shop_id = $this->user_info->shop_id;
        $data['encashment_settings'] = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();
        if($type == 0)
        {
            $data['bank_option'] = DB::table('tbl_encashment_bank_deposit')->where('shop_id', $shop_id)->where('encashment_bank_deposit_archive', 0)->get();
            return view('member.mlm_encashment.type.bank', $data);
        }
        else if($type == 1)
        {
            return view('member.mlm_encashment.type.cheque', $data);
        }
    }
    public function cheque_edit()
    {
        $shop_id = $this->user_info->shop_id;
        $update['enchasment_settings_cheque_edit'] = Request::input('enchasment_settings_cheque_edit');
        Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->update($update);

        $data['status'] = 'success_new';
        $data['message'] = 'Name on cheque option editted';
        return json_encode($data);
    }
    public function bank_add()
    {
            // return $_POST;
            $shop_id = $this->user_info->shop_id;
            if($_POST['encashment_bank_deposit_name'] != null)
            {
                $insert['encashment_bank_deposit_name'] = Request::input('encashment_bank_deposit_name');
                $insert['shop_id'] = $shop_id;
                $insert['encashment_bank_deposit_archive'] = 0;
                DB::table('tbl_encashment_bank_deposit')->insert($insert);
                $data['status'] = 'success_new';
                $data['message'] = 'Bank option added.';
            }
            else
            {
                $data['status'] = 'warning';
                $data['message'] = 'Bank deposit name is required';
            }
            return json_encode($data);
    }
    public function bank_archive()
    {
        $shop_id = $this->user_info->shop_id;
        $encashment_bank_deposit_id = Request::input('encashment_bank_deposit_id');
        $update['encashment_bank_deposit_archive']  = 1;
        DB::table('tbl_encashment_bank_deposit')->where('encashment_bank_deposit_id', $encashment_bank_deposit_id)->update($update);
        $data['status'] = 'success_new';
        $data['message'] = 'Bank option archived.';
        return json_encode($data);

    }
    public function bank_edit_name()
    {
        $shop_id = $this->user_info->shop_id;
        $update['enchasment_settings_bank_edit'] = Request::input('enchasment_settings_bank_edit');
        Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->update($update);

        $data['status'] = 'success_new';
        $data['message'] = 'Bank account name option editted';
        return json_encode($data);
    }
    public function add_to_list()
    {
        // return $_POST;
        $wallet_log_id = Request::input('wallet_log_id');
        $add_to_list = Request::input('add_to_list');
        $data['status'] = 'success_new';
        $data['message'] = 'Selected';
        if($wallet_log_id != null)
        {
            if($add_to_list == 'on')
            {
                $update['wallet_log_selected'] = 1;
                Tbl_mlm_slot_wallet_log::where('wallet_log_id', $wallet_log_id)->update($update);
            }
            else
            {
                $update['wallet_log_selected'] = 0;
                Tbl_mlm_slot_wallet_log::where('wallet_log_id', $wallet_log_id)->update($update);
                $data['status'] = 'success_new';
                $data['message'] = 'Removed from Selected';
            }
            
        }
        
        return json_encode($data);
    }
    public function add_to_list_date()
    {
        $shop_id = $this->user_info->shop_id;

        // ---------------------------------
         $update['wallet_log_selected'] = 0;
         Tbl_mlm_slot_wallet_log::where('shop_id', $shop_id)->update($update);

        $from = Request::input('from');
        $to = Request::input('to');


        $update_2['wallet_log_selected'] = 1;
        $all_affected = Tbl_mlm_slot_wallet_log::where('shop_id', $shop_id)
        ->where('wallet_log_date_created', '<=', $to)
        ->where('wallet_log_date_created', '>=', $from)
        ->where('wallet_log_plan', 'ENCASHMENT')
        ->update($update_2);
        $data['status'] = 'success_new';
        $data['message'] = 'Removed from Selected';
        return json_encode($data);
    }
    public function view_all_selected()
    {
        $shop_id = $this->user_info->shop_id;
        $filter['from'] = Carbon::now()->addYears(-5);
        $filter['to'] = Carbon::now();
        $filter['skip'] = 0;
        $filter['take'] = 99999;
        // dd($filter);
        $data['a'] = Mlm_report::encashment_rep_req($shop_id, $filter, 1);
        return view('member.mlm_encashment.requested_selected', $data);
    }
    public function request_all_selected()
    {
        // return $_POST;
        $shop_id = $this->user_info->shop_id;
        $encashment_settings = Tbl_mlm_encashment_settings::where('shop_id', $shop_id)->first();

        $update_wallet['encashment_process_type'] = 1;
        $update_wallet['wallet_log_selected'] =0;
        $update_wallet['wallet_log_remarks'] = Request::input('remarks');
        Tbl_mlm_slot_wallet_log::where('wallet_log_selected', 1)->where('shop_id', $shop_id)->update($update_wallet);
        
        $data['status'] = 'success_new';
        $data['message'] = 'Encashment Processed';
        return json_encode($data);


    }
    public function deny_all_selected()
    {
        $shop_id = $this->user_info->shop_id;

        $all_selected = Tbl_mlm_slot_wallet_log::where('wallet_log_selected', 1)->where('shop_id', $shop_id)->get();

        foreach ($all_selected as $key => $value) {
            # code...
            $update['wallet_log_selected'] = 0;
            $update['wallet_log_denied_amount'] = $value->wallet_log_amount;
            $update['wallet_log_amount'] = 0;
            $update['encashment_process_type'] = 2;
            $update['wallet_log_notified'] = 0;
            $update['wallet_log_details'] = 'Your wallet request has been denied';
            Tbl_mlm_slot_wallet_log::where('wallet_log_id', $value->wallet_log_id)->update($update);

            $update_status['encashment_process'] = null;
            Tbl_mlm_slot_wallet_log::where('encashment_process', $value->encashment_process)
            ->where('wallet_log_plan', '!=', 'ENCASHMENT')
            ->update($update_status);
        }

        $data['status'] = 'success_new';
        $data['message'] = 'Encashment Denied';
        return json_encode($data);
    }
    public function set_currency()
    {
        $data = [];
        $data['country'] = DB::table('currency')->get();
        $shop_id = $this->user_info->shop_id;
        $data['currency_set'] = Tbl_mlm_encashment_currency::where('en_cu_shop_id', $shop_id)->get()->keyBy('iso');
        return view('member.mlm_encashment.currency', $data);
    }
    public function set_currency_update()
    {
        // return $_POST;
        $shop_id = $this->user_info->shop_id;
        $iso = Request::input('iso');
        $en_cu_active = Request::input('en_cu_active');
        $en_cu_convertion = Request::input('en_cu_convertion');

        $active_iso = [];
        $inactive_iso = [];
        foreach($iso as $key => $value)
        {
            if(!isset($en_cu_active[$key])){
                $en_cu_active[$key] = 0;
                $inactive_iso[$key] = $key;
            }
            else
            {
                $en_cu_active[$key] = 1;
                $active_iso[$key] = $key;
            }
        }

        $currency = Tbl_mlm_encashment_currency::where('en_cu_shop_id', $shop_id)
        ->get()->keyBy('iso');

        $currency_plain = Currency::get()->keyBy('iso');
        foreach($active_iso as $key => $value)
        {
            if(!isset($currency[$key]))
            {
                $insert[$key]['en_cu_convertion'] = $en_cu_convertion[$key];
                $insert[$key]['en_cu_active'] = 1;
                $insert[$key]['iso'] = $key;
                $insert[$key]['en_cu_shop_id'] = $shop_id;
                $insert[$key]['en_cu_name'] = $currency_plain[$key]->name;
            }
        }
        if(isset($insert))
        {
            Tbl_mlm_encashment_currency::insert($insert);
        }

        $update['en_cu_active'] = 1;
        Tbl_mlm_encashment_currency::where('en_cu_shop_id', $shop_id)
        ->whereIn('iso', $active_iso)
        ->update($update);

        $update['en_cu_active'] = 0;
        Tbl_mlm_encashment_currency::where('en_cu_shop_id', $shop_id)
        ->whereIn('iso', $inactive_iso)
        ->update($update);

        $status['status'] = 'success';
        $status['message'] = 'Currency Set';

        return json_encode($status);

    }
}