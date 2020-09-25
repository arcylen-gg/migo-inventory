<?php
namespace App\Http\Controllers\Super;
use App\Models\Tbl_admin;
use Crypt;
use Illuminate\Validation\Rule;
use Validator;


class SuperAdminController extends Super
{
    public $admin_info;

    public function getIndex()
    {
    	$data["page"] = "Admin List";
    	$data["_admin"] = Tbl_admin::where("admin_id", "<>", 1)->get();
    	return view("super.admin", $data);
    }
    public function getAdd()
    {
    	$data["page"] 			= "Admin Add";
    	return view("super.admin_add", $data);
    }
    public function postAdd()
    {
        $condition["first_name"]        = array("required");
        $condition["last_name"]         = array("required");
        $condition["username"]        	= array("required", Rule::unique('tbl_admin')->ignore('', 'username'));
        $condition["password"]        	= array("min:5");
        $validator                      = Validator::make(request()->all(), $condition);

        if ($validator->fails())
        {
            $errors                     = $validator->errors();
            $return["status"]           = "error";
            $return["title"]            = "Validation Error";
            $return["message"]          = $errors->first();
        }
        else
        {
        	$insert["first_name"] 		= request("first_name");
        	$insert["last_name"] 		= request("last_name");
        	$insert["username"]			= request("username");
        	$insert["password"]			= Crypt::encrypt(request("password"));

        	if(request("full_access"))
        	{
        		$insert["admin_type"]	= "full";
        	}
        	else
        	{
        		$insert["admin_type"]	= "limited";
        	}

        	Tbl_admin::insert($insert);


            $return["title"]        = "Successfully Created";
            $return["message"]      = "New Super Admin has been created and added to list.";  
            $return["back"]         = true;
        }

        echo json_encode($return);
    }
    public function getEdit()
    {
    	$data["admin_id"]		= $admin_id = request("admin_id");
    	$data["page"] 			= "Admin Edit (" . $admin_id . ")";
    	$data["admin"] 			= $admin = Tbl_admin::where("admin_id", $admin_id)->first();
    	$data["admin_password"] = Crypt::decrypt($admin->password);
    	return view("super.admin_edit", $data);
    }
    public function postEdit()
    {
    	$admin = Tbl_admin::where("admin_id", request("admin_id"))->first();

        $condition["first_name"]        = array("required");
        $condition["last_name"]         = array("required");
        $condition["username"]        	= array("required", Rule::unique('tbl_admin')->ignore($admin->username, 'username'));
        $condition["password"]        	= array("min:5");
        $validator                      = Validator::make(request()->all(), $condition);

        if ($validator->fails())
        {
            $errors                     = $validator->errors();
            $return["status"]           = "error";
            $return["title"]            = "Validation Error";
            $return["message"]          = $errors->first();
        }
        else
        {
        	$update["first_name"] 		= request("first_name");
        	$update["last_name"] 		= request("last_name");
        	$update["username"]			= request("username");
        	$update["password"]			= Crypt::encrypt(request("password"));

        	if(request("full_access"))
        	{
        		$update["admin_type"]	= "full";
        	}
        	else
        	{
        		$update["admin_type"]	= "limited";
        	}

        	Tbl_admin::where("admin_id", request("admin_id"))->update($update);

        	$return["title"] 	= "Successfully Updated";
            $return["message"] 	= "Information of Super Admin No. " . request("admin_id") . " (" . $admin->first_name . " " . $admin->last_name  . ") has been successfully updated."; 
        	$return["back"]		= true;
        }

        echo json_encode($return);
    }
}