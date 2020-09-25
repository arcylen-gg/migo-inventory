<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Crypt;
use Redirect;
use View;
use Validator;
use Carbon\Carbon;
use File;

use App\Models\Tbl_post;
use App\Globals\Post;
use App\Globals\ShopEvent;

class ShopEventsController extends Shop
{
    public function index()
    {
        $data["page"] = "Events";
        $data["_post"] = Post::get_posts($this->shop_info->shop_id);

        return view("events", $data);
    }

    public function view($id)
    {
    	$data["page"] = "Events View";
    	$data["post"] = Post::get_post($id, $this->shop_info->shop_id);
    	$data["_related"] = Post::get_related_posts($data["post"]->post_category_id, $this->shop_info->shop_id);
        // dd($data["_related"]);
    	return view("events_view", $data);
    }
    public function view_details(Request $request)
    {
        $data['event'] = ShopEvent::first($this->shop_info->shop_id, $request->id);
        $data['reserve_seat_btn'] = '/events/event_reserve?id='.$request->id;

        return view('member.view_events', $data);
    }

    public function event_reserve(Request $request)
    {
        $data['page'] = "Reserve a Seat";
        $data['event'] = ShopEvent::first($this->shop_info->shop_id, $request->id);
        $data['action'] = '/events/event_reserve_submit';

        $data['customer_details'] = null;
        $data['customer_address'] = null;

        return view('member.event_popup_form', $data);
    }
     public function event_reserve_submit(Request $request)
    {
        $insert['reservee_fname']           = $request->reservee_fname;
        $insert['reservee_mname']           = $request->reservee_mname;
        $insert['reservee_lname']           = $request->reservee_lname;
        $insert['reservee_address']         = $request->reservee_address;
        $insert['reservee_contact']         = $request->reservee_contact;
        $insert['reservee_enrollers_code']  = $request->reservee_enrollers_code;

        $validate['reservee_fname']             = 'required';
        $validate['reservee_mname']             = 'required';
        $validate['reservee_lname']             = 'required';
        $validate['reservee_address']           = 'required';
        $validate['reservee_contact']           = 'required';
        $validate['reservee_enrollers_code']    = 'required';

        $validator = Validator::make($insert, $validate);

        $insert['reserve_date']  = Carbon::now();
        
        $return['status'] = null;
        $return['status_message'] = null;
        if(!$validator->fails()) 
        {
            $return_id = ShopEvent::reserved_seat($request->event_id, null, $insert);

            if(is_numeric($return_id))
            {
                $return['status'] = 'success';
                $return['call_function'] = 'success_reserve';
            }
            else
            {                
                $return['status'] = 'error_status';
                $return['call_function'] = 'success_reserve';
                $return['status_message'] = $return_id;
            }
        }
        else
        {
            $message = null;
            foreach($validator->errors()->all() as $error)
            {
                $message .= "<div>" . $error . "</div>";
            }
            $return['status'] = 'error_status';
            $return['call_function'] = 'success_reserve';
            $return['status_message'] = $message;
        }

        return json_encode($return);
    }
}