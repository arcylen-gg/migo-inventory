<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Request;
use Response;
use Image;
use Validator;
use Redirect;
use File;
use Crypt;
use URL;
use Input;
 
use App\Models\Tbl_country;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_attachment;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_user;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_term;
use App\Models\Tbl_item;
use App\Models\Tbl_delivery_method;
use App\Models\Tbl_mlm_slot;
use Session;
use App\Globals\Customer;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\Invoice;
use App\Globals\Item;
use App\Globals\Warehouse2;
use App\Globals\AccountingTransaction;
use App\Globals\Purchasing_inventory_system;

class CustomerController extends Member
{
    public function hasAccess($page_code, $acces)
    {
        $access = Utilities::checkAccess($page_code, $acces);
        if($access == 1) return true;
        else return false;
    }

	public function index()
	{
        if($this->hasAccess("customer-list","access_page"))
        {
            $data['_customer'] = $this->customerlist();
            $data['monthly_budget'] = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
            $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");
            

            if (Request::ajax()) 
            {
                return view('member.customer.customer_tbl', $data)->render();  
            }

            if(Purchasing_inventory_system::check())
            {
                $data["pis"] = true;                
            }
            else
            {
                $data["pis"] = false;
            }
            
    		return view('member.customer.index',$data);
        }
        else
        {
            return $this->show_no_access();
        }
	}
    public function viewlead($id)
    {
        if(request()->isMethod("post"))
        {
            $update["customer_lead"] = 0;
            Tbl_customer::where("customer_id", $id)->where("shop_id", $this->user_info->shop_id)->update($update);

            $return["status"] = "success";
            $return["call_function"] = "clear_lead_success";
            echo json_encode($return);
        }
        else
        {
            $data["page"] = "Lead";
            $data["id"] = $id;
            $customer = Tbl_customer::where("customer_id", $id)->first();
            $data["lead"] = Tbl_mlm_slot::where("slot_id", $customer->customer_lead)->customer()->first();
            return view("member.customer.view_lead", $data);
        }

    }
    public function bulk_archive()
    {
        $data["_customer"]  = Customer::getAllCustomer();

        return view("member.customer.bulk_archive", $data);
    }

    public function bulk_archive_post()
    {
        $customer_id = Request::input('customer_id');

        foreach ($customer_id as $key => $value) 
        {
            $update["archived"] = 1;
            Tbl_customer::where("customer_id", $value)->where("shop_id", $this->user_info->shop_id)->update($update);
        }

        return Redirect::back();
    }
	
	public function customerlist($archived = 0, $IsWalkin = 0)
    {
        $filter_by_slot = Request::input('filter_slot');
        $shop_id = $this->checkuser('user_shop');

        $check_settings = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
	    
        $paginate = Tbl_customer::leftjoin('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')
                                ->balanceJournal()
                                ->address()
                                ->selectRaw('tbl_customer.customer_id as customer_id1, tbl_customer.*, tbl_customer_other_info.*, tbl_customer_other_info.customer_id as cus_id, tbl_customer.archived as customer_archived')
                                ->where('tbl_customer.shop_id',$shop_id)
                                ->where('tbl_customer.archived',$archived)
                                ->where('tbl_customer.IsWalkin',$IsWalkin)
								->orderBy('tbl_customer.created_date', 'desc');

		if($filter_by_slot == 'w_slot')
        {
            $paginate = $paginate->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_owner','=', 'tbl_customer.customer_id');
        }		
        else if($filter_by_slot == 'w_o_slot')
        {
            $paginate = $paginate->leftjoin('tbl_mlm_slot', 'tbl_mlm_slot.slot_owner','=', 'tbl_customer.customer_id')
            ->whereNull('tbl_mlm_slot.slot_id');
        }	
        if($check_settings == 1)
        {
            // $paginate = $paginate->whereNull('customer_warehouse_id')->orWhere('customer_warehouse_id', $warehouse_id);
        }			
		return $paginate->groupBy("tbl_customer.customer_id")->paginate(10);
	}
    public function load_customer()
    {
        $data["_customer"]  = Customer::getAllCustomer();
        $data['add_search']     = "";

        return view('member.load_ajax_data.load_customer', $data);
    }
	
    public function loadcustomer()
    {
        $str = Request::input('str');
        $archived = 0;
        if(Request::has('archive')){
            $archived = intval(Request::input('archive'));
        }
        // dd($archived);
        // $arch = 0;
        // if($archived == 0){
        //     $arch = 1;
        // }
        $shop = $this->user_info;
        $data['monthly_budget'] = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
        $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");
            
        if(Request::has('str') || $str != "" || Request::input("customer_category") || Request::input("customer_category_type"))
        {
            // $data['_customer'] = Tbl_customer_search::search($str, $shop->shop_id, $arch)->paginate(20);
            $data['_customer'] = Customer::search_get($this->user_info->shop_id, $str, 20, $archived, Request::input("customer_category"), Request::input('customer_category_type'));
        }
        else
        {
            $data['_customer'] = $this->customerlist($archived);
        }

        $space = "";

        if (Request::ajax()) 
        {
            return view('member.customer.customer_tbl', $data)->render();  
        }
        return view('member.customer.customer_tbl',$data);
    }

    public function inactivecustomer()
    {
        $id = Request::input('id');
        $archived = Request::input('archived');

        $arch = 0;
        $action = 'Restore';
        if($archived == 0)
        {
            $arch = 1;
            $action = 'Archived';
        }
        $update['archived'] = $arch;

        $customer_data = Tbl_customer::info()->where("tbl_customer.customer_id",$id)->first()->toArray();
        AuditTrail::record_logs($action,"customer",$id,"",serialize($customer_data));

        Tbl_customer::where('customer_id',$id)->update($update);
    }

	public function modalcreatecustomer()
    {
        if($this->hasAccess("customer-list","add"))
        {
            $data["customer_status"] = Request::input("stat");
    	    $shop_id = $this->checkuser('user_shop');
    	    $data['_country'] = Tbl_country::orderBy('country_name','asc')->get();
    	    $data['_def_payment_method'] = Tbl_payment_method::where("isDefault",1)->orderBy('payment_name','asc')->get();
    	    $data['_payment_method'] = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->orderBy('payment_name','asc')->get();
        	$data['_term'] = Tbl_term::where('shop_id',$shop_id)->where('archived',0)->orderBy('term_name','asc')->get();
    	    $data['_customer'] = Tbl_customer::where('shop_id',$shop_id)->where('IsWalkin',0)->where('archived',0)->get();
    	    $data['_delivery_method'] = Tbl_delivery_method::where('archived',0)->get();
            $data['_warehouse'] = Warehouse2::get_all_warehouse($shop_id);

            $value = Request::input('value');

            $data['check_user'] = Purchasing_inventory_system::check();
            
            if($value || $value != '')
            {
                $data["value"] = $value;
            }
            $data['proposal_number'] = AccountingTransaction::settings($this->user_info->shop_id, "customer_proposal_number");
            $data['monthly_budget'] = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
            $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");

    	    return view('member.modal.createcustomer',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
	}	
	
	public function insertcustomer()
    {
        $shop_id = $this->checkuser('user_shop');
        
	    $insert['shop_id'] = $shop_id;
        $insert['first_name'] = Request::input('first_name');
        $insert['last_name'] = Request::input('last_name');
        $insert['email'] = Request::input('email');
        $insert['password'] = Crypt::encrypt('0');
        $accepts_marketing = 0;
        if(Request::has('accepts_marketing')){
            $accepts_marketing = Request::input('accepts_marketing');
        }
        $taxt_exempt = 0;
        if(Request::has('tax_exempt')){
            $taxt_exempt = Request::input('tax_exempt');
        }
        $insert['company'] = Request::input('company');
        $insert['created_date'] = Carbon::now();
        $insert['IsWalkin'] = 0;
        
        $check_email = Tbl_customer::where('shop_id',$shop_id)->where('email',$insert['email'])->count();
        if($check_email == 0)
        {
            Tbl_customer::insert($insert);
            $data = $this->customerlist();
		    return view('member.customer.create_result',$data);
        }
        else
        {
            return 'email already exist';
        }
        
	}
	
	public function editcustomer()
    {
	    $customer_id = Request::input('customer_id');
	    $data['customer'] = Tbl_customer::where('customer_id',$customer_id)->first();
	    $data['_country'] = Tbl_country::orderBy('country_name','asc')->get();
	    return view('member.modal.editcustomer',$data);
	}
	
	public function updatecustomer()
    {

	    $shop_id = $this->checkuser('user_shop');

        $customer_id = Request::input('customer_id');
        $update['first_name'] = Request::input('first_name');
        $update['last_name'] = Request::input('last_name');
        $update['email'] = Request::input('email');
        $update['company'] = Request::input('company');
        $check_email = Tbl_customer::where('shop_id',$shop_id)->where('customer_id','!=',$customer_id)->where('IsWalkin',0)->where('email',$update['email'])->count();
        // dd($check_email);
        if($check_email == 0){
            Tbl_customer::where('customer_id','=',$customer_id)->update($update);
            
            $data['customer'] = Tbl_customer::where('customer_id','=',$customer_id)->first();
            $paginate = Tbl_customer::join('tbl_country','tbl_country.country_id','=','tbl_customer.country_id')
								->where('shop_id',$shop_id)
								->where('tbl_customer.customer_id',$customer_id)
								->get();
	        $data['customer'] = Customer::sales($paginate);
            return view('member.customer.editresult',$data);
        }
        else{
            return 'email already exist';
        }
	}
	
	public function checkuser($str = ''){
        $user_info = $this->user_info;
        switch ($str) {
            case 'user_id':
                return $user_info->user_id;
                break;
            case 'user_shop':
                return $user_info->user_shop;
                break;
            default:
                return '';
                break;
        }
    }
    
    public function createpaymentmethod(){
        $name = Request::input('name');
        $shop_id = $this->checkuser('user_shop');
        $count = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->where('payment_name',$name)->count();
        $return = '';
        if($count == 0){
            $insert['shop_id'] = $shop_id;
            $insert['payment_name'] = $name;
            $id = Tbl_payment_method::insertGetId($insert);
            // $data = Tbl_payment_method::where('payment_method_id',$id)->first();
            $return['message'] = 'success';
            $return['id'] = $id;
            $return['name'] = $name;
        }
        else{
            $return['message']  = 'error';
            $return['error'] = 'Method already exist';
        }
        return json_encode($return);
    }
	
	public function createterms(){
	    $category = Request::input('category');
        $name = Request::input('name');
        $day_month = Request::input('day_month');
        $day_due = Request::input('day_due');
        $fixed = Request::input('fixed');
        $shop_id = $this->checkuser('user_shop');
        
        $count = Tbl_term::where('shop_id',$shop_id)->where('term_name',$name)->where('archived',0)->count();
        $return = array();
        if($count == 0){
            $insert['shop_id'] = $shop_id;
            $insert['term_name'] = $name;
            $insert['term_category'] = $category;
            $insert['term_day'] = $fixed;
            $insert['term_day_of_month'] = $day_month;
            $insert['term_day_due_date'] = $day_due;
            $id = Tbl_term::insertGetId($insert);
            $return['message'] = 'success';
            $return['name'] = $name;
            $return['id'] = $id;
        }
        else{
            $return['message'] = 'error';
            $return['error'] = 'Term already exist.';
            
        }
        return json_encode($return);
	}
	
	public function createcustomer(){
	    // return $_POST;

        $shop_id = $this->checkuser('user_shop');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
	    $title = Request::input('title');
        $first_name = Request::input('first_name');
        $middle_name = Request::input('middle_name');
        $last_name = Request::input('last_name');
        $suffix = Request::input('suffix');
        $email = Request::input('email');
        $company = Request::input('company');
        $billing_country = Request::input('billing_country');
        $customer_status = Request::input("customer_status");
        $stockist_warehouse_id = Request::input("stockist_warehouse_id");

        $comfname = $company.$first_name.$middle_name.$last_name; 
        if($comfname == '')
        {
            $json['status'] = 'error';
            $json['status_message'] = 'Please insert company or a contact person.';
            return json_encode($json);
        }

        $is_approved = 0;
        if($customer_status == "approved")
        {
            $is_approved = 1;
        }
        
        $phone = Request::input('phone');
        $mobile = Request::input('mobile');
        $fax = Request::input('fax');
        
        $display_name_as = Request::input('display_name_as');
        $other = Request::input('other');
        $website = Request::input('website');
        $chck_print_on_as = Request::input('chck_print_on_as');
        $print_on_as_check_as = Request::input('print_on_as_check_as');
        $chck_is_sub_customer = 0;
        if(Request::has('chck_is_sub_customer'))
        {
            $chck_is_sub_customer = Request::input('chck_is_sub_customer');
        }
        
        $sub_customer_name = Request::input('sub_customer_name');
        
        $hidden_sub_customer = Request::input('hidden_sub_customer_id');
        $sub_customer_billing = Request::input('sub_customer_billing');
        $billing_street = Request::input('billing_street');
        $billing_city = Request::input('billing_city');
        $billing_state = Request::input('billing_state');
        $billing_zipcode = Request::input('billing_zipcode');
        
        $chk_same_shipping_address = Request::input('chk_same_shipping_address');
        $shipping_street = Request::input('shipping_street');
        $shipping_city = Request::input('shipping_city');
        $shipping_state = Request::input('shipping_state');
        $shipping_zipcode = Request::input('shipping_zipcode');
        $shipping_country = Request::input('shipping_country');
        $notes = Request::input('notes');
        $tax_resale_no = Request::input('tax_resale_no');
        $payment_method = Request::input('payment_method');
        $delivery_method = 0;
        if(Request::has('delivery_method')){
            $delivery_method = Request::input('delivery_method');
        }
        $hidden_payment_method = Request::input('hidden_payment_method');
        $txt_terms = Request::input('txt_terms');
        $hidden_terms = Request::input('hidden_terms');
        $opening_balance = Request::input('opening_balance');
        $date_as_of = Request::input('date_as_of'); 
        $count = 0;
        
        $tin_number = Request::input('tin_number');

        $proposal_number = Request::input("proposal_number");
        $ins_proposal = null;
        $ctr_proposal = 0;
        if($proposal_number)
        {
            foreach ($proposal_number as $key => $value) 
            {
                if($value)
                {
                    $ctr_proposal++;
                    $ins_proposal[$key] = $value;
                }
            }
        }
        $count_proposal_att = Request::has('fileurl') == false ? 0 : count(Request::input('fileurl'));
        
        if(AccountingTransaction::settings($this->user_info->shop_id, "customer_proposal_number"))
        {
            if($ctr_proposal != $count_proposal_att)
            {
                $data['message'] = 'error';
                $data['error'] = 'Proposal Number must equal on the attached proposal';
                return json_encode($data); 
            }
            $check_dupe = AccountingTransaction::check_proposal_exist($this->user_info->shop_id, $ins_proposal);
            if($check_dupe)
            {
                $data['message'] = 'error';
                $data['error'] = $check_dupe;
                return json_encode($data);                 
            }
        }

        $mlm_continue = 0;
        // luke add mlm username and password
        if(Request::input('ismlm') != null)
        {
            $mlm_username = Request::input('mlm_username');
            $mlm_password = Request::input('mlm_password');
            if($mlm_username != null || $mlm_username != "")
            {
                if(strlen($mlm_username) >= 4)
                {
                    $count_username = Tbl_customer::where('mlm_username', $mlm_username)->count();
                    if($count_username == 0)
                    {
                        if($mlm_password != null || $mlm_password != "")
                        {
                            if(strlen($mlm_password) >= 6)
                            {
                                $mlm_continue = 1;
                            }
                            else
                            {
                                $data['message'] = 'error';
                                $data['error'] = 'Password length must be over 6 characters.';
                                return json_encode($data); 
                            }
                        }
                        else
                        {
                            $data['message'] = 'error';
                            $data['error'] = 'Invalid Password.';
                            return json_encode($data); 
                        }
                    }
                    else
                    {
                        $data['message'] = 'error';
                        $data['error'] = 'Username Already Taken.';
                        return json_encode($data);
                    }
                }
                else
                {
                    $data['message'] = 'error';
                    $data['error'] = 'Username length must be over 6 characters.';
                    return json_encode($data);
                }
            }
            else
            {
                $data['message'] = 'error';
                $data['error'] = 'Invalid username.';
                return json_encode($data);
            }
        }
        // end luke

        if($email != '')
        {
            $count = Tbl_customer::where('shop_id',$shop_id)->where('email', $email)->count();
        }
        $ctr_company = 0;
        if($company != '')
        {
            $ctr_company = Tbl_customer::where('shop_id',$shop_id)->where('company', $company)->count();
        }      
        $data = array();

        if($ctr_company == 0)
        {
            if($count == 0)
            {
                $insertcustomer['shop_id'] = $shop_id;
                $insertcustomer['customer_warehouse_id'] = $warehouse_id;
                $insertcustomer['title_name'] = $title;
                $insertcustomer['first_name'] = $first_name;
                $insertcustomer['middle_name'] = $middle_name;
                $insertcustomer['last_name'] = $last_name;
                $insertcustomer['suffix_name'] = $suffix;
                $insertcustomer['email'] = $email;
                $insertcustomer['company'] = $company;
                $insertcustomer['country_id'] = $billing_country;
                $insertcustomer['created_date'] = Carbon::now();
                $insertcustomer['IsWalkin'] = 0;
                $insertcustomer['tin_number']= $tin_number;
                $insertcustomer['approved']= $is_approved;
                $insertcustomer['stockist_warehouse_id']= $stockist_warehouse_id;
                $insertcustomer['customer_proposals']= count($ins_proposal) > 0 ? serialize($ins_proposal) : null;

                $insertcustomer['fix_monthly_budget'] = str_replace(',', "", Request::input("fix_monthly_budget"));
                $insertcustomer['adjusted_monthly_budget'] = str_replace(',', "", Request::input("adjusted_monthly_budget"));

                $insertcustomer['customer_category'] = Request::input("customer_category") ?? 'new-client';
                $insertcustomer['customer_category_type'] = Request::input("customer_category_type") ?? 'non-vip';

                if($mlm_continue == 1)
                {
                    $insertcustomer['ismlm'] = 1;
                    $insertcustomer['mlm_username'] = $mlm_username;
                    $insertcustomer['password'] = Crypt::encrypt($mlm_password);
                }

                $customer_id = Tbl_customer::insertGetId($insertcustomer);
                
                $insertSearch['customer_id'] = $customer_id;
                $insertSearch['body'] = $title.' '.$first_name.' '.$middle_name.' '.$last_name.' '.$suffix.' '.$email.' '.$company;
                
                Tbl_customer_search::insert($insertSearch);
                
                $insertInfo['customer_id'] = $customer_id;
                $insertInfo['customer_phone'] = $phone;
                $insertInfo['customer_mobile'] = $mobile;
                $insertInfo['customer_fax'] = $fax;
                $insertInfo['customer_other_contact'] = $other;
                $insertInfo['customer_website'] = $website;
                $insertInfo['customer_display_name'] = $display_name_as;
                $insertInfo['customer_print_name'] = $print_on_as_check_as;
                $insertInfo['customer_parent'] = $hidden_sub_customer;
                $insertInfo['IsSubCustomer'] = $chck_is_sub_customer;
                if($sub_customer_billing == null)
                {
                    $sub_customer_billing = '';
                }
                
                $insertInfo['customer_billing'] = $sub_customer_billing;
                $insertInfo['customer_tax_resale_no'] = $tax_resale_no;
                $insertInfo['customer_payment_method'] = $payment_method;
                $insertInfo['customer_delivery_method'] = $delivery_method;
                $insertInfo['customer_terms'] = $hidden_terms;
                $insertInfo['customer_opening_balance'] = $opening_balance;
                $insertInfo['customer_balance_date'] = $date_as_of;
                $insertInfo['customer_notes'] = $notes;
                // dd($notes);
                Tbl_customer_other_info::insert($insertInfo);
                
                $insertAddress[0]['customer_id'] = $customer_id;
                $insertAddress[0]['country_id'] = $billing_country;
                $insertAddress[0]['customer_state'] = $billing_state;
                $insertAddress[0]['customer_city'] = $billing_city;
                $insertAddress[0]['customer_zipcode'] = $billing_zipcode;
                $insertAddress[0]['customer_street'] = $billing_street;
                $insertAddress[0]['purpose'] = 'billing';
                

                $insertAddress[1]['customer_id'] = $customer_id;
                $insertAddress[1]['country_id'] = $shipping_country;
                $insertAddress[1]['customer_state'] = $shipping_state;
                $insertAddress[1]['customer_city'] = $shipping_city;
                $insertAddress[1]['customer_zipcode'] = $shipping_zipcode;
                $insertAddress[1]['customer_street'] = $shipping_street;
                $insertAddress[1]['purpose'] = 'shipping';
                
                Tbl_customer_address::insert($insertAddress);
                
                
                // Tbl_customer_attachment
                if(Request::has('fileurl'))
                {
                    $fileurl = Request::input('fileurl');
                    $filename = Request::input("filename");
                    $mimetype = Request::input('mimetype');
                    $attachment = '';

                    if(count($fileurl) > 0)
                    {
                        foreach($fileurl as $key => $url)
                        {
                            $attachment[$key]['customer_id'] = $customer_id;
                            $attachment[$key]['customer_attachment_path'] = $url;
                        }
                        foreach($filename as $key => $name){
                            $attachment[$key]['customer_attachment_name'] = $name;
                        }
                        
                        foreach($mimetype as $key => $mime)
                        {
                            $attachment[$key]['mime_type'] = $mime;
                        }
                        if($attachment != '')
                        {
                            Tbl_customer_attachment::insert($attachment);
                        }
                    }
                    else
                    {
                        dd('<');
                    }
                }
                
                $data['customer_info'] = Tbl_customer::leftjoin('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')->where('tbl_customer.customer_id',$customer_id)->first();
                $data['customer_address'] = Tbl_customer_address::where('customer_id',$customer_id)->get();
                $data['message'] = 'success';
                $data['call_function'] = 'success_update_customer';
                $data['id'] = $customer_id;

                //$customer_data = Tbl_customer::where("customer_id",$customer_id)->first()->toArray();

                /* TRANSACTION JOURNAL FOR OPENING BALANCE */
                if($opening_balance > 0)
                {
                    $this->create_opening_balance($customer_id, $opening_balance);
                }

                //AuditTrail::record_logs("Added","customer",$customer_id,"",serialize($customer_data));
                
                $customer_data = Tbl_customer::info()->where("tbl_customer.customer_id",$customer_id)->first()->toArray();
                AuditTrail::record_logs("Added","customer",$customer_id,"",serialize($customer_data));

            }
            else
            {
                $data['message'] = 'error';
                $data['error'] = 'Email already exist.';
            }
        }
        else
        {
            $data['message'] = 'error';
            $data['error'] = 'Company name already exist.';
        }

        return json_encode($data);
	}
	
	//upload customer file start
	public function uploadcustomerfile()
	{
	    $file = Input::file('file');
	    $extension = $file->getClientOriginalExtension();
	    $path = "upload/member-" . $this->user_info->shop_id.'/customer_file';
        // dd($path);
	    $filename = $this->user_info->shop_id.'-'.date('ymdhis').'.'.$extension;
	    $original_name = $file->getClientOriginalName();
	    $mimetype = $file->getMimeType();
	    if(!file_exists($path))
        {
	        mkdir($path, 0777, true);
	    }
	    $file->move($path,$filename);
	    
	    $json['url'] = $path.'/'.$filename;
	    $json['original'] = $original_name;
	    $json['mimetype'] = $mimetype;
	    return json_encode($json);
	}
	
	public function removefilecustomer()
	{
	    $path = Request::input('path');
	    $value = Request::input('value');
	    $json = '';
	    if(!unlink($path)){
	        $json['result'] = 'error';
	        $json['message'] = 'Failed to remove the file.';
	    }
	    else{
	        Tbl_customer_attachment::where('customer_attachment_id',$value)->where('customer_attachment_path',$path)->delete();
	        $json['value'] = $value;
	        $json['result'] = 'success';
	    }
	    
	    return json_encode($json);
	}
	//upload customer file end
	
    public function customeredit($id)
    {
        if($this->hasAccess("customer-list","edit"))
        {
            $shop_id = $this->checkuser('user_shop');
    	    $data['_country'] = Tbl_country::orderBy('country_name','asc')->get();
    	    $data['_def_payment_method'] = Tbl_payment_method::where("isDefault",1)->orderBy('payment_name','asc')->get();
    	    $data['_payment_method'] = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->orderBy('payment_name','asc')->get();
        	$data['_term'] = Tbl_term::where('shop_id',$shop_id)->where('archived',0)->orderBy('term_name','asc')->get();
    	    $data['_customer'] = Tbl_customer::where('shop_id',$shop_id)->where('IsWalkin',0)->where('archived',0)->get();
    	    $data['customer_info'] =  Tbl_customer::where('customer_id',$id)->first();
            $data['billing'] = Tbl_customer_address::where('customer_id',$id)->where('purpose','billing')->first();
            $data['shipping'] = Tbl_customer_address::where('customer_id',$id)->where('purpose','shipping')->first();
            
            $data['_attachment'] = Tbl_customer_attachment::where('customer_id',$id)->get();
            $data['other'] = Tbl_customer_other_info::where('customer_id',$id)->first();
            $data['_warehouse'] = Warehouse2::get_all_warehouse($shop_id);
            
            $data['_delivery_method'] = Tbl_delivery_method::where('archived',0)->get();
            if(isset($data['other']->customer_payment_method))
            {
                $data['payment_name'] = Tbl_payment_method::where('payment_method_id',$data['other']->customer_payment_method)->first();
            }
            else
            {
                $data['payment_name'] = [];
            }
            if(isset($data['other']->customer_terms))
            {
                $data['termname'] = Tbl_term::where('term_id',$data['other']->customer_terms)->first();
            }
            else
            {
                $data['termname'] = [];
            }

            if(Purchasing_inventory_system::check())
            {
                $data["pis"] = true;                
            }
            else
            {
                $data["pis"] = false;
            }
            $data['_proposal'] = array();
            $data['proposal_number'] = AccountingTransaction::settings($this->user_info->shop_id, "customer_proposal_number");
            if($data['customer_info'] && $data['proposal_number'])
            {
                if($data['customer_info']->customer_proposals)
                {
                    $data['_proposal'] = unserialize($data['customer_info']->customer_proposals);
                }
            }
            $data['check_user'] = Purchasing_inventory_system::check();

            $data['monthly_budget'] = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
            $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");
    	    return view('member.modal.editcustomermodal',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
	
	public function downloadfile($id)
	{
	    $id = Crypt::decrypt($id);
	    $attachment = Tbl_customer_attachment::where('customer_attachment_id',$id)->first();
	    $file = $attachment->customer_attachment_path;
	    $filename = $attachment->customer_attachment_name;
	    $headers = array(
          'Content-Type: application/pdf',
        );
        
        return Response::download($file, $filename, $headers);
	}
	
    public function updatecustomermain()
    {
        $shop_id = $this->checkuser('user_shop');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        $client_id = Request::input('client_id');
        $title = Request::input('title');
        $first_name = Request::input('first_name');
        $middle_name = Request::input('middle_name');
        $last_name = Request::input('last_name');
        $suffix = Request::input('suffix');
        $email = Request::input('email');
        $company = Request::input('company');
        $stockist_warehouse_id = Request::input('stockist_warehouse_id');
        $billing_country = Request::input('billing_country');
        
        
        $comfname = $company.$first_name.$middle_name.$last_name; 
        if($comfname == '')
        {
            $json['status'] = 'error';
            $json['status_message'] = 'Please insert company or a contact person.';
            return json_encode($json);
        }
 
        $phone = Request::input('phone');
        $mobile = Request::input('mobile');
        $fax = Request::input('fax');
        
        $display_name_as = Request::input('display_name_as');
        $other = Request::input('other');
        $website = Request::input('website');
        $chck_print_on_as = Request::input('chck_print_on_as');
        $print_on_as_check_as = Request::input('print_on_as_check_as');
        $chck_is_sub_customer = 0;
        if(Request::has('chck_is_sub_customer'))
        {
            $chck_is_sub_customer = Request::input('chck_is_sub_customer');
        }
        
        $sub_customer_name = Request::input('sub_customer_name');
        
        $hidden_sub_customer = Request::input('hidden_sub_customer_id');
        $sub_customer_billing = Request::input('sub_customer_billing');
        $billing_street = Request::input('billing_street');
        $billing_city = Request::input('billing_city');
        $billing_state = Request::input('billing_state');
        $billing_zipcode = Request::input('billing_zipcode');
        
        $proposal_number = Request::input("proposal_number");
        $ins_proposal = null;
        $ctr_proposal = 0;
        if(count($proposal_number) > 0)
        {
            foreach ($proposal_number as $key => $value) 
            {
                if($value)
                {
                    $ctr_proposal++;
                    $ins_proposal[$key] = $value;
                }
            }
        }
        $count_proposal_att = Request::has('fileurl') == false ? 0 : count(Request::input('fileurl'));

        if(AccountingTransaction::settings($this->user_info->shop_id, "customer_proposal_number"))
        {
            if($ctr_proposal != $count_proposal_att)
            {
                $data['message'] = 'error';
                $data['error'] = 'Proposal Number must equal on the attached proposal';
                return json_encode($data); 
            }
            $check_dupe = AccountingTransaction::check_proposal_exist($this->user_info->shop_id, $ins_proposal, $client_id);
            if($check_dupe)
            {
                $data['message'] = 'error';
                $data['error'] = $check_dupe;
                return json_encode($data);                 
            }
        }


        $chk_same_shipping_address = Request::input('chk_same_shipping_address');
        $shipping_street = Request::input('shipping_street');
        $shipping_city = Request::input('shipping_city');
        $shipping_state = Request::input('shipping_state');
        $shipping_zipcode = Request::input('shipping_zipcode');
        $shipping_country = Request::input('shipping_country');
        $notes = Request::input('notes');
        $tax_resale_no = Request::input('tax_resale_no');
        $payment_method = Request::input('payment_method');
        $delivery_method = Request::input('delivery_method');
        $hidden_payment_method = Request::input('hidden_payment_method');
        $txt_terms = Request::input('txt_terms');
        $hidden_terms = Request::input('hidden_terms');
        $opening_balance = Request::input('opening_balance');
        $date_as_of = Request::input('date_as_of'); 
        $data = array();
        $count = 0;
        $tin_number = Request::input('tin_number');
        $mlm_continue = 0;
        // luke add mlm username and password
        if(Request::input('ismlm') != null)
        {
            $ismlm = Request::input('ismlm');
            switch ($ismlm) {
                case 1:
                    $mlm_username = Request::input('mlm_username');
                    $mlm_password = Request::input('mlm_password');
                    if($mlm_username != null || $mlm_username != "")
                    {
                        if(strlen($mlm_username) >= 4)
                        {
                            $count_username = Tbl_customer::where('mlm_username', $mlm_username)->where('customer_id', '!=', $client_id)->count();
                            if($count_username == 0)
                            {
                                if($mlm_password != null || $mlm_password != "")
                                {
                                    if(strlen($mlm_password) >= 6)
                                    {
                                        $mlm_continue = 1;
                                    }
                                    else
                                    {
                                        $data['message'] = 'error';
                                        $data['error'] = 'Password length must be over 6 characters.';
                                        return json_encode($data); 
                                    }
                                }
                                else
                                {
                                    $data['message'] = 'error';
                                    $data['error'] = 'Invalid Password.';
                                    return json_encode($data); 
                                }
                            }
                            else
                            {
                                $data['message'] = 'error';
                                $data['error'] = 'Username Already Taken.';
                                return json_encode($data);
                            }
                        }
                        else
                        {
                            $data['message'] = 'error';
                            $data['error'] = 'Username length must be over 6 characters.';
                            return json_encode($data);
                        }
                    }
                    else
                    {
                        $data['message'] = 'error';
                        $data['error'] = 'Invalid username.';
                        return json_encode($data);
                    }
                    break;
                case 2:
                    # code...
                    $mlm_continue = 2;
                    break;
                default:
                    # code...
                    break;
            }
            
        }
        // end luke

        if($email != '')
        {
            $count = Tbl_customer::where('shop_id',$shop_id)->where('email', $email)
            ->where('customer_id', '!=', $client_id)
            ->count();
        }
         $ctr_company = 0;
        if($company != '')
        {
            $ctr_company = Tbl_customer::where('shop_id',$shop_id)
                                        ->where('customer_id', '!=', $client_id)
                                        ->where('company', $company)
                                        ->count();
        }      
        if($ctr_company == 0)
        {        
            if($count == 0)
            {
                $updatecustomer['customer_warehouse_id'] = $warehouse_id;
                $updatecustomer['title_name'] = $title;
                $updatecustomer['first_name'] = $first_name;
                $updatecustomer['middle_name'] = $middle_name;
                $updatecustomer['last_name'] = $last_name;
                $updatecustomer['suffix_name'] = $suffix;
                $updatecustomer['email'] = $email;
                $updatecustomer['company'] = $company;
                $updatecustomer['country_id'] = $billing_country;
                $updatecustomer['created_date'] = Carbon::now();
                $updatecustomer['IsWalkin'] = 0;
                $updatecustomer['tin_number'] = $tin_number;
                $updatecustomer['stockist_warehouse_id'] = $stockist_warehouse_id;
                $updatecustomer['customer_proposals'] = $ins_proposal > 0 ? serialize($ins_proposal) : null;
                
                $updatecustomer['fix_monthly_budget'] = str_replace(',', "", Request::input("fix_monthly_budget"));
                $updatecustomer['adjusted_monthly_budget'] = str_replace(',', "", Request::input("adjusted_monthly_budget")); 

                $updatecustomer['customer_category'] = Request::input("customer_category") ?? 'new-client';
                $updatecustomer['customer_category_type'] = Request::input("customer_category_type") ?? 'non-vip';           

                switch ($mlm_continue) {
                    case 1:
                            $updatecustomer['ismlm'] = 1;
                            $updatecustomer['mlm_username'] = $mlm_username;
                            $updatecustomer['password'] = Crypt::encrypt($mlm_password);
                        break;
                    case 2:
                        # code...
                        break;
                    default:
                            $updatecustomer['ismlm'] = 0;
                        break;
                }

                $old_customer_data = Tbl_customer::info()->where("tbl_customer.customer_id",$client_id)->first()->toArray();

                Tbl_customer::where('customer_id',$client_id)->update($updatecustomer);


                $updatetSearch['body'] = $title.' '.$first_name.' '.$middle_name.' '.$last_name.' '.$suffix.' '.$email.' '.$company;
                
                Tbl_customer_search::where('customer_id',$client_id)->update($updatetSearch);
     
     
                $updateInfo['customer_phone'] = $phone;
                $updateInfo['customer_mobile'] = $mobile;
                $updateInfo['customer_fax'] = $fax;
                $updateInfo['customer_other_contact'] = $other;
                $updateInfo['customer_website'] = $website;
                $updateInfo['customer_display_name'] = $display_name_as;
                $updateInfo['customer_print_name'] = $print_on_as_check_as;
                $updateInfo['customer_parent'] = $hidden_sub_customer;
                $updateInfo['IsSubCustomer'] = $chck_is_sub_customer;
                if($sub_customer_billing == null){
                    $sub_customer_billing = '';
                }
                
                $updateInfo['customer_billing'] = $sub_customer_billing;
                $updateInfo['customer_tax_resale_no'] = $tax_resale_no;
                $updateInfo['customer_payment_method'] = $payment_method;
                $updateInfo['customer_delivery_method'] = $delivery_method;
                $updateInfo['customer_terms'] = $hidden_terms;
                $updateInfo['customer_opening_balance'] = $opening_balance;
                $updateInfo['customer_balance_date'] = $date_as_of;
                $updateInfo['customer_notes'] = $notes;
                
                Tbl_customer_other_info::where('customer_id',$client_id)->update($updateInfo);
                
                $insertAddress[0]['customer_id'] = $client_id;
                $insertAddress[0]['country_id'] = $billing_country;
                $insertAddress[0]['customer_state'] = $billing_state;
                $insertAddress[0]['customer_city'] = $billing_city;
                $insertAddress[0]['customer_zipcode'] = $billing_zipcode;
                $insertAddress[0]['customer_street'] = $billing_street;
                $insertAddress[0]['purpose'] = 'billing';
                $insertAddress[1]['customer_id'] = $client_id;
                $insertAddress[1]['country_id'] = $shipping_country;
                $insertAddress[1]['customer_state'] = $shipping_state;
                $insertAddress[1]['customer_city'] = $shipping_city;
                $insertAddress[1]['customer_zipcode'] = $shipping_zipcode;
                $insertAddress[1]['customer_street'] = $shipping_street;
                $insertAddress[1]['purpose'] = 'shipping';
                Tbl_customer_address::where('customer_id',$client_id)->delete();
                Tbl_customer_address::insert($insertAddress);
                
                
                // Tbl_customer_attachment
                Tbl_customer_attachment::where('customer_id',$client_id)->delete();
                if(Request::has('fileurl'))
                {
                    $fileurl = Request::input('fileurl');
                    $filename = Request::input("filename");
                    $mimetype = Request::input('mimetype');
                    $attachment = '';
                    foreach($fileurl as $key => $url){
                        $attachment[$key]['customer_id'] = $client_id;
                        $attachment[$key]['customer_attachment_path'] = $url;
                    }
                    foreach($filename as $key => $name){
                        $attachment[$key]['customer_attachment_name'] = $name;
                    }
                    
                    foreach($mimetype as $key => $mime)
                    {
                        $attachment[$key]['mime_type'] = $mime;
                    }
                    
                    if($attachment != ''){
                        Tbl_customer_attachment::insert($attachment);
                    }
                }
                
                $data['message'] = 'success';
                $data['call_function'] = 'success_update_customer';
                $data['customer'] = Tbl_customer::leftjoin('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')->where('tbl_customer.customer_id',$client_id)->select('tbl_customer.customer_id as customer_id1', 'tbl_customer.*', 'tbl_customer_other_info.*', 'tbl_customer_other_info.customer_id as cus_id')->first();
                $data['view'] = view('member.customer.customer_update_result',$data)->render();
                $data['address'] = Tbl_customer_address::where('customer_id',$client_id)->get();
                $data['target'] = '#tr-customer-'.$client_id;

                
                $new_customer_data = Tbl_customer::info()->where("tbl_customer.customer_id",$client_id)->first()->toArray();
                AuditTrail::record_logs("Edited","customer",$client_id,serialize($old_customer_data),serialize($new_customer_data));
                
            }
            else
            {
                $data['message'] = 'error';
                $data['error'] = 'Email already exist.';
            }
        }
        else
        {
            $data['message'] = 'error';
            $data['error'] = 'Company name already exist.';
        }


        return json_encode($data);
    }
	
    public function view_customer_details($id)
    {
        $data["customer"]              = Tbl_customer::info()->balanceJournal()->where("tbl_customer.customer_id", $id)->first();
        $data["_transaction"]          = Tbl_customer::transaction($this->checkuser('user_shop'), $id)->get();
        $data["customer"]->customer_id = $id;
        return view('member.customer.customer_details', $data);
    }

    public function create_opening_balance($customer_id, $amount)
    {
        $customer = Tbl_customer::where("customer_id", $customer_id)->first();
        $customer_info                      = [];
        $customer_info['customer_id']       = $customer->customer_id;
        $customer_info['customer_email']    = $customer->email;

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = '';
        $invoice_info['new_inv_id']         = '';
        $invoice_info['invoice_date']       = datepicker_input(Carbon::now());
        $invoice_info['invoice_due']        = datepicker_input(Carbon::now());
        $invoice_info['billing_address']    = '';

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = '';
        $invoice_other_info['invoice_memo'] = 'Opening Balance';

        $total_info                         = [];
        $total_info['ewt']                  = '';
        $total_info['total_discount_type']  = '';
        $total_info['total_discount_value'] = '';
        $total_info['taxable']              = '';

        $item_info                          = [];
        $item_info[0]['item_service_date']  = '';
        $item_info[0]['item_id']            = Item::getOtherChargeItem();
        $item_info[0]['item_description']   = 'Opening Balance';
        $item_info[0]['um']                 = '';
        $item_info[0]['quantity']           = 1;
        $item_info[0]['rate']               = $amount;
        $item_info[0]['discount']           = '';
        $item_info[0]['discount_remark']    = '';
        $item_info[0]['amount']             = $amount;
        $item_info[0]['taxable']            = '';
        $item_info[0]['ref_name']           = '';
        $item_info[0]['ref_id']             = '';

        $inv_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info);
    }

}