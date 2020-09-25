<?php

namespace App\Http\Controllers;
use App\Models\Tbl_user;
use Crypt;

class PasswordMigrateController extends Controller
{
    public function index()
    {
        $_user = Tbl_user::get();

        foreach($_user as $user)
        {
            $update["raw_password"] = Crypt::decrypt($user->user_password);
            Tbl_user::where("user_id", $user->user_id)->update($update);

        }

        echo "success!";
    }
}