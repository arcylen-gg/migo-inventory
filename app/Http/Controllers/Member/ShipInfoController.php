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
use App\Models\Tbl_shipping;
use App\Models\Tbl_user;
use Session;

class ShipInfoController extends Member
{
	public function index(){
		$shop_id = $this->checkuser('user_shop');
		$data['_shipping'] = Tbl_shipping::where('shop_id', $shop_id)->where('archived',0)->orderBy('shipping_name','asc')->get();
	    return view('member.shipping.index',$data);
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
    public function create(){
        $shipping_name = Request::input('shipping_name');
        $shipping_contact = Request::input('shipping_contact');
        $measurement = Request::input('measurement');
        $unit = Request::input('unit');
        $currency = Request::input('currency');
        $fee = Request::input('fee');
        
        $count = Tbl_shipping::where('shop_id',$this->checkuser('user_shop'))->where('shipping_name',$shipping_name)->where('archived',0)->count();
        
        
        
        $insert['shop_id'] = $this->checkuser('user_shop');
        $insert['shipping_name'] = $shipping_name;
        $insert['shipping_fee'] = $fee;
        $insert['currency'] = $currency;
        $insert['measurement'] = $measurement;
        $insert['unit'] = $unit;
        $insert['contact'] = $shipping_contact;
        if($count == 0){
            $id = Tbl_shipping::insertGetId($insert);
            $data['ship'] = Tbl_shipping::where('shipping_id',$id)->first();
            return view('member.shipping.newshipping',$data);
        }
        else{
            return 'exist';
        }
    }
    public function load(){
        
        $id = Request::input('id');
        Session::put('shipping_id',$id);
        $data['ship'] = Tbl_shipping::where('shipping_id',$id)->first();
        return view('member.shipping.shippingload',$data);
    }
    public function remove(){
        if(Session::has('shipping_id')){
            $shipping_id = Session::get('shipping_id');
            $update['archived'] = 1;
            Tbl_shipping::where('shipping_id',$shipping_id)->update($update);
            Session::forget('shipping_id');
        }
        return Redirect('/member/shipping');
    }
    public function update(){
        if(Session::has('shipping_id')){
            $shipping_id = Session::get('shipping_id');
            $name = Request::input('name');
            $contact = Request::input('contact');
            $measurement = Request::input('measurement');
            $unit = Request::input('unit');
            $currency = Request::input('currency');
            $fee = Request::input('fee');
            $count = Tbl_shipping::where('shop_id',$this->checkuser('user_shop'))
                                ->where('shipping_name',$name)
                                ->where('shipping_id','!=',$shipping_id)
                                ->count();
            if($count == 0){
                $update['shipping_name'] = $name;
                $update['shipping_fee'] = $fee;
                $update['currency'] = $currency;
                $update['measurement'] = $measurement;
                $update['unit'] = $unit;
                $update['contact'] = $contact;
                Tbl_shipping::where('shipping_id',$shipping_id)->update($update);
            }
            else{
                return 'exist';
            }
        }
        else{
            return 'Error';
        }
    }
}