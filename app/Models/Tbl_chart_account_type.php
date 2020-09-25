<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_chart_account_type extends Model
{
	protected $table = 'tbl_chart_account_type';
	protected $primaryKey = "account_type_id";
    public $timestamps = true;

	// [INTEGER] 		account_id
	// [INTGER] 		account_shop_id
	// [INTGER] 		account_type_id
	// [VARCHAR] 		account_number
	// [VARCHAR] 		account_name
	// [VARCHAR] 		account_full_name
	// [VARCHAR] 		account_description
	// [INTEGER] 		account_parent_id
	// [INTEGER] 		account_sublevel
	// [DOUBLE] 		account_balance
	// [DOUBLE] 		account_open_balance
	// [DATE] 			account_open_balance_date
	// [TINY INT] 		is_tax_account
	// [INTEGER] 		account_tax_code_id
	// [TINY INTEGER] 	archived
	// [DATE TIME] 		account_timecreated
	// [TINY INTEGER] 	account_protected
	// [VARCHAR] 		account_code

	
}