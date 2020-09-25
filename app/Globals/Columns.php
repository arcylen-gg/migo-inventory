<?php
namespace App\Globals;

use App\Models\Tbl_variant;
use App\Models\Tbl_user;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_category;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_audit_trail;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Tablet_global;
use App\Globals\Currency;
use App\Models\Tbl_price_level;
use App\Models\Tbl_price_level_item;
use App\Models\Tbl_item_type;
use App\Models\Tbl_columns;
use Session;
use DB;
use Carbon\carbon;
use App\Globals\Merchant;
use App\Globals\Columns;
use Validator;
use function GuzzleHttp\json_decode;

class Columns
{
    public static function setDefault($default)
    {
       foreach ($default as $key => $value) 
       {
          $temp_value               = $value[0];
          $temp_array               = $value[1];
          $temp_checked             = $value[2];
          $default[$key]            = [];
          $default[$key]["value"]   = $temp_value;
          $default[$key]["array"]   = $temp_array;
          $default[$key]["checked"] = $temp_checked;
       }

       return $default;
    }
    public static function getColumns($shop_id, $user_id, $from, $default = null)
    {
       $columns = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();

       if (!isset($columns)) 
       {
           $default = Columns::setDefault($default);

           $columns = $default;
       }
       else
       {
            if (isset($columns->columns_data) && is_serialized($columns->columns_data)) 
            {
                if ($default) 
                {
                    $temp_columns = unserialize($columns->columns_data);

                    if (count($temp_columns) != count($default)) 
                    {
                        $default = Columns::setDefault($default);
                       
                        $temp_columns = $default;
                    }
                }
                else
                {
                    $temp_columns = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();

                    if ($temp_columns && isset($temp_columns->columns_data) && is_serialized($temp_columns->columns_data)) 
                    {
                        $temp_columns = unserialize($temp_columns->columns_data);
                    }
                    else
                    {
                        $temp_columns = [];
                    }
                }
            }
            else
            {
                $temp_columns = [];
            }
            
            $columns = $temp_columns;
       }
       return $columns;
    }
    public static function submitColumns($shop_id, $user_id, $from, $column)
    {   
        foreach ($column as $key => $value) 
        {
            $submit[$key]["value"]   = $value["value"];
            $submit[$key]["array"]   = $value["array"];
            $submit[$key]["checked"] = $value["checked"] == "yes" ? true : false;
        }


        $insert["columns_data"] = serialize($submit);
        $columns                = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();
        // dd($columns);
        if (isset($columns)) 
        {
            Tbl_columns::user($user_id)->shop($shop_id)->from($from)->update($insert);
        }
        else
        {
            $insert["shop_id"]      = $shop_id;
            $insert["user_id"]      = $user_id;
            $insert["columns_from"] = $from;
            Tbl_columns::insert($insert);
        }

        return true;
    }
    public static function filterColumns($shop_id, $user_id, $from, $data, $default)
    {                               
        $columns = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();
        //dd(unserialize($columns->columns_data));
        if (isset($columns->columns_data) && count(unserialize($columns->columns_data)) != count($default)) 
        {
            Tbl_columns::user($user_id)->shop($shop_id)->from($from)->delete();
            $columns = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();
        }

        if (!isset($columns->columns_data)) 
        {
            $get_columns = Columns::getColumns($shop_id, $user_id, $from, $default);
            Columns::submitColumns($shop_id, $user_id, $from, $get_columns);
            $columns = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();
        }
        elseif(!is_serialized($columns->columns_data))
        {
            Tbl_columns::user($user_id)->shop($shop_id)->from($from)->delete();
            $get_columns = Columns::getColumns($shop_id, $user_id, $from, $default);
            Columns::submitColumns($shop_id, $user_id, $from, $get_columns);
            $columns = Tbl_columns::user($user_id)->shop($shop_id)->from($from)->first();
        }

        $temp_columns = unserialize($columns->columns_data);

        foreach ($temp_columns as $key => $value) 
        {
            if ($value["checked"] == false) 
            {
                unset($temp_columns[$key]);
            }
        }

        $new_data = [];

        if ($data) 
        {
            foreach ($data as $key => $value) 
            {
                foreach ($temp_columns as $key1 => $value1) 
                {
                    if ($value1["checked"] == true) 
                    {
                        $array                            = $value1["array"];
                        $new_data[$key][$key1]["label"]   = $value1["value"];
                        
                        $new_data[$key][$key1]["column_name"]   = $value1["array"];
                        $new_data[$key][$key1]["data"]    = $value->$array;
                        $new_data[$key][$key1]["default"] = $value;
                    }
                }
            }
        }
        
        return $new_data;
    }
    public static function deleteColumns($shop_id, $user_id, $from)
    {
        Tbl_columns::user($user_id)->shop($shop_id)->from($from)->delete();

        return true;
    }
}