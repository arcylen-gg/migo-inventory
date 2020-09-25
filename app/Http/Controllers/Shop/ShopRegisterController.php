<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Crypt;
use Redirect;
use View;
use Input;
use File;
use Image;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Tbl_pressiq_user;

class ShopRegisterController extends Shop
{
    public function index()
    {
        $data["page"] = "register";
        return view("register", $data);
    }
    public function press_signup(Request $request)
    {
        if(Session::exists('user_email'))
        {
            return Redirect::to("/");
        }
        else
        {
            if(request()->isMethod("post"))
            {       
                $value["user_first_name"]=request('user_first_name');
                $rules["user_first_name"]=['required'];
                $value["user_last_name"]=request('user_last_name');
                $rules["user_last_name"]=['required'];
                $value["user_email"]=request('user_email');
                $rules["user_email"]=['required','min:5','unique:tbl_pressiq_user,user_email'];
                $value["password"] = request('user_password');
                $value["password_confirmation"] = request("user_password_confirmation");
                $rules["password"] = ['required','min:5','confirmed'];
                $value["user_company_name"] = request("user_company_name");
                $rules["user_company_name"] = ['required'];
                $value["user_company_image"] = request("user_company_image");
                $rules["user_company_image"] = ['required'];
                $validator = Validator::make($value, $rules);

                if ($validator->fails()) 
                {
                    return Redirect::to("/sign_up")->with('message', $validator->errors()->first())->withInput();
                }
                else
                {
                    $path_prefix = 'http://digimaweb.solutions/public/uploadthirdparty/';
                    $path ="";
                    if($request->hasFile('user_company_image'))
                    {
                        $path = Storage::putFile('user_company_image', $request->file('user_company_image'));
                    }

                    $insert_user["user_level"]="2";
                    $insert_user["user_first_name"]=$value["user_first_name"];
                    $insert_user["user_last_name"]=$value["user_last_name"];
                    $insert_user["user_email"]=$value["user_email"];
                    $insert_user["user_password"]=Crypt::encrypt(request('user_password'));
                    $insert_user["user_date_created"]=Carbon::now();
                    $insert_user["user_company_name"]=$value["user_company_name"];
                    if($path!="")
                    {
                        $insert_user["user_company_image"]=$path_prefix.$path;
                    }
                    $user_id = tbl_pressiq_user::insertGetId($insert_user);  
                    return Redirect::to("/thank_you");   
                }       
            }
            else
            {
                $data["page"] = "sign_up";
                return view("sign_up", $data);  
            }
        }
    }
}