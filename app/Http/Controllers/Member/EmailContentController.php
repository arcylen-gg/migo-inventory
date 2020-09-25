<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;

use App\Models\Tbl_email_content;
use Carbon\Carbon;
use Mail;
use Validator;
use App\Globals\EmailContent;
use Redirect;
class EmailContentController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["_email_content"] = Tbl_email_content::where("archived",0)->where("shop_id",$this->user_info->shop_id)->get();
        $data["_email_content_archived"] = Tbl_email_content::where("archived",1)->where("shop_id",$this->user_info->shop_id)->get();

        return view("member.maintenance.email_content.email_content_list",$data);
    }
    public function add()
    {
        $data["action"] = "/member/maintenance/email_content/add_submit";

        $id = Request::input("id");
        if($id)
        {            
            $data["action"] = "/member/maintenance/email_content/edit_submit";
            $data["email_content"] = Tbl_email_content::where("email_content_id",$id)->first(); 
        }

        return view("member.maintenance.email_content.email_content",$data);
    }
    public function add_submit()
    {
        $shop_id = $this->user_info->shop_id;
        $data["status"] = "";
        $data["status_message"] = "";

        $insert["email_content_key"] = Request::input("email_content_key");
        $insert["email_content_subject"] = Request::input("email_content_subject");
        $insert["email_content"] = Request::input("email_content");
        $insert["shop_id"] = $shop_id;
        $insert["date_created"] = Carbon::now();

        $rules["email_content_key"] = "required|alpha_dash|unique:tbl_email_content,email_content_key,".$this->user_info->shop_id.",shop_id";
        $rules["email_content"] = "required";
        $validator = Validator::make($insert, $rules);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }

        if($data["status"] == "")
        {
            $data["status"] = "success";
            Tbl_email_content::insert($insert);
        }

        return json_encode($data);
    }
    public function edit_submit()
    {
        $shop_id = $this->user_info->shop_id;
        $data["status"] = "";
        $data["status_message"] = "";

        $id = Request::input("email_content_id");

        $update["email_content_key"] = Request::input("email_content_key");
        $update["email_content_subject"] = Request::input("email_content_subject");
        $update["email_content"] = Request::input("email_content");
        $update["date_updated"] = Carbon::now();

        $rules["email_content_key"] = "required|alpha_dash|unique:tbl_email_content,shop_id,".$this->user_info->shop_id.",email_content_key";
        $rules["email_content"] = "required";

        $validator = Validator::make($update, $rules);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }

        if($data["status"] == "")
        {
            $data["status"] = "success";
            Tbl_email_content::where("email_content_id",$id)->update($update);
        }

        return json_encode($data);
    }
    public function archived($id, $action)
    {
        $data["email_content_id"] = $id;
        $data["email_content"] = Tbl_email_content::where("email_content_id",$id)->first();
        $data["action"] = $action;

        return view("member.maintenance.email_content.confirm_email_content",$data);
    }
    public function archived_submit()
    {
        $id = Request::input("email_content_id");
        $action = Request::input("action");

        $data["status"] = "";

        $update["archived"] = 0;
        if($action == "archived")
        {
            $update["archived"] = 1;
        }

        if($data["status"] == "")
        {
            Tbl_email_content::where("email_content_id",$id)->update($update);
            $data["status"] = "success";
        }

        return json_encode($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {

        $content_key = "test";

        $txt[0]["txt_to_be_replace"] = "[name]";
        $txt[0]["txt_to_replace"] = "Raymond Fajardo";

        $txt[1]["txt_to_be_replace"] = "[from]";
        $txt[1]["txt_to_replace"] = "DIGIMAWEB";

        $change_content = $txt;

        $data["email"] = Request::input("email") != "" ? Request::input("email") : "arcylen103095@gmail.com";

        $data["content"] = EmailContent::email_txt_replace($content_key, $change_content);

        Mail::send('emails.test', $data, function ($message) use ($data)
        {
            $message->from(env('MAIL_USERNAME'), 'Sample Email');

            $message->to($data["email"])->subject("test");
        });

        return Redirect::back();
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
