<?php

use Illuminate\Routing\Router;
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

// $router->get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group(['middleware' => 'auth'], function (Router $router) {

    /*
    /*
    |--------------------------------------------------------------------------
    | Admin PROJECT
    |--------------------------------------------------------------------------
    */
    $router->get('/form-add-project', [ProjectController::class, 'formAddProject']);
    $router->get('/form-edit-project/{id}', [ProjectController::class, 'formEditProject']);
    $router->get('/projects', [ProjectController::class, 'index']); // show list Project
    $router->get('/delete-project/{id}', [ProjectController::class, 'deleteProject']); //delete project by id
    $router->post('/add-project', [ProjectController::class, 'addProject']);
    $router->put('/edit-project', [ProjectController::class, 'editProject']);

    /*
    |--------------------------------------------------------------------------
    | Admin ESTIMATE
    |--------------------------------------------------------------------------
    */
    $router->get('/estimates', [EstimateController::class, 'index']); // show list estimate
    $router->get('/delete-estimate/{id}', [EstimateController::class, 'deleteEstimate']); //delete estimate by id
    $router->get('/download-estimate/{id}', [EstimateController::class, 'downloadEstimate']); //download file
    $router->get('/form-add-estimate', [EstimateController::class, 'formAddEstimate']); //show form add estimate
    $router->get('/form-edit-estimate/{id}', [EstimateController::class, 'formEditEstimate']); //show form edit estimate
    $router->post('/add-estimate', [EstimateController::class, 'addEstimate']); // add estimate and upload file
    $router->post('/edit-estimate', [EstimateController::class, 'editEstimate']); // edit estimate

    /*
    |--------------------------------------------------------------------------
    | Admin CUSTOMER
    |--------------------------------------------------------------------------
    */
    $router->get('/customers', [CustomerController::class, 'index']);   // show list customer
    $router->get('/form-edit-customer/{id}', [CustomerController::class, 'formEditCustomer']); //show form edit customer
    $router->get('/delete-customer/{id}', [CustomerController::class, 'deleteCustomer']); //delete customer by id
    $router->get('/form-add-customer', [CustomerController::class, 'formAddCustomer']); //show form add customer
    $router->post('/add-customer', [CustomerController::class, 'addCustomer']); //add customer
    $router->post('/edit-customer', [CustomerController::class, 'editCustomer']); //post: edit customer

    /*
    |--------------------------------------------------------------------------
    | Admin INVOICE
    |--------------------------------------------------------------------------
    */
    $router->get('/invoices', [InvoiceController::class, 'index']);//get all invoice
    $router->get('/invoice-detail/{id}', [InvoiceController::class, 'invoiceDetail']);//show invoice detail
    $router->get('/form-add-invoice', [InvoiceController::class, 'formAddInvoice']);//show form add invoice
    $router->get('/get-info-customer', [InvoiceController::class, 'getInfoCustomer']);//show customer info by ajax
    $router->get('/export-invoice/{id}', [InvoiceController::class, 'exportInvoice']);//export excel
    $router->get('/download-invoice/{id}', [InvoiceController::class, 'downloadInvoice']);//export excel
    $router->get('/form-edit-invoice/{id}', [InvoiceController::class, 'formEditInvoice']); //show form edit
    $router->get('/delete-invoice/{id}', [InvoiceController::class, 'deleteInvoice']); //delete invoice
    $router->get('/ajax-get-item', [InvoiceController::class, 'getItem']); // get item when select project
    $router->post('/add-invoice', [InvoiceController::class, 'addInvoice']); //add invoice
    $router->post('/edit-invoice', [InvoiceController::class, 'editInvoice']); //update invoice
    $router->post('/update-status', [InvoiceController::class, 'updateStatus']); //update status invoice

    /*
    |--------------------------------------------------------------------------
    | Admin ITEM
    |--------------------------------------------------------------------------
    */
    $router->get('/items', [ItemController::class, 'index']);
    $router->get('/items/{projectId}', [ItemController::class, 'findItemByProjectID']);
    $router->get('/form-add-item', [ItemController::class, 'formAddItem']); //show form add item
    $router->get('/form-edit-item/{id}', [ItemController::class, 'formEditItem']); //show form edit item
    $router->get('/delete-item/{id}', [ItemController::class, 'deleteItem']); //delete item by id
    $router->post('/add-item', [ItemController::class, 'addItem']); //add item
    $router->post('/edit-item', [ItemController::class, 'editItem']); //post: edit item

    /*
    |--------------------------------------------------------------------------
    | Admin ORDER
    |--------------------------------------------------------------------------
    */
    $router->get('/orders', [OrderController::class, 'index']); // show list order
    $router->get('/form-add-order', [EstimateController::class, 'formAddEstimate']); //show form add order
    $router->get('/download-order/{id}', [OrderController::class, 'downloadOrder']); //download file
    $router->get('/form-edit-order/{id}', [OrderController::class, 'formEditOrder']); //show form edit estimate
    $router->get('/delete-order/{id}', [OrderController::class, 'deleteOrder']); //delete estimate by id
    $router->post('/add-order', [OrderController::class, 'addOrder']); // add order and upload file
    $router->post('/edit-order', [OrderController::class, 'editOrder']); // edit estimate

});
