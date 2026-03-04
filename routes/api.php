<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\FlashsController;
use App\Http\Controllers\Api\OffresController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\RecruiterController;
use App\Http\Controllers\Api\PaysVillesController;
use App\Http\Controllers\Api\TypeOffresController;
use App\Http\Controllers\Api\EntreprisesController;
use App\Http\Controllers\Api\DashboardAppController;
use App\Http\Controllers\Api\NiveauEtudesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SecteurActivitesController;
use App\Http\Controllers\Api\RecruiterRegisterController;
use App\Http\Controllers\Api\UserBiimController;
use App\Http\Controllers\Api\ReservationsController;
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
    Route::get('/lists-offres-attentes', [DashboardAppController::class, 'lists_offre_attente']);

    Route::get('/lists-flash-annonces', [DashboardAppController::class, 'lists_flash']);
    Route::get('/lists-postulants', [DashboardAppController::class, 'lists_postulant']);
    Route::post('/dashboard-annonces/{id}', [DashboardAppController::class, 'destroy']);
    Route::get('/dashboard-offres/{id}', [DashboardAppController::class, 'show']);
    Route::get('/dashboard-flash/{id}', [DashboardAppController::class, 'show_flash']);
    Route::get('/dashboard-postulants/{id}', [DashboardAppController::class, 'show_postulant']);

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto']);

    // 📥 Liste des notifications
    Route::get('/notifications', [NotificationController::class, 'index']);

    // 🔔 Compteur non-lu (badge)
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

    // 👁 Marquer comme lue
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // 📩 Créer notification (admin / système)
    Route::post('/notifications-store', [NotificationController::class, 'store']);

    Route::get('/profile/aboutmes/{id}', [ProfileController::class, 'getAboutMe']);
    Route::post('/profile/aboutme', [ProfileController::class, 'saveAboutMe']);
    Route::put('/profile/aboutme-update/{id}', [ProfileController::class, 'saveAboutMeUpdate']);

    Route::get('/profile/skills/{id}', [ProfileController::class, 'getSkills']);
    Route::post('/profile/skill', [ProfileController::class, 'saveSkill']);
    Route::post('/profile/skill/{id}', [ProfileController::class, 'saveSkillUpdate']);


    Route::get('/profile/experiences/{id}', [ProfileController::class, 'getExperiences']);
    Route::post('/profile/experience', [ProfileController::class, 'saveExperience']);
    Route::put('/profile/experience-update/{id}', [ProfileController::class, 'saveExperienceUpdate']);
    Route::post('/profile/experience-delete/{id}', [ProfileController::class, 'ExperiencesDelete']);


    Route::get('/profile/educations/{id}', [ProfileController::class, 'getEducations']);
    Route::post('/profile/education', [ProfileController::class, 'saveEducation']);
    Route::put('/profile/education-update/{id}', [ProfileController::class, 'saveEducationUpdate']);
    Route::post('/profile/education-delete/{id}', [ProfileController::class, 'EducationsDelete']);



    Route::post('/upload-cv', [ProfileController::class, 'uploadCV']);
    Route::post('/upload-cv/{id}', [ProfileController::class, 'uploadCVUpdate']);

    Route::post('/candidature/postuler', [CandidateController::class, 'postuler']);
});

Route::post('/recruiter/register/step1', [RecruiterRegisterController::class, 'step1']);
Route::post('/recruiter/register/step2', [RecruiterRegisterController::class, 'step2']);
Route::post('/recruiter/register/step3', [RecruiterRegisterController::class, 'step3']);
Route::post('/recruiter/register/step4', [RecruiterRegisterController::class, 'step4']);

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
Route::get('/pays-villes', [PaysVillesController::class, 'index']);


Route::post('/login', [UsersController::class, 'login']);









Route::post('/wp-register', [UserBiimController::class, 'registerToWordpress']);
Route::post('/wp-login', [UserBiimController::class, 'loginToWordpress']);
Route::post('/wp-login-and-register', [UserBiimController::class, 'registerAndLogin']);

Route::get('/property', [PropertyController::class, 'index']);
Route::get('/property/{id}', [PropertyController::class, 'show']);
Route::post('/add-property', [PropertyController::class, 'store']);
Route::get('/home', [PropertyController::class, 'home']);

Route::get('/eltproperty', [PropertyController::class, 'eltinsert']);
Route::get('/property-similaires', [PropertyController::class, 'similaires']);


Route::post('/save-reservation', [ReservationsController::class, 'store']);

Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('/my-properties/{user}', [UserBiimController::class, 'myProperties']);
    }
);
