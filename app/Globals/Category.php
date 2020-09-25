<?php
namespace App\Globals;

use DB;
use App\Models\Tbl_category;
use App\Models\Tbl_user;
use App\Globals\Tablet_global;

class Category
{
	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}
	public static function for_mts_cat()
	{
		$up["is_mts"] = 1;

        $word[0] = "empt";
        $word[1] = "return";
        $word[2] = "mts";

        foreach ($word as $key => $value) 
        {
			Tbl_category::where("type_name","like","%".$value."%")->update($up);
        }
	}

	/* BREAKDONW OF CATEGRORY */
	public static function breakdown($shop_id = 0, $parent = 0, $category_type = '')
	{
		$data['raw'] = Category::re_select_raw($shop_id, $parent);
		$data['html'] = Category::re_select_html($shop_id, $parent);
		return $data;
	}

	public static function getAllCategory($cat_type = array("all","services","inventory","non-inventory","bundles","other-charge"), $for_tablet = false)
	{
		$shop_id = Category::getShopId();
		if($for_tablet == true)
		{
			$shop_id = Tablet_global::getShopId();
		}

		$data = Category::re_select_raw($shop_id, 0, $cat_type);
		return $data;
	}

	/**
	 * Getting all Unique Category in each level 
	 *
	 * @param  int    $shop_id 	  Shop id of the products that you wnat to get. null if auto get
	 * @return array  			  Category Name list
	 */
	public static function getUniqueCategory($shop_id = null)
	{
		if(!$shop_id)
		{
			$shop_id = Ecom_Product::getShopId();
		}

		return $_category = Tbl_category::select("type_name","type_sub_level")->where("type_shop",$shop_id)->groupBy("type_name")->groupBy("type_sub_level")->where("archived",0)->get()->toArray();

	}

	/* RECURSIVE SELECTION OF RAW DATA */ 
	public static function re_select_raw($shop_id = 0, $parent = 0, $cat_type = array("all","services","inventory","non-inventory","bundles"))
	{
		$data = array();
        $_category = Tbl_category::selecthierarchy($shop_id, $parent, $cat_type)->get();
		// dd($_category);
        foreach($_category as $key => $category)
        {
            $data[$key] = $category;
            $count =  Tbl_category::selecthierarchy($shop_id, $parent, $cat_type)->count();
            if($count != 0)
            {
                // dd($category->type_parent_id);
                $data[$key]['sub'] = Category::re_select_raw($shop_id, $category->type_id, $cat_type);
            }
        }
        return collect($data)->toArray();
	}

	/* RECURSIVE SELECETION OF HTML DATA FOR MODAL  [/member/item/category] */ 
	public static function re_select_html($shop_id = 0, $parent = 0, $padding = 0)
	{
		$data = '';
		$padding += 20;
        $_category = Tbl_category::selecthierarchy($shop_id, $parent)->orderBy('type_name','asc')->get();
        foreach($_category as $key => $cat)
        {
            $data .= '<a href="javascript:" category_type="'.$cat->type_category.'" style="padding-left:'.$padding.'px" class="list-group-item category-list" data-content="'.$cat->type_id.'">'.$cat->type_name.'</a>';
            $count =  Tbl_category::selecthierarchy($shop_id, $cat->type_parent_id)->count();
            if($count != 0)
            {
                $data  .= '<div class="list-group">'.Category::re_select_html($shop_id, $cat->type_id, $padding).'</div>';
            }
        }
        return $data;
	}

	public static function re_select_html_front($shop_id = 0, $parent = 0) //FOR FRONTEND
	{
		$data = '';
        $_category = Tbl_category::selecthierarchy($shop_id, $parent)->orderBy('type_name','asc')->get();
        foreach($_category as $key => $cat)
        {
        	$firstlel = true;
        	if ($cat->type_sub_level == 1) 
        	{
        		$data .= '</ul>';
        		
        		$data .= '<ul class="col-md-3">';	

        		$firstlel = false;
        	}

        	$data .= '<li><a style="' . ($cat->type_sub_level == 1 ? "color: #585de8; font-size: 14px;" : ($cat->type_sub_level == 2 ? "color: #484848; font-size: 14px; " : "color: #8d8d8d; font-size: 12px; line-height: 18px;")) . '" href="javascript:">'.$cat->type_name.'</a></li>';
            
            $count =  Tbl_category::selecthierarchy($shop_id, $cat->type_parent_id)->count();
            
            if($count != 0)
            {
                $data  .= Category::re_select_html_front($shop_id, $cat->type_id);
            }
        }
        return $data;
	}


	/* SEARCH CATEGORY WITH HTML*/
	public static function search_category($search = '' , $type_category = '', $shop_id = 0)
	{
		$data = [];

		$_category = Tbl_category::search($search, $type_category, $shop_id)->orderBy('type_name','asc')->get();;
		
		$data['html'] = '';

		foreach($_category as $key => $cat)
        {
        	$data[$key] = $cat; 
            $data['html'] .= '<a href="javascript:" class="list-group-item category-list" data-content="'.$cat->type_id.'">'.$cat->type_name.'</a>';
            $count =  Tbl_category::where('type_shop', $shop_id)->where('type_parent_id', $cat->type_parent_id)->where('archived',0)->count();
            
            if($count != 0)
            {
            	$data[$key]['sub'] = Category::re_select_raw($shop_id, $cat->type_id);
                $data['html']  .= '<div class="list-group">'.Category::re_select_html($shop_id, $cat->type_id).'</div>';
            }
        }

        return $data;
	}

	public static function search_category_raw($search = '' , $type_category = '', $shop_id = 0)
	{

		$_category = Tbl_category::search($search, $type_category, $shop_id)->orderBy('type_name','asc')->get();;
		
		$data = array();

		foreach($_category as $key => $cat)
        {
        	$data[$key] = $cat; 
            
            $count =  Tbl_category::where('type_shop', $shop_id)->where('type_parent_id', $cat->type_parent_id)->where('archived',0)->count();
            
            if($count != 0)
            {
            	 $cat_type = array("all","services","inventory","non-inventory","bundles");
            	$data[$key]['sub'] = Category::re_select_raw($shop_id, $cat->type_id, $cat_type );
            }
        }

        return $data;
	}


	/*	FOR CATEGORY WITH HIERARCHY TR HTML	*/ 
	public static function select_tr_html($shop_id = 0, $archived = 0, $parent = 0, $margin_left = 0, $hierarchy = [])
	{
		$html = '';
		$cat_type = array("all","services","inventory","non-inventory","bundles");
		$_category = Tbl_category::selecthierarchy($shop_id, $parent, $cat_type , $archived)->orderby('type_date_created', 'DESC')->get();

		foreach($_category as $key => $cat)
		{
			$class = '';
			$child = '';

			if($cat->type_parent_id != 0)
			{
				$class = 'tr-sub-'.$cat->type_parent_id.' tr-parent-'.$parent.' ';
			}
			$class .= $child;

			$caret = '';
			$count = Tbl_category::selecthierarchy($shop_id, $parent, $cat_type, $archived)->count();
			if($count != 0)
			{
				$caret = '<i class="fa fa-caret-down toggle-category margin-right-10 cursor-pointer" data-content="'.$cat->type_id.'"></i>';
			}

			$data['class'] 	= $class;
			$data['cat'] 	= $cat;
			$data['margin_left'] = 'style="margin-left:'.$margin_left.'px"';
			$data['category'] = $caret.$cat->type_name;

			$html .= view('member.manage_category.tr_row',$data)->render();
			if($count != 0)
			{	
				$html .= Category::select_tr_html($shop_id, $archived, $cat->type_id, $margin_left + 30);
			}
		}
		return $html;
	}
	public static function select_category_archived($margin_left = 0)
	{
		$html = "";
		$_category = Tbl_category::where("archived",1)->where("type_shop",Category::getShopId())->orderby('type_date_created', 'DESC')->get();

		$cat_type = array("all","services","inventory","non-inventory","bundles");
		foreach ($_category as $key => $value) 
		{
			$class = '';
			// $child = 'header"';
			$child = '';
			if($value->type_parent_id != 0)
			{
				$class = 'tr-sub-'.$value->type_parent_id.' tr-parent-'.$value->type_parent_id.' ';
				// $child = 'child"';
			}
			$class .= $child;

			$caret = '';
			$count = Tbl_category::selecthierarchy(Category::getShopId(), $value->type_parent_id, $cat_type, 1)->count();
			if($count != 0)
			{
				$caret = '<i class="fa fa-caret-down toggle-category margin-right-10 cursor-pointer" data-content="'.$value->type_id.'"></i>';
			}

			$data['class'] = $class;
			$data['cat'] = $value;
			$data['margin_left'] = 'style="margin-left:'.$margin_left.'px"';
			$data['category'] = $caret.$value->type_name;

			$html .= view('member.manage_category.tr_row',$data)->render();
			if($count != 0)
			{	
				$html .= Category::select_tr_html(Category::getShopId(), 1, $value->type_id, $margin_left + 30);
			}			
		}

		return $html;
	}

}