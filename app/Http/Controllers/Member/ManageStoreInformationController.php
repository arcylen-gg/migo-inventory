<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_country;
use App\Models\Tbl_shop;
use App\Globals\AuditTrail;
use Validator;

class ManageStoreInformationController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["store_info"] = Tbl_shop::where("shop_id",$this->user_info->shop_id)->first();
        $data["_country"] = Tbl_country::get();
        return view("member.page.manage_store_info.manage_store_info",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_submit()
    {
        $data["status"] = "";
        $data["status_message"] = "";

        $old_data = AuditTrail::get_table_data("tbl_shop","shop_id",$this->user_info->shop_id);

        $store_name = Request::input("store_name");
        $country = Request::input("country");
        $city = Request::input("city");
        $postal_code = Request::input("postal_code");
        $contact_number = Request::input("contact_number");
        $street_address = Request::input("street_address");

        $update["shop_key"] = $store_name;
        $update["shop_country"] = $country;
        $update["shop_city"] = $city;
        $update["shop_zip"] = $postal_code;
        $update["shop_street_address"] = $street_address;
        $update["shop_contact"] = $contact_number;

        $rules["shop_key"] = "required|unique:tbl_shop,shop_key,".$this->user_info->shop_id.",shop_id|alpha_num";
        $rules["shop_country"] = "required";
        $rules["shop_city"] = "required";
        $rules["shop_zip"] = "required";
        $rules["shop_street_address"] = "required";
        $rules["shop_contact"] = "required";

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
            Tbl_shop::where("shop_id",$this->user_info->shop_id)->update($update);
            $data["status"] = "success";

            $shop_data = AuditTrail::get_table_data("tbl_shop","shop_id",$this->user_info->shop_id);
            AuditTrail::record_logs("Edited","store_information",$this->user_info->shop_id,serialize($old_data),serialize($shop_data));
        }

        return json_encode($data);

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
