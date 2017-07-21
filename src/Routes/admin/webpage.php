<?php

// Add the default route to the routes list for this provider
Route::get('{webpage}/edit/parent', 'WebpageController@editParent')->name('webpage.edit.parent');
Route::get('{webpage}/child', 'WebpageController@create')
    ->name('webpage.create.child');

Route::resource('', 'WebpageController');
