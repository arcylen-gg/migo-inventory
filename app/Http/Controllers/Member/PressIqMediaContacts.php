<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PressIqMediaContacts extends Controller
{
    public function adminAddContact()
    {
    	if(request()->isMethod("post"))
    	{  		
	    		$insert_user["user_name"]=request('user_name');
	    		$insert_user["user_email"]=request('user_email');
	    		$insert_user["user_password"]=Crypt::encrypt(request('user_password'));
	    		$user_id = tbl_user::insertGetId($insert_user);  
	    		return Redirect::to("/login");  		
    	}	
    	else
    	{
    		return view ('sample_signup');
    	}
    }
}
