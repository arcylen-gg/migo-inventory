<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use App\Globals\ShopEvent;
/**
 * 
 *
 * @author ARCYLEN GARCIA GUTIERREZ
 */

class EventController extends Member
{
	public function getIndex()
	{
		$data['page'] = 'Events';
		$data['_event'] = ShopEvent::get($this->user_info->shop_id);
		$data['_event_archived'] = ShopEvent::get($this->user_info->shop_id,1);
		return view('member.manage_page_event.event_list',$data);
	}
	public function getCreate(Request $request)
	{
		$data['process'] = 'Create';
		$data['action'] = '/member/page/events/create-submit';

		if($request->id)
		{
			$data['process'] = 'Update';
			$data['event'] = ShopEvent::first($this->user_info->shop_id, $request->id);
			if($data['event'])
			{
				$data['event_member'] = null;
				$data['event_guest'] = null;
				if($data['event']->event_attendee == 'all')
				{
					$data['event_member'] = 'on';
					$data['event_guest'] = 'on';
				}	
				else if($data['event']->event_attendee == 'members')
				{
					$data['event_member'] = 'on';
				}
				else if($data['event']->event_attendee == 'guest')
				{
					$data['event_guest'] = 'on';
				}

			}
			$data['action'] = '/member/page/events/update-submit';
		}
		return view('member.manage_page_event.event_create',$data);		
	}
	public function postCreateSubmit(Request $request)
	{
		$insert['event_title']		 = $request->event_title;
		$insert['event_sub_title'] 	 = $request->event_sub_title;
		$insert['event_date']        = datepicker_input($request->event_date);
		$insert['event_description'] = $request->event_description;
		$insert['event_number_attendee'] = $request->event_number_attendee;

		$rules["event_title"]           = "required";
        $rules["event_sub_title"]   	= "required";
        $rules["event_date"]   		    = "required";
        $rules["event_description"]  	= "required";
        $rules["event_number_attendee"]  	= "required|numeric";

        $validate = Validator::make($insert, $rules);

		$attendee = '';
		if($request->event_member && $request->event_guest)
		{
			$attendee = 'all';
		}
		else if($request->event_member)
		{
			$attendee = 'members';
		}
		else if($request->event_guest)
		{
			$attendee = 'guest';
		}

		$insert['event_thumbnail_image']	 = $request->event_thumbnail_image;
		$insert['event_banner_image'] 		 = $request->event_banner_image;
		$insert['event_attendee']  		     = $attendee;
		$insert['event_shop_id']			 = $this->user_info->shop_id;

        $return['status'] = null;
        $return['status_message'] = null;
        if(!$validate->fails()) 
        {
        	$id = ShopEvent::create_event($insert);
        	if(is_numeric($id))
        	{
        		$return['status'] = 'success';
        		$return['call_function'] = 'success_event';
        	}
        	else
        	{
	        	$return['status'] = 'error';
	        	$return['status_message'] = "Something wen't wrong";        		
        	}
        }
        else
        {
        	$message = '';
            foreach($validate->errors()->all() as $error)
            {
                $message .= "<li>" . $error . "</li>";
            }
        	$return['status'] = 'error';
        	$return['status_message'] = $message;
        }

        return json_encode($return);
	}
	public function postUpdateSubmit(Request $request)
	{
		$event_id = $request->event_id;

		$update['event_title']		 = $request->event_title;
		$update['event_sub_title'] 	 = $request->event_sub_title;
		$update['event_date']        = datepicker_input($request->event_date);
		$update['event_description'] = $request->event_description;
		$update['event_number_attendee'] = $request->event_number_attendee;

		$rules["event_title"]           = "required";
        $rules["event_sub_title"]   	= "required";
        $rules["event_date"]   		    = "required";
        $rules["event_description"]  	= "required";
        $rules["event_number_attendee"]  	= "required|numeric";

        $validate = Validator::make($update, $rules);

		$attendee = '';
		if($request->event_member && $request->event_guest)
		{
			$attendee = 'all';
		}
		else if($request->event_member)
		{
			$attendee = 'members';
		}
		else if($request->event_guest)
		{
			$attendee = 'guest';
		}

		$update['event_thumbnail_image']	 = $request->event_thumbnail_image;
		$update['event_banner_image'] 		 = $request->event_banner_image;
		$update['event_attendee']  		     = $attendee;

        $return['status'] = null;
        $return['status_message'] = null;
        if(!$validate->fails()) 
        {
        	$id = ShopEvent::update_event($event_id, $update);
        	if(is_numeric($id))
        	{
        		$return['status'] = 'success';
        		$return['call_function'] = 'success_event';
        	}
        	else
        	{
	        	$return['status'] = 'error';
	        	$return['status_message'] = "Something wen't wrong";        		
        	}
        }
        else
        {
        	$message = '';
            foreach($validate->errors()->all() as $error)
            {
                $message .= "<li>" . $error . "</li>";
            }
        	$return['status'] = 'error';
        	$return['status_message'] = $message;
        }

        return json_encode($return);
	}
	public function getConfirmArchived(Request $request)
	{
		$data['event'] = ShopEvent::first($this->user_info->shop_id, $request->id);
		$data['action'] = $request->action;
		return view('member.manage_page_event.event_confirm',$data);
	}
	public function postArchived(Request $request)
	{
		$id = ShopEvent::archive_event($request->id, $request->action);
    	if(is_numeric($id))
    	{
    		$return['status'] = 'success';
    		$return['call_function'] = 'success_event';
    	}
    	else
    	{
        	$return['status'] = 'error';
        	$return['status_message'] = "Something wen't wrong";        		
    	}

    	return json_encode($return);
	}
	public function getReserveeList(Request $request)
	{
		$data['_reservee'] = ShopEvent::get_all_reservee($request->id);

		return view('member.manage_page_event.event_reservee_list',$data);
	}
}