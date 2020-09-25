<?php
namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Request;
use Image;
use Validator;
use Redirect;
use File;
use URL;
use App\Models\Tbl_about_us;
use App\Models\Tbl_user;
use Crypt;
use Session;


class StoreInfoController extends Store
{
	public function storeInfo()
	{	
		$shop_id = $this->checkuser('user_id');
		$data['_about'] = Tbl_about_us::where('shop_id', $shop_id)->where('archived',0)->orderBy('title','asc')->get();
		return view('member.store.info',$data);
	}
	

	public function storeDescription()
	{
		$shop_id = $this->checkuser('user_id');
		$title_header = Request::input('title_header');
		$description = Request::input('description');
		$insert['title'] = $title_header;
		$insert['content'] = $description;
		$insert['shop_id'] = $shop_id;
		$insert['date_created'] = Carbon::now();
		Tbl_about_us::insert($insert);
		return Redirect('/member/info');
	}
	
	public function checkuser($str = ''){
        $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        switch ($str) {
            case 'user_id':
                return $user_info->user_id;
                break;
            case 'user_shop':
                return $user_info->user_shop;
                break;
            default:
                return '';
                break;
        }
    }
    
    public function edit($id){
    	$id = Crypt::decrypt($id);
    	Session::put('about_us_id',$id);
    	$data['about'] = Tbl_about_us::where('about_us_id',$id)->first();
    	return view('member.store.editInfo', $data);
    }
    public function update(){
    	if(Session::has('about_us_id')){
    		$id = Session::get('about_us_id');
    		$update['title'] = Request::input('title_header');
    		$update['content'] = Request::input('description');
    		Tbl_about_us::where('about_us_id',$id)->update($update);
    		Session::forget('about_us_id');
    	}
    	
    	return Redirect('/member/info');
    	
    }
    public function remove($id){
    	$id = Crypt::decrypt($id);
    	$update['archived'] = 1;
    	Tbl_about_us::where('about_us_id',$id)->update($update);
    	return Redirect('/member/info');
    }
    
    // contact start
    public function contactInfo()
	{
		return view('member.store.contact');
	}
	
    public function craeteContact(){
    	
    }
}