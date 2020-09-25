<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_binary_pairing;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_indirect_setting;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;

class Mlm_complan_manager_fs
{   
    public static function direct()
    {

    }
    public static function indirect()
    {

    }
    public static function binary()
    {
        
    }
}