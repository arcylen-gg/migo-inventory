<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DOMDocument;
use Config;
use DB;
use App\Globals\Mail_global;
use App\Models\Tbl_user;
use App\Models\Tbl_email_template;

class ShopContactController extends Shop
{
    public function index()
    {
        $data["page"] = "Contact";
        return view("contact", $data);
    }

    public function contact_submit()
    {
        $owner_email = DB::table("tbl_settings")->where("shop_id", $this->shop_info->shop_id)->where("settings_key", "contact_email_address")->first();

        if (!$owner_email) 
        {
            $owner_email = Tbl_user::where("user_shop", $this->shop_info->shop_id)->where("archived", 0)->first();
        }

    	$data["template"] = Tbl_email_template::where("shop_id", $this->shop_info->shop_id)->first();
    	$data['mail_to'] = $owner_email;
    	$data['mail_username'] = Config::get('mail.username');
    	$data['mail_first_name'] = Request::input("first_name");
        $data['mail_last_name'] = Request::input("last_name");
        $data['mail_phone_number'] = Request::input("phone_number");
        $data['mail_email_address'] = Request::input("email_address");
        $data['mail_subject'] = Request::input("subject");
        $data['mail_message'] = Request::input("message");

    	$result = Mail_global::contact_mail($data, $this->shop_info->shop_id);

    	if ($result == 1) 
    	{
            if ($this->shop_theme == "intogadgets") 
            {
                return Redirect::to('/contact#contact_success'); 
            }
            else
            {
                return Redirect::to('/contact?success=Successfully sent!');    
            }
    	}
    	else
    	{
            if ($this->shop_theme == "intogadgets") 
            {
                return Redirect::to('/contact#contact_fail');
            }
            else
            {
                return Redirect::to('/contact?fail=Some error occurred. Please try again later.'); 
            }
    		
    	}
    }

    public function find_store()
    {
    	// Get parameters from URL
		$center_lat = Request::input("lat");
		$center_lng = Request::input("lng");
		$radius = Request::input("radius");
		// Start XML file, create parent node
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);

		// header("Content-type: text/xml");
		// Iterate through the rows, adding XML nodes for each
		$locations = is_serialized(get_content($this->shop_theme_info, "contact", "contact_store_maintenance")) ? unserialize(get_content($this->shop_theme_info, "contact", "contact_store_maintenance")) : [];

		foreach ($locations as $key => $value) 
		{
			$distance = 6371 * acos( cos( deg2rad(Request::input("lat")) ) * cos( deg2rad( $value['latitude'] ) ) * cos( deg2rad( $value['longitude'] ) - deg2rad(Request::input("lng")) ) + sin( deg2rad(Request::input("lat")) ) * sin( deg2rad( $value['latitude'] ) ) );

			if ($distance < $radius) 
			{
				$node = $dom->createElement("marker");
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("id", $key);
				$newnode->setAttribute("name", $value["name"]);
				$newnode->setAttribute("address", $value['address']);
				$newnode->setAttribute("lat", $value['latitude']);
				$newnode->setAttribute("lng", $value['longitude']);
				$newnode->setAttribute("distance", $distance);
			}
		}
		
		$result = $dom->saveXML();
		echo $result;
    }
     public function contact_us()
    {
        $data["page"] = "Contact Us";
        return view("press_user.contact", $data);
    }
    
}