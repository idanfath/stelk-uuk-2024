<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('fetchAllItems', [ItemController::class, 'fetchAllItems']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('info', [UserController::class, 'info']);
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('role', [UserController::class, 'role']);
    Route::post('update', [UserController::class, 'update']);
    Route::put('updatePassword', [UserController::class, 'updatePassword']);
    //deleteUsers
    Route::delete('deleteUsers', [UserController::class, 'deleteUsers']);
    Route::delete('deleteUser', [UserController::class, 'deleteUser']);
    Route::post('updateUser', [UserController::class, 'updateUser']);

    // getallu
    Route::get('fetchAllUsers', [UserController::class, 'fetchAllUsers']);

    Route::post('addItem', [ItemController::class, 'addItem']);
    Route::get('getItems', [ItemController::class, 'getItems']);

    Route::delete('deleteItems', [ItemController::class, 'deleteItems']);
    Route::delete('deleteItem', [ItemController::class, 'deleteItem']);
    Route::post('editItem', [ItemController::class, 'editItem']);
    // getItemsNamePrice
    Route::get('getItemsNamePrice', [ItemController::class, 'getItemsNamePrice']);
    // getCustomersNameNumber
    Route::get('getCustomersNameNumber', [PelangganController::class, 'getCustomersNameNumber']);

    Route::get('fetchAllCustomers', [PelangganController::class, 'fetchAllCustomers']);
    Route::post('addCustomer', [PelangganController::class, 'addCustomer']);
    Route::post('editCustomer', [PelangganController::class, 'editCustomer']);
    Route::post('fetchCustomer', [PelangganController::class, 'fetchCustomer']);
    // del
    Route::delete('deleteCustomer', [PelangganController::class, 'deleteCustomer']);
    Route::delete('deleteCustomers', [PelangganController::class, 'deleteCustomers']);

    // addTrx
    Route::post('addTrx', [TransaksiController::class, 'addTrx']);
    Route::get('getTrxData', [TransaksiController::class, 'getTrxData']);
    Route::delete('deleteTrx', [TransaksiController::class, 'deleteTrx']);
    Route::delete('deleteTrxs', [TransaksiController::class, 'deleteTrxs']);

    // adminEditUser
    Route::post('adminEditUser', [UserController::class, 'adminEditUser']);
});


