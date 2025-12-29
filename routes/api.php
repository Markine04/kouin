<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OffresController;
use App\Http\Controllers\Api\TypeOffresController;
use App\Http\Controllers\Api\EntreprisesController;
use App\Http\Controllers\Api\FlashsController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\DashboardAppController;
use App\Http\Controllers\Api\RecruiterController;
use App\Http\Controllers\Api\NiveauEtudesController;
use App\Http\Controllers\Api\SecteurActivitesController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/annonces', [OffresController::class, 'store']);
    Route::post('/flash-annonces', [FlashsController::class, 'store']);
    Route::get('/dashboard-app', [DashboardAppController::class, 'index']);
    Route::get('/lists-offres', [DashboardAppController::class, 'lists_offre']);

    Route::get('/lists-flash-annonces', [DashboardAppController::class, 'lists_flash']);
    Route::get('/lists-postulants', [DashboardAppController::class, 'lists_postulant']);
    Route::post('/dashboard-annonces/{id}', [DashboardAppController::class, 'destroy']);
    Route::get('/dashboard-offres/{id}', [OffresController::class, 'show']);
});

Route::get('/offres', [OffresController::class, 'index']);
Route::get('/offres/{id}', [OffresController::class, 'show']);

Route::get('/flashers', [FlashsController::class, 'index']);
Route::get('/flashers/{id}', [FlashsController::class, 'show']);

Route::get('/entreprises', [EntreprisesController::class, 'index']);
Route::get('/entreprises/{id}', [EntreprisesController::class, 'show']);

Route::get('/typeoffres', [TypeOffresController::class, 'index']);
Route::get('/typeoffres/{id}', [TypeOffresController::class, 'show']);

Route::get('/secteuractivites', [SecteurActivitesController::class, 'index']);
Route::get('/secteuractivites/{id}', [SecteurActivitesController::class, 'show']);

Route::post('/candidates', [CandidateController::class, 'store']);
Route::post('/recruiters', [RecruiterController::class, 'store']);


Route::get('/niveaux-etudes', [NiveauEtudesController::class, 'index']);
