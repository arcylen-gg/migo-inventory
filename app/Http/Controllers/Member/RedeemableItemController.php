<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Tbl_item_redeemable;
use Illuminate\Http\Request;
use App\Models\Tbl_item;

use Validator;

class RedeemableItemController extends Member
{
    public function index()
    {
        $data["page"] = "Redeemable Item";
        $data["row"] = Tbl_item_redeemable::get();

        return view("member.redeemable.redeemable", $data);
    }
    public function add()
    {
        $data['page'] = "ADD NEW REDEEMABLE";
        return view('member.redeemable.redeemable_add',$data);
    }
    public function submit_add(Request $request)
    {
        $data['item_name'] = $request->item_name;
        $data['item_description'] = $request->item_sales_information;
        $data['redeemable_points'] = $request->redeemable_points;
        $data['quantity'] = $request->quantity;

        $rules['item_name'] = 'required';
        $rules['item_description'] = 'required';
        $rules['redeemable_points'] = 'required';
        $rules['quantity'] = 'required';

        $validator = Validator::make($data,$rules);

        $response['status_message'] = "";

        if($validator->fails())
        {
            $response["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $response["status_message"] .= $message;
            }
        }
        else
        {

        // $insert['item_name'] = $request->item_name;
        // $insert['item_sales_information'] = $request->item_sales_information;
        // $insert['shop_id'] = $this->user_info->shop_id;

        // $item_id = Tbl_item::insertGetId($insert);

        $insertRedeem['redeemable_points'] = $request->redeemable_points;
        $insertRedeem['item_id'] = 0;
        // $insertRedeem['item_id'] = $item_id;
        $insertRedeem['item_name'] = $request->item_name;
        $insertRedeem['quantity'] = $request->quantity;
        $insertRedeem['item_description'] = $request->item_sales_information;
        $insertRedeem['shop_id'] = $this->user_info->shop_id;
        $insertRedeem['image_path'] = $request->image_path;
        $insertRedeem['date_created'] = now();

        Tbl_item_redeemable::insert($insertRedeem);

        $response["status"] = "success";
        $response["call_function"] = "success_project_create";
        }

        return json_encode($response);
    }
    public function table()
    {
        $data["activetab"] = request("activetab");
        $query = Tbl_item_redeemable::where('shop_id',$this->user_info->shop_id)
                                    ->where("archived",request("activetab"));

        if(request("search")!="")
        {
            $query->where("item_name","LIKE","%".request("search")."%");
        }

        $data["_redeemable"] = $query->get();
        return view("member.redeemable.redeemable_table", $data);
    }
    public function archive()
    {
        if(request("action")=='archive')
        {
            $update['archived'] = 1;
        }
        else
        {
            $update['archived'] = 0;
        }
        Tbl_item_redeemable::where("item_redeemable_id",request("item_redeemable_id"))->update($update);
    }
    public function modify()
    {
        $data['page'] = "EDIT REDEEMABLE";
        $data['row'] = Tbl_item_redeemable::where("item_redeemable_id",request("item_redeemable_id"))->get();
        return view('member.redeemable.redeemable_add',$data);
    }
    public function submit_modify(Request $request)
    {
        $data['item_name'] = $request->item_name;
        $data['item_description'] = $request->item_sales_information;
        $data['redeemable_points'] = $request->redeemable_points;
        $data['quantity'] = $request->quantity;

        $rules['item_name'] = 'required';
        $rules['item_description'] = 'required';
        $rules['redeemable_points'] = 'required';
        $rules['quantity'] = 'required';

        $validator = Validator::make($data,$rules);

        $response['status_message'] = "";

        if($validator->fails())
        {
            $response["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $response["status_message"] .= $message;
            }
        }
        else
        {
            $update['redeemable_points'] = $request->redeemable_points;
            $update['item_name'] = $request->item_name;
            $update['quantity'] = $request->quantity;
            $update['item_description'] = $request->item_sales_information;
            $update['image_path'] = $request->image_path;
            Tbl_item_redeemable::where('item_redeemable_id',request("item_redeemable_id"))->update($update);

            $response["status"] = "success";
            $response["call_function"] = "success_project_create";
        }
        return json_encode($response);
    }

}