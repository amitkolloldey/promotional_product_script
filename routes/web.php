<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

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

// Front Page's Routes
Route::get('/', 'PageController@home')->name('home');
Route::get('page/{page}', 'PageController@index')->name('page');


// Front Post's Routes
Route::get('post/{post}', 'PostController@index')->name('post');


// Front Message's Routes
Route::post('/contact_save', 'MessageController@saveContactData')->name('contact_save');


// Front Category's Routes
Route::get('/category/{category}', 'CategoryController@index')->name('category_show');


// Front User's Routes
Route::post('/user/update/{id}', 'UserController@frontUserUpdate')->name('front_user_update');


// Front Cache's Routes
Route::get('cache/clear/', 'CacheController@cache_clear')->name('cache_clear');


// Front Products's Routes
Route::get('/product/{product}', 'ProductController@index')->name('product_show');
Route::get('/product_pricing/matrix', 'ProductController@viewProductPricing')->name('view_product_pricing');
Route::get('/products/search', 'ProductController@search')->name('search');


// Front Order's Routes
Route::get('/order/branding_color', 'OrderController@getPersonalisationColor')->name('get_personalisation_color');
Route::post('/cart/store/{product_id}', 'OrderController@cartStore')->name('cart_store');
Route::get('/order/create/', 'OrderController@create')->name('order_create');
Route::get('/order/show/pricing/', 'OrderController@getPricing')->name('get_pricing');
Route::post('/order/cart/store/', 'OrderController@orderCartStore')->name('order_cart_store');
Route::get('/order/authenticate', 'OrderController@orderAuthenticate')->name('order_authenticate');
Route::get('/order/checkout/', 'OrderController@orderCheckout')->name('order_checkout')->middleware(['verified']);
Route::post('/order/checkout/submit', 'OrderController@orderSubmit')->name('order_submit')->middleware(['customer']);
Route::get('/order/checkout/thankyou', 'OrderController@orderThankyou')->name('order_thankyou')->middleware(['customer']);
Route::get('/order/show/{id}', 'OrderController@orderShow')->name('order_show')->middleware(['customer']);


// Front Quotation's Routes
Route::get('/quotation/create/', 'QuotationController@create')->name('quotation_create');
Route::post('/quotation/store/{id}', 'QuotationController@store')->name('quotation_store');
Route::post('/quotation/cart/store/{id}', 'QuotationController@cartStore')->name('quotation_cart_store');
Route::get('/quotation/thankyou', 'QuotationController@quotationThankyou')->name('quotation_thankyou');


// Front Question's Routes
Route::get('/question/create/', 'QuickQuestionController@create')->name('question_create');
Route::post('/question/store/{id}', 'QuickQuestionController@store')->name('question_store');
Route::post('/question/cart/store/{id}', 'QuickQuestionController@cartStore')->name('question_cart_store');
Route::get('/question/thankyou', 'QuickQuestionController@questionThankyou')->name('question_thankyou');


// Front Compare Product's Routes
Route::get('/compare/product/', 'ProductCompareController@compares')->name('product_compare');
Route::get('/compare/add/{id}', 'ProductCompareController@addToCompare')->name('add_to_compare');
Route::get('/compare/remove/{id}', 'ProductCompareController@removeCompare')->name('remove_compare');
Route::get('/compare/remove_all/', 'ProductCompareController@removeAll')->name('remove_all_compare');


// Auth Routes
Route::group(['prefix' => 'auth'], function () {
    Auth::routes(['verify' => true]);
});


// Admin Filemanager's Routes
Route::group(['prefix' => 'admin/filemanager', 'middleware' => ['permission:access media']], function () {
    Lfm::routes();
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified']], function () {

    // Cache's Routes
    Route::get('/cache/products_all_with_categories', 'CacheController@products_all_with_categories')->name('products_all_with_categories');
    Route::get('/cache/categories_all', 'CacheController@categories_all')->name('categories_all');
    Route::get('/cache/messages_all', 'CacheController@messages_all')->name('messages_all');
    Route::get('/cache/orders_all', 'CacheController@orders_all')->name('orders_all');
    Route::get('/cache/questions_all', 'CacheController@questions_all')->name('questions_all');
    Route::get('/cache/quotations_all', 'CacheController@quotations_all')->name('quotations_all');
    Route::get('/cache/users_all', 'CacheController@users_all')->name('users_all');


    // Dashboard's Routes...
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');


    // Table Action's Routes
    Route::delete('/deleteselected', 'TableActionController@deleteSelected')->name('selected_item_delete');
    Route::post('/makenew', 'TableActionController@makeNewProduct')->name('make_new');
    Route::post('/makepopular', 'TableActionController@makePopularProduct')->name('make_popular');
    Route::post('/makediscontinuedstock', 'TableActionController@makeDiscontinuedStock')->name('make_discontinued_stock');
    Route::post('/undonew', 'TableActionController@undoNewProduct')->name('undo_new');
    Route::post('/undopopular', 'TableActionController@undoPopularProduct')->name('undo_popular');
    Route::post('/undodiscontinuedstock', 'TableActionController@undoDiscontinuedStock')->name('undo_discontinued_stock');
    Route::post('/markasread', 'TableActionController@markAsRead')->name('mark_as_read');
    Route::post('/markasunread', 'TableActionController@markAsUnRead')->name('mark_as_unread');


    // User's Routes...
    Route::get('/users', 'UserController@users')->name('users');
    Route::get('/user/create', 'UserController@create')->name('user_create');
    Route::post('/user/store', 'UserController@store')->name('user_store');
    Route::delete('/user/delete/{id?}', 'UserController@destroy')->name('user_delete');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('user_edit');
    Route::put('/user/update/{id}', 'UserController@update')->name('user_update');


    // Role's Routes...
    Route::get('/roles', 'RoleController@roles')->name('roles');
    Route::get('/role/create', 'RoleController@create')->name('role_create');
    Route::post('/role/store', 'RoleController@store')->name('role_store');
    Route::delete('/role/delete/{id?}', 'RoleController@destroy')->name('role_delete');
    Route::get('/role/edit/{id}', 'RoleController@edit')->name('role_edit');
    Route::put('/role/update/{id}', 'RoleController@update')->name('role_update');


    // Permission's Routes...
    Route::get('/permissions', 'PermissionController@permissions')->name('permissions');
    Route::get('/permission/create', 'PermissionController@create')->name('permission_create');
    Route::post('/permission/store', 'PermissionController@store')->name('permission_store');
    Route::delete('/permission/delete/{id?}', 'PermissionController@destroy')->name('permission_delete');
    Route::get('/permission/edit/{id}', 'PermissionController@edit')->name('permission_edit');
    Route::put('/permission/update/{id}', 'PermissionController@update')->name('permission_update');


    // Message's Routes...
    Route::get('/messages', 'MessageController@messages')->name('messages');
    Route::post('/message/store', 'MessageController@store')->name('message_store');
    Route::get('/message/view/{id}', 'MessageController@show')->name('message_show');
    Route::delete('/message/delete/{id?}', 'MessageController@destroy')->name('message_delete');


    // Setting's Routes...
    Route::get('/settings', 'SettingController@settings')->name('site_settings');
    Route::post('/settings/store', 'SettingController@store')->name('settings_store');
    Route::post('/settings/update/{id}', 'SettingController@update')->name('settings_update');


    // Page's Routes...
    Route::get('/pages', 'PageController@pages')->name('pages');
    Route::get('/page/create', 'PageController@create')->name('page_create');
    Route::post('/page/store', 'PageController@store')->name('page_store');
    Route::get('/page/edit/{id}', 'PageController@edit')->name('page_edit');
    Route::post('/page/update/{id}', 'PageController@update')->name('page_update');
    Route::delete('/page/delete/{id?}', 'PageController@destroy')->name('page_delete');


    // Post's Routes...
    Route::get('/posts', 'PostController@posts')->name('posts');
    Route::get('/post/create', 'PostController@create')->name('post_create');
    Route::post('/post/store', 'PostController@store')->name('post_store');
    Route::get('/post/edit/{id}', 'PostController@edit')->name('post_edit');
    Route::post('/post/update/{id}', 'PostController@update')->name('post_update');
    Route::delete('/post/delete/{id?}', 'PostController@destroy')->name('post_delete');


    // Product's Routes...
    Route::get('/products', 'ProductController@products')->name('products');
    Route::get('/get_products', 'ProductController@getProducts')->name('get_products');
    Route::get('/product/create', 'ProductController@create')->name('product_create');
    Route::post('/product/store/', 'ProductController@store')->name('product_store');
    Route::get('/product/edit/{id}', 'ProductController@edit')->name('product_edit');
    Route::put('/product/update/{slug}', 'ProductController@update')->name('product_update');
    Route::delete('/product/delete/{id?}', 'ProductController@destroy')->name('product_delete');
    Route::get('product/get_sub_categories', 'ProductController@getSubCategories')->name('get_sub_categories');
    Route::get('product/get_sub_sub_categories', 'ProductController@getSubSubCategories')->name('get_sub_sub_categories');
    Route::post('product/attribute_insert', 'ProductController@insertAttribute')->name('attribute_insert');
    Route::post('product/attribute_primary_color_update', 'ProductController@updatePrimaryColor')->name('attribute_primary_color_update');
    Route::delete('product/attribute_delete/{aid?}', 'ProductController@deleteAttribute')->name('attribute_delete');
    Route::get('/export/products', 'ProductController@exportProducts')->name('products_export');
    Route::get('/import/products', 'ProductController@importProducts')->name('products_import');
    Route::post('/upload/products', 'ProductController@uploadProducts')->name('products_upload');


    // Category's Routes...
    Route::get('/categories', 'CategoryController@categories')->name('categories');
    Route::get('/category/create', 'CategoryController@create')->name('category_create');
    Route::post('/category/store/', 'CategoryController@store')->name('category_store');
    Route::get('/category/edit/{id}', 'CategoryController@edit')->name('category_edit');
    Route::put('/category/update/{id}', 'CategoryController@update')->name('category_update');
    Route::delete('/category/delete/{id?}', 'CategoryController@destroy')->name('category_delete');
    Route::get('/export/categories', 'CategoryController@exportCategories')->name('categories_export');
    Route::get('/export/categorymarkups', 'CategoryController@exportCategoryMarkups')->name('category_markups_export');
    Route::get('/import/categories', 'CategoryController@importCategories')->name('categories_import');
    Route::post('/upload/categories', 'CategoryController@uploadCategories')->name('categories_upload');
    Route::get('/import/category_markups', 'CategoryController@importCategoryMarkups')->name('category_markups_import');
    Route::post('/upload/category_markups', 'CategoryController@uploadCategoryMarkups')->name('category_markups_upload');


    // Quantity's Routes...
    Route::get('/quantities', 'QuantityController@quantities')->name('quantities');
    Route::get('/quantity/create', 'QuantityController@create')->name('quantity_create');
    Route::post('/quantity/store/', 'QuantityController@store')->name('quantity_store');
    Route::get('/quantity/edit/{id}', 'QuantityController@edit')->name('quantity_edit');
    Route::put('/quantity/update/{id}', 'QuantityController@update')->name('quantity_update');
    Route::delete('/quantity/delete/{id?}', 'QuantityController@destroy')->name('quantity_delete');


    // USB Type's Routes...
    Route::get('/usb_types', 'UsbTypeController@usbTypes')->name('usb_types');
    Route::get('/usb_type/create', 'UsbTypeController@create')->name('usb_type_create');
    Route::post('/usb_type/store/', 'UsbTypeController@store')->name('usb_type_store');
    Route::get('/usb_type/edit/{id}', 'UsbTypeController@edit')->name('usb_type_edit');
    Route::put('/usb_type/update/{id}', 'UsbTypeController@update')->name('usb_type_update');
    Route::delete('/usb_type/delete/{id?}', 'UsbTypeController@destroy')->name('usb_type_delete');


    // Primary Color's Routes...
    Route::get('/primary_colors', 'PrimaryColorController@primaryColors')->name('primary_colors');
    Route::get('/primarycolor/create', 'PrimaryColorController@create')->name('primary_color_create');
    Route::post('/primarycolor/store/', 'PrimaryColorController@store')->name('primary_color_store');
    Route::get('/primarycolor/edit/{id}', 'PrimaryColorController@edit')->name('primary_color_edit');
    Route::put('/primarycolor/update/{id}', 'PrimaryColorController@update')->name('primary_color_update');
    Route::delete('/primarycolor/delete/{id?}', 'PrimaryColorController@destroy')->name('primary_color_delete');


    // Printing Agency's Routes...
    Route::get('/printingagencies', 'PrintingAgencyController@printingAgencies')->name('printing_agencies');
    Route::get('/printingagency/create', 'PrintingAgencyController@create')->name('printing_agency_create');
    Route::post('/printingagency/store/', 'PrintingAgencyController@store')->name('printing_agency_store');
    Route::get('/printingagency/edit/{id}', 'PrintingAgencyController@edit')->name('printing_agency_edit');
    Route::put('/printingagency/update/{id}', 'PrintingAgencyController@update')->name('printing_agency_update');
    Route::delete('/printingagency/delete/{id?}', 'PrintingAgencyController@destroy')->name('printing_agency_delete');


    // Manufacturer's Routes...
    Route::get('/manufacturers', 'ManufacturerController@manufacturers')->name('manufacturers');
    Route::get('/manufacturer/create', 'ManufacturerController@create')->name('manufacturer_create');
    Route::post('/manufacturer/store/', 'ManufacturerController@store')->name('manufacturer_store');
    Route::get('/manufacturer/edit/{id}', 'ManufacturerController@edit')->name('manufacturer_edit');
    Route::put('/manufacturer/update/{id}', 'ManufacturerController@update')->name('manufacturer_update');
    Route::delete('/manufacturer/delete/{id?}', 'ManufacturerController@destroy')->name('manufacturer_delete');


    // Personalisation option's Routes...
    Route::get('/personalisationoptions', 'PersonalisationoptionController@personalisationOptions')->name('personalisation_options');
    Route::get('/personalisationoption/create', 'PersonalisationoptionController@create')->name('personalisation_option_create');
    Route::post('/personalisationoption/store/', 'PersonalisationoptionController@store')->name('personalisation_option_store');
    Route::get('/personalisationoption/edit/{id}', 'PersonalisationoptionController@edit')->name('personalisation_option_edit');
    Route::put('/personalisationoption/update/{id}', 'PersonalisationoptionController@update')->name('personalisation_option_update');
    Route::delete('/personalisationoption/delete/{id?}', 'PersonalisationoptionController@destroy')->name('personalisation_option_delete');
    Route::delete('personalisationoptionvalue/option_delete/{oid?}', 'PersonalisationoptionController@deleteOption')->name('option_delete');


    // Personalisation Type's Routes...
    Route::get('/personalisationtypes', 'PersonalisationtypeController@personalisationTypes')->name('personalisation_types');
    Route::get('/personalisationtype/create', 'PersonalisationtypeController@create')->name('personalisation_type_create');
    Route::post('/personalisationtype/store/', 'PersonalisationtypeController@store')->name('personalisation_type_store');
    Route::get('/personalisationtype/edit/{id}/', 'PersonalisationtypeController@edit')->name('personalisation_type_edit');
    Route::put('/personalisationtype/update/{id}', 'PersonalisationtypeController@update')->name('personalisation_type_update');
    Route::get('/personalisation_type_pricing/matrix', 'PersonalisationtypeController@viewPersonalisationTypePricing')->name('view_personalisation_type_pricing');
    Route::delete('/personalisationtype/delete/{id?}', 'PersonalisationtypeController@destroy')->name('personalisation_type_delete');


    Route::get('/export/personalisationprices', 'PersonalisationtypeController@exportPersonalisationPrices')->name('personalisation_prices_export');
    Route::get('/export/personalisationtypemarkups', 'PersonalisationtypeController@exportPersonalisationTypeMarkups')->name('personalisation_type_markups_export');


    // Order's Routes...
    Route::get('/orders', 'OrderController@orders')->name('orders');
    Route::get('/order/edit/{id}', 'OrderController@edit')->name('order_edit');
    Route::put('/order/update/{id}', 'OrderController@update')->name('order_update');
    Route::delete('/order/delete/{id?}', 'OrderController@destroy?')->name('order_delete');


    // Quotation's Routes...
    Route::get('/quotations', 'QuotationController@quotations')->name('quotations');
    Route::get('/quotation/edit/{id}', 'QuotationController@edit')->name('quotation_edit');
    Route::put('/quotation/update/{id}', 'QuotationController@update')->name('quotation_update');
    Route::delete('/quotation/delete/{id?}', 'QuotationController@destroy?')->name('quotation_delete');


    // Question's Routes...
    Route::get('/questions', 'QuickQuestionController@questions')->name('questions');
    Route::get('/question/edit/{id}', 'QuickQuestionController@edit')->name('question_edit');
    Route::put('/question/update/{id}', 'QuickQuestionController@update')->name('question_update');
    Route::delete('/question/delete/{id?}', 'QuickQuestionController@destroy?')->name('question_delete');


    // Client's Routes...
    Route::get('/clients', 'ClientController@clients')->name('clients');
    Route::get('/client/create', 'ClientController@create')->name('client_create');
    Route::post('/client/store/', 'ClientController@store')->name('client_store');
    Route::get('/client/edit/{id}', 'ClientController@edit')->name('client_edit');
    Route::put('/client/update/{id}', 'ClientController@update')->name('client_update');
    Route::delete('/client/delete/{id?}', 'ClientController@destroy')->name('client_delete');
});


