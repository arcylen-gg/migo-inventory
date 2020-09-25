<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Globals\Utilities;
use App\Models\Tbl_unit_measurement_type;
use Validator;
use Carbon\Carbon;
class UnitMeasurementTypeController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access = Utilities::checkAccess('item-um-type', 'access_page');
        if($access == 1)
        { 
            $data["_um_type_parent"] = Tbl_unit_measurement_type::where("um_type_parent_id",0)->where("archived",0)->where("shop_id",$this->user_info->shop_id)->orderby('created_at', 'DESC')->get();
            $data["_um_type"] = Tbl_unit_measurement_type::where("archived",0)->where("shop_id",$this->user_info->shop_id)->orderby('created_at', 'DESC')->get();
            
            $data["um_type"] = null;
            foreach ($data["_um_type"] as $key => $value) 
            {
                if($value->um_type_parent_id == 0)
                {
                    $data["um_type"] .= "<tr>
                                            <td>".$value->um_type_name."</td>
                                            <td>".$value->um_type_abbrev."</td>
                                            <td class='text-center'><a class='popup' size='md' link='/member/item/um_type/edit/".$value->um_type_id."'>Edit</a></td>
                                          </tr>";
                    $data["sub"] = Tbl_unit_measurement_type::where("um_type_parent_id",$value->um_type_id)->where("shop_id",$this->user_info->shop_id)->orderby('created_at', 'DESC')->get();
                    foreach ($data["sub"] as $key2 => $value2) 
                    {
                        $data["um_type"] .= "<tr>
                                                <td><span style='margin-left:20px'>".$value2->um_type_name."</span></td>
                                                <td>".$value2->um_type_abbrev."</td>
                                                <td class='text-center'><a class='popup' size='md' link='/member/item/um_type/edit/".$value2->um_type_id."'>Edit</a></td>
                                            </tr>";
                    }

                }
            }

            return view('member.unit_of_measurement.um_type.um_type',$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function add()
    {
        $access = Utilities::checkAccess('item-um-type', 'access_page');
        if($access == 1)
        { 
            $data["_um_type_parent"] = Tbl_unit_measurement_type::where("um_type_parent_id",0)->where("archived",0)->where("shop_id",$this->user_info->shop_id)->get();
            return view('member.unit_of_measurement.um_type.um_type_add',$data);
        }
        else
        {
            return $this->show_no_access();
        }        
    }
    public function add_submit()
    {
        $access = Utilities::checkAccess('item-um-type', 'access_page');
        if($access == 1)
        { 
            $um_type_parent = Request::input("um_type_parent");
            $um_type_name = Request::input("um_type_name");
            $um_type_abbre = Request::input("um_type_abbre");

            if($um_type_parent == 0)
            {
                $um_type_abbre = "";
            }

            $ins["um_type_parent_id"] = $um_type_parent;
            $ins["um_type_name"] = $um_type_name;
            $ins["um_type_abbrev"] = $um_type_abbre;
            $ins["shop_id"] = $this->user_info->shop_id;
            $ins["created_at"] = Carbon::now();

            $rule["um_type_parent_id"] = "required";
            $rule["um_type_name"] = "required";
            $rule["um_type_abbrev"] = "required|unique:tbl_unit_measurement_type,um_type_abbrev";
            if($um_type_parent == 0)
            {
                $rule["um_type_abbrev"] = "unique:tbl_unit_measurement_type,um_type_abbrev";
            }

            $validator = Validator::make($ins, $rule);

            $data["status"] = null;
            $data["status_message"] = null;
            if($validator->fails())
            {
                $data["status"] = "error";
                foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                {
                    $data["status_message"] .= $message;
                }
            }
            
            if($data["status"] == null)
            {
                $data["status"] = "success";
                Tbl_unit_measurement_type::insert($ins);
            }

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function edit($id)
    {      
        $access = Utilities::checkAccess('item-um-type', 'access_page');
        if($access == 1)
        { 
            $data["_um_type_parent"] = Tbl_unit_measurement_type::where("um_type_parent_id",0)->where("archived",0)->where("shop_id",$this->user_info->shop_id)->get();
            $data["edit"] = Tbl_unit_measurement_type::where("um_type_id",$id)->where("shop_id",$this->user_info->shop_id)->first();

            return view('member.unit_of_measurement.um_type.um_type_edit',$data);
        }
        else
        {
            return $this->show_no_access();
        } 
    }
    public function edit_submit()
    {
        $access = Utilities::checkAccess('item-um-type', 'access_page');
        if($access == 1)
        { 
            $id = Request::input("um_type_id");
            $um_type_parent = Request::input("um_type_parent");
            $um_type_name = Request::input("um_type_name");
            $um_type_abbre = Request::input("um_type_abbre");

            if($um_type_parent == 0)
            {
                $um_type_abbre = "";
            }

            $data["status"] = null;
            $data["status_message"] = null;

            $old = Tbl_unit_measurement_type::where("um_type_id",$id)->first();
            if($old->um_type_parent_id == 0 )
            {
                if($um_type_parent != $old->um_type_parent_id)
                {
                    $data["status"] = "error";
                    $data["status_message"] .= "You can't place the parent U/M types under another parent.";                
                }
                $um_type_parent = 0 ;
            }

            $update["um_type_parent_id"] = $um_type_parent;
            $update["um_type_name"] = $um_type_name;
            $update["um_type_abbrev"] = $um_type_abbre;
            $update["created_at"] = Carbon::now();

            $rule["um_type_parent_id"] = "required";
            $rule["um_type_name"] = "required";
            $rule["um_type_abbrev"] = "required|unique:tbl_unit_measurement_type,um_type_abbrev";
            if($um_type_parent == 0)
            {
                $rule["um_type_abbrev"] = "unique:tbl_unit_measurement_type,um_type_abbrev";
            }

            $validator = Validator::make($update, $rule);

            if($validator->fails())
            {
                $data["status"] = "error";
                foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                {
                    $data["status_message"] .= $message;
                }
            }
            
            if($data["status"] == null)
            {
                $data["status"] = "success";
                Tbl_unit_measurement_type::where("um_type_id",$id)->update($update);
            }

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
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
