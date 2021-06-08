<?php
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/',['middlware' => 'auth', function () {
	if(auth()->check()){
	  $user = auth()->user();
	  $role = $user->roles->first()->name;
	  
      if($role == 'client'){
		return redirect('/client/home');
	  }else{
		return redirect('/admin/home');
	  }
	}else{
	   return view('auth.client_login');
	}
}]);

Route::post('image-upload', 'ItemActionController@uploadImg')->name('upload_img');
Route::redirect('/admin', 'login');

Auth::routes(['register' => false]);

// Change Password Routes...
	Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
	Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');


	Route::get('client-confirmation/{id}/{token}', 'ItemActionController@confirmation_client');
	Route::get('sales_man-confirmation/{id}/{token}', 'ItemActionController@confirmation_salesman');
	Route::get('replace-confirmation/{token}', 'ItemActionController@confirmation_replace');
	Route::get('replacement-confirmation/{id}/{token}', 'ItemActionController@replaced');
	Route::get('download-document/{id}/{token}', 'ItemActionController@download');
	Route::group(['middleware' => ['auth','role:client'], 'prefix' => 'client', 'as' => 'client.'], function () {
		Route::get('/home', 'Client\ClientController@index')->name('home');
		Route::match(['get','post'],'/change-password', 'Client\ClientController@changePassword')->name('change-password');
		Route::match(['get','post'],'/orders', 'Client\ClientController@orders')->name('orders');
		Route::match(['get','post'],'/invoices', 'Client\ClientController@invoices')->name('invoices');
	});
	Route::group(['middleware' => ['auth','role:administrator|sales man|Bodega|sales person|manager'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
		
    Route::get('/transfer', 'Admin\ClientsController@transfer');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');

    Route::post('load/perm', 'Admin\UsersController@loadPerm')->name('load.perm');
    Route::post('perm/select', 'Admin\UsersController@permSelect')->name('perm.select');
    Route::post('perm/unselect', 'Admin\UsersController@permUnselect')->name('perm.unselect');
    Route::resource('users', 'Admin\UsersController');
    Route::get('users/profile/{id}', 'Admin\UsersController@userProfile')->name('userProfile');
	Route::match(['get','post'],'user/clients', 'Admin\UsersController@userClients')->name('userClients');
	Route::match(['get','post'],'user/orders', 'Admin\UsersController@userOrders')->name('userOrders');
	Route::match(['get','post'],'user/{id}/clients', 'Admin\UsersController@clientsSalesman')->name('salesClient');
	Route::match(['get','post'],'user/{id}/orders', 'Admin\UsersController@ordersSalesman')->name('salesOrders');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');

    Route::post('/storetosession', 'Admin\InvoicesController@sessoinsave')->name('invoices.savetosession');
	Route::get('/invoice/{id}/history', 'Admin\InvoicesController@history')->name('invoices.history');
	Route::get('/invoice/{id}/cancel', 'Admin\InvoicesController@cancel')->name('invoices.cancel');
    Route::resource('invoices', 'Admin\InvoicesController');
    Route::post('invoices/list', 'Admin\InvoicesController@index');
    
    Route::get('/receipts/cash-transactions', 'Admin\ReceiptsController@cash_transactions')->name('receipts.cash');

    Route::get('/receipts/athorize/{id}', 'Admin\ReceiptsController@cash_auth')->name('receipts.authorize');


    Route::post('/receipts/all', 'Admin\ReceiptsController@listAll')->name('receipts.all');
    Route::post('/pdfsendaspdf', 'Admin\ReceiptsController@pdfAsMail')->name('receipts.emailpdf');
    Route::get('/generatepdf/{id}', 'Admin\ReceiptsController@covertToPdf')->name('receipts.pdf');
    Route::get('/delete-receipt/{id}', 'Admin\ReceiptsController@deleteReceipt')->name('receiptss.delete');
    Route::post('/generatecsv', 'Admin\ReceiptsController@exportCSV')->name('receipts.csv');

    Route::resource('receipts', 'Admin\ReceiptsController');

    Route::post('/reports/all', 'Admin\ReportsController@listAll')->name('reports.all');
    Route::resource('reports', 'Admin\ReportsController');
    
	Route::get('/sales-reports', 'Admin\SalesReportsController@index')->name('salesReports');
	Route::post('/generate-report', 'Admin\SalesReportsController@generate')->name('reports-generate-all');
   
    //Route::resource('settings', 'Admin\SettingsController');
    Route::get('settings/index', 'Admin\SettingsController@index')->name('settings.index');
    Route::post('settings/store', 'Admin\SettingsController@store')->name('settings.store');
    Route::post('settings/index', 'Admin\SettingsController@index')->name('settings.index');
    Route::post('setting/create/factura-format', 'Admin\SettingsController@facturaFormat')->name('setting.facturaFormat');
    Route::post('setting/create/factura-format/update', 'Admin\SettingsController@facturaFormatUpdate')->name('setting.facturaFormatUpdate');
    Route::match(['get','post'],'setting/comision', 'Admin\SettingsController@comision');
    Route::get('item-delete/{id}', 'Admin\WarrantyController@deleteItem');
    Route::get('sales_man-received/{id}', 'Admin\WarrantyController@receiveFromWarehouse');
    Route::post('/send-replaced-email', 'Admin\WarrantyController@replaced_receipt')->name('items.emailreplaced');
    Route::post('/send-as-email', 'Admin\WarrantyController@mail_receipt')->name('items.emailaspdf');
    Route::resource('warranty-items', 'Admin\WarrantyController');
    Route::get('manager-received/{id}', 'Admin\ItemReplaceController@goods_receive');
    Route::get('preview-items/{id}', 'Admin\ItemReplaceController@preview');
    Route::resource('replace-item', 'Admin\ItemReplaceController');
    Route::get('products/{id}/delete', 'Admin\ProductsController@destroy')->name('products.destroy');
    Route::post('products-list', 'Admin\ProductsController@list')->name('products.list');
    Route::post('products-csv', 'Admin\ProductsController@importCSV')->name('importProductCSV');
    Route::resource('products', 'Admin\ProductsController');
    Route::get('product/priceTransfer', 'Admin\ProductsController@price');
    Route::post('products/updateqty', 'Admin\ProductsController@updateQty')->name('products.updateqty');
    Route::post('products/qtycomplete', 'Admin\ProductsController@qtyComplete')->name('products.qtycomplete');

    Route::get('clients/{id}/delete', 'Admin\ClientsController@destroy')->name('products.destroy');
    Route::post('clients-list', 'Admin\ClientsController@list')->name('clients.list');
    Route::post('clients-json', 'Admin\ClientsController@listJson')->name('clients.list.json');
    Route::post('clients-csv', 'Admin\ClientsController@importCSV')->name('importClientsCSV');
    Route::resource('clients', 'Admin\ClientsController');
    Route::get('clients/profile/{id}', 'Admin\ClientsController@profile')->name('profile');
    Route::match(['get','post'],'check-in', 'Admin\ClientsController@checkin')->name('checkin');
    Route::get('clients/{id}/invoices', 'Admin\ClientsController@invoices')->name('client.invoices');
    Route::match(['get','post'],'clients/{id}/orders', 'Admin\ClientsController@orders')->name('client.orders');
    Route::post('search/client', 'Admin\ClientsController@search')->name('client.search');
    Route::match(['get','post'],'client/products/{id}', 'Admin\ClientsController@products')->name('client.products');
    
    Route::post('orders/ajax/{id}', 'Admin\OrderController@ajaxOrder')->name('orders.ajax');
    Route::post('orders/getPrice', 'Admin\OrderController@getPrice')->name('orders.getPrice');
    Route::get('orders/approve/{id}', 'Admin\OrderController@approve')->name('orders.approve');
    Route::get('orders/disapprove/{id}', 'Admin\OrderController@disapprove')->name('orders.disapprove');
    Route::get('orders/dispatch/{id}', 'Admin\OrderController@dispatch')->name('orders.dispatch');
    Route::get('orders/deliver/{id}', 'Admin\OrderController@deliver')->name('orders.deliver');
    Route::get('orders/{id}/delete', 'Admin\OrderController@destroy')->name('orders.destroy');
    Route::get('orders/{id}/generate-invoice', 'Admin\OrderController@generateInvoice')->name('orders.generateinvoice');
    Route::post('orders/{id}/generate-invoice', 'Admin\OrderController@storeInvoice')->name('orders.invoice.store');
    Route::get('orders/{id}/delete-product', 'Admin\OrderController@orderDeleteProduct')->name('orders.delete.product');
    Route::post('orders/update-quantity', 'Admin\OrderController@updateProductQuantity')->name('orders.update.quantity');
    Route::post('orders-list', 'Admin\OrderController@list')->name('orders.list');
    Route::get('orders-pdf/{id}', 'Admin\OrderController@covertToPdf')->name('orders.pdf');
    Route::get('invoice-pdf/{id}', 'Admin\OrderController@downloadInvoice')->name('invoice.pdf');
    Route::post('orders-pdfmail/{id}', 'Admin\OrderController@emailpdf')->name('orders.emailpdf');
	Route::post('nota-de-credito', 'Admin\NotaCreditController@store')->name('notacredit.store');
	Route::post('nota-de-credito-list', 'Admin\NotaCreditController@list')->name('notacredit.list');
	Route::get('nota-de-credito-list', 'Admin\NotaCreditController@index')->name('notacredit.index');
	Route::get('nota-de-credito-pdf/{id}', 'Admin\NotaCreditController@pdf')->name('nota.pdf');
	Route::post('filter-commision', 'Admin\CommisionController@calculateCommision')->name('filterComm');

    Route::resource('orders', 'Admin\OrderController');


});
