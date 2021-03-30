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

Route::get('/', function () {
    return view('welcome');
});

//Admin Routes....

Route::get('Login',function(){
    return view('admin.adminlogin');
});

Route::get('AdminPanel',function(){
    return view('admin.adminindex');
});

Route::get('/orderdetails',function(){
    return view('admin.orderpage.vieworder');
});

Route::get('Add-Category',function(){
    return view('admin.category.addcategory');
});

Route::get('view-category',function(){
    return view('admin.category.viewcategory');
});

Route::get('update-category',function(){
    return view('admin.category.updatecategory');
});

Route::get('addsubcategory',function(){
    return view('admin.subcategory.addsubcategory');
});

Route::get('view-subcategory',function(){
    return view('admin.subcategory.viewsubcategory');
});

Route::get('update-subcategory',function(){
    return view('admin.subcategory.updatesubcategory');
});

Route::get('addproduct',function(){
    return view('admin.product.addproduct');
});

Route::get('view-product',function(){
    return view('admin.product.viewproduct');
});

Route::get('update-product',function(){
    return view('admin.product.updateproduct');
});

Route::get('addcolor',function(){
    return view('admin.color.addcolor');
});

Route::get('view-color',function(){
    return view('admin.color.viewcolor');
});

Route::get('update-color',function(){
    return view('admin.color.updatecolor');
});

Route::get('addbrand',function(){
    return view('admin.brand.addbrand');
});

Route::get('view-brand',function(){
    return view('admin.brand.viewbrand');
});

Route::get('update-brand',function(){
    return view('admin.brand.updatebrand');
});

//Admin




//User Route....
Route::get('/index',function(){
    return view('user.index');
});

Route::get('/user-register',function(){
    return view('user.userregister');
});

Route::get('/forgetpassword',function(){
    return view('user.forgetpassword');
});

Route::get('/resetpage',function(){
    return view('user.Resetpassword');
});

Route::get('/user-login',function(){
    return view('user.userlogin');
});

Route::get('/user-cart',function(){
    return view('user.cart');
});

Route::get('/user-category',function(){
    return view('user.category');
});

Route::get('/user-checkout',function(){
    return view('user.checkout');
});

Route::get('/user-contact',function(){
    return view('user.contact');
});

Route::get('/user-product',function(){
    return view('user.product');
});

Route::get('/user-registermail',function(){
    return view('user.registermail');
});

Route::get('/user-dispatch',function(){
    return view('user.dispatch');
});

//User

//UserControllers
Auth::Routes();
Route::get('/user', 'UserController@index')->name('/');
Route::post('/Rdata','UserController@Rdata');
Route::post('/logins','UserController@logins');
Route::get('/logins','UserController@list');
Route::post('/updatepass','UserController@resetpassword');
Route::match(['get','post'],'forget','UserController@forget');
Route::get('/userlogout','UserController@userlogout');

//AdminController
Route::get('/admin', 'AdminController@index')->name('admin');
Route::post('/logs','AdminController@logs');
Route::post('/add_category','AdminController@add_category');
Route::post('/add_subcategory','AdminController@add_subcategory');
Route::post('/addproduct','AdminController@addproduct');
Route::post('/add_color','AdminController@add_color');
Route::post('/add_brand','AdminController@add_brand');
Route::get('/orderdetails/{id}','AdminController@vieworder');
Route::post('/updateorder/{id}','AdminController@updateorder');
Route::get('/logout','AdminController@logout');

//mainpagecontroller
Route::get('/AdminPanel', 'mainpagecontroller@index');

//CategoryController
Route::get('/view-category','Categorycontroller@index');
Route::get('/click_edit/{id}','Categorycontroller@ucategory');
Route::post('/update/{id}','Categorycontroller@upcategory');
Route::get('/click_delete/{id}','Categorycontroller@dcategory');

//SubCategoryController
Route::get('/addsubcategory','Subcategorycontroller@index');
Route::get('/view-subcategory','Subcategorycontroller@view');
Route::get('click_edit_sub/{id}','Subcategorycontroller@ucategory');
Route::post('/updatesub/{id}','Subcategorycontroller@upcategory');
Route::get('/click_delete_sub/{id}','Subcategorycontroller@dcategory');

//Productcontroller
Route::get('/addproduct','Productcontroller@index');
Route::get('/view-product','Productcontroller@view');
Route::get('/click_edit_pro/{id}','productcontroller@uproduct');
Route::post('/updateproduct/{id}','productcontroller@upproduct');
Route::get('/click_delete_pro/{id}','productcontroller@dproduct');

//cartcontroller
Route::get('/viewproduct/{id}','cartcontroller@index');
Route::post('/addcart/{id}','cartcontroller@addcart');
Route::get('/Shoppingcart','cartcontroller@shoppingcart');
Route::get('/deletecart/{id}','cartcontroller@deletecart');
Route::get('/checkoutdata','cartcontroller@checkoutdata');

//BookingController
Route::post('/Orders','Bookingcontroller@index');
//Route::get('/Orders','Bookingcontroller@store');

//brandcolorcontroller
Route::get('/view-color','brandcolorcontroller@index');
Route::get('/click_edit_color/{id}','brandcolorcontroller@click_edit_color');
Route::post('/updatecolor/{id}','brandcolorcontroller@upcolor');
Route::get('/click_delete_color/{id}','brandcolorcontroller@click_delete_color');

Route::get('/view-brand','brandcolorcontroller@view');
Route::get('/click_edit_brand/{id}','brandcolorcontroller@click_edit_brand');
Route::post('/updatebrand/{id}','brandcolorcontroller@upbrand');
Route::get('/click_delete_brand/{id}','brandcolorcontroller@click_delete_brand');
//Functions




