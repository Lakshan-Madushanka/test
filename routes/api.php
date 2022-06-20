<?php

use App\Domains\Calender\Controllers\CalenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//calender
Route::prefix('calender/')->name('calender.')->group(function () {
    Route::get('holidays', [CalenderController::class, 'retrieveRegionHolidays'])
    ->name('retrieveRegionHolidays');
});
