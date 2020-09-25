<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_chart_of_account extends Model
{
	protected $table = 'tbl_chart_of_account';
	protected $primaryKey = "account_id";

    public $timestamps = true;
    
    public function scopeAccountInfo($query, $shop)
    {
        return $query->join('tbl_chart_account_type','account_type_id','=','chart_type_id')
              ->join('tbl_shop','shop_id','=','account_shop_id')
              ->where( function ($where) use($shop)
              {
                    $where->where("shop_id", $shop)
                          ->orWhere("shop_key", $shop);
              });
    }

    public function scopeAccountType($query)
    {
      return $query->join('tbl_chart_account_type','account_type_id','=','chart_type_id');
    }

    public function scopegetbytype($query, $shop_id, $account_type_id = array())
    {
        // $query->where('account_shop_id', $shop_id)
        //       ->whereIn('account_type_id', $account_type_id);
        $query->where('account_shop_id', $shop_id);
        return $query;
    }

    public function scopeType($query)
    {
      return $query->join('tbl_chart_account_type','chart_type_id','=','account_type_id');
    } 

    public function scopeBalance($query, $from = null, $to = null)
    {
      $balance = DB::table("tbl_journal_entry_line")->join("tbl_chart_of_account as coa", "account_id", "=", "jline_account_id")
                                           ->join("tbl_chart_account_type","chart_type_id", "=", "account_type_id")
                                           ->whereRaw("tbl_chart_of_account.account_id = jline_account_id")
                                           ->whereRaw("account_shop_id = account_shop_id")
                                           ->whereRaw("chart_type_name NOT IN ('Income', 'Other Income', 'Expense', 'Other Expense', 'Cost of Goods Sold')")
                                           ->selectRaw("SUM( CASE jline_type WHEN normal_balance then jline_amount ELSE -jline_amount END)")
                                           ->toSql();

      return $query->selectRaw("*, IFNULL(($balance), 0) as balance");
    }
}