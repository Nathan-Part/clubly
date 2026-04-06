<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public routes for consultation
Route::get('nouvellepage', 'App\Http\Controllers\MonControleur@retourneNouvellePage');
Route::get('exemple', 'App\Http\Controllers\MonControleur@retournePageExemple');
Route::get('membres', 'App\Http\Controllers\ControleurMembres@index');
Route::get('membrescss', 'App\Http\Controllers\ControleurMembres@indexCss');
Route::get('membre/{numero}', 'App\Http\Controllers\ControleurMembres@afficher');

// Routes requiring authentication and validation
Route::middleware(['auth', 'validated'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('creer', 'App\Http\Controllers\ControleurMembres@creer');
    Route::post('enregistrer', 'App\Http\Controllers\ControleurMembres@enregistrer');
    Route::get('modifier/{numero}', 'App\Http\Controllers\ControleurMembres@editer');
    Route::post('miseAJour/{numero}', 'App\Http\Controllers\ControleurMembres@miseAJour');
    Route::get('identite', 'App\Http\Controllers\ControleurMembres@identite');
});

// Admin validation routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/validations', [\App\Http\Controllers\AdminController::class, 'validations'])->name('admin.validations');
    Route::post('/admin/valider/{id}', [\App\Http\Controllers\AdminController::class, 'valider'])->name('admin.valider');
});

Route::get('/protege','App\Http\Controllers\ControleurMembres@acces_protege');

require __DIR__.'/auth.php';

Route::get('/pending-validation', function () {
    return view('auth.pending-validation');
})->middleware('auth')->name('pending-validation');
