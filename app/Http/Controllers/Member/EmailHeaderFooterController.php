<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Tbl_email_template;
use Request;
class EmailHeaderFooterController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["template"] = Tbl_email_template::where("shop_id",$this->user_info->shop_id)->first();
        return view("member.maintenance.email_header_footer.email_header_footer",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update($type)
    {
        $data["type"] = $type;
        $ctr = Tbl_email_template::where("shop_id",$this->user_info->shop_id)->first();

        $data["action"] = "/member/maintenance/email_header_footer/update_submit";
        if($ctr != null)
        {
            $data["template"] = $ctr;
        }
        
        return view("member.maintenance.email_header_footer.edit_email_header_footer",$data);
    }
    public function update_submit()
    {
        $data["status"] = "";
        $data["status_message"] = "";

        $id = Request::input("email_template_id");

        $type = Request::input("type");
        $header_txt = Request::input("header_txt");
        $header_background_color = Request::input("header_background_color");
        $header_text_color = Request::input("header_text_color");
        $header_image = Request::input("header_image");

        $footer_txt = Request::input("footer_txt");
        $footer_background_color = Request::input("footer_background_color");
        $footer_text_color = Request::input("footer_text_color");

        if($id != null)
        {
            //update
            if($type == "header")
            {
                $update["header_txt"] = $header_txt;
                $update["header_background_color"] = $header_background_color;
                $update["header_text_color"] = $header_text_color;
                $update["header_image"] = $header_image;                
            }
            else
            {             
                $update["footer_txt"] = $footer_txt;
                $update["footer_background_color"] = $footer_background_color;
                $update["footer_text_color"] = $footer_text_color;           
            }

            $data["status"] = "success";
            Tbl_email_template::where("email_template_id",$id)->update($update);

        }
        else
        {
            //add
            $insert["shop_id"] = $this->user_info->shop_id;
            if($type == "header")
            {
                $insert["header_txt"] = $header_txt;
                $insert["header_background_color"] = $header_background_color;
                $insert["header_text_color"] = $header_text_color;
                $insert["header_image"] = $header_image;                
            }
            else
            {             
                $insert["footer_txt"] = $footer_txt;
                $insert["footer_background_color"] = $footer_background_color;
                $insert["footer_text_color"] = $footer_text_color;           
            }

            $data["status"] = "success";
            Tbl_email_template::insert($insert);
        }

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
