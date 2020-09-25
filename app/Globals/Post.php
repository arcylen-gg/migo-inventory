<?php
namespace App\Globals;
use DB;
use Session;
use App\Models\Tbl_post;
use App\Models\Tbl_post_category;
use App\Models\Rel_post_category;
use Carbon\Carbon;
use Validator;

/**
 * Post Maintenance - all post of shop or front related module
 *
 * @author Edward Guevarra
 */

class Post
{
    /**
     * Get the id of current shop_id that logged in.
     *
     * @return int      Shop id
     */
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    /**
     * For adding post.
     *
     * @param array    $insert   Needed data for adding post. This is required.
     */
    public static function add($insert)
    {
        $post_category_id       = $insert["post_category_id"];
        unset($insert["post_category_id"]);

        $rules['post_author']   = 'required';
        $rules['post_date']     = 'required|date';
        $rules['post_title']    = 'required';
        $rules['post_content']  = 'required';
        // $rules['post_excerpt']  = 'required';
        $rules['post_type']     = 'required';
        $rules['post_image']    = 'required';

        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            $response["response_status"]  = "warning";
            $response["message"]          = null;
            foreach ($validator->errors()->all() as $key => $value) 
            {
                $response["message"] .= $value . "</br>";
            }
        }
        else
        {
            $success = Tbl_post::insertGetId($insert);

            if ($success) 
            {
                Rel_post_category::where("post_id", $success)->delete();

                if ($post_category_id) 
                {
                    foreach ($post_category_id as $key => $value) 
                    {
                        $rel_insert["post_id"]          = $success;
                        $rel_insert["post_category_id"] = $value;
                        
                        Rel_post_category::insert($rel_insert);
                    }
                }

                $response["response_status"]  = "success";
                $response["message"]          = "Post has been successfully added.";
            }
            else
            {
                $response["response_status"]  = "warning";
                $response["message"]          = "Some error occurred. Please try again.";
            }
        }

        return $response;
    }

    /**
     * For editing post.
     *
     * @param array    $insert   Needed data for editing post. This is required.
     */
    public static function edit($insert, $id)
    {
        $post_category_id       = $insert["post_category_id"];
        unset($insert["post_category_id"]);

        $rules['post_author']   = 'required';
        $rules['post_date']     = 'required|date';
        $rules['post_title']    = 'required';
        $rules['post_content']  = 'required';
        // $rules['post_excerpt']  = 'required';
        $rules['post_type']     = 'required';
        $rules['post_image']    = 'required';

        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            $response["response_status"]  = "warning";
            $response["message"] = null;
            foreach ($validator->errors()->all() as $key => $value) 
            {
                $response["message"] .= $value . "</br>";
            }
        }
        else
        {
            $success = Tbl_post::where("post_id", $id)->update($insert);

            if ($success) 
            {
                Rel_post_category::where("post_id", $id)->delete();

                if ($post_category_id) 
                {
                    foreach ($post_category_id as $key => $value) 
                    {
                        $rel_insert["post_id"]          = $id;
                        $rel_insert["post_category_id"] = $value;
                        
                        Rel_post_category::insert($rel_insert);
                    }
                }

                $response["response_status"]  = "success";
                $response["message"]          = "Post has been successfully edited.";
            }
            else
            {
                $response["response_status"]  = "warning";
                $response["message"]          = "Some error occurred. Please try again.";
            }
        }

        return $response;
    }

    /**
     * For adding category post.
     *
     * @param array    $insert   Needed data for adding category post. This is required.
     */
    public static function category_add($insert)
    {
        $response['id']              = null;

        $rules['post_category_name'] = 'required';

        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            $response["response_status"]  = "warning";
            $response["message"]          = null;
            foreach ($validator->errors()->all() as $key => $value) 
            {
                $response["message"] .= $value . "</br>";
            }
        }
        else
        {
            $success = Tbl_post_category::insertGetId($insert);

            if ($success) 
            {
                $response['id']               = $success;
                $response["response_status"]  = "success";
                $response["message"]          = "Post Category has been successfully added.";
            }
            else
            {
                $response["response_status"]  = "warning";
                $response["message"]          = "Some error occurred. Please try again.";
            }
        }

        return $response;
    }

    /**
     * For getting post.
     *
     * @param array    $shop_id   Get posts for current shop. If shop_id is null, the current shop id that logged on will be used.
     */
    public static function get_posts($shop_id)
    {
        if(!$shop_id)
        {
            $shop_id = Ecom_Product::getShopId();
        }

        $post = Tbl_post::select("*","tbl_post.post_id as main_id",DB::raw('group_concat(rel_post_category.post_category_id) as post_categories'))
                          ->where("tbl_post.archived", 0)
                          ->where("tbl_post.shop_id", $shop_id)
                          ->leftJoin("rel_post_category", "tbl_post.post_id", "=", "rel_post_category.post_id")
                          ->leftJoin("tbl_post_category", "rel_post_category.post_category_id", "=", "tbl_post_category.post_category_id")
                          ->groupBy("tbl_post.post_id")
                          ->get();

        foreach ($post as $key => $value) 
        {
            $category_id = explode(",", $value->post_categories);
            $category = DB::table("tbl_post_category")->select("post_category_name")->whereIn("post_category_id", $category_id)->get();
            $post[$key]->categories = $category;
        }        

        return $post;
    }

    /**
     * For getting related post.
     *
     * @param array    $category_id   Paremeter for current category.
     * @param array    $shop_id   Get posts for current shop. If shop_id is null, the current shop id that logged on will be used.
     */
    public static function get_related_posts($category_id, $shop_id)
    {
        if(!$shop_id)
        {
            $shop_id = Ecom_Product::getShopId();
        }

        $post = Tbl_post::select("*","tbl_post.post_id as main_id",DB::raw('group_concat(rel_post_category.post_category_id) as post_categories'))
                          ->where("tbl_post.archived", 0)
                          ->where("tbl_post.shop_id", $shop_id)
                          ->where("tbl_post_category.post_category_id", $category_id)
                          ->leftJoin("rel_post_category", "tbl_post.post_id", "=", "rel_post_category.post_id")
                          ->leftJoin("tbl_post_category", "rel_post_category.post_category_id", "=", "tbl_post_category.post_category_id")
                          ->groupBy("tbl_post.post_id")
                          ->get();

        foreach ($post as $key => $value) 
        {
            $category_id = explode(",", $value->post_categories);
            $category = DB::table("tbl_post_category")->select("post_category_name")->whereIn("post_category_id", $category_id)->get();
            $post[$key]->categories = $category;
        }        

        return $post;
    }

    /**
     * For getting single post.
     *
     * @param array    $shop_id   Get posts for current shop. If shop_id is null, the current shop id that logged on will be used.
     * @param array    $post_id   Get specific post. If shop_id is null, the current shop id that logged on will be used.
     */
    public static function get_post($post_id, $shop_id)
    {
        if(!$shop_id)
        {
            $shop_id = Ecom_Product::getShopId();
        }

        $post = Tbl_post::select("*","tbl_post.post_id as main_id",DB::raw('group_concat(rel_post_category.post_category_id) as post_categories'))
                          ->where("tbl_post.archived", 0)
                          ->where("tbl_post.shop_id", $shop_id)
                          ->where("tbl_post.post_id", $post_id)
                          ->leftJoin("rel_post_category", "tbl_post.post_id", "=", "rel_post_category.post_id")
                          ->leftJoin("tbl_post_category", "rel_post_category.post_category_id", "=", "tbl_post_category.post_category_id")
                          ->groupBy("tbl_post.post_id")
                          ->first();

        $category_id = explode(",", $post->post_categories);
        $category = DB::table("tbl_post_category")->select("post_category_name")->whereIn("post_category_id", $category_id)->get();
        $post->categories = $category;

        return $post;
    }
}