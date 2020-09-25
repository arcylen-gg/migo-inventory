<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Models\Tbl_online_pymnt_method;
use App\Models\Tbl_online_pymnt_gateway;
use App\Models\Tbl_online_pymnt_api;
use App\Models\Tbl_online_pymnt_other;
use App\Models\Tbl_online_pymnt_link;
use App\Models\Tbl_user;

use Request;
use DB;
use Crypt;
use Carbon\Carbon;
use Session;
use Redirect;

class OnlinePaymentMethodController extends Member
{
	public function getList()
	{
		
	}

	public function getIndex()
	{
		$data["_method"] 	= Tbl_online_pymnt_method::where("method_shop_id", $this->getShopId())->link($this->getShopId())->get();

		foreach($data["_method"] as $key=>$method)
		{
			$data["_method"][$key] 			= $method;
			$data["_method"][$key]->gateway = $this->filterPaymentGateway($method->method_id);
		}

		$data["_gateway"] 	= $this->gatewayInfo();
		foreach ($data["_gateway"] as $key => $value) 
		{
			switch ($value->gateway_code_name) 
			{
				case 'ipay88':
					$data["_gateway"][$key]->gateway_first_label = "Merchant Code";
					$data["_gateway"][$key]->gateway_second_label = "Merchant Key";
				break;

				case 'dragonpay':
					$data["_gateway"][$key]->gateway_first_label = "Merchant ID";
					$data["_gateway"][$key]->gateway_second_label = "Merchant Key";
				break;

				case 'manual1':
					$data["_gateway"][$key]->gateway_first_label = "Reference List";
					$data["_gateway"][$key]->gateway_second_label = "Instruction";
				break;
				
				case 'manual2':
					$data["_gateway"][$key]->gateway_first_label = "Reference List";
					$data["_gateway"][$key]->gateway_second_label = "Instruction";
				break;

				default:
					$data["_gateway"][$key]->gateway_first_label = "Client ID";
					$data["_gateway"][$key]->gateway_second_label = "Secret ID";
				break;
			}
		}

		return view('member.online_payment.payment', $data);
	}

	public function gatewayInfo()
	{
		$_gateway = Tbl_online_pymnt_gateway::gatewayApi($this->getShopId())
						->get();
		foreach($_gateway as $key=>$gateway)
		{
			$_gateway[$key] = $gateway;
			if($gateway->gateway_code_name == 'other')
			{
				$_gateway[$key]->other = Tbl_online_pymnt_other::where('other_shop_id', $this->getShopId())->get();
			}
			else
			{
				$_gateway[$key]->client_id = $gateway->api_client_id;
				$_gateway[$key]->secret_id = $gateway->api_secret_ids;
			}
		}

		return $_gateway;
	}

	public function getOtherInfo()
	{
		$other_id = Request::input('id');
		$data = [];
		if($other_id)
		{
			$data["other"] = Tbl_online_pymnt_other::where("other_id", $other_id)->first();
		}

		return view('member.online_payment.load_other_info', $data);
		
	}

	public function postSaveGateway()
	{
		// dd(Request::input());
		$gateway_code = Request::input('gateway_code_name');

		$data["api_shop_id"] 	= $this->getShopId();
		$data["api_gateway_id"] = Request::input('api_gateway_id');
		$data["api_client_id"] 	= Request::input('api_client_id');
		$data["api_secret_id"] 	= Request::input('api_secret_id');

		if($gateway_code == "cashondelivery")
		{
			$data["api_client_id"] = "Cash on delivery";
			$data["api_secret_id"] = "Cash on delivery";
		}


		$api_id = Tbl_online_pymnt_api::where("api_shop_id", $data["api_shop_id"])->where("api_gateway_id", $data["api_gateway_id"])->value("api_id");
		
		if($api_id)
		{
			Tbl_online_pymnt_api::where("api_id", $api_id)->update($data);
		}
		else
		{
			$api_id = Tbl_online_pymnt_api::insertGetId($data);
		}

		$json["status"] = "success";
		$json["type"]	= "api";
		$json["api_id"]	= $api_id;

		return json_encode($json);
	}

	public function postSaveGatewayOther()
	{
		$other_id = Request::input('other_id');
		// dd($other_id);
		$data["other_shop_id"]			= $this->getShopId();
		$data["other_gateway_id"]		= 5;
		$data["other_name"] 			= Request::input('other_name');
		$data["other_description"] 		= Request::input('other_description');

		if($other_id != null)
		{
			Tbl_online_pymnt_other::where("other_id", $other_id)->update($data);
		}
		else
		{
			$other_id = Tbl_online_pymnt_other::insertGetId($data);
		}

		$json["status"]		= "success";
		$json["type"]		= "other";
		$json["other_id"]	= $other_id;

		return json_encode($json);
	}

	public function load_payment_gateway($id)
	{
		$data["_gateway"] = $this->filterPaymentGateway($id);

		return view('member.load_ajax_data.load_payment_gateway', $data);
	}

	public function filterPaymentGateway($id)
	{
		$_gateway		= Tbl_online_pymnt_gateway::unionGateway($this->getShopId())->get();

		return $_gateway;
	}

	public function postSavePaymentSetting()
	{
		$link_id = Tbl_online_pymnt_link::where("link_method_id", Request::input('link_method_id'))->where("link_shop_id", $this->getShopId())->first();

		$data["link_shop_id"] 			= $this->getShopId();
		$data["link_method_id"] 		= Request::input('link_method_id');
		$data["link_reference_name"] 	= Request::input('link_reference_name');
		$data["link_reference_id"] 		= str_replace("-","",strstr(Request::input('link_reference_id'), "-"));
		$data["link_description"]		= Request::input('link_description');
		$data["link_discount_fixed"]	= Request::input('link_discount_fixed');
		$data["link_discount_percentage"] = Request::input('link_discount_percentage');
		$data["link_img_id"] 			= Request::input('link_img_id');
		$data["link_delimeter"] 		= Request::input('link_delimeter');
		$data["link_is_enabled"] 		= Request::input('link_is_enabled') == 'on' ? 1 : 0;

		if($link_id != null)
		{
			Tbl_online_pymnt_link::where("link_id", $link_id->link_id)->update($data);
		}
		else
		{
			$link_id = Tbl_online_pymnt_link::insertGetId($data);
		}

		$json["status"]	= "success";
		$json["type"]	= "link";
		$json["link_id"]= $link_id;

		return json_encode($json);
	}

	public function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	public function getModalMethodList()
	{
		$data["name"] = '';

		$method_id = Request::input('method_id');
		if($method_id)
		{
			$data['method'] = Tbl_online_pymnt_method::where("method_id", $method_id)->first(); 
		}

		return view('member.online_payment.modal_method_list', $data);
	}

	public function postModalMethodList()
	{
		$data["method_name"] = Request::input('method_name');

		$method_id = Request::input('method_id');
		if($method_id)
		{
			Tbl_online_pymnt_method::where("method_id", $method_id)->update($data);
		}
		else
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	        $charactersLength = strlen($characters);
	        $randomString = '';
	        for ($i = 0; $i < 10; $i++) 
	        {
	            $randomString .= $characters[rand(0, $charactersLength - 1)];
	        }

			$data['method_shop_id']		 = $this->getShopId();
			$data['method_code_name']	 = $randomString;
			$data['method_date_created'] = Carbon::now();
			$method_id = Tbl_online_pymnt_method::insert($data);
		}

		$json["status"] 	= 'success';
		$json["type"] 		= 'payment_method';
		$json["message"]	= 'Success!';
		$json["method_id"]	= $method_id;

		return json_encode($json);
	}

	public function getDeleteMethodList()
	{
		$method_id = Request::input('method_id');
		$data["method"] = Tbl_online_pymnt_method::where("method_id", $method_id)->first();

		return view('member.online_payment.modal_confirm_delete', $data);
	}

	public function postDeleteMethodList()
	{
		$method_id = Request::input('method_id');

		Tbl_online_pymnt_method::where("method_id", $method_id)->delete();
		Tbl_online_pymnt_link::where("link_method_id", $method_id)->delete();

		$json["status"] 	= 'success';
		$json["type"] 		= 'payment_method';
		$json["message"]	= 'Success!';
		$json["method_id"]	= $method_id;

		return json_encode($json);
	}
}