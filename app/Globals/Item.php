<?php
namespace App\Globals;
use App\Models\Tbl_variant;
use App\Models\Tbl_user;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_category;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_item_pricing_history;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_item_price_history;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Globals\AccountingTransaction;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_unit_measurement_type;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Accounting;
use App\Globals\Tablet_global;
use App\Globals\Currency;
use App\Models\Tbl_price_level;
use App\Models\Tbl_price_level_item;
use App\Models\Tbl_shop;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_item_type;
use App\Models\Tbl_membership;
use App\Models\Tbl_item_token;
use App\Models\Tbl_vendor_item;
use App\Models\Tbl_vendor;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_item_average_cost_per_warehouse;
use App\Models\Tbl_monitoring_inventory;

use Session;
use DB;
use Carbon\carbon;
use App\Globals\Merchant;
use App\Globals\LandingCost;
use App\Globals\Warehouse2;
use Validator;
use stdClass;
class Item
{
    public static function get_ave_cost_per_warehouse($shop_id, $item_id, $warehouse_id)
    {
        return Tbl_item_average_cost_per_warehouse::where('iacpw_item_id', $item_id)->where('iacpw_shop_id',$shop_id)->where('iacpw_warehouse_id', $warehouse_id)->value('iacpw_ave_cost');
    }
    /* ITEM CRUD START */
    public static function get_markup($shop_id)
    {
        $_item = Tbl_item::wherenull('item_mark_up')->where('shop_id',$shop_id)->get();
        
        $get_markup = null;
        foreach ($_item as $key => $value) 
        {
            $update['item_mark_up'] = $value->item_price - $value->item_cost;
            Tbl_item::where('item_id', $value->item_id)->where('shop_id', $shop_id)->update($update);
        }
    }
    public static function get_item_list($shop_id, $type = array(1,2,3,4,5), $archived = 0)
    {
        return Tbl_item::where("shop_id", $shop_id)->whereIn("item_type_id", $type)->where("archived",$archived)->get();
    }
    public static function get_item_warehouse_reorder($shop_id, $type = array(1,2,3,4,5), $warehouse_id = null, $archived = 0)
    {
        $_get = Tbl_item::warehousereorder()->where("shop_id", $shop_id)->whereIn("item_type_id", $type)->where("archived",$archived);
        if($warehouse_id)
        {
            $_get = $_get->where("wr_warehouse_id",$warehouse_id);
        }
        $_get = $_get->groupBy('item_id')->get();
        if(count($_get) <= 0)
        {
            $_get = Self::get_item_list($shop_id, $type);
        }
        return $_get;
    }
    public static function edit_bulk_validation($shop_id, $edit_bulk)
    {
        $return = null;
        foreach ($edit_bulk as $key => $value)
        {
            $item_info = '<b>'.$value['item_name'].'</b> <br><br>';

            $rules['item_name'] = 'required';
            $rules['item_sku'] = 'required';
            $rules['item_price'] = 'required';
            $rules['item_category_id'] = 'required';

            if($value['item_type_id'] <= 2)
            {
                $rules['item_cost'] = 'required';

                if($value['item_cost'] > $value['item_price'])
                {   
                    $return .= $item_info.' <li>The cost is greater than the sales price.</li>'."<br>";
                }            
            }
            $validator = Validator::make($value, $rules);

            if($validator->fails())
            {
                foreach ($validator->messages()->all('') as $keys => $message)
                {
                    // $return .= $item_info." <li>".$message."</li><br>";
                }
            }
        }
        
        return $return;

    }
    public static function update_bulk_items($shop_id, $edit_bulk)
    {
        $update_bulk = null;
        foreach ($edit_bulk as $key => $value)
        {
            if($value)
            {
                $update_bulk['item_id']       = $value['item_id'];
                $update_bulk['item_sku']      = $value['item_sku'];
                $update_bulk['item_barcode']  = $value['item_barcode'];
                $update_bulk['item_name']     = $value['item_name'];
                $update_bulk['item_price']    = $value['item_price'];
                $update_bulk['item_cost']     = $value['item_cost'];
                $update_bulk['item_type_id']  = $value['item_type_id'];
                $update_bulk['item_mark_up']  = $value['item_mark_up'];
                $update_bulk['item_category_id']      = $value['item_category_id'];
                $update_bulk['item_measurement_id']   = $value['item_measurement_id'];
                $update_bulk['item_reorder_point']    = $value['item_reorder_point'];
                $update_bulk['item_sales_information'] = $value['item_sales_information'];
                
                Tbl_item::where('item_id',$value['item_id'])->update($update_bulk);
            }
        }
    }
    public static function create_validation($shop_id, $item_type, $insert, $item_id = null)
    {
        $return = null;

        if($item_id)
        {
            // $rules['item_name'] = 'required|unique:tbl_item,item_name,'.$item_id.',item_id';
            // $rules['item_sku'] = 'required|unique:tbl_item,item_sku,'.$item_id.',item_id';            
        }
        else
        {
            // $rules['item_name'] = 'required|unique:tbl_item,item_name';
            // $rules['item_sku'] = 'required|unique:tbl_item,item_sku';
        }
        $rules['item_price'] = 'required';
        $rules['item_category_id'] = 'required';

        if($item_type <= 2)
        {
            $rules['item_cost'] = 'required';
            if($insert['item_cost'] > $insert['item_price'])
            {       
                $return .= 'The cost is greater than the sales price.'."<br>";
            }            
        }
        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            foreach ($validator->messages()->all('') as $keys => $message)
            {
                // $return .= $message."<br>";
            }
        }
        if($shop_id)
        {
            $shop_data = Tbl_shop::where('shop_id',$shop_id)->first();
            if(!$shop_data)
            {
                $return .= 'Your account does not exist. <br>';                
            }
        }
        if($item_type)
        {
            $type_data = Tbl_item_type::where('item_type_id',$item_type)->first();
            if(!$type_data)
            {
                $return .= 'Item type does not exist. <br>';            
            }
        }
        if($insert['item_category_id'] != 0)
        {
            $category_data = Tbl_category::where('type_id',$insert['item_category_id'])->where('type_shop',$shop_id)->first();
            if(!$category_data)
            {
                $return .= 'Category does not exist. <br>';            
            }            
        }
        if($insert['item_manufacturer_id'] != 0)
        {
            $category_data = Tbl_manufacturer::where('manufacturer_id',$insert['item_manufacturer_id'])->where('manufacturer_shop_id',$shop_id)->first();
            if(!$category_data)
            {
                $return .= 'Manufacturer does not exist. <br>';            
            }            
        }
        if($insert['item_asset_account_id'] != 0)
        {
            $asset_data = Tbl_chart_of_account::where('account_id',$insert['item_asset_account_id'])->where('account_shop_id',$shop_id)->first();
            if(!$asset_data)
            {
                $return .= 'Asset account does not exist. <br>';            
            }            
        }
        if($insert['item_income_account_id'] != 0)
        {
            $income_data = Tbl_chart_of_account::where('account_id',$insert['item_income_account_id'])->where('account_shop_id',$shop_id)->first();
            if(!$income_data)
            {
                $return .= 'Income account does not exist. <br>';            
            }            
        }
        if($insert['item_expense_account_id'] != 0)
        {
            $expense_data = Tbl_chart_of_account::where('account_id',$insert['item_expense_account_id'])->where('account_shop_id',$shop_id)->first();
            if(!$expense_data)
            {
                $return .= 'Expense account does not exist. <br>';            
            }            
        }

        return $return;

    }
    public static function item($item_id)
    {
        return Tbl_item::um()->where('item_id', $item_id)->first();
    }
    public static function insertLine($item_id, $insert, $entry)
    {
        $return = null;
        if(isset($insert))
        {
            $item_type = Item::get_item_type($item_id);
            /* TRANSACTION JOURNAL */  
            if($item_type != 4 && $item_type != 5)
            {
                $total = $insert['item_quantity'] * $insert['item_cost'];
                $entry_data['item_id']            = $item_id;
                $entry_data['entry_qty']          = $insert['item_quantity'];
                $entry_data['vatable']            = 0;
                $entry_data['discount']           = 0;
                $entry_data['entry_amount']       = $total;
                $entry_data['entry_description']  = $insert['item_purchasing_information'];
            }
            else
            {
                $item_bundle = Item::get_item_in_bundle($item_id);
                if(count($item_bundle) > 0)
                {
                    foreach ($item_bundle as $key_bundle => $value_bundle) 
                    {
                        $item_data = Item::get_item_details($value_bundle->bundle_item_id);
                        $entry_data['b'.$key_bundle]['item_id']            = $value_bundle->bundle_item_id;
                        $entry_data['b'.$key_bundle]['entry_qty']          = $insert['item_quantity'] * (UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty);
                        $entry_data['b'.$key_bundle]['vatable']            = 0;
                        $entry_data['b'.$key_bundle]['discount']           = 0;
                        $entry_data['b'.$key_bundle]['entry_amount']       = $item_data->item_price * $entry_data['b'.$key_bundle]['entry_qty'];
                        $entry_data['b'.$key_bundle]['entry_description']  = $item_data->item_sales_information; 
                    }
                }
            }
            
        }
        if(count($entry_data) > 0)
        {
            Accounting::postJournalEntry($entry, $entry_data);        
        }
        $return = $item_id;
        return $return;
    }
    public static function create($shop_id, $item_type, $insert,$token = null)
    {
        $return['item_id'] = 0;
        $return['status'] = null;
        $return['message'] = null; 

        $rules['item_name'] = 'required';
        $rules['item_sku'] = 'required';
        $rules['item_price'] = 'required';
        $rules['item_category_id'] = 'required';

        if(!isset($insert['item_expense_account_id']))
        {
            $insert['item_expense_account_id'] = Accounting::get_default_coa("accounting-expense");
        }
        if(!isset($insert['item_income_account_id']))
        {
            $insert['item_income_account_id'] = Accounting::get_default_coa("accounting-sales");
        }
        if(!isset($insert['item_asset_account_id']))
        {
            $insert['item_asset_account_id'] = Accounting::get_default_coa("accounting-inventory-asset");
        }

        if($item_type <= 2)
        {
            $rules['item_cost'] = 'required';
            if($insert['item_cost'] > $insert['item_price'])
            {       
                $return['status'] = 'error';
                $return['message'] .= 'The cost is greater than the sales price.'."<br>";
            }
        }
        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            // $return["status"] = "error";
            foreach ($validator->messages()->all('') as $keys => $message)
            {
                // $return["message"] .= $message."<br>";
            }
        }
        if(!$return['status'])
        {
            $insert['shop_id'] = $shop_id;
            $insert['item_type_id'] = $item_type;
            $insert['item_date_created'] = Carbon::now();

            $warehouse_id = Warehouse2::get_current_warehouse($shop_id);

            $return = null;
            if($insert['item_quantity'] > 0)
            {
                $return = Warehouse2::refill_validation($shop_id, $warehouse_id, 0, $insert['item_quantity'], 'Initial Quantity from Item');
            }
            
            if(!$return)
            {
                $item_id = Tbl_item::insertGetId($insert);
                AuditTrail::record_logs("Added", "item", $item_id ,"", serialize($insert));

                $total = $insert['item_cost'] * $insert['item_quantity'];
                /* Transaction Journal */
                $entry["reference_module"]  = "item";
                $entry["reference_id"]      = $item_id;
                $entry["name_reference"]    ='item-initial-qty';
                $entry["name_id"]           = $item_id;
                $entry["total"]             = $total;
                $entry["vatable"]           = 0;
                $entry["discount"]          = 0;
                $entry["ewt"]               = 0;
                //die(var_dump($entry));
                // Self::insertLine($item_id, $insert, $entry); /* TEMPORARY 7-18-18 */

                $source['name'] = 'initial_qty';
                $source['id'] = $item_id;
                $source['item_cost'] = $insert['item_cost'];
                $source['item_price'] = $insert['item_price'];

                $warehouse_id = Warehouse2::get_current_warehouse($shop_id);

                if($insert['item_quantity'] > 0)
                {
                    $return = Warehouse2::refill($shop_id, $warehouse_id, $item_id, $insert['item_quantity'], 'Initial Quantity from Item',$source);
                }

                // patrick
                if($shop_id == 87)
                {
                    $insert_token['item_id']    = $item_id;
                    $insert_token['token_id']   = $token['token_id'];
                    $insert_token['amount']     = $token['amount'];
                    Tbl_item_token::insert($insert_token);
                }

                $return['item_id']       = $item_id;
                $return['status']        = 'success';
                $return['message']       = 'Item successfully created.';
                $return['call_function'] = 'success_item';

                
                if(session('landing_cost') > 0 || session('landing_cost') != null)
                {
                    LandingCost::insert_cost_item($item_id, $shop_id, session('landing_cost'));
                }
            }
        }

        return $return;
    }
    public static function item_data($shop_id, $item_id)
    {
        return Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->first();
    }
    public static function array_item_data($shop_id, $item_id)
    {
        return Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->first()->toArray();
    }
    public static function modify($shop_id, $item_id, $update, $token = null, $user_id)
    {
        $old_data = Self::array_item_data($shop_id, $item_id);
        
        $return['item_id'] = $item_id;
        $return['status'] = null;
        $return['message'] = null; 

        $rules['item_name'] = 'required';
        $rules['item_sku'] = 'required';
        $rules['item_price'] = 'required';
        $rules['item_cost'] = 'required';

        $validator = Validator::make($update, $rules);

        if($update['item_cost'] > $update['item_price'])
        {       
            $return['status'] = 'error';
            $return['message'] = 'The cost is greater than the sales price.'."<br>";
        }

        if($validator->fails())
        {
            $return["status"] = "error";
            foreach ($validator->messages()->all('') as $keys => $message)
            {
                $return["message"] .= $message."<br>";
            }
        }
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        if(!$return['status'])
        {
            // $item = Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->first();
            // $old_item_cost = $item->item_cost;
            // $old_item_price = $item->item_price;

            // $checkave_cost_per_whse = Tbl_item_average_cost_per_warehouse::where('iacpw_shop_id',$shop_id)->where('iacpw_warehouse_id',$warehouse_id)->where('iacpw_item_id',$item_id)->first();
            // if($update['item_cost'] != $old_item_cost || $update['item_price'] != $old_item_price)
            // {
            //     $pricing_history['invty_item_id']     = $item_id;
            //     $pricing_history['invty_sales_price'] = $update['item_price'];
            //     $pricing_history['invty_cost_price']  = $checkave_cost_per_whse->iacpw_ave_cost;
            //     $pricing_history['invty_shop_id']     = $shop_id;
            //     Item::insert_pricing_history($pricing_history, $user_id, $item_id, $shop_id);
            // }
            $update['updated_at'] = Carbon::now();
            Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->update($update);

            // if($checkave_cost_per_whse)
            // {
            //     /*UPDATE*/ 
            //     $up_ave_cost_per_whse['iacpw_ave_cost'] = $update['item_cost'];
            //     Tbl_item_average_cost_per_warehouse::where('iacpw_id', $checkave_cost_per_whse->iacpw_id)->update($up_ave_cost_per_whse);
            // }

            // $check_monitoring = Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)->where('invty_warehouse_id',$warehouse_id)->where('invty_item_id',$item_id)->where('invty_transaction_name', 'initial_qty')->where('invty_transaction_id', $item_id)->first();
            // if($check_monitoring)
            // {
            //     $update_monitoring['invty_cost_price']        = $update['item_cost'];
            //     $update_monitoring['invty_total_cost_price']  = $update['item_cost'] * $check_monitoring->invty_qty;
            //     $update_monitoring['invty_sales_price']       = $update['item_price'];
            //     $update_monitoring['invty_total_sales_price'] = $update['item_price'] * $check_monitoring->invty_qty;
            //     Tbl_monitoring_inventory::where('invty_id', $check_monitoring->invty_id)->update($update_monitoring);
            // }

            //patrick
            if($token)
            {
                $check = Tbl_item_token::where('item_id',$item_id)->first();
                if($check)
                {
                    Tbl_item_token::where('item_id',$item_id)->update($token);
                }
                else
                {
                    $insert['item_id']      = $item_id;
                    $insert['token_id']     = $token['token_id'];
                    $insert['amount']       = $token['amount'];
                    Tbl_item_token::insert($insert);
                }
            }
            $return['item_id']       = $item_id;
            $return['status']        = 'success';
            $return['message']       = 'Item successfully updated.';
            $return['call_function'] = 'success_item';

            AuditTrail::record_logs("Edited", "item", $item_id , serialize($old_data), serialize($update));

            if(count(session('landing_cost')) > 0)
            {
                LandingCost::insert_cost_item($item_id, $shop_id, session('landing_cost'));
            }
        }
        return $return;
    }
    public static function archive($shop_id, $item_id)
    {
        $update["archived"] = 1;
        $update["item_date_archived"] = Carbon::now();
        Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->update($update);

        $new_data = Self::array_item_data($shop_id, $item_id);
        AuditTrail::record_logs("Archived", "item", $item_id ,"", serialize($new_data));
    }
    public static function restore($shop_id, $item_id)
    {
        $update["archived"] = 0;
        $update["item_date_archived"] = null;
        Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->update($update);

        $new_data = Self::array_item_data($shop_id, $item_id);
        AuditTrail::record_logs("Restored", "item", $item_id ,"", serialize($new_data));
    }

    public static function create_bundle_validation($shop_id, $item_type, $insert, $_item)
    {
        $return = null;

        $rules['item_name'] = 'required';
        $rules['item_sku'] = 'required';
        $rules['item_price'] = 'required';
        $rules['item_category_id'] = 'required';

        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            foreach ($validator->messages()->all('') as $keys => $message)
            {
                // $return .= $message."<br>";
            }
        }
        if($shop_id)
        {
            $shop_data = Tbl_shop::where('shop_id',$shop_id)->first();
            if(!$shop_data)
            {
                $return .= 'Your account does not exist. <br>';                
            }
        }
        if($item_type)
        {
            $type_data = Tbl_item_type::where('item_type_id',$item_type)->first();
            if(!$type_data)
            {
                $return .= 'Item type does not exist. <br>';            
            }
        }
        if($insert['item_category_id'] != 0)
        {
            $category_data = Tbl_category::where('type_id',$insert['item_category_id'])->where('type_shop',$shop_id)->first();
            if(!$category_data)
            {
                $return .= 'Category does not exist. <br>';            
            }            
        }
        if($insert['item_income_account_id'] != 0)
        {
            $income_data = Tbl_chart_of_account::where('account_id',$insert['item_income_account_id'])->where('account_shop_id',$shop_id)->first();
            if(!$income_data)
            {
                $return .= 'Income account does not exist. <br>';            
            }            
        }
        // if(count($_item) <= 0)
        // {
        //     $return .= 'Please add items to bundle. <br>';  
        // }

        return $return;
    }
    public static function create_bundle($shop_id, $item_type, $insert, $_item)
    {
        $insert['shop_id'] = $shop_id;
        $insert['item_type_id'] = $item_type;
        $insert['item_date_created'] = Carbon::now();
       
        $item_id = Tbl_item::insertGetId($insert);
        $item = Self::array_item_data($shop_id, $item_id);
        AuditTrail::record_logs("Edited", "item", $item_id ,"", serialize($item));

        if(count($_item) > 0)
        {
            foreach ($_item as $key => $value) 
            {
                $ins_item['bundle_bundle_id'] = $item_id;
                $ins_item['bundle_item_id'] = $value['item_id'];
                $ins_item['bundle_qty'] = $value['quantity'];

                Tbl_item_bundle::insert($ins_item);
            }
        }
        $return['item_id']       = $item_id;
        $return['status']        = 'success';
        $return['message']       = 'Item successfully created.';
        $return['call_function'] = 'success_item';       

        return $return;
    }
    public static function modify_bundle($shop_id, $item_id, $insert, $_item)
    {  
        $old_data = Self::array_item_data($shop_id, $item_id);
        $insert['shop_id'] = $shop_id;
        Tbl_item::where('item_id',$item_id)->update($insert);
        $new_data = Self::array_item_data($shop_id, $item_id);
        AuditTrail::record_logs("Edited", "item", $item_id , serialize($old_data), serialize($new_data));
        Tbl_item_bundle::where('bundle_bundle_id',$item_id)->delete();

        if(count($_item) > 0)
        {
            foreach ($_item as $key => $value) 
            {
                $ins_item['bundle_bundle_id'] = $item_id;
                $ins_item['bundle_item_id'] = $value['item_id'];
                $ins_item['bundle_qty'] = $value['quantity'];

                Tbl_item_bundle::insert($ins_item);
            }
        }

        $return['item_id']       = $item_id;
        $return['status']        = 'success';
        $return['message']       = 'Item successfully updated.';
        $return['call_function'] = 'success_item';       

        return $return;

    }
    /* ITEM CRUD END */


    /* READ DATA */
    public static function get($shop_id = 0, $paginate = false, $archive = 0, $sort_table = false, $column_name = null, $order_by = null)
    {
        $query = Tbl_item::where("tbl_item.shop_id", $shop_id)->type()->membership()->um_item();

        
        $check_settings = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);

        if(session("get_inventory"))
        {
            $query = $query->recordloginventory(session("get_inventory"));
        }
        // dd($query->toSql());
        // // dd(session("get_inventory"));
        // /* SEARCH */
        // /*if (session("get_search")) 
        // {           
        //     $query = $query->searchItem(session("get_search"));
        // }*/
        if(session("get_search"))
        {
            $search_keyword = session("get_search");
            $query->where(function($q) use ($search_keyword)
            {   
                $q->orWhere("tbl_item.item_id", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_sku", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_name", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_sales_information", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_purchasing_information", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_quantity", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_price", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_cost", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_type_id", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_category_id", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_barcode", "LIKE", "%$search_keyword%");
            });

            // $query->search($search_keyword);

        }

        /* FILTER BY TYPE */
        if (session("get_filter_type")) 
        {
            $query = $query->where("tbl_item.item_type_id", session("get_filter_type"));
        }

        /* FILTER BY CATEGORY */
        if (session("get_filter_category")) 
        {   
            $check_parent_category = Self::check_category(session("get_filter_category"));
            $check_parent_category[session("get_filter_category")] = session("get_filter_category");
            $query = $query->whereIn("tbl_item.item_category_id", $check_parent_category);
        }

        if($archive != 'all')
        {
            $query = $query->where("tbl_item.archived", $archive);
        }
        // dd($query->get());
        /* CHECK IF THERE IS PAGINATION */

        if($paginate)
        {
            if($sort_table == true && $column_name && $order_by)
            {
                $_item = $query->groupBy('tbl_item.item_id')->orderBy($column_name, $order_by)->paginate($paginate);
                session(['item_pagination' => $_item->render()]);
            }
            else
            {
                $_item = $query->groupBy('tbl_item.item_id')->orderby('item_name',"ASC")->paginate($paginate);
                session(['item_pagination' => $_item->render()]);
            }
        }
        else
        {
            $_item = $query->groupBy('tbl_item.item_id')->get();
        }

        //uncomment this need item per warehouse
        // if($check_settings == 1)
        // {
        //     $item = Self::item_per_warehouse($shop_id, $_item);
        // }

        /* ITEM ADDITIONAL DATA */
        foreach($_item as $key => $item)
        {
            $item = Self::add_info($item);
            $_item_new[$key] = $item;
            $_item_new[$key]->inventorylog = Warehouse2::get_item_qty(session("get_inventory"), $item->item_id);
            $_item_new[$key]->inventory_count = UnitMeasurement::um_view(Warehouse2::get_item_qty(session("get_inventory"), $item->item_id), $item->item_measurement_id);
            $_item_new[$key]->average_cost = Tbl_item_average_cost_per_warehouse::where('iacpw_shop_id', $shop_id)->where('iacpw_warehouse_id',$warehouse_id)->where('iacpw_item_id',$item->item_id)->value('iacpw_ave_cost');
            $_item_new[$key]->stock_onhand = Warehouse2::get_item_qty(session("get_inventory"), $item->item_id);
        }
        $return = isset($_item_new) ? $_item_new : null;  

        Self::get_clear_session();
        return $return;
    }
    public static function check_category($category_id, $return = null)
    {
        $data = Tbl_category::where("type_parent_id", $category_id)->get();
        if(count($data) > 0)
        {
            foreach ($data as $key => $value) 
            {
                $return[$value->type_id] = $value->type_id;
                $return = Self::get_category($value->type_id, $return);
            }
        }
        return $return;
    }

    public static function get_category($category_id, $return = null)
    {
        $cid = Tbl_category::where("type_parent_id", $category_id)->value("type_id");
        if($cid)
        {
            $return[$cid] = $cid;             
        }
        $return = Self::check_category($category_id, $return);
        return $return;
    }
    public static function get_per_warehouse($shop_id, $warehouse_id, $archive = 0)
    {
        $query = Tbl_item::inventorylog()->where("tbl_item.shop_id", $shop_id)->where('record_warehouse_id',$warehouse_id)->where("tbl_item.archived", $archive)->type()->groupBy('tbl_item.item_id')->get();
        return $query;
    }
    public static function get_item_info($shop_id, $item_id)
    {
        return Tbl_item::Um_item()->where("shop_id", $shop_id)->where("item_id", $item_id)->first();
    }
    public static function get_all_item()
    {
        return Tbl_item::where("shop_id", Item::getShopId())->where("archived", 0)->get();
    }
    public static function info($item_id)
    {
        $query = Tbl_item::type()->um_multi()->where("item_id", $item_id);

        if(session("get_inventory"))
        {
            $query = $query->inventory(session("get_inventory"));
        }

        $item = $query->first();

        Self::add_info($item);
        Self::get_clear_session();
        return $item;
    }

    public static function get_inventory($warehouse_id)
    {
        session(['get_inventory' => $warehouse_id]);
    }
    public static function get_search($keyword)
    {
        session(['get_search' => $keyword]);
    }
    public static function get_pagination()
    {
        $pagination = session("item_pagination");
        session(['item_pagination' => null]);
        return $pagination;
    }
    public static function get_add_markup()
    {
        session(['get_add_markup' => true]);
    }
    public static function get_add_display()
    {
        session(['get_add_display' => true]);
    }
    public static function get_filter_type($id)
    {
        session(['get_filter_type' => $id]);
    }
    public static function get_filter_category($id)
    {
        session(['get_filter_category' => $id]);
    }
    public static function get_apply_price_level($price_level_id)
    {
        session(['get_apply_price_level' => $price_level_id]);
    }

    public static function get_clear_session()
    {
        $store["get_add_markup"] = null;
        $store["get_add_display"] = null;
        $store["get_filter_type"] = null;
        $store["get_filter_category"] = null;
        $store["get_apply_price_level"] = null;
        $store["get_search"] = null;
        $store["get_inventory"] = null;
        session($store);
    }

    public static function add_info($item)
    {
        if(session("get_apply_price_level"))
        {
            $item = Self::add_apply_price_level($item);
        }

        if(session("get_add_markup"))
        {
            $item = Self::add_info_markup($item);
        }

        if(session("get_add_display"))
        {
            $item = Self::add_info_display($item);
        }


        return $item;
    }
    public static function add_apply_price_level($item)
    {
        $price_level_id         = session("get_apply_price_level");
        $check_price_level      = Tbl_price_level::where("price_level_id", $price_level_id)->first();

        if($check_price_level)
        {
            if($check_price_level->price_level_type == "per-item")
            {
                $check_item = Tbl_price_level_item::where("price_level_id", $price_level_id)->where("item_id", $item->item_id)->first();
            
                if($check_item)
                {
                    $new_computed_price     = $check_item->custom_price;
                }
                else
                {
                    $new_computed_price     = $item->item_price;
                }
            }
            else
            {
                $percentage_mode        = $check_price_level->fixed_percentage_mode;
                $percentage_value       = $check_price_level->fixed_percentage_value;
                $percentage_source      = $check_price_level->fixed_percentage_source;
                $applied_multiplier     = ($percentage_mode == "lower" ? ($percentage_value * -1) : $percentage_value);
                $price_basis            = ($percentage_source == "standard price" ? $item->item_price : $item->item_cost);
                $addend                 = $price_basis * ($applied_multiplier / 100);
                $new_computed_price     = $price_basis + $addend; 
            }
        }
        else
        {
            $new_computed_price     = $item->item_price;
        }

        $item->original_item_price = $item->item_price;
        $item->item_price = $new_computed_price;

        return $item;
    }
    public static function add_info_markup($item)
    {
        $item->computed_price   = $item->item_price;
        $item->markup           = $item->item_price - $item->item_cost;
        $item->display_markup   = Currency::format($item->markup);
        return $item;
    }
    public static function add_info_display($item)
    {
        $item->display_price   = Currency::format($item->item_price);
        $item->display_cost   = Currency::format($item->item_cost);
        return $item;
    }
    /* READ DATA END */
    public static function list_price_level($shop_id, $type = null, $search_keyword = null)
    {
        $_price_level = Tbl_price_level::where("shop_id", $shop_id)->orderby('price_level_id', 'DESC');

        if($type)
        {
            $_price_level->where('price_level_type',$type);
        }
        if($search_keyword)
        {
            $_price_level->where('price_level_name','LIKE','%'.$search_keyword.'%');
        }
        //dd($_price_level->paginate(5));
        return $_price_level->paginate(5);
    }
    public static function insert_price_level($shop_id, $price_level_name, $price_level_type, $fixed_percentage_mode, $fixed_percentage_source, $fixed_percentage_value)
    {  
        $insert_price_level["price_level_name"] = $price_level_name;
        $insert_price_level["price_level_type"] = $price_level_type;
        $insert_price_level["shop_id"] = $shop_id;
        
        if($price_level_type == "fixed-percentage")
        {

            $insert_price_level["fixed_percentage_mode"] = $fixed_percentage_mode;
            $insert_price_level["fixed_percentage_source"] = $fixed_percentage_source;
            $insert_price_level["fixed_percentage_value"] = $fixed_percentage_value;
        }

        return Tbl_price_level::insertGetId($insert_price_level);
    }
    public static function update_price_level($shop_id, $price_level_id, $price_level_name, $price_level_type, $fixed_percentage_mode, $fixed_percentage_source, $fixed_percentage_value)
    {  
        $update_price_level["price_level_name"] = $price_level_name;
        $update_price_level["price_level_type"] = $price_level_type;
        
        if($price_level_type == "fixed-percentage")
        {

            $update_price_level["fixed_percentage_mode"] = $fixed_percentage_mode;
            $update_price_level["fixed_percentage_source"] = $fixed_percentage_source;
            $update_price_level["fixed_percentage_value"] = $fixed_percentage_value;
        }
        Tbl_price_level::where('price_level_id',$price_level_id)->update($update_price_level);
        return $price_level_id;
    }
    public static function delete_price_level_item($price_level_id)
    {  
        Tbl_price_level_item::where('price_level_id',$price_level_id)->delete();
    }
    public static function insert_price_level_item($shop_id, $price_level_id, $_item)
    {  
        $_insert = array();

        foreach($_item as $item_id => $custom_price)
        {
            if($custom_price != "")
            {
                $insert["price_level_id"]   = $price_level_id;
                $insert["item_id"]          = $item_id;
                $insert["custom_price"]     = $custom_price;
                array_push($_insert, $insert);
            }
        }

        if($_insert)
        {
            Tbl_price_level_item::insert($_insert);
        }
    }
    public static function price_level_info($shop_id, $price_level_id)
    {
        return Tbl_price_level::where('shop_id',$shop_id)->where('price_level_id',$price_level_id)->first();
    }
    public static function price_level_info_item($price_level_id)
    {
        $data = Tbl_price_level_item::where('price_level_id',$price_level_id)->get();
        $return = null;
        foreach ($data as $key => $value) 
        {
            $return[$value->item_id] = $value->custom_price;
        }
        return $return;
    }
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    public static function getUserid()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_id');
    }
    public static function generate_barcode($barcode = 0)
    {
        $return = $barcode;
        $chk =  Tbl_item::where("item_barcode",$return)->where('shop_id',Item::getShopId())->get();
        if(count($chk) > 1)
        {
            $num = '1234567890';
            $return = str_shuffle($return);
        }

        return $return;
    }

    public static function get_item_price_history_v2($shop_id, $date_from = '', $date_to = '', $warehouse_id)
    {
        $get_item = Tbl_item::where("shop_id", $shop_id)->get();

        $return = null;
        foreach ($get_item as $key => $value) 
        {
            $get_avecost = Tbl_item_average_cost_per_warehouse::where('iacpw_item_id', $value->item_id)->where('iacpw_warehouse_id', $warehouse_id)->where('iacpw_shop_id',$shop_id)->first();

            $return[$key] = $value;
            if($get_avecost)
            {
                $return[$key]->average_cost = $get_avecost->iacpw_ave_cost;
            }
            $return[$key]->item_price_history = Self::get_price_history($value->item_id, $date_from, $date_to);
        }
        return $return;
    }
    public static function get_price_history($item_id, $date_from = '', $date_to = '')
    {
        return Tbl_item_pricing_history::user()->where("pricing_item_id", $item_id)->whereBetween("pricing_created",[$date_from, $date_to])->orderBy("pricing_created", "DESC")->get();
    }
    public static function get_item_price_history($item_id, $show_all = false)
    {
        $item_data = Item::get_item_details($item_id);

        $item_history = Tbl_item_price_history::where('item_id',$item_id)->orderBy('updated_at','DESC')->get();

        $text = null;
        $return = null; 
        if($show_all == true)
        {
            $return .= "<table class='table table-bordered'>";
            $return .= "<thead>";
            $return .= "<tr>";
            $return .= "<th>Date</th>";
            $return .= "<th>Type</th>";
            $return .= "<th>Amount</th>";
            $return .= "<th></th>";
            $return .= "</tr>";
            $return .= "</thead><tbody>";
        }
        foreach ($item_history as $key => $value) 
        {
            if($show_all == true)
            {
                $return .= "<tr><td>".date('m/d/Y',strtotime($value->updated_at))."</td>";
                $return .= "<td>".strtoupper(str_replace('_', ' ', $value->price_type))."</td>";
                $return .= "<td>".currency("PHP ",$value->price)."</td>";
                $return .= "<td><a class='click_delete' href='javascript:' history-id='".$value->item_price_history_id."'>Delete</td></tr>";
            }
            if($show_all == false)
            {
                $return .= date('m/d/Y',strtotime($value->updated_at))." - ".currency("PHP ",$value->price)."<br>";
            }
        }

        if($show_all == false)
        {
            $len = strlen($return);
            if($len > 25)
            {
                $text = (substr($return, 0, 30)."...<a class='popup' size='md' link='/member/item/view_item_history/".$item_id."'>View</a>");
            }
        }
        if($show_all == true)
        {   
            $return .= "</tbody></table>";
            $text = $return;
        }

        // $return = "";
        // $text = "";
        // $trail = Tbl_audit_trail::where("source","item")->where("source_id",$item_id)->orderBy("created_at","DESC")->get();
       


        // $last = null;
        // foreach ($trail as $key => $value)
        // {
        //     $item_qty = 1;
        //     if(Purchasing_inventory_system::check())
        //     {
        //         $item_qty = UnitMeasurement::um_qty($item_data->item_measurement_id, 1);
        //     }
        //     $old[$key] = unserialize($value->old_data);
        //     $amount = 0;
        //     if($old)
        //     {
        //         if($item_data->item_price != $old[$key]["item_price"] && $old[$key]["item_price"] != 0)
        //         {
        //             $len = strlen($return);
                    
        //             $amount = $old[$key]["item_price"] * $item_qty;
        //             if ($last != $amount) 
        //             {
        //                 $return .= date('m/d/Y',strtotime($value->created_at))." - ".currency("PHP ",$amount)."<br>";

        //                 if($show_all == true)
        //                 {
        //                     $return .= " - <a class='click_delete' href='javascript:' history-id='".$value->audit_trail_id."'>&nbsp;&nbsp;Delete</a><br>";
        //                 }

        //                 $text = $return;
        //                 if($show_all == false)
        //                 {
        //                     if($len > 25)
        //                     {
        //                         $text = (substr($text, 0, 30)."...<a class='popup' size='sm' link='/member/item/view_item_history/".$item_id."'>View</a>");
        //                     }
        //                 }
        //             }
        //         }
        //         $last = $amount;
        //     }
        // }  
        return $text;
    }
    public static function get_item_details($item_id = 0)
    {
        $data = Tbl_item::um_item()->category()->where("item_id",$item_id)->first();

        if($data->item_type_id == 4)
        {
            $data->item_price = Item::get_item_bundle_price($item_id);
        }

        return $data;
    }

    public static function get_item_in_bundle($item_id = 0)
    {
        $items = array();
        if($item_id != 0)
        {
            $items = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
        }
        return $items;
    }    
    public static function get_item_type($item_id = 0)
    {
        $type = null;
        if($item_id != 0)
        {
            $type = Tbl_item::where("item_id",$item_id)->value("item_type_id");
        }
        return $type;
    }
    public static function get_item_type_list()
    {
        return Tbl_item_type::where("archived", 0)->get();
    }
    public static function get_item_type_id($type_name = '') // Inventory, Non-Inventory, Service, Bundle, Membership
    {        
        $id = Tbl_item_type::where("item_type_name", $type_name)->value('item_type_id');        
        if(!$id)
        {
            $id = 5; //For Membership (Temporary)
        }
        return $id;
    }
	public static function breakdown($_item='')
	{
		$data = '';
        $total = 0;
        foreach($_item as $key => $item){
            $data['item'][$key]['product_name'] = $item->product_name;
            $data['item'][$key]['variant_product_id'] = $item->variant_product_id;
            $data['item'][$key]['variant_id'] = $item->variant_id;
            $data['item'][$key]['tbl_order_item_id'] = $item->tbl_order_item_id;
            $data['item'][$key]['image_path'] = $item->image_path;
            $data['item'][$key]['variant_sku'] = $item->variant_sku;
            $data['item'][$key]['item_amount_def'] = $item->item_amount;
            $data['item'][$key]['item_amount'] = number_format($item->item_amount,2);
            $data['item'][$key]['quantity'] = $item->quantity;
            $data['item'][$key]['discount'] = $item->item_discount;
            $data['item'][$key]['discount_reason'] = $item->item_discount_reason;
            $data['item'][$key]['discount_var'] = $item->item_discount_var;
            $data['item'][$key]['variant_charge_taxes'] = $item->variant_charge_taxes;
            $discount_amount = 0;
            $amount_to_show = 0;
            if($item->item_discount_var == 'amount'){
                $discount_amount = $item->discount;
            }
            else{
                $discount_amount = ($item->item_discount / 100) * $item->item_amount;
            }
            $discount_amount_def = $discount_amount;
            $variant_id = $item->variant_id;
            if($discount_amount == 0){
                $discount_amount = '';
                $amount_to_show = '';
            }
            else{
                $discount_amount = number_format($discount_amount,2);
                $amount_to_show = number_format($item->item_amount,2);
            }
            $data['item'][$key]['amount_to_show'] = $amount_to_show;
            $less_discount = $item->item_amount - $discount_amount;
            $data['item'][$key]['less_discount'] = number_format($less_discount,2);
            $data['item'][$key]['less_discount_def'] = $less_discount;
            $data['item'][$key]['discount_amount'] = $discount_amount;
            $data['item'][$key]['discount_amount_def'] = $discount_amount_def;
            $data['item'][$key]['total_amount'] = number_format($item->quantity * $less_discount,2);
            $data['item'][$key]['total_amount_def'] = $item->quantity * $less_discount;
            $variat = Tbl_variant::VariantOnly($variant_id)->get();
            $strvariant = '';
            foreach($variat as $var){
                if($strvariant != ''){
                    $strvariant.=' / ';
                }
                $strvariant.=$var->option_value;
            }

            $data['item'][$key]['variant_name'] = $strvariant;
            $total += ($item->quantity * $less_discount);

        }
        $data['total'] = $total;
        // dd($data);
        return $data;
	}


    public static function apply_additional_info_to_array($_item)
    {
        $_new_item = null;

        foreach($_item as $key => $item)
        {
            $_new_item[$key] = $item;
            $_new_item[$key] = Self::item_additional_info($_new_item[$key]);
        }

        return $_new_item;
    }

    public static function insert_item_discount($item_info)
    {
        $chck = Tbl_item_discount::where("discount_item_id",$item_info["item_id"])->first();

        if($chck == null)
        {
            if($item_info["item_discount_value"] >= 1)
            {
                $insert["discount_item_id"] = $item_info["item_id"];
                $insert["item_discount_value"] = $item_info["item_discount_value"];
                $insert["item_discount_date_start"] = date("Y-m-d g:i:s",strtotime($item_info["item_discount_date_start"]));
                $insert["item_discount_date_end"]  =  date("Y-m-d g:i:s",strtotime($item_info["item_discount_date_end"]));

                Tbl_item_discount::insert($insert);
            }   
        }
        else
        {
            if($item_info["item_discount_value"] <= 0)
            {
                Tbl_item_discount::where("item_discount_id",$chck->item_discount_id)->delete();
                Tbl_item_discount::where("item_discount_value",0)->delete();
            }
            else
            {
                $insert["item_discount_value"] = $item_info["item_discount_value"];
                $insert["item_discount_date_start"] = date("Y-m-d g:i:s",strtotime($item_info["item_discount_date_start"]));
                $insert["item_discount_date_end"]  =  date("Y-m-d g:i:s",strtotime($item_info["item_discount_date_end"]));

                Tbl_item_discount::where("discount_item_id",$item_info["item_id"])->update($insert);
            }
        }
    }

    public static function get_returnable_item($for_tablet = false)
    {        
        $shop_id = Item::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        $data = Tbl_item::category()->where("shop_id",$shop_id)
                                    ->where("tbl_item.archived",0)
                                    ->where("is_mts",1)
                                    ->groupBy("tbl_item.item_id")
                                    ->get();  
        foreach ($data as $key => $value) 
        {
            if($value->item_type_id == 4)
            {
               $data[$key]->item_price = Item::get_item_bundle_price($value->item_id);   
               $data[$key]->item_cost= Item::get_item_bundle_cost($value->item_id); 
            }
        }       
 
        return $data;        
    }



    public static function pis_get_all_category_item_transaction($type = array(1,2,3,4,6))
    {        
        $shop_id = Item::getShopId();
        $_category = Tbl_category::where("type_shop",$shop_id)->where("type_parent_id",0)->where("is_mts",0)->where("archived",0)->get()->toArray();

        foreach($_category as $key =>$category)
        {
            $_category[$key]['item_list']   = Tbl_item::where("item_category_id",$category['type_id'])->whereIn("item_type_id",$type)->where("archived",0)->get()->toArray();
            foreach($_category[$key]['item_list'] as $key1=>$item_list)
            {
                //  //cycy
                if($item_list['item_type_id'] == 4)
                {
                   $_category[$key]['item_list'][$key1]['item_price'] = Item::get_item_bundle_price($item_list['item_id']); 
                   $_category[$key]['item_list'][$key1]['item_cost'] = Item::get_item_bundle_cost($item_list['item_id']); 
                }
                $_category[$key]['item_list'][$key1]['multi_price'] = Tbl_item::multiPrice()->where("item_id", $item_list['item_id'])->get()->toArray();
            }
            $_category[$key]['subcategory'] = Item::pis_get_item_per_sub($category['type_id'], $type);
        }

        return $_category;
    }
    public static function pis_get_item_per_sub($category_id, $type = array())
    {
        $_category  = Tbl_category::where("type_parent_id",$category_id)->where("archived",0)->where("is_mts",0)->get()->toArray();
        foreach($_category as $key =>$category)
        {
            $_category[$key]['item_list']   = Tbl_item::where("item_category_id",$category['type_id'])->where("archived",0)->whereIn("item_type_id",$type)->get()->toArray();
            foreach($_category[$key]['item_list'] as $key1=>$item_list)
            {
                if($item_list['item_type_id'] == 4)
                {
                   $_category[$key]['item_list'][$key1]['item_price'] = Item::get_item_bundle_price($item_list['item_id']); 
                   $_category[$key]['item_list'][$key1]['item_cost'] = Item::get_item_bundle_cost($item_list['item_id']); 
                }
                $_category[$key]['item_list'][$key1]['multi_price'] = Tbl_item::multiPrice()->where("item_id", $item_list['item_id'])->get()->toArray();
            }
            $_category[$key]['subcategory'] = Item::get_item_per_sub($category['type_id'], $type);
        }

        return $_category;
    } 
    public static function get_all_category_item($type = array(1,2,3,4,6), $for_tablet = false, $get_inventory = false, $get_vendor = false, $is_transaction = false, $get_ave_cost = false)
    {
        $shop_id = Item::getShopId(); 
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        } 

        $check_settings = AccountingTransaction::settings($shop_id, 'allow_transaction');
        
        $_category = Tbl_category::where("type_shop",$shop_id)->where("type_parent_id",0)->where("archived",0)->get()->toArray();

        foreach($_category as $key =>$category)
        {
            $ismerchant = Merchant::ismerchant();
            if($ismerchant == 1)
            {
                $user_id = Merchant::getuserid();
                $_category[$key]['item_list'] = Tbl_item::where("item_category_id",$category['type_id'])
                ->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
                ->where('item_merchant_requested_by', $user_id)
                ->whereIn("item_type_id",$type)->where("archived",0)->get()->toArray(); 
            }
            else
            {
                $_category[$key]['item_list']   = Tbl_item::category()->where("item_category_id",$category['type_id'])->whereIn("item_type_id",$type)->where("tbl_item.archived",0);  
                //$_category[$key]['item_list'] = $_category[$key]['item_list']/*->whereNull('item_warehouse_id')*/->get();
                if($is_transaction && $check_settings)
                {
                    $_category[$key]['item_list'] = $_category[$key]['item_list']->get();
                    // $_category[$key]['item_list'] = Self::item_per_warehouse($shop_id, $_category[$key]['item_list']);  
                    //dd($_category[$key]['item_list']);
                }
                else
                {
                    $_category[$key]['item_list'] = $_category[$key]['item_list']->get()->toArray();
                }
            }
            foreach($_category[$key]['item_list'] as $key1=>$item_list)
            {
                //  //cycy
                if($item_list['item_type_id'] == 4 || $item_list['item_type_id'] == 5)
                {
                   $_category[$key]['item_list'][$key1]['item_cost'] = Item::get_item_bundle_cost($item_list['item_id']); 
                }
                $_category[$key]['item_list'][$key1]['multi_price'] = Tbl_item::multiPrice()->where("item_id", $item_list['item_id'])->get()->toArray();

                $_category[$key]['item_list'][$key1]['inventory_count'] = 0;
                if($item_list['item_type_id'] == 1)
                {
                    $_category[$key]['item_list'][$key1]['inventory_count'] = Warehouse2::get_item_qty(Warehouse2::get_current_warehouse($shop_id), $item_list['item_id']);
                }
                $_category[$key]['item_list'][$key1]['item_inventory'] = null;
                if($get_inventory)
                {
                    // $_category[$key]['item_list'][$key1]['item_inventory'] = Self::item_inventory_report($shop_id, $item_list['item_id']);
                }
                $_category[$key]['item_list'][$key1]['item_ave_cost'] = null;
                if($get_ave_cost)
                {
                    $_category[$key]['item_list'][$key1]['item_ave_cost'] = Self::item_ave_cost($shop_id, $item_list['item_id']);
                }
                $_category[$key]['item_list'][$key1]['item_vendor'] = null;
                if($get_vendor)
                {
                    $_category[$key]['item_list'][$key1]['item_vendor'] = Self::item_vendor($shop_id, $item_list['item_id']);
                }
                $_category[$key]['item_list'][$key1]['warehouse'] = array();
                //dd($shop_id);
                if($is_transaction && $check_settings)
                {
                    $_category[$key]['item_list'][$key1]['warehouse'] = Self::item_warehouse($shop_id, $item_list['item_id']);
                }

                $_category[$key]['item_list'][$key1]['_range_discount'] = AccountingTransaction::get_range_discount($shop_id, $item_list['item_id']);
            }
            $_category[$key]['subcategory'] = Item::get_item_per_sub($category['type_id'], $type, $get_inventory);
        }
        // dd($_category);
        return $_category;
    }

    public static function item_per_warehouse($shop_id, $item_list_array)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        foreach ($item_list_array as $key => $value)
        {
            $get_old = $value->item_warehouse_id;

            /*IF ITEM WAREHOUSE ID IS NOT NULL*/
            if($get_old)
            {                           
                $arr = unserialize($get_old);

                /*IF WAREHOUSE ID IS ALREADY EXISTED IN ITEM WAREHOUSE ID*/
                if(in_array($warehouse_id, $arr, TRUE))
                {
                    $item_list_array[$key] = $value; 
                }
                else/*IF WAREHOUSE ID IS NOT EXISTED IN ITEM WAREHOUSE ID*/
                {
                    unset($item_list_array[$key]);   
                }
            }
        }
        return $item_list_array;
    }

    public static function item_warehouse($shop_id, $item_id)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        $item_warehouse = Tbl_item::where('item_id', $item_id)->where('shop_id', $shop_id)->first();

        //dd($item_warehouse);
        $return = array();
        if($item_warehouse)
        { 
            $wh_id = unserialize($item_warehouse->item_warehouse_id); 

            if($wh_id > 0)
            {
                
                foreach ($wh_id as $key => $value)
                {
                    $warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('warehouse_id', $value)->first();
                    $return[$key]['warehouse_id'] = $warehouse->warehouse_id;  
                    $return[$key]['warehouse_name'] = $warehouse->warehouse_name;              
                }
            }
        }
        else
        {
            
        }
        return $return;
    }
    public static function item_vendor($shop_id, $item_id)
    {
        $item_vendor = Tbl_vendor_item::where('tag_item_id', $item_id)->get();
        $return = null;
        if(count($item_vendor) > 0)
        {
            foreach ($item_vendor as $key => $value) 
            {
                $vendor = Tbl_vendor::where('vendor_id', $value->tag_vendor_id)->where('vendor_shop_id', $shop_id)->first();
                $return[$key]['v_id'] = $vendor->vendor_id;
                $return[$key]['v_name'] = $vendor->vendor_company != ''? $vendor->vendor_company : ucwords($vendor->vendor_title_name.' '.$vendor->vendor_first_name.' '.$vendor->vendor_middle_name.' '.$vendor->vendor_last_name);
                //$return[$key]['v_item_id'] = $item_id;
            }    
        }
        return $return;
    }
    public static function get_item_per_sub($category_id, $type = array(), $get_inventory = false)
    {
        $_category  = Tbl_category::where("type_parent_id",$category_id)->where("archived",0)->get()->toArray();
        foreach($_category as $key =>$category)
        {
            $ismerchant = Merchant::ismerchant();
            if($ismerchant == 1)
            {
                $user_id = Merchant::getuserid();
                $_category[$key]['item_list'] = Tbl_item::where("item_category_id",$category['type_id'])
                ->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
                ->where('item_merchant_requested_by', $user_id)
                ->whereIn("item_type_id",$type)->where("archived",0)->get()->toArray(); 
            }
            else
            {
                $_category[$key]['item_list']   = Tbl_item::category()->where("item_category_id",$category['type_id'])->where("tbl_item.archived",0)->whereIn("item_type_id",$type)->get()->toArray();
            }
            foreach($_category[$key]['item_list'] as $key1=>$item_list)
            {
                if($item_list['item_type_id'] == 4)
                {
                   $_category[$key]['item_list'][$key1]['item_cost'] = Item::get_item_bundle_cost($item_list['item_id']); 
                }
                $_category[$key]['item_list'][$key1]['multi_price'] = Tbl_item::multiPrice()->where("item_id", $item_list['item_id'])->get()->toArray();

                $_category[$key]['item_list'][$key1]['item_inventory'] = null;
                if($get_inventory)
                {
                    $_category[$key]['item_list'][$key1]['item_inventory'] = Self::item_inventory_report($category['type_shop'], $item_list['item_id']);
                }
                $_category[$key]['item_list'][$key1]['inventory_count'] = 0;
                if($item_list['item_type_id'] == 1)
                {
                    $_category[$key]['item_list'][$key1]['inventory_count'] = Warehouse2::get_item_qty(Warehouse2::get_current_warehouse(Item::getShopId()), $item_list['item_id']);
                }
                
                $_category[$key]['item_list'][$key1]['_range_discount'] = AccountingTransaction::get_range_discount($category['type_shop'], $item_list['item_id']);
            }
            $_category[$key]['subcategory'] = Item::get_item_per_sub($category['type_id'], $type, $get_inventory);
        }

        return $_category;
    } 
   
    public static function get_all_item_sir($sir_id, $for_tablet = false)
    {
        $shop_id = Item::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        $item = Tbl_sir_item::select_sir_item()->where("tbl_sir_item.sir_id",$sir_id)->groupBy("tbl_item.item_category_id")->get();
        foreach ($item as $key1 => $value) 
        {         
            $_category[$key1] = collect(Tbl_category::where("type_shop",$shop_id)->where("archived",0)->where("type_id",$value->item_category_id)->first())->toArray();  
        }

        foreach($_category as $key => $category)
        {
            $_category[$key]['item_list']   = Tbl_sir_item::select_sir_item()->where("tbl_sir_item.sir_id",$sir_id)->where("item_category_id",$category['type_id'])->groupBy("tbl_sir_item.item_id")->get()->toArray();
            foreach($_category[$key]['item_list'] as $key3 => $item_list)
            {
                if($item_list['item_type_id'] == 4)
                {
                   $_category[$key]['item_list'][$key3]['item_price'] = Item::get_item_bundle_price($item_list['item_id']); 
                }
            }
            $_category[$key]['subcategory'] = Item::get_item_per_sub_sir($category['type_id'],$sir_id);        
        }

        return collect($_category)->toArray();
    }

    public static function get_item_per_sub_sir($category_id, $sir_id)
    {
        $_category  = Tbl_category::where("type_parent_id",$category_id)->where("archived",0)->get()->toArray();
        foreach($_category as $key =>$category)
        {
            $_category[$key]['item_list']   = Tbl_sir_item::select_sir_item()->where("tbl_sir_item.sir_id",$sir_id)->where("item_category_id",$category['type_id'])->groupBy("tbl_sir_item.item_id")->get()->toArray();

            foreach ($_category[$key]['item_list'] as $key3 => $value3)
            {               
               if($value3['item_type_id'] == 4)
                {
                   $_category[$key]['item_list'][$key3]['item_price'] = Item::get_item_bundle_price($value3['item_id']); 
                }
            }
            $_category[$key]['subcategory'] = Item::get_item_per_sub_sir($category['type_id'],$sir_id);
        }

        return collect($_category)->toArray();
    }
    public static function bundle_count($item_id, $warehouse_id)
    {
        $_item = Item::get_item_from_bundle($item_id, $warehouse_id);
        $limit_array = array();

        foreach($_item as $item)
        {
            $ans = $item->inventory_count / $item->bundle_qty;
            array_push($limit_array, (int)$ans);
        }

        if($limit_array)
        {
            return min($limit_array);
        }
        else
        {
            return -1;
        }

        
    }
    public static function get_item_bundle_price($item_id = null)
    {
        $price = 0;
        $item_type = Tbl_item::where("item_id",$item_id)->value("item_type_id");
        if($item_id != null && $item_type == 4)
        {
            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
            foreach ($bundle_item as $key => $value) 
            {
                $item_price =  Purchasing_inventory_system::get_item_price($value->bundle_item_id);
                $um_qty = UnitMeasurement::um_qty($value->bundle_um_id);

                $price += $item_price * ($um_qty * $value->bundle_qty);
            }
        }
        return $price;
    }   
    public static function get_item_bundle_cost($item_id = null)
    {
        $cost = 0;
        $item_type = Tbl_item::where("item_id",$item_id)->value("item_type_id");
        if($item_id != null && $item_type == 4)
        {
            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
            foreach ($bundle_item as $key => $value) 
            {
                $item_cost =  Purchasing_inventory_system::get_item_cost($value->bundle_item_id);
                $um_qty = UnitMeasurement::um_qty($value->bundle_um_id);

                $cost += $item_cost * ($um_qty * $value->bundle_qty);
            }
        }
        return $cost;
    }    
    public static function get_bundle_item_qty($item_id = null)
    {
        $qty = 0;
        $item_type = Tbl_item::where("item_id",$item_id)->value("item_type_id");
        if($item_id != null && $item_type == 4)
        {
            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();
            foreach ($bundle_item as $key => $value) 
            {
                
            }
        }
        return $qty;
    }  
    public static function get_item_from_bundle($item_id, $warehouse_id = null)
    {
        $_item_bundle = Tbl_item_bundle::where("bundle_bundle_id", $item_id)->get();
        $_item = array();
        foreach($_item_bundle as $item_bundle)
        {  
            if($warehouse_id)
            {
                Item::get_inventory($warehouse_id);
            }

            $item = Item::info($item_bundle->bundle_item_id);
            $item->bundle_qty = $item_bundle->bundle_qty;

            array_push($_item, $item);
        }

        return $_item;
    }  
    public static function get_item_bundle($item_id = null)
    {
        $items = [];

        if($item_id)
        {
            $items           = Tbl_item::where("item_id", $item_id)->first()->toArray();

            $items["bundle"] = Tbl_item_bundle::item()->where("bundle_bundle_id", $item_id)->get()->toArray();
        }
        else
        {
            $_item = Tbl_item::get()->toArray();

            foreach($_item as $key=>$item)
            {
                $items[$key]             = $item;
                $items[$key]["bundle"]   = Tbl_item_bundle::item()->where("bundle_bundle_id", $item["item_id"])->get()->toArray();
            }
        }

        return $items;
    }

    public static function view_item_dropdown($shop_id,$sel = null,$multiple = false)
    {
        if($sel == null)
        {
            $sel = 0;
        }
        $data["multiple"] = $multiple;
        $data['selected'] = $sel;
        $ismerchant = Merchant::ismerchant();
            if($ismerchant == 1)
            {
                $user_id = Merchant::getuserid();

                $data['_item'] = Tbl_item::where("shop_id",$shop_id)
                ->where('tbl_item.item_type_id', '!=', 4)
                ->where('tbl_item.archived', 0)
                ->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
                ->where('item_merchant_requested_by', $user_id)
                ->orderBy('tbl_item.item_id','asc')
                ->type()->category()->get();

                // $data['_item'] = Tbl_item::where("shop_id",$shop_id)
                // ->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
                // ->where('item_merchant_requested_by', $user_id)
                // ->whereIn("item_type_id",$type)->where("archived",0)->get()->toArray(); 
            }
            else
            {
                $data['_item']    = Tbl_item::where("shop_id",$shop_id)
                ->where('tbl_item.item_type_id', '!=', 4)
                ->where('tbl_item.archived', 0)
                ->orderBy('tbl_item.item_id','asc')
                ->type()->category()->get();
            }
        

        return view('member.mlm_product_code.dropdown.mlm_item_dropdown', $data);
    }
    public static function get_all_item_per_shop($shop_id, $array_filter)
    {
        $data['_item']    = Tbl_item::where("shop_id",$shop_id)
        ->where(function($query) use($array_filter)
        {
            foreach($array_filter as $key => $value)
            {
                $query->where($key, $value);
            }
        })
        ->where('item_price', '>=', 1)
        ->join("tbl_mlm_item_points","tbl_mlm_item_points.item_id","=","tbl_item.item_id")
        ->orderBy('tbl_item.item_id','asc')->type()->category()->get();

        return $data['_item'];
    }
    public static function sell_item_add_to_session($array)
    {
       // Session::forget('sell_item_codes_session');
        $get_session = Session::get("sell_item_codes_session"); 
        
        if(!empty($get_session))
        {
            $condition = "false";

            foreach($get_session as $key => $value)
            {
                if($array['item_id'] == $key)
                {
                   $get_session[$key]['quantity']        = $get_session[$key]['quantity'] + $array['quantity'];
                   $get_session[$key]['total']           = $get_session[$key]['total'] + $array['total'];
                   $condition = "true";
                }
            }
            // return $get_session;
            if($condition == "false")
            {
                $get_session[$array['item_id']] = $array;
                Session::put('sell_item_codes_session', $get_session);  
            }
            else
            {
                Session::put('sell_item_codes_session', $get_session); 
            }
        }
        else
        {
            $array2[$array['item_id']] = $array;
            Session::put('sell_item_codes_session', $array2);
        }

        $get_session = Session::get("sell_item_codes_session"); 
        return $get_session;
    }

    public static function sell_item_edit_to_session($array,$removed = null)
    {
       // Session::forget('sell_item_codes_session');
        $get_session = Session::get("sell_item_codes_session"); 
        $total       = 0;
        if(!empty($get_session))
        {
            $condition = "false";
            if($removed)
            {
                $get_session[$array['item_id']] = $array; 
                Session::put('sell_item_codes_session', $get_session);
                
                $remove_session = Session::get("sell_item_codes_session");
                $remove_session = Item::replace_key_function($remove_session,$removed,$array["item_id"]);
                
                unset($remove_session[$removed]);
                Session::put('sell_item_codes_session', $remove_session);
                $get_session = Session::get("sell_item_codes_session");
            }
            else
            {
                foreach($get_session as $key => $value)
                {
                    if($array['item_id'] == $key)
                    {
                       $get_session[$key]['quantity'] = $array['quantity'];
                       $get_session[$key]['total'] = $array['total'];
                       $condition = "true";
                    }
                }
                
            }
            
            if($condition == "false")
            {
                $get_session[$array['item_id']] = $array;
                Session::put('sell_item_codes_session', $get_session);  
            }
            else
            {
                Session::put('sell_item_codes_session', $get_session); 
            }
            
            
            $get_session = Session::get("sell_item_codes_session"); 
            foreach($get_session as $key => $value)
            {
                $total = $total + $get_session[$key]['total'];
                $data["total"] = $total;
            }
            
            return $data;
        }
        else
        {
            return json_encode("Fail"); 
        }
    }

    public static function replace_key_function($array, $key1, $key2)
    {
        $keys = array_keys($array);
        $index = array_search($key1, $keys);
    
        if ($index !== false) {
            $keys[$index] = $key2;
            $array = array_combine($keys, $array);
        }
    
        return $array;
    }

    public static function get_discounted_price_mlm($item_id, $membership)
    {
        $count = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $membership)->count();
        $item_price = Item::get_original_price($item_id);
        if($count === 0)
        {
            return $item_price;
        }
        else
        {

            $discount = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $membership)->first();
            if($discount)
            {
                $discounted_value = get_discount_price($item_price, $discount->item_discount_percentage, $discount->item_discount_price);
                return $discounted_value;
            }
            else
            {
                return $item_price;
            }
            
        }
    }
    public static function get_discount_only($item_id, $membership_id)
    {
        $count = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $membership_id)->count();
        $item_price = Item::get_original_price($item_id);
        if($count === 0)
        {
            return 0;
        }
        else
        {
            $discount = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $membership_id)->first();
            // return $discount->item_discount_percentage;
            if($discount->item_discount_percentage == 0){
                $discounted_value = $discount->item_discount_price;
            }
            else
            {
                return $discounted_value = $item_price *  ($discount->item_discount_price/100);
            }
            return $discounted_value;
        }
    }
    public static function get_original_price($item_id)
    {
        $item_count = Tbl_item::where('item_id', $item_id)->count();
        if($item_count == 0)
        {
            $item_price = 0;
        }
        else
        {
           $item_price = Tbl_item::where('item_id', $item_id)->value('item_price'); 
        }
        return $item_price;
    }
    public static function fix_discount_session($slot_id)
    {
        $slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
        if($slot)
        {
            $item_session = Session::get("sell_item_codes_session");
            foreach($item_session as $key => $item)
            {
                $item_session[$key]['membership_discount'] = Item::get_discount_only($key, $slot->slot_membership);
                $item_session[$key]['membership_discounted_price'] = $item['price'] - $item_session[$key]['membership_discount'];
                $item_session[$key]['membership_discounted_price_total'] =  $item['quantity'] * $item_session[$key]['membership_discounted_price'];
            }  
            Session::put('sell_item_codes_session', $item_session);
        }
        else
        {
            $item_session = Session::get("sell_item_codes_session");
            foreach($item_session as $key => $item)
            {
                $item_session[$key]['membership_discount'] = 0;
                $item_session[$key]['membership_discounted_price'] = $item['price'] - $item_session[$key]['membership_discount'];
                $item_session[$key]['membership_discounted_price_total'] =  $item['quantity'] * $item_session[$key]['membership_discounted_price'];
            }
            Session::put('sell_item_codes_session', $item_session);
        }
        return $item_session;
    }
    public static function fix_discount_session_w_dis($slot_id, $discount_card_log_id)
    {

        if($slot_id != 0)
        {
            $slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
            if($slot)
            {
              $item_session = Session::get("sell_item_codes_session");
              if($item_session != null)
              {
                foreach($item_session as $key => $item)
                {
                    $item_session[$key]['membership_discount'] = Item::get_discount_only($key, $slot->slot_membership);
                    $item_session[$key]['membership_discounted_price'] = $item['price'] - $item_session[$key]['membership_discount'];
                    $item_session[$key]['membership_discounted_price_total'] =  $item['quantity'] * $item_session[$key]['membership_discounted_price'];
                }  
                Session::put('sell_item_codes_session', $item_session);
              }
                
            }
            else
            {
                $item_session = Session::get("sell_item_codes_session");
                foreach($item_session as $key => $item)
                {
                    $item_session[$key]['membership_discount'] = 0;
                    $item_session[$key]['membership_discounted_price'] = $item['price'] - $item_session[$key]['membership_discount'];
                    $item_session[$key]['membership_discounted_price_total'] =  $item['quantity'] * $item_session[$key]['membership_discounted_price'];
                }
                Session::put('sell_item_codes_session', $item_session);
            }
        }
        else
        {
            $discount_card  = Tbl_mlm_discount_card_log::where('discount_card_log_id', $discount_card_log_id)->first();
            if($discount_card)
            {
              $item_session = Session::get("sell_item_codes_session");
              if($item_session != null)
              {
                foreach($item_session as $key => $item)
                {
                    $item_session[$key]['membership_discount'] = Item::get_discount_only($key, $discount_card->discount_card_membership);
                    $item_session[$key]['membership_discounted_price'] = $item['price'] - $item_session[$key]['membership_discount'];
                    $item_session[$key]['membership_discounted_price_total'] =  $item['quantity'] * $item_session[$key]['membership_discounted_price'];
                } 
                }
                Session::put('sell_item_codes_session', $item_session);
            }
            else
            {
                $item_session = Session::get("sell_item_codes_session");
                if($item_session != null)
                {
                    foreach($item_session as $key => $item)
                    {
                        $item_session[$key]['membership_discount'] = 0;
                        $item_session[$key]['membership_discounted_price'] = $item['price'] - $item_session[$key]['membership_discount'];
                        $item_session[$key]['membership_discounted_price_total'] =  $item['quantity'] * $item_session[$key]['membership_discounted_price'];
                    }
                    Session::put('sell_item_codes_session', $item_session);
                }
                
            }
        }
        
        return $item_session;
    }
    public static function getOtherChargeItem()
    {
        $exist_item = Tbl_item::where("shop_id", Item::getShopId())->where("item_code", "other-charge")->first();
        if(!$exist_item)
        {
            $insert["shop_id"]                  = Item::getShopId();
            $insert["item_type_id"]             = 3;
            $insert["item_category_id"]         = Item::getServiceCategory();
            $insert["item_name"]                = "Other Charge";
            $insert["item_income_account_id"]   = Accounting::getOpenBalanceEquity();
            $insert["item_code"]                = "other-charge";
            
            return Tbl_item::insertGetId($insert);
        }

        return $exist_item->item_id;
    }
    public static function getServiceCategory()
    {
        $exist_type = Tbl_category::where("type_shop", Item::getShopId())->where("type_name", "Service")->first();
        if(!$exist_type)
        {
            $insert["type_shop"]                = Item::getShopId();
            $insert["type_category"]            = "services";
            $insert["type_name"]                = "Service";
            $insert["type_date_created"]        = Carbon::now();  
            
            return Tbl_category::insertGetId($insert);
        }

        return $exist_type->type_id;
    }
    public static function getItemCategory($shop_id)
    {
        return Tbl_category::where("type_shop", $shop_id)->where("archived", 0)->get();
    }
    public static function get_choose_item($id)
    {
        $items = Tbl_item_bundle::where('bundle_bundle_id', $id)->get();
        $data = [];
        foreach ($items as $key => $value) 
        {
            $info = Item::info($value->bundle_item_id);

            $data[$value->bundle_item_id]['item_id'] = $value->bundle_item_id;
            $data[$value->bundle_item_id]['item_sku'] = $info->item_sku;
            $data[$value->bundle_item_id]['item_price'] = $info->item_price;
            $data[$value->bundle_item_id]['item_cost'] = $info->item_cost;
            $data[$value->bundle_item_id]['quantity'] = $value->bundle_qty;

            Session::put('choose_item',$data);
        }

        return $data;
    }
    public static function get_item_type_modify($type_id = 0)
    {
        $data['inventory_type'] = 'display: none';
        $data['non_inventory_type'] = 'display: none';
        $data['service_type'] = 'display: none';
        $data['bundle_type'] = 'display: none';
        $data['membership_kit_type'] = 'display: none';
        $data['other_charge_type'] = 'display: none';
        $data['type_main'] = 'display : none';
        $data['type_bundle_main'] = 'display : none';
        $data['type_remove_main'] = 'remove-this-type';
        $data['type_remove_bundle'] = 'remove-this-type';

        if($type_id == 1)
        {
            $data['inventory_type'] = '';
            $data['type_main'] = '';
            $data['type_remove_main'] = '';
        }
        if($type_id == 2)
        {
            $data['non_inventory_type'] = '';
            $data['type_main'] = '';
            $data['type_remove_main'] = '';
        }
        if($type_id == 3)
        {
            $data['service_type'] = '';
            $data['type_main'] = '';
            $data['type_remove_main'] = '';
        }
        if($type_id == 4)
        {
            $data['bundle_type'] = '';
            $data['type_bundle_main'] = '';
            $data['type_remove_bundle'] = '';

        }
        if($type_id == 5)
        {
            $data['membership_kit_type'] = '';
            $data['type_bundle_main'] = '';
            $data['type_remove_bundle'] = '';
        }
        if($type_id == 6)
        {
            $data['other_charge_type'] = '';
            $data['type_main'] = '';
            $data['type_remove_main'] = '';
        }
        return $data;
    }
    public static function get_membership()
    {
        $shop_id = Item::getShopId();
        return Tbl_membership::where('shop_id',$shop_id)->where('membership_archive',0)->get();
    }
    public static function get_all_item_record_log($search_keyword = '', $status = '', $paginate = 0, $item_id = 0, $get_to = 0, $take = 0)
    {
        $shop_id = Item::getShopId();
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);

        $query = Tbl_warehouse_inventory_record_log::slotinfo()->item()->membership()->where('record_shop_id',$shop_id)->where('record_warehouse_id',$warehouse_id)->where('item_type_id','!=',5)->groupBy('record_log_id')->orderBy('record_log_id');
        
        if($search_keyword)
        {
            // $query->where('mlm_pin', "LIKE", "%" . $search_keyword . "%");
            $query->where(function($q) use ($search_keyword)
            {
                $q->orWhere("mlm_pin", "LIKE", "%$search_keyword%");
                $q->orWhere("mlm_activation", "LIKE", "%$search_keyword%");
                $q->orWhere("item_name", "LIKE", "%$search_keyword%");
            });
        }
        if($status == 'reserved')
        {
            $query->where('record_consume_ref_name',$status)->reserved_customer();
        }
        else if($status == 'block')
        {
            $query->where('record_consume_ref_name',$status);            
        }
        else if($status == 'used')
        {
            $query->where('item_in_use', 'used');
        }
        else if($status == 'sold')
        {
            $query->where('record_consume_ref_id','!=', 0)->where('item_in_use','unused');
        }
        else
        {
            $query->where('record_inventory_status',0)->where('record_consume_ref_name',null)->where('item_in_use','unused');
        }
        if($item_id != 0)
        {
            $query->where('tbl_item.item_id',$item_id);
        }

        if($paginate != 0)
        {
            $data = $query->paginate($paginate);
        }
        else
        {
            $data = $query->get();            
        }

        if($take != 0)
        {
            if($get_to > 1)
            {
                $query->skip($get_to);
            }
            $data = $query->take($take + 1)->get();
        }
        return $data;
    }
    public static function get_first_assembled_kit($shop_id)
    {
        return Tbl_item::where('shop_id',$shop_id)->where('item_type_id',5)->value('item_id');
    }
    public static function get_all_assembled_kit($shop_id)
    {
        return Tbl_item::where('shop_id',$shop_id)->where('item_type_id',5)->where("archived", 0)->pluck('item_id', 'item_name');
    }
    public static function get_all_assembled_kit_v2($shop_id)
    {
        return Tbl_item::inventory(Warehouse2::get_main_warehouse($shop_id))->where('shop_id',$shop_id)->where('item_type_id',5)->where("archived", 0)->get();
    } 
    public static function get_assembled_kit($record_id = 0, $item_kit_id = 0, $item_membership_id = 0, $search_keyword = '', $status = '', $paginate = 0, $get_to = 0, $take = 0, $get_from = 0)
    {
        $shop_id = Item::getShopId();
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);

        $query = Tbl_warehouse_inventory_record_log::where('item_type_id',5)->slotinfo()->item()->membership()->where('record_shop_id',$shop_id)->where('record_warehouse_id',$warehouse_id)->groupBy('record_log_id')->orderBy('record_log_id');
        if($record_id > 0)
        {
            $query = Tbl_warehouse_inventory_record_log::where('item_type_id',5)->slotinfo()->where('record_log_id',$record_id)->item()->membership()->where('record_shop_id',$shop_id)->where('record_warehouse_id',$warehouse_id)->groupBy('record_log_id')->orderBy('record_log_id');
        }

        if($item_kit_id)
        {
            $query->where('record_item_id',$item_kit_id);
        }
        if($item_membership_id)
        {
            $query->where('tbl_item.membership_id',$item_membership_id);
        }
        if($search_keyword)
        {
            // $query->where('mlm_pin', "LIKE", "%" . $search_keyword . "%");
            $query->where(function($q) use ($search_keyword)
            {
                $q->orWhere("mlm_pin", "LIKE", "%$search_keyword%");
                $q->orWhere("mlm_activation", "LIKE", "%$search_keyword%");
                $q->orWhere("item_name", "LIKE", "%$search_keyword%");
            });
        }

        if($status == 'reserved')
        {
             $query->where('record_consume_ref_name',$status)->reserved_customer();
        }
        else if($status == 'block')
        {
             $query->where('record_consume_ref_name',$status);
        }
        else if($status == 'used')
        {
            $query->where('item_in_use','used');
        }
        else if($status == 'sold')
        {
            $query->where('record_consume_ref_id','!=', 0)->where('item_in_use','unused');
        }
        else
        {
            $query->where('record_inventory_status',0)->where('record_consume_ref_name',null)->where('item_in_use','unused');
        }  

        if($paginate != 0)
        {
            $data = $query->paginate($paginate);
        }
        else
        {
            if($shop_id == 5)
            {
                $data = $query->whereBetween('ctrl_number',[$get_to, $get_from])->get();

            }
            else
            {
                if($take != 0)
                {
                    if($get_to > 1)
                    {
                        $query->skip($get_to);
                    }
                    $data = $query->take($take + 1)->get();
                }
                else
                {
                    $data = $query->get(); 
                }        
            }           
        }

        return $data; 
    } 

    public static function assemble_membership_kit($shop_id, $warehouse_id, $item_id, $quantity)
    {
        $item_list = Item::get_item_in_bundle($item_id);
        $_item = [];
        foreach ($item_list as $key => $value) 
        {
            $_item[$key]['item_id'] = $value->bundle_item_id;
            $_item[$key]['quantity'] = $value->bundle_qty * $quantity;
            $_item[$key]['remarks'] = 'consume item upon assembling item';
        }
        $validate_consume = Warehouse2::consume_bulk($shop_id, $warehouse_id, 'assemble_item', $item_id, 'Consume Item upon assembling membership kit Item#'.$item_id, $_item);

        $itemdata = Self::info($item_id);
        if(!$validate_consume)
        {
            $source['name'] = 'assemble_item';
            $source['id'] = $item_id;
            $validate_consume .= Warehouse2::refill($shop_id, $warehouse_id, $item_id, $quantity, 'Refill Item upon assembling membership kit Item#'.$item_id, $source);            
        }

        return $validate_consume;
    } 
    public static function disassemble_membership_kit($record_log_id)
    {
        $record_data = Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)->first();
        $qty = Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)->count();

        if($record_data)
        {
            $item_list = Item::get_item_in_bundle($record_data->record_item_id);
           foreach ($item_list as $key => $value) 
           {
                Warehouse2::consume_update('assemble_item', $record_data->record_item_id, $value->bundle_item_id, $value->bundle_qty);
                Warehouse2::update_inventory_count($record_data->record_warehouse_id, $record_log_id, $value->bundle_item_id, $value->bundle_qty);
           }
           Warehouse2::update_inventory_count($record_data->record_warehouse_id, $record_log_id, $record_data->record_item_id, -($qty));

           Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)->delete();
        }
    }
    public static function check_mlm_activation($shop_id, $mlm_activation = '')
    {
        $ctr = Tbl_warehouse_inventory_record_log::where("record_shop_id",$shop_id)->where('mlm_activation',$mlm_activation)->count();
        if($ctr > 0)
        {
            $mlm_activation = Self::check_mlm_activation($shop_id, strtoupper(str_random(6)));
        }

        return $mlm_activation;
    }
    public static function get_mlm_activation($shop_id)
    {
        $mlm_activation = strtoupper(str_random(6));

        $ctr = Tbl_warehouse_inventory_record_log::where("record_shop_id",$shop_id)->where('mlm_activation',$mlm_activation)->count();
        if($ctr > 0)
        {
            $mlm_activation = Self::check_mlm_activation($shop_id, strtoupper(str_random(6)));
        }

        return $mlm_activation;
    }
    public static function check_unused_product_code($shop_id = 0, $mlm_pin = '', $mlm_activation = '')
    {
        $ctr = Tbl_warehouse_inventory_record_log::where("record_shop_id",$shop_id)
                                                 ->where('mlm_activation',$mlm_activation)
                                                 ->where('mlm_pin',$mlm_pin)
                                                 ->where('item_in_use','unused')
                                                 ->count();
        $return = false;
        if($ctr > 0)
        {
            $return = true;
        }

        return $return;
    }
    public static function check_product_code($shop_id = 0, $mlm_pin = '', $mlm_activation = '')
    {
        $ctr = Tbl_warehouse_inventory_record_log::where("record_shop_id",$shop_id)
                                                 ->where('mlm_activation',$mlm_activation)
                                                 ->where('mlm_pin',$mlm_pin)
                                                 ->where('record_inventory_status',0)
                                                 ->where('item_in_use','unused')
                                                 ->count();
        $return = false;
        if($ctr > 0)
        {
            $return = true;
        }

        return $return;
    }
    public static function type($item_id = 0)
    {
        return Tbl_item::where("item_id",$item_id)->value('item_type_id');
    }
    public static function view_item_receipt($item_id)
    {
        $audit = Tbl_audit_trail::where("source_id",$item_id)->where("source","item")->where("remarks","Added")->first();

        if($audit)
        {
            $data = unserialize($audit->new_data);
            
            $data["category_name"] = Tbl_category::where("type_id",$data["item_category_id"])->first() ? Tbl_category::where("type_id",$data["item_category_id"])->first()->type_name : "";
        }
        else
        {
            $data = null;
        }

        return $data;
    }
    public static function change_price($item_id, $type, $price)
    {
        $data = Tbl_item::where('item_id',$item_id)->first();
        if($data)
        {
            $con = true;
            if($type == 'sales_price')
            {
                if($data->item_price == $price)
                {
                    $con = false;
                }
                $price = $data->item_price;
            }
            if($type == 'cost_price')
            {
                if($data->item_cost == $price)
                {
                    $con = false;
                }
                $price = $data->item_cost;
            }
            if($con == true)
            {
                $insert['item_id'] = $item_id;
                $insert['shop_id'] = Item::getShopId();
                $insert['price_type'] = $type;
                $insert['price'] = $price;
                $insert['updated_at'] = Carbon::now();

                Tbl_item_price_history::insert($insert);                
            }
        }
    }
    public static function tag_as_printed($warehouse_id, $from, $to)
    {
        $update['printed_by'] = Self::getUserid();
        $get = Tbl_warehouse_inventory_record_log::item()->where('item_type_id',5)->where('record_warehouse_id',$warehouse_id)->whereBetween('ctrl_number',[$from,$to])->update($update);
    }
    public static function get_last_print()
    {
        $return = Tbl_warehouse_inventory_record_log::item()->where('item_type_id',5)->where('record_shop_id',Self::getShopId())->where('printed_by',0)->where('ctrl_number','!=',0)->value('ctrl_number');

        return $return;
    }
    public static function get_bundle_list($item_id)
    {
        return Tbl_item_bundle::item()->where("bundle_bundle_id",$item_id)->get();
    }
    public static function get_item_inventory($shop_id, $item_id, $warehouse_id = 0, $from = '', $to = '')
    {
        $offset = Tbl_warehouse_inventory_record_log::where('record_shop_id', $shop_id)->where("record_warehouse_id",$warehouse_id)->where('record_item_id', $item_id)->where('record_count_inventory',0);
        $current = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->where("record_count_inventory",1);
        if($from && $to)
        {
            $offset_qty = $offset->whereBetween('record_log_date_updated',[$from, $to])->count();
            $current_qty = $current->whereBetween('record_log_date_updated',[$from, $to])->count();
        }
        else
        {
            $offset_qty = $offset->count();
            $current_qty = $current->count();
        }

        return $current_qty - $offset_qty;
    }
    public static function item_inventory_report($shop_id, $item_id, $date_from = '', $date_to = '', $user_id = 0)
    {
        $get_warehouse = Warehouse2::get_user_warehouse_access($shop_id, $user_id);
        if($shop_id == 81)
        {
            $get_warehouse = Warehouse2::get_all_warehouse($shop_id);
        }
        $return = null;
        if(count($get_warehouse) > 0)
        {
            foreach ($get_warehouse as $key => $value) 
            {
                $return[$key] = new \stdClass();
                $return[$key]->warehouse_name = $value->warehouse_name;
                $return[$key]->warehouse_id = $value->warehouse_id;
                $return[$key]->qty_on_hand = Warehouse2::get_item_qty($value->warehouse_id, $item_id, null, null ,null, $date_from, $date_to);
            }
        }
        return $return;
    }
    public static function item_ave_cost($shop_id, $item_id, $date_from = '', $date_to = '', $user_id = 0)
    {
        $get_warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('archived',0)->get();
        $return = null;
        if(count($get_warehouse) > 0)
        {
            foreach ($get_warehouse as $key => $value) 
            {
                $item = Tbl_item::where('item_id', $item_id)->where('shop_id', $shop_id)->first();
                $get_cost = Tbl_item_average_cost_per_warehouse::where('iacpw_shop_id',$shop_id)->where('iacpw_warehouse_id',$value->warehouse_id)->where('iacpw_item_id',$item_id)->first();
                $return[$key] = new \stdClass();
                $return[$key]->warehouse_name = $value->warehouse_name;
                $return[$key]->warehouse_id = $value->warehouse_id;
                
                if($get_cost != null)
                {
                    $return[$key]->average_cost = $get_cost->iacpw_ave_cost;
                }
                else
                {
                    $return[$key]->average_cost = $item->item_cost;
                }
            }
        }
        return $return;
    }
    public static function import_create_bundle($bundle_id, $_item_id = array())
    {
        $date = Carbon::now();
        if(count($_item_id) > 0)
        {
            $ins = null;
            foreach ($_item_id as $key => $value) 
            {
                if($key && $bundle_id)
                {
                    $ins[$key]['bundle_bundle_id'] = $bundle_id;
                    $ins[$key]['bundle_item_id'] = $key;
                    $ins[$key]['bundle_qty'] = $value;
                    $ins[$key]['created_at'] = $date;                    
                }
            }
            if(count($ins) > 0)
            {
                Tbl_item_bundle::insert($ins);
            }
        }
    }
    public static function record_item_pricing_history($shop_id, $user_id, $item_id, $old, $new)
    {
        if(count($old) > 0 && count($new) > 0)
        {
            $old_price = null;
            $old_cost = null;
            $date = Carbon::now(); 

            if($old['item_price'] != $new['item_price'])
            {
                $old_price = $old['item_price'];
                $old_cost = $old['item_cost'];   
            }
            if($old['item_cost'] != $new['item_cost'])
            {
                $old_price = $old['item_price'];
                $old_cost = $old['item_cost'];                 
            }
            if(is_numeric($old_cost) && is_numeric($old_price))
            {
                $ins_old['pricing_item_id'] = $item_id;
                $ins_old['pricing_sales_price'] = $old_price;
                $ins_old['pricing_cost_price'] = $old_cost;
                $ins_old['pricing_shop_id'] = $shop_id;
                $ins_old['pricing_user_id'] = $user_id;
                $ins_old['pricing_created'] = $date;
                $id = Tbl_item_pricing_history::insertGetId($ins_old);
            }
        }
    }
    public static function insert_pricing_history($insert_pricing_history, $user_id, $item_id, $shop_id)
    {
        if($insert_pricing_history)
        {
            $item_data = Tbl_item::where("item_id", $item_id)->first();
            /* UPDATE ITEM PRICE HISTORY HERE */
            $get = Tbl_item_pricing_history::where("pricing_shop_id", $shop_id)->where("pricing_item_id", $item_id)->get();
            $date = Carbon::now();
            
            if(count($get) < 1) /*INSERT OLD AND NEW PRICING/COSTING */
            {
                if($item_data)
                {
                    $pricing_history['pricing_item_id'] = $item_id;
                    $pricing_history['pricing_sales_price'] = $item_data->item_price;
                    $pricing_history['pricing_cost_price'] = $item_data->item_cost;
                    $pricing_history['pricing_shop_id'] = $shop_id;
                    $pricing_history['pricing_user_id'] = $user_id;
                    $pricing_history['pricing_created'] = $date;
                    Tbl_item_pricing_history::insert($pricing_history);
                }
            }
            else /* INSERT NEW PRICING */
            {
                if($item_data)
                {                
                    $new_pricing_history['pricing_item_id'] = $insert_pricing_history['invty_item_id'];
                    $new_pricing_history['pricing_sales_price'] = $insert_pricing_history['invty_sales_price'] ;
                    $new_pricing_history['pricing_cost_price'] = $insert_pricing_history['invty_cost_price'];
                    $new_pricing_history['pricing_shop_id'] = $insert_pricing_history['invty_shop_id'];
                    $new_pricing_history['pricing_user_id'] = $user_id;
                    $new_pricing_history['pricing_created'] = $date;
                    Tbl_item_pricing_history::insert($new_pricing_history);
                    /*$ins_new['pricing_item_id'] = $item_id;
                    $ins_new['pricing_sales_price'] = $item_data->item_price;
                    $ins_new['pricing_cost_price'] = $item_data->item_cost;
                    $ins_new['pricing_shop_id'] = $shop_id;
                    $ins_new['pricing_user_id'] = $user_id;
                    $ins_new['pricing_created'] = $date;
                    Tbl_item_pricing_history::insert($ins_new);*/
                }
            }
        }


    }
    public static function update_item_price($shop_id, $user_id, $item_id, $update)
    {
        $old_item_data = Tbl_item::where("item_id", $item_id)->first();
        Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->update($update);
        $item_data = Tbl_item::where("item_id", $item_id)->first();
        /* UPDATE ITEM PRICE HISTORY HERE */
        $get = Tbl_item_pricing_history::where("pricing_shop_id", $shop_id)->where("pricing_item_id", $item_id)->get();
        $date = Carbon::now();
        
        if(count($get) < 1) /*INSERT OLD AND NEW PRICING/COSTING */
        {
            if($old_item_data && $item_data)
            {
                $ins_old['pricing_item_id'] = $item_id;
                $ins_old['pricing_sales_price'] = $old_item_data->item_price;
                $ins_old['pricing_cost_price'] = $old_item_data->item_cost;
                $ins_old['pricing_shop_id'] = $shop_id;
                $ins_old['pricing_user_id'] = $user_id;
                $ins_old['pricing_created'] = $date;
                Tbl_item_pricing_history::insert($ins_old);

                $ins_new['pricing_item_id'] = $item_id;
                $ins_new['pricing_sales_price'] = $item_data->item_price;
                $ins_new['pricing_cost_price'] = $item_data->item_cost;
                $ins_new['pricing_shop_id'] = $shop_id;
                $ins_new['pricing_user_id'] = $user_id;
                $ins_new['pricing_created'] = $date;
                Tbl_item_pricing_history::insert($ins_new);
            }
        }
        else /* INSERT NEW PRICING */
        {
            if($item_data)
            {                
                $ins_new['pricing_item_id'] = $item_id;
                $ins_new['pricing_sales_price'] = $item_data->item_price;
                $ins_new['pricing_cost_price'] = $item_data->item_cost;
                $ins_new['pricing_shop_id'] = $shop_id;
                $ins_new['pricing_user_id'] = $user_id;
                $ins_new['pricing_created'] = $date;
                Tbl_item_pricing_history::insert($ins_new);
            }
        }
    }
    public static function get_transit_item($shop_id, $item_id, $date_from = null, $date_to = null, $user_id = '')
    {
        $warehouse = Warehouse2::warehouse_access_array($shop_id, $user_id);


        // $get_pending = Tbl_warehouse_inventory_record_log::transit()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","customer_wis");

        // $get_intransit = Tbl_warehouse_inventory_record_log::transit()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","customer_wis");

        // $get_pending_transfer = Tbl_warehouse_inventory_record_log::transitTransfer()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","wis");
        // $get_intransit_transfer = Tbl_warehouse_inventory_record_log::transitTransfer()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","wis");


        // $get_delivered = Tbl_warehouse_inventory_record_log::transit()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","customer_wis");

        $get_pending = Tbl_customer_wis::item()
                                       ->whereIn("cust_wis_from_warehouse", $warehouse)
                                       ->where("itemline_item_id", $item_id)
                                       ->where("cust_wis_shop_id", $shop_id);

        $get_intransit = Tbl_customer_wis::item()
                                       ->whereIn("cust_wis_from_warehouse", $warehouse)
                                       ->where("itemline_item_id", $item_id)
                                       ->where("cust_wis_shop_id", $shop_id);

        $get_pending_transfer = Tbl_warehouse_issuance_report::itemline()
                                                             ->where("wis_shop_id", $shop_id)
                                                             ->where("wt_item_id", $item_id)
                                                             ->whereIn("wis_from_warehouse", $warehouse);

        $get_intransit_transfer = Tbl_warehouse_issuance_report::itemline()
                                                             ->where("wis_shop_id", $shop_id)
                                                             ->where("wt_item_id", $item_id)
                                                             ->whereIn("wis_from_warehouse", $warehouse);

        $get_delivered = Tbl_customer_wis::item()
                                       ->whereIn("cust_wis_from_warehouse", $warehouse)
                                       ->where("itemline_item_id", $item_id)
                                       ->where("cust_wis_shop_id", $shop_id);
        $return["pending_transit"] = 0;
        $return["in_transit"] = 0;
        $return['in_transit_transfer'] = 0;
        $return['pending_transit_transfer'] = 0;
        $return['delivered'] = 0;
        // dd($get_intransit->get());
        if($date_from != "1000-01-01" && $date_to != "9999-12-30")
        {
            $get_pending = $get_pending->whereBetween('tbl_customer_wis.created_at',[$date_from, $date_to]);
            $get_intransit = $get_intransit->whereBetween('tbl_customer_wis.created_at',[$date_from, $date_to]);

            $get_pending_transfer = $get_pending_transfer->whereBetween('tbl_warehouse_issuance_report.created_at',[$date_from, $date_to]);
            $get_intransit_transfer = $get_intransit_transfer->whereBetween('tbl_warehouse_issuance_report.created_at',[$date_from, $date_to]);
            $get_delivered = $get_delivered->whereBetween('tbl_customer_wis.created_at',[$date_from, $date_to]);
        }
        
        $return['in_transit_transfer'] = $get_intransit_transfer->where("wis_status","confirm")->sum("wt_orig_qty");
        $return['in_transit'] = $get_intransit->where("cust_wis_status","confirm")->sum("itemline_orig_qty") + $return['in_transit_transfer'];
        $return['pending_transit_transfer'] = $get_pending_transfer->where("wis_status","pending")->sum("wt_orig_qty");
        $return['pending_transit'] = $get_pending->where("cust_wis_status","pending")->sum("itemline_orig_qty") + $return['pending_transit_transfer'];
        $return['delivered'] = $get_delivered->where("cust_wis_status","delivered")->sum("itemline_orig_qty");

        return $return;
    }

    public static function search_item($shop_id, $search_keyword)
    {
        $check = Tbl_item::where('shop_id', $shop_id)->where('item_barcode', $search_keyword)->first();
        
        $return = null;
        if($check)
        {
            $return = $check->item_id;
        }
        else
        {
            $check = Tbl_item::where('shop_id', $shop_id)->where('item_id', $search_keyword)->first();
            if($check)
            {
                $return = $check->item_id;
            }
        }
    }
    public static function intransit_breakdown($shop_id, $item_id, $date_from = null, $date_to = null, $user_id = '')
    {
        $warehouse = Warehouse2::warehouse_access_array($shop_id, $user_id);

        // $get_intransit = Tbl_warehouse_inventory_record_log::selectRaw("*, count(record_log_id) as invty")->transit()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","customer_wis")
        //                                          ->where("cust_wis_status","confirm")
        //                                          ->groupBy("record_consume_ref_name")
        //                                          ->groupBy("record_consume_ref_id");
        $get_intransit = Tbl_monitoring_inventory::selectRaw("*, sum(invty_qty) as invty")->transit()
                                                 ->where("invty_shop_id", $shop_id)
                                                 ->where("invty_item_id", $item_id)
                                                 ->whereIn("invty_warehouse_id", $warehouse)
                                                 ->where("invty_transaction_name","customer_wis")
                                                 ->where("cust_wis_status","confirm")
                                                 ->groupBy("invty_transaction_name")
                                                 ->groupBy("invty_transaction_id");

        // $get_intransit_transfer = Tbl_warehouse_inventory_record_log::selectRaw("*, count(record_log_id) as invty")->transitTransfer()
        //                                          ->where("record_shop_id", $shop_id)
        //                                          ->where("record_item_id", $item_id)
        //                                          ->whereIn("record_warehouse_id", $warehouse)
        //                                          ->where("record_consume_ref_name","wis")
        //                                          ->where("wis_status","confirm")
        //                                          ->groupBy("record_consume_ref_name")
        //                                          ->groupBy("record_consume_ref_id");

        $get_intransit_transfer = Tbl_monitoring_inventory::selectRaw("*, sum(invty_qty) as invty")->transitTransfer()
                                                 ->where("invty_shop_id", $shop_id)
                                                 ->where("invty_item_id", $item_id)
                                                 ->whereIn("invty_warehouse_id", $warehouse)
                                                 ->where("invty_transaction_name","wis")
                                                 ->where("wis_status","confirm")
                                                 ->groupBy("invty_transaction_name")
                                                 ->groupBy("invty_transaction_id");

        if($date_from != "1000-01-01" && $date_to != "9999-12-30")
        {
            $get_intransit = $get_intransit->whereBetween('invty_date_created',[$date_from, $date_to]);
            $get_intransit_transfer = $get_intransit_transfer->whereBetween('invty_date_created',[$date_from, $date_to]);
        }

        $get_wis_intransit = $get_intransit->get();
        $get_wt_intransit = $get_intransit_transfer->get();

        $return = null;
        foreach ($get_wis_intransit as $key => $value) 
        {
            $cwis = Tbl_customer_wis::truck()->customerInfo()->where("cust_wis_id", $value->invty_transaction_id)->first();

            if($cwis)
            {
                $return["customer_wis".$key]['trans_num'] = $cwis->transaction_refnum;
                $return["customer_wis".$key]['receiver'] = $cwis->company != "" ? $cwis->company : ucwords($cwis->first_name." ".$cwis->middle_name." ".$cwis->last_name);
                $return["customer_wis".$key]['truck'] = $cwis->plate_number;
                $return["customer_wis".$key]['qty'] = abs($value->invty);
            }
        }

        foreach ($get_wt_intransit as $keys => $values) 
        {
            $wt = Tbl_warehouse_issuance_report::truck()->destinationWarehouse()->where("wis_id", $values->invty_transaction_id)->first();
            if($wt)
            {
                $return["wis".$keys]['trans_num'] = $wt->wis_number;
                $return["wis".$keys]['receiver'] = $wt->warehouse_name;
                $return["wis".$keys]['truck'] = $wt->plate_number;
                $return["wis".$keys]['qty'] = abs($values->invty);
            }
        }
        return $return;
    }
    public static function delete_items($shop_id)
    {
        Tbl_item_pricing_history::where("pricing_shop_id", $shop_id)->delete();
        Tbl_item::where("shop_id", $shop_id)->delete();
        Tbl_manufacturer::where("manufacturer_shop_id", $shop_id)->delete();
    }

    public static function delete_category($shop_id)
    {
        Tbl_ec_product::where("eprod_shop_id", $shop_id)->delete();
        Tbl_category::where("type_shop", $shop_id)->delete();
    }
    public static function delete_um($shop_id)
    {
        Tbl_item::where("shop_id", $shop_id)->update(["item_measurement_id" => 0]);
        Tbl_unit_measurement::where("um_shop", $shop_id)->delete();
        Tbl_unit_measurement_type::where("shop_id", $shop_id)->delete();
    }
}