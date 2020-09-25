/* INITITALIZE ARRAY */
$customer_info      = ['customer_id' => '1', 'customer_email' => 'email@.com'];
$invoice_info       = ['invoice_terms_id' => '1', 'invoice_date' => '2017-01-01', 'invoice_due' => '2017-01-30', 'billing_address' => 'address 1'];
$invoice_other_info = ['invoice_msg' => 'customer_message', 'invoice_memo' => 'customer_memo'];

/* INITITALIZE ARRAY */
$item_info          = [];

/* BATCH DATA */
$item_info[0]['item_service_date']   = '2017-01-01';
$item_info[0]['item_id']             = 1;
$item_info[0]['item_description']    = 'this is a description';
$item_info[0]['quantity']            = 1;
$item_info[0]['rate']                = 1;
$item_info[0]['discount']            = 1;
$item_info[0]['discount_remark']     = 'remark';
$item_info[0]['taxable']             = 1;
$item_info[0]['amount']              = 1;

/* BATCH DATA */
$item_info[1]['item_service_date']   = '2017-01-01';
$item_info[1]['item_id']             = 1;
$item_info[1]['item_description']    = 'this is a description';
$item_info[1]['quantity']            = 1;
$item_info[1]['rate']                = 1;
$item_info[1]['discount']            = 1;
$item_info[1]['discount_remark']     = 'remark';
$item_info[1]['taxable']             = 1;
$item_info[1]['amount']              = 1;

/* INITITALIZE ARRAY */
$total_info         = ['total_subtotal_price' => '1', 'ewt' => '1', 'total_discount_type' => 'percent', 'total_discount_value' => 50, 'taxable' => '1', 'total_overall_price' => 100 ];

Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info);