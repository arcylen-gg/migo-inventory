<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_item;
use App\Models\Tbl_user;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_settings;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_um;
use App\Globals\Tablet_global;
use DB;
use Carbon\Carbon;
use Session;
class UnitMeasurement
{

    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public static function getQty($um_id)
    {
        $qty = 1;
        $um_data = Tbl_unit_measurement_multi::where("multi_um_id",$um_id)->orderBy("multi_id","DESC")->first();
        if($um_data)
        {
            $qty = $um_data->unit_qty;
        }

        return $qty;
    }
    public static function get_umqty($um_id)
    {
        $qty = 1;
        $um_data = Tbl_unit_measurement_multi::where("multi_id",$um_id)->orderBy("multi_id","DESC")->first();
        if($um_data)
        {
            $qty = $um_data->unit_qty;
        }

        return $qty;
    }
    public static function um_convertion($item_id = 0)
    {
        $return = '';
        if($item_id != 0)
        {
            $item_data = Item::get_item_details($item_id);
            if($item_data)
            {
                $um = Tbl_unit_measurement_multi::where("multi_um_id",$item_data->item_measurement_id)->where("is_base",0)->first();
                if($um)
                {
                    $return = "1 ".$um->multi_abbrev." = ".$um->unit_qty." ".$item_data->multi_abbrev;
                }
                else
                {
                    $return = "1 ".$item_data->multi_abbrev;
                }
                
            }
        }
        return $return; 
    }
    public static function load_one_um($um_id)
    {
        return Tbl_unit_measurement::multi()->where("um_shop", UnitMeasurement::getShopId())
                                    ->where("um_id", $um_id)
                                    ->where("um_archived",0)
                                    ->orderBy('multi_id','ASC')
                                    ->get();
    }
    public static function create_um($um_n_based_id, $um_base_id, $qty)
    {
        $um_id = 0;
        if($um_n_based_id != 0 && $um_base_id != 0)
        {
            $related_unit_data = Tbl_um::where("id",$um_n_based_id)->first();
            $based_unit_data = Tbl_um::where("id",$um_base_id)->first();

            $ins_um['um_shop'] = UnitMeasurement::getShopId();
            $ins_um['um_name'] = $related_unit_data->um_name;
            $ins_um['um_date_created'] = Carbon::now();
            $ins_um['um_n_base'] = $um_n_based_id;
            $ins_um['um_base'] = $um_base_id;

            $um_id = Tbl_unit_measurement::insertGetId($ins_um);

            $ins_base['multi_um_id'] = $um_id;
            $ins_base['multi_name'] = $based_unit_data->um_name;
            $ins_base['unit_qty'] = 1;
            $ins_base['multi_abbrev'] = $based_unit_data->um_abbrev;
            $ins_base['is_base'] = 1;

            Tbl_unit_measurement_multi::insert($ins_base);

            $ins_base = [];

            $ins_base['multi_um_id'] = $um_id;
            $ins_base['multi_name'] = $related_unit_data->um_name;
            $ins_base['unit_qty'] = $qty;
            $ins_base['multi_abbrev'] = $related_unit_data->um_abbrev;

            Tbl_unit_measurement_multi::insert($ins_base);
        }
        elseif($um_base_id == 0 && $um_n_based_id != 0)
        {
            $related_unit_data = Tbl_um::where("id",$um_n_based_id)->first();

            $ins_um['um_shop'] = UnitMeasurement::getShopId();
            $ins_um['um_name'] = $related_unit_data->um_name;
            $ins_um['um_date_created'] = Carbon::now();
            $ins_um['um_n_base'] = $um_n_based_id;

            $um_id = Tbl_unit_measurement::insertGetId($ins_um);

            $ins_base['multi_um_id'] = $um_id;
            $ins_base['multi_name'] = $related_unit_data->um_name;
            $ins_base['unit_qty'] = 1;
            $ins_base['multi_abbrev'] = $related_unit_data->um_abbrev;
            $ins_base['is_base'] = 1;

            Tbl_unit_measurement_multi::insert($ins_base);
        }
        return $um_id;
    }
    public static function update_um_v2($um_n_based_id, $um_base_id, $qty, $um_id)
    {
        if($um_n_based_id != 0 && $um_base_id != 0)
        {
            $related_unit_data = Tbl_um::where("id",$um_n_based_id)->first();
            $based_unit_data = Tbl_um::where("id",$um_base_id)->first();

            $ins_um['um_shop'] = UnitMeasurement::getShopId();
            $ins_um['um_name'] = $related_unit_data->um_name;
            $ins_um['um_date_created'] = Carbon::now();
            $ins_um['um_n_base'] = $um_n_based_id;
            $ins_um['um_base'] = $um_base_id;

            if (Tbl_unit_measurement::where("um_id", $um_id)->first()) 
            {
                Tbl_unit_measurement::where("um_id", $um_id)->update($ins_um);
            }
            else
            {
                $um_id = Tbl_unit_measurement::insertGetId($ins_um);
            }

            $ins_base['multi_name'] = $based_unit_data->um_name;
            $ins_base['unit_qty'] = 1;
            $ins_base['multi_abbrev'] = $based_unit_data->um_abbrev;
            
            if (Tbl_unit_measurement_multi::where("multi_um_id", $um_id)->where("is_base", 1)->first()) 
            {
                Tbl_unit_measurement_multi::where("multi_um_id", $um_id)->where("is_base", 1)->update($ins_base);
            }
            else
            {
                $ins_base['is_base'] = 1;
                $ins_base['multi_um_id'] = $um_id;

                Tbl_unit_measurement_multi::insert($ins_base);
            }
            

            $ins_base = [];

            $ins_base['multi_name'] = $related_unit_data->um_name;
            $ins_base['unit_qty'] = $qty;
            $ins_base['multi_abbrev'] = $related_unit_data->um_abbrev;

            if (Tbl_unit_measurement_multi::where("multi_um_id", $um_id)->where("is_base", 0)->first()) 
            {
                Tbl_unit_measurement_multi::where("multi_um_id", $um_id)->where("is_base", 0)->update($ins_base);
            }
            else
            {
                $ins_base['is_base'] = 0;
                $ins_base['multi_um_id'] = $um_id;

                Tbl_unit_measurement_multi::insert($ins_base);
            }
            
        }
        elseif($um_base_id == 0 && $um_n_based_id != 0)
        {
            $related_unit_data = Tbl_um::where("id",$um_n_based_id)->first();

            $ins_um['um_shop'] = UnitMeasurement::getShopId();
            $ins_um['um_name'] = $related_unit_data->um_name;
            $ins_um['um_date_created'] = Carbon::now();
            $ins_um['um_n_base'] = $um_n_based_id;

            if (Tbl_unit_measurement::where("um_id", $um_id)->first()) 
            {
                Tbl_unit_measurement::where("um_id", $um_id)->update($ins_um);
            }
            else
            {
                $um_id = Tbl_unit_measurement::insertGetId($ins_um);
            }

            $ins_base['multi_name'] = $related_unit_data->um_name;
            $ins_base['unit_qty'] = 1;
            $ins_base['multi_abbrev'] = $related_unit_data->um_abbrev;

            if (Tbl_unit_measurement_multi::where("multi_um_id", $um_id)->where("is_base", 1)->first()) 
            {
                Tbl_unit_measurement_multi::where("multi_um_id", $um_id)->where("is_base", 1)->update($ins_base);
            }
            else
            {
                $ins_base["multi_um_id"] = $um_id;
                $ins_base["is_base"] = 1;
                
                Tbl_unit_measurement_multi::insert($ins_base);
            }
        }

        return $um_id;
    }
    public static function load_um_multi($for_tablet = false)
    {
        $shop_id = UnitMeasurement::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        return Tbl_unit_measurement::multi()->where("um_shop", $shop_id)
                                    ->where("um_archived",0)
                                    ->orderBy("multi_id","ASC")
                                    ->get();
    }
    public static function archived_um()
    {        
        $del = Tbl_unit_measurement::where("parent_basis_um","!=",0)->where("um_item_id",0)->where("um_shop",UnitMeasurement::getShopId())->get();

        foreach ($del as $key => $value) 
        {
           $up["is_multi"] = 1;
           $up["um_archived"] = 1;

           Tbl_unit_measurement::where("um_id",$value->um_id)->update($up);
        }
    } 
    public static function check()
    {
        $check = Tbl_settings::where("settings_key","pis-jamestiong")->where("settings_value","enable")->where("shop_id",UnitMeasurement::getShopId())->value("settings_setup_done");
        return $check;
    }
    public static function um_qty($um_id, $fix = 0)
    {
        $um_info = UnitMeasurement::um_info($um_id, $fix);
        $return_qty = 1;
        if($um_info != null)
        {
            $return_qty = $um_info->unit_qty;
        }
        return $return_qty;
    }
    public static function update_um($um_id,$item_name,$item_id)
    {
        if($um_id != null)
        {
            $chk = Tbl_unit_measurement::where("um_id",$um_id)->first();

            $old_um = Tbl_unit_measurement::where("um_id",$chk->parent_basis_um)->first();
            if($old_um)
            {
                $up["um_name"] = $old_um->um_name."(".$item_name.")";
                $up["um_item_id"] = $item_id;

                Tbl_unit_measurement::where("um_id",$um_id)->update($up);                
            }
        }
        Session::forget("um_id");   
    }
    public static function um_convert($qty, $um_base_id = "")
    {
        $um_base = Tbl_unit_measurement_multi::where("multi_um_id",$um_base_id)->where("is_base",1)->first();
        // dd($um_base);
        $return = $qty." PC";
        if($um_base)
        {
            $return = $qty." ".$um_base->multi_abbrev;
        }

        return $return;
    }
    public static function um_view($qty, $um_base_id = "", $um_issued_id = "")
    {
        // if($um_issued_id == "")
        // {
        //     $um_issued_id = $um_base_id;
        // }
        
        $um_issued = Tbl_unit_measurement_multi::where("multi_id",$um_issued_id)->first();
        $um_base = Tbl_unit_measurement_multi::where("multi_um_id",$um_base_id)->where("is_base",1)->first();
        if($um_base != null && $um_issued == null)
        {
             $return = number_format($qty)." ".$um_base->multi_abbrev;
        }
        else if($um_base_id == $um_issued_id)
        {
            if($um_base != null || $um_issued != null)
            {
                $return = number_format($qty)." ".$um_base->multi_abbrev;
            }
            else
            {
                $return = number_format($qty)." pcs";
            }
        }
        else if($um_issued != null && $um_base != null )
        {
            $issued_um_qty = 1;
            $base_um_qty = 1;
            if($um_issued != null)
            {
                $issued_um_qty = $um_issued->unit_qty;
            }
            if($um_base != null)
            {
                $base_um_qty = $um_base->unit_qty;
            }

            $issued_um = floor($qty / $issued_um_qty);
            // dd(($qty/$issued_um_qty - ($issued_um)) * $issued_um_qty);
            $each = round((($qty / $issued_um_qty) - floor($qty / $issued_um_qty)) * $issued_um_qty);
            $return = number_format($issued_um)." ".$um_issued->multi_abbrev." & ".number_format($each)." ".$um_base->multi_abbrev;
        }
        else
        {
            $return = number_format($qty)." pcs";
        }

        if($um_issued != null)
        {     
            if($um_issued->is_base == 1)
            {
                $return = number_format($qty)." ".$um_issued->multi_abbrev;
            }
        }

        return $return;
    }
    public static function load_um()
    {
        return Tbl_unit_measurement::where("um_shop", UnitMeasurement::getShopId())
                                    ->multi()
                                    ->where("tbl_unit_measurement_multi.is_base",1)
                                    ->groupBy("um_id")
                                    ->where("um_archived",0)
                                    ->where("parent_basis_um",0)
                                    ->get();
    }

    public static function select_um($um_id = 0, $return = 'array')
    {
    	$data = Tbl_unit_measurement_multi::where("multi_um_id",$um_id)->get();

    	if($return == "json")
    	{
    		$data = json_encode($data);
    	}
    	return $data;
    }

    public static function um_info($multi_id, $fix = 0)
    {
        if ($fix == 1) 
        {
            $unit_m = Tbl_unit_measurement_multi::where("multi_um_id",$multi_id)->where("is_base", 0)->first();
        }
        else
        {
            $unit_m = Tbl_unit_measurement_multi::where("multi_id",$multi_id)->first();
        }
        // dd($unit_m);
        return $unit_m;
    }
    public static function um_other($multi_id)
    {
        $unit_m = Tbl_unit_measurement_multi::where("multi_um_id",$multi_id)->where("is_base",0)->first();
        
        return $unit_m;
    }
    public static function select_um_array($multi_id = 0, $return = 'array')
    {
    	if(is_numeric($multi_id))
        {
            //unit_measurement_multi
            //multi_id
            $um_id = Tbl_unit_measurement_multi::where("multi_id",$multi_id)->value("multi_um_id");
            $data = Tbl_unit_measurement::multi()->where("um_id",$um_id)->where("um_archived",0)->get();
        }
        else
        {
            //unit_measurement 
            //um_id
            $data = Tbl_unit_measurement::multi()->where("um_id",str_replace("_um", "", $multi_id))->where("um_archived",0)->get();
        }           

    	if($return == "json")
    	{
    		$data = json_encode($data);
    	}
    	return $data;
    }
}