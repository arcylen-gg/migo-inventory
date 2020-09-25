<?php
/* MEMBER - MULTILEVEL MARKETING */
Route::any('/member/mlm/membership', 'Member\MLM_MembershipController@index'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/add', 'Member\MLM_MembershipController@add'); //GUILLERMO TABLIGAN
Route::post('/member/mlm/membership/add/save', 'Member\MLM_MembershipController@save'); // LUKE GLENN JORDAN
Route::any('/member/mlm/membership/edit/{membership_id}', 'Member\MLM_MembershipController@edit'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/edit/add/member/product', 'Member\MLM_MembershipController@edit_add_membership_product'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/edit/{membership_id}/add_package', 'Member\MLM_MembershipController@add_package'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/popup', 'Member\MLM_MembershipController@popup'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/popups', 'Member\MLM_MembershipController@popup'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/edit/{membership_id}/package/edit/{membership_package_id}', 'Member\MLM_MembershipController@edit_package');
Route::any('/member/mlm/membership/edit/{membership_id}/package/view/product', 'Member\MLM_MembershipController@view_product'); //LUKE GLENN JORDAN
Route::any('/member/mlm/membership/edit/{membership_id}/package/{package_id}', 'Member\MLM_MembershipController@edit_package'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/delete/{membership_id}', 'Member\MLM_MembershipController@delete'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/restore/{membership_id}', 'Member\MLM_MembershipController@restore'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/add/package/save', 'Member\MLM_MembershipController@save_package'); // Luke
Route::any('/member/mlm/membership/view/package/{membership_id}', 'Member\MLM_MembershipController@get_packages_with_view'); //Luke
Route::any('/member/mlm/membership/edit/package/{membership_package_id}', 'Member\MLM_MembershipController@edit_package_popup'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/edit/package/archive/{membership_package_id}', 'Member\MLM_MembershipController@edit_package_popup_archive'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/membership/edit/package/save/submit', 'Member\MLM_MembershipController@save_package_popup'); 
Route::any('/member/mlm/membership/change_picture', 'Member\MLM_MembershipController@change_picture_package'); //GUILLERMO TABLIGAN

Route::any('/member/mlm/code', 'Member\MLM_CodeController@index'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/code/block/{id}', 'Member\MLM_CodeController@block'); //ERWIN
Route::any('/member/mlm/code/block_submit', 'Member\MLM_CodeController@block_submit'); //ERWIN
Route::any('/member/mlm/code/sell', 'Member\MLM_CodeController@sell'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/code/sell/add_line', 'Member\MLM_CodeController@add_line'); //Luke
Route::any('/member/mlm/code/sell/add_line/view', 'Member\MLM_CodeController@view_all_lines'); //Luke
Route::any('/member/mlm/code/sell/add_line/submit', 'Member\MLM_CodeController@addl_line_submit'); //Luke
Route::any('/member/mlm/code/sell/clear_line_all', 'Member\MLM_CodeController@clear_line_all'); //Luke
Route::any('/member/mlm/code/sell/clear_line/{id}', 'Member\MLM_CodeController@remove_one_line'); //Luke
Route::any('/member/mlm/code/sell/compute', 'Member\MLM_CodeController@compute');//Luke
Route::any('/member/mlm/code/sell/process', 'Member\MLM_CodeController@process');// ERWIN
Route::any('/member/mlm/code/receipt/', 'Member\MLM_CodeController@receipt'); //ERWIN
Route::any('/member/mlm/code/receipt/view/{id}', 'Member\MLM_CodeController@view_receipt'); //ERWIN


Route::any('/member/mlm/code2', 'Member\MLM_CodeControllerV2@membership_code'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/code2/table', 'Member\MLM_CodeControllerV2@membership_code_table'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/code2/assemble', 'Member\MLM_CodeControllerV2@membership_code_assemble'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/code2/assemble/table', 'Member\MLM_CodeControllerV2@membership_code_assemble_table'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/code2/change_status','Member\MLM_CodeControllerV2@change_status');
Route::any('/member/mlm/code2/disassemble', 'Member\MLM_CodeControllerV2@membership_code_disassemble'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/product_code2', 'Member\MLM_CodeControllerV2@index'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/product_code2/table', 'Member\MLM_CodeControllerV2@product_code_table'); //GUILLERMO TABLIGAN

Route::any('/member/mlm/report_codes', 'Member\MLM_CodeControllerV2@report_code'); //ARCY
Route::any('/member/mlm/report_codes/data','Member\MLM_CodeControllerV2@report_code_table');
Route::any('/member/mlm/print_codes', 'Member\MLM_CodeControllerV2@print_codes'); //ARCY
Route::any('/member/mlm/print_codes/submit', 'Member\MLM_CodeControllerV2@print_codes_submit'); //ARCY
Route::any('/member/mlm/print', 'Member\MLM_CodeControllerV2@print'); //ARCY

Route::any('/member/mlm/claim_voucher', 'Member\MLM_ClaimVoucher@index'); //ERWIN
Route::any('/member/mlm/claim_voucher/claim', 'Member\MLM_ClaimVoucher@claim'); //ERWIN
Route::post('/member/mlm/claim_voucher/check_claim', 'Member\MLM_ClaimVoucher@check_claim'); //ERWIN
Route::post('/member/mlm/claim_voucher/check_claim/process', 'Member\MLM_ClaimVoucher@process'); //ERWIN
Route::post('/member/mlm/claim_voucher/check_claim/void', 'Member\MLM_ClaimVoucher@void'); //ERWIN

Route::get('/member/mlm/item_redeemable_points', 'Member\MLM_ItemRedeemablePointsController@index'); //ERWIN
Route::get('/member/mlm/item_redeemable_points_table', 'Member\MLM_ItemRedeemablePointsController@get_table'); //ERWIN
Route::get('/member/mlm/item_redeemable_points/submit', 'Member\MLM_ItemRedeemablePointsController@submitpoints'); //ERWIN

/* Start MLM Slot */
Route::any('/member/mlm/slot', 'Member\MLM_SlotController@index'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/slot/login', 'Member\MLM_SlotController@force_login'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/slot/simulate/{code}', 'Member\MLM_SlotController@simulate');
Route::any('/member/mlm/slot/add', 'Member\MLM_SlotController@add_slot');
Route::any('/member/mlm/slot/view/{slot_id}', 'Member\MLM_SlotController@view_slot_info');
Route::any('/member/mlm/slot/add/submit', 'Member\MLM_SlotController@add_slot_submit');
Route::any('/member/mlm/slot/head', 'Member\MLM_SlotController@add_slot_head');
Route::any('/member/mlm/slot/get/code/{customer_id}', 'Member\MLM_SlotController@get_member_code');
Route::any('/member/mlm/slot/get/code/form/submit', 'Member\MLM_SlotController@get_member_code_form_submit');
Route::any('/member/mlm/slot/genealogy', 'Member\MLM_SlotController@tree');
Route::any('/member/mlm/slot/genealogy/downline', 'Member\MLM_SlotController@downline');
Route::any('/member/mlm/slot/set/inactive/{slot_id}', 'Member\MLM_SlotController@set_inactive_slot');
Route::get('/member/mlm/slot/transfer', 'Member\MLM_SlotController@transfer_slot');
Route::post('/member/mlm/slot/transfer_post', 'Member\MLM_SlotController@transfer_slot_post');
/* end MLM Slot */

/* start MLM PLAN */
Route::any('/member/mlm/plan', 'Member\MLM_PlanController@index'); //GUILLERMO TABLIGAN
Route::any('/member/mlm/plan/save/setting', 'Member\MLM_PlanController@save_settings'); // Luke
Route::any('/member/mlm/plan/{plan}', 'Member\MLM_PlanController@configure_plan');//Luke
Route::any('/member/mlm/plan/{plan}/basicsettings', 'Member\MLM_PlanController@get_basicsettings');//Luke
Route::any('/member/mlm/plan/edit/submit', 'Member\MLM_PlanController@configure_plan_submit');//Luke
Route::any('/member/mlm/plan/binary/settings/submit', 'Member\MLM_PlanController@submit_binary_advance');//Luke
Route::any('/member/mlm/plan/wallet/type/view', 'Member\MLM_PlanController@wallet_type');//Luke
Route::any('/member/mlm/plan/wallet/type/add', 'Member\MLM_PlanController@add_wallet_type');//Luke

/* start binary PLAN */
Route::any('/member/mlm/plan/binary/edit/membership/points', 'Member\MLM_PlanController@edit_binary_membership_points');//Luke
Route::any('/member/mlm/plan/binary/get/membership/pairing/{membership_id}', 'Member\MLM_PlanController@get_binary_pairing_combination'); //Luke
Route::any('/member/mlm/plan/binary/edit/membership/pairing/save', 'Member\MLM_PlanController@save_binary_pairing_combinartion'); //Luke


/* start advertisement plan */
Route::any('/member/mlm/plan/advertisement_bonus/settings/submit', 'Member\MLM_PlanController@advertisement_bonus_submit'); //Erwin
Route::any('/member/mlm/plan/leadership_advertisement_bonus/settings/submit', 'Member\MLM_PlanController@leadership_advertisement_bonus_submit'); //Erwin

/* start indirect PLAN */
Route::any('/member/mlm/plan/indirect/edit/settings/{membership_id}', 'Member\MLM_PlanController@edit_indirect_setting');//Luke
Route::any('/member/mlm/plan/indirect/edit/settings/addlevel/save', 'Member\MLM_PlanController@edit_indirect_setting_add_level');//Luke

/* START PASS UP DIRECT */
Route::any('/member/mlm/plan/direct_pass_up/edit/settings', 'Member\MLM_PlanController@direct_pass_up_save_direct_number');//ERWIN
/* end stairstep Plan */

/* start RANK PLAN */
Route::any('/member/mlm/plan/rank/get', 'Member\MLM_PlanController@get_rank');//Erwin
Route::any('/member/mlm/plan/rank/save', 'Member\MLM_PlanController@save_rank');//Erwin
Route::any('/member/mlm/plan/rank/edit/save', 'Member\MLM_PlanController@edit_save_rank');//Erwin
Route::any('/member/mlm/plan/rank/edit/save_level', 'Member\MLM_PlanController@save_rank_level');//Erwin
Route::any('/member/mlm/plan/rank/edit/save_include', 'Member\MLM_PlanController@save_include');//Erwin

Route::any('/member/mlm/plan/stairstep/edit/save_dynamic', 'Member\MLM_PlanController@save_dynamic');//Erwin

Route::any('/member/mlm/plan/direct_referral_pv/edit/save_include_direct_referral', 'Member\MLM_PlanController@save_include_direct_referral');//Erwin

/* start stairstep PLAN */
// Route::any('/member/mlm/plan/stairstep/get', 'Member\MLM_PlanController@get_stairstep');//Luke
// Route::any('/member/mlm/plan/stairstep/save', 'Member\MLM_PlanController@save_stairstep');//Luke
// Route::any('/member/mlm/plan/stairstep/edit/save', 'Member\MLM_PlanController@edit_save_stairstep');//Luke
// Route::any('/member/mlm/plan/stairstep/edit/save_level', 'Member\MLM_PlanController@save_stairstep_level');//Erwin
/* end stairstep Plan */

/* start unilevel Plan */
Route::any('/member/mlm/plan/unilevel/get/all', 'Member\MLM_PlanController@get_all_unilevel');//luke
Route::any('/member/mlm/plan/unilevel/get/single/{membership_id}', 'Member\MLM_PlanController@get_single_unilevel');//luke
Route::any('/member/mlm/plan/unilevel/settings/save', 'Member\MLM_PlanController@save_settings_unilevel');
/* end unilevel Plan */

/* start Matching Plan */
Route::any('/member/mlm/plan/matching/add', 'Member\MLM_PlanController@matching_add_new');//luke
/* end unilevel Plan */

/* start Executive Plan */
Route::any('/member/mlm/plan/executive/edit/points', 'Member\MLM_PlanController@edit_executive_points');//luke
Route::any('/member/mlm/product/set/settings', 'Member\MLM_PlanController@set_settings_executive_points');
/* end Executive Plan */

/* start leadership Plan */
Route::any('/member/mlm/plan/leadership/edit/points', 'Member\MLM_PlanController@leadership_bonus_edit');//luke
Route::any('/member/mlm/plan/leadership/edit/matching', 'Member\MLM_PlanController@leadership_bonus_matching');
/* end leadership Plan */

/* start direct points NOTE: Direct POINTS ONLY Plan */
Route::any('/member/mlm/plan/direct/edit/points', 'Member\MLM_PlanController@direct_points_edit_v2');//luke
/* end direct points Plan */

/* start indirect points NOTE: INDirect POINTS ONLY Plan */
Route::any('/member/mlm/plan/indirect/edit/points', 'Member\MLM_PlanController@indirect_points_edit_v2');//luke
/* end indirect points Plan */

/* start indirect points NOTE: INDirect POINTS ONLY Plan */
Route::any('/member/mlm/plan/repurchase/edit/points', 'Member\MLM_PlanController@repurchase_add');//luke
/* end indirect points Plan */

/* start initial points */
Route::any('/member/mlm/plan/initial_points/edit/membership/points', 'Member\MLM_PlanController@initial_points_add');//luke
/* end initial points Plan */

/* start REPURCHASE CASHBACK points */
Route::any('/member/mlm/plan/repurchase_cashback/edit/membership/points', 'Member\MLM_PlanController@repurchase_cashback_add');//luke
/* end REPURCHASE CASHBACK Plan */

/* start Unilevel repurchase points */
Route::any('/member/mlm/plan/unilevel_repurchase_points/edit/membership/points', 'Member\MLM_PlanController@unilevel_repurchase_points_add');//luke
/* end Unilevel repurchase Plan */

/* start discount card  */
Route::any('/member/mlm/plan/discountcard/add', 'Member\MLM_PlanController@discount_card_add');//luke
/* end discount card Plan */

/* start direct promotions */
Route::any('/member/mlm/plan/direct_promotions/save', 'Member\MLM_PlanController@save_direct_promotions');//luke
/* end direct promotions  Plan */

/* start triangle repurchase */
Route::any('/member/mlm/plan/triangle_repurchase/save', 'Member\MLM_PlanController@save_triangle_repurchase');//luke
/* end triangle repurchase  Plan */

/* start triangle repurchase */
Route::any('/member/mlm/plan/binary_promotions/save', 'Member\MLM_PlanController@binary_promotions_save');//luke
Route::any('/member/mlm/plan/binary_promotions/get', 'Member\MLM_PlanController@binary_promotions_get');//luke
Route::any('/member/mlm/plan/binary_promotions/edit', 'Member\MLM_PlanController@binary_promotions_edit');//luke
/* end triangle repurchase  Plan */

/* start triangle repurchase */
Route::any('/member/mlm/plan/brown_rank', 'Member\MLM_PlanController@brown_rank'); 
Route::any('/member/mlm/plan/brown_rank/table', 'Member\MLM_PlanController@brown_rank_table'); 
Route::any('/member/mlm/plan/brown_rank/add_rank', 'Member\MLM_PlanController@brown_rank_add');
Route::any('/member/mlm/plan/brown_rank/add_rank_submit', 'Member\MLM_PlanController@add_rank_submit');
Route::any('/member/mlm/plan/brown_rank/update_rank_submit', 'Member\MLM_PlanController@update_rank_submit');

Route::any('/member/mlm/plan/brown_repurchase', 'Member\MLM_PlanController@brown_repurchase'); 
/* end triangle repurchase  Plan */



/* end MLM Plan */

/* start MLM Product */
Route::any('/member/mlm/wallet', 'Member\Mlm_WalletController@index');//luke
Route::any('/member/mlm/wallet/adjust', 'Member\Mlm_WalletController@adjust');//luke
Route::any('/member/mlm/wallet/adjust/submit', 'Member\Mlm_WalletController@adjust_submit');//luke
Route::any('/member/mlm/wallet/breakdown/{slot_id}', 'Member\Mlm_WalletController@breakdown_wallet');//luke
Route::any('/member/mlm/wallet/refill', 'Member\Mlm_WalletController@refill');//luke
Route::any('/member/mlm/wallet/refill/{id}', 'Member\Mlm_WalletController@refill_id');//luke
Route::any('/member/mlm/wallet/refill/change/attachment', 'Member\Mlm_WalletController@refill_change');//luke
Route::any('/member/mlm/wallet/refill/process/submit', 'Member\Mlm_WalletController@refill_process');//luke
Route::any('/member/mlm/wallet/refill/change/settings', 'Member\Mlm_WalletController@refill_settings');//luke
Route::any('/member/mlm/wallet/transfer', 'Member\Mlm_WalletController@transfer');
Route::any('/member/mlm/wallet/transfer/change_settings', 'Member\Mlm_WalletController@transfer_change_settings');
/* end MLM Product */

/* start MLM Encashment */
Route::any('/member/mlm/encashment', 'Member\Mlm_EncashmentController@index');//luke
Route::any('/member/mlm/encashment/update/settings', 'Member\Mlm_EncashmentController@update_settings');//luke
Route::any('/member/mlm/encashment/process/all', 'Member\Mlm_EncashmentController@process_all_encashment');//luke
Route::any('/member/mlm/encashment/view/{encashment_process}', 'Member\Mlm_EncashmentController@view_process');//luke
Route::any('/member/mlm/encashment/view/breakdown/{encashment_process}/{slot_id}', 'Member\Mlm_EncashmentController@breakdown_slot');//luke
Route::any('/member/mlm/encashment/view/breakdown/process', 'Member\Mlm_EncashmentController@process_breakdown');//luke
Route::any('/member/mlm/encashment/view/pdf/{encashment_process}/{slot_id}', 'Member\Mlm_EncashmentController@show_pdf');//luke
Route::any('/member/mlm/encashment/view/type/{type}', 'Member\Mlm_EncashmentController@show_type');//luke
Route::any('/member/mlm/encashment/view/type/cheque/edit', 'Member\Mlm_EncashmentController@cheque_edit');//luke
Route::any('/member/mlm/encashment/view/type/bank/add', 'Member\Mlm_EncashmentController@bank_add');//luke
Route::any('/member/mlm/encashment/view/type/bank/archive', 'Member\Mlm_EncashmentController@bank_archive');//luke
Route::any('/member/mlm/encashment/view/type/bank/edit/name', 'Member\Mlm_EncashmentController@bank_edit_name');//luke
Route::any('/member/mlm/encashment/currency', 'Member\Mlm_EncashmentController@set_currency');
Route::any('/member/mlm/encashment/currency/update', 'Member\Mlm_EncashmentController@set_currency_update');

Route::any('/member/mlm/encashment/add/to/list', 'Member\Mlm_EncashmentController@add_to_list');//luke
Route::any('/member/mlm/encashment/add/to/list/date', 'Member\Mlm_EncashmentController@add_to_list_date');//luke
Route::any('/member/mlm/encashment/view/all/selected', 'Member\Mlm_EncashmentController@view_all_selected');//luke
Route::any('/member/mlm/encashment/request/all/selected', 'Member\Mlm_EncashmentController@request_all_selected');//luke
Route::any('/member/mlm/encashment/deny/all/selected', 'Member\Mlm_EncashmentController@deny_all_selected');//luke

AdvancedRoute::controller("/member/mlm/payout","Member\MLM_PayoutController");
AdvancedRoute::controller("/member/mlm/gcmaintenance","Member\MLM_GCMaintenanceController");
/* end MLM Product */

Route::any("/member/mlm/distribute_cashback","Member\MLM_DistributeCashbackController@index");
Route::any("/member/mlm/distribute_cashback/distribute","Member\MLM_DistributeCashbackController@distribute");

/* start MLM Product */
Route::any('/member/mlm/product', 'Member\MLM_ProductController@index');//luke
Route::any('/member/mlm/product/point/add', 'Member\MLM_ProductController@add_product_points');
Route::any('/member/mlm/product/discount', 'Member\MLM_ProductController@discount');//luke
Route::any('/member/mlm/product/discount/submit', 'Member\MLM_ProductController@discount_add');
Route::any('/member/mlm/product/discount/get/{item_id}/{slot_id}', 'Member\MLM_ProductCodeController@discount_get');
Route::any('/member/mlm/product/discount/get/{item_id}', 'Member\MLM_ProductCodeController@price_original_get');
Route::any('/member/mlm/product/discount/fix/session/{slot_id}', 'Member\MLM_ProductCodeController@fix_discount_session');
Route::any('/member/mlm/product/repurchase/points', 'Member\MLM_ProductController@discount');//luke
Route::any('/member/mlm/product/set/all/points', 'Member\MLM_ProductController@set_all_points');//luke
/* end MLM Product */

/* start MLM PRODUCT CODE */
Route::any('/member/mlm/product_code', 'Member\MLM_ProductCodeController@index');//ewen
Route::any('/member/mlm/product_code/sell', 'Member\MLM_ProductCodeController@sell');//ewen
Route::any('/member/mlm/product_code/sell/add_line', 'Member\MLM_ProductCodeController@add_line'); //ewen
Route::any('/member/mlm/product_code/sell/add_line/product_barcode/submit', 'Member\MLM_ProductCodeController@add_line_barcode_product'); //ewen
Route::any('/member/mlm/product_code/sell/add_line/submit', 'Member\MLM_ProductCodeController@add_line_submit'); //ewen
Route::any('/member/mlm/product_code/sell/add_line/view', 'Member\MLM_ProductCodeController@view_all_lines'); //ewen
Route::any('/member/mlm/product_code/sell/get_customer_slot', 'Member\MLM_ProductCodeController@get_customer_slot'); //ewen
Route::any('/member/mlm/product_code/sell/compute', 'Member\MLM_ProductCodeController@compute'); //ewen
Route::any('/member/mlm/product_code/sell/clear_line/{id}', 'Member\MLM_ProductCodeController@remove_one_line'); //ewen
Route::any('/member/mlm/product_code/sell/clear_line_all', 'Member\MLM_ProductCodeController@clear_line_all'); //ewen
Route::any('/member/mlm/product_code/sell/process', 'Member\MLM_ProductCodeController@process'); //ewen
Route::any('/member/mlm/product_code/block/{id}', 'Member\MLM_ProductCodeController@block'); //ewen
Route::any('/member/mlm/product_code/block_submit', 'Member\MLM_ProductCodeController@block_submit'); //ewen
Route::any('/member/mlm/product_code/receipt', 'Member\MLM_ProductCodeController@receipt'); //ewen
Route::any('/member/mlm/product_code/receipt/view/{id}', 'Member\MLM_ProductCodeController@view_receipt'); //ewen
/* end MLM PRODUCT CODE */

Route::get('member/mlm/card', 'Member\MLM_CardController@all_slot');
Route::get('member/mlm/card/view', 'Member\MLM_CardController@view');
Route::post('member/mlm/card/filter', 'Member\MLM_CardController@filter');
Route::get('member/mlm/card/image/{slot}', 'Member\MLM_CardController@generate');
Route::get('member/mlm/card/image/discount/{id}', 'Member\MLM_CardController@generate_discount');
Route::get('member/mlm/card/all', 'Member\MLM_CardController@all_slot');
Route::post('member/mlm/card/done', 'Member\MLM_CardController@done');
Route::post('member/mlm/card/done/discount', 'Member\MLM_CardController@done_discount');
Route::post('member/mlm/card/pending', 'Member\MLM_CardController@pending');
Route::post('member/mlm/card/pending/discount', 'Member\MLM_CardController@pending_discount');


Route::get('/member/mlm/report', 'Member\MLM_ReportController@index');
Route::any('/member/mlm/report/get', 'Member\MLM_ReportController@get_report');



/* STAIRSTEP*/
Route::any('member/mlm/stairstep/distribution', 'Member\MLM_StairstepController@stairstep_view'); 
Route::any('member/mlm/stairstep/distribution/submit', 'Member\MLM_StairstepController@distribution_submit'); 
Route::any('member/mlm/stairstep/view_summary', 'Member\MLM_StairstepController@view_summary'); 

/* RANK UPDATE */ 
Route::any('member/mlm/rank/update', 'Member\MLM_RankController@rank_stairstep_view'); 
Route::any('member/mlm/rank/update/start', 'Member\MLM_RankController@start'); 
Route::any('member/mlm/rank/update/start/compute', 'Member\MLM_RankController@compute'); 
Route::any('member/mlm/rank/update/view_rank_update', 'Member\MLM_RankController@view_rank_update'); 

Route::any('member/mlm/complan_setup', 'Member\Mlm_ComplanSetupController@index'); 
Route::any('member/mlm/complan_setup/binary_pro', 'Member\Mlm_ComplanSetupController@binary_promotions'); 
Route::any('member/mlm/complan_setup/settings/update/myphone', 'Member\Mlm_ComplanSetupController@myphone_other_settings_update'); 
Route::any('member/mlm/merchant_school', 'Member\BeneficiaryController@index'); 

Route::any('member/mlm/tours_wallet', 'Member\Mlm_ComplanSetupController@tours_wallet'); 
Route::any('member/mlm/tours_wallet/update/settings', 'Member\Mlm_ComplanSetupController@set_tours_wallet_settings');
Route::any('member/mlm/tours_wallet/get/log', 'Member\Mlm_ComplanSetupController@get_log'); 

Route::any('member/mlm/merchant_school/create', 'Member\BeneficiaryController@create'); 
Route::any('member/mlm/merchant_school/get/receipt', 'Member\BeneficiaryController@receipt'); 
Route::any('member/mlm/merchant_school/get', 'Member\BeneficiaryController@get'); 
Route::any('member/mlm/merchant_school/destroy', 'Member\BeneficiaryController@destroy'); 
Route::any('member/mlm/merchant_school/get/table', 'Member\BeneficiaryController@get_table'); 
Route::any('member/mlm/merchant_school/mark/used', 'Member\BeneficiaryController@mark_used'); 

Route::any('member/mlm/merchant_school/get_customer/{id}', 'Member\BeneficiaryController@get_customer'); 
Route::any('member/mlm/merchant_school/consume', 'Member\BeneficiaryController@consume'); 


Route::any('member/mlm/complan_setup/unilevel/distribute', 'Member\Mlm_ComplanSetupController@unilevel_distribute'); 
Route::any('member/mlm/complan_setup/unilevel/distribute/set/settings', 'Member\Mlm_ComplanSetupController@unilevel_distribute_set_settings'); 
Route::any('member/mlm/complan_setup/unilevel/distribute/simulate', 'Member\Mlm_ComplanSetupController@unilevel_distribute_simulate'); 

/* DEVELOPER MENU */
Route::get('member/mlm/developer', 'Member\MlmDeveloperController@index');
Route::get('member/mlm/developer/table', 'Member\MlmDeveloperController@index_table');
Route::get('member/mlm/developer/create_slot', 'Member\MlmDeveloperController@create_slot');
Route::post('member/mlm/developer/create_slot', 'Member\MlmDeveloperController@create_slot_submit');
Route::get('member/mlm/developer/import', 'Member\MlmDeveloperController@import');
Route::post('member/mlm/developer/import', 'Member\MlmDeveloperController@import_submit');
Route::get('member/mlm/developer/repurchase', 'Member\MlmDeveloperController@repurchase');
Route::post('member/mlm/developer/repurchase', 'Member\MlmDeveloperController@repurchase_submit');
Route::get('member/mlm/developer/reset', 'Member\MlmDeveloperController@reset');
Route::post('member/mlm/developer/sample_upload', 'Member\MlmDeveloperController@sample_upload');
Route::any('member/mlm/developer/myTest', 'Member\MlmDeveloperController@myTest');
Route::any('member/mlm/developer/recompute', 'Member\MlmDeveloperController@recompute');
Route::any('member/mlm/developer/recompute_reset', 'Member\MlmDeveloperController@recompute_reset');

Route::get('member/mlm/developer/redistribute', 'Member\MlmDeveloperController@redistribute');
Route::post('member/mlm/developer/redistribute', 'Member\MlmDeveloperController@redistribute_submit');

Route::get('member/mlm/developer/modify_slot', 'Member\MlmDeveloperController@modify_slot');
Route::post('member/mlm/developer/modify_slot', 'Member\MlmDeveloperController@modify_slot_submit');

Route::any('member/mlm/developer/allow_multiple_slot', 'Member\MlmDeveloperController@allow_multiple_slot');

Route::any('member/mlm/developer/tag_as_ambassador', 'Member\MlmDeveloperController@tag_as_ambassador');



Route::any('member/mlm/developer/popup_genealogy', 'Member\MlmDeveloperController@popup_genealogy');
Route::any('member/mlm/developer/popup_slot_created', 'Member\MlmDeveloperController@popup_slot_created');
Route::any('member/mlm/developer/popup_earnings', 'Member\MlmDeveloperController@popup_earnings');
Route::any('member/mlm/developer/distributed_income', 'Member\MlmDeveloperController@distributed_income');
Route::any('member/mlm/developer/popup_points', 'Member\MlmDeveloperController@popup_points');
Route::any('member/mlm/developer/change_owner', 'Member\MlmDeveloperController@change_owner');


// point log setting
Route::get('/member/mlm/point_log_complan', 'Member\MLM_PointLogSettingController@index');
Route::get('/member/mlm/point_log_complan/add', 'Member\MLM_PointLogSettingController@add');
Route::post('/member/mlm/point_log_complan/add', 'Member\MLM_PointLogSettingController@submit_add');
Route::get('/member/mlm/point_log_complan/table', 'Member\MLM_PointLogSettingController@table');
Route::get('/member/mlm/point_log_complan/modify', 'Member\MLM_PointLogSettingController@modify');
Route::post('/member/mlm/point_log_complan/modify', 'Member\MLM_PointLogSettingController@submit_modify');

//recaptcha
Route::get('/member/mlm/recaptcha','Member\MLM_RecaptchaController@index');
Route::get('/member/mlm/recaptcha/recaptcha_table','Member\MLM_RecaptchaController@table');
Route::get('/member/mlm/recaptcha/recaptcha_setting','Member\MLM_RecaptchaController@setting');
Route::post('/member/mlm/recaptcha/recaptcha_setting','Member\MLM_RecaptchaController@submit_setting');
Route::get('/member/mlm/recaptcha/add_pool','Member\MLM_RecaptchaController@add_pool');
Route::post('/member/mlm/recaptcha/add_pool','Member\MLM_RecaptchaController@submit_add_pool');
Route::get('/member/mlm/recaptcha/load_pool','Member\MLM_RecaptchaController@load_pool');
Route::get('/member/mlm/recaptcha/load_points','Member\MLM_RecaptchaController@load_points');
