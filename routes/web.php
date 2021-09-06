<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LoginAuthController;
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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


/*
/*
|--------------------------------------------------------------------------
| Admin PROJECT
|--------------------------------------------------------------------------
*/
//Route::get('/projects', 'ProjectController@index');
//Route::get('/delete-project', 'ProjectController@destroy');

Route::get('/form-add-project', [ProjectController::class, 'formAddProject'])->middleware('auth');

Route::post('/add-project', [ProjectController::class, 'addProject'])->middleware('auth');

Route::get('/form-edit-project/{id}', [ProjectController::class, 'formEditProject'])->middleware('auth');

Route::post('/edit-project', [ProjectController::class, 'editProject'])->middleware('auth');

Route::get('/projects', [ProjectController::class, 'index'])->middleware('auth'); // show list Project

Route::get('/delete-project/{id}', [ProjectController::class, 'deleteProject'])->middleware('auth'); //delete project by id

/*
|--------------------------------------------------------------------------
| Admin ESTIMATE
|--------------------------------------------------------------------------
*/
//Route::get('/estimates', 'EstimateController@index');

Route::get('/estimates', [EstimateController::class, 'index'])->middleware('auth'); // show list estimate

Route::get('/download-estimate/{id}', [EstimateController::class, 'downloadEstimate'])->middleware('auth'); //download file

Route::get('/form-add-estimate', [EstimateController::class, 'formAddEstimate'])->middleware('auth'); //show form add estimate

Route::post('/add-estimate', [EstimateController::class, 'addEstimate'])->middleware('auth'); // add estimate and upload file

Route::get('/form-edit-estimate/{id}', [EstimateController::class, 'formEditEstimate'])->middleware('auth'); //show form edit estimate

Route::post('/edit-estimate', [EstimateController::class, 'editEstimate'])->middleware('auth'); // edit estimate 

Route::get('/delete-estimate/{id}', [EstimateController::class, 'deleteEstimate'])->middleware('auth'); //delete estimate by id


/*
|--------------------------------------------------------------------------
| Admin CUSTOMER
|--------------------------------------------------------------------------
*/
Route::get('/customers', [CustomerController::class, 'index'])->middleware('auth');   // show list customer

Route::get('/form-edit-customer/{id}', [CustomerController::class, 'formEditCustomer'])->middleware('auth'); //show form edit customer

Route::post('/edit-customer', [CustomerController::class, 'editCustomer'])->middleware('auth'); //post: edit customer

Route::get('/delete-customer/{id}', [CustomerController::class, 'deleteCustomer'])->middleware('auth'); //delete customer by id

Route::get('/form-add-customer', [CustomerController::class, 'formAddCustomer'])->middleware('auth'); //show form add customer

Route::post('/add-customer', [CustomerController::class, 'addCustomer'])->middleware('auth'); //add customer
/*
|--------------------------------------------------------------------------
| Admin INVOICE
|--------------------------------------------------------------------------
*/
Route::get('/invoices', [InvoiceController::class, 'index'])->middleware('auth');//get all invoice

Route::get('/invoice-detail/{id}', [InvoiceController::class, 'invoiceDetail'])->middleware('auth');//show invoice detail

Route::get('/form-add-invoice', [InvoiceController::class, 'formAddInvoice'])->middleware('auth');//show form add invoice

Route::post('/add-invoice', [InvoiceController::class, 'addInvoice'])->middleware('auth'); //add invoice

//Route::get('/get-project', [InvoiceController::class, 'getProject'])->middleware('auth');// show all project

Route::get('/get-info-customer', [InvoiceController::class, 'getInfoCustomer'])->middleware('auth');//show customer info by ajax

Route::get('/export-invoice/{id}={type}', [InvoiceController::class, 'exportInvoice'])->middleware('auth');//export excel

Route::post('/update-status', [InvoiceController::class, 'updateStatus'])->middleware('auth'); //update status invoice

Route::get('/form-edit-invoice/{id}', [InvoiceController::class, 'formEditInvoice'])->middleware('auth'); //show form edit

Route::post('/edit-invoice', [InvoiceController::class, 'editInvoice'])->middleware('auth'); //update invoice

Route::get('/delete-invoice/{id}', [InvoiceController::class, 'deleteInvoice'])->middleware('auth'); //delete invoice

Route::get('/ajax-get-item', [InvoiceController::class, 'getItem'])->middleware('auth'); // get item when select project


/*
|--------------------------------------------------------------------------
| Admin ITEM
|--------------------------------------------------------------------------
*/
Route::get('/items', [ItemController::class, 'index'])->middleware('auth');

Route::get('/items/{projectId}', [ItemController::class, 'findItemByProjectID'])->middleware('auth');

Route::get('/form-add-item', [ItemController::class, 'formAddItem'])->middleware('auth'); //show form add item

Route::post('/add-item', [ItemController::class, 'addItem'])->middleware('auth'); //add item

Route::get('/form-edit-item/{id}', [ItemController::class, 'formEditItem'])->middleware('auth'); //show form edit item

Route::post('/edit-item', [ItemController::class, 'editItem'])->middleware('auth'); //post: edit item

Route::get('/delete-item/{id}', [ItemController::class, 'deleteItem'])->middleware('auth'); //delete item by id


/*
|--------------------------------------------------------------------------
| Admin ORDER
|--------------------------------------------------------------------------
*/
Route::post('/add-order', [OrderController::class, 'addOrder'])->middleware('auth'); // add estimate and upload file