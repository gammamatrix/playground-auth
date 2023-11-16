<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'web',
    ],
    'namespace' => '\GammaMatrix\Playground\Auth\Http\Controllers',
], function () {
    Route::post('/logout', [
        'uses' => 'AuthenticatedSessionController@destroy',
    ]);

    Route::get('/logout', [
        'as'   => 'logout',
        'uses' => 'AuthenticatedSessionController@destroy',
    ]);
});

Route::group([
    'middleware' => [
        'web',
        'guest',
    ],
    'namespace' => '\GammaMatrix\Playground\Auth\Http\Controllers',
], function () {
    Route::get('/token', [
        'as'   => 'token',
        'uses' => 'AuthenticatedSessionController@token',
    ]);

    Route::get('/login', [
        'as'   => 'login',
        'uses' => 'AuthenticatedSessionController@create',
    ]);

    Route::post('/login', [
        'as'   => 'logout',
        'uses' => 'AuthenticatedSessionController@store',
    ]);

    Route::get('/register', [
        'as'   => 'register',
        'uses' => 'RegisteredUserController@create',
    ]);

    Route::post('/register', [
        'uses' => 'RegisteredUserController@store',
    ]);

    Route::get('/forgot-password', [
        'as'   => 'password.request',
        'uses' => 'PasswordResetLinkController@create',
    ]);

    Route::post('/forgot-password', [
        'as'   => 'password.email',
        'uses' => 'PasswordResetLinkController@store',
    ]);

    Route::get('/reset-password/{token}', [
        'as'   => 'password.reset',
        'uses' => 'NewPasswordController@create',
    ]);

    Route::post('/reset-password', [
        'as'   => 'password.update',
        'uses' => 'NewPasswordController@store',
    ]);
});

Route::group([
    'middleware' => [
        'web',
        'auth',
    ],
    'namespace' => '\GammaMatrix\Playground\Auth\Http\Controllers',
], function () {
    Route::get('/verify-email', [
        'as'   => 'verification.notice',
        'uses' => 'EmailVerificationController@show',
    ]);

    Route::get('/verify-email/{id}/{hash}', [
        'as'         => 'verification.verify',
        'uses'       => 'EmailVerificationController@verify',
        'middleware' => ['signed', 'throttle:6,1'],
    ]);

    Route::post('/verify-email', [
        'as'         => 'verification.send',
        'uses'       => 'EmailVerificationController@send',
        'middleware' => ['throttle:6,1'],
    ]);

    Route::get('/confirm-password', [
        'as'   => 'password.confirm',
        'uses' => 'ConfirmablePasswordController@show',
    ]);

    Route::post('/confirm-password', [
        'uses' => 'ConfirmablePasswordController@store',
    ]);
});
