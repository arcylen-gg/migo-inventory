<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use App\Models\Tbl_voucher;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_membership_code;
use Crypt;
use Redirect;
use DB;
// use Request;
use View;
use App\Globals\abs\AbsMain;
use App\Models\Tbl_mlm_gc;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\Tbl_tour_wallet;
use App\Globals\Mlm_slot_log;
use Carbon\Carbon;
class MlmWalletAbsController extends Mlm
{
    public function index()
    {
        $data = [];

        $data['account_tours'] = Tbl_tour_wallet::where('tour_wallet_customer_id', Self::$customer_id)
        ->where('tour_wallet_main', 0)
        ->first();

        $data['logs'] = DB::table('tbl_tour_wallet_logs')
        ->where('tour_wallet_logs_customer_id', Self::$customer_id)
        ->get();

        return view('mlm.tours.index', $data);
    }
    public function transfer()
    {
        $headers = ['Connection'=> 'keep-alive', 
                    'Content-Type' => 'application/x-www-form-urlencoded', 
                    'Credentials' => '{"account_id":"ABS-0019","username":"philtech","password":"CxxApx6CsrK6r2yr"}'   
                        
                    ,'contents' => '', 'name' => ''];
        // $headers = ['multipart' => ['name' => [ 'contents' => '', 'name' => '' ],'contents'=> [ 'contents' => '', 'name' => ''], 'headers' => $headers], 'headers' => $headers];
        $headers = ['multipart' => ['headers' => $headers], 'headers' => $headers];
        // dd($headers);
        $client = new Client([
            'base_uri' => 'http://staging.tripoption.tours',
            'timeout'  => 10.0,
        ], $headers);

        // $response = $client->request('GET', '/api/wallet/ABS-0019', $headers);

        try 
        {
            // $request =  $client->request('GET', '/api/wallet/ABS-0019', $headers);
            // $response = $client->send($request, ['timeout' => 10]);
            $request = new Request('GET', 'http://staging.tripoption.tours/api/wallet/ABS-0019');
            $response = $client->send($request, $headers);
            $body = $response->getBody();
            $data = $body->read(1024);
            $data_decoded = json_decode($data);
            dd($data_decoded);

            $promise = $client->getAsync('/api/wallet/ABS-0019', $headers);
            $message = '';
            $promise->then(
                function (ResponseInterface $res) {
                    echo $res->getStatusCode() . "\n";
                    $message = $e->getStatusCode();
                },
                function (RequestException $e) {
                    echo $e->getMessage() . "\n";
                    $message = $e->getMessage();
                    echo $e->getRequest()->getMethod();
                }
            );
            // dd($message);
        }
        catch (\Exception $e) 
        {
            return 'Caught exception: ' .  $e->getMessage();
        }
    }
    public function get_balance()
    {
        $base_uri = 'http://staging.tripoption.tours';
        $base_account_id = 'ABS-0019';
        $base_user_name = 'philtech';
        $base_password = 'CxxApx6CsrK6r2yr';

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
            $data_a['result'] = $data_decoded->result;
        }
        catch (\Exception $e) 
        {
            $data_a['status'] = 0;
            $data_a['message'] = 'Caught exception: ' .  $e->getMessage();
        }
        return $data_a;
    }

    public function update_info()
    {
        // return $_POST;
        $tour_Wallet_a_account_id = $_POST['tour_Wallet_a_account_id'];
        // $tour_wallet_a_username = $_POST['tour_wallet_a_username'];
        // $tour_wallet_a_base_password = $_POST['tour_wallet_a_base_password'];
        $base_uri = Self::$shop_info->shop_wallet_tours_uri;


        $host = Tbl_tour_wallet::where('tour_wallet_shop', Self::$shop_id)
        ->where('tour_wallet_main', 1)
        ->first();

        $status = AbsMain::get_balance_client($base_uri, $host->tour_Wallet_a_account_id, $host->tour_wallet_a_username, $host->tour_wallet_a_base_password, $tour_Wallet_a_account_id);

        if($status['status'] == 1)
        {
            $count = Tbl_tour_wallet::where('tour_wallet_customer_id', Self::$customer_id)
            ->where('tour_wallet_main', 0)
            ->count();

            $insert['tour_wallet_shop'] = Self::$shop_id; 
            $insert['tour_wallet_customer_id'] = Self::$customer_id;
            $insert['tour_wallet_user_id'] = 0;
            $insert['tour_Wallet_a_account_id'] = $tour_Wallet_a_account_id;
            // $insert['tour_wallet_a_username'] = $tour_wallet_a_username;
            // $insert['tour_wallet_a_base_password'] = $tour_wallet_a_base_password;
            $insert['tour_wallet_a_current_balance'] = $status['result'];
            $insert['tour_wallet_main'] = 0;
            $insert['tour_wallet_block'] = 0; 

            if($count == 0)
            {
                Tbl_tour_wallet::insert($insert);
            }
            else
            {
                Tbl_tour_wallet::where('tour_wallet_customer_id', Self::$customer_id)
                ->where('tour_wallet_main', 0)
                ->update($insert);
            }

            return json_encode($status);
        }
        else
        {
            return json_encode($status);
        }
        
    }

    public function transfer_wallet()
    {
        

        $wallet_amount = $_POST['wallet_amount'];
        $password = $_POST['password'];
        $password_dec = Crypt::decrypt(Self::$customer_info->password);

        if($password == $password_dec)
        {
            $sum_wallet = Mlm_slot_log::get_sum_wallet(Self::$slot_id);
            if($sum_wallet == null)
            {

                $status['status'] = 0;
                $status['message'] = 'Insuficient Wallet Amount';
            }
            if($sum_wallet >= $wallet_amount)
            {
                $tour = Tbl_tour_wallet::where('tour_wallet_customer_id', Self::$customer_id)
                ->where('tour_wallet_main', 0)
                ->first();

                if(!$tour)
                {
                    $status['status'] = 0;
                    $status['message'] = 'Please Setup Your Account Id';
                    return json_encode($status);
                }
                $host = Tbl_tour_wallet::where('tour_wallet_shop', Self::$shop_id)
                ->where('tour_wallet_main', 1)
                ->first();

                $base_uri = Self::$shop_info->shop_wallet_tours_uri;
                $status = AbsMain::transfer_wallet($base_uri, $host->tour_Wallet_a_account_id, $host->tour_wallet_a_username, $host->tour_wallet_a_base_password, $tour->tour_Wallet_a_account_id, $wallet_amount);
                $status['message'] = 'Wallet Transfer Success';
                if($status['status'] == 1)
                {
                    $wallet_nega = $wallet_amount * (-1);
                    $log['shop_id'] = Self::$shop_id;
                    $log['wallet_log_slot'] = Self::$slot_id;
                    $log['wallet_log_slot_sponsor'] = Self::$slot_id;
                    $log['wallet_log_details'] = 'You have transfered ' . $wallet_amount . ' To your tours wallet. ' . $wallet_amount . ' is deducted to your wallet.' ;
                    $log['wallet_log_amount'] = $wallet_nega;
                    $log['wallet_log_plan'] = 'TOURS_WALLET';
                    $log['wallet_log_status'] = 'released';
                    $log['wallet_log_claimbale_on'] = Carbon::now();


                    $insert['tour_wallet_logs_wallet_amount'] =  $wallet_amount;
                    $insert['tour_wallet_logs_date'] = Carbon::now(); 
                    $insert['tour_wallet_logs_tour_id'] = $tour->tour_wallet_id; 
                    $insert['tour_wallet_logs_account_id'] =  $tour->tour_Wallet_a_account_id;
                    $insert['tour_wallet_logs_customer_id'] =  Self::$customer_id;
                    $insert['tour_wallet_logs_accepted'] =  1;
                    $insert['tour_wallet_logs_points'] = 0;

                    
                    // get_balance
                    $host_update = AbsMain::get_balance($base_uri, $host->tour_Wallet_a_account_id, $host->tour_wallet_a_username, $host->tour_wallet_a_base_password);
                    if($host_update['status'] == 1)
                    {
                        $update['tour_wallet_a_current_balance'] = $host_update['result'];
                        Tbl_tour_wallet::where('tour_wallet_shop', Self::$shop_id)
                        ->where('tour_wallet_main', 1)
                        ->update($update);
                    }

                    $update['tour_wallet_a_current_balance'] = $tour->tour_wallet_a_current_balance + $wallet_amount;
                    Tbl_tour_wallet::where('tour_wallet_customer_id', Self::$customer_id)
                    ->where('tour_wallet_main', 0)
                    ->update($update);



                    Mlm_slot_log::slot_array($log);

                    $tour_wallet_convertion = $host->tour_wallet_convertion;

                    if($tour_wallet_convertion != 0)
                    {
                        $points = ($wallet_amount/100) * $tour_wallet_convertion;
                        $insert['tour_wallet_logs_points'] = $points;
                        $l = "You have earned " . $points . " Repurchase Points. From  tours wallet."; 
                        $log['shop_id'] = Self::$shop_id;
                        $log['wallet_log_slot'] = Self::$slot_id;
                        $log['wallet_log_slot_sponsor'] = Self::$slot_id;
                        $log['wallet_log_details'] = $l ;
                        $log['wallet_log_amount'] = 0;
                        $log['wallet_log_plan'] = 'TOURS_WALLET_POINTS';
                        $log['wallet_log_status'] = 'released';
                        $log['wallet_log_claimbale_on'] = Carbon::now();

                        Mlm_slot_log::slot_array($log);

                        $array['points_log_complan'] = "REPURCHASE_POINTS";
                        $array['points_log_level'] = 0;
                        $array['points_log_slot'] = Self::$slot_id;
                        $array['points_log_Sponsor'] = Self::$slot_id;
                        $array['points_log_date_claimed'] = Carbon::now();
                        $array['points_log_converted'] = 0;
                        $array['points_log_converted_date'] = Carbon::now();
                        $array['points_log_type'] = 'PV';
                        $array['points_log_from'] = 'Wallet Tours';
                        $array['points_log_points'] = $points;

                        Mlm_slot_log::slot_log_points_array($array);
                    }
                    DB::table('tbl_tour_wallet_logs')->insert($insert);
                    
                }
                else
                {
                    $status['status'] = 0;
                    $status['message'] = $stat['message'];
                }
                
            }
            else
            {
                $status['status'] = 0;
                $status['message'] = 'Insuficient Wallet Amount';
            }
        }
        else
        {
            $status['status'] = 0;
            $status['message'] = 'Wrong Password';
        }
        

        return json_encode($status);
    }
}