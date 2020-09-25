<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_shop;
use App\Models\Tbl_user;
use Crypt;
use Carbon\Carbon;
use Validator;
use App\Globals\Utilities;
use App\Globals\AuditTrail;

class UtilitiesClientController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Crypt::decrypt("eyJpdiI6InFnOEU5d1g5YWt5VGc3VU12bkRvXC9BPT0iLCJ2YWx1ZSI6Imk1RERrUkQ0dVNMWmpoa3JBSkh2djc5cUFVaUcxV2Z3QUo5bVoyejU2UlE9IiwibWFjIjoiZDAyYjExNWIxYjhiODEyYmJiOWZiYjc2MWZmOTg2NDRiYjhkMTk0ODI0MWNlN2QxMWU3NzAzYWNkNWU2YTdmNSJ9"));
        $access = Utilities::checkAccess("utilities-client-list","access_page");
        if($access != 0)
        {
            $data["_shop_info"] = Tbl_user::shop()->where("user_id",$this->user_info->user_id)->get();

            return view("member.client.client",$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function update($id)
    {     
        $access = Utilities::checkAccess("utilities-client-list","update_password");
        if($access != 0)
        {            
            $data["shop"] = Tbl_user::shop()->where("user_id",$id)->first();
            return view("member.client.client_update",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function update_submit()
    {
        $data["status"] = "";
        $data["status_message"] = "";

        $user_id = Request::input("user_id");
        $shop_id = Request::input("shop_id");
        $first_name = Request::input("first_name");
        $last_name = Request::input("last_name");
        $email_address = Request::input("email_address");
        $old_password = Request::input("old_password");
        $new_password = Request::input("new_password");
        $confirm_password = Request::input("confirm_password");

        $old_data = AuditTrail::get_table_data("tbl_user","user_id",$user_id);
        if($user_id != null) 
        { 
            $shop_data = Tbl_user::shop()->where("user_id",$user_id)->where("shop_id",$shop_id)->first();  

            if(Request::input("update_password") != null)
            {
                if($old_password == Crypt::decrypt($shop_data->user_password))
                {

                    if($new_password == $confirm_password)
                    {
                        $update["user_first_name"] = $first_name;
                        $update["user_last_name"] = $last_name;
                        $update["user_email"] = $email_address;
                        $update["user_password"] = Crypt::encrypt($new_password);

                        $rule["user_first_name"] = "required";//|unique:tbl_user,user_first_name,".$user_id.",user_id
                        $rule["user_last_name"] = "required";
                        $rule["user_password"] = "required";
                        $rule["user_email"] = "required|email|unique:tbl_user,user_email,".$user_id.",user_id";

                        $validator = Validator::make($update, $rule);
                        if($validator->fails())
                        {
                            $data["status"] = "error";
                            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                            {
                                $data["status_message"] .= $message;
                            }
                        }
                        else
                        {
                            /*IF USER DOESNT CHANGE ANY INFO*/
                            if($email_address == $shop_data->user_email && $first_name == $shop_data->user_first_name && $last_name == $shop_data->user_last_name && Crypt::encrypt($new_password) == Crypt::decrypt($shop_data->user_password) && Crypt::encrypt($confirm_password) == Crypt::decrypt($shop_data->user_password))
                            {
                                $data["status"] = "no_changes_made";                  
                            }
                            else
                            {
                                /*IF USEREMAIL CHANGE*/
                                Tbl_user::where("user_shop",$shop_id)->where("user_id",$user_id)->update($update);
                                $data['status'] = "success_update";
                                $data['redirect'] = "/login";
                            }                  
                        }
                    }
                    else
                    {
                        $data["status"] = "error";
                        $data["status_message"] = "New Password not match.";
                    }
                }
                elseif($old_password == '')
                {
                    $data["status"] = "error";
                    $data["status_message"] = "Please enter your password";
                }
                else
                {
                    $data["status"] = "error";
                    $data["status_message"] = "Password not match.";
                }    
            }      
            else
            { 
               /* IF UPDATE PASSWORD IS NOT CHECKED AND NO CHANGES HAVE MADE*/
                $update["user_first_name"] = $first_name;
                $update["user_last_name"] = $last_name;
                $update["user_email"] = $email_address;

                $rule["user_first_name"] = "required";//|unique:tbl_user,user_first_name,".$user_id.",user_id
                $rule["user_last_name"] = "required";
                $rule["user_last_name"] = "required";
                $rule["user_email"] = "required|email|unique:tbl_user,user_email,".$user_id.",user_id";

                $validator = Validator::make($update, $rule);
                if($validator->fails())
                {
                    $data["status"] = "error";
                    foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                    {
                        $data["status_message"] .= $message;
                    }
                }
                else
                {
                    /*IF USER DOESNT CHANGE ANY INFO*/
                    if($email_address == $shop_data->user_email && $first_name == $shop_data->user_first_name && $last_name == $shop_data->user_last_name)
                    {
                        $data["status"] = "no_changes_made";                  
                    }
                    else
                    {
                        /*IF USEREMAIL CHANGE*/
                        Tbl_user::where("user_shop",$shop_id)->where("user_id",$user_id)->update($update);
                        $data['status'] = "success_update";
                        $data['redirect'] = "/login";
                    }
                }
            }
        }   
        else
        {
            if($new_password == $confirm_password)
            {
                $ins_user["user_first_name"] = $first_name;
                $ins_user["user_last_name"] = $last_name;
                $ins_user["user_email"] = $email_address;
                $ins_user["user_password"] = Crypt::encrypt($new_password);
                $ins_user["user_date_created"] = Carbon::now();
                $ins_user["user_shop"] = $shop_id;

                $rule["user_first_name"] = "required";
                $rule["user_last_name"] = "required";
                $rule["user_password"] = "required";
                $rule["user_email"] = "required|email|unique:tbl_user,user_email";

                $validator = Validator::make($ins_user, $rule);
                if($validator->fails())
                {
                    $data["status"] = "error";
                    foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                    {
                        $data["status_message"] .= $message;
                    }
                }
                else
                {
                    Tbl_user::insert($ins_user);
                    $data["status"] = "success";                    
                }   
            }
            else
            {
                $data["status"] = "error";
                $data["status_message"] = "New Password not match.";
            }
        }
        if($data["status"] == "success")
        {            
            $user_data = AuditTrail::get_table_data("tbl_user","user_id",$user_id);
            AuditTrail::record_logs("Edited","user",$user_id,serialize($old_data),serialize($user_data));
        }
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
