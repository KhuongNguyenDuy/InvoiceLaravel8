<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;

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
/*
|--------------------------------------------------------------------------
| Admin Project
|--------------------------------------------------------------------------
*/
//Route::get('/projects', 'ProjectController@index');
//Route::get('/delete-project', 'ProjectController@destroy');
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/delete-project/{id}', [ProjectController::class, 'deleteProject']);
/*
|--------------------------------------------------------------------------
| Admin Estimate
|--------------------------------------------------------------------------
*/
//Route::get('/estimates', 'EstimateController@index');
Route::get('/estimates', [EstimateController::class, 'index']);
Route::get('/download-estimate/{id}', [EstimateController::class, 'downloadEstimate']); //download file
Route::get('/form-add-estimate', [EstimateController::class, 'formAddEstimate']);
Route::post('/add-estimate', [EstimateController::class, 'addEstimate']);

/*
|--------------------------------------------------------------------------
| Admin Customer
|--------------------------------------------------------------------------
*/
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/form-edit-customer/{id}', [CustomerController::class, 'formEditCustomer']);
Route::post('/edit-customer', [CustomerController::class, 'editCustomer']);
Route::get('/delete-customer/{id}', [CustomerController::class, 'deleteCustomer']);
Route::get('/form-add-customer', [CustomerController::class, 'formAddCustomer']);
Route::post('/add-customer', [CustomerController::class, 'addCustomer']);
/*
|--------------------------------------------------------------------------
| Admin Invoice
|--------------------------------------------------------------------------
*/
Route::get('/invoices', [InvoiceController::class, 'index']);//get all invoice
Route::get('/invoice-detail/{id}', [InvoiceController::class, 'invoiceDetail']);//show invoice detail
Route::post('/form-add-invoice', [InvoiceController::class, 'formAddInvoice']);//show form add invoice
Route::post('/add-invoice', [InvoiceController::class, 'addInvoice']); //add invoice
Route::get('/get-project', [InvoiceController::class, 'getProject']);// show all project
Route::get('/get-customer', [InvoiceController::class, 'getCustomer']);//show customer info by ajax
Route::get('/export-invoice/{id}={type}', [InvoiceController::class, 'exportInvoice']);//export excel
Route::post('/update-status', [InvoiceController::class, 'updateStatus']); //update status invoice

//Route::get('/export-invoice{id}','ProjectController@exportInvoice');
/*
|--------------------------------------------------------------------------
| Admin Item
|--------------------------------------------------------------------------
*/
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{projectId}', [ItemController::class, 'findItemByProjectID']);

/*
|--------------------------------------------------------------------------
| Web Routes Invoice
|--------------------------------------------------------------------------
|
*/
