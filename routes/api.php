<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {

	//presale
	Route::get('creditnote/search/{type}/{folio}', 'Api\CreditNoteController@search');
	Route::get('creditnote/busca', 'Api\CreditNoteController@busca');	
	Route::resource('creditnotes', 'Api\CreditNoteController');
	Route::resource('ordernotes', 'Api\OrderNoteController');
	Route::post('cart/stock', 'Api\PresaleController@stock');
	Route::get('clients/search/{type}/{value}', 'Api\ClientController@list');
	Route::get('items/search', 'Api\ItemController@search');
	Route::get('presale/print/{id}/{printIndex}', 'Api\PresaleController@print');
	Route::get('sellers/list', 'Api\SellersController@list');
	Route::resource('sellers', 'Api\SellersController');
	Route::post('report/printSales', 'Api\ReportController@printSales');
	Route::get('/pricetypes', 'Api\ItemPricesController@pricetypes');
	Route::resource('/itemprices', 'Api\ItemPricesController');


	/////////
	//CART STORAGE //
	/////////
	Route::resource('conciliations', 'Api\ConciliationController');
	Route::get('reports/sales/box/movements/export', 'Api\ReportController@exportmovements');
	Route::get('reports/sales/box/movements/pdf', 'Api\ReportController@downloadpdf');
	Route::get('reports/sales/box/movements', 'Api\ReportController@boxmovements');
	Route::get('reports/sales/export', 'Api\ReportController@exportsales');
    Route::get('reports/sales/export/categories', 'Api\ReportController@exportsalesCategory');
	Route::get('reports/sales/resume', 'Api\ReportController@sales');
    Route::get('reports/sales/resume/categories', 'Api\ReportController@salesCategory');
	Route::get('reports/sales/movements', 'Api\ReportController@movements');
    Route::get('reports/sales/movements/category', 'Api\ReportController@movementsCategory');
	Route::get('reports/sales_without_stock', 'Api\ReportController@salesWithoutStock');
	Route::get('reports/export_sales_without_stock', 'Api\ReportController@exportSalesWithoutStock');
	Route::get('pos/sale/{id}/print', 'Api\PointOfSaleController@print');
	Route::get('configs/params/{param}', 'Api\ConfigController@param');
	Route::resource('configs','Api\ConfigController');
	Route::post('client/save', 'Api\ClientController@save');
	Route::post('pos/cart/discount', 'Api\PointOfSaleController@discount');
	Route::post('pos/cart/item/{id}/discount', 'Api\PointOfSaleController@updateitemdiscount');
	Route::post('pos/cart/item/{id}/location', 'Api\PointOfSaleController@updatelocation');
	Route::get('pos/cart/stock', 'Api\PointOfSaleController@stock');
	Route::get('pos/cart/delete/all', 'Api\PointOfSaleController@deleteAll');
	Route::post('/pos/cart/store','Api\PointOfSaleController@add')->name('cart.add');
	Route::get('/pos/cart','Api\PointOfSaleController@all')->name('cart.all');
	Route::get('/pos/cart/process', 'Api\PointOfSaleController@process')->name('cart.process');
	Route::get('/pos/items', 'Api\PointOfSaleController@items');
	Route::get('/pos/barcode-item','Api\PointOfSaleController@itemBarcode');
	Route::post('/pos/cart/sell','Api\PointOfSaleController@sell');
	Route::get('/pos/replacementTicket/print/{id}', 'Api\PointOfSaleController@printReplacementDocument');
	Route::post('/pos/cart/update-discount','Api\PointOfSaleController@updateDiscount');
	Route::get('/pos/clients', 'Api\PointOfSaleController@clients');
	Route::get('/pos/movements', 'Api\PointOfSaleController@getMovements');
	Route::get('/pos/movements/resume', 'Api\PointOfSaleController@getMovementResume');
	Route::get('transacts/search', 'Api\TransactController@search');

	Route::get('AppPaymentTypes', 'Api\AppPaymentTypesController@index');
    Route::post('AppPaymentTypes', 'Api\AppPaymentTypesController@store');
    Route::delete('AppPaymentTypes/{id}', 'Api\AppPaymentTypesController@destroy');

	Route::get('sale_box','Api\SaleBoxController@index');
	Route::get('active_sale_boxes','Api\SaleBoxController@activeBoxes');
	Route::post('sale_box','Api\SaleBoxController@store');
	Route::post('sale_box/update_sale_box_status','Api\SaleBoxController@updateSaleBoxStatus');

	Route::get('branch_office','Api\BranchOfficeController@index');
	Route::post('branch_office','Api\BranchOfficeController@store');

	Route::get('concilliation','Api\ConcillationController@index');
	Route::get('concilliation/{id}','Api\ConcillationController@show');
	Route::post('concilliation','Api\ConcillationController@store');
	Route::put('concilliation/{id}','Api\ConcillationController@update');
	Route::delete('concilliation/{id}','Api\ConcillationController@destroy');


	Route::get('postitems','Api\ItemController@axiosItems');
	//Route::get('sellers','Api\SellerController@index');

	//Route::resource('items','Api\ItemController');
	Route::get('items/{id}/locationItem','Api\ItemController@getLocationItem')->where('id', '[0-9]+');
	Route::post('items/{id}/controlstock','Api\ItemController@saveControlStockData')->where('id', '[0-9]+');

    //Route::get('users/config','Api\UserController@config');
	Route::resource('users','Api\UserController');

	Route::get('users','Api\UserController@index')->middleware('can:users_view');
	Route::get('users/{id}','Api\UserController@show')->where('id', '[0-9]+')->middleware('can:users_view');
	Route::post('users', 'Api\UserController@store')->middleware('can:users_create');
	Route::put('users/{id}', 'Api\UserController@update')->middleware('can:users_edit');
	Route::delete('users/{id}', 'Api\UserController@destroy')->middleware('can:users_delete');

	Route::post('users/avatar','Api\UserController@avatar');
	Route::post('users/profile','Api\UserController@profile');

	Route::get('roles','Api\RoleController@index')->middleware('can:roles_view');
	Route::get('roles/{id}','Api\RoleController@show')->where('id', '[0-9]+')->middleware('can:roles_view');
	Route::post('roles', 'Api\RoleController@store')->middleware('can:roles_create');
	Route::put('roles/{id}', 'Api\RoleController@update')->middleware('can:roles_edit');
	Route::put('roles/{id}/permissions', 'Api\RoleController@updatePermissions')->middleware('can:roles_edit');
	Route::delete('roles/{id}', 'Api\RoleController@destroy')->middleware('can:roles_delete');

	Route::get('items','Api\ItemController@index')->middleware('can:items_view');
	Route::get('items/{id}','Api\ItemController@show')->where('id', '[0-9]+')->middleware('can:items_view');
	Route::get('items/{id}/stock','Api\ItemController@stock')->where('id', '[0-9]+')->middleware('can:items_view');
	Route::post('items', 'Api\ItemController@store')->middleware('can:items_create');
	Route::put('items/{id}', 'Api\ItemController@update')->middleware('can:items_edit');
	Route::delete('items/{id}', 'Api\ItemController@destroy')->middleware('can:items_delete');
	Route::get('items/{id}/bomitems', 'Api\ItemController@bomitems')->middleware('can:items_delete');

	Route::post('items/massive', 'Api\ItemController@postMassive')->middleware('can:items_create');
	Route::post('items/massive/shoes', 'Api\ItemController@postShoes')->middleware('can:items_create');

	Route::post('bom_items', 'Api\BomItemController@store');//->middleware('can:clients_create');
	Route::put('bom_items/{id}', 'Api\BomItemController@update');
	Route::delete('bom_items/{id}', 'Api\BomItemController@destroy');

	Route::get('clients','Api\ClientController@index');//->middleware('can:clients_view');
	Route::get('clients/{id}','Api\ClientController@show')->where('id', '[0-9]+');//->middleware('can:clients_view');
	Route::post('clients', 'Api\ClientController@store');//->middleware('can:clients_create');
	Route::put('clients/{id}', 'Api\ClientController@update');//->middleware('can:clients_edit');
	Route::delete('clients/{id}', 'Api\ClientController@destroy');//->middleware('can:clients_delete');

	Route::get('suppliers','Api\SupplierController@index');//->middleware('can:suppliers_view');
	Route::get('suppliers/{id}','Api\SupplierController@show')->where('id', '[0-9]+');//->middleware('can:suppliers_view');
	Route::post('suppliers', 'Api\SupplierController@store');//->middleware('can:suppliers_create');
	Route::put('suppliers/{id}', 'Api\SupplierController@update');//->middleware('can:suppliers_edit');
	Route::delete('suppliers/{id}', 'Api\SupplierController@destroy');//->middleware('can:suppliers_delete');

	Route::get('contacts','Api\ContactController@index');//->middleware('can:contacts_view');
	Route::get('contacts/{id}','Api\ContactController@show')->where('id', '[0-9]+');//->middleware('can:contacts_view');
	Route::post('contacts', 'Api\ContactController@store');//->middleware('can:contacts_create');
	Route::put('contacts/{id}', 'Api\ContactController@update');//->middleware('can:contacts_edit');
	Route::delete('contacts/{id}', 'Api\ContactController@destroy');//->middleware('can:contacts_delete');

	Route::get('locations','Api\LocationController@index');
	Route::get('locations/{id}','Api\LocationController@show')->where('id', '[0-9]+');//->middleware('can:locations_view');
	Route::post('locations', 'Api\LocationController@store');//->middleware('can:locations_create');
	Route::put('locations/{id}', 'Api\LocationController@update');//->middleware('can:locations_edit');
	Route::delete('locations/{id}', 'Api\LocationController@destroy');//->middleware('can:locations_delete');

	Route::get('purchases','Api\PurchaseOrderController@index');
	Route::get('purchases/{id}','Api\PurchaseOrderController@show')->where('id', '[0-9]+');//->middleware('can:purchases_view');
	Route::get('purchases/{id}/items','Api\PurchaseOrderController@items')->where('id', '[0-9]+');//->middleware('can:purchases_view');
	Route::post('purchases', 'Api\PurchaseOrderController@store');//->middleware('can:purchases_create');
	Route::put('purchases/{id}', 'Api\PurchaseOrderController@update');//->middleware('can:purchases_edit');
	Route::delete('purchases/{id}', 'Api\PurchaseOrderController@destroy');//->middleware('can:purchases_delete');

	Route::get('sales','Api\SaleOrderController@index');
    Route::get('sales/forward', 'Api\SaleController@forwardTable');
    Route::post('sales/forward', 'Api\SaleController@forwardDocument');
	Route::get('sales/conciliated/{id}/{client}/{type}/{conciliated}', 'Api\SaleController@searchconciliated');
	Route::get('sales/{id}','Api\SaleOrderController@show')->where('id', '[0-9]+');//->middleware('can:sales_view');
	Route::get('sales/{id}/items','Api\SaleOrderController@items')->where('id', '[0-9]+');//->middleware('can:sales_view');
	Route::post('sales', 'Api\SaleOrderController@store');//->middleware('can:sales_create');
	Route::put('sales/{id}', 'Api\SaleOrderController@update');//->middleware('can:sales_edit');
	Route::delete('sales/{id}', 'Api\SaleOrderController@destroy');//->middleware('can:sales_delete');

	Route::get('salesGrid','Api\SaleController@index');

	Route::get('reports/salesGrid','Api\ReportController@indexSales');

	Route::get('transfers','Api\TransferController@index');
	Route::get('transfers/{id}','Api\TransferController@show')->where('id', '[0-9]+');//->middleware('can:transfers_view');
	Route::get('transfers/{id}/items','Api\TransferController@items')->where('id', '[0-9]+');//->middleware('can:transfers_view');
	Route::post('transfers', 'Api\TransferController@store');//->middleware('can:transfers_create');
	Route::put('transfers/{id}', 'Api\TransferController@update');//->middleware('can:transfers_edit');
	Route::delete('transfers/{id}', 'Api\TransferController@destroy');//->middleware('can:transfers_delete');

	Route::get('work_orders','Api\WorkOrderController@index');
	Route::get('work_orders/{id}','Api\WorkOrderController@show')->where('id', '[0-9]+');//->middleware('can:work_orders_view');
	Route::get('work_orders/{id}/items','Api\WorkOrderController@items')->where('id', '[0-9]+');//->middleware('can:work_orders_view');
	Route::post('work_orders', 'Api\WorkOrderController@store');//->middleware('can:work_orders_create');
	Route::put('work_orders/{id}', 'Api\WorkOrderController@update');//->middleware('can:work_orders_edit');
	Route::delete('work_orders/{id}', 'Api\WorkOrderController@destroy');//->middleware('can:work_orders_delete');


	Route::get('adjustments','Api\AdjustmentController@index');
	Route::get('adjustments/{id}','Api\AdjustmentController@show')->where('id', '[0-9]+');//->middleware('can:adjustments_view');
	Route::get('adjustments/{id}/items','Api\AdjustmentController@items')->where('id', '[0-9]+');//->middleware('can:adjustments_view');
	Route::post('adjustments', 'Api\AdjustmentController@store');//->middleware('can:adjustments_create');
	Route::put('adjustments/{id}', 'Api\AdjustmentController@update');//->middleware('can:adjustments_edit');
	Route::delete('adjustments/{id}', 'Api\AdjustmentController@destroy');//->middleware('can:adjustments_delete');

	Route::get('work_stations','Api\WorkStationController@index');
	Route::get('work_stations/{id}','Api\WorkStationController@show')->where('id', '[0-9]+');//->middleware('can:work_stations_view');
	Route::post('work_stations', 'Api\WorkStationController@store');//->middleware('can:work_stations_create');
	Route::put('work_stations/{id}', 'Api\WorkStationController@update');//->middleware('can:work_stations_edit');
	Route::delete('work_stations/{id}', 'Api\WorkStationController@destroy');//->middleware('can:work_stations_delete');



	Route::post('purchase_order_items', 'Api\PurchaseOrderItemController@store');//->middleware('can:purchases_create');
	Route::put('purchase_order_items/{id}', 'Api\PurchaseOrderItemController@update')->where('id', '[0-9]+');
	Route::delete('purchase_order_items/{id}', 'Api\PurchaseOrderItemController@destroy')->where('id', '[0-9]+');

	Route::post('sale_order_items', 'Api\SaleOrderItemController@store');//->middleware('can:sales_create');
	Route::put('sale_order_items/{id}', 'Api\SaleOrderItemController@update')->where('id', '[0-9]+');
	Route::delete('sale_order_items/{id}', 'Api\SaleOrderItemController@destroy')->where('id', '[0-9]+');

	Route::post('transfer_items', 'Api\TransferItemController@store');//->middleware('can:purchases_create');
	Route::put('transfer_items/{id}', 'Api\TransferItemController@update')->where('id', '[0-9]+');
	Route::delete('transfer_items/{id}', 'Api\TransferItemController@destroy')->where('id', '[0-9]+');

	Route::post('adjustment_items', 'Api\AdjustmentItemController@store');//->middleware('can:purchases_create');
	Route::put('adjustment_items/{id}', 'Api\AdjustmentItemController@update')->where('id', '[0-9]+');
	Route::delete('adjustment_items/{id}', 'Api\AdjustmentItemController@destroy')->where('id', '[0-9]+');

	Route::post('work_order_items', 'Api\WorkOrderItemController@store');//->middleware('can:works_create');
	Route::put('work_order_items/{id}', 'Api\WorkOrderItemController@update')->where('id', '[0-9]+');
	Route::delete('work_order_items/{id}', 'Api\WorkOrderItemController@destroy')->where('id', '[0-9]+');


	Route::get('files', 'Api\FileController@index');
	Route::post('files', 'Api\FileController@store');//->middleware('can:items_create');
	Route::get('files/{id}', 'Api\FileController@show')->where('id', '[0-9]+');
	Route::delete('files/{id}', 'Api\FileController@destroy')->where('id', '[0-9]+')->middleware('can:files_delete')->middleware('can:files_enterprise,id');


	//Route::resource('configs','Api\ConfigController');

	Route::get('location_types','Api\LocationTypeController@index');
	Route::get('location_types/{id}','Api\LocationTypeController@show')->where('id', '[0-9]+');//->middleware('can:location_types_view');
	Route::post('location_types', 'Api\LocationTypeController@store');//->middleware('can:location_types_create');
	Route::put('location_types/{id}', 'Api\LocationTypeController@update');//->middleware('can:location_types_edit');
	Route::delete('location_types/{id}', 'Api\LocationTypeController@destroy');//->middleware('can:location_types_delete');

	Route::get('attributes','Api\AttributeController@index');
	Route::get('attributes/{id}','Api\AttributeController@show')->where('id', '[0-9]+');//->middleware('can:attributes_view');
	Route::post('attributes', 'Api\AttributeController@store');//->middleware('can:attributes_create');
	Route::put('attributes/{id}', 'Api\AttributeController@update');//->middleware('can:attributes_edit');
	Route::delete('attributes/{id}', 'Api\AttributeController@destroy');//->middleware('can:attributes_delete');

	Route::get('categories','Api\CategoryController@index');
	Route::get('comunes','Api\ComuneController@index');
	Route::get('comunes/{i}','Api\ComuneController@show');
	Route::post('comunes','Api\ComuneController@store');
	Route::delete('comunes/{i}','Api\ComuneController@destroy');
	
	
	Route::get('categories/{id}','Api\CategoryController@show')->where('id', '[0-9]+');//->middleware('can:categories_view');
	Route::post('categories', 'Api\CategoryController@store');//->middleware('can:categories_create');
	Route::put('categories/{id}', 'Api\CategoryController@update');//->middleware('can:categories_edit');
	Route::delete('categories/{id}', 'Api\CategoryController@destroy');//->middleware('can:categories_delete');

	Route::get('brands','Api\BrandController@index');
	Route::get('brands/{id}','Api\BrandController@show')->where('id', '[0-9]+');//->middleware('can:brands_view');
	Route::post('brands', 'Api\BrandController@store');//->middleware('can:brands_create');
	Route::put('brands/{id}', 'Api\BrandController@update');//->middleware('can:brands_edit');
	Route::delete('brands/{id}', 'Api\BrandController@destroy');//->middleware('can:brands_delete');

	Route::get('unit_of_measures','Api\UnitOfMeasureController@index');
	Route::get('unit_of_measures/{id}','Api\UnitOfMeasureController@show')->where('id', '[0-9]+');//->middleware('can:unit_of_measures_view');
	Route::post('unit_of_measures', 'Api\UnitOfMeasureController@store');//->middleware('can:unit_of_measures_create');
	Route::put('unit_of_measures/{id}', 'Api\UnitOfMeasureController@update');//->middleware('can:unit_of_measures_edit');
	Route::delete('unit_of_measures/{id}', 'Api\UnitOfMeasureController@destroy');//->middleware('can:unit_of_measures_delete');

	Route::get('unit_of_measure_conversions','Api\UnitOfMeasureConversionController@index');
	Route::get('unit_of_measure_conversions/{id}','Api\UnitOfMeasureConversionController@show')->where('id', '[0-9]+');//->middleware('can:unit_of_measure_conversions_view');
	Route::post('unit_of_measure_conversions', 'Api\UnitOfMeasureConversionController@store');//->middleware('can:unit_of_measure_conversions_create');
	Route::put('unit_of_measure_conversions/{id}', 'Api\UnitOfMeasureConversionController@update');//->middleware('can:unit_of_measure_conversions_edit');
	Route::delete('unit_of_measure_conversions/{id}', 'Api\UnitOfMeasureConversionController@destroy');//->middleware('can:unit_of_measure_conversions_delete');

	Route::get('price_types','Api\PriceTypeController@index');
	Route::get('price_types/{id}','Api\PriceTypeController@show')->where('id', '[0-9]+');//->middleware('can:price_types_view');
	Route::post('price_types', 'Api\PriceTypeController@store');//->middleware('can:price_types_create');
	Route::put('price_types/{id}', 'Api\PriceTypeController@update');//->middleware('can:price_types_edit');
	Route::delete('price_types/{id}', 'Api\PriceTypeController@destroy');//->middleware('can:price_types_delete');

	Route::post('discount', 'Api\DiscountController@store');
	Route::get('discount', 'Api\DiscountController@index');
	Route::get('discount/{id}','Api\DiscountController@show')->where('id', '[0-9]+');
    Route::delete('discount/{id}', 'Api\DiscountController@destroy');
    Route::put('discount/{id}', 'Api\DiscountController@update');

    Route::post('pos', 'Api\PointOfSaleController@salePoint');


	Route::get('dashboards','Api\DashboardController@get');
	Route::get('movement_statuses','Api\MovementStatusController@index');
	Route::delete('client_contacts/{id}', 'Api\ClientContactController@destroy');
	Route::delete('supplier_contacts/{id}', 'Api\SupplierContactController@destroy');
	//Route::post('configs/save','Api\ConfigController@save');

	Route::get('item_types','Api\ItemTypeController@index');

	Route::get('price_quote','Api\PriceQuoteController@index');
	Route::get('price_quote/{id}','Api\PriceQuoteController@show');
	Route::post('price_quote','Api\PriceQuoteController@store');
	Route::put('price_quote','Api\PriceQuoteController@update');
	Route::delete('price_quote','Api\PriceQuoteController@destroy');
	Route::get('price_quote/print/{id}', 'Api\PriceQuoteController@printPriceQuotes');
	
	Route::get('optimization', 'Api\SimulationController@optimization');
	Route::get('/pos/vouchers', 'Api\PointOfSaleController@vouchers');

	//COBRANZA
	Route::get('collection/auto_send_mail/{id}/{doc_type}','Api\CollectionController@autoSendMail');
	Route::post('collection/manual_send_mail/{id}/{doc_type}','Api\CollectionController@manualSendMail');
	Route::get('collection/get_excel/{id}/{doc_type}','Api\CollectionController@getExcel');
	Route::resource('collection', 'Api\CollectionController');

	
});
 
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');