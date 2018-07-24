<?php
use Illuminate\Support\Facades\DB;
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

Route::get('/', 'HomeController@index');
Route::get('/account', 'UserController@index');
Route::post('/addorderitem','CartOrderController@additem');
Route::post('/changeQuantity','CartOrderController@changeQuantity');
Route::post('/changeOrderName', 'CartOrderController@changeOrderName');
Route::post('/changeCustomFoodName','CartOrderController@changeCustomFoodName');
Route::post('/deleteuserinfo', 'UserController@delete' );
Route::patch('/deleteorderfood','CartOrderController@deletefood');
Route::get('/deliverylocation', 'DeliveryLocationController@show');
Route::get('/food/{catid}', 'FoodController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/item/{foodItem}', 'FoodController@show');
Route::get('/keepusersdeliveryaddress', 'DeliveryLocationController@setorderuseraddress');
Route::get('/ourmenu', 'CategoryController@show');
Route::get('/onlineorder','CartOrderController@index');
Route::post('/ordertimeset', 'OrderTimeController@ordertimeset');
Route::get('/pickuporder','CartOrderController@makePickupOrder');
Route::get('/pickupordeliverymethod', 'CartOrderController@pickupordeliverymethod');
Route::post('/placeorder', 'OrderController@placeOrder');
Route::post('/removeDiscount', 'DiscountController@removeDiscount');
Route::post('removeTimeReservation', 'OrderTimeController@removeTimeReservation');
Route::get('/revieworder', 'CartOrderController@reviewOrder');
Route::get('/settimeforcustomdeliverylocation', 'DeliveryLocationController@goToDeliveryTimeAfterSettingOrderAddress');
Route::post('/setdeliverylocation', 'DeliveryLocationController@updateUserDeliveryLocation');
Route::patch('/setorderaddress', 'CartOrderController@setorderaddress');
Route::post('/useDiscount', 'DiscountController@useDiscount');
Route::post('/user', 'UserController@update');
Route::get('/userSession/{id}', 'UserController@show');
Route::get('/vieworder', 'CartOrderController@show');

Auth::routes();

