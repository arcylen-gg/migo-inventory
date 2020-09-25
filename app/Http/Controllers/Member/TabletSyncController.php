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
use App\Globals\Tablet_sync;


use App\Models\Tbl_shop;
use App\Models\Tbl_category;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_country;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_attachment;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_employee;
use App\Models\Tbl_image;
use App\Models\Tbl_inventory_serial_number;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_item_multiple_price;
use App\Models\Tbl_item_type;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_manual_credit_memo;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_manual_receive_payment;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_position;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_settings;
use App\Models\Tbl_sir;
use App\Models\Tbl_sir_cm_item;
use App\Models\Tbl_sir_inventory;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_sir_sales_report;
use App\Models\Tbl_terms;
use App\Models\Tbl_truck;
use App\Models\Tbl_um;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_user;
use App\Models\Tbl_tablet_data;
use stdClass;
use Session;
use Crypt;
use Validator;
use Redirect;

use Carbon\Carbon;


class TabletSyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clean_value($value)
    {
        $new_value = new stdClass();

        foreach($value->toArray() as $key => $val)
        { 
            $val = stripslashes($val);
            $val = preg_replace("/'/", "\&#39;", $val);
            $new_value->$key = $val;
        }

        return $new_value;
    }
    public function add_limiter($limit, $limit_ctr)
    {
        if($limit != 0)
        {
            if($limit == $limit_ctr)
            {
                return true;   
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public function sync($table, $date)
    {
        $limit = 0;
        $limit_ctr = 0;
        $shop_id = Request::input('shop_id');

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, PUT, POST');
        $return = [];
        if($table == 'tbl_shop')
        {
            $data = Tbl_shop::where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_shop (shop_id, shop_key, shop_date_created, shop_date_expiration, shop_last_active_date, shop_status, shop_country, shop_city, shop_zip, shop_street_address, shop_contact, url, shop_domain, shop_theme, shop_theme_color, member_layout, shop_wallet_tours, shop_wallet_tours_uri, shop_merchant_school, created_at, updated_at) VALUES " . "(".$value->shop_id.",'".$value->shop_key."','".$value->shop_date_created."','".$value->shop_date_expiration."','".$value->shop_last_active_date."','".$value->shop_status."','".$value->shop_country."','".$value->shop_city."','".$value->shop_zip."','".$value->shop_street_address."','".$value->shop_contact."','".$value->url."','".$value->shop_domain."','".$value->shop_theme."','".$value->shop_theme_color."','".$value->member_layout."','".$value->shop_wallet_tours."','".$value->shop_wallet_tours_uri."','".$value->shop_merchant_school."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_category')
        {
            $data = Tbl_category::where('type_shop', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_category (type_id, type_name, type_parent_id, type_sub_level, type_shop, type_category, type_date_created, archived, is_mts, created_at, updated_at) VALUES " . "(".$value->type_id.",'".$value->type_name."','".$value->type_parent_id."','".$value->type_sub_level."','".$value->type_shop."','".$value->type_category."','".$value->type_date_created."','".$value->archived."','".$value->is_mts."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_audit_trail')
        {
            // $data = Tbl_audit_trail::where('audit_shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_audit_trail (audit_trail_id, user_id, remarks, old_data, new_data, created_at, updated_at, source, source_id, audit_shop_id) VALUES " . "(".$value->audit_trail_id.",'".$value->user_id."','".$value->remarks."','".$value->old_data."','".$value->new_data."','".$value->created_at."','".$value->updated_at."','".$value->source."','".$value->source_id."','".$value->audit_shop_id."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_chart_account_type')
        {
            $data = Tbl_chart_account_type::get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_chart_account_type (chart_type_id, chart_type_name, chart_type_description, has_open_balance, chart_type_category, normal_balance, created_at, updated_at) VALUES " . "(".$value->chart_type_id.",'".$value->chart_type_name."','".$value->chart_type_description."','".$value->has_open_balance."','".$value->chart_type_category."','".$value->normal_balance."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_chart_of_account')
        {
            $data = Tbl_chart_of_account::where('account_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_chart_of_account (account_id, account_shop_id, account_type_id, account_number, account_name, account_full_name, account_description, account_parent_id, account_sublevel, account_balance, account_open_balance, account_open_balance_date, is_tax_account, account_tax_code_id, archived, account_timecreated, account_protected, account_code, created_at, updated_at) VALUES " . "(".$value->account_id.",'".$value->account_shop_id."','".$value->account_type_id."','".$value->account_number."','".$value->account_name."','".$value->account_full_name."','".$value->account_description."','".$value->account_parent_id."','".$value->account_sublevel."','".$value->account_balance."','".$value->account_open_balance."','".$value->account_open_balance_date."','".$value->is_tax_account."','".$value->account_tax_code_id."','".$value->archived."','".$value->account_timecreated."','".$value->account_protected."','".$value->account_code."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_country')
        {
            // $data = Tbl_country::get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_country (country_id, country_code, country_name, created_at, updated_at) VALUES " . "(".$value->country_id.",'".$value->country_code."','".$value->country_name."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_credit_memo')
        {
            $data = Tbl_credit_memo::where('cm_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_credit_memo (cm_id, cm_customer_id, cm_shop_id, cm_ar_acccount, cm_customer_email, cm_date, cm_message, cm_memo, cm_amount, date_created, cm_type, cm_used_ref_name,cm_used_ref_id,created_at,updated_at) VALUES " . "(".$value->cm_id.",'".$value->cm_customer_id."','".$value->cm_shop_id."','".$value->cm_ar_acccount."','".$value->cm_customer_email."','".$value->cm_date."','".$value->cm_message."','".$value->cm_memo."','".$value->cm_amount."','".$value->date_created."','".$value->cm_type."','".$value->cm_used_ref_name."','".$value->cm_used_ref_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_credit_memo_line')
        {
            $data = Tbl_credit_memo_line::leftjoin('tbl_credit_memo','tbl_credit_memo.cm_id','=','tbl_credit_memo_line.cmline_cm_id')->where('cm_shop_id', $shop_id)->groupBy('tbl_credit_memo_line.cmline_id')->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_credit_memo_line (cmline_id, cmline_cm_id, cmline_service_date, cmline_um, cmline_item_id, cmline_description, cmline_qty, cmline_rate, cmline_amount, created_at,updated_at) VALUES " . "(".$value->cmline_id.",'".$value->cmline_cm_id."','".$value->cmline_service_date."','".$value->cmline_um."','".$value->cmline_item_id."','".$value->cmline_description."','".$value->cmline_qty."','".$value->cmline_rate."','".$value->cmline_amount."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_customer')
        {
            $data = Tbl_customer::leftjoin("tbl_customer_other_info",'tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_customer (customer_id, shop_id, country_id, title_name, first_name, middle_name, last_name,suffix_name, email, password, company, b_day,profile,IsWalkin, created_date, archived, ismlm, mlm_username, tin_number, is_corporate, approved, created_at, updated_at, customer_phone, customer_mobile, customer_fax, get_status) VALUES " . "(".$value->customer_id.",'".$value->shop_id."','".$value->country_id."','".$value->title_name."','".$value->first_name."','".$value->middle_name."','".$value->last_name."','".$value->suffix_name."','".$value->email."','".$value->password."','".$value->company."','".$value->b_day."','".$value->profile."','".$value->IsWalkin."','".$value->created_date."','".$value->archived."','".$value->ismlm."','".$value->mlm_username."','".$value->tin_number."','".$value->is_corporate."','".$value->approved."','".$value->created_at."','".$value->updated_at."','".$value->customer_phone."','".$value->customer_mobile."','".$value->customer_fax."','old')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_customer_address')
        {
            $data = Tbl_customer_address::leftjoin("tbl_customer",'tbl_customer.customer_id','=','tbl_customer_address.customer_id')->where('shop_id', $shop_id)->groupBy('tbl_customer_address.customer_address_id')->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_customer_address (customer_address_id, customer_id , country_id, customer_state, customer_city, customer_zipcode, customer_street, purpose, archived, created_at, updated_at, get_status) VALUES " . "(".$value->customer_address_id.",'".$value->customer_id."','".$value->country_id."','".$value->customer_state."','".$value->customer_city."','".$value->customer_zipcode."','".$value->customer_street."','".$value->purpose."','".$value->archived."','".$value->created_at."','".$value->updated_at."','old')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_customer_attachment')
        {
            // $data = Tbl_customer_attachment::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_customer_attachment (customer_attachment_id, customer_id , customer_attachment_path, customer_attachment_name, customer_attachment_extension, mime_type, archived, created_at, updated_at) VALUES " . "(".$value->customer_attachment_id.",'".$value->customer_id."','".$value->customer_attachment_path."','".$value->customer_attachment_name."','".$value->customer_attachment_extension."','".$value->mime_type."','".$value->archived."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_customer_invoice')
        {
            $data = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_customer_invoice (inv_id, new_inv_id , inv_shop_id, inv_customer_id, inv_customer_email, inv_customer_billing_address, inv_terms_id, inv_date, inv_due_date,inv_message,inv_memo,inv_discount_type,inv_discount_value,ewt,taxable,inv_subtotal_price,inv_overall_price,inv_payment_applied,inv_is_paid,inv_custom_field_id,date_created,credit_memo_id,is_sales_receipt,sale_receipt_cash_account,created_at,updated_at) VALUES " . "(".$value->inv_id.",'".$value->new_inv_id."','".$value->inv_shop_id."','".$value->inv_customer_id."','".$value->inv_customer_email."','".$value->inv_customer_billing_address."','".$value->inv_terms_id."','".$value->inv_date."','".$value->inv_due_date."','".$value->inv_message."','".$value->inv_memo."','".$value->inv_discount_type."','".$value->inv_discount_value."','".$value->ewt."','".$value->taxable."','".$value->inv_subtotal_price."','".$value->inv_overall_price."','".$value->inv_payment_applied."','".$value->inv_is_paid."','".$value->inv_custom_field_id."','".$value->date_created."','".$value->credit_memo_id."','".$value->is_sales_receipt."','".$value->sale_receipt_cash_account."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_customer_invoice_line')
        {
            $data = Tbl_customer_invoice_line::leftjoin('tbl_customer_invoice','tbl_customer_invoice.inv_id','=','tbl_customer_invoice_line.invline_inv_id')->groupBy('tbl_customer_invoice_line.invline_id')->where('inv_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_customer_invoice_line (invline_id, invline_inv_id , invline_service_date, invline_item_id, invline_description, invline_um, invline_qty, invline_rate, taxable,invline_discount,invline_discount_type,invline_discount_remark,invline_amount,date_created,invline_ref_name,invline_ref_id,created_at,updated_at) VALUES " . "(".$value->invline_id.",'".$value->invline_inv_id."','".$value->invline_service_date."','".$value->invline_item_id."','".$value->invline_description."','".$value->invline_um."','".$value->invline_qty."','".$value->invline_rate."','".$value->taxable."','".$value->invline_discount."','".$value->invline_discount_type."','".$value->invline_discount_remark."','".$value->invline_amount."','".$value->date_created."','".$value->invline_ref_name."','".$value->invline_ref_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_default_chart_account')
        {
            $data = Tbl_default_chart_account::get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_default_chart_account (default_id, default_type_id, default_number, default_name, default_description, default_parent_id, default_sublevel, default_balance, default_open_balance,default_open_balance_date,is_tax_account,account_tax_code_id,default_for_code,account_protected,created_at,updated_at) VALUES " . "(".$value->default_id.",'".$value->default_type_id."','".$value->default_number."','".$value->default_name."','".$value->default_description."','".$value->default_parent_id."','".$value->default_sublevel."','".$value->default_balance."','".$value->default_open_balance."','".$value->default_open_balance_date."','".$value->is_tax_account."','".$value->account_tax_code_id."','".$value->default_for_code."','".$value->account_protected."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }


        if($table == 'tbl_employee')
        {
            $data = Tbl_employee::where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_employee (employee_id, shop_id, warehouse_id, first_name, middle_name, last_name, gender, email, username,password,b_day,position_id,date_created,archived,created_at,updated_at) VALUES " . "(".$value->employee_id.",'".$value->shop_id."','".$value->warehouse_id."','".$value->first_name."','".$value->middle_name."','".$value->last_name."','".$value->gender."','".$value->email."','".$value->username."','".Crypt::decrypt($value->password)."','".$value->b_day."','".$value->position_id."','".$value->date_created."','".$value->archived."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_image')
        {
            // $data = Tbl_image::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_image (image_id, image_path, image_key, image_shop, image_reason, image_reason_id, image_date_created, created_at  , updated_at) VALUES " . "(".$value->image_id.",'".$value->image_path."','".$value->image_key."','".$value->image_shop."','".$value->image_reason."','".$value->image_reason_id."','".$value->image_date_created."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_inventory_serial_number')
        {
            // $data = Tbl_inventory_serial_number::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_inventory_serial_number (serial_id, serial_inventory_id, item_id, serial_number, serial_created, item_count, item_consumed, sold, consume_source,consume_source_id,serial_has_been_credit,serial_has_been_debit,created_at,updated_at) VALUES " . "(".$value->serial_id.",'".$value->serial_inventory_id."','".$value->item_id."','".$value->serial_number."','".$value->serial_created."','".$value->item_count."','".$value->item_consumed."','".$value->sold."','".$value->consume_source."','".$value->consume_source_id."','".$value->serial_has_been_credit."','".$value->serial_has_been_debit."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_inventory_slip')
        {
           //  $data = Tbl_inventory_slip::where('shop_id', $shop_id)->get();
           //  foreach ($data as $key => $value) 
           //  {
           //      $value = $this->clean_value($value);
           //      $return[$key] = "INSERT INTO tbl_inventory_slip (inventory_slip_id, inventory_slip_id_sibling, inventory_reason, warehouse_id, inventory_remarks, inventory_slip_date, archived, inventory_slip_shop_id, slip_user_id,inventory_slip_status,inventroy_source_reason,inventory_source_id,inventory_source_name,inventory_slip_consume_refill,inventory_slip_consume_cause,inventory_slip_consumer_id,created_at,updated_at) VALUES " . "(".$value->inventory_slip_id.",'".$value->inventory_slip_id_sibling."','".$value->inventory_reason."','".$value->warehouse_id."','".$value->inventory_remarks."','".$value->inventory_slip_date."','".$value->archived."','".$value->inventory_slip_shop_id."','".$value->slip_user_id."','".$value->inventory_slip_status."','".$value->inventroy_source_reason."','".$value->inventory_source_id."','".$value->inventory_source_name."','".$value->inventory_slip_consume_refill."','".$value->inventory_slip_consume_cause."','".$value->inventory_slip_consumer_id."','".$value->created_at."','".$value->updated_at."')";
           //      if($this->add_limiter($limit, $limit_ctr++))
           //      {
           //          break 1;
           //      }
           // }
        }

        if($table == 'tbl_item')
        {
            $data = Tbl_item::where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_item (item_id, item_id, item_name, item_sku, item_sales_information, item_purchasing_information, item_img, item_quantity, item_reorder_point,item_price,item_cost,item_sale_to_customer,item_purchase_from_supplier,item_type_id,item_category_id,item_asset_account_id,item_income_account_id,item_expense_account_id,item_date_tracked,item_date_created,item_date_archived,archived,shop_id,item_barcode,has_serial_number,item_measurement_id,item_vendor_id,item_manufacturer_id,packing_size,item_code,item_show_in_mlm,promo_price,start_promo_date,end_promo_date,bundle_group,created_at,updated_at) VALUES " . "(".$value->item_id.",'".$value->item_id."','".$value->item_name."','".$value->item_sku."','".$value->item_sales_information."','".$value->item_purchasing_information."','".$value->item_img."','".$value->item_quantity."','".$value->item_reorder_point."','".$value->item_price."','".$value->item_cost."','".$value->item_sale_to_customer."','".$value->item_purchase_from_supplier."','".$value->item_type_id."','".$value->item_category_id."','".$value->item_asset_account_id."','".$value->item_income_account_id."','".$value->item_expense_account_id."','".$value->item_date_tracked."','".$value->item_date_created."','".$value->item_date_archived."','".$value->archived."','".$value->shop_id."','".$value->item_barcode."','".$value->has_serial_number."','".$value->item_measurement_id."','".$value->item_vendor_id."','".$value->item_manufacturer_id."','".$value->packing_size."','".$value->item_code."','".$value->item_show_in_mlm."','".$value->promo_price."','".$value->start_promo_date."','".$value->end_promo_date."','".$value->bundle_group."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_item_bundle')
        {
            $data = Tbl_item_bundle::leftjoin('tbl_item','tbl_item.item_id','=','tbl_item_bundle.bundle_bundle_id')->groupBy('tbl_item_bundle.bundle_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_item_bundle (bundle_id, bundle_bundle_id, bundle_item_id, bundle_um_id, bundle_qty, bundle_display_components,created_at,updated_at) VALUES " . "(".$value->bundle_id.",'".$value->bundle_bundle_id."','".$value->bundle_item_id."','".$value->bundle_um_id."','".$value->bundle_qty."','".$value->bundle_display_components."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_item_discount')
        {
            $data = Tbl_item_discount::leftjoin('tbl_item','tbl_item.item_id','=','tbl_item_discount.discount_item_id')->where('shop_id', $shop_id)->groupBy('tbl_item_discount.item_discount_id')->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_item_discount (item_discount_id, discount_item_id, item_discount_value, item_discount_type, item_discount_remark, item_discount_date_start,item_discount_date_end,created_at,updated_at) VALUES " . "(".$value->item_discount_id.",'".$value->discount_item_id."','".$value->item_discount_value."','".$value->item_discount_type."','".$value->item_discount_remark."','".$value->item_discount_date_start."','".$value->item_discount_date_end."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_item_multiple_price')
        {
            // $data = Tbl_item_multiple_price::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_item_multiple_price (multiprice_id, multiprice_item_id, multiprice_qty, multiprice_price, date_created,created_at,updated_at) VALUES " . "(".$value->multiprice_id.",'".$value->multiprice_item_id."','".$value->multiprice_qty."','".$value->multiprice_price."','".$value->date_created."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_item_type')
        {
            $data = Tbl_item_type::get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_item_type (item_type_id, item_type_name, archived, created_at,updated_at) VALUES " . "(".$value->item_type_id.",'".$value->item_type_name."','".$value->archived."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_journal_entry')
        {
            // $data = Tbl_journal_entry::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_journal_entry (je_id, je_shop_id, je_reference_module, je_reference_id, je_entry_date,je_remarks ,created_at,updated_at) VALUES " . "(".$value->je_id.",'".$value->je_shop_id."','".$value->je_reference_module."','".$value->je_reference_id."','".$value->je_entry_date."','".$value->je_remarks."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }


        if($table == 'tbl_journal_entry_line')
        {
            // $data = Tbl_journal_entry_line::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_journal_entry_line (jline_id, jline_je_id, jline_name_id, jline_name_reference, jline_item_id, jline_account_id, jline_type,jline_amount,jline_description, created_at,updated_at,jline_warehouse_id) VALUES " . "(".$value->jline_id.",'".$value->jline_je_id."','".$value->jline_name_id."','".$value->jline_name_reference."','".$value->jline_item_id."','".$value->jline_account_id."','".$value->jline_type."','".$value->jline_amount."','".$value->jline_description."','".$value->created_at."','".$value->updated_at."','".$value->jline_warehouse_id."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }

        if($table == 'tbl_manual_credit_memo')
        {
            $data = Tbl_manual_credit_memo::leftjoin('tbl_sir','tbl_sir.sir_id','=','tbl_manual_credit_memo.sir_id')->selectRaw('*, tbl_manual_credit_memo.sir_id as cm_sir_id')->groupBy('tbl_manual_credit_memo.manual_cm_id')->where('shop_id',$shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_manual_credit_memo (manual_cm_id, sir_id, cm_id, manual_cm_date, is_sync,created_at,updated_at) VALUES " . "(".$value->manual_cm_id.",'".$value->cm_sir_id."','".$value->cm_id."','".$value->manual_cm_date."','".$value->is_sync."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_manual_invoice')
        {
            $data = Tbl_manual_invoice::leftjoin('tbl_sir','tbl_sir.sir_id','=','tbl_manual_invoice.sir_id')->selectRaw('*, tbl_manual_invoice.sir_id as inv_sir_id')->groupBy('tbl_manual_invoice.manual_invoice_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_manual_invoice (manual_invoice_id, sir_id, inv_id, manual_invoice_date, is_sync,created_at,updated_at) VALUES " . "(".$value->manual_invoice_id.",'".$value->inv_sir_id."','".$value->inv_id."','".$value->manual_invoice_date."','".$value->is_sync."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_manual_receive_payment')
        {
            $data = Tbl_manual_receive_payment::leftjoin('tbl_sir','tbl_sir.sir_id','=','tbl_manual_receive_payment.sir_id')->selectRaw('*, tbl_manual_receive_payment.sir_id as rp_sir_id')->groupBy('tbl_manual_receive_payment.manual_receive_payment_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_manual_receive_payment (manual_receive_payment_id, agent_id, rp_id, sir_id,rp_date, is_sync,created_at,updated_at) VALUES " . "(".$value->manual_receive_payment_id.",'".$value->agent_id."','".$value->rp_id."','".$value->rp_sir_id."','".$value->rp_date."','".$value->is_sync."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_manufacturer')
        {
            // $data = Tbl_manufacturer::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_manufacturer (manufacturer_id, manufacturer_name, manufacturer_address, phone_number,email_address, website,date_created,date_updated,archived, manufacturer_shop_id,manufacturer_fname,manufacturer_mname,manufacturer_lname,manufacturer_image,created_at,updated_at) VALUES " . "(".$value->manufacturer_id.",'".$value->manufacturer_name."','".$value->manufacturer_address."','".$value->phone_number."','".$value->email_address."','".$value->website."','".$value->date_created."','".$value->date_updated."','".$value->archived."','".$value->manufacturer_shop_id."','".$value->manufacturer_fname."','".$value->manufacturer_mname."','".$value->manufacturer_lname."','".$value->manufacturer_image."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }
        if($table == 'tbl_position')
        {
            $data = Tbl_position::where('position_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_position (position_id, position_name, daily_rate, position_created,archived, position_code,position_shop_id,created_at,updated_at) VALUES " . "(".$value->position_id.",'".$value->position_name."','0','".$value->position_created."','".$value->archived."','".$value->position_code."','".$value->position_shop_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_receive_payment')
        {
            $data = Tbl_receive_payment::where('rp_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_receive_payment (rp_id, rp_shop_id, rp_customer_id, rp_ar_account, rp_date, rp_total_amount, rp_payment_method,rp_memo,date_created, rp_ref_name,rp_ref_id,created_at,updated_at) VALUES " . "(".$value->rp_id.",'".$value->rp_shop_id."','".$value->rp_customer_id."','".$value->rp_ar_account."','".$value->rp_date."','".$value->rp_total_amount."','".$value->rp_payment_method."','".$value->rp_memo."','".$value->date_created."','".$value->rp_ref_name."','".$value->rp_ref_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_receive_payment_line')
        {
            $data = Tbl_receive_payment_line::leftjoin('tbl_receive_payment','tbl_receive_payment.rp_id','=','tbl_receive_payment_line.rpline_rp_id')->groupBy('tbl_receive_payment_line.rpline_id')->where('rp_shop_id',$shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_receive_payment_line (rpline_id, rpline_rp_id, rpline_reference_name, rpline_reference_id,rpline_amount,created_at,updated_at) VALUES " . "(".$value->rpline_id.",'".$value->rpline_rp_id."','".$value->rpline_reference_name."','".$value->rpline_reference_id."','".$value->rpline_amount."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_settings')
        {
            $data = Tbl_settings::where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_settings (settings_id, settings_key, settings_value, settings_setup_done,shop_id,created_at,updated_at) VALUES " . "(".$value->settings_id.",'".$value->settings_key."','".$value->settings_value."','".$value->settings_setup_done."','".$value->shop_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_sir')
        {
            $data = Tbl_sir::where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_sir (sir_id, sir_warehouse_id, truck_id, shop_id,sales_agent_id, date_created,archived,lof_status,sir_status, is_sync,ilr_status,rejection_reason,agent_collection,agent_collection_remarks,reload_sir,created_at,updated_at) VALUES " . "(".$value->sir_id.",'".$value->sir_warehouse_id."','".$value->truck_id."','".$value->shop_id."','".$value->sales_agent_id."','".$value->date_created."','".$value->archived."','".$value->lof_status."','".$value->sir_status."','".$value->is_sync."','".$value->ilr_status."','".$value->rejection_reason."','".$value->agent_collection."','".$value->agent_collection_remarks."','".$value->reload_sir."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_sir_cm_item')
        {
            $data = Tbl_sir_cm_item::leftjoin('tbl_sir','tbl_sir.sir_id','=','tbl_sir_cm_item.sc_sir_id')->groupBy('tbl_sir_cm_item.s_cm_item_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_sir_cm_item (s_cm_item_id, sc_sir_id, sc_item_id, sc_item_qty, sc_physical_count, sc_item_price, sc_status,sc_is_updated,sc_infos ,created_at,updated_at) VALUES " . "(".$value->s_cm_item_id.",'".$value->sc_sir_id."','".$value->sc_item_id."','".$value->sc_item_qty."','".$value->sc_physical_count."','".$value->sc_item_price."','".$value->sc_status."','".$value->sc_is_updated."','".$value->sc_infos."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_sir_inventory')
        {
            $data = Tbl_sir_inventory::leftjoin('tbl_sir','tbl_sir.sir_id','=','tbl_sir_inventory.inventory_sir_id')->groupBy('tbl_sir_inventory.sir_inventory_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_sir_inventory (sir_inventory_id, sir_item_id, inventory_sir_id, sir_inventory_count,sir_inventory_ref_name, sir_inventory_ref_id,created_at,updated_at, is_bundled_item) VALUES " . "(".$value->sir_inventory_id.",'".$value->sir_item_id."','".$value->inventory_sir_id."','".$value->sir_inventory_count."','".$value->sir_inventory_ref_name."','".$value->sir_inventory_ref_id."','".$value->created_at."','".$value->updated_at."','".$value->is_bundled_item."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_sir_item')
        {
            $data = Tbl_sir_item::leftjoin('tbl_sir','tbl_sir.sir_id','=','tbl_sir_item.sir_id')->groupBy('tbl_sir_item.sir_item_id')->where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_sir_item (sir_item_id, sir_id, item_id, item_qty,archived, related_um_type,total_issued_qty,um_qty,sold_qty, remaining_qty,physical_count,status,loss_amount,sir_item_price,is_updated,infos,created_at,updated_at) VALUES " . "(".$value->sir_item_id.",'".$value->sir_id."','".$value->item_id."','".$value->item_qty."','".$value->archived."','".$value->related_um_type."','".$value->total_issued_qty."','".$value->um_qty."','".$value->sold_qty."','".$value->remaining_qty."','".$value->physical_count."','".$value->status."','".$value->loss_amount."','".$value->sir_item_price."','".$value->is_updated."','".$value->infos."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_sir_sales_report')
        {
            // $data = Tbl_sir_sales_report::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_sir_sales_report (sir_sales_report_id, sir_id, report_data, report_created,created_at,updated_at) VALUES " . "(".$value->sir_sales_report_id.",'".$value->sir_id."','".$value->report_data."','".$value->report_created."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }
        // 39
        if($table == 'tbl_terms')
        {
            $data = Tbl_terms::where('terms_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_terms (terms_id, terms_shop_id, terms_name, terms_no_of_days,archived,created_at,updated_at) VALUES " . "(".$value->terms_id.",'".$value->terms_shop_id."','".$value->terms_name."','".$value->terms_no_of_days."','".$value->archived."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_truck')
        {
            $data = Tbl_truck::where('truck_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_truck (truck_id, plate_number, warehouse_id, date_created,archived, truck_model,truck_kilogram,truck_shop_id,created_at,updated_at) VALUES " . "(".$value->truck_id.",'".$value->plate_number."','".$value->warehouse_id."','".$value->date_created."','".$value->archived."','".$value->truck_model."','".$value->truck_kilogram."','".$value->truck_shop_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_um')
        {
            $data = Tbl_um::where('um_shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_um (id, um_name, um_abbrev, is_based,um_shop_id,created_at,updated_at) VALUES " . "(".$value->id.",'".$value->um_name."','".$value->um_abbrev."','".$value->is_based."','".$value->um_shop_id."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_unit_measurement')
        {
            $data = Tbl_unit_measurement::where('um_shop', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_unit_measurement (um_id, um_shop, um_name, is_multi,um_date_created, um_archived,um_type,parent_basis_um,um_item_id ,um_n_base, um_base,created_at,updated_at) VALUES " . "(".$value->um_id.",'".$value->um_shop."','".$value->um_name."','".$value->is_multi."','".$value->um_date_created."','".$value->um_archived."','".$value->um_type."','".$value->parent_basis_um."','".$value->um_item_id."','".$value->um_n_base."','".$value->um_base."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }
        if($table == 'tbl_unit_measurement_multi')
        {
            $data = Tbl_unit_measurement_multi::leftjoin('tbl_unit_measurement','tbl_unit_measurement_multi.multi_um_id','=','tbl_unit_measurement.um_id')->groupBy('tbl_unit_measurement_multi.multi_id')->where('um_shop', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $value = $this->clean_value($value);
                $return[$key] = "INSERT INTO tbl_unit_measurement_multi (multi_id, multi_um_id, multi_name, multi_conversion_ratio,multi_sequence, unit_qty,multi_abbrev,is_base, created_at,updated_at) VALUES " . "(".$value->multi_id.",'".$value->multi_um_id."','".$value->multi_name."','".$value->multi_conversion_ratio."','".$value->multi_sequence."','".$value->unit_qty."','".$value->multi_abbrev."','".$value->is_base."','".$value->created_at."','".$value->updated_at."')";
                if($this->add_limiter($limit, $limit_ctr++))
                {
                    break 1;
                }
            }
        }

        if($table == 'tbl_user')
        {
            // $data = Tbl_user::where('shop_id', $shop_id)->get();
            // foreach ($data as $key => $value) 
            // {
            //     $value = $this->clean_value($value);
            //     $return[$key] = "INSERT INTO tbl_user (user_id, user_email, user_level, user_first_name,user_last_name, user_contact_number,user_password, user_date_created, user_last_active_date, user_shop,IsWalkin,archived,created_at,updated_at) VALUES " . "(".$value->user_id.",'".$value->user_email."','".$value->user_level."','".$value->user_first_name."','".$value->user_last_name."','".$value->user_contact_number."','".Crypt::decrypt($value->user_password)."','".$value->user_date_created."','".$value->user_last_active_date."','".$value->user_shop."','".$value->IsWalkin."','".$value->archived."','".$value->created_at."','".$value->updated_at."')";
            //     if($this->add_limiter($limit, $limit_ctr++))
            //     {
            //         break 1;
            //     }
            // }
        }


        if($table == 'tbl_payment_method')
        {
            $data = Tbl_payment_method::where('shop_id', $shop_id)->get();
            foreach ($data as $key => $value) 
            {
                $return[$key] = 'INSERT INTO tbl_payment_method (payment_method_id, shop_id, payment_name, isDefault, archived) VALUES ' . '('.$value->payment_method_id.',"'.$value->shop_id.'","'.$value->payment_name.'","'.$value->isDefault.'","'.$value->archived.'")';
            }
        }
        if($table == 'tbl_invoice_log')
        {
            $data = Tbl_customer::leftjoin("tbl_customer_other_info","tbl_customer_other_info.customer_id","=","tbl_customer.customer_id")
                                    ->balanceJournal()
                                    ->selectRaw("tbl_customer.customer_id as customer_id1, tbl_customer.*, tbl_customer_other_info.*, tbl_customer_other_info.customer_id as cus_id")
                                    ->where('tbl_customer.shop_id',$shop_id)
                                    ->groupBy('tbl_customer.customer_id')
                                    ->orderBy("tbl_customer.first_name")
                                    ->get();
            foreach ($data as $key => $value) 
            {
                $return[$key] = 'INSERT INTO tbl_invoice_log (shop_id, transaction_customer_id ,transaction_name, transaction_id, transaction_amount, date_created) VALUES (' . $value->shop_id . ','.$value->customer_id.', "customer_beginning_balance", 0,' . ($value->balance ? $value->balance : 0) . ', "'. Carbon::now() . '")';

            }   
        }
        return json_encode($return);
    }
    public function sync_update()
    {
        $table_name = Request::input('table');
        $dataset = Request::input('dataset');
        
        $new_data = json_decode($dataset);
        // dd($new_data);
        foreach($new_data as $key => $value)
        {
            if($table_name == 'tbl_shop')
            {
                $up['shop_key'] = $value->shop_key;
                $up['shop_date_created'] = $value->shop_date_created;
                $up['shop_date_expiration'] = $value->shop_date_expiration;
                $up['shop_last_active_date'] = $value->shop_last_active_date;
                $up['shop_status'] = $value->shop_status;
                $up['shop_country'] = $value->shop_country;
                $up['shop_city'] = $value->shop_city;
                $up['shop_zip'] = $value->shop_zip;
                $up['shop_street_address'] = $value->shop_street_address;
                $up['shop_contact'] = $value->shop_contact;
                $up['url'] = $value->url;
                $up['shop_domain'] = $value->shop_domain;
                $up['shop_theme'] = $value->shop_theme;
                $up['shop_theme_color'] = $value->shop_theme_color;
                $up['member_layout'] = $value->member_layout;
                $up['shop_wallet_tours'] = $value->shop_wallet_tours;
                $up['shop_wallet_tours_uri'] = $value->shop_wallet_tours_uri;
                $up['shop_merchant_school'] = $value->shop_merchant_school;
                $up['shop_wallet_tours'] = $value->shop_wallet_tours;
                $up['created_at'] = $value->created_at;
                $up['updated_at'] = $value->updated_at;
                
                Tbl_shop::where('shop_id',$value->shop_id)->update($up);
            }
        }
        
        return 'success';
        
    }
    function get_updates()
    {
        $all_data = collect(json_decode(Request::input("getdata")))->toArray();
        $sir_id = Request::input("sir_id");
        $sync_type = Request::input("sync_type");
        if($sir_id && $all_data)
        {
            /*LOGIN FIRST*/
            $sir_data = Tbl_sir::where('sir_id',$sir_id)->first();
            if($sir_data)
            {
                $data['account'] = Tbl_employee::position()->where('employee_id',$sir_data->sales_agent_id)->first();
                Session::put('sales_agent',$data['account']);
                // $count = Tbl_tablet_data::where('sir_id',$sir_id)->count();
                // dd(unserialize($count->sir_data));
                $insert["sir_id"] = $sir_id;
                $insert["sir_data"] = serialize($all_data);
                $data_id = Tbl_tablet_data::insertGetId($insert);
                $return = Tablet_sync::sync($data_id, $sync_type);
                if($return == "success")
                {
                    $returndata['status'] = $return;
                    return json_encode($returndata);
                }       
            }

        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
