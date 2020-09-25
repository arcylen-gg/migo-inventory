<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Models\Tbl_item;
use App\Models\Tbl_user;

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

/**
 * Item Multiple Price Module - all price according to bundle / qty related module
 *
 * @author Bryan Kier Aradanas
 */

class ItemMultiplePrice extends Member
{
 	public function getIndex()
 	{

 	}

 	public function postIndex()
 	{
 		
 	}
}