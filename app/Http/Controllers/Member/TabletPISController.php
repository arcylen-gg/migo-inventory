<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Globals\UnitMeasurement;
use App\Globals\Item;
use App\Globals\Tablet_invoice;
use App\Globals\Invoice;
use App\Globals\Transaction;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Customer;
use App\Globals\Accounting;
use App\Globals\Pdf_global;
use App\Globals\Category;
use App\Globals\CreditMemo;
use App\Globals\ReceivePayment;
use App\Globals\Tablet_global;

use App\Models\Tbl_terms;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_manual_credit_memo;
use App\Models\Tbl_employee;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_sir;
use App\Models\Tbl_customer;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_manual_receive_payment;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_sir_inventory;
use App\Models\Tbl_position;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_item;

use Session;
use Crypt;
use Validator;
use Redirect;
use Carbon\Carbon;

class TabletPISController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function edit_agent($id)
    {
        $data["_position"] = Tbl_position::where("position_code","sales_agent")->where("position_shop_id",Tablet_global::getShopId())->get();

        $data["edit"] = Tbl_employee::where("employee_id",$id)->first();
        $data["action"] = "/tablet/agent/edit_submit";

        return view('member.employee.employee_edit',$data);
    }
    public function edit_customer()
    {
        
    }
    public function edit_agent_submit()
    {
        $data["status"] = null;
        $data["status_message"] = null;

        $id = Request::input("employee_id");

        $first_name= Request::input("first_name");
        $last_name = Request::input("last_name");
        $middle_name = Request::input("middle_name");
        $position = Request::input("position");
        $email_address = Request::input("email_address");
        $password = Request::input("password");
        $username = Request::input("username");

        $insert["shop_id"] = Tablet_global::getShopId();
        $insert["first_name"] = $first_name;
        $insert["last_name"] = $last_name;
        $insert["middle_name"] = $middle_name;
        $insert["position_id"] = $position;
        $insert["email"] = $email_address;
        $insert["password"] = Crypt::encrypt($password);
        $insert["username"] = $username;
        $insert["created_at"] = Carbon::now();

        // dd($insert);
        $rule["first_name"] = 'required';
        $rule["last_name"] = 'required';
        $rule["middle_name"] = 'required';
        $rule["position_id"] = 'required';
        $rule["email"] = 'required|email|unique:tbl_employee,email,'.$id.",employee_id";
        $rule["password"] = 'required';
        $rule["username"] = 'required|alpha_num|unique:tbl_employee,username,'.$id.",employee_id";

        $validation = Validator::make($insert, $rule);

        if($validation->fails())
        {
            $data["status"] = "error";
            foreach ($validation->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        if($data["status"] == null)
        {            
            $data["status"] = "success";
            Tbl_employee::where("employee_id",$id)->update($insert);
        }

        return json_encode($data);
    }
	public function confirm_submission()
	{
		$data["action"] = "close";
        $data["sir_id"] = Session::get("sir_id");

		return view("tablet.agent.confirm_sync",$data);
	}
	public function submit_transactions()
	{	
        $sir_id = Request::input("sir_id");
        if(Request::input("action") == "close")
        {
            $data["status"] = Purchasing_inventory_system::close_sir($sir_id);
            Session::forget("sir_id");
        }	
        else
        {
            $update["reload_sir"] = 1;
            Tbl_sir::where("sir_id",$sir_id)->update($update);
            $data["status"] = "success-close";
        }
        return json_encode($data);
	}
    public function sir_reload($sir_id)
    {
        $data["action"] = "reload";
        $data["sir_id"] = $sir_id;

        return view("tablet.agent.confirm_sync",$data);
    }
    public function cm_choose_type()
    {
        $data["for_tablet"] = "true";
        $data["tablet"] = "true";
        $data["cm_id"] = Request::input("cm_id");

        return view("member.customer.credit_memo.cm_type",$data);
    }
     public function update_action()
    {
        $cm_id = Request::input("cm_id");
        $cm_type = Request::input("type");

        $data["cm_data"] = Tbl_credit_memo::where("cm_id",$cm_id)->first();
        $data["c_id"] = Tbl_credit_memo::where("cm_id",$cm_id)->value("cm_customer_id");

        if($cm_type == "invoice")
        {
            $data["_customer"]      = Tbl_customer::where("customer_id",$data["c_id"])->first();
            $data['_account']       = Accounting::getAllAccount('all','',['Bank']);
            $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
            $data['action']         = "/member/customer/receive_payment/add";
            $data["_invoice"]       = Invoice::getAllInvoiceByCustomer($data["c_id"], true);

            $cm_amount = $data["cm_data"]->cm_amount;
            $total_inv = 0;

            if(count($data["_invoice"]) > 0)
            {  
                $data["_invoice"][0]["amount_applied"] = $cm_amount;
                $data["_invoice"][0]["rpline_amount"] = $cm_amount;
            }

            return view("member.receive_payment.modal_receive_payment",$data);
        }
        if($cm_type == "invoice_tablet")
        {
            $data["_customer"]      = Tbl_customer::where("customer_id",$data["c_id"])->first();
            $data['_account']       = Accounting::getAllAccount('all','',['Bank']);
            $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
            $data['action']         = "/tablet/receive_payment/add_submit";
            $data["_invoice"]       = Invoice::getAllInvoiceByCustomer($data["c_id"],true);

            $cm_amount = $data["cm_data"]->cm_amount;
            $total_inv = 0;

            if(count($data["_invoice"]) > 0)
            {  
                $data["_invoice"][0]["amount_applied"] = $cm_amount;
                $data["_invoice"][0]["rpline_amount"] = $cm_amount;
            }
            return view("member.receive_payment.modal_receive_payment",$data);
        }
        if($cm_type == "others")
        {
            $up["cm_type"] = 1;
            $up["cm_used_ref_name"] = "others";

            Tbl_credit_memo::where("cm_id",$cm_id)->update($up);

            return Redirect::to("/member/customer/credit_memo/list");
        }
        if($cm_type == "others_tablet")
        {
            $up["cm_type"] = 1;
            $up["cm_used_ref_name"] = "others";

            Tbl_credit_memo::where("cm_id",$cm_id)->update($up);

            return Redirect::to("/tablet/credit_memo");
        }
    }
    public function getShopId()
    {
        $shop_id = collect(Session::get("sales_agent"));
        return $shop_id['shop_id'];
    }
	public function index()
	{
        if($this->get_user())
        {
            $data["employee_name"] = $this->get_user()->first_name." ".$this->get_user()->middle_name." ".$this->get_user()->last_name;
            $data["employee_position"] = $this->get_user()->position_name;
            $data["employee_id"] = $this->get_user()->employee_id;
            $data['_category']  = Category::getAllCategory(["inventory","all"], true);

            $sir_id = Request::input("sir_id");
            $data["_sir_item"] = array();

            $data["_sirs"] = Tbl_sir::where("sales_agent_id",$this->get_user()->employee_id)->whereIn("lof_status",[1,2])->whereIn("sir_status",[0,1])->get();
            if($sir_id != null)
            {
                $sir_data = Tbl_sir::where("sir_id",$sir_id)->first();
                if($sir_data)
                {
                    if($sir_data->lof_status == 1)
                    {                    
                        Session::put("sir_id",$sir_id);
                        $data["_sir_item"] = Purchasing_inventory_system::get_sir_item($sir_id);

                        return view("tablet.index",$data);
                    }
                    elseif($sir_data->sir_status == 1)
                    {

                        Session::put("sir_id",$sir_id);
                        $data["open_sir"] = Tbl_sir::truck()->saleagent()->where("sales_agent_id",$this->get_user()->employee_id)->where("sir_status",1)->where("sir_id",$sir_id)->first();

                        $data["_invoices"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",0)->where("inv_is_paid",0)->get();

                        $data["_sales_receipt"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",1)->get();
                        $data["total_sales_receipt"] = 0;
                        foreach ($data["_sales_receipt"] as $key => $value) 
                        {
                            $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
                            $cm_amt = 0 ;
                            if($cm != null)
                            {
                              $cm_amt = $cm->cm_amount;  
                            }
                            $data["total_sales_receipt"] += $value->inv_overall_price - $cm_amt;
                        }

                        $data["total_invoice_amount"] = 0;
                        foreach ($data["_invoices"] as $key => $value) 
                        {
                            $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
                            $cm_amt = 0 ;
                            if($cm != null)
                            {
                              $cm_amt = $cm->cm_amount;  
                            }
                            $data["total_invoice_amount"] += $value->inv_overall_price - $cm_amt;
                        }

                        $data["_receive_payment"] = Tbl_manual_receive_payment::sir()->customer_receive_payment()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_receive_payment.rp_id","DESC")->get();
                        $data["total_receive_payment"] = 0;
                        foreach ($data["_receive_payment"] as $key => $value) 
                        {
                            $data["total_receive_payment"] += $value->rp_total_amount;
                        }

                        $data["_cm"] = Tbl_manual_credit_memo::sir()->customer_cm()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_credit_memo.cm_id","DESC")->get();
                        $data["total_cm"] = 0;
                        foreach ($data["_cm"] as $key => $value) 
                        {
                            $data["total_cm"] += $value->cm_amount;
                        }

                        $data["total_receive_payment"] = currency("Php", $data["total_receive_payment"]);
                        $data["total_invoice_amount"] = currency("Php", $data["total_invoice_amount"]);
                        $data["total_sales_receipt"] = currency("Php", $data["total_sales_receipt"]);
                        $data["total_cm"] = currency("Php", $data["total_cm"]);
                        $data["total_customer"] = Customer::countAllCustomer(true);

                        $data["_customer"] = Customer::getAllCustomer(true);


                        return view("tablet.agent.agent_dashboard",$data);

                    }
                    else
                    { 
                        Session::put("sir_id",$sir_id);
                        $data["no_sir"] = "no_sir";                                
                        if($sir_data->ilr_status == 1)
                        {
                            $data["no_sir"] = "close_sir";
                        }
                        return view("tablet.index",$data);                    
                    }
                }
            }
            else
            {
                $data["open_sir"] = Tbl_sir::truck()->saleagent()->where("sales_agent_id",$this->get_user()->employee_id)->where("sir_status",1)->first();
                if($data["open_sir"])
                {
                    if($data["open_sir"]->reload_sir == 0)
                    {
                        Session::put("sir_id",$data["open_sir"]->sir_id);
                        $data["_invoices"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",0)->where("inv_is_paid",0)->get();

                        $data["_sales_receipt"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",1)->get();
                        $data["total_sales_receipt"] = 0;
                        foreach ($data["_sales_receipt"] as $key => $value) 
                        {
                            $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
                            $cm_amt = 0 ;
                            if($cm != null)
                            {
                              $cm_amt = $cm->cm_amount;  
                            }
                            $data["total_sales_receipt"] += $value->inv_overall_price - $cm_amt;
                        }

                        $data["total_invoice_amount"] = 0;
                        foreach ($data["_invoices"] as $key => $value) 
                        {
                            $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
                            $cm_amt = 0 ;
                            if($cm != null)
                            {
                              $cm_amt = $cm->cm_amount;  
                            }
                            $data["total_invoice_amount"] += $value->inv_overall_price - $cm_amt;
                        }

                        $data["_receive_payment"] = Tbl_manual_receive_payment::sir()->customer_receive_payment()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_receive_payment.rp_id","DESC")->get();
                        $data["total_receive_payment"] = 0;
                        foreach ($data["_receive_payment"] as $key => $value) 
                        {
                            $data["total_receive_payment"] += $value->rp_total_amount;
                        }

                        $data["_cm"] = Tbl_manual_credit_memo::sir()->customer_cm()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_credit_memo.cm_id","DESC")->get();
                        $data["total_cm"] = 0;
                        foreach ($data["_cm"] as $key => $value) 
                        {
                            $data["total_cm"] += $value->cm_amount;
                        }

                        $data["total_receive_payment"] = currency("Php", $data["total_receive_payment"]);
                        $data["total_invoice_amount"] = currency("Php", $data["total_invoice_amount"]);
                        $data["total_sales_receipt"] = currency("Php", $data["total_sales_receipt"]);
                        $data["total_cm"] = currency("Php", $data["total_cm"]);
                        $data["total_customer"] = Customer::countAllCustomer(true);

                        $data["_customer"] = Customer::getAllCustomer(true);  
                        return view("tablet.agent.agent_dashboard",$data);                      
                    }
                    else
                    {
                        Session::put("sir_id",$data["open_sir"]->sir_id);
                        $data["no_sir"] = "reload";   
                        
                        return view("tablet.index",$data);
                    }

                }
                else
                {
                    $data["sir"] = Purchasing_inventory_system::tablet_lof_per_sales_agent($this->getShopId() ,'array',1,null,$this->get_user()->employee_id);
                    if($data['sir'])
                    {
                        Session::put("sir_id",$data["sir"]->sir_id);
                        $data["_sir_item"] = Purchasing_inventory_system::get_sir_item($data["sir"]->sir_id);
                    }
                    else
                    {
                        $data["sir"] = Purchasing_inventory_system::tablet_lof_per_sales_agent($this->getShopId(),'array',2,null,$this->get_user()->employee_id);
                        if($data["sir"])
                        {
                            Session::put("sir_id",$data["sir"]->sir_id);
                            $data["no_sir"] = "no_sir";                                
                            if($data["sir"]->ilr_status == 1)
                            {
                                $data["no_sir"] = "close_sir";
                            }
                        }
                    }
                    return view("tablet.index",$data);         
                }
            }           
        }
        else
        {
            return redirect("/tablet");
        }

	}
	public function selected_sir()
	{
		Session::forget("sir_id");
		$sir_id = Request::input("sir_id");

		Session::put("sir_id",$sir_id);

		$data["status"] = "success";
		return json_encode($data);
	}
    public function credit_memo()
    {
        $data["pis"] = Purchasing_inventory_system::check(true);

        $data["employee_name"] = $this->get_user()->first_name." ".$this->get_user()->middle_name." ".$this->get_user()->last_name;
        $data["employee_position"] = $this->get_user()->position_name;
        $data["employee_id"] = $this->get_user()->employee_id;

        if(Session::get("sir_id") != null)
        {
            $data["_cm"] = Tbl_manual_credit_memo::sir()->customer_cm()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_credit_memo.cm_id","DESC")->get();
        }
        return view("tablet.agent.credit_memo",$data);
    }
    public function add_cm()
    {
        $data["sir_id"]     = Request::input("sir_id");
        $data["page"]       = "Credit Memo";
        $data["_customer"]  = Customer::getAllCustomer(true);
        $data['_item']      = Item::get_all_category_item([1,2,3,4],true);
        $data['_um']        = UnitMeasurement::load_um_multi(true);
        $data["action"]     = "/tablet/credit_memo/add_cm_submit";

        $id = Request::input('id');
        if($id)
        {
            $data["cm"]            = Tbl_credit_memo::where("cm_id", $id)->first();
            $data["_cmline"]       = Tbl_credit_memo_line::cm_item()->um()->where("cmline_cm_id", $id)->get();
            $data["action"]        = "/tablet/credit_memo/edit_cm_submit";
        }

        return view("tablet.agent_transaction.credit_memo.credit_memo_transaction",$data);
    }
    public function add_cm_submit()
    {
        $ctr = 0;
        $data["status"] = null;
        $data["status_message"] = null;

        $customer_info[] = null;
        $customer_info["cm_customer_id"] = Request::input("cm_customer_id");
        $customer_info["cm_customer_email"] = Request::input("cm_customer_email");
        $customer_info["cm_date"] = datepicker_input(Request::input("cm_date"));
        $customer_info["cm_message"] = Request::input("cm_message");
        $customer_info["cm_memo"] = Request::input("cm_memo");
        $customer_info["cm_amount"] = Request::input("overall_price");

        $item_info[] = null;
        $_items = Request::input("cmline_item_id");
        if($_items)
        {
            foreach ($_items as $key => $value) 
            {     
                if($value != null)
                {  
                $ctr ++;              
                    $item_info[$key]['item_service_date']  = "";
                    $item_info[$key]['item_id']            = Request::input('cmline_item_id')[$key];
                    $item_info[$key]['item_description']   = Request::input('cmline_description')[$key];
                    $item_info[$key]['um']                 = Request::input('cmline_um')[$key];
                    $item_info[$key]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$key]);
                    $item_info[$key]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$key]);
                    $item_info[$key]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$key]);
                }      
            }
        }
        if($ctr == 0)
        {
            $data["status"] = "error";
            $data["status_message"] = "Please Insert Item";
        }
        if($data["status"] == null)
        {            
            $cm_id = CreditMemo::postCM($customer_info, $item_info,0, true);

            $ins_manual_cm["sir_id"] = Request::input("sir_id");
            $ins_manual_cm["cm_id"] = $cm_id;
            $ins_manual_cm["manual_cm_date"] = Carbon::now();

            Tbl_manual_credit_memo::insert($ins_manual_cm);

            $data["status"] = "success-credit-memo-tablet";
            $data["id"] = $cm_id;
            $data["redirect_to"] = "/tablet/credit_memo/add?id=".$cm_id."&sir_id=".Request::input("sir_id");
        }

        return json_encode($data);

    }
    public function edit_cm_submit()
    {
        $cm_id = Request::input("credit_memo_id");

        $ctr = 0;
        $data["status"] = null;
        $data["status_message"] = null;

        $customer_info[] = null;
        $customer_info["cm_customer_id"] = Request::input("cm_customer_id");
        $customer_info["cm_customer_email"] = Request::input("cm_customer_email");
        $customer_info["cm_date"] = datepicker_input(Request::input("cm_date"));
        $customer_info["cm_message"] = Request::input("cm_message");
        $customer_info["cm_memo"] = Request::input("cm_memo");
        $customer_info["cm_amount"] = Request::input("overall_price");

        $item_info[] = null;
        $_items = Request::input("cmline_item_id");
        if($_items)
        {
            foreach ($_items as $key => $value) 
            {     
                if($value != null)
                {     
                    $ctr++;  
                    $item_info[$key]['item_service_date']  = "";
                    $item_info[$key]['item_id']            = Request::input('cmline_item_id')[$key];
                    $item_info[$key]['item_description']   = Request::input('cmline_description')[$key];
                    $item_info[$key]['um']                 = Request::input('cmline_um')[$key];
                    $item_info[$key]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$key]);
                    $item_info[$key]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$key]);
                    $item_info[$key]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$key]);
                }
            }            
        }
        if($ctr == 0)
        {
            $data["status"] = "error";
            $data["status_message"] = "Please Insert Item";
        }
        if($data["status"] == null)
        {  
            CreditMemo::updateCM($cm_id, $customer_info, $item_info);

            $data["status"] = "success-credit-memo";
            $data["redirect_to"] = "/tablet/credit_memo/add?id=".$cm_id;
        }
        return json_encode($data);
    }
    public function invoice()
    {
        $data["employee_name"] = $this->get_user()->first_name." ".$this->get_user()->middle_name." ".$this->get_user()->last_name;
        $data["employee_position"] = $this->get_user()->position_name;
        $data["employee_id"] = $this->get_user()->employee_id;

        if(Session::get("sir_id") != null)
        {
            $data["_invoices"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",0)->get();
            foreach ($data["_invoices"] as $key => $value) 
            {
                $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
                if($cm != null)
                {
                  $data["_invoices"][$key]->inv_overall_price = $value->inv_overall_price - $cm->cm_amount;  
                }
            }
        }
        return view("tablet.agent.invoice",$data);

    }
    public function customer()
    {   
        $data["employee_name"] = $this->get_user()->first_name." ".$this->get_user()->middle_name." ".$this->get_user()->last_name;
        $data["employee_position"] = $this->get_user()->position_name;
        $data["employee_id"] = $this->get_user()->employee_id;

        if(Session::get("sir_id") != null)
        {    
            $data["_customer"] = Customer::getAllCustomer(true);
        }
        return view("tablet.agent.customer",$data);
    }
    public function checkuser($str = '')
    {
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
    public function customer_details($id)
    {
        $data["customer"]       = Tbl_customer::info()->balance($this->checkuser('user_shop'), $id)->where("tbl_customer.customer_id", $id)->first();
        $data["_transaction"]   = Tbl_customer::transaction($this->checkuser('user_shop'), $id)->get();

        return view("tablet.agent.customer_details",$data);
    }
    public function receive_payment()
    {
        $data["employee_name"] = $this->get_user()->first_name." ".$this->get_user()->middle_name." ".$this->get_user()->last_name;
        $data["employee_position"] = $this->get_user()->position_name;
        $data["employee_id"] = $this->get_user()->employee_id;

        if(Session::get("sir_id") != null)
        {
            $data["_receive_payment"] = Tbl_manual_receive_payment::sir()->customer_receive_payment()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_receive_payment.rp_id","DESC")->get();
        }
        return view("tablet.agent.receive_payment",$data);
    }
	public function review_sir($sir_id)
	{
        $data["sir_id"] = $sir_id;
        $data["type_code"] = "lof";
	    $data["type"] = "Load Out Form";
	    $data["tablet"] = "confirm_reject";

        return view("member.purchasing_inventory_system.sir_view",$data);
	}
	public function lof_action($id, $action)
	{
     	$data["sir_id"] = $id;
        $data["action"] = $action;

        return view("tablet.confirm_tablet",$data);
	}
	public function lof_action_submit()
	{
        $id = Request::input("sir_id");
        $action = Request::input("action");

        if($action == "confirm")
        {
        	$update["lof_status"] = 2;
        	$update["sir_status"] = 1;
            $update["is_sync"] = 1;
        }
        else if($action == "reject")
        {
        	$update["lof_status"] = 3;
            $update["rejection_reason"] = Request::input("reason_txt");

            Purchasing_inventory_system::reject_return_stock($id);
        }

        Tbl_sir::where("sir_id",$id)->update($update);

        $data["status"] = "success";

        return json_encode($data);

	}
	public function logout()
	{
		Session::forget('sales_agent');
		return Redirect::to("/tablet");
	}
	public function get_user()
	{
		$user = Session::get("sales_agent");
		if($user != null)
		{
			return $user;
		}
		else
		{
			return null;
		}
	}
	public function inventory_sir($sir_id)
	{
		$data = Purchasing_inventory_system::get_inventory_in_sir($sir_id);

		return view('tablet.sir_inventory',$data);
	}
	public function view_invoices($sir_id)
	{        
		$data["_invoices"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",$sir_id)->orderBy("Tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",0)->get();
		$data["sir_id"] = $sir_id;
		return view("member.customer_invoice.customer_invoice_list",$data);
	}
	public function sync_import()
	{
		$update["is_sync"] = 1;
		Tbl_sir::where("sales_agent_id",$this->get_user()->employee_id)->where("sir_status",1)->update($update);

		$data["status"] = "success";

		return json_encode($data);
	}
	public function tablet_create_invoice()
	{         
		$sir_id = Request::input("sir_id");
        $data["c_id"] = Request::input("customer_id");
        $data["pis"]       = Purchasing_inventory_system::check(true);
        
		$data["_customer"]  = Customer::getAllCustomer(true);
        $data['_um']        = UnitMeasurement::load_um_multi(true);
        $data["_terms"]     = Tbl_terms::where("archived", 0)->where("terms_shop_id", $this->getShopId())->get();
		$data['_item']      = Item::get_all_item_sir($sir_id, true);
        $data['_cm_item']   = Item::get_returnable_item(true);
        $data["new_inv_id"] = Transaction::get_last_number("tbl_customer_invoice","new_inv_id","inv_shop_id", true); 
		$data["sir_id"] = $sir_id;
		// dd($data["sir_id"]);
		$data["action"] = "/tablet/create_invoice/add_submit";
		// dd($data["_item"]);
		$id = Request::input("id");
		$sir = Tbl_manual_invoice::where("inv_id",$id)->first();

        if($sir)
        {
            $data["inv"]            = Tbl_customer_invoice::appliedPayment($this->getShopId())->where("inv_id", $id)->first();
            
            $data["_invline"]       = Tbl_customer_invoice_line::invoice_item()->um()->where("invline_inv_id", $id)->get();
            $data["_cmline"]       = Tbl_customer_invoice::returns_item()->where("inv_id", $id)->get();
            // dd($data["_cmline"]);

            $data["sir_id"] = $sir->sir_id;
            $data["action"] = "/tablet/update_invoice/edit_submit";
            $data['_item'] = Item::get_all_item_sir($sir->sir_id, true);
        }

		return view('tablet.agent_transaction.invoice.invoice_transaction', $data);
	}
    public function invoice_add_item($id)
    {
        $data["item_details"] = Item::get_item_details($id);
        $data["sir_id"] = Request::input("sir_id");
        $data['_um']          = UnitMeasurement::load_um_multi(true);
        $data['action']       = "/tablet/invoice/add_item_submit";

        return view('tablet.agent_transaction.invoice.add_item_modal',$data);
    }
    public function credit_memo_add_item($id, $is_return = false)
    {
        $data["item_details"] = Item::get_item_details($id);
        $data["sir_id"] = Request::input("sir_id");
        $data['_um']          = UnitMeasurement::load_um_multi(true);
        $data['action']       = "/tablet/invoice/add_item_submit";
        $data["is_return"] = $is_return;

        return view('tablet.agent_transaction.credit_memo.add_item_modal_cm',$data);        
    }
	public function tablet_receive_payment()
	{
		$data["c_id"] = Request::input("customer_id");
	    $data["_customer"]      = Customer::getAllCustomer(true);
        $data['_account']       = Accounting::getAllAccount('all',null, null, null, false,true);
        $data["_terms"]     = Tbl_terms::where("archived", 0)->where("terms_shop_id", $this->getShopId())->get();
        $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
        $data['action']         = "/tablet/receive_payment/add_submit";
        $data["_invoice"] = Invoice::getAllInvoiceByCustomer($data["c_id"], true);

        $id = Request::input('id');
        if($id)
        {
            $data["rcvpayment"]         = Tbl_receive_payment::where("rp_id", $id)->first();
            $data["_rcvpayment_line"]   = Tbl_receive_payment_line::where("rpline_rp_id", $id)->get();
            $data["_invoice"]           = Invoice::getAllInvoiceByCustomerWithRcvPymnt($data["rcvpayment"]->rp_customer_id, $data["rcvpayment"]->rp_id, true);
            // dd($data["_invoice"]);
            $data['action']             = "/tablet/receive_payment/update/".$data["rcvpayment"]->rp_id;
        }

        return view("tablet.agent_transaction.receive_payment.receive_payment_transaction", $data);
	}

    public function load_customer_rp($customer_id)
    {
        $data["_invoice"] = Invoice::getAllInvoiceByCustomer($customer_id,true);
        return view('member.receive_payment.load_receive_payment_items', $data);
    }
	public function add_receive_payment()
	{
        //for credit memo
        $cm_id = Request::input("cm_id");

		$insert["rp_shop_id"]           = $this->getShopId();
        $insert["rp_customer_id"]       = Request::input('rp_customer_id');
        $insert["rp_ar_account"]        = Request::input('rp_ar_account') or 0;
        $insert["rp_date"]              = datepicker_input(Request::input('rp_date'));
        $insert["rp_total_amount"]      = convertToNumber(Request::input('rp_total_amount'));
        $insert["rp_payment_method"]    = Request::input('rp_payment_method');
        $insert["rp_memo"]              = Request::input('rp_memo');
        $insert["date_created"]         = Carbon::now();

        if($cm_id != '')
        {
            $insert["rp_ref_name"]        = "credit_memo";
            $insert["rp_ref_id"]          = $cm_id;
        }

        $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

        $txn_line = Request::input('line_is_checked');
        foreach($txn_line as $key=>$txn)
        {
            if($txn == 1)
            {
                $insert_line["rpline_rp_id"]            = $rcvpayment_id;
                $insert_line["rpline_reference_name"]   = Request::input('rpline_txn_type')[$key];
                $insert_line["rpline_reference_id"]     = Request::input('rpline_txn_id')[$key];
                $check_inv_if_have_cm = Tbl_customer_invoice::c_m()->where("inv_id",Request::input('rpline_txn_id')[$key])->first();
                $cm_amount = 0;
                if($check_inv_if_have_cm != null)
                {
                    $cm_amount = $check_inv_if_have_cm->cm_amount;
                }
                $insert_line["rpline_amount"]           = convertToNumber(Request::input('rpline_amount')[$key]) + $cm_amount;

                Tbl_receive_payment_line::insert($insert_line);
                if($insert_line["rpline_reference_name"] == 'invoice')
                {
                    Invoice::updateAmountApplied($insert_line["rpline_reference_id"],true);
                }
            }
        }

        $button_action = Request::input('button_action');

        $json["status"]         = "success";
        $json["rcvpayment_id"]  = $rcvpayment_id;
        $json["message"]        = "Successfully received payment";
        $json["url"]            = "/tablet/receive_payment";

        $ins_manual_rcv_pymnt["rp_id"] = $rcvpayment_id;
        $ins_manual_rcv_pymnt["sir_id"] = Session::get("sir_id");
        $ins_manual_rcv_pymnt["rp_date"] = Carbon::now();
        $ins_manual_rcv_pymnt["agent_id"] = $this->get_user()->employee_id;

        Tbl_manual_receive_payment::insert($ins_manual_rcv_pymnt);

        if($cm_id == '')
        {
            if($button_action == "save-and-edit")
            {
                $json["redirect"]    = "/tablet/receive_payment/add?id=".$rcvpayment_id;
            }            
        }
        else
        {
            ReceivePayment::updateCM($cm_id,$rcvpayment_id);
            $json["redirect"]    = "/tablet/credit_memo";
        }

        return json_encode($json);
	}
	public function update_receive_payment($rcvpayment_id)
    {
        // dd(Request::input());

        $update["rp_customer_id"]       = Request::input('rp_customer_id');
        $update["rp_ar_account"]        = Request::input('rp_ar_account') or 0;
        $update["rp_date"]              = datepicker_input(Request::input('rp_date'));
        $update["rp_total_amount"]      = convertToNumber(Request::input('rp_total_amount'));
        $update["rp_payment_method"]    = Request::input('rp_payment_method');
        $update["rp_memo"]              = Request::input('rp_memo');

        Tbl_receive_payment::where("rp_id", $rcvpayment_id)->update($update);
        Tbl_receive_payment_line::where("rpline_rp_id", $rcvpayment_id)->delete();

        $txn_line = Request::input('line_is_checked');
        foreach($txn_line as $key=>$txn)
        {
            if($txn == 1)
            {
                $insert_line["rpline_rp_id"]            = $rcvpayment_id;
                $insert_line["rpline_reference_name"]   = Request::input('rpline_txn_type')[$key];
                $insert_line["rpline_reference_id"]     = Request::input('rpline_txn_id')[$key];
                    $cm_amount = CreditMemo::cm_amount(Request::input('rpline_txn_id')[$key]);
                $insert_line["rpline_amount"]           = convertToNumber(Request::input('rpline_amount')[$key]) + $cm_amount;

                Tbl_receive_payment_line::insert($insert_line);
                if($insert_line["rpline_reference_name"] == 'invoice')
                {
                    Invoice::updateAmountApplied($insert_line["rpline_reference_id"]);
                }
            }
        }

        $button_action = Request::input('button_action');

        $json["status"]         = "success";
        $json["rcvpayment_id"]  = $rcvpayment_id;
        $json["message"]        = "Successfully updated payment";
        $json["redirect"]            = "/tablet/receive_payment/add?id=".$rcvpayment_id;
        
        if($button_action == "save-and-new")
        {
            $json["redirect"]   = '/tablet/receive_payment/add';
        }

        return json_encode($json);
    }

	public function create_invoice_submit()
	{  
		$sir_id = Request::input("sir_id");
		$data["status"] = "";
		$data["status_message"] = "";

		$customer_info                      = [];
		$customer_info['customer_id']       = Request::input('inv_customer_id');;
		$customer_info['customer_email']    = Request::input('inv_customer_email') or "test@gmail.com";

		$invoice_info                       = [];
		$invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
		$invoice_info['invoice_date']       = datepicker_input(Request::input('inv_date'));
		$invoice_info['invoice_due']        = datepicker_input(Request::input('inv_due_date'));
		$invoice_info['billing_address']    = Request::input('inv_customer_billing_address') or "billing";

		$invoice_other_info                 = [];
		$invoice_other_info['invoice_msg']  = Request::input('inv_message') or "test";
		$invoice_other_info['invoice_memo'] = Request::input('inv_memo') or "test";

		$total_info                         = [];
		$total_info['total_subtotal_price'] = str_replace(',', "", Request::input('subtotal_price'));
		$total_info['ewt']                  = Request::input('ewt');
		$total_info['total_discount_type']  = Request::input('inv_discount_type');
		$total_info['total_discount_value'] = Request::input('inv_discount_value');
		$total_info['taxable']              = Request::input('taxable');
		$total_info['total_overall_price']  = Request::input('overall_price');

		$item_info                          = [];
		$_itemline                          = Request::input('invline_item_id');

		$return = 0;
        if($_itemline)
        {
            foreach($_itemline as $key => $item_line)
            {
                if($item_line)
                {
                    $item_info[$key]['item_service_date']  = "";
                    $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                    $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                    $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                    $item_info[$key]['quantity']           = str_replace(',', "",Request::input('invline_qty')[$key]);
                    $item_info[$key]['rate']               = str_replace(',', "", Request::input('invline_rate')[$key]);
                    $item_info[$key]['discount']           = Request::input('invline_discount')[$key];
                    $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                    $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                    $item_info[$key]['amount']             = str_replace(',', "", Request::input('invline_amount')[$key]);
                    $item_info[$key]['ref_name']           = "";
                    $item_info[$key]['ref_id']             = "";

                    $return += Purchasing_inventory_system::check_qty_sir($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key],0,"tbl_customer_invoice_line");
                    if($return != 0)
                    {
                        $item_name[$key] = Tbl_item::where("item_id",Request::input("invline_item_id")[$key])->value("item_name");
                    }
                }
            }
        }

        //CM returns
        $cm_customer_info[] = null;
        $cm_item_info = null;
        $item_returns = null;    
        if(Request::input("returns") != null && Purchasing_inventory_system::check(true) != 0)
        {
            $cm_customer_info["cm_customer_id"] = Request::input('inv_customer_id');
            $cm_customer_info["cm_customer_email"] = Request::input('inv_customer_email');
            $cm_customer_info["cm_date"] = datepicker_input(Request::input('inv_date'));
            $cm_customer_info["cm_message"] = "";
            $cm_customer_info["cm_memo"] = "";
            $cm_customer_info["cm_amount"] = str_replace(",","",Request::input("subtotal_price_returns"));

            $cm_item_info[] = null;
            $_cm_items = Request::input("cmline_item_id");
            if($_cm_items != null)
            {
                foreach ($_cm_items as $keys => $values) 
                { 
                    if($values != null)
                    {
                        $cm_item_info[$keys]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$keys]);
                        $cm_item_info[$keys]['item_id']            = Request::input('cmline_item_id')[$keys];
                        $cm_item_info[$keys]['item_description']   = Request::input('cmline_description')[$keys];
                        $cm_item_info[$keys]['um']                 = Request::input('cmline_um')[$keys];
                        $cm_item_info[$keys]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$keys]);
                        $cm_item_info[$keys]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$keys]);
                        $cm_item_info[$keys]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$keys]);
                
                        $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keys]);
                        $item_returns[$keys]["qty"] = $um_qty * $cm_item_info[$keys]['quantity'];
                        $item_returns[$keys]["item_id"] = Request::input('cmline_item_id')[$keys];                    
                    }          
                } 
                // --> for bundles
                foreach ($_cm_items as $keyitem_cm => $value_item) 
                {
                    if($value_item != null)
                    {
                        $item_bundle_info = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitem_cm])->where("item_type_id",4)->first();
                        if($item_bundle_info)
                        {
                            $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("cmline_item_id")[$keyitem_cm])->get();
                            foreach ($bundle as $key_bundle_cm => $value_bundle_cm) 
                            {
                                $qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keyitem_cm]);
                                $bundle_qty = UnitMeasurement::um_qty($value_bundle_cm->bundle_um_id);
                                $_bundle[$key_bundle_cm]['item_id'] = $value_bundle_cm->bundle_item_id;
                                $_bundle[$key_bundle_cm]['qty'] = (Request::input('cmline_qty')[$keyitem_cm] * $qty) * ($value_bundle_cm->bundle_qty * $bundle_qty);

                                array_push($item_returns, $_bundle[$key_bundle_cm]);
                            }
                        }                 
                    }
                }
                if($item_returns != null)
                {
                    foreach ($item_returns as $key_items_cm => $value_items_cm) 
                    {
                         $i = null;
                         foreach ($_cm_items as $keyitemline_cm => $valueitemline)
                         {
                            $type = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitemline_cm])->value("item_type_id");
                            if($type == 4)
                            {
                                if(Request::input("cmline_item_id")[$keyitemline_cm] == $value_items_cm['item_id'])
                                {
                                    $i = "true";
                                }                    
                            }
                         }
                        if($i != null)
                        {
                            unset($item_returns[$key_items_cm]);
                        }           
                    }
                }
                // <-- end bundle                
            }

        }
        // END CM/RETURNS
		if($return == 0)
		{
		    $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'), true);

	        // if($inv == 0 || Request::input("keep_val") == "keep")
	        // {
		   		$invoice_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info,'',true);

                if($cm_customer_info != null && $cm_item_info != null)
                {
                    $cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $invoice_id, true);

                    $ref_name   = "credit_memo";
                    $ref_id     = $cm_id;
                    //arcy refill sir_inventory
                    foreach ($item_returns as $key_returns => $value_returns) 
                    {
                        $cm_data = Purchasing_inventory_system::insert_sir_inventory($sir_id, $value_returns, $ref_name, $ref_id);                  
                    }
                }

			   if($sir_id != null && $invoice_id != null)
			   {
					$insert_manual_invoice["sir_id"] = $sir_id;
                    $insert_manual_invoice["inv_id"] = $invoice_id;
                    $insert_manual_invoice["manual_invoice_date"] = Carbon::now();

					Tbl_manual_invoice::insert($insert_manual_invoice);

					foreach($_itemline as $keys => $item_line)
					{
						if($item_line)
						{
							// Purchasing_inventory_system::mark_as_sold($sir_id, Request::input('invline_item_id')[$keys],Request::input('invline_um')[$keys],Request::input('invline_qty')[$keys]);

                            $item["item_id"] = Request::input('invline_item_id')[$keys];
                            $item["qty"] = (UnitMeasurement::um_qty(Request::input('invline_um')[$keys]) * Request::input('invline_qty')[$keys]) * -1;

                            Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"invoice",$invoice_id);
						}
					}
					$data["status"] = "success-tablet";
			   }
			   else
			   {
					$data["status"] = "error";
					$data["status_message"] = "error";
			   }
			// }
			// else
			// {
			// 	$data["inv_id"] = Request::input("new_invoice_id");            
   //          	$data["status"] = "error-inv-no";
			// }
		}
		else
		{
			$data["status"] = "error";
			foreach ($item_name as $key_item => $value_item) 
			{
				$data["status_message"] .= "<li style='list-style:none'>The quantity of ".$value_item." is not enough.</li>";
			}
		}
	   return json_encode($data);
	}
    public function update_invoice_submit()
    {
        $invoice_id = Request::input("invoice_id");
        $sir_id = Request::input("sir_id");
        $data["status_message"] = "";

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');;
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_date']       = datepicker_input(Request::input('inv_date'));
        $invoice_info['invoice_due']        = datepicker_input(Request::input('inv_due_date'));
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['total_subtotal_price'] = Request::input('subtotal_price');
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');
        $total_info['total_overall_price']  = Request::input('overall_price');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $item_name = '';
        $return = 0;

        if($_itemline)
        {           
            foreach($_itemline as $key => $item_line)
            {
                if($item_line)
                {               
                    $item_info[$key]['item_service_date']  = "";
                    $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                    $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                    $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                    $item_info[$key]['quantity']           = str_replace(',', "",Request::input('invline_qty')[$key]);
                    $item_info[$key]['rate']               = str_replace(',', "", Request::input('invline_rate')[$key]);
                    $item_info[$key]['discount']           = Request::input('invline_discount')[$key];
                    $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                    $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                    $item_info[$key]['amount']             = str_replace(',', "", Request::input('invline_amount')[$key]);
                    $item_info[$key]['ref_name']           = "";
                    $item_info[$key]['ref_id']             = "";


                    $return += Purchasing_inventory_system::check_qty_sir($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key],$invoice_id,"tbl_customer_invoice_line");
                    if($return != 0)
                    {
                        $item_name[$key] = Tbl_item::where("item_id",Request::input("invline_item_id")[$key])->value("item_name");
                    }

                    // $um_info = UnitMeasurement::um_info(Request::input("invline_um")[$key]);
                    // $product_consume[$key]["quantity"] = (isset($um_info->unit_qty) ? $um_info->unit_qty : 1) * $item_info[$key]['quantity'];
                    // $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];
                }
            } 
        }
        //CREDIT MEMO / RETURNS
        $cm_customer_info[] = null;
        $item_returns = null; 
        $_cm_items = Request::input("cmline_item_id");
        $cm_item_info = null;
        if(Request::input("returns") != null && Purchasing_inventory_system::check(true) != 0)
        {
            $cm_customer_info["cm_customer_id"] = Request::input('inv_customer_id');
            $cm_customer_info["cm_customer_email"] = Request::input('inv_customer_email');
            $cm_customer_info["cm_date"] = datepicker_input(Request::input('inv_date'));
            $cm_customer_info["cm_message"] = "";
            $cm_customer_info["cm_memo"] = "";
            $cm_customer_info["cm_amount"] = str_replace(",","",Request::input("subtotal_price_returns"));

            foreach ($_cm_items as $keys => $values) 
            {  
                if($values != "")
                {      
                    $cm_item_info[$keys]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$keys]);
                    $cm_item_info[$keys]['item_id']            = Request::input('cmline_item_id')[$keys];
                    $cm_item_info[$keys]['item_description']   = Request::input('cmline_description')[$keys];
                    $cm_item_info[$keys]['um']                 = Request::input('cmline_um')[$keys];
                    $cm_item_info[$keys]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$keys]);
                    $cm_item_info[$keys]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$keys]);
                    $cm_item_info[$keys]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$keys]);
                   
                    $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keys]);
                    $item_returns[$keys]["qty"] = $um_qty * $cm_item_info[$keys]['quantity'];
                    $item_returns[$keys]["item_id"] = Request::input('cmline_item_id')[$keys];
                }   
            }            
        }
        if($_cm_items != null)
        {
             // --> for bundles
            foreach ($_cm_items as $keyitem_cm => $value_item) 
            {
                if($value_item != null)
                {
                    $item_bundle_info = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitem_cm])->where("item_type_id",4)->first();
                    if($item_bundle_info)
                    {
                        $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("cmline_item_id")[$keyitem_cm])->get();
                        foreach ($bundle as $key_bundle_cm => $value_bundle_cm) 
                        {
                            $qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keyitem_cm]);
                            $bundle_qty = UnitMeasurement::um_qty($value_bundle_cm->bundle_um_id);
                            $_bundle[$key_bundle_cm]['item_id'] = $value_bundle_cm->bundle_item_id;
                            $_bundle[$key_bundle_cm]['qty'] = (Request::input('cmline_qty')[$keyitem_cm] * $qty) * ($value_bundle_cm->bundle_qty * $bundle_qty);

                            array_push($item_returns, $_bundle[$key_bundle_cm]);
                        }
                    }                 
                }
            }
            if($item_returns != null)
            {
                foreach ($item_returns as $key_items_cm => $value_items_cm) 
                {
                     $i = null;
                     foreach ($_cm_items as $keyitemline_cm => $valueitemline)
                     {
                        $type = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitemline_cm])->value("item_type_id");
                        if($type == 4)
                        {
                            if(Request::input("cmline_item_id")[$keyitemline_cm] == $value_items_cm['item_id'])
                            {
                                $i = "true";
                            }                    
                        }
                     }
                    if($i != null)
                    {
                        unset($item_returns[$key_items_cm]);
                    }           
                }            
            }
            // <-- end bundle            
        }
        // END CM/RETURNS 
        if($return == 0)
        {

            $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'),true);

            // if($inv <= 1 || Request::input("keep_val") == "keep")
            // {
                // $inv_item = Tbl_customer_invoice_line::where("invline_inv_id",$invoice_id)->get();
                // // dd($inv_item);
                // foreach ($inv_item as $keys => $value) 
                // {                 
                //     Purchasing_inventory_system::return_qty($sir_id, $value->invline_item_id, $value->invline_um, $value->invline_qty); 
                // }

                $inv_id = Invoice::updateInvoice($invoice_id, $customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info,true);

                Tbl_sir_inventory::where("sir_inventory_ref_name","invoice")->where("sir_inventory_ref_id",$invoice_id)->delete();
                foreach($_itemline as $key => $item_line)
                {
                    if($item_line)
                    {
                        $item["item_id"] = Request::input('invline_item_id')[$key];
                        $item["qty"] = (UnitMeasurement::um_qty(Request::input('invline_um')[$key]) * Request::input('invline_qty')[$key]) * -1;

                        Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"invoice",$invoice_id);
                    }
                }

                if($cm_customer_info != null && $cm_item_info != null)
                {
                    $credit_memo_id = Tbl_customer_invoice::where("inv_id",$invoice_id)->value("credit_memo_id");
                    if($credit_memo_id != null)
                    {
                        $cm_id = CreditMemo::updateCM($credit_memo_id, $cm_customer_info, $cm_item_info);
                        $ref_id = $credit_memo_id;
                        $ref_name = "credit_memo";
                        Tbl_sir_inventory::where("sir_inventory_ref_name","credit_memo_id")->where("sir_inventory_ref_id",$credit_memo_id)->delete();
                        //arcy refill sir_inventory
                        foreach ($item_returns as $key_returns => $value_returns) 
                        {
                            $cm_data = Purchasing_inventory_system::insert_sir_inventory($sir_id, $value_returns, $ref_name, $ref_id);
                        }
                    }
                    else
                    {
                        $cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $invoice_id, true);

                        $ref_name   = "credit_memo";
                        $ref_id     = $cm_id;
                        //arcy refill sir_inventory
                        foreach ($item_returns as $key_returns => $value_returns) 
                        {
                            $cm_data = Purchasing_inventory_system::insert_sir_inventory($sir_id, $value_returns, $ref_name, $ref_id);
                        }

                    }
                }
                $data["status"] = "success-tablet";
            // }
            // else
            // {
            //     $data["inv_id"] = Request::input("new_invoice_id");            
            //     $data["status"] = "error-inv-no";
            // }
        }
        else
        {
            $data["status"] = "error";
            foreach ($item_name as $key_item => $value_item) 
            {
                $data["status_message"] .= "<li style='list-style:none'>The quantity of ".$value_item." is not enough.</li>";
            }
        }
        return json_encode($data);
    }
	public function view_invoice_pdf($inv_id)
    {
        $data["invoice"] = Tbl_customer_invoice::customer()->where("inv_id",$inv_id)->first();

        $data["transaction_type"] = "INVOICE";
        if(Tbl_customer_invoice::where("inv_id",$inv_id)->value("is_sales_receipt") != 0)
        {
            $data["transaction_type"] = "Sales Receipt";            
        }
        $data["invoice_item"] = Tbl_customer_invoice_line::invoice_item()->where("invline_inv_id",$inv_id)->get();
        foreach($data["invoice_item"] as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->invline_um);

            $total_qty = $value->invline_qty * $qty;
            $data["invoice_item"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->invline_um);
        }
        $data["cm"] = null;
        $data["_cmline"] = null;
        if($data["invoice"] != null)
        {
            $data["cm"] = Tbl_credit_memo::where("cm_id",$data["invoice"]->credit_memo_id)->first();
            $data["_cmline"] = Tbl_credit_memo_line::cm_item()->where("cmline_cm_id",$data["invoice"]->credit_memo_id)->get();

            foreach ($data["_cmline"] as $keys => $values)
            {
                $qtys = UnitMeasurement::um_qty($values->cmline_um);

                $total_qtys = $values->cmline_qty * $qtys;
                $data["_cmline"][$keys]->cm_qty = UnitMeasurement::um_view($total_qtys,$values->item_measurement_id,$values->cmline_um);
            }
        }
          $pdf = view('member.customer_invoice.invoice_pdf', $data);
          return Pdf_global::show_pdf($pdf);
    }
	public function view_invoices_view($id)
	{
		$data["invoice_id"] = $id;
        $inv_data = Tbl_customer_invoice::where("inv_id",$id)->where("is_sales_receipt",1)->first();
        $data["transaction_type"] = "Credit Sales";
        if($inv_data)
        {
            $data["transaction_type"] = "Cash Sales";
        }
		$data["action_load"] = "/tablet/view_invoice_pdf";
        return view("member.customer_invoice.invoice_view",$data);
	}

	public function login()
	{
		if(Session::get("sales_agent"))
		{
			return Redirect::to("/tablet/dashboard");
		}
		else
		{
			return view("tablet.login");
		}
	}
	public function login_submit()
	{
		$username = Request::input("username");
		$password = Request::input("password");

		$data["status"] = null;
		$data["status_message"] = null;

		$data["account"] = Tbl_employee::position()->where("username",$username)->where("position_code","sales_agent")->first();

		if($data["account"] != null)
		{
			if(Crypt::decrypt($data["account"]->password) == $password)
			{
				$data["status"] = "success-login";
				Session::put("sales_agent",$data["account"]);
			}
			else
			{
				$data["status"] = "error";
				$data["status_message"] = "The Username / Password is Incorrect";
			}
		}
		else
		{
			$data["status"] = "error";
			$data["status_message"] = "The Username / Password is Incorrect";
		}

		return json_encode($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sync_export()
	{
		$data["status"] = "success";

		$all = Tbl_manual_invoice::sir()->where("sales_agent_id",$this->get_user()->employee_id)->customer_invoice() 
										->where("tbl_manual_invoice.is_sync",0)
										->get();

		foreach ($all as $key => $value) 
		{
			$customer_info                      = [];
			$customer_info['customer_id']       = $value->inv_customer_id;
			$customer_info['customer_email']    = $value->inv_customer_email;

			$invoice_info                       = [];
			$invoice_info['invoice_terms_id']   = $value->inv_terms_id;
			$invoice_info['invoice_date']       = $value->inv_date;
			$invoice_info['invoice_due']        = $value->inv_due_date;
			$invoice_info['billing_address']    = $value->inv_customer_billing_address;

			$invoice_other_info                 = [];
			$invoice_other_info['invoice_msg']  = $value->inv_message;
			$invoice_other_info['invoice_memo'] = $value->inv_memo;

			$total_info                         = [];
			$total_info['total_subtotal_price'] = $value->inv_subtotal_price;
			$total_info['ewt']                  = $value->ewt;
			$total_info['total_discount_type']  = $value->inv_discount_type;
			$total_info['total_discount_value'] = $value->inv_discount_value;
			$total_info['taxable']              = $value->taxable;
			$total_info['total_overall_price']  = $value->inv_overall_price;

			$item_info                          = [];
			$_itemline                          = Tbl_customer_invoice_line::where("invline_inv_id",$value->inv_id)->get();

			$return = 0;
	        foreach($_itemline as $keys => $item_line)
	        {
	            if($item_line)
	            {
	                $item_info[$keys]['item_service_date']  = $item_line->invline_service_date;
	                $item_info[$keys]['item_id']            = $item_line->invline_item_id;
	                $item_info[$keys]['item_description']   = $item_line->invline_description;
	                $item_info[$keys]['um']                 = $item_line->invline_um;
	                $item_info[$keys]['quantity']           = $item_line->invline_qty;
	                $item_info[$keys]['rate']               = $item_line->invline_rate;
	                $item_info[$keys]['discount']           = $item_line->invline_discount;
	                $item_info[$keys]['discount_remark']    = $item_line->invline_discount_remark;
	                $item_info[$keys]['taxable']            = $item_line->taxable;
	                $item_info[$keys]['amount']             = $item_line->invline_amount;
	            }
	        }
           $invoice_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info,'', true);

           $update["inv_id"] = $invoice_id;
           $update["is_sync"] = 1;
           Tbl_manual_invoice::where("manual_invoice_id",$value->manual_invoice_id)->update($update);
           Tbl_customer_invoice::where("inv_id",$value->inv_id)->update($update);
		}

		return json_encode($data);
	}

    public function sales_receipt()
    {
        $sir_id = Request::input("sir_id");
        $data["sir_id"] = $sir_id;
        $data["page"]       = "Customer Sales Receipt";
        $data["pis"]        = Purchasing_inventory_system::check(true);
        $data["_customer"]  = Customer::getAllCustomer(true);
        $data["_terms"]     = Tbl_terms::where("archived", 0)->where("terms_shop_id", $this->getShopId())->get();
        $data['_item']      = Item::get_all_item_sir($sir_id, true);
        $data['_cm_item']   = Item::get_returnable_item(true);
        $data['_um']        = UnitMeasurement::load_um_multi(true);
        $data["action"]     = "/tablet/sales_receipt/create_submit";
        $data["new_inv_id"] = Transaction::get_last_number("tbl_customer_invoice","new_inv_id","inv_shop_id"); 
        $data["c_id"] = Request::input("customer_id");
        $id = Request::input('id');
        if($id)
        {
            $data["inv"]            = Tbl_customer_invoice::where("inv_id", $id)->first();
            
            $data["_invline"]       = Tbl_customer_invoice_line::invoice_item()->um()->where("invline_inv_id", $id)->get();
            $data["_cmline"]       = Tbl_customer_invoice::returns_item()->where("inv_id", $id)->get();
            $data["action"]         = "/tablet/sales_receipt/update_submit";

            // dd($data["inv"]);

            $sir = Tbl_manual_invoice::where("inv_id",$id)->first();
            if($sir)
            {
                $data["sir_id"] = $sir->sir_id;
                $data["action"] = "/tablet/sales_receipt/update_submit";
                $data['_item'] = Item::get_all_item_sir($sir->sir_id, true);
            }
        }

        return view('tablet.agent_transaction.sales_receipt.sales_receipt_transaction', $data);
    }
    public function sales_receipt_list()
    {
        $data["employee_name"] = $this->get_user()->first_name." ".$this->get_user()->middle_name." ".$this->get_user()->last_name;
        $data["employee_position"] = $this->get_user()->position_name;
        $data["employee_id"] = $this->get_user()->employee_id;

        if(Session::get("sir_id") != null)
        {
            $data["_invoices"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",Session::get("sir_id"))->orderBy("tbl_customer_invoice.inv_id","DESC")->where("is_sales_receipt",1)->get();
            foreach ($data["_invoices"] as $key => $value) 
            {
                $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
                if($cm != null)
                {
                  $data["_invoices"][$key]->inv_overall_price = $value->inv_overall_price - $cm->cm_amount;  
                }
            }
        }
        return view("tablet.agent.sales_receipt",$data);
    }
    public function create_sales_receipt_submit()
    {
        $sir_id = Request::input("sir_id");
        $data["status"] = "";
        $data["status_message"] = "";

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');;
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_date']       = datepicker_input(Request::input('inv_date'));
        $invoice_info['invoice_due']        = datepicker_input(Request::input('inv_due_date'));
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['total_subtotal_price'] = str_replace(',', "", Request::input('subtotal_price'));
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');
        $total_info['total_overall_price']  = Request::input('overall_price');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $return = 0;
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $item_info[$key]['item_service_date']  = "";
                $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('invline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('invline_rate')[$key]);
                $item_info[$key]['discount']           = Request::input('invline_discount')[$key];
                $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('invline_amount')[$key]);
                $item_info[$key]['ref_name']           = "";
                $item_info[$key]['ref_id']             = "";

                $return += Purchasing_inventory_system::check_qty_sir($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key],0,"tbl_customer_invoice_line");
                if($return != 0)
                {
                    $item_name[$key] = Tbl_item::where("item_id",Request::input("invline_item_id")[$key])->value("item_name");
                }
            }
        }

        //CM returns
        $cm_customer_info[] = null;
        $cm_item_info = null;
        $item_returns = null;    
        if(Request::input("returns") != null && Purchasing_inventory_system::check(true) != 0)
        {
            $cm_customer_info["cm_customer_id"] = Request::input('inv_customer_id');
            $cm_customer_info["cm_customer_email"] = Request::input('inv_customer_email');
            $cm_customer_info["cm_date"] = datepicker_input(Request::input('inv_date'));
            $cm_customer_info["cm_message"] = "";
            $cm_customer_info["cm_memo"] = "";
            $cm_customer_info["cm_amount"] = str_replace(",","",Request::input("subtotal_price_returns"));

            $cm_item_info[] = null;
            $_cm_items = Request::input("cmline_item_id");
            if($_cm_items != null)
            {
                foreach ($_cm_items as $keys => $values) 
                { 
                    if($values != null)
                    {
                        $cm_item_info[$keys]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$keys]);
                        $cm_item_info[$keys]['item_id']            = Request::input('cmline_item_id')[$keys];
                        $cm_item_info[$keys]['item_description']   = Request::input('cmline_description')[$keys];
                        $cm_item_info[$keys]['um']                 = Request::input('cmline_um')[$keys];
                        $cm_item_info[$keys]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$keys]);
                        $cm_item_info[$keys]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$keys]);
                        $cm_item_info[$keys]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$keys]);
                
                        $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keys]);
                        $item_returns[$keys]["qty"] = $um_qty * $cm_item_info[$keys]['quantity'];
                        $item_returns[$keys]["item_id"] = Request::input('cmline_item_id')[$keys];                    
                    }          
                } 
                // --> for bundles
                foreach ($_cm_items as $keyitem_cm => $value_item) 
                {
                    if($value_item != null)
                    {
                        $item_bundle_info = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitem_cm])->where("item_type_id",4)->first();
                        if($item_bundle_info)
                        {
                            $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("cmline_item_id")[$keyitem_cm])->get();
                            foreach ($bundle as $key_bundle_cm => $value_bundle_cm) 
                            {
                                $qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keyitem_cm]);
                                $bundle_qty = UnitMeasurement::um_qty($value_bundle_cm->bundle_um_id);
                                $_bundle[$key_bundle_cm]['item_id'] = $value_bundle_cm->bundle_item_id;
                                $_bundle[$key_bundle_cm]['qty'] = (Request::input('cmline_qty')[$keyitem_cm] * $qty) * ($value_bundle_cm->bundle_qty * $bundle_qty);

                                array_push($item_returns, $_bundle[$key_bundle_cm]);
                            }
                        }                 
                    }
                }
                if($item_returns != null)
                {
                    foreach ($item_returns as $key_items_cm => $value_items_cm) 
                    {
                         $i = null;
                         foreach ($_cm_items as $keyitemline_cm => $valueitemline)
                         {
                            $type = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitemline_cm])->value("item_type_id");
                            if($type == 4)
                            {
                                if(Request::input("cmline_item_id")[$keyitemline_cm] == $value_items_cm['item_id'])
                                {
                                    $i = "true";
                                }                    
                            }
                         }
                        if($i != null)
                        {
                            unset($item_returns[$key_items_cm]);
                        }           
                    }
                }
                // <-- end bundle                
            }

        }
        // END CM/RETURNS
        if($return == 0)
        {
            $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'), true);

            // if($inv == 0 || Request::input("keep_val") == "keep")
            // {
                $invoice_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info,"sales_receipt", true);

                /* SUBTOTAL */
                $subtotal_price = collect($item_info)->sum('amount');

                /* DISCOUNT */
                $discount = $total_info['total_discount_value'];
                if($total_info['total_discount_type'] == 'percent') $discount = (convertToNumber($total_info['total_discount_value']) / 100) * $subtotal_price;

                /* TAX */
                $tax = (collect($item_info)->where('taxable', '1')->sum('amount')) * 0.12;

                /* EWT */
                $ewt = $subtotal_price*convertToNumber($total_info['ewt']);

                /* OVERALL TOTAL */
                $overall_price  = convertToNumber($subtotal_price) - $ewt - $discount + $tax;

                $update['inv_payment_applied']        = $overall_price;
                $update['is_sales_receipt']           = 1;  
                Tbl_customer_invoice::where("inv_id",$invoice_id)->update($update);

                // $rcv_payment_id = Invoice::postSales_receipt_payment($customer_info,$invoice_info,$overall_price,$invoice_id);

                if($cm_customer_info != null && $cm_item_info != null)
                {
                    $cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $invoice_id, true);

                    $ref_name   = "credit_memo";
                    $ref_id     = $cm_id;
                    //arcy refill sir_inventory
                    foreach ($item_returns as $key_returns => $value_returns) 
                    {
                        $cm_data = Purchasing_inventory_system::insert_sir_inventory($sir_id, $value_returns, $ref_name, $ref_id);                        
                    }
                }

               if($sir_id != null && $invoice_id != null)
               {
                    $insert_manual_invoice["sir_id"] = $sir_id;
                    $insert_manual_invoice["inv_id"] = $invoice_id;
                    $insert_manual_invoice["manual_invoice_date"] = Carbon::now();

                    Tbl_manual_invoice::insert($insert_manual_invoice);

                    // $insert_manual_rcv_payment["agent_id"] = $this->get_user()->employee_id;
                    // $insert_manual_rcv_payment["rp_id"] = $rcv_payment_id;
                    // $insert_manual_rcv_payment["sir_id"] = $sir_id;
                    // $insert_manual_rcv_payment["rp_date"] = Carbon::now();

                    // Tbl_manual_receive_payment::insert($insert_manual_rcv_payment);

                    foreach($_itemline as $keys => $item_line)
                    {
                        if($item_line)
                        {
                            // Purchasing_inventory_system::mark_as_sold($sir_id, Request::input('invline_item_id')[$keys],Request::input('invline_um')[$keys],Request::input('invline_qty')[$keys]);

                            $item["item_id"] = Request::input('invline_item_id')[$keys];
                            $item["qty"] = (UnitMeasurement::um_qty(Request::input('invline_um')[$keys]) * Request::input('invline_qty')[$keys]) * -1;

                            Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"invoice",$invoice_id);
                        }
                    }
                    $data["status"] = "success-tablet-sr";
               }
               else
               {
                    $data["status"] = "error";
                    $data["status_message"] = "error";
               }
            // }
            // else
            // {
            //     $data["inv_id"] = Request::input("new_invoice_id");            
            //     $data["status"] = "error-inv-no";
            // }
        }
        else
        {
            $data["status"] = "error";
            foreach ($item_name as $key_item => $value_item) 
            {
                $data["status_message"] .= "<li style='list-style:none'>The quantity of ".$value_item." is not enough.</li>";
            }
        }
       return json_encode($data);
    }
    public function update_sales_receipt_submit()
    {
        $invoice_id = Request::input("invoice_id");
        $sir_id = Request::input("sir_id");
        $data["status_message"] = "";

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');;
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_date']       = datepicker_input(Request::input('inv_date'));
        $invoice_info['invoice_due']        = datepicker_input(Request::input('inv_due_date'));
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['total_subtotal_price'] = Request::input('subtotal_price');
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');
        $total_info['total_overall_price']  = Request::input('overall_price');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $item_name = '';
        $return = 0;

        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {               
                $item_info[$key]['item_service_date']  = Request::input('invline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('invline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('invline_rate')[$key]);
                $item_info[$key]['discount']           = Request::input('invline_discount')[$key];
                $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('invline_amount')[$key]);
                $item_info[$key]['ref_name']           = Request::input('invline_ref_name')[$key];
                $item_info[$key]['ref_id']             = Request::input('invline_ref_id')[$key];


                $return += Purchasing_inventory_system::check_qty_sir($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key],$invoice_id,"tbl_customer_invoice_line");
                if($return != 0)
                {
                    $item_name[$key] = Tbl_item::where("item_id",Request::input("invline_item_id")[$key])->value("item_name");
                }

                // $um_info = UnitMeasurement::um_info(Request::input("invline_um")[$key]);
                // $product_consume[$key]["quantity"] = (isset($um_info->unit_qty) ? $um_info->unit_qty : 1) * $item_info[$key]['quantity'];
                // $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];
            }
        }
        //CREDIT MEMO / RETURNS
        $cm_customer_info[] = null;
        $item_returns = null; 
        $_cm_items = Request::input("cmline_item_id");
        $cm_item_info = null;
        if(Request::input("returns") != null && Purchasing_inventory_system::check(true) != 0)
        {
            $cm_customer_info["cm_customer_id"] = Request::input('inv_customer_id');
            $cm_customer_info["cm_customer_email"] = Request::input('inv_customer_email');
            $cm_customer_info["cm_date"] = datepicker_input(Request::input('inv_date'));
            $cm_customer_info["cm_message"] = "";
            $cm_customer_info["cm_memo"] = "";
            $cm_customer_info["cm_amount"] = str_replace(",","",Request::input("subtotal_price_returns"));

            foreach ($_cm_items as $keys => $values) 
            {  
                if($values != "")
                {      
                    $cm_item_info[$keys]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$keys]);
                    $cm_item_info[$keys]['item_id']            = Request::input('cmline_item_id')[$keys];
                    $cm_item_info[$keys]['item_description']   = Request::input('cmline_description')[$keys];
                    $cm_item_info[$keys]['um']                 = Request::input('cmline_um')[$keys];
                    $cm_item_info[$keys]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$keys]);
                    $cm_item_info[$keys]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$keys]);
                    $cm_item_info[$keys]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$keys]);
                   
                    $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keys]);
                    $item_returns[$keys]["qty"] = $um_qty * $cm_item_info[$keys]['quantity'];
                    $item_returns[$keys]["item_id"] = Request::input('cmline_item_id')[$keys];
                }   
            }            
        }
        if($_cm_items != null)
        {
             // --> for bundles
            foreach ($_cm_items as $keyitem_cm => $value_item) 
            {
                if($value_item != null)
                {
                    $item_bundle_info = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitem_cm])->where("item_type_id",4)->first();
                    if($item_bundle_info)
                    {
                        $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("cmline_item_id")[$keyitem_cm])->get();
                        foreach ($bundle as $key_bundle_cm => $value_bundle_cm) 
                        {
                            $qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keyitem_cm]);
                            $bundle_qty = UnitMeasurement::um_qty($value_bundle_cm->bundle_um_id);
                            $_bundle[$key_bundle_cm]['item_id'] = $value_bundle_cm->bundle_item_id;
                            $_bundle[$key_bundle_cm]['qty'] = (Request::input('cmline_qty')[$keyitem_cm] * $qty) * ($value_bundle_cm->bundle_qty * $bundle_qty);

                            array_push($item_returns, $_bundle[$key_bundle_cm]);
                        }
                    }                 
                }
            }
            if($item_returns != null)
            {
                foreach ($item_returns as $key_items_cm => $value_items_cm) 
                {
                     $i = null;
                     foreach ($_cm_items as $keyitemline_cm => $valueitemline)
                     {
                        $type = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitemline_cm])->value("item_type_id");
                        if($type == 4)
                        {
                            if(Request::input("cmline_item_id")[$keyitemline_cm] == $value_items_cm['item_id'])
                            {
                                $i = "true";
                            }                    
                        }
                     }
                    if($i != null)
                    {
                        unset($item_returns[$key_items_cm]);
                    }           
                }            
            }
            // <-- end bundle            
        }
        // END CM/RETURNS 
        if($return == 0)
        {

            $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'),true);

            // if($inv <= 1 || Request::input("keep_val") == "keep")
            // {
                // $inv_item = Tbl_customer_invoice_line::where("invline_inv_id",$invoice_id)->get();
                // // dd($inv_item);
                // foreach ($inv_item as $keys => $value) 
                // {                 
                //     Purchasing_inventory_system::return_qty($sir_id, $value->invline_item_id, $value->invline_um, $value->invline_qty); 
                // }

                $inv_id = Invoice::updateInvoice($invoice_id, $customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info, "sales_receipt");

                 /* SUBTOTAL */
                $subtotal_price = collect($item_info)->sum('amount');

                /* DISCOUNT */
                $discount = $total_info['total_discount_value'];
                if($total_info['total_discount_type'] == 'percent') $discount = (convertToNumber($total_info['total_discount_value']) / 100) * $subtotal_price;

                /* TAX */
                $tax = (collect($item_info)->where('taxable', '1')->sum('amount')) * 0.12;

                /* EWT */
                $ewt = $subtotal_price*convertToNumber($total_info['ewt']);

                /* OVERALL TOTAL */
                $overall_price  = convertToNumber($subtotal_price) - $ewt - $discount + $tax;

                $update['inv_payment_applied']        = $overall_price;
                Tbl_customer_invoice::where("inv_id",$invoice_id)->update($update);

                // Invoice::update_rcv_payment("invoice",$invoice_id,$overall_price);

                Tbl_sir_inventory::where("sir_inventory_ref_name","invoice")->where("sir_inventory_ref_id",$invoice_id)->delete();
                foreach($_itemline as $key => $item_line)
                {
                    if($item_line)
                    {
                        $item["item_id"] = Request::input('invline_item_id')[$key];
                        $item["qty"] = (UnitMeasurement::um_qty(Request::input('invline_um')[$key]) * Request::input('invline_qty')[$key]) * -1;

                        Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"invoice",$invoice_id);
                    }
                }

                if($cm_customer_info != null && $cm_item_info != null)
                {
                    $credit_memo_id = Tbl_customer_invoice::where("inv_id",$invoice_id)->value("credit_memo_id");
                    if($credit_memo_id != null)
                    {
                        $cm_id = CreditMemo::updateCM($credit_memo_id, $cm_customer_info, $cm_item_info);
                        $ref_id = $credit_memo_id;
                        $ref_name = "credit_memo";
                        Tbl_sir_inventory::where("sir_inventory_ref_name","credit_memo_id")->where("sir_inventory_ref_id",$credit_memo_id)->delete();
                        //arcy refill sir_inventory
                        foreach ($item_returns as $key_returns => $value_returns) 
                        {
                            $cm_data = Purchasing_inventory_system::insert_sir_inventory($sir_id, $value_returns, $ref_name, $ref_id);
                        }
                    }
                    else
                    {
                        $cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $invoice_id, true);

                        $ref_name   = "credit_memo";
                        $ref_id     = $cm_id;
                        //arcy refill sir_inventory
                        foreach ($item_returns as $key_returns => $value_returns) 
                        {
                            $cm_data = Purchasing_inventory_system::insert_sir_inventory($sir_id, $value_returns, $ref_name, $ref_id);
                        }

                    }
                }
                $data["status"] = "success-tablet-sr";
            // }
            // else
            // {
            //     $data["inv_id"] = Request::input("new_invoice_id");            
            //     $data["status"] = "error-inv-no";
            // }
        }
        else
        {
            $data["status"] = "error";
            foreach ($item_name as $key_item => $value_item) 
            {
                $data["status_message"] .= "<li style='list-style:none'>The quantity of ".$value_item." is not enough.</li>";
            }
        }
        return json_encode($data);
    }
}
