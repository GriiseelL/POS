<?php

use AmrShawky\LaravelCurrency\Facade\Currency;
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

// Route::get('/', function () {
//     return view('app');
// });

use App\Http\Controllers\TransactionController;

Route::get('transaction/download', [TransactionController::class, 'download_pdf']);
Route::get('transactions', [TransactionController::class, 'indexpdf']);


Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api\/)[\/\w\.-]*');