<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_mlm_slot extends Model
{
	protected $table = 'tbl_mlm_slot';
	protected $primaryKey = "slot_id";
    public $timestamps = false;
    
    public function scopeId($query, $slot_id)
    {
        return $query->where("slot_id", $slot_id);
    }

    public function scopeBank($query)
    {
        $query->leftJoin("tbl_mlm_slot_bank", "tbl_mlm_slot_bank.slot_id", "=", "tbl_mlm_slot.slot_id");
    }
    public function scopeAirline($query)
    {
        $query->leftJoin("tbl_tour_wallet_slot", "tbl_tour_wallet_slot.slot_id", "=", "tbl_mlm_slot.slot_id");
        $query->leftJoin("tbl_tour_wallet", "tbl_tour_wallet.tour_wallet_id", "=", "tbl_tour_wallet_slot.tour_wallet_id");
    }
    public function scopeVmoney($query)
    {
        $query->leftJoin("tbl_vmoney_settings", "tbl_vmoney_settings.slot_id", "=", "tbl_mlm_slot.slot_id");
    }
    public function scopeMoney_remittance($query)
    {
        $query->leftJoin("tbl_mlm_slot_money_remittance", "tbl_mlm_slot_money_remittance.slot_id", "=", "tbl_mlm_slot.slot_id");
    }
    public function scopeCoinsph($query)
    {
        $query->leftJoin("tbl_mlm_slot_coinsph", "tbl_mlm_slot_coinsph.slot_id", "=", "tbl_mlm_slot.slot_id");

    }
    public function scopeMembership($query)
    {
        $query->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership');
	    return $query;
    }
    public function scopeMembership_points($query)
    {
        $query->leftjoin('tbl_membership_points', 'tbl_membership_points.membership_id', '=', 'tbl_membership.membership_id');
        return $query;
    }
    public function scopeMembershipcode($query)
    {
        $query->leftjoin('tbl_membership_code', 'tbl_membership_code.slot_id', '=', 'tbl_mlm_slot.slot_id');
        return $query;
    }
    public function scopeCurrentWallet($query)
    {
        $query->select("*",
            DB::raw("(select sum(wallet_log_amount) from tbl_mlm_slot_wallet_log where tbl_mlm_slot_wallet_log.wallet_log_slot = tbl_mlm_slot.slot_id) AS current_wallet"),
            DB::raw("(select sum(wallet_log_amount) from tbl_mlm_slot_wallet_log where tbl_mlm_slot_wallet_log.wallet_log_slot = tbl_mlm_slot.slot_id AND wallet_log_amount > 0) AS total_earnings"),
            DB::raw("(select sum(wallet_log_amount) from tbl_mlm_slot_wallet_log where tbl_mlm_slot_wallet_log.wallet_log_slot = tbl_mlm_slot.slot_id AND wallet_log_amount < 0) AS total_payout")
        );
    }
    public function scopeMlm_points($query)
    {
        $query->leftjoin("tbl_mlm_slot_points_log","tbl_mlm_slot_points_log.points_log_slot","=","tbl_mlm_slot.slot_id");
    }
    public function scopeShop($query, $shop_id)
    {
        return $query->where("shop_id", $shop_id);
    }
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner');
        
        // ->where('tbl_customer_address.purpose', 'billing');

        // ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        // ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_slot.slot_owner')
	    return $query;
    }
    public function scopeCustomerv2($query)
    {
        $query->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner');
        
        // ->where('tbl_customer_address.purpose', 'billing');

        // ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        // ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        return $query;
    }
    public function scopeInfo($query)
    {
        $query->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_customer.customer_id');
        return $query;
    }
    public function scopePriceLevel($query, $item_id)
    {
        $query->leftJoin('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
              ->leftJoin('tbl_price_level', 'tbl_price_level.price_level_id', '=', 'tbl_membership.membership_price_level')
              ->leftJoin('tbl_price_level_item', 'tbl_price_level_item.price_level_id', '=', 'tbl_price_level.price_level_id')
              ->leftJoin('tbl_item', 'tbl_item.item_id', '=', 'tbl_price_level_item.item_id')
              ->where("tbl_price_level_item.item_id", $item_id);
        return $query;
    }
}
