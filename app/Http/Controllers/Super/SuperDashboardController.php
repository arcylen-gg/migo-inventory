<?php
namespace App\Http\Controllers\Super;
use App\Models\Tbl_admin;
use Crypt;

class SuperDashboardController extends Super
{
    public $admin_info;

    public function getIndex()
    {   
        if(session("admin_username"))
        {
            return view("super.super");
        }
        else
        {
            Self::seed_admin_if_there_isnt_any();
            return view("super.login");
        }
    }
    public static function seed_admin_if_there_isnt_any()
    {
        $admin = Tbl_admin::where("admin_id", 1)->first();

        if(!$admin)
        {
            $insert_admin["admin_id"]       = 1;
            $insert_admin["first_name"]     = "Guillermo";
            $insert_admin["last_name"]      = "Tabligan";
            $insert_admin["username"]       = "developer";
            $insert_admin["mobile_number"]  = "09778049113";
            $insert_admin["password"]       = Crypt::encrypt("water123");

            Tbl_admin::insert($insert_admin);
        }
    }
    public function getLogout()
    {
        session()->forget("admin_username");
        session()->forget("admin_password");
        return redirect()->back();
    }
    public function postLogin()
    {
        $error = "";

        $username = request("username");
        $password = request("password");

        $check_admin = Tbl_admin::where("username", $username)->first();

        if(!$check_admin)
        {
            $error = "The account you are trying to login doesn't exist.";
        }
        else
        {
            $real_password = Crypt::decrypt($check_admin->password);

            if($real_password != $password)
            {
                $error = "Password is incorrect.";
            }
        }


        if($error == "")
        {
            $store["admin_username"] = request("username");
            $store["admin_password"] = request("password");
            session($store);
            $return["status"] = "success";
        }
        else
        {
            $return["status"] = "error";
            $return["title"] = "Login Error";
            $return["message"] = $error;
        }

        
        echo json_encode($return);
    }
}