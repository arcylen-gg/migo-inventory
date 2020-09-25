<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_manufacturer;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\Vendor;
use Carbon\Carbon;
use Validator;

class ManufacturerController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manufacturer_list()
    {
        $access = Utilities::checkAccess('item-manufacturer', 'access_page');
        if($access == 1)
        {
            $data["_manufacturer"] = Tbl_manufacturer::where("archived",0)->where("manufacturer_shop_id",$this->user_info->shop_id)->orderby('date_created', 'DESC')->get();
            $data["_manufacturer_archived"] = Tbl_manufacturer::where("archived",1)->where("manufacturer_shop_id",$this->user_info->shop_id)->orderby('date_created', 'DESC')->get();
            return view("member.manufacturer.manufacturer_list",$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function index()
    {
        $access = Utilities::checkAccess('item-manufacturer', 'access_page');
        if($access == 1)
        {
            $data["action"] = "/member/item/manufacturer/add_submit";

            $id = Request::input("id");
            if($id)
            {            
                $data["action"] = "/member/item/manufacturer/edit_submit";
                $data["manufacturer"] = Tbl_manufacturer::where("manufacturer_id",$id)->leftJoin("tbl_image", "tbl_manufacturer.manufacturer_image", "=", "tbl_image.image_id")->first();
            }

            return view("member.manufacturer.manufacturer",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function load_manufacturer()
    {
        $data["_manufacturer"] = Tbl_manufacturer::where("manufacturer_shop_id",$this->user_info->shop_id)->get();

        return view("member.load_ajax_data.load_manufacturer",$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_submit()
    {
        $access = Utilities::checkAccess('item-manufacturer', 'access_page');
        if($access == 1)
        {
            $data["status"] = "";
            $data["status_message"] = "";

            $manufacturer_name = Request::input("manufacturer_name");
            $manufacturer_address = Request::input("manufacturer_address");
            $phone_number = Request::input("phone_number");
            $email_address = Request::input("email_address");
            $website = Request::input("website");
            $manufacturer_fname = Request::input("manufacturer_fname");
            $manufacturer_mname = Request::input("manufacturer_mname");
            $manufacturer_lname = Request::input("manufacturer_lname");
            $manufacturer_image = Request::input("manufacturer_image");

            $insert["manufacturer_name"] = $manufacturer_name;
            $insert["manufacturer_address"] = $manufacturer_address;
            $insert["phone_number"] = $phone_number;
            $insert["manufacturer_fname"] = $manufacturer_fname;
            $insert["manufacturer_mname"] = $manufacturer_mname;
            $insert["manufacturer_lname"] = $manufacturer_lname;
            $insert["email_address"] = $email_address;
            $insert["website"] = $website;
            $insert["manufacturer_shop_id"] = $this->user_info->shop_id;
            $insert["date_created"] = Carbon::now();
            $insert["manufacturer_image"] = $manufacturer_image;

            $rules["manufacturer_name"] = "required";
            $rules["phone_number"] = "numeric";
            $rules["email_address"] = "email";

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
                $data["status"] = "success";
                $data["type"] = "manufacturer";
                $id = Tbl_manufacturer::insertGetId($insert);

                if(Request::input("create_vendor") != "")
                {
                    Vendor::ins_vendor($insert);
                }

                $manu_data = AuditTrail::get_table_data("tbl_manufacturer","manufacturer_id",$id);
                AuditTrail::record_logs("Added","manufacturer",$id,"",serialize($manu_data));

                $data["id"] = $id;
            }

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }

    public function edit_submit()
    {
        $access = Utilities::checkAccess('item-manufacturer', 'access_page');
        if($access == 1)
        {
            $data["status"] = "";
            $data["status_message"] = "";

            $old_data = AuditTrail::get_table_data("tbl_manufacturer","manufacturer_id",Request::input("manufacturer_id"));

            $manufacturer_name = Request::input("manufacturer_name");
            $manufacturer_address = Request::input("manufacturer_address");
            $phone_number = Request::input("phone_number");
            $email_address = Request::input("email_address");
            $website = Request::input("website");
            $manufacturer_image = Request::input("manufacturer_image");

            $update["manufacturer_name"] = $manufacturer_name;
            $update["manufacturer_address"] = $manufacturer_address;
            $update["phone_number"] = $phone_number;
            $update["email_address"] = $email_address;
            $update["website"] = $website;
            $update["date_updated"] = Carbon::now();
            $update["manufacturer_image"] = $manufacturer_image;
            
            $rules["manufacturer_name"] = "required";
            $rules["phone_number"] = "numeric";
            $rules["email_address"] = "email";

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
                Tbl_manufacturer::where("manufacturer_id",Request::input("manufacturer_id"))->update($update);

                $manu_data = AuditTrail::get_table_data("tbl_manufacturer","manufacturer_id",Request::input("manufacturer_id"));
                AuditTrail::record_logs("Edited","manufacturer",Request::input("manufacturer_id"),serialize($old_data),serialize($manu_data));
            }

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function archived($id, $action)
    {   
        $access = Utilities::checkAccess('item-manufacturer', 'access_page');
        if($access == 1)
        {
            $data["manufacturer"] = Tbl_manufacturer::where("manufacturer_id",$id)->first();
            $data["action"] = $action;

            return view("member.manufacturer.confirm",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function archived_submit()
    {        
        $access = Utilities::checkAccess('item-manufacturer', 'access_page');
        if($access == 1)
        {
            $id = Request::input("manufacturer_id");
            $action = Request::input("action");

            $update["archived"] = 0;
            if($action == "archived")
            {
                $update["archived"] = 1;
            }

            Tbl_manufacturer::where("manufacturer_id",$id)->update($update);
             
             $manu_data = AuditTrail::get_table_data("tbl_manufacturer","manufacturer_id",$id);
             AuditTrail::record_logs($action,"manufacturer",$id,"",serialize($manu_data));

            $data["status"] = "success";

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
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
