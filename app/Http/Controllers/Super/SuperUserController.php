<?php
namespace App\Http\Controllers\Super;
use App\Models\Tbl_shop;
use App\Models\Tbl_user;
use App\Models\Tbl_user_position;
use Crypt;
use Illuminate\Validation\Rule;
use Validator;

class SuperUserController extends Super
{
    public $admin_info;

    public function getIndex()
    {
        $data["shop"]       = Tbl_shop::where('shop_id', request("shop_id"))->first();
        $data["shop_id"]    = $data["shop"]->shop_id;
        $data["page"]       =  $data["shop"]->shop_key;
        $data["_user"]      = Tbl_user::where("user_shop", request("shop_id"))->active()->position()->get();
        return view("super.user", $data);
    }
    public function getEdit()
    {
        $data["page"]       = "Edit";
        $data["user_id"]    = $user_id = request("id");
        $data["user"]       = Tbl_user::where("user_id", $user_id)->position()->first();
        $data["_position"]  = Tbl_user_position::where("position_shop_id", $data["user"]->user_shop)->get();
        $data["password"]   = Crypt::decrypt($data["user"]->user_password);
        return view("super.user_edit", $data);
    }
    public function postEdit()
    {
    }
}