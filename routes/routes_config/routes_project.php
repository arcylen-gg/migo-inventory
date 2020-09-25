<?php

Route::group(array('prefix' => '/member/project'), function()
{
	Route::get('/project_list', 'Member\ProjectController@index');
	Route::get('/project_list/add', 'Member\ProjectController@add');
	Route::post('/project_list/add', 'Member\ProjectController@submit_add');
	Route::get('/project_list/table', 'Member\ProjectController@table');
	Route::get('/project_list/archive', 'Member\ProjectController@archive');
	Route::get('/project_list/restore', 'Member\ProjectController@restore');
	Route::get('/project_list/modify','Member\ProjectController@modify');
	Route::post('/project_list/modify','Member\ProjectController@submit_modify');
	Route::get('/project_list/view','Member\ProjectController@view');
	Route::get('/project_list/addtask','Member\ProjectController@addTask');
});