<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_journal_tag_entity extends Model
{
    protected $table = 'tbl_payroll_journal_tag_entity';
	protected $primaryKey = "payroll_journal_tag_entity_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] 		payroll_journal_tag_entity_id
	// [INTEGER] 		payroll_journal_tag_id
	// [TINY INTEGER] 	payroll_entity_id


	public function scopecheckentity($query, $payroll_journal_tag_id = 0, $payroll_entity_id = 0)
	{
		$query->where("payroll_journal_tag_id", $payroll_journal_tag_id)->where('payroll_entity_id', $payroll_entity_id);

		return $query;
	}
}
