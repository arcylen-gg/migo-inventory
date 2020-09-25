<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_item_points extends Model
{
	protected $table = 'tbl_mlm_item_points';
	protected $primaryKey = "item_points_id";
    public $timestamps = false;

    public function scopeJoinItem($query)
    {
        $query->join('tbl_item','tbl_mlm_item_points.item_id','=','tbl_item.item_id');
        return $query;
    }
    public function scopeJoinMembership($query)
    {
        $query->join('tbl_membership','tbl_mlm_item_points.membership_id','=','tbl_membership.membership_id');
        $query->where('tbl_membership.membership_archive', 0);
        return $query;
    }
}