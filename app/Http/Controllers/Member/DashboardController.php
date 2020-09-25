<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
// use App\Http\Middleware\RandomColor;
use App\Globals\Accounting;
use App\Globals\Category;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Invoice;
use App\Globals\Billing;
use App\Globals\Report;
use App\Globals\Warehouse2;
use App\Globals\Purchase_Order;
use App\Globals\RequisitionSlip;
use App\Globals\TransactionPayBills;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionSalesOrder;
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionPurchaseOrder;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Migo;
use App\Globals\Pdf_global;

use App\Models\Tbl_User;
use App\Models\Tbl_customer;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_journal_entry_line;

use Carbon\Carbon;
use Request;
use DB;
use Session;

class DashboardController extends Member
{
    public function migo_index()
    {
    }

    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public function index()
    {
        $period         = Request::input("period");
        $period         = "days_ago";
        $date["days"]   = "365";
        $from           = Report::checkDatePeriod($period, $date)['start_date'];
        $to             = Report::checkDatePeriod($period, $date)['end_date'];

        /* INVOICE DATA */
        $data["open_invoice"]   = Invoice::invoiceStatus($from, $to)["open"];
        $data["overdue_invoice"]= Invoice::invoiceStatus($from, $to)["overdue"];

        $date["days"]           = "30";
        $from                   = Report::checkDatePeriod($period, $date)['start_date'];
        $to                     = Report::checkDatePeriod($period, $date)['end_date'];
        $data["paid_invoice"]   = Invoice::invoiceStatus($from, $to)["paid"];


        /* EXPENSE DATA */
        $data["_expenses"]   = Tbl_journal_entry_line::account()->journal()->totalAmount()
                            ->where("je_shop_id", $this->getShopId())
                            ->whereIn("chart_type_name", ['Expense', 'Other Expense', 'Cost of Good Sold'])
                            ->whereRaw("DATE(je_entry_date) >= '$from'")
                            ->whereRaw("DATE(je_entry_date) <= '$to'")
                            ->get();

        $data['position_name'] = $this->user_info->position_name;

        $data["expense_name"]   = [];
        $data["expense_color"]  = [];
        $data["expense_value"]  = [];
        foreach($data["_expenses"] as $key=>$expense)
        {
            $data["_expenses"][$key]->percentage = currency('',((@($expense->amount / collect($data["_expenses"])->sum('amount'))) * 100))." %";
            array_push($data["expense_name"], $expense->account_name);
            array_push($data["expense_value"], currency('',((@($expense->amount / collect($data["_expenses"])->sum('amount'))) * 100)));
            array_push($data["expense_color"], $this->random_color());
        }
        $data["expense_name"]   = json_encode($data["expense_name"]);
        $data["expense_value"]  = json_encode($data["expense_value"]);
        $data["expense_color"]  = json_encode($data["expense_color"]);

        /* INCOME DATA */
        $data["_income"]     = Tbl_journal_entry_line::account()->journal()
                                                                ->selectRaw("*")
                                                                ->amount()
                                                                ->where("je_shop_id", $this->getShopId())
                                                                ->whereIn("chart_type_name", ['Income', 'Other Income'])
                                                                ->whereRaw("DATE(je_entry_date) >= '$from'")
                                                                ->whereRaw("DATE(je_entry_date) <= '$to'")
                                                                ->groupBy(DB::raw("DATE(je_entry_date)"))
                                                                ->get();

        $data["income_date"]    = [];
        $data["income_value"]   = [];
        foreach($data["_income"] as $key=>$income)
        {
            array_push($data["income_date"], dateFormat($income->je_entry_date));
            array_push($data["income_value"], $income->amount);
        }
        $data["income_date"]    = json_encode($data["income_date"]);
        $data["income_value"]   = json_encode($data["income_value"]);

        /* BANK DATA */
        $data["_bank"]      = Tbl_journal_entry_line::account()->journal()->totalAmount()
                            ->where("je_shop_id", $this->getShopId())
                            ->whereIn("chart_type_name", ['Bank'])
                            ->get();

        Warehouse2::check_new_item_reorderpoint($this->user_info->shop_id, Warehouse2::get_current_warehouse($this->user_info->shop_id));
        // dd(Session::get("show_reorder"));
        $data['show_reorder'] = Session::get("show_reorder");

        $data['po_amount'] = currency('PHP ',TransactionPurchaseOrder::get_open_po_total_amount($this->user_info->shop_id)); 
        $data['count_po']  = number_format(TransactionPurchaseOrder::count_open_po($this->user_info->shop_id));

        $data['pr_amount'] = currency('PHP ',RequisitionSlip::get_open_pr_total_amount($this->user_info->shop_id)); 
        $data['count_pr']  = number_format(RequisitionSlip::count_open_pr($this->user_info->shop_id));

        $data['so_amount'] = currency('PHP ',TransactionSalesOrder::get_open_so_total_amount($this->user_info->shop_id)); 
        $data['count_so']  = number_format(TransactionSalesOrder::count_open_so($this->user_info->shop_id));

        $data['ap_amount'] = currency('PHP ',TransactionPayBills::get_ap_amount($this->user_info->shop_id));
        $data['count_ap']  =   number_format(TransactionPayBills::count_ap($this->user_info->shop_id)); 

        $data['ar_amount'] = currency('PHP ',TransactionSalesInvoice::get_open_ar_total_amount($this->user_info->shop_id));
        $data['count_ar']  =   number_format(TransactionSalesInvoice::count_open_ar($this->user_info->shop_id)); 
        
        $check = AccountingTransaction::settings($this->user_info->shop_id, 'allow_transaction');
        if($check == 1)
        {
            $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

            $data['ap_amount'] = currency('PHP ',TransactionPayBills::get_eb_amount_perwh($this->user_info->shop_id, $warehouse_id));
            $data['count_ap']  = number_format(TransactionPayBills::count_eb_perwh($this->user_info->shop_id, $warehouse_id));

            /*$receive_amount = TransactionPayBills::get_receive_amount_perwh($this->user_info->shop_id, $warehouse_id);
            $count_receive  =   TransactionPayBills::count_receive_perwh($this->user_info->shop_id, $warehouse_id); 

            $data['ap_amount'] = currency('PHP ', $eb_amount + $receive_amount);
            $data['count_ap'] = number_format($count_eb + $count_receive);*/

            $data['po_amount'] = currency('PHP ',TransactionPurchaseOrder::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id)); 
            $data['count_po']  = number_format(TransactionPurchaseOrder::count_perwh($this->user_info->shop_id, $warehouse_id));

            $data['pr_amount'] = currency('PHP ',RequisitionSlip::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id)); 
            $data['count_pr']  = number_format(RequisitionSlip::count_perwh($this->user_info->shop_id, $warehouse_id));

            $data['so_amount'] = currency('PHP ',TransactionSalesOrder::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id)); 
            $data['count_so']  = number_format(TransactionSalesOrder::count_perwh($this->user_info->shop_id, $warehouse_id));

            $data['ar_amount'] = currency('PHP ',TransactionSalesInvoice::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id));
            $data['count_ar']  =   number_format(TransactionSalesInvoice::count_perwh($this->user_info->shop_id, $warehouse_id)); 
        }
        $data['purchase_requisition'] = AccountingTransaction::settings($this->user_info->shop_id,"purchase_requisition");
        $data['auto_load_dashboard'] = AccountingTransaction::settings($this->user_info->shop_id, 'auto_load_dashboard');
        $data['auto_load_reorder_print'] = AccountingTransaction::settings($this->user_info->shop_id, 'auto_load_reorder_print');
        $data['auto_load_reorder_hour'] = AccountingTransaction::settings($this->user_info->shop_id, 'auto_load_reorder_hour');
        $data['auto_load_reorder_min'] = AccountingTransaction::settings($this->user_info->shop_id, 'auto_load_reorder_min');
        
        if(AccountingTransaction::settings($this->user_info->shop_id, "migo_customization"))
        {
            $selected_yr = Request::input("selected_yr");
            $selected_mo = Request::input("selected_mo");
            $data['year_now'] = $selected_yr ? $selected_yr : date("Y");
            $data['month_now'] =  $selected_mo ? $selected_mo : date("m");
            $data['_migo'] = Migo::dashboard($this->user_info->shop_id, $data['month_now'], $data['year_now']);
            $data['_month'] = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $data['_year'] = [];
            //year to start with
            $startdate = 2017;
            $enddate = date("Y")+25;         
            $years = range ($startdate,$enddate);
            foreach($years as $key => $year)
            {
              $data['_year'][$key] = $year;
            }

            $selected_year = Request::input("selected_year") ? Request::input("selected_year") : date('Y');
            $data["income_date_migo"]    = [];
            $data["income_value_migo"]   = [];
            $period         = "custom";
            $data['selected_year'] = $selected_year;
            $arr[] = null;
            foreach ($data['_month'] as $key => $value) 
            {
                array_push($data['income_date_migo'], $value);
                $date['start'] = ($key+1)."/1/".$selected_year;
                $date['end'] = ($key+1)."/31/".$selected_year;
                $mfrom                   = Report::checkDatePeriod($period, $date)['start_date'] . " 00:00:00";
                $mto                     = Report::checkDatePeriod($period, $date)['end_date'] . " 23:59:59";
                $amount = TransactionSalesInvoice::getSales($this->user_info->shop_id, $mfrom, $mto);
                $amount = $amount ? $amount : 0;
                array_push($data['income_value_migo'], $amount);
            }
  
            $data["income_date_migo"]    = json_encode($data["income_date_migo"]);
            $data["income_value_migo"]   = json_encode($data["income_value_migo"]);
            return view('member.dashboard.migo_dashboard', $data);
        }
        else
        {
            return view('member.dashboard.new_dashboard', $data);            
        }
    }
    
    public function load_count_transaction()
    {
        $data['po_amount'] = currency('PHP ',TransactionPurchaseOrder::get_open_po_total_amount($this->user_info->shop_id)); 
        $data['count_po']  = number_format(TransactionPurchaseOrder::count_open_po($this->user_info->shop_id));

        $data['pr_amount'] = currency('PHP ',RequisitionSlip::get_open_pr_total_amount($this->user_info->shop_id)); 
        $data['count_pr']  = number_format(RequisitionSlip::count_open_pr($this->user_info->shop_id));

        $data['so_amount'] = currency('PHP ',TransactionSalesOrder::get_open_so_total_amount($this->user_info->shop_id)); 
        $data['count_so']  = number_format(TransactionSalesOrder::count_open_so($this->user_info->shop_id));

        $data['ap_amount'] = currency('PHP ',TransactionPayBills::get_ap_amount($this->user_info->shop_id));
        $data['count_ap']  =   number_format(TransactionPayBills::count_ap($this->user_info->shop_id)); 
        
        $data['ar_amount'] = currency('PHP ',TransactionSalesInvoice::get_open_ar_total_amount($this->user_info->shop_id));
        $data['count_ar']  =   number_format(TransactionSalesInvoice::count_open_ar($this->user_info->shop_id));

        $check = AccountingTransaction::settings($this->user_info->shop_id, 'allow_transaction');
        if($check == 1)
        {
            $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

            $data['ap_amount'] = TransactionPayBills::get_eb_amount_perwh($this->user_info->shop_id, $warehouse_id);
            $data['count_ap']  = TransactionPayBills::count_eb_perwh($this->user_info->shop_id, $warehouse_id);

            /*$receive_amount = TransactionPayBills::get_receive_amount_perwh($this->user_info->shop_id, $warehouse_id);
            $count_receive  =   TransactionPayBills::count_receive_perwh($this->user_info->shop_id, $warehouse_id); 

            $data['ap_amount'] = currency('PHP ', $eb_amount + $receive_amount);
            $data['count_ap'] = number_format($count_eb + $count_receive);*/

            $data['po_amount'] = currency('PHP ',TransactionPurchaseOrder::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id)); 
            $data['count_po']  = number_format(TransactionPurchaseOrder::count_perwh($this->user_info->shop_id, $warehouse_id));

            $data['pr_amount'] = currency('PHP ',RequisitionSlip::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id)); 
            $data['count_pr']  = number_format(RequisitionSlip::count_perwh($this->user_info->shop_id, $warehouse_id));

            $data['so_amount'] = currency('PHP ',TransactionSalesOrder::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id)); 
            $data['count_so']  = number_format(TransactionSalesOrder::count_perwh($this->user_info->shop_id, $warehouse_id));

            $data['ar_amount'] = currency('PHP ',TransactionSalesInvoice::get_total_amount_perwh($this->user_info->shop_id, $warehouse_id));
            $data['count_ar']  =   number_format(TransactionSalesInvoice::count_perwh($this->user_info->shop_id, $warehouse_id)); 
        }
        $data['purchase_requisition'] = AccountingTransaction::settings($this->user_info->shop_id,"purchase_requisition");

        return view('member.dashboard.count_transaction', $data);
    }
    public function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    public function random_color() {    
        return '#'.$this->random_color_part() .''. $this->random_color_part() .''. $this->random_color_part();
    }

    public function new_dashboard()
    {
        return view('member.dashboard.new_dashboard');
    }

    public function change_warehouse()
    {
        if(Request::input("change_warehouse"))
        {
            Session::put("show_reorder",null);
            Session::put("reorder_item_".Warehouse2::get_current_warehouse($this->user_info->shop_id), null);
            Session::put("reorder_item_".Request::input("change_warehouse"),null);
            $data = $this->save_warehouse_id(Request::input("change_warehouse"));
            return json_encode($data);
        }
        else
        {
            dd("Error 404");
        }
    }
    public function load_warehouse_name() /* DON'T REMOVE THIS FUNCTION ONEGAII */
    {
        $data = array();
        return view("member.dashboard.load_warehouse_name", $data);
    }
    /**
     * Dashboard Statistics
     *
     * @return  view
     */
    public function statistics()
    {
        $period         = Request::input("period");
        $period         = "days_ago";
        $date["days"]   = "365";
        $from           = Report::checkDatePeriod($period, $date)['start_date'];
        $to             = Report::checkDatePeriod($period, $date)['end_date'];

        $data["open_invoice"]       = Invoice::invoiceStatus($from, $to)["open"];
        $data["overdue_invoice"]    = Invoice::invoiceStatus($from, $to)["overdue"];

        $date["days"]   = "30";
        $from           = Report::checkDatePeriod($period, $date)['start_date'];
        $to             = Report::checkDatePeriod($period, $date)['end_date'];
        
        $data["paid_invoice"]       = Invoice::invoiceStatus($from, $to)["paid"];


        $data["_expenses"]   = Tbl_journal_entry_line::account()->journal()->totalAmount()
                            ->where("je_shop_id", $this->getShopId())
                            ->whereIn("chart_type_name", ['Expense', 'Other Expense', 'Cost of Good Sold'])
                            ->whereRaw("DATE(je_entry_date) >= '$from'")
                            ->whereRaw("DATE(je_entry_date) <= '$to'")
                            ->get();

        $data["_income"]     = Tbl_journal_entry_line::account()->journal()
                            ->selectRaw("*")
                            ->amount()
                            ->where("je_shop_id", $this->getShopId())
                            ->whereIn("chart_type_name", ['Income', 'Other Income'])
                            ->whereRaw("DATE(je_entry_date) >= '$from'")
                            ->whereRaw("DATE(je_entry_date) <= '$to'")
                            ->groupBy(DB::raw("DATE(je_entry_date)"))
                            ->get();

        $data["_bank"]      = Tbl_journal_entry_line::account()->journal()->totalAmount()
                            ->where("je_shop_id", $this->getShopId())
                            ->whereIn("chart_type_name", ['Bank'])
                            ->get();

        return view('member.dashboard.dashboard', $data);
    }
}