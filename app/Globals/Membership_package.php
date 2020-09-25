<?php
namespace App\Globals;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use Session;
class Membership_package
{
	public static function view_membership_dropdown($sel = null, $shop_id, $multiple = false)
	{
	    if($sel == null)
	    {
	        $sel = 0;
	    }
	    $data["multiple"] = $multiple;
	    $data['selected'] = $sel;
	    $data['membership_packge'] = Tbl_membership_package::membership()->where('tbl_membership.membership_archive', '0')
	    ->where('membership_package_archive', 0)
	    ->where('tbl_membership.shop_id', $shop_id)
	    ->get();
	   // dd($data);
	    return view('member.mlm_membership.mlm_dropdown_membership', $data);
	}
	public static function view_ps_cd_fs_dropdown($sel = null, $multiple = false)
	{
	    if($sel == null)
	    {
	        $sel = 0;
	    }
	    
	    $data["multiple"] = $multiple;
	    $data['selected'] = $sel;
	   // dd($data);
        return view('member.mlm_membership.mlm_dropdown_membership_type', $data);
	}
	public static function sell_membership_add_to_session($array)
	{
	   // Session::forget('sell_codes_session');
	    $get_session = Session::get("sell_codes_session"); 
	    
	    if(!empty($get_session))
	    {
	        $condition = "false";
	        foreach($get_session as $key => $value)
	        {
	            if($array['membership_package_id'] == $key)
	            {
	               $get_session[$key]['quantity']		 = $get_session[$key]['quantity'] + $array['quantity'];
	               $get_session[$key]['total']		     = $get_session[$key]['total'] + $array['total'];
	               $get_session[$key]['membership_type'] = $array['membership_type'];
	               $condition = "true";
	            }
	        }
	        if($condition == "false")
	        {
	            $get_session[$array['membership_package_id']] = $array;
	            Session::put('sell_codes_session', $get_session);  
	        }
	        else
	        {
	            Session::put('sell_codes_session', $get_session); 
	        }
	    }
	    else
	    {
	        $array2[$array['membership_package_id']] = $array;
	        Session::put('sell_codes_session', $array2);
	    }
	    $get_session = Session::get("sell_codes_session"); 
        return $get_session;
	}
	
	public static function sell_membership_edit_to_session($array,$removed = null)
	{
	   // Session::forget('sell_codes_session');
	    $get_session = Session::get("sell_codes_session"); 
	    $total       = 0;
	    if(!empty($get_session))
	    {
	        $condition = "false";
	    	if($removed)
	    	{
		        $get_session[$array['membership_package_id']] = $array;	
		        Session::put('sell_codes_session', $get_session);
		        
   				$remove_session = Session::get("sell_codes_session");
   				$remove_session = Membership_package::replace_key_function($remove_session,$removed,$array["membership_package_id"]);
				
				unset($remove_session[$removed]);
	    		Session::put('sell_codes_session', $remove_session);
	    		$get_session = Session::get("sell_codes_session");
	    	}
	    	else
	    	{
		        foreach($get_session as $key => $value)
		        {
		            if($array['membership_package_id'] == $key)
		            {
		               $get_session[$key]['quantity'] = $array['quantity'];
		               $get_session[$key]['total'] = $array['total'];
		               $condition = "true";
		            }
		        }
	    		
	    	}
	    	
	        if($condition == "false")
	        {
	            $get_session[$array['membership_package_id']] = $array;
	            Session::put('sell_codes_session', $get_session);  
	        }
	        else
	        {
        		Session::put('sell_codes_session', $get_session); 
	        }
	        
	        
	        $get_session = Session::get("sell_codes_session"); 
	        foreach($get_session as $key => $value)
	        {
	        	$total = $total + $get_session[$key]['total'];
	        	$data["total"] = $total;
	        }
	        
        	return $data;
	    }
	    else
	    {
	    	return json_encode("Fail");	
	    }
	}
	
	public static function replace_key_function($array, $key1, $key2)
	{
	    $keys = array_keys($array);
	    $index = array_search($key1, $keys);
	
	    if ($index !== false) {
	        $keys[$index] = $key2;
	        $array = array_combine($keys, $array);
	    }
	
	    return $array;
	}
}