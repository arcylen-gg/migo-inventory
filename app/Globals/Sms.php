<?php
namespace App\Globals;

use App\Models\Tbl_sms_key;
use App\Models\Tbl_sms_template;
use App\Models\Tbl_sms_logs;
use App\Models\Tbl_user;

use DB;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use File;
use Carbon\carbon;

/**
 * Branded Sms Module - all sms related module
 *
 * @author Bryan Kier Aradanas
 */

class Sms 
{
	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	/**
	 * Send single sms with a template content
	 *
	 * @param string or array  	$recipient 		Mobile Number of the recipient (e.g. 63912345678)
	 * @param array  			$content  		Contains the content of the sms
	 * @param string  			$key      		Contains the key of a specific sms type or can be null
	 * @param int  				$shop_id    	Shop id of the products that you wnat to get. null if auto get
	 * @return array 			$response
	 */
	public static function SendSingleText($recipient, $content, $key, $shop_id = null)
	{
		if(!$shop_id)
		{
			$shop_id = Sms::getShopId();
		}

		$sms_key = Tbl_sms_key::where("sms_shop_id", $shop_id)->value("sms_authorization_key");

		if(is_array($recipient))
		{
			$_recipient = "";
			foreach($recipient as $key1=>$number)
			{
				$key1 == 0 ? $_recipient .= "\"$number\"" : $_recipient .= ",\"$number\"" ;
			}
			$recipient = "[$_recipient]";
		}
		else
		{
			$recipient = "\"$recipient\"";
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{ \"from\":\"PhilTECH\", \"to\":$recipient, \"text\":\"$content.\" }",
			CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"authorization: Basic $sms_key",
				"content-type: application/json"
			),
		));

		$response 	= curl_exec($curl);
		$err 		= curl_error($curl);

		curl_close($curl);

		if ($err) {
			$status = "FAILED";
			$data 	= "cURL Error #:" . $err;
		} 
		else {
			$status = "UNKNOWN";
			$data   = $response;
		}

		$insert["sms_logs_shop_id"] = $shop_id;
		$insert["sms_logs_key"]		= $key;
		$insert["sms_logs_status"]	= $status;
		$insert["sms_logs_remarks"]	= json_encode($response);
		$insert["created_at"]		= Carbon::now();
		Tbl_sms_logs::insert($insert);

		return $response;
	}

	/**
	 * Send single sms with a template content
	 *
	 * @param string or array  	$recipient 		Mobile Number of the recipient (e.g. 63912345678)
	 * @param array  			$replace_data  	Contains the text to be replace (e.g : [name] => Juan) 
	 *											(variable : data[0][txt_to_be_replace] , $txt[0]["txt_to_replace"]) 
	 * @param string  			$key      		Contains the key of a specific sms tamplate
	 * @param int  				$shop_id    	Shop id of the products that you wnat to get. null if auto get
	 * @return array 			['status'], ['message']
	 */
	public static function SendSms($recipient, $key, $replace_data, $shop_id = null)
	{
		if(!$shop_id)
		{
			$shop_id = Sms::getShopId();
		}

		/* Check Content */
		$content_status = Sms::getSmsContent($key, $replace_data, $recipient, $shop_id);

		if($content_status['status'] == "FAILED")
		{
			$data["status"]  = $content_status['status'];
			$data["message"] = $content_status['message'];

			return $data;
		}

		$content = $content_status["message"];
		$sms_key = Tbl_sms_key::where("sms_shop_id", $shop_id)->value("sms_authorization_key");
		// $sms_key = Sms::apiKey($sms_key);
		
		if(is_array($recipient))
		{
			$_recipient = "";
			foreach($recipient as $key=>$number)
			{
				$key == 0 ? $_recipient .= "\"$number\"" : $_recipient .= ",\"$number\"" ;
			}
			$new_recipient = "[$_recipient]";
		}
		else
		{
			$new_recipient = "\"$recipient\"";
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{ \"from\":\"PhilTECH\", \"to\":$new_recipient, \"text\":\"$content.\" }",
			CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"authorization: Basic $sms_key",
				"content-type: application/json"
			),
		));

		// dd($curl);

		$response 	= curl_exec($curl);
		$err 		= curl_error($curl);

		curl_close($curl);

		if ($err) {
			$status = "FAILED";
			$result = "cURL Error #:" . $err;
		} 
		else {
			$status = "UNKNOWN";
			$result = $response;
		}

		$insert["sms_logs_shop_id"] = $shop_id;
		$insert["sms_logs_key"]		= $key;
		$insert["sms_logs_status"]	= $status;
		$insert["sms_logs_recipient"] = $recipient == null ? '' : $recipient;
		$insert["sms_logs_remarks"]	= json_encode($result);
		$insert["created_at"]		= Carbon::now();
		Tbl_sms_logs::insert($insert);

		$data["status"]   = $status;
		$data["message"]  = $result;

		return $data;
	}

	public static function getSmsContent($key, $replace_data, $recipient, $shop_id)
	{
		$sms_content 	= Tbl_sms_template::where("sms_temp_shop_id", $shop_id)->where("sms_temp_key", "$key")->first();
		$sms_key 		= Tbl_sms_key::where("sms_shop_id", $shop_id)->first();

		/* IF THER IS SMS AUTHORIZATION KEY */
		if($sms_key && $sms_key != '')
		{
			/* IF THERE IS TEMPLATE FOR SMS KEY */
			if($sms_content)
			{
				/* IF THE TEMPLATE IS ENABLED */
				if($sms_content->sms_temp_is_on == 1)
				{
					$content = $sms_content->sms_temp_content;
					foreach ($replace_data as $key => $value)
			        {        	
			        	$content = str_replace($value["txt_to_be_replace"], $value["txt_to_replace"], $content);	
			        }
			        
			        $data["status"] 	= "success";
			        $data["message"] 	= $content;
			        
		    	}
		    	else
		    	{
		    		$data["status"] 	= "FAILED";
			        $data["message"] 	= "template for this sms key is disabled";
		    	}
			}
			else
			{
				$data["status"] 	= "FAILED";
				$data["message"] 	= "template not found";
			}

			if($data["status"] == "FAILED")
			{
				$insert["sms_logs_shop_id"] = $shop_id;
				$insert["sms_logs_key"]		= $key;
				$insert["sms_logs_status"]	= $data["status"];
				$insert["sms_logs_recipient"] = $recipient == null ? '' : $recipient;
				$insert["sms_logs_remarks"]	= $data["message"];
				$insert["created_at"]		= Carbon::now();
				Tbl_sms_logs::insert($insert);
			}
		}
		else
		{
			$data["status"] = "FAILED";
			$data["message"] = "No Sms Key Found";
		}

		return $data;
	} 

	public static function limit($str, $length)
	{
		if(strlen($x)<=$length)
		{
			return $str;
		}
		else
		{
			return substr($x,0,$length) . '...';
		}
	}

	public static function getSmsLogs($shop_id = null)
	{
		if(!$shop_id)
		{
			$shop_id = Sms::getShopId();
		}

		$sms_key = Tbl_sms_key::where("sms_shop_id", $shop_id)->value("sms_authorization_key");

		if($sms_key)
		{
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://api.infobip.com/sms/1/logs",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"authorization: Basic $sms_key"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

		    if(!$sms_key) {
		    	return array();
		    }
			else if ($err) {
			  	return "cURL Error #:" . $err;
			} else {
			  	$data = json_decode($response);
			  	if(isset($data->requestError))  return [];
			  	else 							return $data->results;
			}
		}
		return [];

	}

	public static function getSmsBalance($shop_id = null)
	{
		if(!$shop_id)
		{
			$shop_id = Sms::getShopId();
		}

		$sms_key = Tbl_sms_key::where("sms_shop_id", $shop_id)->value("sms_authorization_key");

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.infobip.com/account/1/balance",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"accept: application/json",
			"authorization: Basic $sms_key"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

	    if(!$sms_key) {
	    	return [];
	    }
		else if ($err) {
		  	return "cURL Error #:" . $err;
		} else {
		  	$data = json_decode($response);
		  	if(isset($data->requestError))  return [];
		  	else 							return $data;
		}
	}

	public static function apiKey($sms_key)
	{

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.infobip.com/2fa/1/api-key",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"authorization: Basic $sms_key",
				"content-type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		return json_decode($response);
	}
}