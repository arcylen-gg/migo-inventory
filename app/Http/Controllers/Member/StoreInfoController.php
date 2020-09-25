<?php
namespace App\Http\Controllers\Member;
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
use App\Models\Tbl_contact;
use App\Models\Tbl_location;
use Crypt;
use Session;


class StoreInfoController extends Member
{
	public function storeInfo()
	{	
		$shop_id = $this->checkuser('user_id');
		$data['_about'] = Tbl_about_us::where('shop_id', $shop_id)->where('archived',0)->get();
		
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
		return Redirect('/member/about');
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
    	
    	return Redirect('/member/about');
    	
    }
    public function remove($id){
    	$id = Crypt::decrypt($id);
    	$update['archived'] = 1;
    	Tbl_about_us::where('about_us_id',$id)->update($update);
    	return Redirect('/member/about');
    }
    
    // contact start
    public function contactInfo()
	{
		$data['_contact'] = Tbl_contact::where('shop_id',$this->checkuser('user_id'))->where('archived',0)->get();
		$data['_location'] = Tbl_location::where('shop_id', $this->checkuser('user_id'))->where('archived',0)->get();
		return view('member.store.contact',$data);
	}
	
    public function createContact(){
    	$shop_id = $this->checkuser('user_id');
    	$insert['shop_id'] = $shop_id;
    	$insert['category'] = Request::input('category');
    	$insert['contact'] = Request::input('new_contact');
    	$insert['date_created'] = Carbon::now();
    	$id = Tbl_contact::insertGetId($insert);
    	$data['contact'] = Tbl_contact::where('contact_id',$id)->first();
    	return view('member.store.contactNew',$data);
    }
    public function loadContact(){
    	$id = Request::input('content');
    	$data['contact'] = Tbl_contact::where('contact_id',$id)->first();
    	return view('member.store.contactLoad',$data);
    }
    public function remContact($id){
    	$update['archived'] = 1;
    	Tbl_contact::where('contact_id',$id)->update($update);
    	return Redirect('/member/contact');
    }
    public function updateContact(){
    	$shop_id = $this->checkuser('user_id');
		$update_category = Request::input('update_category');
		$update_contact = Request::input('update_contact');
		$content = Request::input('content');
		$count = Tbl_contact::where('shop_id',$shop_id)->where('archived',0)->where('contact_id','!=',$content)->where('contact',$update_contact)->count();
		if($count == 0){
			$update['category'] = $update_category;
			$update['contact'] = $update_contact;
			Tbl_contact::where('contact_id',$content)->update($update);
			return 'success';
		}
		else{
			return 'exist';
		}
    }
    
    public function displaycontact(){
    	$content = Request::input('content');
		$num = Request::input('num');
		$update['primary'] = $num;
		Tbl_contact::where('contact_id', $content)->update($update);
		$str = '';
		if($num == 0){
			$str = 'Contact has been hidden';	
		}
		else{
			$str = 'Contact has been displayed.';	
		}
		return $str;
    }
    
    public function createLocation(){
    	$locations = Request::input('locations');
    	$shop_id = $this->checkuser('user_id');
    	$insert['shop_id'] = $shop_id;
    	$insert['location'] = $locations;
    	$insert['date_created'] = Carbon::now();
    	$id = Tbl_location::insertGetId($insert);
    	$data['location'] = Tbl_location::where('location_id',$id)->first();
    	return view('member.store.newlocation',$data);
    }
    public function loadlocation(){
    	$id = Request::input('content');
    	$data['location'] = Tbl_location::where('location_id',$id)->first();
    	return view('member.store.loadlocation',$data);
    }
    public function removeLocation($id){
    	$update['archived'] = 1;
    	Tbl_location::where('location_id',$id)->update($update);
    	return Redirect('/member/contact');
    }
    public function updateLocation(){
    	$locations = Request::input('locations');
		$content = Request::input('content');
		$update['location'] = $locations;
		Tbl_location::where('location_id',$content)->update($update);
    }
    public function setPrimary(){
    	$id = Request::input('val');
    	$shop_id = $this->checkuser('user_id');
    	$updateAll['primary'] = 0;
    	Tbl_location::where('shop_id', $shop_id)->update($updateAll);
    	$update['primary'] = 1;
    	Tbl_location::where('location_id',$id)->update($update);
    }
}