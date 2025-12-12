<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OffresController;
use App\Http\Controllers\Api\TypeOffresController;
use App\Http\Controllers\Api\EntreprisesController;
use App\Http\Controllers\Api\FlashsController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\RecruiterController;
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




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/offres', [OffresController::class, 'index']);
Route::get('/offres/{id}', [OffresController::class, 'show']);

Route::get('/flashers', [FlashsController::class, 'index']);
Route::get('/flashers/{id}', [FlashsController::class, 'show']);

Route::get('/entreprises', [EntreprisesController::class, 'index']);
Route::get('/entreprises/{id}', [EntreprisesController::class, 'show']);

Route::get('/typeoffres', [TypeOffresController::class, 'index']);
Route::get('/typeoffres/{id}', [TypeOffresController::class, 'show']);

Route::post('/candidates', [CandidateController::class, 'store']);
Route::post('/recruiters', [RecruiterController::class, 'store']);
