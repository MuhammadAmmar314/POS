<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

Route::resource('/carts', App\Http\Controllers\CartController::class);
Route::get('/carts/{id}/api', [App\Http\Controllers\CartController::class, 'api'])->name('carts.api');
Route::get('/carts/loadform/{total}/{diterima}', [App\Http\Controllers\CartController::class, 'loadForm'])->name('carts.load_form');

Route::resource('/invoices', App\Http\Controllers\InvoiceController::class);
Route::get('api/invoices', [App\Http\Controllers\InvoiceController::class, 'api']);


Route::resource('/product_units', App\Http\Controllers\ProductUnitController::class);
Route::get('/api/product_units', [App\Http\Controllers\ProductUnitController::class, 'api']);

Route::resource('/product_categories', App\Http\Controllers\ProductCategoryController::class);
Route::get('/api/product_categories', [App\Http\Controllers\ProductCategoryController::class, 'api']);

Route::resource('/members', App\Http\Controllers\MemberController::class);
Route::get('/api/members', [App\Http\Controllers\MemberController::class, 'api']);

Route::resource('/products', App\Http\Controllers\ProductController::class);
Route::get('/api/products', [App\Http\Controllers\ProductController::class, 'api']);
