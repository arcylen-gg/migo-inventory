<?php 

namespace App\Globals\abs;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\Tbl_shop;
use App\Models\Tbl_tour_wallet;
use App\Models\Tbl_tour_wallet_slot;
class AbsMain
{
    public static function get_balance($base_uri, $base_account_id, $base_user_name, $base_password)
    {
    	$headers = ['Connection'=> 'keep-alive', 
                    'Content-Type' => 'application/x-www-form-urlencoded', 
                    'Credentials' => '{"account_id":"'.$base_account_id.'","username":"'.$base_user_name.'","password":"'.$base_password.'"}',
                    'contents' => '',
                    'name' => ''];

        $headers = ['multipart' => ['headers' => $headers], 'headers' => $headers];    
        
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout'  => 10.0,
        ], $headers);

        try 
        {
            $uri = $base_uri . '/api/wallet/' . $base_account_id ;
            $request = new Request('GET', $uri);
            $response = $client->send($request, $headers);
            $body = $response->getBody();
            $data = $body->read(1024);
            $data_decoded = json_decode($data);

            $data_a['status'] = $data_decoded->status;
            $data_a['message'] = $data_decoded->message;
            if(isset($data_decoded->result))
            {
            	$data_a['result'] = $data_decoded->result;
            }
            
        }
        catch (\Exception $e) 
        {
            $data_a['status'] = 0;
            $data_a['message'] = 'Caught exception: ' .  $e->getMessage();
        }
        return $data_a;
    }

    public static function get_balance_client($base_uri, $base_account_id, $base_user_name, $base_password, $client_account_id)
    {
        $headers = ['Connection'=> 'keep-alive', 
                    'Content-Type' => 'application/x-www-form-urlencoded', 
                    'Credentials' => '{"account_id":"'.$base_account_id.'","username":"'.$base_user_name.'","password":"'.$base_password.'"}',
                    'contents' => '',
                    'name' => ''];

        $headers = ['multipart' => [], 'headers' => $headers];    
        
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout'  => 10.0,
        ], $headers);

        try 
        {
            $uri = $base_uri . '/api/wallet/' . $client_account_id ;
            $request = new Request('GET', $uri);
            $response = $client->send($request, $headers);
            $body = $response->getBody();
            $data = $body->read(1024);
            $data_decoded = json_decode($data);

            $data_a['status'] = $data_decoded->status;
            $data_a['message'] = $data_decoded->message;
            if(isset($data_decoded->result))
            {
                $data_a['result'] = $data_decoded->result;
            }
        }
        catch (\Exception $e) 
        {
            $data_a['status'] = 0;
            $data_a['message'] = 'Caught exception: ' .  $e->getMessage();
        }
        return $data_a;
    }

    public static function transfer_wallet($base_uri, $base_account_id, $base_user_name, $base_password, $client_account_id, $amount)
    {
        $data[0]['key'] = "account_id";
        $data[0]['value'] = "ABS-0008";
        $data[0]['type'] = "text";
        $data[0]['enabled'] = true;
        $data[0]['contents'] = '';
        $data[0]['name'] = '';

        $data[1]['key'] = "amount";
        $data[1]['value'] = "1000";
        $data[1]['type'] = "text";
        $data[1]['enabled'] = true;
        $data[1]['contents'] = '';
        $data[1]['name'] = '';
        // $data = '{"key": "account_id","value": "ABS-0008","type": "text","enabled": true},{"key": "amount","value": "1000","type": "text","enabled": true}';

        $headers = ['Connection'=> 'keep-alive', 
                    'Content-Type' => 'application/x-www-form-urlencoded', 
                    'Credentials' => '{"account_id":"'.$base_account_id.'","username":"'.$base_user_name.'","password":"'.$base_password.'"}',
                    'contents' => '',
                    'name' => ''
                    ];

          $headers = 
          [
            'form_params' => 
                [
                    'account_id' => $client_account_id,
                    'amount' => $amount,
                ], 
            'headers' => $headers, 
            'contents' => '', 
            'name' => ''
        ];      
        // dd($headers);
        $client = new Client([
            'base_uri' => $base_uri,
            'timeout'  => 10.0,
        ], $headers);

        try 
        {
            $uri = $base_uri . '/api/wallet';
            // dd($uri);
            $request = new Request('POST', $uri);

            $response = $client->send($request, $headers);
            $body = $response->getBody();
            $data = $body->read(1024);
            $data_decoded = json_decode($data);

            $data_a['status'] = $data_decoded->status;
            $data_a['message'] = $data_decoded->message;
            if(isset($data_decoded->result))
            {
                $data_a['result'] = $data_decoded->result;
            }
            
        }
        catch (\Exception $e) 
        {
            $data_a['status'] = 0;
            $data_a['message'] = 'Caught exception: ' .  $e->getMessage();
        }
        return $data_a;
    }

    public static function update_info($customer_id, $slot_id, $tour_wallet_account_id, $shop_id)
    {
        $shop = Tbl_shop::where("shop_id", $shop_id)->first();

        if ($shop) 
        {
            $base_uri = $shop->shop_wallet_tours_uri;
        }
        else
        {
            $base_uri = "http://tripoption.tours";
        }

        $host = Tbl_tour_wallet::where('tour_wallet_shop', $shop_id)
                               ->where('tour_wallet_main', 1)
                               ->first();

        $status = AbsMain::get_balance_client($base_uri, $host->tour_Wallet_a_account_id, $host->tour_wallet_a_username, $host->tour_wallet_a_base_password, $tour_wallet_account_id);
        
        if($status['status'] == 1)
        {
            $count = Tbl_tour_wallet::where('tour_wallet_customer_id', $customer_id)
                                    ->where('tour_wallet_main', 0)
                                    ->count();

            $insert['tour_wallet_shop']              = $shop_id; 
            $insert['tour_wallet_customer_id']       = $customer_id;
            $insert['tour_wallet_user_id']           = 0;
            $insert['tour_Wallet_a_account_id']      = $tour_wallet_account_id;
            $insert['tour_wallet_a_current_balance'] = $status['result'];
            $insert['tour_wallet_main']              = 0;
            $insert['tour_wallet_block']             = 0; 

            if($count == 0)
            {
                $tour_wallet_id = Tbl_tour_wallet::insertGetid($insert);
            }
            else
            {
                Tbl_tour_wallet::where('tour_wallet_customer_id', $customer_id)
                               ->where('tour_wallet_main', 0)
                               ->update($insert);

                $tour_wallet = Tbl_tour_wallet::where('tour_wallet_customer_id', $customer_id)
                                              ->where('tour_wallet_main', 0)
                                              ->first();
                if ($tour_wallet) 
                {
                    $tour_wallet_id = $tour_wallet->tour_wallet_id;
                }
                else
                {
                    $tour_wallet_id = null;
                }
            }

            /* Tour Wallet Slot */
            $tour_wallet_slot = Tbl_tour_wallet_slot::where("tour_wallet_id", $tour_wallet_id)
                                                    ->where("slot_id", $slot_id)
                                                    ->first();

            $update["tour_wallet_id"]         = $tour_wallet_id;
            $update["slot_id"]                = $slot_id;
            $update["tour_wallet_account_id"] = $tour_wallet_account_id;
            
            if ($tour_wallet_slot) 
            {
               Tbl_tour_wallet_slot::where("tour_wallet_id", $tour_wallet_id)
                                   ->where("slot_id", $slot_id)
                                   ->update($update);
            }
            else
            {
                Tbl_tour_wallet_slot::insert($update);
            }

            return $status;
        }
        else
        {
            return $status;
        }
    }
}

