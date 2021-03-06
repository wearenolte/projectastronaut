<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('aaa', function(){return view('pdf');});

Route::get('/admin/company-view', 'CompanyView@index');
Route::get('/display-report', 'DisplayReport@index');
Route::get('/display-report-authed', 'internalDisplayReport@index');
// Route::get('/display-report', 'DisplayReport@unidentified');

//Route::get('/admin/', 'Login@index');
//Route::get('/admin/login', 'Login@index');
Route::get('/admin/report-setup/', 'ReportSetup@index');
Route::post('/admin/uploadreport/', 'ReportSetup@uploadreport');

Route::get('/admin/company-view-detail={id}', 'CompanyView@detail');

Route::get('/admin/new-company', 'NewCompany@index');

//Route::get('/admin/upload/', 'Upload@index');
//Route::get('/admin/uploaded/', 'Upload@display');
Route::get('/admin/all-companies/', 'AllCompanies@index');
Route::get('/admin/dashboard/', 'dashboard@index');
Route::get('/ajax', 'Ajax@index');
Route::post('/ajax-insertuser', 'Ajax@insertuser');
Route::post('/ajax-deleteuser', 'Ajax@deleteuser');
Route::post('/ajax-updateuser', 'Ajax@updateuser');
Route::get('/ajax-upload', 'Ajax@upload');
Route::get('/ajax-new-company', 'Ajax@newCompany');
Route::get('/admin/user-detail={id}', 'userdata@index');
Route::get('tests', 'tests@index');
Route::post('/ajax-loadcode', 'Ajax@loadcode');
Route::post('/ajax-savecode', 'Ajax@savecode');
Route::post('/ajax-loadeditor', 'Ajax@editor');
Route::post('/ajax-active', 'Ajax@activeuser');
Route::post('/ajax-manager', 'Ajax@updatemanager');
Route::post('/ajax-activemulti', 'Ajax@activecompanyusers');

Route::post('/ajax-userurl', 'Ajax@userurl');

Route::get('admin/print={id}', 'ReportSetup@printreport');

Route::get('admin/exportall', 'ReportSetup@export');

Route::get('admin/exportppl={id}', 'CompanyView@export');

Route::post('/ajax-deletecompany', 'Ajax@deletecompany');

Route::post('/ajax-reportauth', 'Ajax@reportauth');
Route::post('/ajax-reportauthemail', 'Ajax@reportauthemail');
Route::post('/ajax-sendmail', 'Ajax@sendmail');
Route::post('/ajax-sendmail2', 'Ajax@sendmail2');
Route::post('/ajax-sendmail3', 'Ajax@sendmail3');
Route::get('/testmail', 'tests@sendmail');
Route::post('/contact-form','Ajax@contactForm');

Route::post('/admin/uploadnew','NewCompany@upload');
Route::post('/admin/updatecompany','ReportSetup@updatecompany');

/*Route::get('test', function () {
    return LaravelAnalytics->getVisitorsAndPageViews();
});*/

// Authentication routes...
Route::get('/', function(){return redirect('admin/login');});
Route::get('/admin/', function(){return redirect('admin/login');});
Route::get('/home',function(){return redirect('admin/dashboard');});
Route::get('admin/login', 'Auth\AuthController@getLogin');
Route::post('admin/login', 'Auth\AuthController@postLogin');
Route::get('admin/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('admin/register', 'Auth\AuthController@getRegister');
Route::post('admin/register', 'Auth\AuthController@postRegister');
 
Route::post('/webhook','Ajax@webhook');
Route::get('/rankApi','Ajax@ranklist');

