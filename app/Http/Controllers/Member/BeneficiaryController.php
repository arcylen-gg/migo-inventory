<?php

namespace App\Http\Controllers\Member;

use Request;
use Session;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Tbl_category;
use App\Globals\Item;
use App\Models\Tbl_merchant_school;
use App\Models\Tbl_mlm_slot;
use App\Globals\Mlm_member;
use Carbon\Carbon;
use App\Globals\Pdf_global;
class BeneficiaryController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = [];
        $shop_id = $this->user_info->shop_id;
        $data['_item'] = Item::get_all_category_item();
        $data['reciept'] = DB::table('tbl_merchant_school_wallet')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_merchant_school_wallet.merchant_school_custmer_id')
        ->where('shop_id', $shop_id)
        ->paginate(10);
        return view('member.merchant_school.index', $data);
    }
    public function get()
    {
        $shop_id = $this->user_info->shop_id;
        $data['items'] = Tbl_merchant_school::where('merchant_school_shop', $shop_id)
        ->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_merchant_school.merchant_item_id')
        ->get();
        return view('member.merchant_school.get', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // return $_POST;

        $shop_id = $this->user_info->shop_id;
        $item_id = Request::input('item_id');

        if($item_id != null)
        {
            $count = Tbl_merchant_school::where('merchant_school_shop', $shop_id)
            ->where('merchant_item_id', $item_id)
            ->count();
            if($count == 0)
            {
                $insert['merchant_school_shop'] = $shop_id;
                $insert['merchant_item_id'] = $item_id;
                Tbl_merchant_school::insert($insert);

                $data['status'] = 'success'; 
                $data['message'] = 'Item Added to list';
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] = 'Item already added';
            }
        }
        else
        {
            $data['status'] = 'error';
            $data['message'] = 'Invalid Item';
        }

        return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
        $shop_id = $this->user_info->shop_id;
        $item_id = Request::input('item_id');

        Tbl_merchant_school::where('merchant_school_shop', $shop_id)
        ->where('merchant_item_id', $item_id)
        ->delete();

        $data['status'] = 'success';
        $data['message'] = 'Item removed from list';

        return json_encode($data);
    }
    public function get_table()
    {
        $data['items_s'] = DB::table('tbl_merchant_school_item')
        ->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_merchant_school_item.merchant_item_item_id')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_merchant_school_item.merchant_item_customer_id');

        $s_status = Request::input('s_status');
        $s_search_by = Request::input('s_search_by');
        $s_input = Request::input('s_input');
        if($s_status != null)
        {
            if($s_status != 'all')
            {
                $data['items_s'] = $data['items_s']->where('merchant_item_status', $s_status);
            }
        }
        if($s_search_by != null)
        {
            switch ($s_search_by) {
                case 'all':
                    break;
                case 'customer_name':
                    $data['items_s'] = $data['items_s']->where('first_name', 'like', '%' . $s_input . '%');
                    $data['items_s'] = $data['items_s']->orWhere('last_name', 'like', '%' . $s_input . '%');
                    break;  
                case 'order':
                    $data['items_s'] = $data['items_s']->where('merchant_item_ec_order_id', 'like', '%' . $s_input . '%');
                    break;   
                case 'code':
                    $data['items_s'] = $data['items_s']->where('merchant_item_code', 'like', '%' . $s_input . '%');
                    break;  
                case 's_id':
                    $data['items_s'] = $data['items_s']->where('merchant_school_s_id', 'like', '%' . $s_input . '%');
                    break;  
                case 's_name':
                    $data['items_s'] = $data['items_s']->where('merchant_school_s_name', 'like', '%' . $s_input . '%');
                    break;                 
                default:
                    break;
            }
        }
        $data['s_status'] = $s_status;
        $data['s_search_by'] = $s_search_by;
        $data['s_input'] = $s_input;
        $data['items_s'] = $data['items_s']->paginate(10);

        return view('member.merchant_school.table', $data);
    }
    public function mark_used()
    {
        $merchant_school_item_id = Request::input('merchant_school_item_id');
        $update['merchant_item_status'] = 2;

         DB::table('tbl_merchant_school_item')->where('merchant_school_item_id', $merchant_school_item_id)->update($update);

         $data['status'] = 'success'; 
         $data['message'] = 'Mark as Used';

         return json_encode($data);
    }
    public function get_customer($id)
    {
        $slot = Tbl_mlm_slot::where('slot_no', $id)
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->first();
        if($slot)
        {
            $customer_id = $slot->slot_owner;
            $data['customer_id'] = $customer_id;
            $data['info'] = Mlm_member::get_customer_info($customer_id);
            $data['sum'] = DB::table('tbl_merchant_school_wallet')->where('merchant_school_custmer_id', $customer_id)->sum('merchant_school_amount');
            $data['get_wallet'] = DB::table('tbl_merchant_school_wallet')->where('merchant_school_custmer_id', $customer_id)->get();
            return view('member.merchant_school.search', $data);
        }
        else
        {
            return 'Invalid Customer';
        }
        
    }

    public function consume()
    {
        $insert['merchant_school_amount'] = Request::input('merchant_school_amount'); 
        $insert['merchant_school_s_id'] = Request::input('merchant_school_s_id');
        $insert['merchant_school_s_name'] = Request::input('merchant_school_s_name');
        $insert['merchant_school_remarks'] = Request::input('merchant_school_remarks');
        $insert['merchant_school_custmer_id'] = Request::input('customer_id');
        $insert['merchant_school_date'] = Carbon::now();
        $insert['merchant_school_anouncement'] = Request::input('merchant_school_anouncement');
        $insert['merchant_school_cash'] = Request::input('merchant_school_cash');
        $customer_id = Request::input('customer_id');
        $all_wallet = DB::table('tbl_merchant_school_wallet')->where('merchant_school_custmer_id', $customer_id)->sum('merchant_school_amount');
        
        $insert['merchant_school_amount'] = floatval($insert['merchant_school_amount'] );
        // dd($insert['merchant_school_amount']);
        if($all_wallet >= $insert['merchant_school_amount'])
        {
            $insert['merchant_school_amount_old'] = $all_wallet;
            $insert['merchant_school_amount_new'] = $all_wallet - $insert['merchant_school_amount'];
            $insert['merchant_school_total_cash'] = $insert['merchant_school_amount'] +  $insert['merchant_school_cash'];
            $insert['merchant_school_amount'] = $insert['merchant_school_amount'] * (-1);

            if($insert['merchant_school_amount'] <= 1)
            {
                DB::table('tbl_merchant_school_wallet')->insert($insert);
                $data['status'] = 'success_consume'; 
                $data['message'] = 'consumed';
            }
            else
            {
                $data['status'] = 'error'; 
                $data['message'] = 'Amount Must be over 1.';
            }
            
        }
        else
        {
            $data['status'] = 'error'; 
            $data['message'] = 'Not enough Consumable';
        }
        return json_encode($data);
    }

    public function receipt()
    {
        $id = Request::input('merchant_school_id');

        $data['reciept'] = DB::table('tbl_merchant_school_wallet')
        ->where('merchant_school_id', $id)
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_merchant_school_wallet.merchant_school_custmer_id')
        ->leftjoin('tbl_mlm_slot', 'tbl_mlm_slot.slot_owner', '=', 'tbl_customer.customer_id')
        ->first();
        $shop_id = $this->user_info->shop_id;
        $data["shop_address"]    = $this->user_info->shop_street_address;
        $data["shop_contact"]    = $this->user_info->shop_contact;
        $data['company_name']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_name')->value('value');
        $data['company_email']   = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_email')->value('value');
        $data['company_logo']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'receipt_logo')->value('value');
        if(Request::input('pdf') == 'true')
        {
            $view = view('member.merchant_school.reciept', $data);
            return Pdf_global::show_pdf($view);
        }
        else
        {
            return view('member.merchant_school.reciept', $data);
        }
        
        // 
    }
}
