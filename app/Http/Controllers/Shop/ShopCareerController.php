<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Mail;
use Input;

class ShopCareerController extends Shop
{
    public function index()
    {
    	if(Request::isMethod("post"))
    	{
    		$input = Input::all();
    		$data["page"] = "Career - Send E-Mail";
    		$data["name"] = Request::input("name");
    		$data["email"] = Request::input("mail");
    		$data["contact"] = Request::input("number");
    		$data["position"] = Request::input("position");
    		$data["messages"] = Request::input("message");
    		
            if ($input['resume']->getRealPath() && $input['resume']->getClientOriginalExtension() && $input['resume']->getMimeType()) 
            {
                Mail::send('career_mail', $data, function ($message)
                {
                    $input = Input::all();

                    $message->subject("NEW APPLICANT FOR INTOGADGETS");
                    $message->from('no-reply@philtechglobalinc.com', Request::input("name"));
                    $message->to('info@intogadgets.com.ph')->cc('gtplus.net@gmail.com');
                    $message->attach($input['resume']->getRealPath(), array(
                        'as' => 'resume.' . $input['resume']->getClientOriginalExtension(), 
                        'mime' => $input['resume']->getMimeType()));
                });
            }
			else
            {
                dd("Some error occurred. Please try again later.");
            }

    		return Redirect::to("/career/success");
    	}
    	else
    	{
	        $data["page"] = "Career";
	        return view("career", $data);
    	}
    }
    public function success()
    {
    	$data["page"] = "Success";
    	return view("career_success", $data);
    }
}