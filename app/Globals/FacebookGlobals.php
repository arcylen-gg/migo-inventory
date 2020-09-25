<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_user;

use Carbon\carbon;
use DB;
use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use App\Globals\SocialNetwork;
/**
 * Chart of Account Module - all account related module
 *
 * @author ARCY
 */

class FacebookGlobals
{
    public static function check_app_key($shop_id)
    {  
      $return = false;

      $get = SocialNetwork::get_keys($shop_id, 'facebook');
      if($get)
      {
        $return = true;
      } 
      return $return;     
    }
    public static function get_data($shop_id = 0)
    {      
       $get_keys = SocialNetwork::get_keys($shop_id, 'facebook');
       $fb = new Facebook([
          'app_id' => $get_keys['app_id'], // Replace {app-id} with your app id
          'app_secret' => $get_keys['app_secret'],
          'default_graph_version' => 'v2.1'
          ]);
       return $fb;
    }
    public static function get_data_session($shop_id)
    {      
       session_start(); 
       $get_keys = SocialNetwork::get_keys($shop_id, 'facebook');
       $fb = new Facebook([
          'app_id' => $get_keys['app_id'], // Replace {app-id} with your app id
          'app_secret' => $get_keys['app_secret'],
          'persistent_data_handler'=>'session'
          ]);
       return $fb;
    }
	  public static function get_link($shop_id)
    {
    	  $fb = Self::get_data($shop_id);
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/members/login-submit', array(
   'scope' => 'email'));
        $login_url = htmlspecialchars($loginUrl);
        
        return $login_url;
    }
    public static function get_link_register($shop_id)
    {
        $fb = Self::get_data($shop_id);
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/members/register-submit', array(
   'scope' => 'email'));
        $login_url =  htmlspecialchars($loginUrl);

        return $login_url;
    }
    public static function get_facebook_session($shop_id)
    {     
        $fb = Self::get_data_session($shop_id);

        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state'] = isset($_GET['state']) ? $_GET['state'] : null;

        try 
        {
          $accessToken = $helper->getAccessToken();
        } 
        catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } 
        catch(Facebook\Exceptions\FacebookSDKException $e) 
        {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $return = null;
        if (! isset($accessToken)) 
        {
          if ($helper->getError()) 
          {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } 
          else 
          {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }
        if($accessToken->getValue())
        {
            $return = true;
        }

        return $return;

    }
    public static function user_profile($shop_id)
    {
        $fb = Self::get_data_session($shop_id);

        $helper = $fb->getRedirectLoginHelper();
        $_SESSION['FBRLH_state'] = isset($_GET['state']) ? $_GET['state'] : null;

        $accessToken = null;
        try 
        {
          $accessToken = $helper->getAccessToken();
        } 
        catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } 
        catch(Facebook\Exceptions\FacebookSDKException $e) 
        {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if($accessToken)
        {          
          try 
          {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,email,first_name,middle_name,last_name,gender,locale,age_range,picture', $accessToken);
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }


          if (! isset($accessToken)) 
          {
            if ($helper->getError()) 
            {
              header('HTTP/1.0 401 Unauthorized');
              echo "Error: " . $helper->getError() . "\n";
              echo "Error Code: " . $helper->getErrorCode() . "\n";
              echo "Error Reason: " . $helper->getErrorReason() . "\n";
              echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } 
            else 
            {
              header('HTTP/1.0 400 Bad Request');
              echo 'Bad request';
            }
            exit;
          }

          // Logged in
          // echo '<h3>Access Token</h3>';
          // var_dump($accessToken->getValue());

          // The OAuth 2.0 client handler helps us manage access tokens
          $oAuth2Client = $fb->getOAuth2Client();

          // Get the access token metadata from /debug_token
          $tokenMetadata = $oAuth2Client->debugToken($accessToken);
          // echo '<h3>Metadata</h3>';
          // $test = var_dump($tokenMetadata);

          $get_keys = SocialNetwork::get_keys($shop_id, 'facebook');
           // Validation (these will throw FacebookSDKException's when they fail)
          $tokenMetadata->validateAppId($get_keys['app_id']); // Replace {app-id} with your app id
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
            // var_dump($accessToken->getValue());
          }

          $_SESSION['fb_access_token'] = (string) $accessToken;
          // User is logged in with a long-lived access token.
          // You can redirect them to a members-only page.
          //header('Location: https://example.com/members.php')

          $user = $response->getGraphUser();
          // dd($user);
          return $user;

        }
        else
        {
          return null;
        }
    }
}