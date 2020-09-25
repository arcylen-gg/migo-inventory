<?php
namespace App\Globals;

class global_app
{
	public function curl_function($url = '' , $method = '', $data = '')
	{

		$ch = curl_init();

		switch ($method)
	    {
	        case "POST":
	            curl_setopt($ch, CURLOPT_POST, 1);

	            curl_setopt($ch, C\URLOPT_POSTFIELDS, http_build_query($data));
	            break;

	        case "PUT":
	            curl_setopt($ch, CURLOPT_PUT, 1);
	            break;
	    }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "username:password");

        $response = curl_exec($ch);
        $code	  = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return json_decode($response);
	}

	public function wherein_array($arr, $compare_from, $compare_to_array)
	{
		foreach($arr as $key => $item)
		{
			foreach($compare_to_array as $item_id)
			{
        		if ($item->$compare_from == $item_id)
            	$data[$key] = $item;
        	}
    	}

    	return $data;
	}
}