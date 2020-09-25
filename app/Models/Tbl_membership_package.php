<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_membership_package extends Model
{
    //
    protected $table = 'tbl_membership_package';
	protected $primaryKey = "membership_package_id";
    public $timestamps = false;
    public function scopeMembership($query)
    {
        $query->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_membership_package.membership_id');
	    return $query;
    }
}
