<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_employee;
use App\Models\Tbl_position;
use App\Globals\Employee;
use Validator;
use Carbon\Carbon;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use Crypt;
class AgentController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["_employee"] = Tbl_employee::position()->where("tbl_employee.archived",0)->where("shop_id",$this->user_info->shop_id)->get();
        $data["_employee_archived"] = Tbl_employee::position()->where("tbl_employee.archived",1)->where("shop_id",$this->user_info->shop_id)->get();

        return view("member.employee.employee_list",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $access = Utilities::checkAccess("pis-agent","add");
        if($access != 0)
        {
            $data["_position"] = Tbl_position::where("position_code","sales_agent")->where("position_shop_id",$this->user_info->shop_id)->get();

            return view('member.employee.employee_add',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    
    public function edit($id)
    {
        $access = Utilities::checkAccess("pis-agent","edit");
        if($access != 0)
        {
            $data["_position"] = Tbl_position::where("position_code","sales_agent")->where("position_shop_id",$this->user_info->shop_id)->get();

            $data["edit"] = Tbl_employee::where("employee_id",$id)->first();
            $data["action"] = "/member/pis/agent/edit_submit";

            return view('member.employee.employee_edit',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function archived($id,$action)
    {

        $access = Utilities::checkAccess("pis-agent","archived");
        if($access != 0)
        {
            $data["employee_id"] = $id;
            $data["employee_info"] = Tbl_employee::position()->where("employee_id",$id)->first();
            $data["action"] = $action;

            return view("member.employee.confirm_employee",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function archived_submit()
    {
        $id = Request::input("employee_id");
        $action = Request::input("action");

        $update["archived"] = 0;
        if($action == "archived")
        {
            $update["archived"] = 1;
        }

        Tbl_employee::where("employee_id",$id)->update($update);

        $agent_data = AuditTrail::get_table_data("tbl_employee","employee_id",$id);
        AuditTrail::record_logs($action,"agent",$id,"",serialize($agent_data));

        $data["status"] = "success";

        return json_encode($data);
    }
    public function edit_submit()
    {
        $data["status"] = null;
        $data["status_message"] = null;

        $id = Request::input("employee_id");


        $old_agent_data = AuditTrail::get_table_data("tbl_employee","employee_id",$id);

        $first_name= Request::input("first_name");
        $last_name = Request::input("last_name");
        $middle_name = Request::input("middle_name");
        $position = Request::input("position");
        $email_address = Request::input("email_address");
        $password = Request::input("password");
        $username = Request::input("username");

        $insert["shop_id"] = $this->user_info->shop_id;
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

            $new_agent_data = AuditTrail::get_table_data("tbl_employee","employee_id",$id);
            AuditTrail::record_logs("Edited","agent",$id,serialize($old_agent_data),serialize($new_agent_data));
        }

    return json_encode($data);
    }
    public function add_submit()
    {        
        $data["status"] = null;
        $data["status_message"] = null;

        $first_name= Request::input("first_name");
        $last_name = Request::input("last_name");
        $middle_name = Request::input("middle_name");
        $position = Request::input("position");
        $email_address = Request::input("email_address");
        $password = Request::input("password");
        $username = Request::input("username");

        $insert["shop_id"] = $this->user_info->shop_id;
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
        $rule["email"] = 'required|email|unique:tbl_employee,email';
        $rule["password"] = 'required';
        $rule["username"] = 'required|alpha_num|unique:tbl_employee,username';

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
            $agent_id = Tbl_employee::insertGetId($insert);


            $agent_data = AuditTrail::get_table_data("tbl_employee","employee_id",$agent_id);
            AuditTrail::record_logs("Added","agent",$agent_id,"",serialize($agent_data));
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
