<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_payment_method;
use App\Globals\Invoice; 
use Validator;
use Carbon\Carbon;
class MaintenancePaymentMethodController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["_payment_method"] = Tbl_payment_method::where("archived",0)->where("shop_id",$this->user_info->shop_id)->get();
        $data["_payment_method_archived"] = Tbl_payment_method::where("archived",1)->where("shop_id",$this->user_info->shop_id)->get();

        return view("member.maintenance.payment_method.payment_method_list",$data);
    }
    public function load_payment_method()
    {
        $data["_payment_method"] = Tbl_payment_method::where("archived",0)->where("shop_id",$this->user_info->shop_id)->get();
        return view('member.load_ajax_data.load_payment_method', $data);
    }
    public function add()
    {
        $data["action"] = "/member/maintenance/payment_method/add_submit";

        $id = Request::input("id");
        if($id)
        {            
            $data["action"] = "/member/maintenance/payment_method/edit_submit";
            $data["payment_method"] = Tbl_payment_method::where("payment_method_id",$id)->first(); 
        }

        return view("member.maintenance.payment_method.payment_method",$data);
    }
    public function add_submit()
    {
        $shop_id = Invoice::getShopId();
        $data["status"] = "";
        $data["status_message"] = "";

        $ctr_default = Tbl_payment_method::where("shop_id",$shop_id)->where("isDefault",1)->count();
        if($ctr_default == 0)
        {
            $insert["isDefault"] = 1;
        }

        $insert["payment_name"] = Request::input("payment_method_name");
        $insert["shop_id"] = $shop_id;

        $rules["payment_name"] = "required";

        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }

        if($data["status"] == "")
        {
            $payment_method_id = Tbl_payment_method::insertGetId($insert);

            $data["status"]         = "success";
            $data["type"]           = "payment_method";   
            $data["message"]        = "success";
            $data["payment_method_id"] = $payment_method_id;
        }

        return json_encode($data);
    }
    public function edit_submit()
    {
        $shop_id = Invoice::getShopId();
        $data["status"] = "";
        $data["status_message"] = "";

        $id = Request::input("payment_method_id");

        $ctr_default = Tbl_payment_method::where("shop_id",$shop_id)->where("isDefault",1)->count();
        if($ctr_default == 0)
        {
            $update["isDefault"] = 1;
        }

        $update["payment_name"] = Request::input("payment_method_name");

        $rules["payment_name"] = "required";

        $validator = Validator::make($update, $rules);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }

        if($data["status"] == "")
        {
            $data["status"] = "success";
            Tbl_payment_method::where("payment_method_id",$id)->update($update);
        }

        return json_encode($data);
    }
    public function archived($id, $action)
    {
        $data["payment_method_id"] = $id;
        $data["payment_method"] = Tbl_payment_method::where("payment_method_id",$id)->first();
        $data["action"] = $action;

        return view("member.maintenance.payment_method.confirm_payment_method",$data);
    }
    public function archived_submit()
    {
        $id = Request::input("payment_method_id");
        $action = Request::input("action");

        $data["status"] = "";

        $update["archived"] = 0;
        if($action == "archived")
        {
            $update["archived"] = 1;
        }

        $is_default = Tbl_payment_method::where("payment_method_id",$id)->where("isDefault",1)->first();
        if($is_default != null)
        {
            $data["status"] = "error";
            $data["status_message"] = "This payment method is selected as default";
        }

        if($data["status"] == "")
        {
            Tbl_payment_method::where("payment_method_id",$id)->update($update);
            $data["status"] = "success";
        }

        return json_encode($data);
    }
    public function update_default()
    {
        $id = Request::input("id");
        $shop_id = Invoice::getShopId();

        $all_payment_method = Tbl_payment_method::where("shop_id",$shop_id)->get(); 
        foreach ($all_payment_method as $key => $value) 
        {
           $update["isDefault"] = 0;

           Tbl_payment_method::where("payment_method_id",$value->payment_method_id)->update($update);
        }

        $update["isDefault"] = 1;
        Tbl_payment_method::where("payment_method_id",$id)->update($update);

        $data["status"] = "success";

        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
