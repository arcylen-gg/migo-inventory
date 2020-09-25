<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_image;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_ec_variant;
use App\Models\Tbl_ec_variant_image;
use App\Models\Tbl_variant_name;
use App\Models\Tbl_option_name;
use App\Models\Tbl_option_value;
use App\Models\Tbl_item;
use App\Models\Tbl_product;
use App\Models\Tbl_user;

use App\Globals\Variant;
use App\Globals\Item;
use App\Globals\Ecom_Product;
use App\Globals\Category;
use App\Globals\Utilities;
use App\Globals\Warehouse;

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
 * Accounting Settings Module - all settings related to accounting module
 *
 * @author Bryan Kier Aradanas
 */

class AccountingSettingController extends Member
{
	public function getIndex()
	{
		return view('member.accounting.setting.accounting_setting');
	}
}