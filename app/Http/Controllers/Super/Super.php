<?php
namespace App\Http\Controllers\Super;
use App\Http\Controllers\Controller;
use App\Models\Tbl_admin;
use Crypt;

class Super extends Controller
{
    public $admin_info;

    public function __construct()
    {
        $this->middleware(function ($request, $next)
        {  
            if(session("admin_username"))
            {
                $error = "";

                $username = session("admin_username");
                $password = session("admin_password");

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

                if($error != "")
                {
                    session()->forget("admin_username");
                    session()->forget("admin_password");
                    return redirect("/super");
                }
                else
                {
                    $this->admin_info = $check_admin;
                }
            }
            else
            {
                /* IF NO SESSION - ALLOWED PAGE IS ONLY LOGIN AND LOGIN SUBMIT */
                if(request()->segment(2) != "" && request()->segment(2) != "login")
                {
                    abort(404);
                }
                
            }
            
            return $next($request);
        });
    }
}