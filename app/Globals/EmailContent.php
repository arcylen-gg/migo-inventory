<?php
namespace App\Globals;

use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_email_content;
use App\Models\Tbl_shop;
use DB;
use App\Globals\EmailContent;
use App\Models\Tbl_user;
class EmailContent
{    
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    public static function getShopkey()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('shop_key');
    }
    public static function getShopkey_front($shop_id)
    {
        return Tbl_user::shop()->where("tbl_shop.shop_id", $shop_id)->value('shop_key');
    }
    public static function getAllEmailContent()
    {
    	return Tbl_email_content::where("archived",0)->where("shop_id",EmailContent::getShopId())->get();
    }
    public static function getSubject($content_key, $shop_id = 1)
    {
        return Tbl_email_content::where("email_content_key",$content_key)->where('shop_id',$shop_id)->value("email_content_subject");
    }
    public static function checkIfexisting($content_key, $shop_id = 1)
    {
        return Tbl_email_content::where("email_content_key",$content_key)->where('shop_id',$shop_id)->count();
    }
    public static function checkIfexisting_shop_id($content_key, $shop_id = 1)
    {
        return Tbl_email_content::where("email_content_key",$content_key)->where("shop_id",$shop_id)->count();
    }
    public static function email_txt_replace($content_key, $change_content = array(), $shop_id = 1)
    {    	
        $content = Tbl_email_content::where("email_content_key",$content_key)
        ->where('shop_id', $shop_id)
        ->orderBy('email_content_id', 'DESC')
        ->value("email_content");
        
        foreach ($change_content as $key => $value)
        {        	
        	$content = str_replace($value["txt_to_be_replace"],$value["txt_to_replace"],$content);	
        }

        return $content;
    }
}