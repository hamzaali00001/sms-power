<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Frontend routes
Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/', 'HomeController')->name('homepage');
});

// Auth routes
Auth::routes(['verify' => true]);
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified'], 'namespace' => 'Auth'], function () {
	Route::post('logout', 'LoginController@logout');
    Route::get('change-password', 'ChangePasswordController@edit')->name('change-password');
    Route::post('change-password', 'ChangePasswordController@update');
});

// Backend routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified'], 'namespace' => 'Backend'], function () {
    Route::get('/', 'HomeController')->name('dashboard');

	Route::get('contact-us', 'ContactUsController@create')->name('contact-us');
	Route::post('contact-us', 'ContactUsController@sendEmail');

    Route::resource('purchases', 'PurchasesController', ['only' => ['index', 'create', 'store']]);
	
    Route::resource('groups', 'GroupsController', ['except' => ['show']]);

    Route::post('groups/{group}/contacts/upload', 'UploadContactsController')->name('upload-file');

    Route::get('groups.contacts.sample', 'ContactsController@sample')->name('groups.contacts.sample');
    Route::get('delete-contacts', 'ContactsController@deleteMultiple')->name('delete-contacts');
    Route::resource('groups.contacts', 'ContactsController', ['except' => ['show']]);

    Route::resource('senderids', 'SenderIDsController', ['except' => ['show']]);

	Route::get('send-sms', 'SendSMSController@create')->name('send-sms');
	Route::post('single-sms', ['uses' => 'SendSMSController@singleSMS'])->name('single-sms');
	Route::post('bulk-sms', ['uses' => 'SendSMSController@bulkSMS'])->name('bulk-sms');
	
    Route::resource('scheduled-sms', 'ScheduledSMSController', ['only' => ['index', 'destroy']]);

    Route::resource('sent-sms', 'SentSMSController', ['only' => ['index', 'destroy']]);

    Route::get('reports', 'ReportsController')->name('reports');

	Route::resource('messages', 'MessagesController');

	Route::resource('templates', 'TemplatesController');

    Route::resource('users', 'UsersController');

    Route::resource('sub-accounts', 'SubAccountsController');
});
