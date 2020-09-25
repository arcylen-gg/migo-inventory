<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Crypt;
use App\Http\Controllers\Controller;
use App\Models\Tbl_ecommercer_remittance;
use App\Models\Tbl_ecommerce_banking;
use App\Models\Tbl_ecommerce_setting;
use App\Models\Tbl_ecommerce_paypal;

class EcommerceSettingController extends Member
{
    
    
    public function setting()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        $this->setup($shop_id);
        
        $data['_setting_code'] = Tbl_ecommerce_setting::sel($shop_id)->get();
        return view('member.ecommerce_setting.ecommerce_setting',$data);
    }
    
    public function setup($shop_id)
    {
        $number = 0;
        $insert = array();
        
        // PAYPAL
        $countPaypal = Tbl_ecommerce_setting::countsel($shop_id,"PAYPAL")->count();
        if($countPaypal == 0)
        {
            $number++;
            $insert[$number]['shop_id'] = $shop_id;
            $insert[$number]['ecommerce_setting_code'] = "PAYPAL";
            $insert[$number]['ecommerce_setting_enable'] = 0;
            $insert[$number]['ecommerce_setting_url'] = '/member/ecommerce/settings/paypalsetting';
            $insert[$number]['ecommerce_setting_date'] = Carbon::now();
            
        }
        
        // BANKING 
        $countBank = Tbl_ecommerce_setting::countsel($shop_id,"BANK")->count();
        
        if($countBank == 0)
        {
            $number++;
            $insert[$number]['shop_id'] = $shop_id;
            $insert[$number]['ecommerce_setting_code'] = "BANK";
            $insert[$number]['ecommerce_setting_enable'] = 0;
            $insert[$number]['ecommerce_setting_url'] = '/member/ecommerce/settings/banksetting';
            $insert[$number]['ecommerce_setting_date'] = Carbon::now();
            
        }
        
        // REMITTANCE START
        
        $countRemittance = Tbl_ecommerce_setting::countsel($shop_id,"REMITTANCE")->count();
        
        if($countRemittance == 0)
        {
            $number++;
            $insert[$number]['shop_id'] = $shop_id;
            $insert[$number]['ecommerce_setting_code'] = "REMITTANCE";
            $insert[$number]['ecommerce_setting_enable'] = 0;
            $insert[$number]['ecommerce_setting_url'] = '/member/ecommerce/settings/remittancesetting';
            $insert[$number]['ecommerce_setting_date'] = Carbon::now();
            
        }
        
        $countCOD = Tbl_ecommerce_setting::countsel($shop_id,"CASH ON DELIVERY")->count();
        
        if($countCOD == 0)
        {
            $number++;
            $insert[$number]['shop_id'] = $shop_id;
            $insert[$number]['ecommerce_setting_code'] = "CASH ON DELIVERY";
            $insert[$number]['ecommerce_setting_enable'] = 0;
            $insert[$number]['ecommerce_setting_url'] = '/member/ecommerce/settings/cashondeliverysetting';
            $insert[$number]['ecommerce_setting_date'] = Carbon::now();
            
        }
        
        if($number > 0)
        {
            Tbl_ecommerce_setting::insert($insert);
        }
        
    }
    
    public function paypalsetting()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        
        $data['paypal'] = Tbl_ecommerce_setting::countsel($shop_id,"PAYPAL")->first();
        $data['credentials'] = Tbl_ecommerce_paypal::where('ecommerce_setting_id',$data['paypal']->ecommerce_setting_id)->first();
        return view('member.ecommerce_setting.paypal.paypal_setting',$data);
    }
    
    public function banksetting()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        $data['bank_active'] = Tbl_ecommerce_banking::sel($shop_id)->orderBy('ecommerce_banking_name','asc')->get();
        $data['bank_inactive'] = Tbl_ecommerce_banking::sel($shop_id,1)->orderBy('ecommerce_banking_name','asc')->get();
        $data['bank'] = Tbl_ecommerce_setting::countsel($shop_id,"BANK")->first();
        return view('member.ecommerce_setting.bank.bank_setting',$data);
    }
    
    public function remittancesetting()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        $data['remittance_active'] = Tbl_ecommercer_remittance::sel($shop_id)->orderBy('ecommerce_remittance_name','asc')->get();
        $data['remittance_inactive'] = Tbl_ecommercer_remittance::sel($shop_id,1)->orderBy('ecommerce_remittance_name','asc')->get();
        $data['remittance'] = Tbl_ecommerce_setting::countsel($shop_id,"REMITTANCE")->first();
        return view('member.ecommerce_setting.remittance.remittance_setting',$data);
    }
    
    public function cashondeliverysetting()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        $data['cod'] = Tbl_ecommerce_setting::countsel($shop_id,"CASH ON DELIVERY")->first();
        return view('member.ecommerce_setting.cashondelivery.cashondelivery_setting',$data);
    }
    
    public function create_banking()
    {
        return view('member.modal.create_ecom_set_banking');
    }
    
    public function create_remittance()
    {
        return view('member.modal.create_ecom_set_remittance');
    }
    
    
    
    public function insert_banking()
    {
        
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        
        $bank_name = Request::input('bank_name');
        $account_name = Request::input('account_name');
        $account_number = Request::input('account_number');
        $insert['shop_id'] = $shop_id;
        $insert['ecommerce_banking_name'] = $bank_name;
        $insert['ecommerce_banking_account_name'] = $account_name;
        $insert['ecommerce_banking_account_number'] = $account_number;
        
        $id = Tbl_ecommerce_banking::insertGetId($insert);
        
        $data['response_status'] = 'success';
        $data['trigger'] = 'bank';
        $data['result'] = $this->result('bank', 0, $id);
        return json_encode($data);
    }
    
    public function loadBankdata($id)
    {
        $data['bank'] = Tbl_ecommerce_banking::where('ecommerce_banking_id',$id)->first();
        return view('member.ecommerce_setting.bank_details',$data);
    }
    
    public function updateBank()
    {
        $bank_id = Request::input('bank_id');
        $bank_name = Request::input('bank_name');
        $account_name = Request::input('account_name');
        $account_number = Request::input('account_number');
        
        $update['ecommerce_banking_name'] = $bank_name;
        $update['ecommerce_banking_account_name'] = $account_name;
        $update['ecommerce_banking_account_number'] = $account_number;
        Tbl_ecommerce_banking::where('ecommerce_banking_id',$bank_id)->update($update);
        $data['bank'] = Tbl_ecommerce_banking::where('ecommerce_banking_id',$bank_id)->first();
        $view = view('member.ecommerce_setting.update_bank',$data);
        $data['trigger'] = 'bank update';
        $data['result'] = $view->render();
        $data['id'] = $bank_id;
        return json_encode($data);
    }
    public function archive_warning_bank($id)
    {
        $id = Crypt::decrypt($id);
        $bank = Tbl_ecommerce_banking::where('ecommerce_banking_id',$id)->first();
        $data['message'] = 'Do you realy want to delete '.$bank->ecommerce_banking_name.'?';
        $data['action'] = '/member/ecommerce/settings/archivedbank';
        $data['id'] = Crypt::encrypt($id);
        $data['btn'] = $this->btn('delete');
        return view('member.modal.confirm',$data);
    }
    public function archivedbank()
    {
        $data_id = Crypt::decrypt(Request::input('data_id'));
        $update['archived'] = 1;
        Tbl_ecommerce_banking::where('ecommerce_banking_id',$data_id)->update($update);
        $data['trigger'] = 'archive';
        $data['result'] = $this->result('bank', 1, $data_id);
        $data['id'] = '#banking-'.$data_id;
        $data['table'] = '.table-bank-inactive';
        
        return json_encode($data);
    }
    
    public function restore_warning_bank($id)
    {
        $id = Crypt::decrypt($id);
        $bank = Tbl_ecommerce_banking::where('ecommerce_banking_id',$id)->first();
        $data['message'] = 'Do you realy want to restore '.$bank->ecommerce_banking_name.'?';
        $data['action'] = '/member/ecommerce/settings/restorebank';
        $data['id'] = Crypt::encrypt($id);
        $data['btn'] = $this->btn('restore');
        return view('member.modal.confirm',$data);
    }
    
    public function restorebank()
    {
        $data_id = Crypt::decrypt(Request::input('data_id'));
        $update['archived'] = 0;
        Tbl_ecommerce_banking::where('ecommerce_banking_id',$data_id)->update($update);
        $data['trigger'] = 'archive';
        $data['result'] = $this->result('bank', 0, $data_id);;;
        $data['id'] = '#inactive-banking-'.$data_id;
        $data['table'] = '.table-bank-active';
        // dd($data);
        return json_encode($data);
    }
    
    public function insertremittance()
    {
        $remittance_name = Request::input('remittance_name');
        $account_name = Request::input('account_name');
        $address = Request::input('address');
        $contact_number = Request::input('contact_number');
        
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        
        $insert['shop_id'] = $shop_id;
        $insert['ecommerce_remittance_name'] = $remittance_name;
        $insert['ecommerce_remittance_full_name'] = $account_name;
        $insert['ecommerce_remittance_address'] = $address;
        $insert['ecommerce_remittance_contact'] = $contact_number;
        
        $id = Tbl_ecommercer_remittance::insertGetId($insert);

        $return['response_status'] = 'success';
        $return['trigger'] = 'remittance';
        $return['result'] = $this->result('remittance', 0, $id);
        
        return json_encode($return);
    }
    
    public function loadRemittancedata($id)
    {
        $data['remittance'] = Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$id)->first();
        return view('member.ecommerce_setting.remittance_details',$data);
    }
    
    public function  updateremittance()
    {
        $remittance_id = Request::input('remittance_id');
        $remittance_name = Request::input('remittance_name');
        $account_name = Request::input('account_name');
        $address = Request::input('address');
        $contact_number = Request::input('contact_number');
        
        $update['ecommerce_remittance_name'] = $remittance_name;
        $update['ecommerce_remittance_full_name'] = $account_name;
        $update['ecommerce_remittance_address'] = $address;
        $update['ecommerce_remittance_contact'] = $contact_number;
        
        Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$remittance_id)->update($update);
    
        $data['remittance'] = Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$remittance_id)->first();
        
        $return['response_status'] = 'success';
        $return['trigger'] = 'remittance update';
        $return['id'] = $remittance_id;
        $return['result'] = view('member.ecommerce_setting.update_remittance',$data)->render();
        
        return json_encode($return);
    }
    
    public function archive_warning_remittance($id){
        $remittance = Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$id)->first();
        $data['message'] = 'Do you realy want to delete '.$remittance->ecommerce_remittance_name.'?';
        $data['action'] = '/member/ecommerce/settings/archiveremittance';
        $data['id'] = Crypt::encrypt($id);
        $data['btn'] = $this->btn('delete');
        return view('member.modal.confirm',$data);
    }
    
    public function archiveremittance()
    {
        $data_id = Crypt::decrypt(Request::input('data_id'));
        $update['archived'] = 1;
        Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$data_id)->update($update);
        $data['trigger'] = 'archive';
        $data['result'] = $this->result('remittance', 1, $data_id);
        $data['id'] = '#remittance-'.$data_id;
        $data['table'] = '.table-remittance-inactive';
        
        return json_encode($data);
    }
    
    public function restore_warning_remittance($id)
    {
        $remittance = Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$id)->first();
        $data['message'] = 'Do you realy want to restore '.$remittance->ecommerce_remittance_name.'?';
        $data['action'] = '/member/ecommerce/settings/restoreremittance';
        $data['id'] = Crypt::encrypt($id);
        $data['btn'] = $this->btn('restore');
        return view('member.modal.confirm',$data);
    }
    public function restoreremittance()
    {
        $data_id = Crypt::decrypt(Request::input('data_id'));
        $update['archived'] = 0;
        Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$data_id)->update($update);
        $data['trigger'] = 'archive';
        $data['result'] = $this->result('remittance', 1, $data_id);
        $data['id'] = '#in-remittance-'.$data_id;
        $data['table'] = '.table-remittance-active';
        
        return json_encode($data);
    }
    
    public function result($trigger = '',$archived = 0, $id = 0)
    {
        // $id = Crypt::decrypt($id);
        $data = array();
        if($trigger == 'bank')
        {
            $banking = Tbl_ecommerce_banking::where('ecommerce_banking_id',$id)->first();
            $data['name'] = $banking->ecommerce_banking_name;
           
            $data['details'] = '/member/ecommerce/settings/loadBankdata/'.$id;
            
            if($archived == 0)
            {
                 $data['tr_id'] = 'banking-'.$id;
                $data['archived'] = '/member/ecommerce/settings/archive_warning_bank/'.Crypt::encrypt($id); 
            }
            else{
                 $data['tr_id'] = 'inactive-banking-'.$id;
                $data['archived'] = '/member/ecommerce/settings/restore_warning_bank/'.Crypt::encrypt($id); 
            }
        }
        if($trigger == 'remittance')
        {
            $remittance = Tbl_ecommercer_remittance::where('ecommerce_remittance_id',$id)->first();
            // dd($id);
            $data['details'] = '/member/ecommerce/settings/loadRemittancedata/'.$remittance->ecommerce_remittance_id;
            $data['name'] = $remittance->ecommerce_remittance_name;
            
            if($archived == 0)
            {   
                $data['tr_id'] = 'remittance-'.$remittance->ecommerce_remittance_id;
                $data['archived'] = '/member/ecommerce/settings/archive_warning_remittance/'.$remittance->ecommerce_remittance_id;
            }
            else
            {
                $data['tr_id'] = 'in-remittance-'.$remittance->ecommerce_remittance_id;
                $data['archived'] = '/member/ecommerce/settings/restore_warning_remittance/'.$remittance->ecommerce_remittance_id;
            }
        }
        return view('member.ecommerce_setting.result_remittance',$data)->render();
    }
    
    public function btn($str = '')
    {
        switch($str)
        {
            case 'delete':
                return '<button type="submit" class="btn btn-custom-red-white btn-del-modallarge">Delete</button>';
                break;
                
            case 'restore':
                return '<button type="submit" class="btn btn-custom-green-white btn-del-modallarge">Restore</button>';
                break;
        }
    }
    
    public function updatepaypal()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        
        $update['ecommerce_setting_enable'] = Request::input('enable');
        $paypal = Tbl_ecommerce_setting::countsel($shop_id,"PAYPAL")->first();
        Tbl_ecommerce_setting::where('ecommerce_setting_id',$paypal->ecommerce_setting_id)->update($update);
        
        
        
        $updatepaypal['ecommerce_setting_id'] = $paypal->ecommerce_setting_id;
        $updatepaypal['paypal_clientid'] = Request::input('client_id');
        $updatepaypal['paypal_secret'] = Request::input('secret');
        $updatepaypal['shop_id'] = $shop_id;
        
        
        $count = Tbl_ecommerce_paypal::where('ecommerce_setting_id',$paypal->ecommerce_setting_id)->count();
        if($count == 0)
        {
            Tbl_ecommerce_paypal::insert($updatepaypal);
        }
        else{
            Tbl_ecommerce_paypal::where('ecommerce_setting_id',$paypal->ecommerce_setting_id)->update($updatepaypal);
        }
        
        $return['response_status'] = 'success';
        $return['trigger'] = 'setting';
        return json_encode($return);
    }
    
    public function settingupdate()
    {
        $user_info = $this->user_info;
        $shop_id = $user_info->shop_id;
        $trigger = Request::input('trigger');
        $update['ecommerce_setting_enable'] = Request::input('enable');
        $paypal = Tbl_ecommerce_setting::countsel($shop_id,$trigger)->first();
        if(isset($paypal->ecommerce_setting_id)){
            Tbl_ecommerce_setting::where('ecommerce_setting_id',$paypal->ecommerce_setting_id)->update($update);
        }
        
        $return['response_status'] = 'success';
        $return['trigger'] = 'setting';
        return json_encode($return);
    }
}
