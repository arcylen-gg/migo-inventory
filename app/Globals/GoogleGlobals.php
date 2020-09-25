<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;


use App\Globals\SocialNetwork;
use Carbon\carbon;
use DB;
use Google_Client; 
use Google_Service_Drive;
use Google_Service_Plus;


/**
 * 
 *
 * @author ARCY
 */

class GoogleGlobals
{


    public static function check_app_key($shop_id)
    {  
      $return = false;

      $get = SocialNetwork::get_keys($shop_id, 'googleplus');
      if($get)
      {
        $return = true;
      } 
      return $return;     
    }
	public static function get_test($shop_id = 0)
	{
		$get_key = SocialNetwork::get_keys($shop_id, 'googleplus');
		$client = new Google_Client();
		$client->setApplicationName("My Application");
		$client->setDeveloperKey($get_key['app_id']);

		dd($client);
	}
	public static function revoke_access($shop_id)
	{
		session_start();
		if(isset($_SESSION['access_token']))
		{
			$get_key = SocialNetwork::get_keys($shop_id, 'googleplus');
			$client = new Google_Client();
			$client->setApplicationName("My Application");
			$client->setDeveloperKey($get_key['app_id']);
			$client->revokeToken($_SESSION['access_token']);
		} 

		session_destroy();   

	}
	public static function test()
	{
		session_start();

		$client_id = '431988284265-f8brg2nuvhmmgs3l5ip8bdogj62jkidp.apps.googleusercontent.com';
		$client_secret = '1hArs-eRANIXj1uaubhajbu8';
		$redirect_uri = 'http://myphone.digimahouse.dev/member';

		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->addScope("https://www.googleapis.com/auth/plus/login");
		$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
		$client->addScope("https://www.googleapis.com/auth/userinfo.email");

		$plus = new Google_Service_Plus($client);
		if (isset($_REQUEST['logout'])) 
		{
		    unset($_SESSION['access_token']);
		}
		if (isset($_GET['code'])) 
		{
		    $client->authenticate($_GET['code']);
		    $_SESSION['access_token'] = $client->getAccessToken();
		    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));

		}

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		    $client->setAccessToken($_SESSION['access_token']);
		    $_SESSION['token'] = $client->getAccessToken();

		} else {
		    $authUrl = $client->createAuthUrl();
		}

		if (isset($authUrl)) 
		{
    		print "<a class='login' href='$authUrl'><img src='logogoo/Red-signin-Medium-base-32dp.png'></a>";
		} 
		else 
		{
    		print "<a class='logout' href='pruebas.php?logout'>Cerrar:</a>";
		}
		if (isset($_SESSION['access_token']))
		{
		    $me = $plus->people->get("me");

		    print "<br>ID: {$me['id']}\n<br>";
		    print "Display Name: {$me['displayName']}\n<br>";
		    print "Image Url: {$me['image']['url']}\n<br>";
		    print "Url: {$me['url']}\n<br>";
		    $name3 = $me['name']['givenName'];
		    echo "Nombre: $name3 <br>"; //Everything works fine until I try to get the email
		    $correo = ($me['emails'][0]['value']);
		    echo $correo;
		}
		dd($correo);
	}
}