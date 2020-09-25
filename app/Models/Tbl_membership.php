<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_membership extends Model
{
    protected $table = 'tbl_membership';
	protected $primaryKey = "membership_id";
    public $timestamps = false;

    public function scopeActive($query)
    {
        $query->where("tbl_membership.membership_archive", 0);
    }
    public function scopeShop($query, $shop_id)
    {
        $query->where("tbl_membership.shop_id", $shop_id);
    }
    public function scopeCodes($query, $pin, $activation)
    {
        //dd($pin);
        $query->join("tbl_item", "tbl_item.membership_id", "tbl_membership.membership_id");
        $query->join("tbl_warehouse_inventory_record_log", "tbl_warehouse_inventory_record_log.record_item_id", "tbl_item.item_id");
        $query->whereRaw("REPLACE(tbl_warehouse_inventory_record_log.mlm_pin, '\n','') = '" . $pin . "'");
        $query->where("tbl_warehouse_inventory_record_log.mlm_activation", $activation);
        return $query;
    }
    public function scopeReverseOrder($query)
    {
        $query->orderBy("tbl_membership.membership_id", "desc");
    }
    public function scopeJoinPackage($query)
    {
        $query->join("tbl_membership_package", "tbl_membership_package.membership_id", "=", "tbl_membership.membership_id");
    }
    public function scopeArchive($query, $archive=0)
    {
        $query->where('membership_archive', $archive);
    	return $query;
    }
    public function scopeId($query, $membership_id=0)
    {
        $query->where('membership_id', $membership_id);
    	return $query;
    }
    public function scopeMembership_points($query)
    {
        $query->selectRaw('tbl_membership_points.initial_gc,tbl_membership_points.membership_points_repurchase_cashback_points,tbl_membership_points.direct_referral_self_spv,tbl_membership_points.direct_referral_self_rpv,tbl_membership_points.direct_referral_sgpv,tbl_membership_points.direct_referral_spv,tbl_membership_points.direct_referral_rgpv,tbl_membership_points.stairstep_direct_points,tbl_membership_points.stairstep_direct_points,tbl_membership_points.direct_referral_rpv,tbl_membership_points.membership_points_direct_gc,tbl_membership_points.membership_points_direct_pass_up,tbl_membership_points.membership_direct_income_limit,tbl_membership_points.membership_points_repurchase, tbl_membership_points.membership_points_direct_not_bonus, tbl_membership_points.membership_points_leadership, tbl_membership_points.membership_points_binary, tbl_membership_points.membership_points_executive, tbl_membership_points.membership_points_binary_max_pair, tbl_membership_points.membership_points_direct, tbl_membership_points.membership_points_initial_points, tbl_membership_points.membership_points_repurchase_cashback, tbl_membership_points.membership_points_binary_limit, tbl_membership_points.membership_points_binary_single_line, tbl_membership_points.membership_points_binary_single_line_limit, tbl_membership_points.membership_points_binary_max_income, tbl_membership_points.membership_points_binary_single_line_level' )

        ->leftjoin('tbl_membership_points', 'tbl_membership_points.membership_id', '=', 'tbl_membership.membership_id');
        return $query;
    }
    public function scopeGetactive($query, $archive=0, $shop_id)
    {
        $query->where('membership_archive', $archive)
        ->leftjoin('tbl_membership_package', 'tbl_membership_package.membership_id', '=', 'tbl_membership.membership_id')
        ->groupBy('tbl_membership.membership_id')
        ->selectRaw('tbl_membership.*,
        COUNT(CASE WHEN tbl_membership_package.membership_package_archive = 0 THEN 1 END) package_count, 
        COUNT(CASE WHEN tbl_membership_package.membership_package_archive = 1  THEN 1 END) package_count_archive')
        ->where('tbl_membership.shop_id', $shop_id)
        ->where('membership_type_a', 0)
        ->orderby('membership_price', 'ASC');
        return $query;
    }
    public function scopeGetbinarypairing($query)
    {
        // tbl_mlm_binary_pairing
        $query->leftjoin('tbl_mlm_binary_pairing','tbl_mlm_binary_pairing.membership_id',"=",'tbl_membership.membership_id');
        return $query;
        
    }
    public function scopeExecutive_settings($query)
    {
        $query->leftjoin('tbl_mlm_complan_executive_settings', 'tbl_mlm_complan_executive_settings.membership_id', '=', 'tbl_membership.membership_id');
        return $query;
    }
}
