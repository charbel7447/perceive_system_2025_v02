<?php 

Route::post('login', 'V2\PageController@login');

Route::group(['middleware' => 'auth'], function() {
    Route::get('dashboard', 'V2\DashboardController@index');
});