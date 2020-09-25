<?php                                                             
namespace App\Globals;
use App\Models\Tbl_variant;
use App\Models\Tbl_order;
use App\Models\Tbl_order_item;
use App\Models\Tbl_ec_order_item;
use App\Models\Tbl_ec_order;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_user;
use App\Models\Tbl_category;
use DB;

class SalesReport
{
    public function get_shop_id()
    {
        $shop_info = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');

        return $shop_info->shop_id;
    }

    public static function reportcount($shop_id = 0)
    {
        $data             = array();
        $date             = date_create(date('Y-m-d'));
		$newdate          = date_sub($date,date_interval_create_from_date_string("30 days"));
		$last30days       = date_format($date,'Y-m-d');
		$order_count      = Tbl_order::SelByShop($shop_id, $last30days)->count();
		$customer_count   = Tbl_order::SelByShop($shop_id, $last30days)->where('customer_id','!=', 'null')
							       ->groupBy('customer_id')
							       ->get();
		
		$customer_count   = count($customer_count);
		$strCustomerCount = ' customer';
		
		if($customer_count > 1)
        {
			$strCustomerCount = ' customers';
		}

		$orderstr = ' order';

		if($order_count > 1)
        {
		    $orderstr  = ' orders';
		}

		$variant_count  = Tbl_order::join('tbl_order_item','tbl_order_item.tbl_order_id','=','tbl_order.tbl_order_id')
		                           ->where('tbl_order.shop_id',$shop_id)
						           ->where('tbl_order.craeted_date','>=',$last30days)
						           ->count();

		$strVariant     = ' variant';

		if($variant_count > 1)
        {
		    $strVariant = ' variants';
		}

		$product_count  = Tbl_order::join('tbl_order_item','tbl_order_item.tbl_order_id','=','tbl_order.tbl_order_id')
		                           ->join('tbl_variant','tbl_variant.variant_id','=','tbl_order_item.variant_id')
		                           ->where('tbl_order.shop_id',$shop_id)
						           ->where('tbl_order.craeted_date','>=',$last30days)
						           ->groupBy('tbl_variant.variant_product_id')
						           ->count();

	    $strproduct_count = ' product';

	    if($product_count > 1)
        {
	        $strproduct_count = ' products';
	    }
		$data['customer_count']['num'] = $customer_count;
		$data['customer_count']['str'] = $customer_count.$strCustomerCount;
		$data['order_count']['num']    = $order_count;
		$data['order_count']['str']    = $order_count.$orderstr;
		$data['variant_count']['num']  = $variant_count;
		$data['variant_count']['str']  = $variant_count.$strVariant;
		$data['product_count']['num']  = $product_count;
		$data['product_count']['str']  = $product_count.$strproduct_count;
		return $data;
    }

    public static function monthlysale($shop_id, $startdate = '0000-00-00', $endate = '0000-00-00')
    {
        // dd($endate);
        // dd($shop_id);
        $data           = array();
        $timeStart      = strtotime($startdate);
        $timeEnd        = strtotime($endate);
        $data['range']  = date('M d, Y',$timeStart).' - '.date('M d, Y',$timeEnd);
        $data['start']  = date('m/d/Y', $timeStart);
        $data['end']    = date('m/d/Y',$timeEnd);
        $explodeStart   = explode("-", $startdate);
        $explodeEnd     = explode("-",$endate);
        $min_date       = min($timeStart, $timeEnd);
        $max_date       = max($timeStart, $timeEnd);
        $arrbetween     = array();
        $i              = 0;

        // dd(date('t',strtotime($endate)));

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) 
        {
            $i++;
        }   

        // dd($explodeStart);
        $month   = $explodeStart[1];
        $year    = $explodeStart[0];
        $mainArr = array();
        $dateArr = array();

        for($j = 0; $j <= $i; $j++)
        {
            if($month > 12)
            {
                $month = 1;
                $year++;
            }

            $tempStart = $year.'-'.$month.'-01';
            
            if($j == 0)
            {
                $tempStart = $startdate;   
            }

            $tempEnd = date('Y-m-t',strtotime($tempStart));
        
            if($j == $i)
            {
                // $tempEnd = $endate;
                $tempEnd = date('Y-m-d', strtotime($endate . ' +1 day'));
            }
            
            $betweendate[0] = $tempStart;
            $betweendate[1] = $tempEnd;
            
                                            array_push($dateArr,$betweendate);
            $tempSEl                      = Tbl_order::where('shop_id',$shop_id)->whereBetween('craeted_date',$betweendate)->orderBy('customer_id','asc')->get();
            $data['data'][$j]['monthStr'] = date('F Y', strtotime($tempStart));
            
            // $tempSEl = Tbl_order::where('shop_id',$shop_id)->where('craeted_date','>=',$betweendate[0])->where('craeted_date','<=',$betweendate[0])->orderBy('customer_id','asc')->get();

            $totalGross     = 0;
            $totalOrder     = 0;
            $totalDiscount  = 0;
            $totalRefund    = 0;
            $totalNet       = 0;
            $totalShipping  = 0;
            $totalTax       = 0;
            $totalSales     = 0;
            $customerCount  = 0;
            $customerArr    = array();

            array_push($mainArr,$tempSEl);
            foreach($tempSEl as $sel)
            {
                if(in_array($sel->customer_id,$customerArr))
                {
                    
                }
                else
                {
                    if($sel->customer_id != null)
                    {
                        array_push($customerArr, $sel->customer_id);
                        $customerCount++;
                    }   
                }
                
                $_item            = Tbl_order_item::where('tbl_order_id',$sel->tbl_order_id)->where('archived',0)->get();
                $tempDiscountSub  = 0;
                $tempDiscountMain = 0;
                $tempGrossRaw     = 0;

                foreach($_item as $item)
                {
                    $quantity         = $item->quantity;
                    $price            = $item->item_amount;
                    $tempRaw          = $quantity * $price;
                    $tempGrossRaw    += $tempRaw;
                    $SubVarDisc       = $item->discount_var;
                    $discItem         = $item->discount;
                    $tempDiscountSub1 = 0;

                    if($SubVarDisc != 'amount')
                    {
                        $tempDiscountSub1 = $tempRaw * ($discItem / 100);
                    }

                    $tempDiscountSub += $tempDiscountSub1;
                }

                $totalShipping      +=  $sel->shipping_amount;
                $discountMainVar    = $sel->discount_var;
                $discountMainAmount = $sel->discount;

                if($discountMainVar != 'amount')
                {
                    $discountMainAmount = ($tempGrossRaw - $tempDiscountSub) * ($discountMainAmount / 100);
                }

                $totalGross    += $tempGrossRaw;
                $totalDiscount += $discountMainAmount + $tempDiscountSub;
                $totalOrder++;
                // if($sel->isTaxExempt == 0 && $sel->hasTax == 1){
                //     $totalTax += ($tempGrossRaw - $discountMainAmount) * ($sel->tax_percentage / 100);
                // }
                
            }
            
            $totalNet                           = $totalGross - ($totalRefund - $totalDiscount);
            $totalSales                         = $totalNet + $totalShipping + $totalTax;
            $data['data'][$j]['totalGross']     = $totalGross;
            $data['data'][$j]['totalOrder']     = $totalOrder;
            $data['data'][$j]['totalDiscount']  = $totalDiscount;
            $data['data'][$j]['totalRefund']    = $totalRefund;
            $data['data'][$j]['totalNet']       = $totalNet;
            $data['data'][$j]['totalShipping']  = $totalShipping;
            $data['data'][$j]['totalTax']       = $totalTax;
            $data['data'][$j]['totalSales']     = $totalSales;
            $data['data'][$j]['customerCount']  = $customerCount;
            $month++;
        }

        // dd($mainArr);
        return $data;  
    }
    
    /*FOR PRODUCT OR VARIANT OR CUSTOMER*/
    public static function SalesReportBy($type, $shop_id, $start = '0000-00-00', $end = '0000-00-00')
    {
        if($type == "product")
        {
            $data['range']    = date('M d Y', strtotime($start)).' - '.date('M d Y', strtotime($end));
            $data["_product"] = null;
            $_product         = Tbl_ec_product::where('eprod_shop_id',$shop_id)
                                          ->where("tbl_ec_product.archived",0)
                                          ->join("tbl_ec_variant","tbl_ec_variant.evariant_prod_id","=","tbl_ec_product.eprod_id")
                                          ->get();

            $ctr = 0;
            $date[0] = $start;
            $date[1] = $end;

            foreach($_product as $product)
            {
                if(isset($data["item"][$product->evariant_prod_id]["quantity_sold"]))
                {
                    $data["item"][$product->evariant_prod_id]["quantity_sold"] = $data["item"][$product->evariant_prod_id]["quantity_sold"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("quantity");
                    $data["item"][$product->evariant_prod_id]["gross_sales"]   = $data["item"][$product->evariant_prod_id]["gross_sales"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                    $data["item"][$product->evariant_prod_id]["discount"]      = $data["item"][$product->evariant_prod_id]["discount"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.discount_amount");
                    $data["item"][$product->evariant_prod_id]["refund"]        = $data["item"][$product->evariant_prod_id]["refund"] + 0;
                    $data["item"][$product->evariant_prod_id]["net_sales"]     = $data["item"][$product->evariant_prod_id]["net_sales"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                    $data["item"][$product->evariant_prod_id]["tax"]           = $data["item"][$product->evariant_prod_id]["tax"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.tax");
                    $data["item"][$product->evariant_prod_id]["total"]         = $data["item"][$product->evariant_prod_id]["total"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.total");
                }
                else
                {
                    $data["item"][$product->evariant_prod_id]["info"]          = $product;
                    $data["item"][$product->evariant_prod_id]["quantity_sold"] = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("quantity");
                    $data["item"][$product->evariant_prod_id]["category_name"] = Tbl_category::where("type_id",$product->eprod_category_id)->first()->type_name;
                    $data["item"][$product->evariant_prod_id]["gross_sales"]   = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                    $data["item"][$product->evariant_prod_id]["discount"]      = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.discount_amount");
                    $data["item"][$product->evariant_prod_id]["refund"]        = 0;
                    $data["item"][$product->evariant_prod_id]["net_sales"]     = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                    $data["item"][$product->evariant_prod_id]["tax"]           = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.tax");
                    $data["item"][$product->evariant_prod_id]["total"]         = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.total");                
                }
            }


            return $data;
        }
        else if($type == "month")
        {
            $data          = array();
            $timeStart     = strtotime($start);
            $timeEnd       = strtotime($end);
            $data['range'] = date('M d, Y',$timeStart).' - '.date('M d, Y',$timeEnd);
            $data['start'] = date('m/d/Y', $timeStart);
            $data['end']   = date('m/d/Y',$timeEnd);
            $data['data']  = null;
            $explodeStart  = explode("-", $start);
            $explodeEnd    = explode("-",$end);
            $min_date      = min($timeStart, $timeEnd);
            $max_date      = max($timeStart, $timeEnd);
            // dd(date('t',strtotime($endate)));
            $arrbetween = array();
            $i = 0;

            while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) 
            {
                $i++;
            }  

            
            if($i != 0)
            {
                $i++;
            } 

            // dd($explodeStart);
            $month = $explodeStart[1];
            $year = $explodeStart[0];
            $mainArr = array();
            $dateArr = array();

            for($j = 0; $j <= $i; $j++)
            {
                if($month > 12)
                {
                    $month = 1;
                    $year++;
                }

                $tempStart                          = $year.'-'.$month.'-01';
                $tempEnd                            = date('Y-m-t',strtotime($tempStart));
                $date[0]                            = $tempStart;
                $date[1]                            = $tempEnd;



                $totalGross       = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("subtotal");
                $totalOrder       = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->count();
                $totalDiscount    = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("discount_amount");
                $totalRefund      = 0;
                $totalNet         = $totalGross - ($totalRefund - $totalDiscount);
                $totalShipping    = 0;
                $totalTax         = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("tax");
                $totalSales       = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("total");
                $customerCount    = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->count();




                $data['data'][$j]['totalGross']     = $totalGross;
                $data['data'][$j]['totalOrder']     = $totalOrder;
                $data['data'][$j]['totalDiscount']  = $totalDiscount;
                $data['data'][$j]['totalRefund']    = $totalRefund;
                $data['data'][$j]['totalNet']       = $totalNet;
                $data['data'][$j]['totalShipping']  = $totalShipping;
                $data['data'][$j]['totalTax']       = $totalTax;
                $data['data'][$j]['totalSales']     = $totalSales;
                $data['data'][$j]['customerCount']  = $customerCount;
                $data['data'][$j]['monthStr']       = date('F Y', strtotime($tempStart));

                $month++;
            }

            return $data;
        }
        else if($type == "product_variant")
        {
            $data['range']    = date('M d Y', strtotime($start)).' - '.date('M d Y', strtotime($end));
            $data["_product"] = null;
            $_product         = Tbl_ec_product::variant(' / ')->where('eprod_shop_id',$shop_id)
                                          ->where("tbl_ec_product.archived",0)
                                          ->get();

            $ctr     = 0;
            $date[0] = $start;
            $date[1] = $end;

            foreach($_product as $product)
            {

                $name                                 = $product->variant_name ? $product["variant_name"] : '--None--';
                $data["item"][$ctr]["variant_name"]   = $name;
                $data["item"][$ctr]["quantity_sold"]  = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("quantity");
                $data["item"][$ctr]["category_name"]  = $product->eprod_name;
                $data["item"][$ctr]["gross_sales"]    = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                $data["item"][$ctr]["discount"]       = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.discount_amount");
                $data["item"][$ctr]["refund"]         = 0;
                $data["item"][$ctr]["net_sales"]      = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                $data["item"][$ctr]["tax"]            = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.tax");
                $data["item"][$ctr]["total"]          = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.total");                
                $ctr++;
            }


            return $data;
        }
        else
        {
            return null;
        }
    }

    public static function productreport($shop_id ,$start, $end)
    {
        $data['range']    = date('M d Y', strtotime($start)).' - '.date('M d Y', strtotime($end));
        $data["_product"] = null;
        $_product         = Tbl_ec_product::where('eprod_shop_id',$shop_id)
                                      ->where("tbl_ec_product.archived",0)
                                      ->join("tbl_ec_variant","tbl_ec_variant.evariant_prod_id","=","tbl_ec_product.eprod_id")
                                      ->get();

        $ctr = 0;
        $date[0] = $start;
        $date[1] = $end;

        foreach($_product as $product)
        {
            if(isset($data["item"][$product->evariant_prod_id]["quantity_sold"]))
            {
                $data["item"][$product->evariant_prod_id]["quantity_sold"] = $data["item"][$product->evariant_prod_id]["quantity_sold"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("quantity");
                $data["item"][$product->evariant_prod_id]["gross_sales"]   = $data["item"][$product->evariant_prod_id]["gross_sales"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                $data["item"][$product->evariant_prod_id]["discount"]      = $data["item"][$product->evariant_prod_id]["discount"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.discount_amount");
                $data["item"][$product->evariant_prod_id]["refund"]        = $data["item"][$product->evariant_prod_id]["refund"] + 0;
                $data["item"][$product->evariant_prod_id]["net_sales"]     = $data["item"][$product->evariant_prod_id]["net_sales"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                $data["item"][$product->evariant_prod_id]["tax"]           = $data["item"][$product->evariant_prod_id]["tax"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.tax");
                $data["item"][$product->evariant_prod_id]["total"]         = $data["item"][$product->evariant_prod_id]["total"] + Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.total");
            }
            else
            {
                $data["item"][$product->evariant_prod_id]["info"]          = $product;
                $data["item"][$product->evariant_prod_id]["quantity_sold"] = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("quantity");
                $data["item"][$product->evariant_prod_id]["category_name"] = Tbl_category::where("type_id",$product->eprod_category_id)->first()->type_name;
                $data["item"][$product->evariant_prod_id]["gross_sales"]   = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                $data["item"][$product->evariant_prod_id]["discount"]      = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.discount_amount");
                $data["item"][$product->evariant_prod_id]["refund"]        = 0;
                $data["item"][$product->evariant_prod_id]["net_sales"]     = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
                $data["item"][$product->evariant_prod_id]["tax"]           = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.tax");
                $data["item"][$product->evariant_prod_id]["total"]         = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.total");                
            }
        }


        return $data;
    }

    public static function monthreport($shop_id,$start,$end)
    {
        $data          = array();
        $timeStart     = strtotime($start);
        $timeEnd       = strtotime($end);
        $data['range'] = date('M d, Y',$timeStart).' - '.date('M d, Y',$timeEnd);
        $data['start'] = date('m/d/Y', $timeStart);
        $data['end']   = date('m/d/Y',$timeEnd);
        $data['data']  = null;
        $explodeStart  = explode("-", $start);
        $explodeEnd    = explode("-",$end);
        $min_date      = min($timeStart, $timeEnd);
        $max_date      = max($timeStart, $timeEnd);
        // dd(date('t',strtotime($endate)));
        $arrbetween = array();
        $i = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) 
        {
            $i++;
        }

        if($i != 0)
        {
            $i++;
        }   
        // dd($explodeStart);
        $month = $explodeStart[1];
        $year = $explodeStart[0];
        $mainArr = array();
        $dateArr = array();

        for($j = 0; $j <= $i; $j++)
        {
            if($month > 12)
            {
                $month = 1;
                $year++;
            }

            $tempStart                          = $year.'-'.$month.'-01';
            $tempEnd                            = date('Y-m-t',strtotime($tempStart));
            $date[0]                            = $tempStart;
            $date[1]                            = $tempEnd;



            $totalGross       = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("subtotal");
            $totalOrder       = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->count();
            $totalDiscount    = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("discount_amount");
            $totalRefund      = 0;
            $totalNet         = $totalGross - ($totalRefund - $totalDiscount);
            $totalShipping    = 0;
            $totalTax         = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("tax");
            $totalSales       = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->sum("total");
            $customerCount    = Tbl_ec_order::whereIn("order_status",['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->count();




            $data['data'][$j]['totalGross']     = $totalGross;
            $data['data'][$j]['totalOrder']     = $totalOrder;
            $data['data'][$j]['totalDiscount']  = $totalDiscount;
            $data['data'][$j]['totalRefund']    = $totalRefund;
            $data['data'][$j]['totalNet']       = $totalNet;
            $data['data'][$j]['totalShipping']  = $totalShipping;
            $data['data'][$j]['totalTax']       = $totalTax;
            $data['data'][$j]['totalSales']     = $totalSales;
            $data['data'][$j]['customerCount']  = $customerCount;
            $data['data'][$j]['monthStr']       = date('F Y', strtotime($tempStart));

            $month++;
        }

        return $data;
    }

    public static function variantreport($shop_id,$start,$end)
    {
        $data['range']    = date('M d Y', strtotime($start)).' - '.date('M d Y', strtotime($end));
        $data["_product"] = null;
        $_product         = Tbl_ec_product::variant(' / ')->where('eprod_shop_id',$shop_id)
                                      ->where("tbl_ec_product.archived",0)
                                      ->get();

        $ctr = 0;
        $date[0] = $start;
        $date[1] = $end;

        foreach($_product as $product)
        {

            $name = $product->variant_name ? $product["variant_name"] : '--None--';
            $data["item"][$ctr]["variant_name"]   = $name;
            $data["item"][$ctr]["quantity_sold"]  = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("quantity");
            $data["item"][$ctr]["category_name"]  = $product->eprod_name;
            $data["item"][$ctr]["gross_sales"]    = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
            $data["item"][$ctr]["discount"]       = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.discount_amount");
            $data["item"][$ctr]["refund"]         = 0;
            $data["item"][$ctr]["net_sales"]      = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.subtotal");
            $data["item"][$ctr]["tax"]            = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.tax");
            $data["item"][$ctr]["total"]          = Tbl_ec_order_item::ecorder(['Completed'])->whereBetween(DB::raw('date(tbl_ec_order.created_date)'),$date)->where("item_id",$product->evariant_id)->sum("tbl_ec_order_item.total");                
            $ctr++;
        }


        return $data;
    }
}