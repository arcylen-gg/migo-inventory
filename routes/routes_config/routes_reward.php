<?php
Route::group(array('prefix' => '/reward'), function()
{
	Route::any('/', 'Reward\RewardController@index'); 
	Route::any('/test', 'Reward\RewardTestController@index'); 
});