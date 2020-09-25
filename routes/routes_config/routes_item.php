<?php
Route::group(array('prefix' => '/member/item/'), function()
{
	Route::any('/v2','Member\ItemControllerV2@list');
	Route::any('/v2/table','Member\ItemControllerV2@list_table');

	Route::any('/v2/add','Member\ItemControllerV2@add_item');
	Route::any('/v2/add_submit','Member\ItemControllerV2@add_item_submit');

	Route::any('/v2/edit','Member\ItemControllerV2@edit_item');
	Route::any('/v2/edit_submit','Member\ItemControllerV2@edit_item_submit');

	Route::any('/v2/submit_checked_to_edit','Member\ItemControllerV2@submit_checked_to_edit');
	Route::any('/v2/choose_bulk_item','Member\ItemControllerV2@choose_bulk_item');
	Route::any('/v2/edit/bulk_item','Member\ItemControllerV2@edit_bulk_item');
	Route::any('/v2/submit_bulk_item','Member\ItemControllerV2@submit_edit_bulk_item');

	Route::any('/v2/archive','Member\ItemControllerV2@archive');
	Route::any('/v2/restore','Member\ItemControllerV2@restore');

	Route::any('/v2/cost','Member\ItemControllerV2@cost');
	Route::any('/v2/create_cost','Member\ItemControllerV2@create_cost');

	Route::any('/v2/item_cost_change','Member\ItemControllerV2@item_cost_change');
	Route::any('/v2/submit_item_cost_change','Member\ItemControllerV2@submit_item_cost_change');
	
	Route::any('/v2/price_level','Member\ItemControllerV2@price_level');

	Route::any('/v2/columns','Member\ItemControllerV2@columns');

	Route::any('/v2/search','Member\ItemControllerV2@search');

	Route::any('/v2/refill_item','Member\ItemControllerV2@refill_item');
	Route::any('/v2/refill_submit','Member\ItemControllerV2@refill_submit');

	Route::any('/v2/warehouse_reorder','Member\ItemControllerV2@warehouse_reorder');
	Route::any('/v2/warehouse_reorder_submit','Member\ItemControllerV2@warehouse_reorder_submit');
	Route::any('/v2/print_barcode','Member\ItemControllerV2@print_barcode');

	Route::any('/choose','Member\ItemControllerV2@choose');
	Route::any('/choose/submit','Member\ItemControllerV2@choose_submit');
	Route::any('/choose/load_item','Member\ItemControllerV2@load_item');
	Route::any('/choose/remove_item','Member\ItemControllerV2@remove_item');

	//patrick
	Route::get('/token/add-token','Member\ItemControllerV2@add_token');
	Route::post('/token/add-token','Member\ItemControllerV2@add_token_submit');
	Route::get('/token/token-list','Member\ItemControllerV2@get_token_list');
	Route::get('/token/token-list-table','Member\ItemControllerV2@token_list_table');
	Route::get('/token/archived','Member\ItemControllerV2@token_list_archived');
	Route::get('/token/update-token','Member\ItemControllerV2@update_token');
	Route::post('/token/update-token','Member\ItemControllerV2@update_token_submit');
});