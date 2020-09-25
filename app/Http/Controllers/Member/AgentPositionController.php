<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Models\Tbl_position;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use App\Globals\Invoice;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\CommissionCalculator;
class AgentPositionController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["_position"] = Tbl_position::where("archived",0)->where("position_shop_id",$this->user_info->shop_id)->get();
        $data["_position_archived"] = Tbl_position::where("archived",1)->where("position_shop_id",$this->user_info->shop_id)->get();
        
        return view('member.employee.employee_position.employee_position_list',$data);
    }
    public function load_position()
    {        
        $data["_position"] = Tbl_position::where("archived",0)->where("position_shop_id",$this->user_info->shop_id)->get();

        return view("member.employee.employee_add",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $access = Utilities::checkAccess("pis-agent-position","add");
        if($access != 0)
        {
           $data['commission'] = CommissionCalculator::check_settings($this->user_info->shop_id);
           return view('member.employee.employee_position.employee_position_add',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function edit($id)
    {
        $access = Utilities::checkAccess("pis-agent-position","edit");
        if($access != 0)
        {
            $data["edit"] = Tbl_position::where("position_id",$id)->first();
            $data['commission'] = CommissionCalculator::check_settings($this->user_info->shop_id);

            return view('member.employee.employee_position.employee_position_edit',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function edit_submit()
    {
        $id = Request::input("position_id");

        $old_data = AuditTrail::get_table_data("tbl_position","position_id",$id);

        $data["status"] = null;
        $data["status_message"] = null;

        $position_name = Request::input("position_name");
        $commission_percent = Request::input("commission_percent") != null ? Request::input("commission_percent") : 0;

        $update["position_name"] = $position_name;
        $update["commission_percent"] = $commission_percent;

        $rule["position_name"] = "required";

         $validation = Validator::make($update, $rule);

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
            Tbl_position::where("position_id",$id)->update($update);

            $position = AuditTrail::get_table_data("tbl_position","position_id",$id);
            AuditTrail::record_logs("Edited","agent_position",$id,serialize($old_data),serialize($position));
        }

        return json_encode($data);
    }

    public function archived($id,$action)
    {
        $access = Utilities::checkAccess("pis-agent-position","archived");
        if($access != 0)
        {
            $data["position_id"] = $id;
            $data["position_info"] = Tbl_position::where("position_id",$id)->first();
            $data["action"] = $action;

            return view("member.employee.employee_position.confirm",$data);
        }
        else
        {            
            return $this->show_no_access_modal();
        }
    }
    public function archived_submit()
    {
        $id = Request::input("position_id");
        $action = Request::input("action");

        $update["archived"] = 0;
        if($action == "archived")
        {
            $update["archived"] = 1;
        }

        Tbl_position::where("position_id",$id)->update($update);

        $position = AuditTrail::get_table_data("tbl_position","position_id",$id);
        AuditTrail::record_logs($action,"agent_position",$id,"",serialize($position));

        $data["status"] = "success";

        return json_encode($data);
    }

    public function add_submit()
    {        
        $data["status"] = null;
        $data["status_message"] = null;
        $position_name = Request::input("position_name");
        $position_code = Request::input("position_code");
        $commission_percent = Request::input("commission_percent") != null ? Request::input("commission_percent") : 0;

        $insert["position_name"] = $position_name;
        $insert["position_code"] = $position_code;
        $insert["commission_percent"] = $commission_percent;
        $insert["position_created"] = Carbon::now();
        $insert["position_shop_id"] = $this->user_info->shop_id;

        $rule["position_name"] = "required";
        $rule["position_code"] = "required";

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
            $data["id"] = Tbl_position::insertGetId($insert);
            $data["status"] = "success";
            $data["type"] = "position";

            $position = AuditTrail::get_table_data("tbl_position","position_id",$data["id"]);
            AuditTrail::record_logs("Added","agent_position",$data["id"],"",serialize($position));
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
