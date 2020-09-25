<?php

namespace App\Http\Controllers\Member;

use App\Globals\Item;
use App\Globals\Category;
use App\Globals\Manufacturer;
use App\Globals\Accounting;
use App\Globals\Warehouse2;
use App\Globals\Columns;
use Request;
use Session;
use Redirect;

class ColumnsController extends Member
{
    public function anyIndex($from)
    {
        if (Request::isMethod('post'))
        {
            $shop_id = $this->user_info->shop_id;
            $user_id = $this->user_info->user_id;
            $column  = Request::input("column");

            $result = Columns::submitColumns($shop_id, $user_id, $from, $column);

            if($result)
            {
                $response["response_status"] = "success";
                $response["message"] = "Column has been saved.";
                $response["call_function"] = "columns_submit_done";
            }
            else
            {
                $response["response_status"] = "error";
                $response["message"] = "Some error occurred.";
            }

            return json_encode($response);
        }
        else
        {
            $data["page"]    = "Columns";
            $shop_id         = $this->user_info->shop_id;
            $user_id         = $this->user_info->user_id;
            $data["_column"] = Columns::getColumns($shop_id, $user_id, $from);
            
            return view("member.columns", $data);
        }
    }
    public function anyReset($from)
    {
        $shop_id         = $this->user_info->shop_id;
        $user_id         = $this->user_info->user_id;

        $result = Columns::deleteColumns($shop_id, $user_id, $from);

        if($result)
        {
            $response["response_status"] = "success";
            $response["message"] = "Column has been reset.";
            $response["call_function"] = "columns_submit_done";
        }
        else
        {
            $response["response_status"] = "error";
            $response["message"] = "Some error occurred.";
        }

        return Redirect::back();
    }
}
