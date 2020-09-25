<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DB;
use Input;
use File;
use Carbon\Carbon;
class ShopAboutController extends Shop
{
    public function index()
    {
        $data["page"] = "About";
        return view("about", $data);
    }

    public function runruno()
    {
    	$data["page"] = "Runruno";
    	return view("runruno", $data);
    }

    public function news()
    {
        $data["page"] = "news";
        $id = Request::input("id");
        $data["main_news"] = DB::table("tbl_post")->where("post_id", $id)->first();
        if (!isset($data["main_news"])) 
        {
            return Redirect::to("/");
        }
        return view("news", $data);
    }

    public function contactus()
    {
        $data["page"] = "Contact Us";
        return view("contactus", $data);
    }

    public function email_payment()
    {
        $data["page"] = "Email Payment";
        return view("email_payment", $data);
    }
    
    public function jobs()
    {
        $data["page"] = "jobs";
        return view("jobs", $data);
    }

    public function promos()
    {
        $data["page"] = "promos";
        return view("promos", $data);
    }

    public function promo_view()
    {
        $data["page"] = "promo_view";
        return view("promo_view", $data);
    }

    public function history()
    {
        $data["page"] = "history";
        return view("history", $data);
    }

    public function about_red_fruit()
    {
        $data["page"] = "about_red_fruit";
        return view("about_red_fruit", $data);
    }

    public function how_to_join()
    {
        $data["page"] = "how_to_join";
        return view("how_to_join", $data);
    }
    public function xcell_login()
    {
        $data["page"] = "3xcell_login";
        return view("3xcell_login", $data);
    }
    public function xcell_signup()
    {
        $data["page"] = "3xcell_signup";
        return view("3xcell_signup", $data);
    }    

    public function job()
    {
        if (Request::input("id") !== null) 
        {
            $data["page"] = "job";
            $content = DB::table("tbl_content")->where("key", "job_maintenance")->where("type", "maintenance")->where("shop_id", $this->shop_info->shop_id)->first();
            if ($content) 
            {
                if (is_serialized($content->value)) 
                {
                    if (isset(unserialize($content->value)[Request::input("id")])) 
                    {
                        $data["job"] = unserialize($content->value)[Request::input("id")];
                    }
                    
                    else
                    {
                        return Redirect::to("/jobs");
                    }
                }
                else
                {
                    return Redirect::to("/jobs");
                }
            }

            return view("job", $data);
        }
        else
        {
            return Redirect::to("/jobs");
        }
    }

    public function job_submit()
    {
        $shop_id    = $this->shop_info->shop_id;
        $shop_key   = $this->shop_info->shop_key;

        /* SAVE THE IMAGE IN THE FOLDER */
        $file               = Input::file('job_resume');
        $extension          = $file->getClientOriginalExtension();
        $filename           = str_random(15).".".$extension;
        $destinationPath    = 'uploads/'.$shop_key."-".$shop_id.'/job-upload';

        if(!File::exists($destinationPath)) 
        {
            $create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
        }

        $upload_success    = Input::file('job_resume')->move($destinationPath, $filename);

        /* SAVE THE IMAGE PATH IN THE DATABASE */
        $image_path = $destinationPath."/".$filename;

        $insert['job_apply'] = Request::input('job_apply');
        $insert['job_resume'] = $image_path;
        $insert['job_introduction'] = Request::input('job_introduction');
        $insert['date_created'] = Carbon::now();

        DB::table('tbl_cms_job_resume')->insert($insert);

        if( $upload_success ) 
        {
           return Redirect::to('/jobs#popup2');
        } 
        else 
        {
           return Redirect::to('/jobs#popup4');
        }
    }

    public function replicated()
    {
        $data["page"] = "replicated";
        return view("replicated", $data);
    }

    public function terms_and_conditions()
    {
        $data["page"] = "Terms and Condition";
        return view("terms_and_conditions", $data);
    }

    public function return_policy()
    {
        $data["page"] = "Return Policy";
        return view("return_policy", $data);
    }

    public function privacy_policy()
    {
        $data["page"] = "Privacy Policy";
        return view("privacy_policy", $data);
    }

    public function franchise_terms_and_condition()
    {
        $data["page"] = "Franchise Terms and Condition";
        return view("franchise_terms_and_condition", $data);
    }

    public function general_terms_and_condition()
    {
        $data["page"] = "General Terms and Condition";
        return view("general_terms_and_condition", $data);
    }

    public function products()
    {
        $data["page"] = "products";
        return view("member.products", $data);
    }

    public function certificate()
    {
        $data["page"] = "certificate";
        return view("member.certificate", $data);
    }



}