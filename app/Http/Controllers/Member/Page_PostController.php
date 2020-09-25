<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use App\Models\Tbl_shop;
use App\Models\Tbl_post_category;
use App\Models\Rel_post_category;
use Validator;
use DB;
use App\Globals\Post;
use App\Models\Tbl_post;
use App\Globals\Utilities;
use Redirect;

class Page_PostController extends Member
{
    public function getIndex()
    {
        $access = Utilities::checkAccess('page-post', 'access_page');
        if($access == 1)
        {
            $data["page"] = "Post List";

            if (Request::input("archive")) 
            {
                $archive = 1;
            }
            else
            {
                $archive = 0;
            }

            $data["_post"] = DB::table("tbl_post")->where("tbl_post.archived", $archive)
                                                  ->where("tbl_post.shop_id", $this->user_info->shop_id)
                                                  // ->where("tbl_post.post_author", $this->user_info->user_id)
                                                  ->leftJoin("tbl_user", "tbl_post.post_author", "=", "tbl_user.user_id")
                                                  ->leftJoin("rel_post_category", "tbl_post.post_id", "=", "rel_post_category.post_id")
                                                  ->leftJoin("tbl_post_category", "rel_post_category.post_category_id", "=", "tbl_post_category.post_category_id")
                                                  ->groupBy("tbl_post.post_id")
                                                  ->select("*","tbl_post.post_id as main_id",DB::raw('group_concat(rel_post_category.post_category_id) as post_categories'))
                                                  ->get();

            foreach ($data["_post"] as $key => $value) 
            {
                $category_id = explode(",", $value->post_categories);
                $category = DB::table("tbl_post_category")->whereIn("post_category_id", $category_id)->get();
                $data["_post"][$key]->categories = $category;
            }                                     
         
            return view('member.page.page_post', $data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function getCategory()
    {
    	$data["page"] = "Post Category";

    	return view('member.page.page_post_category', $data);
    }
    public function postCategorysubmit()
    {
    	$insert['post_category_name'] = Request::input("post_category_name");
        $insert['shop_id']            = $this->user_info->shop_id;

        $response = Post::category_add($insert);

        $response['from']     = 'category';
        $response['category'] = Tbl_post_category::where("post_category_id", $response["id"])->first();

        return json_encode($response);
    }
    public function getAdd()
    {
        $access = Utilities::checkAccess('page-post', 'add_post');
        if($access == 1)
        {
            $data["page"] = "Post Add";

            $data["_category"] = Tbl_post_category::where("shop_id", $this->user_info->shop_id)->get();

            return view('member.page.page_post_add', $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function postAddsubmit()
	{	
		$insert['post_author']      = $this->user_info->user_id;
		$insert['post_date']        = date("Y-m-d H:i:s");
		$insert['post_title']       = Request::input("post_title");  
		$insert['post_content']     = Request::input("post_content");
		$insert['post_excerpt']     = Request::input("post_excerpt");
		$insert['post_type']        = Request::input("post_type");
		$insert['post_image']       = Request::input("post_image");
        $insert['post_category_id'] = Request::input("post_category_id");
        $insert['shop_id']          = $this->user_info->shop_id;

		$response = Post::add($insert);

        $response['from'] = 'post';

		return json_encode($response);
	}
	public function getEdit($id)
	{
        $access = Utilities::checkAccess('page-post', 'edit_post');
        if($access == 1)
        {
            $data["page"] = "Post Edit";

            $data["data"]      = DB::table("tbl_post")->where("post_id", $id)->first();
            $data["id"]        = $id;
            $data["_category"] = Tbl_post_category::where("shop_id", $this->user_info->shop_id)->get();

            foreach ($data["_category"] as $key => $value) 
            {
                $check = Rel_post_category::where("post_id", $id)->where("post_category_id", $value->post_category_id)->first();
                
                if ($check) 
                {
                    $data["_category"][$key]->checked = true;
                }
                else
                {
                    $data["_category"][$key]->checked = false;
                }
            }
            
            return view("member.page.page_post_edit", $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
	}
	public function postEditsubmit($id)
	{
		$insert['post_author']      = $this->user_info->user_id;
		$insert['post_date']        = date("Y-m-d H:i:s");
		$insert['post_title']       = Request::input("post_title");  
		$insert['post_content']     = Request::input("post_content");
		$insert['post_excerpt']     = Request::input("post_excerpt");
		$insert['post_type']        = Request::input("post_type");
		$insert['post_image']       = Request::input("post_image");
        $insert['post_category_id'] = Request::input("post_category_id");
        $insert['shop_id']          = $this->user_info->shop_id;

		$response = Post::edit($insert, $id);

        $response['from'] = 'post';

		return json_encode($response);
	}
    public function getArchive($id)
    {
        $access = Utilities::checkAccess('page-post', 'archive_post');
        if($access == 1)
        {
            $result = Tbl_post::where("post_id", $id)->update(["archived" => 1]);

            $response["id"] = $id;

            return Redirect::back();
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function getUnarchive($id)
    {
        $access = Utilities::checkAccess('page-post', 'archive_post');
        if($access == 1)
        {
            $result = Tbl_post::where("post_id", $id)->update(["archived" => 0]);

            $response["id"] = $id;

            return Redirect::back();
        }
        else
        {
            return $this->show_no_access();
        }
    }
}