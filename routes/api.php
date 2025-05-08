<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\chartController;
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

// Authentication Route
Route::middleware(['auth', 'json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });
    });
    Route::prefix('product')->group(function () {
        Route::middleware('can:product-items')->group(function () {
            Route::get('items', [ProductController::class, 'get'])->withoutMiddleware('can:product-items');
            Route::post('items', [ProductController::class, 'index']);
            Route::post('items/store', [ProductController::class, 'store']);
            Route::apiResource('product', ProductController::class)
                ->except(['index', 'store']);
            Route::get('item/{id}', [ProductController::class, 'first']);
        });

        Route::middleware('can:product-categories')->group(function () {
            Route::get('category', [CategoryController::class, 'get'])->withoutMiddleware('can:product-categories');
            Route::post('category', [CategoryController::class, 'index']);
            Route::post('category/store', [CategoryController::class, 'store']);
            Route::apiResource('category', CategoryController::class)
                ->except(['index', 'store']);
        });
    });

    Route::middleware('can:transaction')->group(function () {
        Route::get('transaction', [TransactionController::class, 'get'])->withoutMiddleware('can:transaction');
        Route::post('transaction', [TransactionController::class, 'index']);
        Route::post('transaction/store', [TransactionController::class, 'store']);
        Route::get('transaction/detail/{id_transaksi}', [TransactionController::class, 'show']);
        Route::get('transaction/view/pdf', [TransactionController::class, 'view_pdf']);
        Route::apiResource('transaction', TransactionController::class)
            ->except(['index', 'store']);
    });
});
Route::post('xendit/invoice', [TransactionController::class, 'invoice']);
Route::post('xendit/store', [TransactionController::class, 'createXendit']);
Route::post('xendit/struk', [TransactionController::class, 'generateReceipt']);
Route::post('xendit/struk/cash', [TransactionController::class, 'generateReceiptCash']);
Route::get('/detail-transaction/{code}', [TransactionController::class, 'byCode']);

Route::get('chart-js', [chartController::class, 'getData']);
