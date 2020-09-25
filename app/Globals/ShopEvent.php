<?php
namespace App\Globals;

use App\Models\Tbl_shop_event;
use App\Models\Tbl_shop_event_reserved;
use App\Models\Tbl_user;
use App\Models\Tbl_email_template;
use File;
use App\Globals\Mail_global;
/**
 * 
 *
 * @author ARCYLEN
 */

class ShopEvent
{
	public static function create_event($ins)
	{
		return Tbl_shop_event::insertGetId($ins);
	}
	public static function update_event($id, $update)
	{
		return Tbl_shop_event::where('event_id',$id)->update($update);
	}
	public static function get($shop_id, $archived = 0, $get = 0, $date = null, $customer_id = null, $type = array())
	{
		$data = Tbl_shop_event::where('event_shop_id',$shop_id)->where('archived', $archived);
		
		if($date)
		{
			$date_now = date('Y-m-d', strtotime($date));
			$data->where('event_date','>=',$date_now);
		}
		if(count($type) > 0)
		{
			$data->whereIn('event_attendee',$type);
		}

		$return = $data->orderBy('event_date','ASC')->get();

		if($customer_id)
		{
			foreach ($return as $key => $value) 
			{
				$return[$key]->is_reserved = 0;
				$check = Tbl_shop_event_reserved::where('customer_id',$customer_id)->where('event_id',$value->event_id)->first();
				if($check)
				{
					$return[$key]->is_reserved = 1;
				}
			}
		}
		if($get != 0)
		{
			$data->paginate($get);
		}
		foreach ($return as $key => $value) 
		{
			$total_count = Tbl_shop_event_reserved::where('event_id',$value->event_id)->count();
			$customer_count = Tbl_shop_event_reserved::where('customer_id', '!=', null)->where('event_id',$value->event_id)->count();
			$guest_count = Tbl_shop_event_reserved::where('customer_id', '=', null)->where('event_id',$value->event_id)->count();

			$return[$key]->reservee_total_count = $total_count;
			$return[$key]->reservee_guest_count = $guest_count;
			$return[$key]->reservee_customer_count = $customer_count;
		}

		return $return;
	}
	public static function first($shop_id, $event_id)
	{
		return Tbl_shop_event::where('event_shop_id',$shop_id)->where('event_id', $event_id)->first();
	}
	public static function archive_event($id, $action)
	{
		$update['archived'] = 1;
		if($action == 'restore')
		{
			$update['archived'] = 0;
		}

		return ShopEvent::update_event($id, $update);
	}
	public static function get_all_reservee($event_id, $paginate = 0)
	{
		$data = Tbl_shop_event_reserved::where('event_id',$event_id);
		$return = null;
		if($paginate != 0)
		{
			$data = $data->paginate($paginate);
		}
		else
		{
			$data = $data->get();
		}

		$return = $data;

		return $return;
	}
	public static function reserved_seat($event_id, $customer_id = 0, $data)
	{
		$return = null;
		$event_data = Tbl_shop_event::where('event_id', $event_id)->first();
		$event_number_attendee = Tbl_shop_event::where('event_id', $event_id)->value('event_number_attendee');
		$count = Tbl_shop_event_reserved::where('event_id', $event_id)->count();
		
		if($count < $event_number_attendee)
		{
			$check_code = Tbl_shop_event_reserved::where('event_id', $event_id)->where("reservee_enrollers_code",$data['reservee_enrollers_code'])->count();
			if(!$check_code)
			{
				if($customer_id != 0)
				{
					$check = Tbl_shop_event_reserved::where('customer_id',$customer_id)->where('event_id',$event_id)->first();

					if($check)
					{
						$return .= "Already reserved a seat"; 
					}
					else
					{
						$data['event_id'] = $event_id;
						$data['customer_id'] = $customer_id;
						$return = Tbl_shop_event_reserved::insertGetId($data);
					}
				}
				else
				{
					$data['event_id'] = $event_id;
					$return = Tbl_shop_event_reserved::insertGetId($data);
				}
			}
			else
			{
				$return .= "Enrollers code already used";
			}
		}
		else
		{
			$return .= "All seats are already reserved";
		}
		if(is_numeric($return))
		{
			ShopEvent::send_email_reservee($event_data->event_shop_id, $event_id);
		}

		return $return;
	}
	public static function send_email_reservee($shop_id = 0, $event_id = 0)
	{
		$template = Tbl_email_template::where("shop_id", $shop_id)->first();
        if(isset($template->header_image))
        {
            if (!File::exists(public_path() . $template->header_image))
            {
                $template->header_image = null;
            }
        }
        $content = "<div style='text-align'><h3>A NEW RESERVEE REGISTERED</h3></div><br><br>";

        $all_attendees = Tbl_shop_event_reserved::where('event_id',$event_id)->orderBy('reservation_id','DESC')->get();

        $list = "<table style='width:100%;border: 1px solid #000;'><thead style='padding:20px;'><tr><th width='20px'>#</th><th width='300px'>Name</th><th width='200px'>Contact Details</th><th width='100px'>Enrollers Code</th><th width='100px'></th></tr></thead><tbody>";

        foreach ($all_attendees as $attendees_key => $attendees_value) 
        {
        	$type = "Guest";
			if($attendees_value->customer_id != null)
			{
				$type = "Member";
			}
        	$list .= "<tr><td >". ($attendees_key + 1) ."</td><td>".ucwords($attendees_value->reservee_fname. ' '.$attendees_value->reservee_mname.' '.$attendees_value->reservee_lname)."</td><td>".$attendees_value->reservee_contact."</td><td><div>".strtoupper($attendees_value->reservee_enrollers_code)."</div></td><td><div>".$type."</div></td>";
        	$list .= "</tr>"; 
        }
        $list .= "</tbody></table>";

        $all_user = Tbl_user::where('user_shop', $shop_id)->get()->toArray();
        if($shop_id == 5)
        {
        	// $all_user = Tbl_user::where('user_shop', $shop_id)->where('user_level',5)->get();
        	$all_user[0]['user_email'] = 'jonathan@brown.com.ph';
        	$all_user[1]['user_email'] = 'jason@brown.com.ph';
        	$all_user[2]['user_email'] = 'archie@myphone.com.ph';
        	$all_user[3]['user_email'] = 'cio@digimaweb.solutions';
        	$all_user[4]['user_email'] = 'ceo@digimaweb.solutions';
        	$all_user[5]['user_email'] = 'raymond.fajardo@digimaweb.solutions';
        }

        foreach ($all_user as $key => $value) 
        {
        	$email_address = $value['user_email'];
	        $email['subject'] = "A New Reservee Registered !";
	        $email['content'] = $content.$list;

	        Mail_global::send_email($template, $email, $shop_id, $email_address);
        }
	}
}