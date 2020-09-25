<?php
namespace App\Http\Controllers\Member;
use Illuminate\Http\Request;
use App\Globals\Terms;
use App\Globals\Vendor;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\Purchase_Order;
use App\Globals\Billing;
use App\Globals\Item;
use App\Globals\Warehouse;
use App\Globals\Warehouse2;
use App\Globals\UnitMeasurement;
use App\Globals\Utilities;
use App\Globals\Pdf_global;
use App\Globals\ItemSerial;
use App\Globals\Purchasing_inventory_system;
use App\Globals\TransactionEnterBills;
use App\Globals\TransactionPurchaseOrder;
use App\Globals\AccountingTransaction;

use App\Models\Tbl_item_range_sales_discount;

use Carbon\Carbon;
use Session;
use PDF;

class ItemRangeController extends Member
{
    
    public function getIndex(Request $request)
    {
        // dd(empty($data['previous_item_range']),$data['previous_item_range']->count());
        $data['page'] = 'Item Range Sales Discount Module';
        $data['icon'] = 'fa fa-shopping-cart';

        
        $data['_item']      = Item::get_all_category_item();
        $data['action']     = '/member/utilities/item_range_sales_discount/create';
        
        $receive_id = $request->id;
        Session::forget("applied_transaction");
  

        $access = Utilities::checkAccess('vendor-receive-inventory', 'access_page');
        if($access == 1)
        { 
            return view('member.item_range_sales_discount.item_range_sales_discount',$data);
        }
        else
        {
            return $this->show_no_access();            
        }
        
    }
    public function getLoadItemRange(Request $request)
    {
        $data['_item']      = Item::get_all_category_item();

        $_item_range = Tbl_item_range_sales_discount::GetItemName()
                                                    ->where('range_shop_id',$this->user_info->shop_id);
        if($request->search_item_id)
        {
            $_item_range = $_item_range->where("range_item_id",$request->search_item_id);
        }
        $data['previous_item_range'] = $_item_range->paginate(10);
        return view('member.item_range_sales_discount.range_sales_discount_table',$data);
    }
    public function postCreate(Request $request)
    {
        ini_set('max_input_vars','5000');
        // for ($x = 0; $x <= 1000; $x++) {
        //         $data[$x]['item_id'] = 478; 
        //         $data[$x]['item_qty'] = $x+1; 
        //         $data[$x]['item_new_price'] = $x+2; 
        //     } 
            // dd($data);
        // dd($request->all());
        $user_id = $this->user_info->user_id;
        $shop_id = $this->user_info->shop_id;
        $item = Self::creation_of_item_validation($request);
        $item_cheking = Self::fields_validation($request);
        // $item = $data;
        // dd($item,$item_cheking);

        if(!empty($item) && $request->selected_item)
        {
            $item = Self::insert_tbl_item_range_sales_discount($item,$user_id,$shop_id, $request->selected_item);
            $return['status'] = 'success';
            $return['status_message'] = 'Success item range sales discount.';
            $return['call_function'] = 'success_item_range';
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $item_cheking;
        }

        return json_encode($return);
        // dd(empty($item));
        // dd($item);
    }

    public static function fields_validation($request)
    {
        $item_id = $request->item_id;
        $item_qty = $request->item_qty;
        $item_new_price = $request->item_new_price;
        // dd($item_id,$item_qty,$item_new_price);

        $item_id = array_filter($item_id);
        $item_qty = array_filter($item_qty);
        $item_new_price = array_filter($item_new_price);
        // dd($item_id,$item_qty,$item_new_price);

        // dd(array_merge_recursive($item_id,$item_qty));


        $return_message = "";


        //CHECKING IF FIELDS ARE FILLED
        foreach ($item_id as $key => $value) 
        {
            $row_error = $key + 1;
            if(!array_key_exists($key, $item_qty))
            {
                $return_message .= "Please check Item Qty Field. (Row ".$row_error.") <br>";
            }

            if(!array_key_exists($key, $item_new_price))
            {
                $return_message .= "Please check Item New Price / PC Field. (Row ".$row_error.") <br>";
            }
        }

        foreach ($item_qty as $key => $value) 
        {
            $row_error = $key + 1;
            if(!array_key_exists($key, $item_id))
            {
                $return_message .= "Please check Item Name Field. (Row ".$row_error.") <br>";
            }
        }

        foreach ($item_new_price as $key => $value) 
        {
            $row_error = $key + 1;
            if(!array_key_exists($key, $item_id))
            {
                $return_message .= "Please check Item Name Field. (Row ".$row_error.") <br>";
            }
        }
        //CHECKING IF FIELDS ARE FILLED

        //CHECKING FOR DUPLICATED ARRAY VALUES
        $item_key = Self::for_array_push($request);
        $item = array_map("unserialize", array_unique(array_map("serialize", $item_key)));

        if(count($item_key) != count($item))
        {
            $return_message .= "Please check Items for duplicate.";
        }
        //CHECKING FOR DUPLICATED ARRAY VALUES

        $str_return_message = "";
        if(!empty($return_message))
        {
            
            $return_message = explode("<br>", $return_message);
            $return_message = array_unique($return_message);

            foreach ($return_message as $key => $value) 
            {
               $str_return_message .= $value."<br>";  
            }
        }
        if(!$request->selected_item)
        {
            $str_return_message .= "Please select item to create or update sales discount <br>";
        }

        return $str_return_message;
    }

    public static function creation_of_item_validation($request)
    {
        // dd($request->all());
        // dd($request->item_id,$request->item_qty,$request->item_new_price);
        $fields_validation = Self::fields_validation($request);

        if(empty($fields_validation))
        {
            $item = Self::for_array_push($request);
            $item = array_map("unserialize", array_unique(array_map("serialize", $item)));

            foreach ($item as $key => $value) 
            {
                $item[$key]["item_new_price"] = $request->item_new_price[$key];
            }
        }
        else
        {
            $item = null;
        }
        return $item;
    }

    public static function for_array_push($request)
    {
        $item = array();

        foreach ($request->item_id as $key => $value) 
        {
            $less_than =  str_replace(",", "", $request->item_qty[$key]) >1;
            $validation_if_empty = !(empty($request->item_id[$key]) || empty($request->item_qty[$key]) || empty($request->item_new_price[$key])); //check if fields is not empty

            $result_validation = $less_than && $validation_if_empty;
            if($result_validation)
            {
                //push only on the array if condition satisfy
                $item_array = array("item_id" => $request->item_id[$key],
                                    "item_qty" => $request->item_qty[$key]);

                array_push($item, $item_array);
            }
        }

        return $item;
    }

    public static function insert_tbl_item_range_sales_discount($item,$user_id,$shop_id, $selected_item_id)
    {
        Tbl_item_range_sales_discount::where("range_shop_id", $shop_id)->where("range_item_id", $selected_item_id)->delete();
        foreach ($item as $key => $value)
        {
            $check = Tbl_item_range_sales_discount::where('range_shop_id',$shop_id)
                                                    ->where("range_item_id",$value['item_id'])
                                                    ->where("range_qty",str_replace(",", "", $item[$key]['item_qty']))
                                                    ->first();
            if($check)
            {
                $udpate['range_item_id'] = $item[$key]['item_id'];
                $udpate['range_qty'] = str_replace(",", "", $item[$key]['item_qty']);
                $udpate['range_new_price_per_piece'] = str_replace(",", "", $item[$key]['item_new_price']);
                $udpate['range_date_created'] = Carbon::now();
                $udpate['range_user_id'] = $user_id;
                $udpate['range_shop_id'] = $shop_id;
                Tbl_item_range_sales_discount::where('range_shop_id',$shop_id)
                                             ->where("range_item_id",$value['item_id'])
                                             ->where("range_qty",str_replace(",", "", $item[$key]['item_qty']))
                                             ->update($udpate);
            }
            else
            {
                $insert['range_item_id'] = $item[$key]['item_id'];
                $insert['range_qty'] = str_replace(",", "", $item[$key]['item_qty']);
                $insert['range_new_price_per_piece'] = str_replace(",", "", $item[$key]['item_new_price']);
                $insert['range_date_created'] = Carbon::now();
                $insert['range_user_id'] = $user_id;
                $insert['range_shop_id'] = $shop_id;
                Tbl_item_range_sales_discount::insert($insert);
            }
        }

        return 'success';
    }
}
