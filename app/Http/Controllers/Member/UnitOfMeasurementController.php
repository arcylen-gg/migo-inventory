<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;

use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_unit_measurement_type;
use App\Models\Tbl_item;
use App\Models\Tbl_settings;
use App\Models\Tbl_um;
use App\Globals\Purchasing_inventory_system;
use App\Globals\UnitMeasurement;
use App\Globals\AuditTrail;
use App\Globals\Utilities;
use Carbon\Carbon;
use Validator;
use Session;

class UnitOfMeasurementController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    public function add_um()
    {
        $data['um_type'] = Request::input("um_type");
        $data['action'] = '/member/pis/um_add_submit';

        return view("member.unit_of_measurement.pis_um.um",$data);
    }
    public function edit_um_submit()
    {
        $id = Request::input("um_id");

        $old_um_data = AuditTrail::get_table_data("tbl_um","id",$id);
        $up["um_name"] = Request::input("um_name");
        $up["um_abbrev"] = Request::input("um_abbrev");

        Tbl_um::where("id",$id)->update($up);

        $um_data = AuditTrail::get_table_data("tbl_um","id",$id);
        AuditTrail::record_logs("Edited","pis_um",$id,serialize($old_um_data),serialize($um_data));

        $data["type"] = "pis-um";
        return json_encode($data);
    }
    public function add_um_submit()
    {
        $type = Request::input("um_type");
        $um_name = Request::input("um_name");
        $um_abbrev = Request::input("um_abbrev");

        $type_1 = 0;
        if($type == 'base') $type_1 = 1;
        else                $type_1 = 0;

        $check = Tbl_um::where("um_shop_id",UnitMeasurement::getShopId())->where("is_based",$type_1)->where("um_name",$um_name)->first();

        if($check == null)
        {
            $ins['um_name'] = $um_name;
            $ins['um_abbrev'] = $um_abbrev;  
            $ins['is_based'] = $type_1;            
            $ins['um_shop_id'] = UnitMeasurement::getShopId();

            $um_id = Tbl_um::insertGetId($ins);

            $data['id'] = $um_id;
            $data['type'] = 'pis-um'; 
            $data['um_type'] = $type."-um";   

            $um_data = AuditTrail::get_table_data("tbl_um","id",$um_id);
            AuditTrail::record_logs("Added","pis_um",$um_id,"",serialize($um_data));
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = 'U/M is already used';
        }

        return json_encode($data);
    }
    public function um_list_pis()
    {
        $data['_um_n'] = Tbl_um::where("um_shop_id",$this->user_info->shop_id)->where("is_based",0)->get();
        $data['_um_n_b'] = Tbl_um::where("um_shop_id",$this->user_info->shop_id)->where("is_based",1)->get();

        return view("member.unit_of_measurement.pis_um.um_list",$data);
    }
    public function load_pis_um($type = '')
    {
        $type_1 = 0;
        if($type == 'notbase-um') $type_1 = 0;
        else                      $type_1 = 1;
        $data["_um"] = Tbl_um::where("is_based",$type_1)->get();

        return view("member.load_ajax_data.load_pis_um",$data);
    }
    public function edit_um($id)
    {
        $data['id'] = $id;
        $data['um'] = Tbl_um::where("id",$id)->first();
        $data['action'] = '/member/pis/um_edit_submit';

        return view("member.unit_of_measurement.pis_um.um",$data);
    }
    public function check()
    {
        $um_id = Request::input("id");
        $item_id = Request::input("item_id");
        $item_id = $item_id == null ? 0 : $item_id;
        $check = Tbl_settings::where("settings_key","pis-jamestiong")->where("settings_value","enable")->where("shop_id",$this->user_info->shop_id)->value("settings_setup_done");

        $data["status"] = "";
        $data["um_multi"] = Tbl_unit_measurement_multi::where("multi_um_id",$um_id)->where("is_base",0)->count();
        if($data["um_multi"] != 0)
        {
            if($check != 0)
            {
                $data["status"] = "pop-up-um";
                $data["action"] = "/member/item/um/add_base/".$um_id."/".$item_id;
            }            
        }

        return json_encode($data);
    }
    public function add_base($id,$item_id)
    {
        $data["um_id"] = $id;
        $ctr = Tbl_unit_measurement::where("um_item_id",$item_id)->where("parent_basis_um",$id)->first();
        if($ctr == null)
        {
            $data["um"] = Tbl_unit_measurement::where("um_id",$id)->first();
            $data["um_multi"] = Tbl_unit_measurement_multi::where("multi_um_id",$id)->get();

            $count = Tbl_unit_measurement::where("parent_basis_um",$id)->count();

            $ins["um_shop"] = $this->user_info->shop_id;
            $ins["um_name"] = $data["um"]->um_name;
            $ins["um_date_created"] = Carbon::now();
            $ins["um_type"] = $data["um"]->um_type;
            $ins["parent_basis_um"] = $id;

            $um_id = Tbl_unit_measurement::insertGetId($ins);

            foreach ($data["um_multi"] as $key => $value) 
            {
                $ins_multi["multi_um_id"] = $um_id;
                $ins_multi["multi_name"] = $value->multi_name;
                $ins_multi["unit_qty"] = $value->unit_qty;
                $ins_multi["multi_abbrev"] = $value->multi_abbrev;
                $ins_multi["is_base"] = $value->is_base;

                Tbl_unit_measurement_multi::insert($ins_multi);
            }
            
            $data["base"] = Tbl_unit_measurement_multi::where("multi_um_id",$um_id)->where("is_base",1)->first();
            $data["sub"] = Tbl_unit_measurement_multi::where("multi_um_id",$um_id)->where("is_base",0)->first();

        }
        else
        {
            $data["base"] = Tbl_unit_measurement_multi::where("multi_um_id",$ctr->um_id)->where("is_base",1)->first();
            $data["sub"] = Tbl_unit_measurement_multi::where("multi_um_id",$ctr->um_id)->where("is_base",0)->first();
        }
        return view("member.unit_of_measurement.add_base",$data);
    }
    public function add_base_submit()
    {
        $id = Request::input("um_id_2");
        $um_id = Request::input("um_id");
        $sub_multi_id = Request::input("sub_multi_id");

        $update_qty["unit_qty"] = Request::input("sub_qty");

        Tbl_unit_measurement_multi::where("multi_id",$sub_multi_id)->update($update_qty);

        Session::put("um_id",$um_id);

        $data["type"] = "base-um";
        $data["id"] = $id;
        return json_encode($data);
    }
    public function archived($id, $action)
    {
        $access = Utilities::checkAccess("item-unit-measurement","access_page");
        if($access != 0)
        {
            $data["um_id"] = $id;
            $data["um_info"] = Tbl_unit_measurement::where("um_id",$id)->first();
            $data["action"] = $action;

            return view("member.unit_of_measurement.confirm_um",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function archived_submit()
    {
        $id = Request::input("um_id");
        $action = Request::input("action");

        $chk = Tbl_item::where("item_measurement_id",$id)->count();
        $update["um_archived"] = 0;

        $return['call_function'] = 'success_status';
        $data["status"] = "success";
        $data["status_message"] = ucfirst($action)." unit of measurement";
        
        if($action == "archive")
        {
            if($chk <= 0)
            {
                $update["um_archived"] = 1;
            }
            else
            {
                $data["status"] = "error";
                $data["status_message"] = "The unit of measurement is in used";
            }
        }

        Tbl_unit_measurement::where("um_id",$id)->update($update);

        $um_data = Tbl_unit_measurement::where('um_shop', $this->user_info->shop_id)->where('um_id', $id)->first()->toArray();
        $um_multi_data = Tbl_unit_measurement_multi::where('multi_um_id', $id)->get()->toArray();
        
        $new_data = null;
        foreach ($um_multi_data as $key => $value)
        {
            $new_data['um'] = $um_data;
            $new_data['um_multi'] = $um_multi_data;
        }    
        AuditTrail::record_logs(ucfirst($action), 'um', $id, "", serialize($new_data));

        return json_encode($data);

    }
    public function index()
    {
        $access = Utilities::checkAccess('item-unit-measurement', 'access_page');
        if($access == 1)
        {
            UnitMeasurement::archived_um();

            $data["_um"] = Tbl_unit_measurement::where("um_archived",0)
                                                        ->groupBy("tbl_unit_measurement.um_id")
                                                        ->where("um_shop",$this->user_info->shop_id)
                                                        ->orderby("um_id",'DESC')
                                                        ->get();
            foreach ($data["_um"] as $key => $value) 
            {
                $multi = Tbl_unit_measurement_multi::where("multi_um_id",$value->um_id)->get();
                foreach ($multi as $key1 => $value1) 
                {
                    if($value1->is_base == 1)
                    {
                        $data["_um"][$key]->um_base_name = $value1->multi_name;
                        $data["_um"][$key]->um_base_abbrev = $value1->multi_abbrev;
                    }                
                }
            }


            $data["_um_archived"] = Tbl_unit_measurement::where("um_archived",1)
                                                        ->where("is_multi",0)
                                                        ->groupBy("tbl_unit_measurement.um_id")
                                                        ->where("um_shop",$this->user_info->shop_id)
                                                        ->orderby("um_id",'DESC')
                                                        ->get();

            foreach ($data["_um_archived"] as $key => $value) 
            {
                $multi = Tbl_unit_measurement_multi::where("multi_um_id",$value->um_id)->orderby("multi_id",'DESC')->get();
                foreach ($multi as $key1 => $value1) 
                {
                    if($value1->is_base == 1)
                    {
                        $data["_um_archived"][$key]->um_base_name = $value1->multi_name;
                        $data["_um_archived"][$key]->um_base_abbrev = $value1->multi_abbrev;
                    }                
                }
            }
            // dd($data["_um"]);
            return view("member.unit_of_measurement.unit_measurement",$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function select_um()
    {
        $unit_id = Request::input("unit_id");

        $data = UnitMeasurement::select_um($unit_id,'json');

        return $data;
    }

    public function load_um()     
    {
        $data["_um"] = UnitMeasurement::load_um();

        return view('member.load_ajax_data.load_unit_measurement', $data);
    }

    public function load_one_um($um_id)     
    {
        $data["_um"] = UnitMeasurement::load_one_um($um_id);

        return view('member.load_ajax_data.load_one_unit_measure', $data);
    }
 public function load_one_um_multi($um_id)     
    {
        $data["_um_multi"] = UnitMeasurement::load_one_um($um_id);
        // dd($data["_um_multi"]);
        return view('member.load_ajax_data.load_um_multi', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $access = Utilities::checkAccess('item-unit-measurement', 'access_page');
        if($access == 1)
        {
            $data["_um_type"] = Tbl_unit_measurement_type::where("um_type_parent_id",0)->where("archived",0)->where("shop_id",$this->user_info->shop_id)->get();

            return view("member.unit_of_measurement.unit_measurement_add",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function add_submit()
    {
        $access = Utilities::checkAccess('item-unit-measurement', 'access_page');
        if($access == 1)
        {
            $data["status_message"] = "";
            $data["status"] = "";

            $set_name = Request::input("um_set_name");
            $base_name = Request::input("base_name");
            $base_abbrev = Request::input("base_abbreviation");
            $um_type = Request::input("um_type");

            $related = Request::input("related_name");
            $abbrev = Request::input("related_abb");
            $qty = Request::input("related_qty");

            $insert["um_name"] = $set_name;
            // $insert["um_base_name"] = $base_name;
            // $insert["um_base_abbrev"] = $base_abbrev;
            $insert["um_date_created"] = Carbon::now();
            $insert["um_shop"] = $this->user_info->shop_id;
            $insert["um_type"] = $um_type;

            $rule["um_name"]            = "required";
            // $rule["um_base_name"]       = "required";
            // $rule["um_base_abbrev"]     = "required";
            $rule["um_type"]            = "";
            //dd(Request::input());
            $validator = Validator::make($insert,$rule);
            if(Request::input("um_type_type") == null)
            {
                //dd(Request::input("um_type_type") == null);
                $data["status"] = "error";
                $data["status_message"] .= "Please select the type of measurement."; 
            }
            else
            {
                if(Request::input("um_type_type") != "selected_other")
                {
                    if(Request::input("um_child_type") == null)
                    {
                        //dd('tru');
                        $data["status"] = "error";
                        $data["status_message"] .= "Please select a base unit of measurement.";                 
                    }         
                }
                

            }
            $ctr = Tbl_unit_measurement::where("um_name",$set_name)->where("um_shop",UnitMeasurement::getShopId())->count();
            if($ctr >= 1)
            {
                $data["status"] = "error";
                $data["status_message"] .= "The U/M Name is already been taken";
            } 
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
                $rule = [];
                if($related != null && $abbrev != null & $qty != null)
                {
                    //dd(123 + $base_name);
                    $ins_related["multi_name"] = $base_name;
                    $ins_related["multi_abbrev"] = $base_abbrev;
                    $ins_related["unit_qty"] = 1;

                    $rule["multi_name"]    = "required";
                    $rule["multi_abbrev"]  = "required";
                    $rule["unit_qty"]      = "required|numeric|min:1";

                    $validator3 = Validator::make($ins_related, $rule);
                    if($validator3->fails())
                    {
                        $data["status"] = "error";
                        foreach ($validator3->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                        {
                            $data["status_message"] .= $message;
                        }
                    }
                    else
                    {
                        $ins_related = [];
                        foreach ($related as $key => $value) 
                        {
                            if($value != "")
                            {
                                $ins_related[$key]["multi_name"] = $value;
                                $ins_related[$key]["multi_abbrev"] = $abbrev[$key];
                                $ins_related[$key]["unit_qty"] = str_replace(",", "", $qty[$key]);

                                $rule[$key]["multi_name"]    = "required";
                                $rule[$key]["multi_abbrev"]  = "required";
                                $rule[$key]["unit_qty"]      = "required|numeric|min:1";

                                $validator2[$key] = Validator::make($ins_related[$key], $rule[$key]);
                                if($validator2[$key]->fails())
                                {
                                    $data["status"] = "error";
                                    foreach ($validator2[$key]->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                                    {
                                        $data["status_message"] .= $message;
                                    }
                                }

                            }
                        }
                    }
                }
                $ins_related = [];
                if($data["status"] == "")
                {
                    $id = Tbl_unit_measurement::insertGetId($insert);
                    
                    $ins_related["multi_um_id"] = $id;
                    $ins_related["multi_name"] = $base_name;
                    $ins_related["multi_abbrev"] = $base_abbrev;
                    $ins_related["unit_qty"] = 1;
                    $ins_related["is_base"] = 1;

                    Tbl_unit_measurement_multi::insert($ins_related);
                    $ins_related = null;
                    if($related != null && $abbrev != null && $qty != null)
                    {
                        foreach ($related as $keys => $value) 
                        {
                            if($value != "")
                            {
                                $ins_related["multi_um_id"] = $id;
                                $ins_related["multi_name"] = $value;
                                $ins_related["multi_abbrev"] = $abbrev[$keys];
                                $ins_related["unit_qty"] = str_replace(",", "", $qty[$keys]); 

                                Tbl_unit_measurement_multi::insert($ins_related);                   
                            }
                        }
                    }
                    $um_data = Tbl_unit_measurement::where('um_shop', $this->user_info->shop_id)->where('um_id', $id)->first()->toArray();
                    $um_multi_data = Tbl_unit_measurement_multi::where('multi_um_id', $id)->get()->toArray();
                    
                    $data = null;
                    foreach ($um_multi_data as $key => $value)
                    {
                        $data['um'] = $um_data;
                        $data['um_multi'] = $um_multi_data;
                    }
    
                    AuditTrail::record_logs('Added', 'um', $id, "", serialize($data));
                    $data["status"] = "success";
                    $data["type"] = "unit-measurement";
                    $data["id"] = $id;
                }
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
        $access = Utilities::checkAccess('item-unit-measurement', 'access_page');
        if($access == 1)
        {
            $data["_um_type"] = Tbl_unit_measurement_type::where("um_type_parent_id",0)->where("archived",0)->get();

            $data["edit_um"] = Tbl_unit_measurement::where("um_id",$id)->first();
            $multi_base = Tbl_unit_measurement_multi::where("multi_um_id",$id)->where("is_base",1)->first();
            $data["edit_um"]->um_base_name = $multi_base->multi_name;
            $data["edit_um"]->um_base_abbrev = $multi_base->multi_abbrev;
            $data["_multi"] = Tbl_unit_measurement_multi::where("multi_um_id",$id)->where("is_base",0)->get();

            return view("member.unit_of_measurement.unit_measurement_edit",$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function edit_submit()
    {
        $access = Utilities::checkAccess('item-unit-measurement', 'access_page');
        if($access == 1)
        {
            $old_um_data = Tbl_unit_measurement::where('um_shop', $this->user_info->shop_id)->where('um_id', Request::input("um_id"))->first()->toArray();
            $old_um_multi_data = Tbl_unit_measurement_multi::where('multi_um_id', Request::input("um_id"))->get()->toArray();
            
            $old_data = null;
            foreach ($old_um_multi_data as $key => $value)
            {
                $old_data['um'] = $old_um_data;
                $old_data['um_multi'] = $old_um_multi_data;
            }
            $data["status_message"] = "";
            $data["status"] = "";

            $um_id = Request::input("um_id");
            $set_name = Request::input("um_set_name");
            $base_name = Request::input("base_name");
            $base_abbrev = Request::input("base_abbreviation");

            $related = Request::input("related_name");
            $abbrev = Request::input("related_abb");
            $qty = Request::input("related_qty");
            $multi_id = Request::input("multi_id");

            $insert["um_name"] = $set_name;
            // $insert["um_base_name"] = $base_name;
            // $insert["um_base_abbrev"] = $base_abbrev;
            $insert["um_date_created"] = Carbon::now();
            $insert["um_shop"] = $this->user_info->shop_id;

            $rule["um_name"]            = "required";
            // $rule["um_base_name"]       = "required|unique:tbl_unit_measurement,um_base_name,".$um_id.",um_id";
            // $rule["um_base_abbrev"]     = "required|unique:tbl_unit_measurement,um_base_abbrev,".$um_id.",um_id";
            $rule["um_type"]            = "";

            $validator = Validator::make($insert,$rule);

            $multi_id_first = null;

            $multi_id_first = Tbl_unit_measurement_multi::where("is_base",1)->where("multi_um_id",$um_id)->value("multi_id");
            $ctr = Tbl_unit_measurement::where("um_name",$set_name)->where("um_id","!=",$um_id)->where("um_shop",$this->user_info->shop_id)->count();
            
            if($ctr >= 1)
            {
                $data["status"] = "error";
                $data["status_message"] .= "The U/M Name is already been taken";
            } 
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
                $rule = [];
                if($related != null && $abbrev != null & $qty != null)
                {
                    $ins_related["multi_um_id"] = $um_id;
                    $ins_related["multi_name"] = $base_name;
                    $ins_related["multi_abbrev"] = $base_abbrev;
                    $ins_related["unit_qty"] = 1;

                    $rule["multi_name"]    = "required";
                    $rule["multi_abbrev"]  = "required";
                    $rule["unit_qty"]      = "required|numeric|min:1";

                    $validator3 = Validator::make($ins_related, $rule);
                    if($validator3->fails())
                    {
                        $data["status"] = "error";
                        foreach ($validator3->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                        {
                            $data["status_message"] .= $message;
                        }
                    }
                    else
                    {
                        foreach ($related as $key => $value) 
                        {
                            if($value != "")
                            {
                                $ins_related[$key]["multi_name"] = $value;
                                $ins_related[$key]["multi_abbrev"] = $abbrev[$key];
                                $ins_related[$key]["unit_qty"] =str_replace(",", "", $qty[$key]);;

                                if(isset($multi_id[$key]))
                                {
                                    $data = Tbl_unit_measurement_multi::where("multi_id",$multi_id[$key])->first();                            
                                    $rule[$key]["multi_name"]    = "required";
                                    $rule[$key]["multi_abbrev"]  = "required";
                                    $rule[$key]["unit_qty"]      = "required|numeric|min:1";
                                }
                                else
                                {                            
                                    $rule[$key]["multi_name"]    = "required";
                                    $rule[$key]["multi_abbrev"]  = "required";
                                    $rule[$key]["unit_qty"]      = "required|numeric";
                                }

                                $validator2[$key] = Validator::make($ins_related[$key], $rule[$key]);
                                if($validator2[$key]->fails())
                                {
                                    $data["status"] = "error";
                                    foreach ($validator2[$key]->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                                    {
                                        $data["status_message"] .= $message;
                                    }
                                }

                            }
                        }
                    }
                }
                
                $ins_related = [];
                if($data["status"] == "")
                {
                    Tbl_unit_measurement::where("um_id",$um_id)->update($insert);

                    $ins_related["multi_name"] = $base_name;
                    $ins_related["multi_abbrev"] = $base_abbrev;

                    Tbl_unit_measurement_multi::where("multi_id",$multi_id_first)->update($ins_related);
                    $ins_related = [];
                    if($related != null && $abbrev != null && $qty != null)
                    {
                        foreach ($related as $keys => $value) 
                        {
                            if($value != "")
                            {
                                if(isset($multi_id[$keys]))
                                {
                                    $data = Tbl_unit_measurement_multi::where("multi_id",$multi_id[$keys])->first();
                                    if($data != null)
                                    {
                                        $ins_related["multi_um_id"] = $um_id;
                                        $ins_related["multi_name"] = $value;
                                        $ins_related["multi_abbrev"] = $abbrev[$keys];
                                        $ins_related["unit_qty"] = str_replace(",", "", $qty[$keys]);; 

                                        Tbl_unit_measurement_multi::where("multi_id",$multi_id[$keys])->update($ins_related);                                
                                    }
                                }
                                else
                                {
                                    $ins_related["multi_um_id"] = $um_id;
                                    $ins_related["multi_name"] = $value;
                                    $ins_related["multi_abbrev"] = $abbrev[$keys];
                                    $ins_related["unit_qty"] = str_replace(",", "", $qty[$keys]);; 

                                    Tbl_unit_measurement_multi::insert($ins_related);                                
                                }
                            }
                        }
                    }
                    $update_um_data = Tbl_unit_measurement::where('um_shop', $this->user_info->shop_id)->where('um_id', $um_id)->first()->toArray();
                    $update_um_multi_data = Tbl_unit_measurement_multi::where('multi_um_id', $um_id)->get()->toArray();
                    
                    $updated_data = null;
                    foreach ($update_um_multi_data as $key => $value)
                    {
                        $updated_data['um'] = $update_um_data;
                        $updated_data['um_multi'] = $update_um_multi_data;
                    }    
                    AuditTrail::record_logs('Edit', 'um', $um_id, serialize($old_data), serialize($updated_data));

                    $data["status"] = "success";
                }
            }
            
            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }

    }
    public function select_type()
    {
        $access = Utilities::checkAccess('item-unit-measurement', 'access_page');
        if($access == 1)
        {
            $type_id = Request::input("type_id");

            $data = Tbl_unit_measurement_type::where("um_type_parent_id",$type_id)->where("shop_id",$this->user_info->shop_id)->get();

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
