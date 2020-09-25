<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_customer;
use App\Models\Tbl_vendor;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_shop;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_ec_order;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_list;
use App\Globals\Accounting;
use App\Globals\Account;
use App\Globals\Invoice;
use App\Globals\Category;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Ecom_Product;
use App\Globals\Sms;
use App\Globals\PayrollJournalEntries;
use App\Globals\Payroll;
use App\Globals\Report;
use App\Globals\Cart2;
use App\Globals\AccountingTransaction;
use App\Globals\AdminNotification;
use App\Globals\Utilities;
use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use App\Globals\Transaction;
use App\Globals\Payment;
use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Crypt;
use DB;

class TesterController extends Controller
{
    public function getTestingPayment()
    {
        $from = 'paymaya';
        $data["requestReferenceNumber"] = 1;
        $data["paymentStatus"] = "PAYMENT_SUCCESS";
        dd(Payment::done($data, $from));
    }

    public function connection_test()
    {
        $_test = DB::table("tbl_connection_test")->orderBy("connection_test_id", "desc")->limit(5)->get();
        foreach($_test as $test)
        {
            echo "<hr>";
            echo "From (" . $test->connection_test_id . ") " . $test->ip_address . "<br>";
            
            echo "<pre>";
            print_r(unserialize($test->test_data_serialize));
            echo "</pre>";
        }
    }
    public function check_notif()
    {
        AdminNotification::check_notification();
    }
        
    public function test_login()
    {
        $fb = new Facebook([
          'app_id' => '898167800349883', // Replace {app-id} with your app id
          'app_secret' => '94e57cf8f55689c4ccb69dea45532e20',
          'default_graph_version' => 'v2.2',
          ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
          $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if (! isset($accessToken)) {
          if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('{app-id}'); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
          // Exchanges a short-lived access token for a long-lived one
          try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
          } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
            exit;
          }

          echo '<h3>Long-lived</h3>';
          var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;
        dd($_SESSION['fb_access_token']);
        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');
        return redirect('/member');
    }
    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public function getDecrypt($password, $code)
    {
        if($password == "water123")
        {
            dd(Crypt::decrypt($code));
        }
    }

    public function getSms($recipient)
    {
        dd(Sms::SendSingleText($recipient,"Test","success_register"));
    }

    public function getIndex()
    {  
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.aftership.com/v4/couriers/all",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => [],
            CURLOPT_HTTPHEADER => array(
                "aftership-api-key: 118485a6-ed28-4200-a924-ee42e5019b47",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        dd(json_decode($response));
    }

    public function getPostTracking()
    {
        dd(Accounting::getAllAccount());
    }

    public function getTracking()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.aftership.com/v4/trackings",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => [],
            CURLOPT_HTTPHEADER => array(
                "aftership-api-key: 118485a6-ed28-4200-a924-ee42e5019b47",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        dd(json_decode($response));

    }

    public function getJournal()
    {
        $data['tbl_journal_entry'] = Accounting::getJounalAll();
    
        return view('member.tester_journal', $data);
    }

    public function getCustomer()
    {
        $shop_id = 1;
        $data["customer_first_name"] = "Bryan";
        dd(Customer::createCustomer($shop_id, $data));
    }

    public function getPayroll()
    {
        
    }
    public function samptest()
    {
        
       
        if(Request::isMethod("post"))
        {
            $_customer  = Tbl_customer::where("archived", 0);

            if(Request::input("sort") != "")
            {
                $_customer->orderBy(Request::input("sort"));
            }

            if(Request::input("shop_id") != 0)
            {
                $_customer->where("shop_id", Request::input("shop_id"));
            }

            $data["_customer"] = $_customer->paginate(5);

            return view("errors.test_table", $data);
        }
        else
        {
            $data["_shop"] = Tbl_shop::get();
            return view("errors.test", $data);
        }
        
    }
}