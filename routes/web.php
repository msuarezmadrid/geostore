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

//URL::forceRootUrl(config('app.url'));

Route::get('/', function () {
    return redirect('login');
});

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');



Route::group(['middleware' => ['auth']], function () {
	//SISTEMA
	Route::get('/configs', 'ConfigController@index')->middleware('can:system');
	Route::get('/defaults', 'ConfigsController@defaultvalues')->middleware('can:system');
	Route::get('/users','UserController@index')->middleware('can:system');;
	Route::get('/users/{id}','UserController@show')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/roles', 'RoleController@index')->middleware('can:system');;
	Route::get('/roles/{id}', 'RoleController@show')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/clients', 'ContactController@clients');
	Route::get('/comunes', 'ContactController@comunes');
	Route::get('/concilliation', 'ConcillationController@index')->middleware('can:system');
	Route::get('/concilliation/show/{id}', 'ConcillationController@show')->middleware('can:system');
	Route::get('/concilliation/docs', 'ConcillationController@docs')->middleware('can:system');
	Route::get('/concilliation/add', 'ConcillationController@add')->middleware('can:system');
	Route::get('/concilliation/{id}', 'ConcillationController@showDoc')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/clients/{id}', 'ContactController@showClients')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/salesreport', 'ReportController@sales');
    Route::get('/salesreportcategory', 'ReportController@salesCategory');
	Route::get('/reports/movements', 'ReportController@movements');
	Route::get('/reports/sales_without_stock', 'ReportController@salesWithoutStock');
	Route::get('/work_stations', 'WorkStationController@workStations')->middleware('can:system');
	Route::get('/suppliers', 'ContactController@suppliers')->middleware('can:system');
	Route::get('/movements', 'MovementController@movements')->middleware('can:system');
	Route::get('/simulation', 'SimulationController@index')->middleware('can:system');
	Route::get('/purchases', 'MovementController@purchases')->middleware('can:system');
	Route::get('/purchases/{id}', 'MovementController@showPurchases')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/sales', 'MovementController@sales')->middleware('can:system');
	Route::get('/sales/{id}', 'MovementController@showSales')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/transfers', 'MovementController@transfers')->middleware('can:system');
	Route::get('/transfers/{id}', 'MovementController@showTransfers')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/adjustments', 'MovementController@adjustments')->middleware('can:system');
	Route::get('/adjustments/{id}', 'MovementController@showAdjustments')->where('id', '[0-9]+')->middleware('can:system');
	 
	Route::get('/work_orders', 'MovementController@workOrders')->middleware('can:system');
	Route::get('/work_orders/{id}', 'MovementController@showWorkOrders')->where('id', '[0-9]+')->middleware('can:system');
	 
	Route::get('/items', 'ItemController@index')->middleware('can:system');
	Route::get('/items/{id}','ItemController@show')->where('id', '[0-9]+')->middleware('can:system');
		 
	Route::get('/locations', 'LocationController@index')->middleware('can:system');
	Route::get('/locations/{id}', 'LocationController@show')->where('id', '[0-9]+')->middleware('can:system');
	Route::get('/box_sales','BoxSaleController@index')->middleware('can:system');
	Route::get('/branch_offices','BranchOfficeController@index')->middleware('can:system');
	Route::get('/reports','ReportController@index')->middleware('can:system');
    Route::get('/dte_forward', 'DteForwardController@index');

	//CAJERO
	Route::get('/pos', 'POSController@index')->middleware('can:cashier');
	Route::get('/creditnotes', 'CreditNoteController@index')->middleware('can:cashier');
	Route::get('/creditnotes/add', 'CreditNoteController@add')->middleware('can:cashier');

	//VENDEDOR GENERICO
	Route::get('/presale', 'PresaleController@index')->middleware('can:presale');
	Route::get('/price_quote', 'PriceQuoteController@index')->middleware('can:presale');
	
	//LIBRES
	Route::get('/profiles', 'ProfileController@index');
	Route::get('/dashboards', 'DashboardController@index');
	
	//COBRANZA
	Route::get('/collection', 'CollectionController@index');
});

