<?php
/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout'); // define get logout to resolve error for guest user

Auth::routes(['verify' => true, 'register' => false]);

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    /*
    * Dashboard Routes...
    */
    Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');

    // File Uploader
    Route::post('upload', 'Admin\UploadController@store')->name('upload');

    // Change Password Routes...
    Route::get('user/change-password', 'Auth\ChangePassword@showChangePasswordForm')->name('user.change-password');
    Route::post('user/change-password', 'Auth\ChangePassword@updatePassword')->name('user.change-password');

    /*
    * User Routes...
    */
    // Bulk Action: Active/ Inactive / Delete Users
    Route::post('user/bulkaction', 'Admin\UserController@bulkaction')->name('user.bulkaction');
    Route::get('user/changestatus', 'Admin\UserController@changestatus')->name('user.changestatus');
    // Reset Password
    Route::get('user/{email}/reset-password', 'Admin\UserController@resetPassword')->name('user.reset-password');
    Route::resource('user', 'Admin\UserController')->except(['destroy']);

    /**
     * Ip tracker
     */
    Route::resource('iptracker', 'Admin\IpTrackerController')->except(['create', 'store', 'show', 'edit', 'update', 'destroy']);

        /*
    * Profile Routes...
    */
    Route::get('profile', 'Admin\ProfileController@edit')->name('profile');
    Route::post('profile/update', 'Admin\ProfileController@update')->name('profile.update');

});