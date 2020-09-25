<?php
namespace App\Http\Controllers\Member;

use Request;
use App\Models\Tbl_category;
use App\Models\Tbl_item;
use Carbon\Carbon;
use App\Globals\Category;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\AccountingTransaction;


class Manage_Category_Controller extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access = Utilities::checkAccess('item-categories', 'access_page');
        if($access == 1)
        {

            Category::for_mts_cat();
            $shop_id = $this->user_info->user_shop;
            $data['category'] = Category::select_tr_html($shop_id, 0);
            // dd($data['category']);
            $data['archived_category'] = Category::select_category_archived();
            return view('member.manage_category.manage_category_list', $data);
        }
        else
        {
            return $this->show_no_access();
        }
    }

    public function load_category()
    {
        $data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');
        $category_type = Request::input("type");
        if($category_type == '')
        {
            $cat_type = array("all","services","inventory","non-inventory","bundles","other-charge");
        }
        else
        {
            $cat_type[0] = $category_type;
        }
        $access = Utilities::checkAccess('item-categories', 'access_page');
        if($access == 1)
        {
            $data['_category'] = Category::getAllCategory($cat_type);
            $data['add_search'] = '';

            return view('member.load_ajax_data.load_category', $data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function archived($id, $action)
    {
        $data["cat_id"] = $id;
        $data["action"] = $action;

        $data["cat"] = Tbl_category::where("type_id",$id)->first();


        return view("member.manage_category.category_confirm",$data);
    }
    public function archived_submit()
    {
        $id = Request::input("cat_id");
        $action = Request::input("action");

        $chk = Tbl_item::where("item_category_id",$id)->where("archived",0)->count();

        $update["archived"] = 0;
        $data["status"] = "success-category"; 
        if($action == "archived")
        {
            if($chk == 0)
            {          
                $update["archived"] = 1;
                $data["status"] = "success-category";         
            }
            else
            {
                $data["status"] = "error";
                $data["status_message"] = "The category is in used";
            }
        }
        $all = Tbl_category::where("type_parent_id",$id)->get();
        if($all)
        {
            foreach ($all as $key => $value) 
            {
                Tbl_category::where("type_id",$value->type_id)->update($update);
            }            
        }

        Tbl_category::where("type_id",$id)->update($update);
        if($data["status"] != "error")
        {
            $cat_data = AuditTrail::get_table_data("tbl_category","type_id",$id);
            AuditTrail::record_logs($action,"category",$id,"",serialize($cat_data));            
        }

        return json_encode($data);
    }
    public function modal_create_category()
    {
        $data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');
        $cat_type = Request::input("type");
        $access = Utilities::checkAccess('item-categories', 'access_page');
        if($access == 1)
        {
            $cat[0] = "inventory";
            $cat[1] = "non-inventory";
            $cat[2] = "services";
            $cat[3] = "bundles";

            $data["cat"] = $cat;

            $data["selected_category"] = $cat_type;
            $shop_id = $this->user_info->user_shop;
            $data['_category'] = Category::breakdown($shop_id);
            // $data['_level_category'] = $this->recursive_select_category($shop_id);
            // dd($data['_category']);
            // $data['_category'] = Tbl_category::where('type_shop', $shop_id)->where('archived',0)->get();
            $data['equipment_category'] = AccountingTransaction::settings($shop_id, "equipment_category");
            return view('member.modal.create_category_modal', $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function create_category()
    {
        $type_name = Request::input('type_name');
        $type_category = Request::input('type_category');

        $type_parent_id = 0;
        $type_sub_level = 0;

        if(Request::has('is_sub_category')){
            $type_parent_id = Request::input('hidden_parent_category');
            $type_sub_level = Tbl_category::where('type_id', $type_parent_id)->value('type_sub_level');
            $type_sub_level++;
        }   

        $shop_id = $this->user_info->user_shop;

        $insert['type_name']                = $type_name;
        $insert['type_category']            = $type_category;
        $insert['type_parent_id']           = $type_parent_id;
        $insert['type_shop']                = $shop_id;
        $insert['equipment_type_category']  = Request::input("equipment_category") == null ? 0 : Request::input("equipment_category");
        $insert['type_sub_level']           = $type_sub_level;
        $insert['type_date_created']        = Carbon::now();
        $insert['type_image']               = Request::input('type_image');

        //dd($insert);
        $category_id = Tbl_category::insertGetId($insert);

        $data["status"] = "success-category";
        $data["type"] = "category";
        $data["cat_type"] = $type_category;
        $data["id"] = $category_id;

        $cat_data = AuditTrail::get_table_data("tbl_category","type_id",$category_id);
        AuditTrail::record_logs("Added","category",$category_id,"",serialize($cat_data));

        return json_encode($data);
    }


    public function edit_category($id)
    {
        $access = Utilities::checkAccess('item-categories', 'access_page');
        if($access == 1)
        {
            $shop_id = $this->user_info->user_shop;
            $category = Tbl_category::where('type_id',$id)->first();
            $check = '';
            if($category->type_parent_id != 0){
                $check = 'checked="checked"';
            }
            $data['check'] = $check;
            $data['category'] = $category;
            $data['parent'] = Tbl_category::where('type_id',$category->type_parent_id)->first();
            $data['_category'] = Category::breakdown($shop_id);
            $data['equipment_category'] = AccountingTransaction::settings($shop_id, "equipment_category");
            return view('member.modal.edit_category_modal', $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function update_category()
    {
        $access = Utilities::checkAccess('item-categories', 'access_page');
        if($access == 1)
        {    
            $type_id = Request::input('type_id');

            $old_data = AuditTrail::get_table_data("tbl_category","type_id",$type_id);

            $type_category = Request::input('type_category');
            $type_name = Request::input('type_name');
            $type_parent_id = 0;
            $parent_data = null;

            if(Request::input('is_sub_category'))
            {
                $type_parent_id = Request::input('hidden_parent_category');
                $parent_data = Tbl_category::where("type_id",$type_parent_id)->first();
                $type_category = $parent_data->type_category;
            }

            $category_data = Tbl_category::where("type_parent_id",$type_id)->get();
            if(count($category_data) > 0)
            {
                foreach ($category_data as $key => $value) 
                {
                    $up_child["type_category"] = $type_category;
                    Tbl_category::where('type_id', $value->type_id)->update($up_child);
                }
            }
            
            $update['type_name']                = $type_name;
            $update['type_category']            = $type_category;
            $update['type_parent_id']           = $type_parent_id;
            $update['type_image']               = Request::input("type_image");
            $update['equipment_type_category']  = Request::input("equipment_category") == null ? 0 : Request::input("equipment_category");
            Tbl_category::where('type_id', $type_id)->update($update);

            $data["status"] = "success-category";
            $data["id"] = $type_id;

            $new_data = AuditTrail::get_table_data("tbl_category","type_id",$type_id);
            AuditTrail::record_logs("Edited","category",$type_id,serialize($old_data),serialize($new_data));

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }

    public function recursive_select_category($shop_id = 0, $parent = 0)
    {
        $data = array();
        $_category = Tbl_category::where('type_shop', $shop_id)->where('type_parent_id', $parent)->where('archived',0)->get();
        foreach($_category as $key => $category)
        {
            $data[$key] = $category;
            $count =  Tbl_category::where('type_shop', $shop_id)->where('type_parent_id', $category->type_parent_id)->where('archived',0)->count();
            
            if($count != 0)
            {
                // dd($category->type_parent_id);
                $data[$key]['sub'] = $this->recursive_select_category($shop_id, $category->type_id);
            }
        }
        return $data;
    }

    public function search_category()
    {
        $search = Request::input('search');
        $type_category = Request::input("type_category");
        $shop_id = $this->user_info->user_shop;
        return json_encode(Category::search_category($search, $type_category, $shop_id));
    }

}
