<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContenuController;
use App\Http\Controllers\TypeOffreController;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\ValidationsController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\EntreprisesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CandidaturesController;
use App\Http\Controllers\AnneeExperiencesController;
use App\Http\Controllers\AnnonceFlashsController;
use App\Http\Controllers\CvthequesController;
use App\Http\Controllers\LevelStudentController;
use App\Http\Controllers\PostulerController;
use App\Http\Controllers\PlanAbonnementController;
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

Route::get('/', [SitesController::class, 'index'])->name('index');




Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('admin/contenus', [ContenuController::class, 'index'])->name('contenu.index');
    Route::get('admin/contenus-show/{id}', [ContenuController::class, 'show'])->name('contenu.show');
    Route::get('admin/contenu-create', [ContenuController::class, 'create'])->name('contenu.create');
    Route::get('admin/contenu-edit/{id}', [ContenuController::class, 'edit'])->name('contenu.edit');
    Route::post('admin/contenu-store', [ContenuController::class, 'store'])->name('contenu.store');
    Route::post('admin/contenu-update/{id}', [ContenuController::class, 'update'])->name('contenu.update');
    Route::post('admin/contenu-delete/{id}', [ContenuController::class, 'delete'])->name('contenu.delete');

    Route::get('admin/prolongations/{id}', [ContenuController::class, 'prolongers'])->name('prolongation.index');
    Route::post('admin/prolongations', [ContenuController::class, 'prolongers_store'])->name('prolongation.store');

    // TypeOffreController
    Route::get('admin/type-offres', [TypeOffreController::class, 'index'])->name('typeOffre.index');
    Route::get('admin/type-offre-create', [TypeOffreController::class, 'create'])->name('typeOffre.create');
    Route::get('admin/type-offre-edit/{id}', [TypeOffreController::class, 'edit'])->name('typeOffre.edit');
    Route::post('admin/type-offre-store', [TypeOffreController::class, 'store'])->name('typeOffre.store');
    Route::post('admin/type-offre-update/{id}', [TypeOffreController::class, 'update'])->name('typeOffre.update');
    Route::post('admin/type-offre-delete/{id}', [TypeOffreController::class, 'delete'])->name('typeOffre.delete');


    // AnneeExperiencesController
    Route::get('admin/year-experience', [AnneeExperiencesController::class, 'index'])->name('yearsExp.index');
    Route::get('admin/year-experience-create', [AnneeExperiencesController::class, 'create'])->name('yearsExp.create');
    Route::get('admin/year-experience-edit/{id}', [AnneeExperiencesController::class, 'edit'])->name('yearsExp.edit');
    Route::post('admin/year-experience-store', [AnneeExperiencesController::class, 'store'])->name('yearsExp.store');
    Route::post('admin/year-experience-update/{id}', [AnneeExperiencesController::class, 'update'])->name('yearsExp.update');
    Route::post('admin/year-experience-delete/{id}', [AnneeExperiencesController::class, 'delete'])->name('yearsExp.delete');

    // FormationsController
    Route::get('admin/formations', [FormationsController::class, 'index'])->name('formations.index');
    Route::get('admin/formation-create', [FormationsController::class, 'create'])->name('formations.create');
    Route::get('admin/formation-edit/{id}', [FormationsController::class, 'edit'])->name('formations.edit');
    Route::post('admin/formation-store', [FormationsController::class, 'store'])->name('formations.store');
    Route::post('admin/formation-update/{id}', [FormationsController::class, 'update'])->name('formations.update');
    Route::post('admin/formation-delete/{id}', [FormationsController::class, 'delete'])->name('formations.delete');


    // ValidationsController
    Route::get('admin/validations', [ValidationsController::class, 'index'])->name('validations.index');
    Route::get('admin/validations-create', [ValidationsController::class, 'create'])->name('validations.create');
    Route::get('admin/validations-edit/{id}', [ValidationsController::class, 'edit'])->name('validations.edit');
    Route::post('admin/validations-store', [ValidationsController::class, 'store'])->name('validations.store');
    Route::post('admin/validations-update/{id}', [ValidationsController::class, 'update'])->name('validations.update');
    Route::post('admin/validations-delete/{id}', [ValidationsController::class, 'delete'])->name('validations.delete');
    Route::get('admin/validations-valide/{id}', [ValidationsController::class, 'validation'])->name('validations.valide');


    // PlanAbonnementController
    Route::get('admin/plan-abonnement', [PlanAbonnementController::class, 'index'])->name('plan-abonnement.index');
    Route::get('admin/plan-abonnement-create', [PlanAbonnementController::class, 'create'])->name('plan-abonnement.create');
    Route::get('admin/plan-abonnement-edit/{id}', [PlanAbonnementController::class, 'edit'])->name('plan-abonnement.edit');
    Route::post('admin/plan-abonnement-store', [PlanAbonnementController::class, 'store'])->name('plan-abonnement.store');
    Route::post('admin/plan-abonnement-update/{id}', [PlanAbonnementController::class, 'update'])->name('plan-abonnement.update');
    Route::post('admin/plan-abonnement-delete/{id}', [PlanAbonnementController::class, 'delete'])->name('plan-abonnement.delete');
    Route::get('admin/plan-abonnement-valide/{id}', [PlanAbonnementController::class, 'validation'])->name('plan-abonnement.valide');


    Route::get('admin/periodes', [PlanAbonnementController::class, 'index_periodes'])->name('periodes.index');
    Route::get('admin/periodes-create', [PlanAbonnementController::class, 'create_periodes'])->name('periodes.create');
    Route::get('admin/periodes-edit/{id}', [PlanAbonnementController::class, 'edit_periodes'])->name('periodes.edit');
    Route::post('admin/periodes-store', [PlanAbonnementController::class, 'store_periodes'])->name('periodes.store');
    Route::post('admin/periodes-update/{id}', [PlanAbonnementController::class, 'update_periodes'])->name('periodes.update');
    Route::post('admin/periodes-delete/{id}', [PlanAbonnementController::class, 'delete_periodes'])->name('periodes.delete');


    Route::get('entreprises', [EntreprisesController::class, 'index'])->name('entreprises.index');
    Route::get('entreprise/{id}', [EntreprisesController::class, 'show'])->name('entreprises.show');


    Route::get('candidatures', [CandidaturesController::class, 'index'])->name('candidatures.index');
    Route::get('candidatures-create', [CandidaturesController::class, 'create'])->name('candidatures.create');
    Route::get('candidatures-show/{id}', [CandidaturesController::class, 'show'])->name('candidatures.show');
    Route::post('candidatures-show', [CandidaturesController::class, 'profil_store'])->name('profil.store');


    Route::get('skill-candidat/{id}', [CandidaturesController::class, 'add_skill'])->name('add-skill');
    Route::post('skill-candidat', [CandidaturesController::class, 'store_skill'])->name('store-skill');
    Route::get('skill-candidat-delete/{id}', [CandidaturesController::class, 'skill_delete'])->name('skill.delete');
    Route::post('skill-candidat-delete', [CandidaturesController::class, 'skill_destroy'])->name('skill.destroy');


    Route::get('experience-candidat/{id}', [CandidaturesController::class, 'add_experience'])->name('add-experience');
    Route::post('experience-candidat', [CandidaturesController::class, 'store_experience'])->name('store-experience');
    Route::get('experience-candidat-delete/{id}', [CandidaturesController::class, 'delete_experience'])->name('experience.delete');
    Route::post('experience-candidat-delete', [CandidaturesController::class, 'destroy_experience'])->name('experience.destroy');

    Route::get('formation-candidat/{id}', [CandidaturesController::class, 'add_formation'])->name('add-formation');
    Route::post('formation-candidat', [CandidaturesController::class, 'store_formation'])->name('store-formation');
    Route::get('formation-candidat-delete/{id}', [CandidaturesController::class, 'delete_formation'])->name('formation.delete');
    Route::post('formation-candidat-delete', [CandidaturesController::class, 'destroy_formation'])->name('formation.destroy');


    // CvthequesController
    Route::get('cvtheque', [CvthequesController::class, 'index'])->name('cvtheque.index');
    Route::get('cvtheque-create', [CvthequesController::class, 'create'])->name('cvtheque.create');
    Route::post('cvtheque-store', [CvthequesController::class, 'store'])->name('cvtheque.store');
    Route::get('cvtheque-show/{id}', [CvthequesController::class, 'show'])->name('cvtheque.show');
    Route::get('cvtheque-delete/{id}', [CvthequesController::class, 'delete'])->name('cvtheque.delete');



    //settingcontroller
    Route::get('admin/setting-client', [SettingsController::class, 'index'])->name('settings.clients');
    Route::post('admin/setting-client-store', [SettingsController::class, 'client'])->name('settings.clients-store');



    // PlanAbonnementController
    Route::get('admin/niveau-etudes', [LevelStudentController::class, 'index'])->name('niveau-etudes.index');
    Route::get('admin/niveau-etudes-create', [LevelStudentController::class, 'create'])->name('niveau-etudes.create');
    Route::get('admin/niveau-etudes-edit/{id}', [LevelStudentController::class, 'edit'])->name('niveau-etudes.edit');
    Route::post('admin/niveau-etudes-store', [LevelStudentController::class, 'store'])->name('niveau-etudes.store');
    Route::post('admin/niveau-etudes-update/{id}', [LevelStudentController::class, 'update'])->name('niveau-etudes.update');
    Route::post('admin/niveau-etudes-delete/{id}', [LevelStudentController::class, 'delete'])->name('niveau-etudes.delete');
    Route::get('admin/niveau-etudes-valide/{id}', [LevelStudentController::class, 'validation'])->name('niveau-etudes.valide');


    Route::get('admin/annonce-flashs', [AnnonceFlashsController::class, 'index'])->name('annonceFlash.index');
    Route::get('admin/annonce-flash-create', [AnnonceFlashsController::class, 'create'])->name('annonceFlash.create');
    Route::post('admin/annonce-flash', [AnnonceFlashsController::class, 'store'])->name('annonceFlash.store');
    Route::get('admin/annonce-flash/{id}/edit', [AnnonceFlashsController::class, 'edit'])->name('annonceFlash.edit');
    Route::post('admin/annonce-flash-update', [AnnonceFlashsController::class, 'update'])->name('annonceFlash.update');
    Route::get('admin/annonce-flash/{id}/delete', [AnnonceFlashsController::class, 'delete'])->name('annonceFlash.delete');
    Route::post('admin/annonce-flash-destroy', [AnnonceFlashsController::class, 'destroy'])->name('annonceFlash.destroy');

});

Route::get('compte-entreprise', [EntreprisesController::class, 'particulier'])->name('particulier');
Route::post('entreprise-store', [EntreprisesController::class, 'particulier_store'])->name('particulier.store');


//JobsController
Route::get('jobs-listing', [JobsController::class, 'index'])->name('jobs.index');
Route::get('jobs-details/{id}', [JobsController::class, 'detail'])->name('jobs-detail.index');
Route::get('jobs-listing-show/{id}', [JobsController::class, 'show'])->name('jobs.show');
Route::get('jobs-create', [JobsController::class, 'create'])->name('jobs.create');
Route::get('jobs-edit/{id}', [JobsController::class, 'edit'])->name('jobs.edit');
Route::post('jobs-store', [JobsController::class, 'store'])->name('jobs.store');
Route::post('jobs-update/{id}', [JobsController::class, 'update'])->name('jobs.update');
Route::post('jobs-delete/{id}', [JobsController::class, 'delete'])->name('jobs.delete');
Route::get('jobs-valide/{id}', [JobsController::class, 'validation'])->name('jobs.valide');


// PostulerController
Route::post('postuler', [PostulerController::class, 'index'])->name('postuler.index');
// Route::get('cvtheque-create',[PostulerController::class, 'create'])->name('cvtheque.create');
// Route::post('cvtheque-store',[PostulerController::class, 'store'])->name('cvtheque.store');
// Route::get('cvtheque-show/{id}',[PostulerController::class, 'show'])->name('cvtheque.show');
// Route::get('cvtheque-delete/{id}',[PostulerController::class, 'delete'])->name('cvtheque.delete');



require __DIR__ . '/auth.php';
