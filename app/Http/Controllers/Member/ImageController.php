<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_image;
use Carbon\Carbon;
use Request;
use Image;
use Validator;
use Redirect;
use File;
use URL;
use DB;
use Crypt;
use Session;
use Response;
use Input;
use Storage;

class ImageController extends Member
{
	public function index()
	{

	}

	public function upload_image()
	{
		$shop_id 	= $this->user_info->shop_id;
		$shop_key	= $this->user_info->shop_key;

		/* SAVE THE IMAGE IN THE FOLDER */
		$file 				= Input::file('file');
		$extension 			= $file->getClientOriginalExtension();
		//$filename 		= $file->getClientOriginalName();
		$filename 			= str_random(15).".".$extension;
		$destinationPath 	= 'uploads/'.$shop_key."-".$shop_id;

		// if(!File::exists($destinationPath)) 
		// {
		// 	$create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
		// }
		// $upload_success    = Input::file('file')->move($destinationPath, $filename);
		/* SAVE THE IMAGE PATH IN THE DATABASE */
		// $image_path = $destinationPath."/".$filename;

		$image_path = Storage::putFile($destinationPath, Input::file('file'));

		if ($image_path) 
		{
			$upload_success = true;
		}
		else
		{
			$upload_success = false;
		}

		$insert_image["image_path"] 		= "/" . $image_path; 
		$insert_image["image_shop"] 		= $this->user_info->shop_id;
		$insert_image["image_reason"] 		= "product";
		$insert_image["image_reason_id"] 	= 0;
		$insert_image["image_date_created"] = Carbon::now();
		$insert_image["image_key"] 			= uniqid();
		$image_id = Tbl_image::insertGetId($insert_image);

		if( $upload_success ) 
		{
		   return Response::json('success', 200);
		} 
		else 
		{
		   return Response::json('error', 400);
		}
	}

	public function load_media_library()
	{
		// $data['_image'] = Tbl_image::where("image_shop", $this->user_info->shop_id)->get();
		$get_only_exist = [];
		$remote_server = Storage::files('/uploads/' . $this->user_info->shop_key . '-' . $this->user_info->shop_id);
		// foreach ($data['_image'] as $key => $value) 
		// {
		// 	if(File::exists(public_path().$value->image_path))
		// 	{
		// 		$get_only_exist[$key] = $value;
		// 	}
		// 	elseif(Storage::disk('ftp')->exists($value->image_path))
		// 	{
		// 		$get_only_exist[$key] = $value;
		// 	}
		// }
		foreach ($remote_server as $key => $value) 
		{
			$image = Tbl_image::where("image_path", '/' . $value)->first();
			$get_only_exist[$key] = $image;
		}
		$data['_image'] = $get_only_exist;

		usort($data['_image'], function($a, $b) 
		{
		    return $b['image_date_created'] <=> $a['image_date_created'];
		});

		return view('member.modal.load_media_library', $data);
	}
}