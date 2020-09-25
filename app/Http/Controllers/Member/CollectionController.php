<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_collection;
use App\Models\Tbl_collection_item;
use App\Models\Tbl_ec_product;
use App\Globals\Ecom_Product;
use Validator;
use Carbon\Carbon;

class CollectionController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $data["action"] = "/member/ecommerce/product/collection/add_submit";
        $data["_ec_product"] = Tbl_ec_product::where("archived",0)->where("eprod_shop_id",Ecom_Product::getShopId())->get();
        $id = Request::input("id");
        if($id)
        {
           $data["collection_id"] = $id;
           $data["action"] = "/member/ecommerce/product/collection/edit_submit";
           $data["collection"] = Tbl_collection::where("collection_id",$id)->first();
           $data["collection_item"] = Tbl_collection_item::where("collection_id",$id)->get();
        }

        return view("member.ecommerce_collection.ecommerce_collection",$data);   
    }
    public function collection_modal()
    {
        $data["action"] = "/member/ecommerce/product/collection/add_submit";
        $data["_ec_product"] = Tbl_ec_product::where("archived",0)->where("eprod_shop_id",Ecom_Product::getShopId())->get();

        $id = Request::input("id");
        if($id)
        {
           $data["action"] = "/member/ecommerce/product/collection/edit_submit";
           $data["collection"] = Tbl_collection::where("collection_id",$id)->first();
           $data["collection_item"] = Tbl_collection_item::where("collection_id",$id)->get();
        }

        return view("member.ecommerce_collection.ecommerce_collection_modal",$data);  
    }
    public function collection_list()
    {
        $data["_collection"] = Tbl_collection::where("archived",0)->where("shop_id",Ecom_Product::getShopId())->get();
        $data["_collection_archived"] = Tbl_collection::where("archived",1)->where("shop_id",Ecom_Product::getShopId())->get();
        return view("member.ecommerce_collection.ecommerce_collection_list",$data);
    }
    public function archived($id, $action)
    {
        $data["collection"] = Tbl_collection::where("collection_id",$id)->first();
        $data["action"] = $action;

        return view("member.ecommerce_collection.confirm_ecommerce_collection",$data);
    }
    public function archived_submit()
    {         
        $id = Request::input("collection_id");
        $action = Request::input("action");

        $data["status"] = "";

        $update["collection_status"] = 1;
        $update["archived"] = 0;
        if($action == "archived")
        {
            $update["archived"] = 1;
            $update["collection_status"] = 0;
        }

        Tbl_collection::where("collection_id",$id)->update($update);
        $data["status"] = "success-archived";

        return json_encode($data);
    }
    public function add_submit()
    {            
        $data["status"] = "";
        $data["status_message"] = "";

        $collection_name = Request::input("collection_name");
        $collection_description = Request::input("collection_description");
        
        $collection_item_id = Request::input("collection_item_id");

        $insert["shop_id"] = $this->user_info->shop_id;
        $insert["collection_name"] = $collection_name;
        $insert["collection_description"] = $collection_description;
        $insert["date_created"] = Carbon::now();
        $insert["collection_status"] = 1;

        $rules["collection_name"] = "required";
        $rules["collection_description"] = "required";

        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        else
        {
            $ctr = 0;
            foreach ($collection_item_id as $key => $value) 
            {
                if($value != null)
                {
                    $ctr++;
                }
            }
            if($ctr == 0)
            {
                $data["status"] = "error";
                $data["status_message"] = "Please enter Product.";
            }
        }
        if($data["status"] == "")
        {
            $collection_id = Tbl_collection::insertGetId($insert);

            foreach ($collection_item_id as $key => $value) 
            {
                if($value != null)
                {                    
                    $ctr = Tbl_collection_item::where("collection_id",$collection_id)->where("ec_product_id",$value)->count();
                    if($ctr == 0)
                    {
                        $insert_item["collection_id"] = $collection_id;
                        $insert_item["ec_product_id"] = $value;
                        $insert_item["date_created"] = Carbon::now();

                        Tbl_collection_item::insert($insert_item);                        
                    }
                }
            }

            $data["status"] = "success";
            $data["redirect_to"] = "/member/ecommerce/product/collection/list";
        }

        return json_encode($data);
    }

    public function edit_submit()
    {            
        $data["status"] = "";
        $data["status_message"] = "";

        $id = Request::input("collection_id");

        $collection_name = Request::input("collection_name");
        $collection_description = Request::input("collection_description");
        
        $collection_item_id = Request::input("collection_item_id");

        $update["shop_id"] = $this->user_info->shop_id;
        $update["collection_name"] = $collection_name;
        $update["collection_description"] = $collection_description;
        $update["date_created"] = Carbon::now();
        $update["collection_status"] = 1;

        $rules["collection_name"] = "required";
        $rules["collection_description"] = "required";

        $validator = Validator::make($update, $rules);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        else
        {            
            $ctr = 0;
            foreach ($collection_item_id as $key => $value) 
            {
                if($value != null)
                {
                    $ctr++;
                }
            }
            if($ctr == 0)
            {
                $data["status"] = "error";
                $data["status_message"] = "Please enter Product.";
            }
        }
        if($data["status"] == "")
        {
            Tbl_collection::where("collection_id",$id)->update($update);

            Tbl_collection_item::where("collection_id",$id)->delete();
            foreach ($collection_item_id as $key => $value) 
            {
                if($value != "")
                {                    
                    $ctr = Tbl_collection_item::where("collection_id",$id)->where("ec_product_id",$value)->count();
                    if($ctr == 0)
                    {
                        $insert_item["collection_id"] = $id;
                        $insert_item["ec_product_id"] = $value;
                        $insert_item["date_created"] = Carbon::now();

                        Tbl_collection_item::insert($insert_item);                        
                    }
                }
            }

            $data["status"] = "success";
            $data["redirect_to"] = "/member/ecommerce/product/collection/list";
        }

        return json_encode($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_active()
    {
        $id = Request::input("id");

        $data["status"] = "";

        $data_collection = Tbl_collection::where("collection_id",$id)->first();

        $update["collection_status"] = 1;
        if($data_collection->collection_status == 1)
        {
            $update["collection_status"] = 0;
        }

        Tbl_collection::where("collection_id",$id)->update($update);

        $data["status"] = "success";

        return json_encode($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
