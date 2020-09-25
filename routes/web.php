<?php
AdvancedRoute::controller('/exam', 'ExamController');
AdvancedRoute::controller('/super/client', 'Super\SuperClientController');
AdvancedRoute::controller('/super/admin', 'Super\SuperAdminController');
AdvancedRoute::controller('/super/user', 'Super\SuperUserController');
AdvancedRoute::controller('/super', 'Super\SuperDashboardController');

Route::get('/ref/{id}', 'LeadController@ref');
Route::any('/inspirers', 'SampleTesting@inspirer');
Route::any('/inspirer', 'SampleTesting@inspirer');

Route::any('/ncabot', 'SampleTesting@ncabot');
Route::any('/oliver/{id}', 'SampleTesting@index');
Route::any('/oliver/samp2', 'SampleTesting@samp2');
Route::any('/s', 'TesterController@samptest');
Route::any('/pmigrate', 'PasswordMigrateController@index');
Route::any('/dd','TesterController@connection_test');
Route::any('/member/payroll/api_login','Api\PayrollConnectController@index');
Route::any('/member/payroll/get_cutoff_data','Api\PayrollConnectController@get_cutoff_data');


Route::any("/admin_check_notification","TesterController@check_notif"); // CRON JOB DO NOT REMOVE
Route::any("/member/load_warehouse_name","Member\DashboardController@load_warehouse_name"); 

Route::any('/member/popup/message','MemberController@message');	
Route::get('/member/mail_setting', 'Member\MailSettingController@index');
Route::post('/member/mail_setting', 'Member\MailSettingController@submit');

Route::any('/member/instant_add_slot', 'Member\MLM_SlotController@instant_add_slot');

Route::any('/member/raymond', 'Member\RaymondController@index'); //RAYMOND

/* FRONTEND - SHIGUMA RIKA */
Route::get('/', 'Frontend\HomeController@index');
Route::get('/barcode', 'MemberController@barcodes');

// for testing only
// Route::get('/card', 'MemberController@card');
// Route::get('/card/all', 'MemberController@all_slot');

Route::any('/login/geturl', 'Member\TesterController@test_login'); 

Route::get('member/register/session', 'MemberController@session');
Route::get('member/login', 'MemberController@login');
Route::get('member/register', 'MemberController@register');
Route::post('member/register/submit', 'MemberController@register_post');

Route::get('member/register/package', 'MemberController@package');
Route::post('member/register/package/submit', 'MemberController@package_post');
Route::get('member/register/package/product/{product_id}', 'MemberController@package_get_details_product');

Route::get('member/register/payment', 'MemberController@payment');
Route::post('member/register/payment/submit', 'MemberController@payment_post');

Route::get('member/register/shipping', 'MemberController@shipping');
Route::post('member/register/shipping/submit', 'MemberController@shipping_post');
Route::get('member/card', 'Member\MLM_CardController@card');
Route::get('member/card/all', 'Member\MLM_CardController@all_slot');

// end

Route::get('/pricing', 'Frontend\HomeController@pricing');
Route::get('/support', 'Frontend\HomeController@support');
/* END FRONTEND - SHIGUMA RIKA */

/* SHOP FRONTENT */

if(get_domain() == "c9users.io")
{
	$domain = "my168shop-primia.c9users.io"; //USE FOR TESTING
}
else
{
	$domain = get_domain();
}

Route::any("send","Member\EmailContentController@test");

/* CHECK IF LOOKING AT CLIENT PAGE */
include_once('routes_config/routes_digimahouse_domain.php');
/* MLM (REPORTS, GENEALOGY, VOUCHER) */
include_once('routes_config/routes_mlm.php');
/* END MLM (REPORTS, GENEALOGY, VOUCHER)

/* MEMBER - MANAGE PAGE */
include_once('routes_config/routes_member_manage_page.php');
/* END MEMBER - MANAGE PAGE */

/* MEMBER - MULTILEVEL MARKETING */
include_once('routes_config/routes_member_mlm.php');
/* END MEMBER - MULTILEVEL MARKETING */

/* MANAGE STORE INFORMATION */
Route::any("/member/page/store_information","Member\ManageStoreInformationController@index"); 
Route::any("/member/page/store_information/update_submit","Member\ManageStoreInformationController@update_submit");

/* MEMBER - PAGE - CONTACT */
AdvancedRoute::controller("/member/page/contact","Member\Page_ContactController");

/* MEMBER - DEVELOPER  */
Route::any('/member/developer/status', 'Member\Developer_StatusController@index'); //GUILLERMO TABLIGAN
Route::any('/member/developer/rematrix', 'Member\Developer_RematrixController@index'); //ERWIN GUEVARRA
Route::any('/member/developer/documentation', 'Member\Developer_DocumentationController@index'); //EVERYONE

Route::any('/member/developer/auto_entry', 'Member\Developer_AutoentryController@index'); //EVERYONE
Route::post('/member/developer/auto_entry/instant_add_slot', 'Member\Developer_AutoentryController@instant_add_slot'); //EVERYONE
Route::any('/member/developer/auto_entry_independent/', 'Member\Developer_AutoentryController@index_independent'); //EVERYONE
Route::post('/member/developer/auto_entry_independent/create_slot', 'Member\Developer_AutoentryController@independent_create_slot'); //EVERYONE
Route::any('/member/developer/single_entry/', 'Member\Developer_AutoentryController@single_entry'); //EVERYONE
Route::any('/member/developer/single_entry/submit', 'Member\Developer_AutoentryController@single_entry_submit'); //EVERYONE

Route::any('/member/developer/simulate', 'Member\Developer_RematrixController@simulate'); //EVERYONE
Route::any('/member/developer/simulate/submit', 'Member\Developer_RematrixController@simulate_submit'); //EVERYONE

Route::any('/member/developer/reset_slot', 'Member\Developer_StatusController@reset_slot'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit', 'Member\Developer_StatusController@reset_slot_submit'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/give', 'Member\Developer_StatusController@give_points_ec_order'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/retro_product_sales', 'Member\Developer_StatusController@retro_product_sales'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/re_tree', 'Member\Developer_StatusController@re_tree'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/re_com_phil_lost', 'Member\Developer_StatusController@re_com_phil_lost'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/re_com_phil_uni', 'Member\Developer_StatusController@re_com_phil_uni'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/recompute', 'Member\Developer_StatusController@recompute'); //GUILLERMO TABLIGAN
Route::any('/member/developer/reset_slot/submit/recompute/membership_matching', 'Member\Developer_StatusController@recompute_membership_matching'); //GUILLERMO TABLIGAN
Route::any('/member/developer/payment_logs', 'Member\Developer_StatusController@payment_logs'); //GUILLERMO TABLIGAN
Route::any('/member/developer/payment_logs/{id}', 'Member\Developer_StatusController@payment_logs_data'); //GUILLERMO TABLIGAN
/* END MEMBER - VENDOR - GUILLERMO TABLIGAN */

/* MEMBER - ACCOUNTING - CHART OF ACCOUNTS */
Route::get('/member/accounting/chart_of_account', 'Member\ChartOfAccountController@index');
Route::any('/member/accounting/chart_of_account/add', 'Member\ChartOfAccountController@add_account');
Route::any('/member/accounting/chart_of_account/update/{id}', 'Member\ChartOfAccountController@update_account');
Route::any('/member/accounting/chart_of_account/delete/{id}', 'Member\ChartOfAccountController@delete_account');
Route::any('/member/accounting/chart_of_account/popup/add', 'Member\ChartOfAccountController@load_add_account');
Route::any('/member/accounting/chart_of_account/popup/update/{id}', 'Member\ChartOfAccountController@load_update_account');
/* END ACCOUNTNG - CHART OF ACCOUNTS - BRYAN KIER ARADANAS */

/* MEMBER - ACCOUNTING - SETTINGS */
AdvancedRoute::controller('/member/accounting/settings', 'Member\AccountingSettingController');
/* END */

/* API - Bryan Kier Aradanas */
Route::get('/api/{shop_id}/{shop_key}/product', 'Api\Api@product');
Route::get('/api/{shop_id}/{shop_key}/product/{product_id}', 'Api\Api@product_info');

Route::any('/login', 'Login\MemberLoginController@login');
Route::get('/logout', 'Login\MemberLoginController@logout');
Route::any('/register', 'Login\MemberLoginController@register');
Route::post('/createAccount', 'Frontend\HomeController@createAccount');


Route::group(array('prefix' => '/member/{page}/'), function()
{
	//order start
	// Route::any('order','Member\OrderController@orders');
	// Route::get('order/new_order','Member\OrderController@new_order');
	// Route::post('order/new_order/create_customer','Member\OrderController@create_customer');
	// Route::post('order/new_order/searchscustomer','Member\OrderController@searchscustomer');
	// Route::any('order/new_order/customerinfo','Member\OrderController@customerinfo');
	// Route::post('order/new_order/updateEmail','Member\OrderController@updateEmail');
	// Route::post('order/new_order/updateShipping','Member\O/member/itemrderController@updateShipping');
	// Route::post('order/new_order/itemlist','Member\OrderController@itemlist');
	// Route::post('order/new_order/create_order','Member\OrderController@create_order');
	// Route::post('order/new_order/removeitemorder','Member\OrderController@removeitemorder');
	// Route::post('order/new_order/addIndiDiscount','Member\OrderController@addIndiDiscount');
	// Route::post('order/new_order/chagequantity','Member\OrderController@chagequantity');
	// Route::post('order/new_order/addMainDiscount','Member\OrderController@addMainDiscount');
	// Route::post('order/new_order/applytax','Member\OrderController@applytax');
	// Route::post('order/new_order/removecustomer','Member\OrderController@removecustomer');
	// Route::post('order/new_order/addshipping','Member\OrderController@addshipping');
	// Route::post('order/new_order/savetodraft','Member\OrderController@savetodraft');
	// Route::post('order/new_order/OrderStatus','Member\OrderController@OrderStatus');
	
	//search for item start
	// Route::post('search_item','Member\OrderController@search_item');
	//search for item end

	// Route::get('order/{id}','Member\OrderListController@item');
	// Route::post('order/addnote','Member\OrderListController@addnote');
	// Route::post('order/refunditem','Member\OrderListController@refunditem');
	// Route::post('order/recordrefund','Member\OrderListController@recordrefund');
	// Route::post('order/updatepaystatus','Member\OrderListController@update_pay_status');
	// Route::get('order/filter/{status}/{payment_stat}/{fulfillment_status}','Member\OrderController@get_orders_with_view');
	
	// Route::get('orders/draft','Member\DraftController@index');
	// Route::get('orders/draft/{id}','Member\DraftController@view_draft');
	// Route::get('orders/abandoned','Member\CheckOutController@index');
	//order end
	
	//shipping start
	// Route::get('shipping','Member\ShipInfoController@index');
	// Route::post('shipping/create','Member\ShipInfoController@create');
	// Route::post('shipping/load','Member\ShipInfoController@load');
	// Route::get('shipping/remove','Member\ShipInfoController@remove');
	// Route::post('shipping/update','Member\ShipInfoController@update');
	//shipping end

	//product order start
	Route::get('product_order','Member\ProductOrderController@invoice_list');
	Route::get('product_order/create_order','Member\ProductOrderController@index');

	Route::any('product_order/create_order/invoice','Member\ProductOrderController@order_invoice');
	Route::post('product_order/create_order/create_invoice','Member\ProductOrderController@create_invoice');
	Route::post('product_order/create_order/update_invoice','Member\ProductOrderController@update_invoice');
	Route::get('product_order/create_order/submit_coupon','Member\ProductOrderController@submit_coupon');
	Route::any('product_order/create_order/submit_payment_upload','Member\ProductOrderController@submit_payment_upload');
	
	Route::get('product_order2','Member\ProductOrderController2@index');
	Route::post('product_order2/table','Member\ProductOrderController2@table');
	Route::get('product_order2/proof','Member\ProductOrderController2@proof');
	Route::get('product_order2/details','Member\ProductOrderController2@details');
	Route::get('product_order2/confirm_payment','Member\ProductOrderController2@confirm_payment');
	Route::post('product_order2/confirm_payment_submit','Member\ProductOrderController2@confirm_payment_submit');
	Route::get('product_order2/reject_payment','Member\ProductOrderController2@reject_payment');
	Route::post('product_order2/reject_payment_submit','Member\ProductOrderController2@reject_payment_submit');
	
	Route::get('product_order2/payref','Member\ProductOrderController2@payref');
	Route::get('product_order2/draref','Member\ProductOrderController2@draref');
	Route::get('product_order2/export','Member\ProductOrderController2@export');
	Route::get('product_order2/exportpayin','Member\ProductOrderController2@exportpayin');

	Route::get('product_order2/settings','Member\ProductOrderController2@settings');
	Route::post('product_order2/settings','Member\ProductOrderController2@settings_submit');
	//product order end
});


Route::get('/member', 'Member\DashboardController@index');
Route::any('/member/load_count_transaction', 'Member\DashboardController@load_count_transaction');
Route::get('/member/new', 'Member\DashboardController@new_dashboard');
Route::post('/member/change_warehouse', 'Member\DashboardController@change_warehouse');
Route::any('/member/setup', 'Member\SetupController@index');
Route::any('/member/product/service_list', 'Member\Product_ServiceListController@index'); //GUILLERMO TABLGIAN
Route::any('/member/product/list', 'Member\ProductController@index');
Route::any('/member/product/add', 'Member\ProductController@add');
Route::post('/member/product/upload', 'Member\ProductController@upload');
Route::post('/member/product/delete_image', 'Member\ProductController@delete_image');
Route::any('/member/product/edit/{id}', 'Member\ProductController@edit');
Route::any('/member/product/edit/variant/{id}', 'Member\ProductController@edit_variant');
Route::post('/member/product/edit/variant/image/save_image', 'Member\ProductController@save_image_variant');
Route::get('/member/product/edit/variant/delete/{id}', 'Member\ProductController@delete_variant');

Route::get('/member/product/variant_item', 'Member\ProductController@get_variant_item_info'); //$ajax_get
Route::get('/member/product/variant_options', 'Member\ProductController@get_variant_options'); //$ajax_get

Route::post('/member/product/save_option_name', 'Member\ProductController@save_option_name'); //form
Route::post('/member/product/remove_option_value', 'Member\ProductController@remove_option_value');
Route::get('search','Member\ProductController@insertSearchTemp');

Route::any("/member/ecommerce/product/collection","Member\CollectionController@index");
Route::any("/member/ecommerce/product/collection_modal","Member\CollectionController@collection_modal");
Route::any("/member/ecommerce/product/collection/list","Member\CollectionController@collection_list");
Route::any("/member/ecommerce/product/collection/add_submit","Member\CollectionController@add_submit");
Route::any("/member/ecommerce/product/collection/edit_submit","Member\CollectionController@edit_submit");
Route::any("/member/ecommerce/product/collection/set_active","Member\CollectionController@set_active");
Route::any("/member/ecommerce/product/collection/archived/{id}/{action}","Member\CollectionController@archived");
Route::any("/member/ecommerce/product/collection/archived_submit","Member\CollectionController@archived_submit");

Route::get("/member/ecommerce/wishlist/list","Member\WishlistController@list");

Route::any('/member/item', 'Member\ItemController@index'); /* ERWIN */  
Route::any('/member/item/add', 'Member\ItemController@add'); /* ERWIN */
Route::any('/member/item/view_item_receipt/{id}', 'Member\ItemController@view_item_receipt'); /* ERWIN */
Route::any('/member/item/load_all_um','Member\ItemController@load_all_um');
Route::any('/member/item/add_submit', 'Member\ItemController@add_submit'); /* ERWIN */
Route::any('/member/item/edit/{id}', 'Member\ItemController@edit'); /* ERWIN */
Route::any('/member/item/edit/show/mlm', 'Member\ItemController@show_mlm');
Route::any('/member/item/edit_submit', 'Member\ItemController@edit_submit'); /* ERWIN */
Route::any('/member/item/archive/{id}', 'Member\ItemController@archive'); /* ERWIN */
Route::any('/member/item/archive_submit', 'Member\ItemController@archive_submit'); /* ERWIN */
Route::any('/member/item/restore/{id}', 'Member\ItemController@restore'); /* B */
Route::any('/member/item/restore_submit', 'Member\ItemController@restore_submit'); /* B */
Route::any('/member/item/insert_saved_data', 'Member\ItemController@insert_session'); /* ERWIN */
Route::any('/member/item/data', 'Member\ItemController@data'); /* ERWIN */
Route::get('/member/item/mulitple_price_modal/{id}', 'Member\ItemController@get_multiple_price_modal'); /* B */
Route::post('/member/item/mulitple_price_modal', 'Member\ItemController@update_multiple_price_modal'); /* B */
Route::get('/member/item/get_new_price/{id}/{qty}', 'Member\ItemController@get_item_new_price'); /* B */
Route::get('/member/item/print_new_item','Member\ItemController@print_new_item');
Route::get('/member/item/check_print_new_item','Member\ItemController@check_print_new_item');

Route::get('/member/item/approve_request/{id}', 'Member\ItemController@merchant_approve_request'); /* ERWIN */
Route::post('/member/item/approve_request_post/approve', 'Member\ItemController@merchant_approve_request_post'); /* ERWIN */

Route::get('/member/item/decline_request/{id}', 'Member\ItemController@merchant_decline_request'); /* ERWIN */
Route::post('/member/item/decline_request_post/{id}', 'Member\ItemController@merchant_decline_request_post'); /* ERWIN */



//*ITEM FOR PIS ARCY*/
Route::any('/member/pis_counter','Member\PurchasingInventorySystemController@pis_counter');

Route::any('/member/cashier_sales_report','Member\PisSalesLiquidationController@cashier_sales_report');

Route::any('/member/item/view_item_history/{id}','Member\ItemController@view_item_history');
Route::any('/member/item/add_submit_pis','Member\ItemController@add_submit_pis');
Route::any('/member/item/edit_submit_pis','Member\ItemController@edit_submit_pis');
Route::any('/member/item/delete_item_history','Member\ItemController@delete_item_history');


Route::any('/member/enable_disable_pis/{pass}/{action}','Member\PurchasingInventorySystemController@enable_pis');
/*END ITEM FOR PIS*/


Route::any("/member/item/view_serials/{id}","Member\ItemSerialController@index");
Route::any("/member/item/serial_number/{id}",'Member\ItemSerialController@view_serial');
Route::any("/member/item/save_serial",'Member\ItemSerialController@save_serial');

Route::any('/member/input/serial_number','Member\ItemSerialController@input_serial');
Route::any('/member/item/archived_item_serial','Member\ItemSerialController@archived_serial');

Route::any('/member/functiontester', 'Member\FunctionTesterController@index'); /* ERWIN */
Route::any('/member/functiontester/clear_all', 'Member\FunctionTesterController@clear_all'); /* ERWIN */
Route::any('/member/functiontester/clear_one', 'Member\FunctionTesterController@clear_one'); /* ERWIN */
Route::any('/member/functiontester/get_cart', 'Member\FunctionTesterController@get_cart'); /* ERWIN */

/* MANAGE CATEGORIES */
Route::any('/member/item/category', 'Member\Manage_Category_Controller@index');
Route::any('/member/item/category/modal_create_category', 'Member\Manage_Category_Controller@modal_create_category');
Route::any('/member/item/category/edit_category/{id}', 'Member\Manage_Category_Controller@edit_category');
Route::any('/member/item/category/update_category', 'Member\Manage_Category_Controller@update_category');
Route::any('/member/item/category/archived/{id}/{action}','Member\Manage_Category_Controller@archived');
Route::any('/member/item/category/archived_submit','Member\Manage_Category_Controller@archived_submit');

Route::any('/member/item/category/create_category', 'Member\Manage_Category_Controller@create_category');
Route::any('/member/item/category/search_category', 'Member\Manage_Category_Controller@search_category');

/* START U/M ARCY*/
Route::any('/member/item/unit_of_measurement','Member\UnitOfMeasurementController@index');
Route::any('/member/item/unit_of_measurement/add','Member\UnitOfMeasurementController@add');
Route::any('/member/item/unit_of_measurement/add_submit','Member\UnitOfMeasurementController@add_submit');
Route::any('/member/item/unit_of_measurement/edit/{id}','Member\UnitOfMeasurementController@edit');
Route::any('/member/item/unit_of_measurement/edit_submit','Member\UnitOfMeasurementController@edit_submit');
Route::any('/member/item/um/select_type','Member\UnitOfMeasurementController@select_type');
Route::any('/member/item/unit_of_measurement/{id}/{action}','Member\UnitOfMeasurementController@archived');
Route::any('/member/item/unit_of_measurement/archived_submit','Member\UnitOfMeasurementController@archived_submit');
/* END U/M ARCY*/

/*PIS UM*/
Route::any('/member/pis/um_add','Member\UnitOfMeasurementController@add_um');
Route::any('/member/pis/um_add_submit','Member\UnitOfMeasurementController@add_um_submit');
Route::any('/member/pis/load_pis_um/{type}','Member\UnitOfMeasurementController@load_pis_um');
Route::any('/member/item/pis_unit_of_measurement','Member\UnitOfMeasurementController@um_list_pis');
Route::any('/member/pis/um_edit/{id}','Member\UnitOfMeasurementController@edit_um');
Route::any('/member/pis/um_edit_submit','Member\UnitOfMeasurementController@edit_um_submit');
/*END PIS UM*/


/*PIS*/
Route::any('/member/item/um/',"Member\UnitOfMeasurementController@check");
Route::any('/member/item/um/add_base/{id}/{item_id}',"Member\UnitOfMeasurementController@add_base");
Route::any('/member/item/um/add_base_submit','Member\UnitOfMeasurementController@add_base_submit');


/* PRICE LEVEL */
Route::any('/member/item/price_level','Member\ItemPriceLevelController@index');
Route::any('/member/item/price_level/table','Member\ItemPriceLevelController@index_table');
Route::get('/member/item/price_level/add','Member\ItemPriceLevelController@add');
Route::post('/member/item/price_level/add','Member\ItemPriceLevelController@add_submit');
Route::post('/member/item/price_level/edit_submit','Member\ItemPriceLevelController@edit_submit');

/* START AUDIT TRAIL*/
Route::any('/member/utilities/audit','Member\AuditTrailController@index');
Route::any('/member/utilities/audit/get_list','Member\AuditTrailController@get_list');
/* END AUDIT TRAIL*/

/* START CLIENT INFO*/
Route::any('/member/utilities/client_list','Member\UtilitiesClientController@index');
Route::any('/member/utilities/client/update/{id}','Member\UtilitiesClientController@update');
Route::any('/member/utilities/client/update_submit','Member\UtilitiesClientController@update_submit');
/*END CLIENT INFO*/


/* START TRUCK ARCY*/
Route::any('/member/utilities/truck_list','Member\TruckController@index');
Route::any('/member/pis/truck_list/add','Member\TruckController@add');
Route::any('/member/pis/truck_list/add_submit','Member\TruckController@add_submit');
Route::any('/member/pis/truck_list/edit/{id}','Member\TruckController@edit');
Route::any('/member/pis/truck_list/edit_submit','Member\TruckController@edit_submit');
Route::any('/member/pis/truck_list/archived/{id}/{action}','Member\TruckController@archived');
Route::any('/member/pis/truck_list/archived_submit','Member\TruckController@archived_submit');
/* END TRUCK ARCY*/

/*ITEM RANGE SALES DISCOUNT EDRICH*/
AdvancedRoute::controller('/member/utilities/item_range_sales_discount','Member\ItemRangeController');
/*ITEM RANGE SALES DISCOUNT EDRICH*/


/* START EMPLOYEE POSITION*/
Route::any('/member/utilities/agent_position','Member\AgentPositionController@index');
Route::any('/member/pis/agent/position/add','Member\AgentPositionController@add');
Route::any('/member/pis/agent/position/add_submit','Member\AgentPositionController@add_submit');
Route::any('/member/pis/agent/position/edit/{id}','Member\AgentPositionController@edit');
Route::any('/member/pis/agent/position/edit_submit','Member\AgentPositionController@edit_submit');
Route::any('/member/pis/agent/position/archived/{id}/{action}','Member\AgentPositionController@archived');
Route::any('/member/pis/agent/position/archived_submit','Member\AgentPositionController@archived_submit');
/* END EMPLOYEE POSITION*/


/* START AGENT*/
Route::any('/member/cashier/agent_list','Member\AgentController@index');
Route::any('/member/pis/agent/add','Member\AgentController@add');
Route::any('/member/pis/agent/add_submit','Member\AgentController@add_submit');
Route::any('/member/pis/agent/edit/{id}','Member\AgentController@edit');
Route::any('/member/pis/agent/edit_submit','Member\AgentController@edit_submit');
Route::any('/member/pis/agent/archived/{id}/{action}','Member\AgentController@archived');
Route::any('/member/pis/agent/archived_submit','Member\AgentController@archived_submit');
/* END AGENT*/
Route::any('/member/report/agent/profit_loss','Member\ReportAgentProfitLossController@index');
  
// Route::any('/member/report/summary/inventory','Member\ReportInventorySummaryController@report');
// Route::any('/member/report/inventory/detailed/{id}','Member\ReportInventoryDetailedController@report');
/*LOGISTIC REPORT*/
Route::any('/member/report/logistic','Member\ReportLogisticController@index');

/* Purchase Order Report */
Route::any('/member/report/open/purchase/order', 'Member\ReportPurchaseOrderController@index'); //edrich
Route::any('/member/report/open/purchase/vendor', 'Member\ReportPurchaseVendorController@index'); //edrich
Route::any('/member/report/open/purchase/vendor_summary', 'Member\ReportPurchaseVendorController@index_summary'); //edrich
Route::any('/member/report/vendor/detailed/{id}', 'Member\ReportPurchaseVendorController@index_detailed'); //edrich

Route::any('/member/report/open/purchase/item_summary', 'Member\ReportPurchaseItemController@index_summary'); //edrich
Route::any('/member/report/open/purchase/item_detail', 'Member\ReportPurchaseItemController@index_detail'); //edrich

Route::any('/member/report/open/purchase/orders/detailed', 'Member\ReportPurchaseOrderDetailedController@index');

/*SALES LIQUIDATION*/
Route::any('member/cashier/sales_liquidation','Member\PisSalesLiquidationController@index');
Route::any('/member/cashier/report/{id}','Member\PisSalesLiquidationController@report');
Route::any('/member/cashier/report/footer/{id}','Member\PisSalesLiquidationController@footer');

/*AGENT TRANSACTION*/
Route::any('/member/pis/agent/transaction/{id}','Member\AgentTransactionController@agents_transaction');
Route::any('/member/pis/agent_transaction/print/{id}','Member\AgentTransactionController@print_transaction');

Route::any('/member/cashier/collection','Member\AgentCollectionController@index');
Route::any('/member/pis_agent/collection_update/{id}','Member\AgentCollectionController@update_collection');
Route::any('/member/pis_agent/collection_update_submit','Member\AgentCollectionController@update_collection_submit');

/* START U/M TYPES ARCY*/
Route::any('/member/item/um_type','Member\UnitMeasurementTypeController@index');
Route::any('/member/item/um_type/add','Member\UnitMeasurementTypeController@add');
Route::any('/member/item/um_type/add_submit','Member\UnitMeasurementTypeController@add_submit');
Route::any('/member/item/um_type/edit/{id}','Member\UnitMeasurementTypeController@edit');
Route::any('/member/item/um_type/edit_submit','Member\UnitMeasurementTypeController@edit_submit');

Route::any('/member/item/select_um','Member\UnitOfMeasurementController@select_um');
/* END U/M TYPES ARCY*/

Route::any('/member/item/warehouse/reorder_notification/{warehouse_id}', 'Member\WarehouseController@reorder_notification');
Route::any('/member/item/warehouse/reorder_submit', 'Member\WarehouseController@reorder_submit');

/* WAREHOUSE ARCY*/
Route::any('/member/item/warehouse', 'Member\WarehouseController@index');
Route::any('/member/item/warehouse/xls/{id}', 'Member\WarehouseController@export_xls'); /* Edward */
Route::any('/member/item/inventory_log/{id}','Member\WarehouseController@inventory_log');
Route::any('/member/item/warehouse/add', 'Member\WarehouseController@add');
Route::any('/member/item/warehouse/edit/{id}', 'Member\WarehouseController@edit');
Route::any('/member/item/warehouse/edit_submit', 'Member\WarehouseController@edit_submit');
Route::any('/member/item/transferinventory','Member\WarehouseController@transferinventory');
Route::any('/member/item/warehouse/transfer_inventory_submit','Member\WarehouseController@transferinventory_submit');
Route::post('/member/item/warehouse/transfer_submit','Member\WarehouseController@transfer_submit');
Route::any('/member/item/warehouse/archived/{id}','Member\WarehouseController@archived');
Route::any('/member/item/warehouse/archive_submit','Member\WarehouseController@archived_submit');
Route::any('/member/item/warehouse/view/{id}','Member\WarehouseController@view');

Route::any('/member/item/warehouse/view_v2/{id}','Member\WarehouseController@view_v2');
Route::any('/member/item/warehouse/view_v2/table/{id}','Member\WarehouseController@view_inventory_table');

Route::any('/member/item/warehouse/view_v2/print/{id}/{type}','Member\WarehouseController@print_inventory');

Route::any('/member/item/warehouse/refill','Member\WarehouseController@refill');
Route::any('/member/item/warehouse/refill_submit','Member\WarehouseController@refill_submit');
Route::any('/item/warehouse/refill/by_vendor/{warehouse_id}/{id}','Member\WarehouseController@refill_item_vendor');



//cycy
Route::any('/warehouse/sir/{warehouse_id}/{item_id}','Member\WarehouseController@inventory_break_down');

//adjust inventory
Route::any('/member/item/warehouse/adjust/{id}','Member\WarehouseController@adjust');
Route::any('/member/item/warehouse/adjust_submit','Member\WarehouseController@adjust_submit');

Route::any('/member/item/warehouse/load_item','Member\WarehouseController@filter_item');

Route::any('/member/item/warehouse/restore/{id}','Member\WarehouseController@restore');
Route::any('/member/item/warehouse/restore_submit','Member\WarehouseController@restore_submit');
Route::any('/member/item/warehouse/load_warehouse','Member\WarehouseController@load_warehouse');

Route::any('/member/item/warehouse/add_submit','Member\WarehouseController@add_submit');
Route::any('/member/item/warehouse/select_item','Member\WarehouseController@select_item');
Route::any('/member/warehouse/load_item','Member\WarehouseController@load_item');

Route::any('/member/item/add_serial','Member\WarehouseController@add_serial_number');
Route::any('/member/item/add_serial_number_submit','Member\WarehouseController@add_serial_number_submit');

Route::any('/member/item/confirm_serial','Member\WarehouseController@confirm_serial');
Route::any('/member/item/confirm_serial_submit','Member\WarehouseController@confirm_serial_submit');
Route::any('/member/item/warehouse/refill_log/{id}','Member\WarehouseController@refill_log');
Route::any('/member/item/warehouse/view_pdf/{id}','Member\WarehouseController@view_pdf');
Route::any('/member/item/warehouse/stock_input_report/{id}','Member\WarehouseController@stock_input');
/* END WAREHOUSE ARCY*/

/* REFILL WAREHOUSE */
AdvancedRoute::controller("/member/item/warehouse/v2/refill","Member\WarehouseRefillController");

/* INVENTORY LOG*/
Route::any('/member/item/inventory_log','Member\InventoryLogController@index');
/*END INVENTORY LOG*/

/* REPORT - INVENTORY - SUMMARY*/
Route::any('/member/report/inventory/summary', 'Member\InventorySummaryController@index');
/* End */

/* REPORT - INVENTORY - DETAILED*/
Route::any('/member/report/inventory/detailed/{id}', 'Member\InventoryDetailedController@index');
/* End */

/* REPORT - ADJUSTMENT - SUMMARY*/
Route::any('/member/report/adjustment/summary', 'Member\AdjustmentSummaryController@index');
/* End */

/* REPORT - ADJUSTMENT - DETAILED*/
Route::any('/member/report/adjustment/detailed/{id}', 'Member\AdjustmentDetailedController@index');
/* End */

/* REPORT - WAREHOUSE - TRANSFER*/
Route::any('/member/report/warehouse/transfer', 'Member\ReportWarehouseTransferController@index');
Route::any('/member/report/warehouse/transfer/detailed/{id}', 'Member\ReportWarehouseTransferController@detailed');
/* End */

/* REPORT - STOCK LEDGER*/
AdvancedRoute::controller('/member/accounting/stock_ledger', 'Member\ReportStockLedgerController');
/* End */

/* WAREHOUSE V2*/
AdvancedRoute::controller('/member/item/v2/warehouse', 'Member\WarehouseControllerV2');
/* End */

/* WIS */
AdvancedRoute::controller('/member/item/warehouse/wis', 'Member\WarehouseIssuanceSlipController');
/* End */

/* RR */
AdvancedRoute::controller('/member/item/warehouse/rr', 'Member\WarehouseReceivingReportController');
/* End */

/* WIS TO CUSTOMER */
AdvancedRoute::controller('/member/customer/wis', 'Member\CustomerWarehouseIssuanceSlipController');
/* End */

/* INVENTORY ADJUSTMENT */
AdvancedRoute::controller('/member/item/warehouse/inventory_adjustment', 'Member\WarehouseInventoryAdjustmentController');
/* End */

/* START PIS ARCY*/
Route::any('/member/pis/sir/view_status/{id}','Member\PurchasingInventorySystemController@view_status');

Route::any('/member/pis/sir','Member\PurchasingInventorySystemController@sir');
Route::any('/member/pis/sir/archived_submit','Member\PurchasingInventorySystemController@archived_sir_submit');
Route::any('/member/pis/sir/create','Member\PurchasingInventorySystemController@create_sir');
Route::any('/member/pis/sir/create_submit','Member\PurchasingInventorySystemController@create_sir_submit');
Route::any('/member/pis/sir/view/{id}/{type}','Member\PurchasingInventorySystemController@view');
Route::any('/member/pis/sir/view_pdf/{id}/{type_code}','Member\PurchasingInventorySystemController@view_pdf');
Route::any('/member/pis/sir/edit/{id}','Member\PurchasingInventorySystemController@edit_sir');
Route::any('/member/pis/sir/edit_submit','Member\PurchasingInventorySystemController@edit_sir_submit');
//reload sir
Route::any('/member/pis/sir_reload/{id}','Member\PisReloadController@index');
Route::any('/member/pis/sir/reload_submit','Member\PisReloadController@reload_submit');


Route::any('/member/pis/ilr/update_count_submit','Member\PurchasingInventorySystemController@update_count_submit');
Route::any('/member/pis/ilr/update_count/{sir_id}/{item_id}','Member\PurchasingInventorySystemController@update_count');

Route::any('/member/pis/ilr/update_count_empties_submit','Member\PurchasingInventorySystemController@update_count_empties_submit');
Route::any('/member/pis/ilr/update_count_empties/{s_cm_id}','Member\PurchasingInventorySystemController@update_count_empties');
//lof
Route::any('/member/pis/lof','Member\PurchasingInventorySystemController@lof');
Route::any('/member/pis/lof/archived_submit','Member\PurchasingInventorySystemController@archived_sir_submit');
Route::any('/member/pis/lof/create','Member\PurchasingInventorySystemController@create_sir');
Route::any('/member/pis/lof/create_submit','Member\PurchasingInventorySystemController@create_sir_submit');
Route::any('/member/pis/lof/edit/{id}','Member\PurchasingInventorySystemController@edit_sir');
Route::any('/member/pis/lof/edit_submit','Member\PurchasingInventorySystemController@edit_sir_submit');
//syncs
Route::any('/member/pis/sir/sync_import','Member\PurchasingInventorySystemController@sync_import');
Route::any('/member/pis/sir/sync_export','Member\PurchasingInventorySystemController@sync_export');
Route::any('/member/pis/sir/confirm_sync/{action}','Member\PurchasingInventorySystemController@confirm_syncing');

Route::any('/member/pis/ilr','Member\PurchasingInventorySystemController@load_ilr');
Route::any('/member/pis/ilr/ilr_submit','Member\PurchasingInventorySystemController@ilr_submit');
Route::any('/member/pis/ilr/{id}','Member\PurchasingInventorySystemController@ilr');
Route::any('/member/pis/sir/{id}/{action}','Member\PurchasingInventorySystemController@archived_sir');
Route::any('/member/pis/sir/open/{id}/{action}','Member\PurchasingInventorySystemController@open_sir');
Route::any('/member/pis/ilr/ilr_confirm/{id}/{action}','Member\PurchasingInventorySystemController@ilr_confirm');
Route::any('/member/pis/ilr/view/{id}','Member\PurchasingInventorySystemController@ilr_view');
Route::any('/member/pis/ilr/view_pdf/{id}','Member\PurchasingInventorySystemController@ilr_pdf');

//Manual Invoicing
Route::any('/member/pis/manual_invoice','Member\ManualInvoiceController@index');
Route::any('/member/pis/manual_invoice/add/{id}','Member\ManualInvoiceController@manual_invoice_add');
Route::any('/member/pis/manual_invoice/add_submit','Member\ManualInvoiceController@manual_invoice_add_submit');
Route::any('/member/customer/invoice/manual_invoice_update',"Member\ManualInvoiceController@manual_invoice_edit_submit");

Route::any('/member/pis/view_invoices/{id}','Member\ManualInvoiceController@view_invoices');
/* END PIS ARCY*/


/* START TABLET FUNCTIONS */
Route::any('/tablet/pis/sir/review/{id}',"Member\TabletPISController@review_sir");
Route::any('/tablet/pis/sir/{id}/{action}',"Member\TabletPISController@lof_action");
Route::any('/tablet/pis/sir/lof_action_submit',"Member\TabletPISController@lof_action_submit");
Route::any('/tablet/selected_sir',"Member\TabletPISController@selected_sir");
Route::any('/tablet/customer/load_rp/{id}','Member\TabletPISController@load_customer_rp');

Route::any('/tablet','Member\TabletPISController@login');
Route::any('/tablet/login_submit','Member\TabletPISController@login_submit');
Route::any('/tablet/dashboard','Member\TabletPISController@index');
Route::any('/tablet/sync_import',"Member\TabletPISController@sync_import");
Route::any('/tablet/sync_export','Member\TabletPISController@sync_export');
Route::any('/tablet/logout','Member\TabletPISController@logout');
Route::any('/tablet/sir_inventory/{id}','Member\TabletPISController@inventory_sir');

/*TABLET CUSTOMER*/
Route::any('/tablet/customer/modalcreatecustomer','Member\TabletPISController@edit_customer');
Route::any('/tablet/agent/edit/{id}','Member\TabletPISController@edit_agent');
Route::any('/tablet/agent/edit_submit','Member\TabletPISController@edit_agent_submit');

//RELOAD
Route::any('/tablet/sir_reload/{id}','Member\TabletPISController@sir_reload');

Route::any('/tablet/customer',"Member\TabletPISController@customer");
Route::any('/tablet/customer_details/{id}',"Member\TabletPISController@customer_details");

Route::any('/tablet/invoice','Member\TabletPISController@invoice');
Route::any('/tablet/view_invoices/{id}','Member\TabletPISController@view_invoices');
Route::any('/tablet/create_invoices/add','Member\TabletPISController@tablet_create_invoice');
Route::any('/tablet/create_invoice/add_submit','Member\TabletPISController@create_invoice_submit');
Route::any('/tablet/update_invoice/edit_submit',"Member\TabletPISController@update_invoice_submit");

Route::any('tablet/invoice/add_item/{id}','Member\TabletPISController@invoice_add_item');
Route::any('tablet/credit_memo/add_item/{id}/{type}','Member\TabletPISController@credit_memo_add_item');

Route::any('/tablet/receive_payment','Member\TabletPISController@receive_payment');
Route::any('/tablet/view_receive_payment/{id}','Member\TabletPISController@view_receive_payment');
Route::any('/tablet/receive_payment/add','Member\TabletPISController@tablet_receive_payment');
Route::any('/tablet/receive_payment/add_submit','Member\TabletPISController@add_receive_payment');
Route::any('/tablet/receive_payment/update/{id}','Member\TabletPISController@update_receive_payment');
Route::any('/tablet/customer/credit_memo/update_action',"Member\TabletPISController@update_action");

Route::any('/tablet/view_invoice_view/{id}','Member\TabletPISController@view_invoices_view');
Route::any('/tablet/view_invoice_pdf/{id}','Member\TabletPISController@view_invoice_pdf');

Route::any('/tablet/credit_memo','Member\TabletPISController@credit_memo');
Route::any('/tablet/credit_memo/add','Member\TabletPISController@add_cm');
Route::any('/tablet/customer/credit_memo/choose_type','Member\TabletPISController@cm_choose_type');
Route::any('/tablet/credit_memo/add_cm_submit','Member\TabletPISController@add_cm_submit');
Route::any('/tablet/credit_memo/edit_cm_submit','Member\TabletPISController@edit_cm_submit');
Route::any('/tablet/credit_memo/choose_type','Member\TabletPISController@cm_choose_type');

Route::any('/tablet/sales_receipt','Member\TabletPISController@sales_receipt');
Route::any('/tablet/sales_receipt/list','Member\TabletPISController@sales_receipt_list');
Route::any('/tablet/sales_receipt/create_submit','Member\TabletPISController@create_sales_receipt_submit');
Route::any('/tablet/sales_receipt/update_submit','Member\TabletPISController@update_sales_receipt_submit');

Route::any('/tablet/submit_all_transaction','Member\TabletPISController@confirm_submission');

Route::any('/tablet/submit_all_transaction/submit','Member\TabletPISController@submit_transactions');

Route::any('/tablet/sync_data/{table}/{date}','Member\TabletSyncController@sync');
Route::any('/tablet/update_sync_data','Member\TabletSyncController@sync_update');
Route::any('/tablet/get_data','Member\TabletSyncController@get_updates');
Route::any('/tablet/confirm_lof','Member\TabletSyncController@confirm_lof');
/* END PIS TABLET*/

 //form

//member
// Route::any('/member/order','Orders\OrdersController@orders');
// Route::get('/member/order/new_order','Orders\OrdersController@new_order');
// Route::post('/member/order/new_order/create_customer','Orders\OrdersController@create_customer');
// Route::post('/member/order/new_order/searchscustomer','Orders\OrdersController@searchscustomer');
// Route::any('/member/order/new_order/customerinfo','Orders\OrdersController@customerinfo');
// Route::post('/member/order/new_order/updateEmail','Orders\OrdersController@updateEmail');
// Route::post('/member/order/new_order/updateShipping','Orders\OrdersController@updateShipping');
// Route::post('/member/order/new_order/itemlist','Orders\OrdersController@itemlist');
// Route::post('/member/order/new_order/create_order','Orders\OrdersController@create_order');
// Route::post('/member/order/new_order/removeitemorder','Orders\OrdersController@removeitemorder');
// Route::post('/member/order/new_order/addIndiDiscount','Orders\OrdersController@addIndiDiscount');
// Route::post('/member/order/new_order/chagequantity','Orders\OrdersController@chagequantity');
// Route::post('/member/order/new_order/addMainDiscount','Orders\OrdersController@addMainDiscount');
// Route::post('/member/order/new_order/applytax','Orders\OrdersController@applytax');
// Route::post('/member/order/new_order/removecustomer','Orders\OrdersController@removecustomer');
// Route::post('/member/order/new_order/addshipping','Orders\OrdersController@addshipping');
// Route::post('/member/order/new_order/savetodraft','Orders\OrdersController@savetodraft');
// Route::post('/member/order/new_order/OrderStatus','Orders\OrdersController@OrderStatus');

// Route::get('/member/order/{id}','Orders\OrderListController@item');
Route::post('/member/order/addnote','Orders\OrderListController@addnote');
Route::post('/member/order/refunditem','Orders\OrderListController@refunditem');
Route::post('/member/order/recordrefund','Orders\OrderListController@recordrefund');

Route::get('/member/orders/draft','Orders\DraftController@index');
Route::get('/member/orders/abandoned','Orders\CheckOuts@index');

Route::get('/member/info','Store\StoreInfoController@storeInfo');
Route::post('/member/info/storeDescription','Store\StoreInfoController@storeDescription');
Route::get('/member/info/edit/{id}','Store\StoreInfoController@edit');
Route::post('/member/info/update','Store\StoreInfoController@update');
Route::get('/member/info/remove/{id}','Store\StoreInfoController@remove');

//contact start
Route::get('/member/contact','Store\StoreInfoController@contactInfo');
Route::post('/member/contact/craeteContact','Store\StoreInfoController@craeteContact');
//contact end

Route::any('/member/customer/customer_invoice_view/{id}','Member\Customer_InvoiceController@invoice_view');
Route::any('/member/customer/customer_invoice_pdf/{id}','Member\Customer_InvoiceController@invoice_view_pdf');
Route::any('/member/customer/invoice_list','Member\Customer_InvoiceController@invoice_list');
Route::get('/member/customer/invoice','Member\Customer_InvoiceController@index');
Route::any('/member/customer/invoice/error/{id}', 'Member\Customer_InvoiceController@error_inv_no');
Route::post('/member/customer/invoice/create','Member\Customer_InvoiceController@create_invoice');
Route::post('/member/customer/invoice/update','Member\Customer_InvoiceController@update_invoice');

//sales receipt
Route::get('/member/customer/sales_receipt','Member\Customer_SaleReceiptController@index');
Route::get('/member/customer/sales_receipt/list','Member\Customer_SaleReceiptController@sales_receipt_list');
Route::any('/member/customer/sales_receipt/create','Member\Customer_SaleReceiptController@create_sales_receipt');
Route::any('/member/customer/sales_receipt/update','Member\Customer_SaleReceiptController@update_sales_receipt');


Route::get('/member/customer/product_repurchase','Member\MLM_ProductRepurchaseController@index');//ERWIN
Route::post('/member/customer/product_repurchase/submit','Member\MLM_ProductRepurchaseController@submit');//ERWIN
Route::get('/member/customer/product_repurchase/get_product_code/{id}','Member\MLM_ProductRepurchaseController@get_code');//ERWIN
Route::get('/member/customer/product_repurchase/get_slot/{id}','Member\MLM_ProductRepurchaseController@get_slot');//ERWIN
Route::get('/member/customer/product_repurchase/get_slot_v_membership_code/{membership_code}','Member\MLM_ProductRepurchaseController@get_membership_code');//ERWIN
Route::get('/member/customer/product_repurchase/get_product_code/info/{id}','Member\MLM_ProductRepurchaseController@get_code_info');

Route::get('/member/about','Member\StoreInfoController@storeInfo');
Route::post('/member/about/storeDescription','Member\StoreInfoController@storeDescription');
Route::get('/member/about/edit/{id}','Member\StoreInfoController@edit');
Route::post('/member/about/update','Member\StoreInfoController@update');
Route::get('/member/about/remove/{id}','Member\StoreInfoController@remove');

//arcy
Route::any('/member/customer/credit_memo','Member\CreditMemoController@index');


/* Customer - Create Estimate */
Route::get('/member/customer/estimate_list','Member\Customer_EstimateController@index');
Route::any('/member/customer/estimate','Member\Customer_EstimateController@estimate');
Route::any('/member/customer/estimate/create','Member\Customer_EstimateController@create_submit');
Route::any('/member/customer/estimate/update','Member\Customer_EstimateController@update_submit');
Route::any('/member/customer/customer_estimate_view/{id}','Member\Customer_EstimateController@estimate_pdf');
Route::any('/member/customer/update_status/{id}','Member\Customer_EstimateController@update_status');
Route::any('/member/customer/update_status_submit','Member\Customer_EstimateController@update_status_submit');

// /member/customer/update_status_submit_continue
Route::any('/member/customer/prompt_update_status/{id}/{action}','Member\Customer_EstimateController@continue_update');
Route::any('/member/customer/update_status_submit_continue','Member\Customer_EstimateController@continue_update_submit');
Route::any('/member/customer/load_estimate_so/{id}','Member\Customer_EstimateController@load_all');
Route::any('/member/customer/load_est_so_item','Member\Customer_EstimateController@load_est_so_item');
Route::any('/member/customer/load_added_item/{est_id}','Member\Customer_EstimateController@add_item');
Route::any('/member/customer/estimate_remove/{est_id}','Member\Customer_EstimateController@remove_items');
/* Customer - Create Sales Order */
Route::get('/member/customer/sales_order_list','Member\Customer_SaleOrderController@index');
Route::any('/member/customer/sales_order','Member\Customer_SaleOrderController@sales_order');
Route::any('/member/customer/sales_order/create','Member\Customer_SaleOrderController@create_submit');
Route::any('/member/customer/sales_order/update','Member\Customer_SaleOrderController@update_submit');
Route::any('/member/customer/customer_sales_order_view/{id}','Member\Customer_SaleOrderController@so_pdf');

/* Customer - Receive Payment */
Route::get('/member/customer/receive_payment/list','Member\Customer_ReceivePaymentController@index');
Route::get('/member/customer/receive_payment','Member\Customer_ReceivePaymentController@receive_payment');
Route::get('/member/customer/load_rp/{id}','Member\Customer_ReceivePaymentController@load_customer_rp');
Route::post('/member/customer/receive_payment/add','Member\Customer_ReceivePaymentController@add_receive_payment');
Route::post('/member/customer/receive_payment/update/{id}','Member\Customer_ReceivePaymentController@update_receive_payment');
Route::get('/member/customer/receive_payment/apply_credit','Member\Customer_ReceivePaymentController@apply_credit');
Route::any('/member/customer/receive_payment/apply_credit_submit','Member\Customer_ReceivePaymentController@apply_credit_submit');
Route::any('/member/customer/receive_payment/load_apply_credit','Member\Customer_ReceivePaymentController@load_apply_credit');
Route::any('/member/customer/receive_payment/remove_apply_credit','Member\Customer_ReceivePaymentController@remove_apply_credit');
Route::any('/member/customer/receive_payment/view_pdf/{id}','Member\Customer_ReceivePaymentController@rp_pdf');

/* CUSTOMER CREDIT MEMO*/
Route::any('/member/customer/credit_memo','Member\CreditMemoController@index');
Route::any('/member/customer/credit_memo/choose_type','Member\CreditMemoController@choose_type');
Route::any('/member/customer/credit_memo/list',"Member\CreditMemoController@cm_list");
Route::any('/member/customer/credit_memo/create_submit','Member\CreditMemoController@create_submit');
Route::any('/member/customer/credit_memo/update',"Member\CreditMemoController@update_submit");

Route::any('/member/customer/credit_memo/update_action',"Member\CreditMemoController@update_action");
Route::any('/member/customer/credit_memo/choose_type','Member\CreditMemoController@choose_type');
Route::any('/member/customer/credit_memo/view_pdf/{id}','Member\CreditMemoController@cm_pdf');

/* Vendor Debit MEMO*/
Route::any('/member/vendor/debit_memo','Member\DebitMemoController@index');
Route::any('/member/vendor/debit_memo/list',"Member\DebitMemoController@db_list");
Route::any('/member/vendor/debit_memo/create_submit','Member\DebitMemoController@create_submit');
Route::any('/member/vendor/debit_memo/update',"Member\DebitMemoController@update_submit");

Route::any('/member/vendor/debit_memo/replace/{id}','Member\DebitMemoController@replace');
Route::any('/member/vendor/debit_memo/replace_item/{id}','Member\DebitMemoController@replace_item');
Route::any('/member/vendor/debit_memo/replace_submit','Member\DebitMemoController@replace_submit');
Route::any('/member/vendor/debit_memo/save_replace_submit','Member\DebitMemoController@save_replace_submit');
Route::any('/member/vendor/debit_memo/confirm_condemned/{id}/{action}','Member\DebitMemoController@confirm_condemned');
Route::any('/member/vendor/debit_memo/confirm_submit/{id}','Member\DebitMemoController@confirm_submit');
Route::any('/member/vendor/debit_memo/choose_type','Member\DebitMemoController@choose_type');

Route::any('/member/vendor/debit_memo/db_view_pdf/{id}','Member\DebitMemoController@db_view_pdf');
Route::any('/member/vendor/debit_memo/db_pdf/{id}','Member\DebitMemoController@db_pdf');

/* Vendor - Purchase Order */
Route::get('/member/vendor/purchase_order','Member\Vendor_PurchaseOrderController@index');
Route::get('/member/vendor/purchase_order/list','Member\Vendor_PurchaseOrderController@po_list');
Route::any('/member/vendor/purchase_order/create_po','Member\Vendor_PurchaseOrderController@create_po');
Route::any('/member/vendor/purchase_order/update_po','Member\Vendor_PurchaseOrderController@update_po');
Route::any('/member/vendor/purchase_order/view_pdf/{id}','Member\Vendor_PurchaseOrderController@view_po_pdf');
Route::any('/member/vendor/purchase_order/pdf/{id}','Member\Vendor_PurchaseOrderController@po_pdf');
Route::get('/member/vendor/purchase_order/void/{id}','Member\Vendor_PurchaseOrderController@void');
Route::post('/member/vendor/purchase_order/submit_void','Member\Vendor_PurchaseOrderController@submit_void');


Route::any('/member/vendor/load_added_item/{po_id}','Member\Vendor_PurchaseOrderController@add_item');

Route::get('/member/vendor/bill_list','Member\Vendor_CreateBillController@index');
Route::get('/member/vendor/create_bill','Member\Vendor_CreateBillController@create_bill');
Route::any('/member/vendor/load_purchase_order/{id}','Member\Vendor_CreateBillController@load_purchase_order');
Route::any('/member/vendor/create_bill/add','Member\Vendor_CreateBillController@add_bill');
Route::any('/member/vendor/create_bill/update','Member\Vendor_CreateBillController@update_bill');
Route::any('/member/vendor/load_po_item','Member\Vendor_CreateBillController@load_po_item');
Route::any('/member/vendor/po_remove/{id}','Member\Vendor_PurchaseOrderController@remove_items');

Route::any('/member/vendor/print_bill','Member\Vendor_CreateBillController@print_bill');

Route::any('/member/vendor/load_po_bill/{id}','Member\Vendor_CreateBillController@load_po_bill');

Route::any('/member/vendor/receive_inventory/list','Member\Vendor_ReceiveInventoryController@index');
Route::any('/member/vendor/receive_inventory','Member\Vendor_ReceiveInventoryController@receive_inventory');
Route::any('/member/vendor/receive_inventory/add','Member\Vendor_ReceiveInventoryController@add_receive_inventory');
Route::any('/member/vendor/receive_inventory/update','Member\Vendor_ReceiveInventoryController@update_receive_inventory');
Route::any('/member/vendor/receive_inventory/view_pdf/{id}','Member\Vendor_ReceiveInventoryController@ri_pdf');

// VENDOR PAYBILLS
Route::any('/member/vendor/paybill','Member\Vendor_PayBillController@index');
Route::any('/member/vendor/paybill/list','Member\Vendor_PayBillController@paybill_list');
Route::get('/member/vendor/load_pb/{id}','Member\Vendor_PayBillController@load_vendor_pb');
Route::any('/member/vendor/paybill/add','Member\Vendor_PayBillController@add_pay_bill');
Route::any('/member/vendor/paybill/count_transaction','Member\Vendor_PayBillController@count_transaction');
Route::any('/member/vendor/paybill/load_debit','Member\Vendor_PayBillController@load_debit');
Route::any('/member/vendor/paybill/update/{id}','Member\Vendor_PayBillController@update_pay_bill');
Route::any('/member/vendor/print_paybill','Member\Vendor_PayBillController@print_pay_bill');
Route::any('/member/vendor/paybill/apply_debit_submit','Member\Vendor_PayBillController@apply_debit_submit');
Route::any('/member/vendor/paybill/load_apply_debit','Member\Vendor_PayBillController@load_apply_debit');
Route::any('/member/vendor/paybill/remove_apply_debit','Member\Vendor_PayBillController@remove_apply_debit');


Route::any('/member/vendor/write_check','Member\Vendor_CheckController@write_check');
Route::any('/member/vendor/write_check/list','Member\Vendor_CheckController@check_list');
Route::any('/member/vendor/write_check/add','Member\Vendor_CheckController@add_check');
Route::any('/member/vendor/write_check/update','Member\Vendor_CheckController@update_check');
Route::any('/member/vendor/write_check/view_pdf/{id}','Member\Vendor_CheckController@wc_pdf');

/*Manufacturer*/
Route::any('/member/item/manufacturer','Member\ManufacturerController@manufacturer_list');
Route::any('/member/item/manufacturer/add','Member\ManufacturerController@index');
Route::any('/member/item/manufacturer/add_submit','Member\ManufacturerController@add_submit');
Route::any('/member/item/manufacturer/edit_submit','Member\ManufacturerController@edit_submit');
Route::any('/member/item/manufacturer/archived/{id}/{action}','Member\ManufacturerController@archived');
Route::any('/member/item/manufacturer/archived_submit','Member\ManufacturerController@archived_submit');

/* MAINTENACE */
Route::any('/member/maintenance/payment_method','Member\MaintenancePaymentMethodController@index');
Route::any('/member/maintenance/payment_method/add','Member\MaintenancePaymentMethodController@add');
Route::any('/member/maintenance/payment_method/add_submit','Member\MaintenancePaymentMethodController@add_submit');
Route::any('/member/maintenance/payment_method/edit_submit','Member\MaintenancePaymentMethodController@edit_submit');
Route::any('/member/maintenance/payment_method/archived/{id}/{action}','Member\MaintenancePaymentMethodController@archived');
Route::any('/member/maintenance/payment_method/archived_submit','Member\MaintenancePaymentMethodController@archived_submit');
Route::any('/member/maintenance/payment_method/update','Member\MaintenancePaymentMethodController@update_default');

Route::any('/member/maintenance/email_content','Member\EmailContentController@index');
Route::any('/member/maintenance/email_content/add','Member\EmailContentController@add');
Route::any('/member/maintenance/email_content/add_submit','Member\EmailContentController@add_submit');
Route::any('/member/maintenance/email_content/edit_submit','Member\EmailContentController@edit_submit');
Route::any('/member/maintenance/email_content/archived/{id}/{action}','Member\EmailContentController@archived');
Route::any('/member/maintenance/email_content/archived_submit','Member\EmailContentController@archived_submit');

Route::any('/member/maintenance/email_header_footer',"Member\EmailHeaderFooterController@index");
Route::any('/member/maintenance/email_header_footer/update/{type}',"Member\EmailHeaderFooterController@update");
Route::any('/member/maintenance/email_header_footer/update_submit',"Member\EmailHeaderFooterController@update_submit");

//contact start
Route::get('/member/contact','Member\StoreInfoController@contactInfo');
Route::post('/member/contact/createContact','Member\StoreInfoController@createContact');
Route::post('/member/contact/loadContact','Member\StoreInfoController@loadContact');
Route::get('/member/contact/remContact/{id}','Member\StoreInfoController@remContact');
Route::post('/member/contact/updateContact','Member\StoreInfoController@updateContact');
Route::post('/member/contact/displaycontact','Member\StoreInfoController@displaycontact');
//contact end

//location start
Route::post('/member/contact/createLocation','Member\StoreInfoController@createLocation');
Route::post('/member/contact/loadlocation','Member\StoreInfoController@loadlocation');
Route::get('/member/contact/removeLocation/{id}','Member\StoreInfoController@removeLocation');
Route::post('/member/contact/updateLocation','Member\StoreInfoController@updateLocation');
Route::post('/member/contact/setPrimary','Member\StoreInfoController@setPrimary');
//location end

//inventory start
Route::get('/member/product/inventory','Member\InventoryController@index');
Route::post('/member/product/inventory/updatquantity','Member\InventoryController@updatquantity');
Route::post('/member/product/inventory/filter','Member\InventoryController@filter');
//inventory end


//collectio start
Route::get('/member/product/collection','Member\ProductController@collections');
Route::post('/member/product/collection/collectionvisibility','Member\ProductController@collectionvisibility');
Route::get('/member/product/collection/edit/{id}','Member\ProductController@editcollection');
Route::post('/member/product/collection/update','Member\ProductController@updatecollection');
Route::post('/member/product/collection/updateitemCOllection','Member\ProductController@updateitemCOllection');
Route::get('/member/product/createcollection','Member\ProductController@createcollection');
Route::post('/member/product/additemcollection','Member\ProductController@additemcollection');
Route::post('/member/product/collectionitemvisibility','Member\ProductController@collectionitemvisibility');
Route::post('/member/product/removeitemcollection','Member\ProductController@removeitemcollection');
Route::post('/member/product/saveCollection','Member\ProductController@saveCollection');
//collection end


//ecommerce setting start
Route::group(array('prefix' => '/member/ecommerce/settings'), function()
{
	Route::get('','Member\EcommerceSettingController@setting');
	Route::get('/paypalsetting','Member\EcommerceSettingController@paypalsetting');
	Route::get('/banksetting','Member\EcommerceSettingController@banksetting');
	Route::get('/remittancesetting','Member\EcommerceSettingController@remittancesetting');
	Route::get('/cashondeliverysetting','Member\EcommerceSettingController@cashondeliverysetting');
	
	Route::any('/create_banking','Member\EcommerceSettingController@create_banking');
	Route::post('/insert_banking','Member\EcommerceSettingController@insert_banking');
	Route::get('/loadBankdata/{id}','Member\EcommerceSettingController@loadBankdata');
	Route::post('/updateBank','Member\EcommerceSettingController@updateBank');
	Route::get('/archive_warning_bank/{id}','Member\EcommerceSettingController@archive_warning_bank');
	Route::post('/archivedbank','Member\EcommerceSettingController@archivedbank');
	Route::get('/restore_warning_bank/{id}','Member\EcommerceSettingController@restore_warning_bank');
	Route::post('/restorebank','Member\EcommerceSettingController@restorebank');
	
	Route::any('/create_remittance','Member\EcommerceSettingController@create_remittance');
	Route::post('/insertremittance','Member\EcommerceSettingController@insertremittance');
	Route::get('/loadRemittancedata/{id}','Member\EcommerceSettingController@loadRemittancedata');
	Route::post('/updateremittance','Member\EcommerceSettingController@updateremittance');
	Route::get('/archive_warning_remittance/{id}','Member\EcommerceSettingController@archive_warning_remittance');
	Route::post('/archiveremittance','Member\EcommerceSettingController@archiveremittance');
	Route::get('/restore_warning_remittance/{id}','Member\EcommerceSettingController@restore_warning_remittance');
	Route::post('/restoreremittance','Member\EcommerceSettingController@restoreremittance');
	
	Route::post('/paypalsetting/updatepaypal','Member\EcommerceSettingController@updatepaypal');
	Route::post('/banksetting/settingupdate','Member\EcommerceSettingController@settingupdate');
});

//ecommerce setting end


Route::group(array('prefix' => '/member/report'), function()
{
	Route::get('','Member\ReportsController@index');
	
	/* SALE */
	Route::get('/sale/month','Member\ReportsController@monthlysale');
	Route::get('/sale/product_variant','Member\ReportsController@variantProduct');
	Route::get('/sale/product','Member\ReportsController@saleProduct');
	Route::get('/sale/customer','Member\ReportsController@saleCustomer');
	Route::get('/sale/customerOverTime','Member\ReportsController@customerOverTime');
	Route::post('/sale/ajax/monthlysale','Member\ReportsController@monthlysaleAjax');
	Route::post('/sale/ajax/by/{name}','Member\ReportsController@saleByAjax');
	Route::post('/sale/customerOverTime/ajax','Member\ReportsController@customerOTajax');
	Route::get('/sale/pdf/{name}/{start}/{end}','Member\ReportsController@pdfreport');

	/* Accounting Sales */
	Route::get('/accounting/sale','Member\ReportsController@accounting_sale');
	Route::post('/accounting/sale/edit/filter','Member\ReportsController@accounting_sale_filter_edit');
	Route::any('/accounting/sale/get/report/customer','Member\ReportsController@accounting_sale_report_view_customer');
	Route::any('/accounting/sale/get/report/items','Member\ReportsController@accounting_sale_report_view_items');

	/* Accounting Sales - per item */
	Route::any('/accounting/sale/item','Member\ReportsController@accounting_sale_items');
	Route::any('/accounting/sale/best_seller_item','Member\ReportsController@best_seller_item');
	Route::any('/accounting/sale/best_seller_item_by_pattern','Member\ReportsController@best_seller_item_by_pattern');
	Route::any('/accounting/sale/adjustment','Member\ReportsController@adjustment');

	/* Accounting Sales - per warehouse */
	Route::any('/accounting/sale_by_warehouse','Member\ReportsController@sale_by_warehouse');
	/* Accounting general ledger */
	Route::get('/accounting/general/ledger','Member\ReportsController@general_ledger');
	Route::any('/accounting/general/ledger/get','Member\ReportsController@general_ledger_get');

	/* Accounting Balance Sheet */
	Route::any('/accounting/balance_sheet','Member\ReportsController@balance_sheet');

	/* Accounting Sales by Manufacturer */
	Route::any('/accounting/manufacturer','Member\ReportSalesByManufacturerController@manufacturer');

	/* Accounting Profit and loss */
	Route::any('/accounting/profit_loss','Member\ReportsController@profit_loss');

	Route::any('/customer_auto_updates','Member\ReportsController@customer_auto_updates');
	/* Accounting Report List (Customer, Vendor, Item and Account) */
	Route::any('/accounting/customer_list','Member\ReportsController@customer_list');
	Route::any('/accounting/vendor_list','Member\ReportsController@vendor_list');
	Route::any('/accounting/item_list','Member\ReportsController@item_list');

	Route::any('/accounting/purchase/item_vendor_category','Member\ReportsController@item_vendor_category');

	Route::any('/accounting/account_list','Member\ReportsController@account_list');

	Route::any('/accounting/income_statement','Member\ReportsController@income_statement');
	Route::any('/accounting/quick_report','Member\ReportsController@quick_report');

	Route::any('/accounting/date_period','Member\ReportsController@get_date_period_covered');
	
	Route::any('/equipment_report','Member\ReportsController@equipment_report');

	Route::any('/item_status','Member\ReportsController@item_status');
	Route::any('/retain_credit','Member\ReportsController@retain_credit');
	Route::any('/sales_gain_item','Member\ReportsController@sales_gain_item');
	Route::any('/sales_bank_interest','Member\ReportsController@sales_bank_interest');
	Route::any('/bin_location','Member\ReportsController@bin_location');
	Route::any('/sales_cost_price_history','Member\ReportsController@sales_cost_price_history');

	//edrich 11/8/2018
	Route::any('/daily_sales_report','Member\CustomizedSalesReportController@daily_sales_report_receive');
	Route::any('/monthly_sales_report','Member\CustomizedSalesReportController@monthly_sales_report');
	//edrich 11/8/2018

	Route::any('/top_customer','Member\ReportsController@top_customer');
	Route::any('/accounts_receivable', 'Member\ReportsController@accounts_receivable');
	Route::any('/accounts_payable', 'Member\ReportsController@accounts_payable');
	Route::any('/sales_representative','Member\ReportsController@sales_representative');

	Route::any('/warehouse/wis','Member\ReportWISController@index');
	Route::any('/warehouse/wis/detailed/{id}','Member\ReportWISController@detailed');

	Route::any('/vendor_purchase_order','Member\ReportsController@vendor_purchase_order');

	/* DYANDCY CUSTOMIZE REPORTS */
	Route::any('/dyandcy/dailysales','Member\ReportsController@dyandcy_dailysales');
	Route::any('/dyandcy/incomepermonth','Member\ReportsController@dyandcy_incomepermonth');
	Route::any('/dyandcy/weeklyreport','Member\ReportsController@dyandcy_weeklyreport');
	Route::any('/dyandcy/monthlyreport','Member\ReportsController@dyandcy_monthlyreport');
	Route::any('/dyandcy/annualreport','Member\ReportsController@dyandcy_annualreport');

});

AdvancedRoute::controller('/member/report', 'Member\ReportControllerV2');

AdvancedRoute::controller('/member/maintenance/landing_cost',"Member\LandingCostController");

//reports end

/* Customer */
Route::get('/member/customer','Member\CustomerController@index');
Route::get('/member/customer/bulk_archive','Member\CustomerController@bulk_archive');
Route::post('/member/customer/bulk_archive','Member\CustomerController@bulk_archive_post');
Route::get('/member/customer/list','Member\CustomerController@index');
Route::any('/member/customer/viewlead/{id}','Member\CustomerController@viewlead');
Route::any('/member/customer/modalcreatecustomer','Member\CustomerController@modalcreatecustomer');
Route::post('/member/customer/insertcustomer','Member\CustomerController@insertcustomer');
Route::post('/member/customer/editcustomer','Member\CustomerController@editcustomer');
Route::post('/member/customer/updatecustomer','Member\CustomerController@updatecustomer');
Route::post('/member/customer/createpaymentmethod','Member\CustomerController@createpaymentmethod');
Route::post('/member/customer/createterms','Member\CustomerController@createterms');
Route::post('/member/customer/createcustomer','Member\CustomerController@createcustomer');
Route::post('/member/customer/uploadcustomerfile','Member\CustomerController@uploadcustomerfile');
Route::post('/member/customer/removefilecustomer','Member\CustomerController@removefilecustomer');
Route::any('/member/customer/customeredit/{id}','Member\CustomerController@customeredit');
Route::post('/member/customer/updatecustomermain','Member\CustomerController@updatecustomermain');
Route::get('/member/customer/downloadfile/{id}','Member\CustomerController@downloadfile');
Route::any('/member/customer/loadcustomer','Member\CustomerController@loadcustomer');
Route::post('/member/customer/inactivecustomer','Member\CustomerController@inactivecustomer');
Route::get('/member/customer/details/{id}','Member\CustomerController@view_customer_details');


/* API v1*/
Route::group(array('prefix' => 'api/{auth}/{store}'), function()
{
	Route::get('products', 'ApiController@products');
	Route::get('products/{id}', 'ApiController@product_id');
	Route::get('products/{id}/variants', 'ApiController@products_id_variants');
	Route::get('products/{id}/related', 'ApiController@products_id_related');
	Route::get('variants', 'ApiController@variants');
	Route::get('category', 'ApiController@category');
	Route::get('filter/products/{column}/{value}', 'ApiController@filter_products');

	Route::post('user/create','ApiController@create_user');
	Route::post('user/login','ApiController@login_user');
	Route::post('user/info','ApiController@user_info');
	Route::post('user/getShoppingHistory','ApiController@getShoppingHistory');
	Route::post('user/SpecificHistory','ApiController@SpecificHistory');
	Route::post('user/update/{id}','ApiController@user_update_id');
	
	Route::get('country','ApiController@country');
	Route::post('payment','ApiController@payment');
	Route::get('contact','ApiController@contact');
	Route::get('location','ApiController@location');
	Route::get('about','ApiController@about');
	Route::get('shipping','ApiController@shipping');
	Route::get('paymentsetting','ApiController@paymentsetting');
});

Route::group(['middleware' => ['web']], function () {
    Route::get('payPremium', ['as'=>'payPremium','uses'=>'PaypalController@payPremium']);

    Route::post('getCheckout', ['as'=>'getCheckout','uses'=>'PaypalController@getCheckout']);

    Route::get('getDone', ['as'=>'getDone','uses'=>'PaypalController@getDone']);

    Route::get('getCancel', ['as'=>'getCancel','uses'=>'PaypalController@getCancel']);
});

Route::get('/testing','Member\MLM_CodeController@tester');

/* ImageController */
Route::post('image/image_upload','Member\ImageController@upload_image');
Route::get('image/load_media_library', 'Member\ImageController@load_media_library');

/* Load Ajax Data */
Route::get('/member/accounting/load_coa','Member\ChartOfAccountController@load_coa');
Route::get('/member/customer/load_customer','Member\CustomerController@load_customer');
Route::get('/member/vendor/load_vendor','Member\VendorController@load_vendor');
Route::get('/member/item/load_item','Member\ItemController@load_item');
Route::get('/member/item/load_item_category','Member\ItemController@load_item_category');
Route::get('/member/ecommerce/load_product_category','Member\EcommerceProductController@load_product_category');
Route::get('/member/item/load_um','Member\UnitOfMeasurementController@load_um');
Route::get('/member/item/load_one_um/{id}','Member\UnitOfMeasurementController@load_one_um');
Route::get('/member/item/load_one_um_multi/{id}','Member\UnitOfMeasurementController@load_one_um_multi');
// Route::get('/member/item/load_one_um/{id}','Member\UnitOfMeasurementController@load_one_um');
Route::get('/member/item/load_category','Member\Manage_Category_Controller@load_category');
Route::get('/member/item/manufacturer/load_manufacturer','Member\ManufacturerController@load_manufacturer');
Route::get('/member/maintenance/load_payment_method','Member\MaintenancePaymentMethodController@load_payment_method');
Route::get('/member/maintenance/load_payment_gateway/{id}','Member\OnlinePaymentMethodController@load_payment_gateway');

Route::any('/member/ecommerce/product/ecom_load_product_table', 'Member\EcommerceProductController@ecom_load_product_table');
/* SettingsController */
Route::get('/member/settings', 'Member\SettingsController@all');
Route::get('/member/settings/{key}', 'Member\SettingsController@index');
Route::post('/member/settings/verify/add', 'Member\SettingsController@verify');
Route::get('/member/settings/get/{key}', 'Member\SettingsController@get_settings');
Route::get('/member/settings/setup/initial', 'Member\SettingsController@initial_setup');
Route::post('/member/settings/terms/set', 'Member\SettingsController@set_terms');
Route::any('/member/settings/signatories/all', 'Member\SettingsController@signatories');
Route::any('/member/settings/signatories/submit', 'Member\SettingsController@signatories_submit');
/* End SettingsController */


/* USER / UTILITIES*/
Route::any('/member/utilities/admin-list/ismerchant', 'Member\UtilitiesController@ismerchant');
AdvancedRoute::controller('/member/utilities', 'Member\UtilitiesController');
/* End */

/*  / Merchant - Commission - markup*/
Route::any('/member/merchant/markup', 'Member\MerchantController@index');
Route::any('/member/merchant/markup/update', 'Member\MerchantController@update');
Route::any('/member/merchant/markup/update/piece', 'Member\MerchantController@update_per_piece');

Route::any('/member/merchant/commission', 'Member\MerchantController@commission');
Route::any('/member/merchant/commission/user/{id}', 'Member\MerchantController@commission_user');
Route::any('/member/merchant/commission/user/request_update/{id}', 'Member\MerchantController@commission_user_request_update');
Route::any('/member/merchant/commission/user/request_submit/submit', 'Member\MerchantController@commission_user_request_update_submit');
Route::any('/member/merchant/commission/request', 'Member\MerchantController@commission_request');
Route::any('/member/merchant/commission/request/range/verfiy', 'Member\MerchantController@commission_range_verify');
Route::any('/member/merchant/commission/request/submit', 'Member\MerchantController@commission_request_submit');
/* End */

// Merchant Commission Report
Route::get('/member/merchant/commission-report', 'Member\MerchantController@commission_report');
Route::get('/member/merchant/commission-report/getpercentage','Member\MerchantController@get_percentage');
Route::post('/member/merchant/commission-report-pass','Member\MerchantController@submit_report_setting');
Route::get('/member/merchant/commission-report-pass','Member\MerchantController@password');
Route::get('/member/merchant/commission_report/table', 'Member\MerchantController@table');
Route::any('/member/merchant/commission_report/export','Member\MerchantController@export');
Route::get('/member/merchant/commission_report/import','Member\MerchantController@import');
Route::post('/member/merchant/commission_report/import','Member\MerchantController@import_submit');
// end

/*  / Merchant - Ewallet*/
Route::any('/member/merchant/ewallet', 'Member\MerchantewalletController@index');
Route::any('/member/merchant/ewallet/list', 'Member\MerchantewalletController@payable_list');
Route::any('/member/merchant/ewallet/request', 'Member\MerchantewalletController@request');
Route::any('/member/merchant/ewallet/request/verfiy', 'Member\MerchantewalletController@verify');
Route::any('/member/merchant/ewallet/request/verfiy/submit', 'Member\MerchantewalletController@verify_submit');
Route::any('/member/merchant/ewallet/request/update', 'Member\MerchantewalletController@request_update');
Route::any('/member/merchant/ewallet/request/update/submit', 'Member\MerchantewalletController@request_update_submit');
/* End */

/*  / Merchant - Report*/
Route::any('/member/merchant/report', 'Member\MerchantReportController@index');
Route::any('/member/merchant/report/view', 'Member\MerchantReportController@view_report');
/* End */

/* ECOMMERCE PRODUCT */
AdvancedRoute::controller('/member/ecommerce/product', 'Member\EcommerceProductController');
/* End */
/* VENDOR */
AdvancedRoute::controller('/member/vendor', 'Member\VendorController');
/* End */
/* ONLINE PAYMENT METHOD */
AdvancedRoute::controller('/member/maintenance/online_payment', 'Member\OnlinePaymentMethodController');
/* End */
/* ONLINE PAYMENT METHOD */
AdvancedRoute::controller('/member/maintenance/sms', 'Member\SmsController');
/* End */
/* ITEM IMPORT*/
AdvancedRoute::controller('/member/item/import', 'Member\ImportController');
/* End */
/* CUSTOMER IMPORT*/
AdvancedRoute::controller('/member/customer/import', 'Member\ImportController');
/* End */
/* VENDOR IMPORT*/
AdvancedRoute::controller('/member/vendors/import', 'Member\ImportController');
/* End */
/* CHART OF ACCOUNTS IMPORT*/
AdvancedRoute::controller('/member/accounting/import', 'Member\ImportController');
/* End */
/* ECOMMERCE COUPON CODE*/
AdvancedRoute::controller('/member/ecommerce/coupon', 'Member\CouponVoucherController');
/* End */
/* ACCOUNTING JOURNAL*/
AdvancedRoute::controller('/member/accounting/journal', 'Member\JournalEntryController');
/* End */
/* TERMS OF PAYMENT*/
AdvancedRoute::controller('/member/maintenance/terms', 'Member\TermsOfPaymentController');
/* End */
/* LOCATION*/
AdvancedRoute::controller('/member/maintenance/location', 'Member\LocationController');
/* End */


/* LOCATION*/
AdvancedRoute::controller('/member/maintenance/location', 'Member\LocationController');
/* End */

/* TRACKINGS */
AdvancedRoute::controller('/member/ecommerce/trackings', 'Member\TrackingsController');
/* END */



/* MEMBER SHIPPING*/
AdvancedRoute::controller('/member/warehouse/migration', 'Member\WarehouseMigrateController');
/* End */

/* MEMBER SHIPPING*/
AdvancedRoute::controller('/member/register/shipping', 'MemberController');
/* End */

/* MEMBER SHIPPING*/
AdvancedRoute::controller('/member/maintenance/app_keys', 'Member\SocialNetworkingKeysController');
/* End */

/* MEMBER COLUMNS */
AdvancedRoute::controller('/member/columns', 'Member\ColumnsController');
/* End */

/* EVENTS */
AdvancedRoute::controller('/member/page/events','Member\EventController');

AdvancedRoute::controller('/tester','TesterController');

// test lang load
Route::any("/member/load_position","Member\EmployeePositionController@load_position");

/*SEARCH INVOICE CONTROLLER*/
Route::any('/member/search/invoice','Member\SearchInvoiceController@index');
Route::any('/member/search/invoice/table','Member\SearchInvoiceController@table');

//core dev testing
Route::any("/kim/core","Core\Times2@TimeExist");
Route::any("/kim/timeshift","Core\Times2@time_shift");
Route::any("/kim/compute_time","Core\Times2@compute_time");
Route::any("/kim/compute_flexi_time","Core\Times2@compute_flexi_time");
//end core testing

/* PAYROLL START */
// Route::group(array('prefix' => '/member/payroll'), function()
// {
// 	/* COMPANY START */
// 	Route::any('/company_list','Member\PayrollController@company_list');
// 	Route::any('/company_list/modal_create_company','Member\PayrollController@modal_create_company');
// 	Route::any('/company_list/upload_company_logo','Member\PayrollController@upload_company_logo');
// 	Route::any('/company_list/modal_save_company','Member\PayrollController@modal_save_company');
// 	Route::any('/company_list/view_company_modal/{id}','Member\PayrollController@view_company_modal');
// 	Route::any('/company_list/edit_company_modal/{id}','Member\PayrollController@edit_company_modal');
// 	Route::any('/company_list/reload_company','Member\PayrollController@reload_company');
// 	Route::any('/company_list/archived_company','Member\PayrollController@archived_company');
// 	Route::any('/company_list/update_company','Member\PayrollController@update_company');
// 	/* COMPANY END */


// 	/* EMPLOYEE START */
// 	Route::any('/employee_list','Member\PayrollController@employee_list');
// 	Route::any('/employee_list/modal_create_employee','Member\PayrollController@modal_create_employee');

// 	/* EMPLOYEE END */

// 	Route::any('/payroll_configuration','Member\PayrollController@payroll_configuration');

// 	Route::any('/employee_timesheet','Member\PayrollTimesheetController@index');


// 	/* DEPARTMENT START */
// 	Route::any('/departmentlist','Member\PayrollController@department_list');
// 	Route::any('/departmentlist/department_modal_create','Member\PayrollController@department_modal_create');
// 	Route::any('/departmentlist/department_save','Member\PayrollController@department_save');
// 	Route::any('/departmentlist/archived_department','Member\PayrollController@archived_department');
// 	Route::any('/departmentlist/department_reload','Member\PayrollController@department_reload');
// 	Route::any('/departmentlist/modal_view_department/{id}','Member\PayrollController@modal_view_department');
// 	Route::any('/departmentlist/modal_edit_department/{id}','Member\PayrollController@modal_edit_department');
// 	Route::any('/departmentlist/modal_update_department','Member\PayrollController@modal_update_department');
// 	/* DEPARTMENT END */

// 	/* JOB TITLE START */ 
// 	Route::any("/jobtitlelist","Member\PayrollController@jobtitle_list");
// 	Route::any("/jobtitlelist/modal_create_jobtitle","Member\PayrollController@modal_create_jobtitle");
// 	Route::any("/jobtitlelist/modal_save_jobtitle","Member\PayrollController@modal_save_jobtitle");
// 	Route::any("/jobtitlelist/reload_tbl_jobtitle","Member\PayrollController@reload_tbl_jobtitle");
// 	Route::any("/jobtitlelist/modal_view_jobtitle/{id}","Member\PayrollController@modal_view_jobtitle");
// 	Route::any("/jobtitlelist/modal_edit_jobtitle/{id}","Member\PayrollController@modal_edit_jobtitle");
// 	Route::any("/jobtitlelist/archived_jobtitle","Member\PayrollController@archived_jobtitle");
// 	Route::any("/jobtitlelist/modal_update_jobtitle","Member\PayrollController@modal_update_jobtitle");
// 	/* JOB TITLE END */
// });
include_once('routes_config/routes_payroll.php');
/* PAYROLL END */

include_once('routes_config/routes_project.php');

/* PAYMENT FACILITIES */
include_once('routes_config/routes_payment.php');
include_once('routes_config/routes_reward.php');
include_once('routes_config/routes_cashier.php');
include_once('routes_config/routes_item.php');

/* Members Area */
include_once('routes_config/routes_members_area.php');

include_once('routes_config/routes_transaction.php');

/*PAYROLL EMPLOYEE*/
include_once('routes_config/routes_payroll_employee.php');
Route::get('/ref/{id}', 'Shop\LeadController@ref');
Route::get('/{id}', 'Shop\LeadController@ref');


// Item Redeemable
Route::get('member/item/redeemable','Member\RedeemableItemController@index');
Route::get('member/item/redeemable/add','Member\RedeemableItemController@add');
Route::post('member/item/redeemable/add','Member\RedeemableItemController@submit_add');
Route::get('member/item/redeemable/redeemable_table', 'Member\RedeemableItemController@table');
Route::get('member/item/redeemable/archive', 'Member\RedeemableItemController@archive');
Route::get('member/item/redeemable/modify','Member\RedeemableItemController@modify');
Route::post('member/item/redeemable/modify','Member\RedeemableItemController@submit_modify');
