<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_user;
use App\Models\Tbl_user_access;
use App\Globals\Purchasing_inventory_system;
use App\Models\Tbl_mlm_plan;
use Log;
use Request;
use Session;
use Validator;
use Redirect;

/**
 * Utilities Module - all utilities related module
 *
 * @author Bryan Kier Aradanas
 */

class Utilities
{

	/**
	 * Check if the particular user has access
	 *
	 * @param string  	$page_code 		Name of the page accessing
	 * @param string  	$accesss_name  	Name or Type of access
     * @return string   1 or 0          1 => if has an access | 0 => if doesn't have an access
	 */
	public static function checkAccess($page_code, $access_name)
	{

		$user_info = Tbl_user::where("user_email", session('user_email'))->shop()->position()->access()->first();

		if($user_info)
		{
            $rank      = $user_info->position_rank;
            
		     if($rank === 0){
                return "1";
		     }
		     else{
		     $checkifaccess = DB::table('tbl_user_access')
		                    ->where('access_position_id', $user_info->access_position_id)
		                    ->where('access_page_code', $page_code)
		                    ->where('access_name', $access_name)
		                    ->where('access_permission', 1)
		                    ->first();

			     if($checkifaccess){
			           return "1";

			     }
			     else{
			           return "0";

			     } 
		 	}
		}
		else
		{
		    return Redirect::to('/admin/login');
		}   
    }

    public static function filterPageList($position_id = null)
    {
        $pis = Purchasing_inventory_system::check();
        return Utilities::filterPageListSub(page_list($pis), $position_id);
    }
    public static function get_all_users($shop_id, $user_id = 0)
    {
        $users = Tbl_user::where("user_shop", $shop_id)->get(); 
        if($user_id != 0)
        {
            $users = Tbl_user::where("user_shop", $shop_id)->where('user_id',$user_id)->get(); 
        }
        return $users;
    }
    public static function filterPageListSub($page_list, $position_id)
    {
        $_page_list         = $page_list;
        //dd($_page_list);
        foreach($_page_list as $key=>$page)
        {
            if(array_has($page, "submenu"))
            { 
                $_page_list[$key]["submenu"] = Utilities::filterPageListSub($page["submenu"], $position_id);
                if(!$_page_list[$key]["submenu"])
                {
                    unset($_page_list[$key]);
                }
            }
            else
            {
                $setting_count   = count($page['user_settings']);

                if($setting_count > 0)
                {
                    $page_code         = $page['code'];
                    $setting_counter   = $setting_count;
                    
                    foreach($page['user_settings'] as $key1=>$access_name)
                    {
                        if(Utilities::checkAccess($page_code, $access_name) == 0)
                        {
                            unset($_page_list[$key]['user_settings'][$key1]);
                            $setting_counter--;
                        }
                        if($position_id <> null)
                        {
                            $if_has_access = Tbl_user_access::where("access_position_id", $position_id)
                                            ->where("access_page_code", $page_code)
                                            ->where("access_name", $access_name)->first();
                            $_page_list[$key]['setting_is_checked'][$key1] = $if_has_access ? 1 : 0;
                        }
                    }

                    if($setting_counter < 1)
                    {
                        unset($_page_list[$key]);
                    }

                    if($page_code == "mlm-stairstep-compute")
                    {
                        $check_shop         = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
                        if($check_shop)
                        {
                            $check_stairstep = Tbl_mlm_plan::where("marketing_plan_code","STAIRSTEP")->where("shop_id",$check_shop)->where("marketing_plan_enable","1")->first();
                            if(!$check_stairstep)
                            {
                                unset($_page_list[$key]);
                            }
                        }
                    }
                }
            }
        }
        
        return $_page_list;
    }
}