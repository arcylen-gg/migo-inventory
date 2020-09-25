<?php
namespace App\Http\Controllers\Super;
use App\Models\Tbl_shop;
use App\Models\Tbl_user;
use App\Models\Tbl_country;
use Crypt;
use Illuminate\Validation\Rule;
use Validator;

class SuperClientController extends Super
{
    public $admin_info;

    public function getIndex()
    {
        if(request("filter") == "archive")
        {
            $data["page"] = "Archive List";
        }
        else
        {
            $data["page"] = "Client List";
        }
        
        $query = Tbl_shop::orderBy("shop_key");


        if(request("filter") == "archive")
        {
            $query->archived();
        }
        else
        {
            $query->active();
        }

        $_shop = $query->get();

        foreach($_shop as $key => $shop)
        {
            $_shop[$key]->shop_name     = strtolower($shop->shop_key);
            $_shop[$key]->domain        = ($shop->shop_domain == "unset_yet" ? "<span style='color: gray;'>no url set yet</span>" : $shop->shop_domain);
            $user_count                 = Tbl_user::where("user_shop", $shop->shop_id)->active()->count();
            $_shop[$key]->user_count    = "<span style='color: gray;'>" . $user_count . " user(s)" . "</span>";
        }

        $data["_shop"] = $_shop;

        return view("super.customer", $data);
    }
    public function getAdd()
    {
        $data["page"]       = "Client Add";
        $data["_country"]   = Tbl_country::get();
        return view("super.customer_add", $data);
    }
    public function postAdd()
    {
        $condition["first_name"]        = array("required");
        $condition["last_name"]         = array("required");
        $condition["user_email"]        = array("required", "email", Rule::unique('tbl_user')->ignore('', 'user_email'));
        $condition["password"]          = array("required", "confirmed", "min:5");
        $condition["shop_key"]          = array("required",'alpha_dash', Rule::unique('tbl_shop')->ignore('', 'shop_key'));
        $condition["contact"]           = array("required");
        $condition["country"]           = array("required");
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
            $insert_shop["shop_key"]            = request("shop_key");
            $insert_shop["shop_status"]         = "trial";
            $insert_shop["shop_country"]        = request("country");
            $insert_shop["shop_street_address"] = request("complete_address");
            $insert_shop["shop_contact"]        = request("contact");
            $shop_id                            = Tbl_shop::insertGetId($insert_shop);

            $insert_user["user_email"]          = request("user_email");
            $insert_user["user_shop"]           = $shop_id;
            $insert_user["user_first_name"]     = request("first_name");
            $insert_user["user_last_name"]      = request("last_name");
            $insert_user["user_contact_number"] = request("contact");
            $insert_user["user_password"]       = Crypt::encrypt(request("password"));
            $insert_user["user_level"]          = 1;

            Tbl_user::insert($insert_user);

            $return["title"]        = "Successfully Created";
            $return["message"]      = "New Customer has been created and added to list.";  
            $return["back"]         = true; 
        }

        echo json_encode($return);
    }
    public function getEdit()
    {
        $data["page"]       = "Customer Edit";
        $data["shop"]       = Tbl_shop::where('shop_id', request("id"))->first();
        $data["shop_id"]    = $data["shop"]->shop_id;
        $data["created"]    = date("m/d/Y", strtotime($data["shop"]->shop_date_created));
        $data["edited"]     = date("m/d/Y", strtotime($data["shop"]->updated_at));
        $data["user_count"] = Tbl_user::where("user_shop", $data["shop"]->shop_id)->active()->count();
        $data["developer"]  = Tbl_user::where("user_shop", $data["shop"]->shop_id)->where("user_level", 1)->first();
        
        if($data["developer"])
        {
            $data["user_password"] = Crypt::decrypt($data["developer"]->user_password);
        }

        return view("super.customer_edit", $data);
    }
    public function postEdit()
    {
        $shop_old = Tbl_shop::where("shop_id", request("id"))->first();

        $condition["shop_key"]          = array("required", Rule::unique('tbl_shop')->ignore($shop_old->shop_key, 'shop_key'));
        $condition["shop_contact"]      = array("required");
        $validator                      = Validator::make(request()->all(), $condition);

        if ($validator->fails())
        {
            $errors                 = $validator->errors();
            $return["status"]       = "error";
            $return["title"]        = "Validation Error";
            $return["message"]      = $errors->first();
        }
        else
        {
            $update = request()->input();
            unset($update["_token"]);
            unset($update["id"]);

            if($update["shop_domain"] == "")
            {
                $update["shop_domain"] = "unset_yet";
            }

            Tbl_shop::where("shop_id", request("id"))->update($update);

            $return["title"] = "Successfully Updated";
            $return["message"] = "Information of Customer No. " . request("id") . " has been successfully updated.";     
        }
        echo json_encode($return);
    }
    public function getArchive()
    {
        $update["archived"] = 1;
        Tbl_shop::where("shop_id", request("shop_id"))->update($update);
    }
    public function getRestore()
    {
        $update["archived"] = 0;
        Tbl_shop::where("shop_id", request("shop_id"))->update($update);
    }
}