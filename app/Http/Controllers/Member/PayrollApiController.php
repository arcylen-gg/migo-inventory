<?php

namespace App\Http\Controllers\Member;
use Crypt;
use App\Models\Tbl_shop;

class PayrollAPIController extends Member
{
    public function index()
    {
        $shop_id = $this->user_info->shop_id;
        
        if($this->user_info->shop_api_key == "unset")
        {
            $shop_key = $this->generaterandomkey(10);
            $update["shop_api_key"] = $shop_key;
            Tbl_shop::where("shop_id", $shop_id)->update($update);
        }
        else
        {
            $shop_key = $this->user_info->shop_api_key;
        }
        
        $data["app_key"] = $shop_key;
        $data["app_secret"] = md5($shop_id . "-" . $data["app_key"]);
        
        return view("member.payroll2.api", $data);
    }
    public function generaterandomkey($len)
    {
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $base = strlen($charset);
        $result = '';
        
        $now = explode(' ', microtime())[1];
        while ($now >= $base){
        $i = $now % $base;
        $result = $charset[$i] . $result;
        $now /= $base;
        }
        return substr($result, -5);
    }
}