<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;

use App\Http\Controllers\Member\Member;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_variant;
use App\Models\Tbl_membership_package_has;
use App\Models\Tbl_membership;
use App\Models\Tbl_product;
use App\Models\Tbl_item;
use App\Models\Tbl_price_level;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\Item;
use App\Globals\Settings;
use App\Globals\Ecom_Product;
use DB;
class MLM_MembershipController extends Member
{
    public function index()
    {
        $access = Utilities::checkAccess('mlm-membership', 'access_page');
        if($access == 1)
        {
            $data["page"] = "Membership";
            // GET MEMBERSHIP
            $shop_id = $this->user_info->shop_id;
            $data['membership_active'] = Tbl_membership::getactive(0, $shop_id)->get();
            $data['membership_archive'] = Tbl_membership::getactive(1, $shop_id)->get();
            // END GET
            return view('member.mlm_membership.mlm_membership_list', $data);
        }
        else
        {
            return $this->show_no_access();
        }
        
    }
    public static function get_membeship($archive = 0)
    {
        $membership = Tbl_membership::archive($archive)
        ->leftjoin('tbl_membership_package', 'tbl_membership_package.membership_id', '=', 'tbl_membership.membership_id')
        ->groupBy('tbl_membership.membership_id')
        ->selectRaw('tbl_membership.*,
        COUNT(CASE WHEN tbl_membership_package.membership_package_archive = 0 THEN 1 END) package_count, 
        COUNT(CASE WHEN tbl_membership_package.membership_package_archive = 1  THEN 1 END) package_count_archive')
        ->where('tbl_membership.shop_id', $this->user_info->shop_id)
        ->orderby('membership_price', 'ASC')->get();
        return $membership;
    }
    public function add()
    {
        $access = Utilities::checkAccess('mlm-membership', 'create_new_membership');
        if($access == 1)
        {
            $data['_price_level'] = Tbl_price_level::where('shop_id', $this->user_info->shop_id)->get();
            return view('member.mlm_membership.mlm_membership_add',$data);
        }
        else
        {
           return $this->show_no_access(); 
        }
    }
    public function save()
    {
        $data['user_info']= $this->user_info;
        $validate['shop_id'] = $data['user_info']->shop_id;
        $validate['membership_name'] = Request::input('membership_name');
        $validate['membership_price'] = Request::input('membership_price');

        $rules['membership_name'] = 'required';
    	$rules['membership_price'] = 'required|integer|min:1';
    	$rules['shop_id'] = 'required';
    	$validator = Validator::make($validate,$rules);
    	if ($validator->passes())
    	{
            $insert['membership_price_level'] = Request::input('membership_price_level');
    	    $insert['membership_name'] = Request::input('membership_name');
    	    $insert['membership_price'] = Request::input('membership_price');
    	    $insert['shop_id']= $data['user_info']->shop_id;
    	    $insert['membership_archive'] = 0;
    	    $insert['membership_date_created']= Carbon::now();
    	    $mem_id = Tbl_membership::insertGetId($insert);
    	    Session::flash('success', "Membership Saved");

            $membership_data = Tbl_membership::where("membership_id",$mem_id)->first()->toArray();
            AuditTrail::record_logs("Added","mlm_membership",$mem_id,"",serialize($membership_data));

    	    return redirect('member/mlm/membership')->send();
    	}
    	else
    	{
    	   return redirect('member/mlm/membership/add')->withErrors($validator)->withInput();  
    	}
    }
    public function edit($membership_id)
    {
        $access = Utilities::checkAccess('mlm-membership', 'delete_membership');
        if($access == 1)
        {
            if(Request::input('membership_id'))
            {
                return $this->edit_save($_POST); 
            }
            $shop_id = $this->user_info->shop_id;
            $data["page"] = "Membership Edit";
            $data['_price_level'] = Tbl_price_level::where('shop_id', $this->user_info->shop_id)->get();
            $data['membership'] = Tbl_membership::id($membership_id)->first();
            $data['membership_packages'] = Tbl_membership_package::where('membership_id', $membership_id)->where('membership_package_archive', 0)->get();
            $data['packages_view']= $this->get_packages_with_view($membership_id);

            $get_settings = Settings::get_settings_php('use_product_as_membership');
            if($get_settings['response_status'] == 'success')
            {
                $data['use_product_as_membership'] = $get_settings['settings_value'];
                if( $get_settings['settings_value'] == 1)
                {
                    $data['product_list'] = Ecom_Product::getProductList();
                    $data['membership_product'] = Tbl_membership::where('shop_id', $shop_id)
                    ->where('membership_archive', 0)->get();

                    $data['ec_product'] = DB::table('tbl_ec_product')->where('eprod_shop_id', $shop_id)->where('archived', 0)->get();
                }
            }
            else
            {
                $data['use_product_as_membership'] = 0;
            }

            return view('member.mlm_membership.mlm_membership_edit', $data);
        }
        else
        {
           return $this->show_no_access(); 
        }
    }
    public function edit_add_membership_product()
    {

        $eprod_id = Request::input('eprod_id');
        $membership_id = Request::input('membership_id');

        foreach ($eprod_id as $key => $value) {
            $update['ec_product_membership'] = $membership_id[$key];
            DB::table('tbl_ec_product')->where('eprod_id', $eprod_id[$key])->update($update);
        }

        $data['response_status'] = 'success';
        $data['message'] = 'ok';

        return json_encode($data);
    }
    public function get_packages_with_view($membership_id)
    {
        $data['membership_packages'] = Tbl_membership_package::where('membership_id', $membership_id)->where('membership_package_archive', 0)->get();
        
        foreach($data['membership_packages'] as $key => $value)
        {
            $data['product_count'][$key] = Tbl_membership_package_has::where('membership_package_id', $value->membership_package_id)->get();
            $data['item_bundle'][$key] = Tbl_membership_package_has::where('membership_package_id', $value->membership_package_id)->get();
            $data['item_list'] = [];
            foreach($data['item_bundle'][$key] as $key2 => $value2)
            {
                $data['item_bundle'][$key][$key2]->item_list = Item::get_item_bundle($value2->item_id);
            }
        }
        return view('member.mlm_membership.viewpackages.view_pacakges', $data);
    }
    public function popup()
    {
        $data["page"] = "Popup";
        $data["jimar"] = "zape";
        $data["response_status"] = "success";
        $data["response_status"] = "error";
        $data["message"] = "There is a problem with your face.";
        echo json_encode($data);
    }
    public function edit_save($input)
    {
        $old_membership_data = Tbl_membership::where("membership_id",$input['membership_id'])->first()->toArray();

        $validate['membership_name'] = $input['membership_name'];
        $validate['membership_price'] = $input['membership_price'];
        $rules['membership_name'] = 'required';
    	$rules['membership_price'] = 'required|integer|min:1';
    	
    	$validator = Validator::make($validate,$rules);
    	if ($validator->passes())
    	{
    	    $update['membership_name'] = $input['membership_name'];
    	    $update['membership_price'] = $input['membership_price'];
            $update['membership_price_level'] = $input['membership_price_level'];
    	    Tbl_membership::id($input['membership_id'])->update($update);

            $new_membership_data = Tbl_membership::where("membership_id",$input['membership_id'])->first()->toArray();
            AuditTrail::record_logs("Edited","mlm_membership",$input['membership_id'],serialize($old_membership_data),serialize($new_membership_data));
            
    	    Session::flash('success', "Membership Saved");

    	    return redirect::back();
    	}
    	else
    	{
    	 return redirect::back()->withErrors($validator)->withInput();  
    	}
    }
    public function delete($membership_id)
    {

        $access = Utilities::checkAccess('mlm-membership', 'delete_membership');
        if($access == 1)
        {
            $update['membership_archive'] = 1;
            $updata_old = Tbl_membership::id($membership_id)->first();
            if($updata_old)
            {
                Tbl_membership::id($membership_id)->update($update);

                $membership_data = Tbl_membership::where("membership_id",$membership_id)->first()->toArray();
                AuditTrail::record_logs("archived","mlm_membership",$membership_id,"",serialize($membership_data));

                Session::flash('success', "Membership Deleted");
                return redirect('member/mlm/membership')->send();
            }
            else
            {
                Session::flash('warning', "Membership Does Not Exist");
                return redirect('member/mlm/membership')->send();
            }
        }
        else
        {
           return $this->show_no_access(); 
        }
    }
    public function restore($membership_id)
    {
        $update['membership_archive'] = 0;
        $updata_old = Tbl_membership::id($membership_id)->first();

        if($updata_old)
        {
            Tbl_membership::id($membership_id)->update($update);

            $membership_data = Tbl_membership::where("membership_id",$membership_id)->first()->toArray();
            AuditTrail::record_logs("restore","mlm_membership",$membership_id,"",serialize($membership_data));

            Session::flash('success', "Membership Restored");
    	    return redirect('member/mlm/membership')->send();
        }
        else
        {
            Session::flash('warning', "Membership Does Not Exist");
    	    return redirect('member/mlm/membership')->send();
        }
    }
    public function add_package($membership_id)
    {
        
        $data['package_name']  = $this->package_name_setter($membership_id);
        $data['membership_id'] = $membership_id;
        $data['products']      = Tbl_product::variant()->where('product_shop', $this->user_info->shop_id)->get();
        $data['items']         = Tbl_item::where('shop_id', $this->user_info->shop_id)->where('item_type_id', 4)->where('archived', 0)->get();

        return view('member.mlm_membership.mlm_membership_popup_add_package', $data);
    }
    public function save_package()
    {
        $data['response_status']                  = "success";
        $insert['membership_id']                  = Request::input('membership_id');
        $insert['membership_package_name']        = Request::input('membership_package_name');
        $insert['membership_package_archive']     = 0;
        $insert['membership_package_created']     = Carbon::now();
        $insert['membership_package_is_gc'] = Request::input('membership_package_is_gc');
        // $insert['membership_package_weight']      = Request::input('membership_package_weight');
        // $insert['membership_package_weight_unit'] = Request::input('membership_package_weight_unit');
        // $insert['membership_package_size_w']      = Request::input('membership_package_size_w');
        // $insert['membership_package_size_h']      = Request::input('membership_package_size_h');
        // $insert['membership_package_size_l']      = Request::input('membership_package_size_l');
        // $insert['membership_package_size_unit']   = Request::input('membership_package_size_unit');
        
        $rules['membership_id']                   = 'required';
        $rules['membership_package_name']         = 'required';
        // $rules['membership_package_weight']       = 'required|integer|min:1';
        // $rules['membership_package_weight_unit']  = 'required';
        // $rules['membership_package_size_w']       = 'required|integer|min:1';
        // $rules['membership_package_size_h']       = 'required|integer|min:1';
        // $rules['membership_package_size_l']       = 'required|integer|min:1';
        // $rules['membership_package_size_unit']    = 'required';
        
    	$validator = Validator::make($insert,$rules);
    	if ($validator->passes())
    	{
            $item                  = Request::input('item_id');
            $variant               = Request::input('variant_id');
            $quantity              = Request::input('quantity');
            if($insert['membership_package_is_gc'] == 1)
            {
                $insert['membership_package_gc_amount'] = Request::input('membership_package_gc_amount');
                $membership_package_id = Tbl_membership_package::insertGetId($insert);
            }
            else
            {
                $membership_package_id = Tbl_membership_package::insertGetId($insert);
                if(isset($item))
                {
                    foreach($item as $key => $v)
                    {
                        if(isset($combined_quantity[$v]))
                        {
                            $combined_quantity[$v] = $combined_quantity[$v] + $quantity[$key];
                        }
                        else
                        {
                            $combined_quantity[$v] = $quantity[$key];
                        }
                    }

                    foreach($item as $key => $v)
                    {
                        $c = Tbl_membership_package_has::where('item_id', $v)->where('membership_package_id', $membership_package_id)->count();
                        if($c >= 1)
                        {
                            $update2['membership_package_has_quantity']     = $combined_quantity[$v];
                            Tbl_membership_package_has::where('item_id', $v)->where('membership_package_id', $membership_package_id)->update($update2);
                        }
                        else
                        {
                            // $varriant_array = Tbl_variant::where('variant_id', $variant_id)->variant_info()->first();
                            $arry_insert['variant_id']                      = 0;
                            $arry_insert['product_id']                      = 0;
                            $arry_insert['item_id']                         = $v;
                            $arry_insert['membership_package_has_archive']  = 0;
                            $arry_insert['membership_package_has_quantity'] = $combined_quantity[$v];
                            $arry_insert['membership_package_id']           = $membership_package_id;
                            Tbl_membership_package_has::insert($arry_insert); 
                        }
                    }
                    $data['response_status'] = "success";
                }


            $membership_package_data = Tbl_membership_package::membership()->where("membership_package_id",$membership_package_id)->first()->toArray();
            AuditTrail::record_logs("added","mlm_membership_package",$membership_package_id,"",serialize($membership_package_data));
            }

            
    	}
    	else
    	{
    	    $data['response_status'] = "warning";
    	    $data['warning_validator'] = $validator->messages();
    	}
    	echo json_encode($data);
    }
    public static function package_name_setter($membership_id)
    {
        $c = Tbl_membership_package::where('membership_id', $membership_id)->where('membership_package_archive', 0)->count();
        $membership = Tbl_membership::where('membership_id', $membership_id)->first();
        $count_of_zero = 3;
        $n2 = str_pad($c + 1, $count_of_zero, 0, STR_PAD_LEFT);

        return $membership->membership_name . " (Set " . $n2 . ")";
    }
    public function edit_package($membership_id, $membership_package_id)
    {
        $data['membership'] = Tbl_membership::id($membership_id)->first();
        $data['membersip_package'] = Tbl_membership_package::where('membership_package_id', $membership_package_id)->first();
        
        $data['product_list'] = $this->view_product(0);

        return view('member.mlm_membership.mlm_membership_add_package', $data);
    }
    public function view_product($category = 0)
    {
        $data['user_info']= $this->user_info;
        $shop_id = $data['user_info']->shop_id;
        $data['product_sel']= Tbl_product::variant()->where('product_shop', $shop_id)->where('tbl_product.archived', 0)->get();
        $data['category'] = "all";

        if($data['category'] == "all")
        {
            $data["_product"]	= Tbl_product::where("image_shop", $this->user_info->shop_id)->info()->get();
        }
        
        return view('member.mlm_membership.viewproducts.viewproducts', $data);
    }
    public function show_product()
    {
        $data['user_info']= $this->user_info;
        $shop_id = $data['user_info']->shop_id;
        $data['product_sel']= Tbl_product::variant()->where('product_shop', $shop_id)->where('tbl_product.archived', 0)->get();
    }
    public function edit_package_popup($membership_package_id)
    {
        $data['membership_packages'] = Tbl_membership_package::where('membership_package_id', $membership_package_id)->where('membership_package_archive', 0)->first();

        
        if($data['membership_packages'])
        {
            $data['package_name']          = $data['membership_packages']->membership_package_name;
            $data['membership_package_id'] = $data['membership_packages']->membership_package_id;
            $data['products']              = Tbl_product::variant()->where('product_shop', $this->user_info->shop_id)->get();
            $data['items']                 = Tbl_item::where('shop_id', $this->user_info->shop_id)->where('archived', 0)->where('item_type_id', 4)->get();
            $data['membership_id']         = $data['membership_packages']->membership_id;
            $data['item_count']            = Tbl_membership_package_has::where('membership_package_id', $data['membership_packages']->membership_package_id)->pluck("membership_package_has_quantity","item_id");
        }
        return view('member.mlm_membership.mlm_membership_popup_edit_package', $data);
    }
    public function edit_package_popup_archive($membership_package_id)
    {
        $update['membership_package_archive'] = 1;
        Tbl_membership_package::where('membership_package_id', $membership_package_id)->update($update);
        return redirect::back();
    }
    public function save_package_popup()
    {
        $data['response_status']                      = "success";
        $insert['membership_id']                      = Request::input('membership_id');
        $insert['membership_package_name']            = Request::input('membership_package_name');
        $insert['membership_package_archive']         = 0;
        $insert['membership_package_created']         = Carbon::now();
        $insert['membership_package_is_gc'] = Request::input('membership_package_is_gc');


        // $insert['membership_package_weight']          = Request::input('membership_package_weight');
        // $insert['membership_package_weight_unit']     = Request::input('membership_package_weight_unit');
        // $insert['membership_package_size_w']          = Request::input('membership_package_size_w');
        // $insert['membership_package_size_h']          = Request::input('membership_package_size_h');
        // $insert['membership_package_size_l']          = Request::input('membership_package_size_l');
        // $insert['membership_package_size_unit']       = Request::input('membership_package_size_unit');
            
        $rules['membership_id']                       = 'required';
        $rules['membership_package_name']             = 'required';
        // $rules['membership_package_weight']           = 'required|integer|min:1';
        // $rules['membership_package_weight_unit']      = 'required';
        // $rules['membership_package_size_w']           = 'required|integer|min:1';
        // $rules['membership_package_size_h']           = 'required|integer|min:1';
        // $rules['membership_package_size_l']           = 'required|integer|min:1';
        // $rules['membership_package_size_unit']        = 'required';
    	$validator = Validator::make($insert,$rules);

    	if ($validator->passes())
    	{
            // $update['membership_package_weight']      = Request::input('membership_package_weight');
            // $update['membership_package_weight_unit'] = Request::input('membership_package_weight_unit');
            // $update['membership_package_size_w']      = Request::input('membership_package_size_w');
            // $update['membership_package_size_h']      = Request::input('membership_package_size_h');
            // $update['membership_package_size_l']      = Request::input('membership_package_size_l');
            // $update['membership_package_size_unit']   = Request::input('membership_package_size_unit');
            $membership_package_id                    = Request::input('membership_package_id');

            $old_membership_package_data = Tbl_membership_package::membership()->where("membership_package_id",$membership_package_id)->first()->toArray();

            if($insert['membership_package_is_gc'] == 1)
            {
                $update['membership_package_is_gc'] = $insert['membership_package_is_gc'];
                $update['membership_package_gc_amount'] = Request::input('membership_package_gc_amount');
                $membership_package_i_a = Tbl_membership_package::where('membership_package_id', $membership_package_id)->update($update);
            }
            else
            {
                $update['membership_package_is_gc'] = $insert['membership_package_is_gc'];
                $membership_package_i_a = Tbl_membership_package::where('membership_package_id', $membership_package_id)->update($update);
                // Tbl_membership_package::where('membership_package_id', $membership_package_id)->update($update);


                $variant                                  = Request::input('variant_id');
                $item                                     = Request::input('item_id');
                $quantity                                 = Request::input('quantity');

                if(isset($item))
                {
                    foreach($item as $key => $v)
                    {
                        if(isset($combined_quantity[$v]))
                        {
                            $combined_quantity[$v] = $combined_quantity[$v] + $quantity[$key];
                        }
                        else
                        {
                            $combined_quantity[$v] = $quantity[$key];
                        }
                    }

                    foreach($item as $key => $v)
                    {
                        $c = Tbl_membership_package_has::where('item_id', $v)->where('membership_package_id', $membership_package_id)->count();
                        if($c >= 1)
                        {
                            $update2['membership_package_has_quantity']     = $combined_quantity[$v];
                            Tbl_membership_package_has::where('item_id', $v)->where('membership_package_id', $membership_package_id)->update($update2);
                        }
                        else
                        {
                            $arry_insert['variant_id']                      = 0;
                            $arry_insert['product_id']                      = 0;
                            $arry_insert['item_id']                         = $v;
                            $arry_insert['membership_package_has_archive']  = 0;
                            $arry_insert['membership_package_has_quantity'] = $combined_quantity[$v];
                            $arry_insert['membership_package_id']           = $membership_package_id;
                            Tbl_membership_package_has::insert($arry_insert); 
                        }
                    }
                    $data['response_status'] = "success";
                }
            }


            $new_membership_package_data = Tbl_membership_package::membership()->where("membership_package_id",$membership_package_id)->first()->toArray();
            
            AuditTrail::record_logs("edited","mlm_membership_package",$membership_package_id,serialize($old_membership_package_data),serialize($new_membership_package_data));
    	}
    	else
    	{
    	    $data['response_status'] = "warning";
    	    $data['warning_validator'] = $validator->messages();
    	}
    	echo json_encode($data); 
    }
    public function package_archive($membership_package_id)
    {
        $data['membership_packages'] = Tbl_membership_package::where('membership_package_id', $membership_package_id)->where('membership_package_archive', 0)->first();

        return view('member.mlm_package.mlm_membership_package_archive', $data);
    }
    public function package_archive_submit()
    {
        $data['response_status'] = "success";
        $input['membership_package_id'] = Request::input('membership_package_id');
        $rules['membership_package_id'] = 'Required';
        $membership_package_id = Request::input('membership_package_id');
        $validator = Validator::make($input,$rules);

        if ($validator->passes())
    	{
    	    $update['membership_package_archive'] = 1;
    	    Tbl_membership_package::where('membership_package_id', $input['membership_package_id'])->update($update);

        $membership_package_data = Tbl_membership_package::membership()->where("membership_package_id",$membership_package_id)->first()->toArray();
        
        AuditTrail::record_logs("archived","mlm_membership_package",$membership_package_id,"",serialize($membership_package_data));

    	    $data['response_status'] = "successd";

    	}
    	else
    	{
    	    $data['response_status'] = "warning";
    	    $data['warning_validator'] = $validator->messages();
    	}
        
        echo json_encode($data); 
    }
    public function change_picture_package()
    {
        $update['membership_package_imgage'] = $_POST['membership_package_imgage'];
        Tbl_membership_package::where('membership_package_id', $_POST['membership_package_id'])->update($update);

        $data['message'] = 'success';
        $data['status'] = 'Success';
        return json_encode($data);
    }
    
}