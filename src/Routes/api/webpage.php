<?php

// Pages Index
Route::get('/', 'WebpageController@index');

// Subpage
Route::get('/{category}', 'WebpageController@categories')
	->where(['category' => '.*']);
