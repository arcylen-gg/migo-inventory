<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_paper_sizes extends Model
{
    protected $table = 'tbl_payroll_paper_sizes';
	protected $primaryKey = "payroll_paper_sizes_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] payroll_paper_sizes_id
	// [INTEGER] shop_id
	// [VARCHAR] paper_size_name
	// [DOUBLE]  paper_size_width
	// [DOUBLE]  paper_size_height
	// [INTEGER] paper_archived

    public function scopegetpaper($query ,$shop_id = 0, $paper_archived = 0)
    {
    	$query->where('shop_id',$shop_id)->where('paper_archived', $paper_archived);
    	
    	return $query;
    }
}
