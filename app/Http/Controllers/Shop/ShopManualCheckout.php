<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Tbl_transaction_list;
use App\Models\Tbl_transaction;
use App\Models\Tbl_online_pymnt_gateway;
use App\Models\Tbl_online_pymnt_api;
use Crypt;
use Carbon\Carbon;
use App\Globals\Currency;
use App\Globals\Transaction;
use stdClass;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Tbl_online_pymnt_method;
use Redirect;


class ShopManualCheckout extends Shop
{
    public function index()
    {
        $transaction_list_id 	= Crypt::decrypt(request("tid"));
        $data["transaction"] 	= $transaction_list = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->transaction()->first();
       	
        $method					= $transaction_list->payment_method;
       	$shop_id 				= $transaction_list->shop_id;
       	$gateway_id 			= Tbl_online_pymnt_gateway::where("gateway_code_name", $method)->value("gateway_id");
       	$api					= Tbl_online_pymnt_api::where("api_gateway_id", $gateway_id)->where("api_shop_id", $shop_id)->first();
        $data["method_name"]    = Tbl_online_pymnt_method::where("method_id", request('method_id'))->value('method_name');
        
       	if(!$api)
       	{
       		$api = new stdClass();
       		$api->api_client_id = "";
       		$api->api_secret_id = "DEFAULT";
       	}

       	if($api->api_secret_id == "DEFAULT")
       	{
       		$api->api_secret_id = view("member2.default_step", $data);
       	}


       	$data["api"] 				= $api;
       	$data["transaction_total"]	= Currency::format($transaction_list->transaction_total);

        return view("member2.manual_checkout", $data);
    }
    public function submit_proof(Request $request)
    {
      $transaction_list_id  = Crypt::decrypt(request("tid"));

      $transaction_list = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->transaction()->first();
      $proof = $transaction_list->transaction_payment_proof;
      $status = $transaction_list->payment_status;
      if($proof == "")
      {
        $this->send_proof($transaction_list_id,$request);
      return redirect("/manual_checkout/success");
      }
      else
      {
        if($status == "reject")
        {
          $this->send_proof($transaction_list_id,$request);
      return redirect("/manual_checkout/success");
          //update status
        }
        else
        {
          $response = 'error';
          return Redirect::back()->with("response",$response);
        }
      }


        

      
    }
    public function send_proof($transaction_list_id,$request)
    {
      $transaction_list     = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->transaction()->first();
      $transaction_type     = "PROOF";
      $transaction_id       = $transaction_list->transaction_id;
      $shop_id              = $transaction_list->shop_id;
      $transaction_date     = Carbon::now();
      $source               = $transaction_list_id;

      $path = Storage::putFile('payment-proof', $request->file('proofupload'));

      Transaction::create_update_proof($path);
      Transaction::create_update_proof_details(request()->except(['proofupload', '_token', "tid", "method_id"]));
      $transaction_list_id  = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, null, $source);
    
    }
    public function success()
    {
      echo "Your payment details has been uploaded. Kindly wait 48 hours for your payment confirmation.<br>You will receive an e-mail once payment has been confirmed.";
    }
}