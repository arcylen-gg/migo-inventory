<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use App\Models\Tbl_shop;
use App\Models\Tbl_content;
use App\Models\Tbl_post_category;
use Redirect;
use App\Models\Tbl_post;
use App\Models\Tbl_collection;
use App\Globals\Category;
use DB;

class Page_ContentController extends Member
{
    public function getIndex()
    {
        $data["page"] = "Vendor List";
        $dirs = scandir("../public/themes");
        $data["theme_color"] = $this->user_info->shop_theme_color;
        $data["shop_theme"] = $this->user_info->shop_theme;
        $data["page_info"] = $string = file_get_contents("../public/themes/" . $this->user_info->shop_theme . "/page.json");
        $data["page_info"] = json_decode($string);
        if ($data["page_info"]) 
        {
            foreach ($data["page_info"] as $key => $value) 
            {
                foreach ($value as $keys => $values) 
                {
                    $get = Tbl_content::where("key", $keys)->where("type", $values->type)->where("shop_id", $this->user_info->shop_id)->first();
                    if ($get) 
                    {
                        $data["page_info"]->$key->$keys->type = $get->type;
                        $data["page_info"]->$key->$keys->default = $get->value;
                    }
                }
            }
        }
        else
        {
            die('An error has occurred. Please contact your system administrator.');
        }

        $data["company_info"] = collect(Tbl_content::where("shop_id", $this->user_info->shop_id)->get())->keyBy('key');
        $data["_collection"] = Tbl_collection::where("shop_id", $this->user_info->shop_id)->where("archived", 0)->where("collection_status", 1)->get();
        $data["_brand"] = Category::getUniqueCategory();
        foreach ($data["_brand"] as $key => $value) 
        {
            if ($value["type_sub_level"] != 1) 
            {
                unset($data["_brand"][$key]);
            }
        }

        /* FCF Exclusive */
        if ($data["shop_theme"] == "fcf") 
        {
            $data["job_resume"] = DB::table("tbl_cms_job_resume")->where("archived", 0)->orderBy("date_created", "DESC")->get();
        }

        /* INTOGADGETS */
        if($data["shop_theme"] == "intogadgets") 
        {
            $data['popular_tags'] = DB::table("tbl_ec_popular_tags")->where("shop_id", $this->user_info->shop_id)->orderBy("count", "DESC")->get();
        }

        return view('member.page.page_content', $data);
    }

    public function postIndex()
    {
        $info  = Request::input("info");
        foreach ($info as $key => $value) 
        {
            $exist = Tbl_content::where("key", $key)->where("type", $value["type"])->where("shop_id", $this->user_info->shop_id)->first();

            $insert["key"]       = $key;

            if ($value["type"] == "gallery") 
            {
                if (is_serialized($value["value"])) 
                {
                    $insert["value"] = $value["value"];
                }
                else
                {
                    if ($value["value"]) 
                    {
                        $insert["value"] = serialize(explode(",",$value["value"]));
                    }
                    else
                    {
                        $insert["value"] = "";
                    }
                }   
            }
            elseif ($value["type"] == "gallery_links") 
            {
                if (isset($value["value"]) && $value["value"]) 
                {
                    $insert["value"] = serialize($value["value"]);
                }
                else
                {
                    $insert["value"] = "";
                }
            }
            elseif ($value["type"] == "brand") 
            {
                $insert["value"] = serialize($value["value"]);
            }
            else
            {
                if(isset($value["value"]))
                {
                    $insert["value"] = $value["value"];
                }
                else
                {
                    $insert["value"] = "";
                }
            }

            $insert["type"]      = $value["type"];
            $insert["shop_id"]   = $this->user_info->shop_id;

            if ($exist) 
            {
                Tbl_content::where("content_id", $exist->content_id)->where("shop_id", $this->user_info->shop_id)->update($insert);
            }
            else
            {   
                Tbl_content::insert($insert);
            }
        }

        return Redirect::back();
    }
    public function getUpdateTag()
    {
        $tag_id = Request::input("tag_id");
        $data_tag = DB::table('tbl_ec_popular_tags')->where("tag_id",$tag_id)->first();

        $update["tag_approved"] = 1;
        if($data_tag->tag_approved == 1)
        {
            $update["tag_approved"] = 0;
        }

        DB::table('tbl_ec_popular_tags')->where("tag_id",$tag_id)->update($update);

        $data["status"] = "success";

        return json_encode($data);
    }
    public function getPost($limit)
    {
        if ($limit) {
            # code...
        }
        $post          = Tbl_content::where("key", Request::input("key"))->where("shop_id", $this->user_info->shop_id)->first();
        if ($post) 
        {
            $explode_post  = explode(",", $post->value);
        }
        $data["_post"] = Tbl_post::where("archived", 0)->where("shop_id", $this->user_info->shop_id)->get();
        foreach ($data["_post"] as $key => $value) 
        {
            $data["_post"][$key]->selected = false;
            if(isset($explode_post))
            {
                foreach ($explode_post as $keys => $values) 
                {
                    if ($value->post_id == $values) 
                    {
                        $data["_post"][$key]->selected = true;
                    }       
                }
            }
        }
        $data["limit"] = $limit;
 
        return view("member.page.page_content_post", $data);
    }

    public function postSubmitPost()
    {
        $post = Request::input("post");
        $limit = Request::input("limit");

        if($limit != "all")
        {
            if(count($post) <= $limit)
            {
                $response["response_status"] = "success";
                $response["message"]         = "Successfully added posts.";
                $response["post"]            = $post;
            }
            else
            {
                $response["response_status"] = "warning";
                $response["message"]         = "Select " . $limit . " post only.";
            }
        }
        else
        {
            $response["response_status"] = "success";
            $response["message"]         = "Successfully added posts.";
            $response["post"]            = $post;
        }

        return json_encode($response);
    }

    public function getMaintenance()
    {
        $field = Request::input("field");
        $key   = Request::input("key");

        if (isset($field) && isset($key)) 
        {
            $data["field"] = $field;
            $data["key"]   = $key;
            $content = Tbl_content::where("key", $key)->where("type", "maintenance")->where("shop_id", $this->user_info->shop_id)->first();
            if (isset($content->value))
            {
                $content = $content->value;
            }
            if (is_serialized($content)) 
            {
                $data["_content"] = unserialize($content);
                foreach ($data["_content"] as $key => $value) 
                {
                    if (isset($data["_content"][$key]["key"])) 
                    {
                        unset($data["_content"][$key]["key"]);
                    }
                }
            }
            else
            {
                $data["_content"] = [];
            }
            
            return view("member.page.page_maintenance", $data);
        }
    }

    public function getAddMaintenance()
    {
        $field = unserialize(Request::input("field"));
        $key   = Request::input("key");
        
        if (isset($field) && isset($key)) 
        {
            $data["field"] = $field;
            $data["key"]   = $key;

            return view("member.page.page_maintenance_add", $data);
        }
    }

    public function getEditMaintenance()
    {
        $key   = Request::input("key");
        $id    = Request::input("id");
        $field = unserialize(Request::input("field"));

        if (isset($key) && isset($id) && isset($field)) 
        {
            $content = Tbl_content::where('key', $key)->where("type", "maintenance")->where("shop_id", $this->user_info->shop_id)->first();

            $data["edit"]  = unserialize($content->value)[$id];
            $data["field"] = $field;
            $data["key"]   = $key;
            $data["id"]    = $id;

            return view("member.page.page_maintenance_edit", $data);
        }
    }

    public function postSubmitMaintenance($id)
    {
        if ($id > -1) 
        {
            // Edit
            $all = Request::except("_token");
            $key = Request::input('key');
            $exist = Tbl_content::where('key', $key)->where("type", "maintenance")->where("shop_id", $this->user_info->shop_id)->first();
            if ($exist) 
            {
                $content_id = $exist->content_id;
                $get_content = $exist->value;

                if (is_serialized($get_content)) 
                {
                    $get_content = unserialize($get_content);
                }
                else
                {
                    $get_content = [];
                }

                $get_content[$id] = $all;

                $get_content = serialize($get_content);
                Tbl_content::where('content_id', $content_id)->where("type", "maintenance")->update(["value" => $get_content]);
            }
            else
            {
                $get_content = [];

                array_push($get_content, $all);
                $get_content = serialize($get_content);

                $content_id = Tbl_content::insertGetId(["type" => "maintenance", "key" => $key, "value" => $get_content, "shop_id" => $this->user_info->shop_id]);
            }
            
            $response["result"] = Tbl_content::where('content_id', $content_id)->first();
            $response["response_status"] = "success";
            $response["do"] = "manage";
            $response["key"] = $key;

            return json_encode($response);
        }
        else
        {
            // Add
            $all = Request::except("_token");
            $key = Request::input('key');
            $exist = Tbl_content::where('key', $key)->where("type", "maintenance")->where("shop_id", $this->user_info->shop_id)->first();
            if ($exist) 
            {
                $content_id = $exist->content_id;
                $get_content = $exist->value;

                if (is_serialized($get_content)) 
                {
                    $get_content = unserialize($get_content);
                }
                else
                {
                    $get_content = [];
                }

                array_push($get_content, $all);
                $get_content = serialize($get_content);
                Tbl_content::where('content_id', $content_id)->where("type", "maintenance")->update(["value" => $get_content]);
            }
            else
            {
                $get_content = [];

                array_push($get_content, $all);
                $get_content = serialize($get_content);

                $content_id = Tbl_content::insertGetId(["type" => "maintenance", "key" => $key, "value" => $get_content, "shop_id" => $this->user_info->shop_id]);
            }
            
            $response["result"] = Tbl_content::where('content_id', $content_id)->where("type", "maintenance")->first();
            $response["response_status"] = "success";
            $response["do"] = "manage";
            $response["key"] = $key;

            return json_encode($response);
        }
    }

    public function getDeleteMaintenance()
    {
        return view("member.page.page_maintenance_delete");
    }

    public function postDeleteMaintenance()
    {
        $id    = Request::input("id");
        $key   = Request::input('key');
        $exist = Tbl_content::where('key', $key)->where("type", "maintenance")->where("shop_id", $this->user_info->shop_id)->first();
        if ($exist) 
        {
            $content_id = $exist->content_id;
            $get_content = unserialize($exist->value);
       
            unset($get_content[$id]);

            $get_content = serialize($get_content);
            Tbl_content::where('content_id', $content_id)->where("type", "maintenance")->update(["value" => $get_content]);
        }
        
        $response["result"] = Tbl_content::where('content_id', $content_id)->where("type", "maintenance")->first();
        $response["response_status"] = "success";
        $response["do"] = "delete";
        $response["key"] = $key;

        return json_encode($response);
    }

    public function getMaintenanceCount()
    {
        $key = Request::input('key');
        $exist = Tbl_content::where('key', $key)->where("type", "maintenance")->where("shop_id", $this->user_info->shop_id)->first();
        if ($exist) 
        {
            $content_id = $exist->content_id;
            $get_content = $exist->value;

            if (is_serialized($get_content)) 
            {
                $get_content = unserialize($get_content);
            }
            else
            {
                $get_content = [];
            }
        }
        else
        {
            $get_content = [];
        }

        $count = count($get_content);

        return json_encode($count);
    }
}