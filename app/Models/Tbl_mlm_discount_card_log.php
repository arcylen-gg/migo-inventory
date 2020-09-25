<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_discount_card_log extends Model
{
	protected $table = 'tbl_mlm_discount_card_log';
	protected $primaryKey = "discount_card_log_id";
    public $timestamps = false;

    public function scopeMembership($query)
    {
    	return $query->join('tbl_membership', 'tbl_membership.membership_id', '=','tbl_mlm_discount_card_log.discount_card_membership');
    }
}