<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;


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
Route::get('/flight/departure/domestik', [ChartController::class, 'departureDomestik']);
Route::get('/flight/departure/internasional', [ChartController::class, 'departureInternasional']);
Route::get('/flight/arrival/domestik', [ChartController::class, 'arrivalDomestik']);
Route::get('/flight/arrival/internasional', [ChartController::class, 'arrivalInternasional']);

Route::get('/flight/add/domestik', [ChartController::class, 'addDataDomestik']);
Route::get('/flight/add/internasional', [ChartController::class, 'addDataInternasional']);
Route::post('/flight/insert', [ChartController::class, 'insert']);
Route::get('/flight/data/search', [ChartController::class, 'search']);
Route::get('/flight/data/arrival/search', [ChartController::class, 'searchArrival']);
//Route::get('/flight/desk', [ChartController::class, 'desk']);
Route::get('/flight/data/counter', [ChartController::class, 'counter']);
Route::get('/flight/data/modal', [ChartController::class, 'modal']);
Route::get('/flight/data/departure/modal', [ChartController::class, 'modal']);
Route::get('/flight/data/arrival/modal', [ChartController::class, 'modalArrival']);
Route::get('/flight/data/domestik', [ChartController::class, 'flightDataDomestik']);
Route::get('/flight/data/internasional', [ChartController::class, 'flightDataInternasional']);

Route::get('/flight/data/arrival/domestik', [ChartController::class, 'arrivalDataDomestik']);
Route::get('/flight/data/arrival/internasional', [ChartController::class, 'arrivalDataInternasional']);
Route::get('/flight/data/internasional/desk', [ChartController::class, 'desk']);
Route::post('/flight/add/import', [ChartController::class, 'import']);
Route::post('/flight/add/export', [ChartController::class, 'export']);