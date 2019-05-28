<?php

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| From php artisan make:en
|
| https://github.com/laravel/framework/blob/f769989694cdcb77e53fbe36d7a47cd06371998c/src/Illuminate/Routing/Router.php#L1178
|
*/

//Here expeditiously to solve a stupid problem I don't wanna look into
// DELETE BEFORE COMMITTING!!!!!
Route::get('user/profile', function () {
    //
})->name('nova.logout');


Route::group(['middleware' => ['web']], function () {

    Route::get('/home', 'Lasallesoftware\Library\Authentication\Http\Controllers\HomeController@index')->name('home');

// Authentication Routes...
    Route::get('login',   'Lasallesoftware\Library\Authentication\Http\Controllers\LoginController@showLoginForm')->name('login');
    Route::post('login',  'Lasallesoftware\Library\Authentication\Http\Controllers\LoginController@login');
    Route::get('logout',  'Lasallesoftware\Library\Authentication\Http\Controllers\LogoutController@showLogoutForm');
    Route::post('logout', 'Lasallesoftware\Library\Authentication\Http\Controllers\LogoutController@logout')->name('logout');

// Registration Routes...
    Route::get('register',  'Lasallesoftware\Library\Authentication\Http\Controllers\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Lasallesoftware\Library\Authentication\Http\Controllers\RegisterController@register');

// Password Reset Routes...
    Route::get('password/reset',  'Lasallesoftware\Library\Authentication\Http\Controllers\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Lasallesoftware\Library\Authentication\Http\Controllers\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Lasallesoftware\Library\Authentication\Http\Controllers\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Lasallesoftware\Library\Authentication\Http\Controllers\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
    Route::get('email/verify',      'Lasallesoftware\Library\Authentication\Http\Controllers\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}', 'Lasallesoftware\Library\Authentication\Http\Controllers\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend',      'Lasallesoftware\Library\Authentication\Http\Controllers\VerificationController@resend')->name('verification.resend');

});
