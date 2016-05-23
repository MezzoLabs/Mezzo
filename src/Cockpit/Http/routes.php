<?php

Route::group(['as' => 'cockpit::', 'prefix' => 'mezzo', 'namespace' => 'MezzoLabs\Mezzo\Cockpit\Http\Controllers'], function () {

    // Authentication routes...
    Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
    Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

    // Registration routes...
    Route::get('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
    Route::post('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);

    // Password reset link request routes...
    Route::get('password/email', ['as' => 'password.email', 'uses' => 'Auth\PasswordController@getEmail']);

    Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\PasswordController@postEmail']);

    // Password reset routes...
    Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\PasswordController@getReset']);
    Route::post('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\PasswordController@postReset']);

    Route::get('{slug?}', ['as' => 'start', 'uses' => mezzo()->makeCockpit()->startAction()])->where('slug', '.+');
});



