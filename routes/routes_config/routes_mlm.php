<?php
Route::get('/mlm/membership_active_code/{pin}', 'Mlm\MlmLoginController@membership_active_code'); 
Route::post('/mlm/membership_active_code/active/code', 'Mlm\MlmLoginController@membership_active_code_post'); 
Route::get('/mlm/login', 'Mlm\MlmLoginController@index'); 
Route::post('/mlm/login', 'Mlm\MlmLoginController@post_login'); 

Route::get('/mlm/login/forgot_password','Mlm\MlmLoginController@forgot_password'); //ARCY
Route::any('/mlm/login/forgot_password/submit','Mlm\MlmLoginController@forgot_password_submit'); //ARCY

Route::get('/mlm/register', 'Mlm\MlmRegisterController@index'); 
Route::get('/mlm/register/package', 'Mlm\MlmRegisterController@package'); 
Route::get('/mlm/register/payment', 'Mlm\MlmRegisterController@payment');
Route::post('/mlm/register', 'Mlm\MlmRegisterController@post_register');

Route::get('/mlm/register/get/membership_code/{membership_code}', 'Mlm\MlmRegisterController@view_customer_info_via_mem_code');

Route::get('/mlm/process_order_queue', 'Mlm\MlmDashboardController@process_order_queue'); 

Route::get('/mlm', 'Mlm\MlmDashboardController@index'); 
Route::get('/mlm/news/{id}', 'Mlm\MlmDashboardController@news_content'); 
Route::get('/mlm/profile', 'Mlm\MlmProfileController@index'); 
Route::get('/mlm/notification', 'Mlm\MlmNotificationController@index'); 
Route::any('/mlm/claim/slot', 'Mlm\MlmDashboardController@claim_slot'); 
Route::get('/mlm/lead', 'Mlm\MlmDashboardController@lead'); 

Route::any('/mlm/network/binary', 'Mlm\MlmNetworkController@binary'); 
Route::any('/mlm/network/unilevel', 'Mlm\MlmNetworkController@unilevel'); 
//
Route::any('/mlm/profile/edit/password', 'Mlm\MlmProfileController@password'); 
Route::any('/mlm/profile/edit/contact', 'Mlm\MlmProfileController@contact');
Route::any('/mlm/profile/edit/basic', 'Mlm\MlmProfileController@basic');
Route::any('/mlm/profile/edit/picture', 'Mlm\MlmProfileController@profile_picture_upload');
Route::any('/mlm/profile/edit/encashment', 'Mlm\MlmProfileController@update_encashment');
//
Route::get('/mlm/repurchase', 'Mlm\MlmRepurchaseController@index'); 
Route::get('/mlm/repurchase/cart', 'Mlm\MlmRepurchaseController@cart'); 
Route::get('/mlm/repurchase/add_cart', 'Mlm\MlmRepurchaseController@add_cart'); 
Route::get('/mlm/repurchase/remove_item', 'Mlm\MlmRepurchaseController@remove_item'); 
Route::get('/mlm/repurchase/clear_cart', 'Mlm\MlmRepurchaseController@clear_cart'); 
Route::get('/mlm/repurchase/checkout', 'Mlm\MlmRepurchaseController@checkout'); 
Route::post('/mlm/repurchase/checkout/submit', 'Mlm\MlmRepurchaseController@checkout_submit'); 

Route::get('/mlm/vouchers', 'Mlm\MlmVouchersController@index'); 
Route::get('/mlm/cheque', 'Mlm\MlmChequeController@index'); 
Route::any('/mlm/changeslot', 'Mlm\Mlm@changeslot');

Route::get('/mlm/cheque', 'Mlm\MlmChequeController@index'); 
Route::get('/mlm/wallet', 'Mlm\MlmTransferController@index'); 
Route::get('/mlm/refill', 'Mlm\MlmTransferController@refill'); 
Route::get('/mlm/refill/request', 'Mlm\MlmTransferController@request_refill'); 
Route::any('/mlm/refill/request/submit', 'Mlm\MlmTransferController@request_refill_post'); 
Route::get('/mlm/transfer', 'Mlm\MlmTransferController@transfer'); 
Route::post('/mlm/transfer/submit', 'Mlm\MlmTransferController@transfer_submit'); 
Route::get('/mlm/transfer/get/customer/{id}', 'Mlm\MlmTransferController@transfer_get_customer'); 
Route::get('/mlm/encashment', 'Mlm\MlmTransferController@encashment'); 
Route::post('/mlm/encashment/request', 'Mlm\MlmTransferController@encashment_request'); 
Route::any('/mlm/encashment/view/breakdown/{encashment_process}/{slot_id}', 'Mlm\MlmTransferController@breakdown_slot');//luke

/* MLM VOUCHERS */
Route::get('/mlm/vouchers', 'Mlm\MlmVouchersController@index'); 
Route::get('/mlm/vouchers/view_voucher', 'Mlm\MlmVouchersController@view_voucher'); 
Route::get('/mlm/gc', 'Mlm\MlmVouchersController@gc'); 

/* MLM GENEALOGY */
Route::get('/mlm/genealogy/{tree}', 'Mlm\MlmGenealogyController@index'); 
Route::any('/mlm/slot/genealogy', 'Mlm\MlmGenealogyController@tree');
Route::any('/mlm/slot/genealogy/downline', 'Mlm\MlmGenealogyController@downline');
/* MLM GENEALOGY REPORTS */

/* MLM MEMBER REPORTS */
Route::get('/mlm/report/{complan}', 'Mlm\MlmReportController@index'); 
Route::get('/mlm/report/discount_card/use', 'Mlm\MlmDiscountCardController@use_discount'); 
Route::get('/mlm/report/discount_card/use/get/customer/{id}', 'Mlm\MlmDiscountCardController@get_customer_info'); 
Route::post('/mlm/report/discount_add/use/submit', 'Mlm\MlmDiscountCardController@submit_use_discount_card'); 
Route::post('/mlm/report/binary_promotions/request', 'Mlm\MlmReportController@request_binary_promotions'); 
Route::get('/mlm/report/merchant_school/get', 'Mlm\MlmReportController@merchant_school_get'); 
/* End MLM MEMBER REPORTS */

/* MLM SLOTs */
Route::get('/mlm/slots', 'Mlm\MlmSlotsController@index'); 
Route::post('/mlm/slots/set_nickname', 'Mlm\MlmSlotsController@set_nickname'); 
Route::get('/mlm/slots/upgrade_slot/{id}', 'Mlm\MlmSlotsController@upgrade_slot'); 
Route::post('/mlm/slots/upgrade_slot_post/{id}', 'Mlm\MlmSlotsController@upgrade_slot_post'); 
Route::get('/mlm/slots/manual_add_slot', 'Mlm\MlmSlotsController@manual_add_slot'); 
Route::post('/mlm/slots/manual_add_slot_post', 'Mlm\MlmSlotsController@manual_add_slot_post'); 
/* ---- TRANSFER SLOT*/
Route::post('/mlm/slots/before_transfer_slot', 'Mlm\MlmSlotsController@before_transfer_slot'); 
Route::get('/mlm/slots/transfer_slot', 'Mlm\MlmSlotsController@transfer_slot'); 
Route::post('/mlm/slots/transfer_slot_post', 'Mlm\MlmSlotsController@transfer_slot_post'); 

/* ---- PRODUCT CODE V2 ----*/
Route::any('/mlm/slot/use_product_code','Mlm\MlmSlotsController@use_product_code');
Route::any('/mlm/slot/use_product_code/validate','Mlm\MlmSlotsController@use_product_code_validate');
Route::any('/mlm/slot/use_product_code/to_slot','Mlm\MlmSlotsController@to_slot');
Route::any('/mlm/slot/use_product_code/confirmation','Mlm\MlmSlotsController@confirmation');
Route::any('/mlm/slot/use_product_code/confirmation/submit','Mlm\MlmSlotsController@confirmation_submit');
Route::any('/mlm/slot/use_product_code/confirmation/used','Mlm\MlmSlotsController@use_submit');

Route::any('/mlm/popup/message','Mlm\MlmSlotsController@message');	

/* ---- PRODUCT CODE */
Route::get('/mlm/slots/item_code', 'Mlm\MlmSlotsController@item_code'); 
Route::post('/mlm/slots/item_code_post', 'Mlm\MlmSlotsController@item_code_post');

Route::get('/mlm/slots/transfer_item_code', 'Mlm\MlmSlotsController@transfer_item_code'); 
Route::post('/mlm/slots/transfer_item_code_post', 'Mlm\MlmSlotsController@transfer_item_code_post'); 

Route::get('/mlm/slots/transfer_mem_code', 'Mlm\MlmSlotsController@transfer_mem_code'); 
Route::post('/mlm/slots/transfer_mem_code_post', 'Mlm\MlmSlotsController@transfer_mem_code_post'); 
/* MLM SLOTs REPORTS */


/* MLM WALLET ABS */
Route::get('/mlm/wallet/tours', 'Mlm\MlmWalletAbsController@index');
Route::post('/mlm/wallet/tours/update', 'Mlm\MlmWalletAbsController@update_info');  
Route::post('/mlm/wallet/tours/transfer', 'Mlm\MlmWalletAbsController@transfer_wallet'); 

/* MLM V-MONEY WALLET */
Route::get('/mlm/wallet/vmoney', 'Mlm\MlmWalletVMoneyController@index');
Route::post('/mlm/wallet/vmoney/transfer', 'Mlm\MlmWalletVMoneyController@transfer');

/* Modal Add Slot */
Route::get('/mlm/slot/add', 'Mlm\MlmSlotsController@add_slot_modal');
Route::any('/mlm/slot/check_add', 'Mlm\MlmSlotsController@check_add');

/* MANUAL ADD FOR SLOT AREA ON MLM (MEMBERSHIP ENTRY CODE) */
Route::get('/mlm/slot/manual_add', 'Mlm\MlmSlotsController@manual_add_slot_modal');
Route::post('/mlm/slot/manual_add_post', 'Mlm\MlmSlotsController@manual_add_slot');
