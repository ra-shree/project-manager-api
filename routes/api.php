<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\EnsureUserIsAdmin;
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

Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/users', [RegisteredUserController::class, 'index'])->name('users');
        Route::post('/users/create',[RegisteredUserController::class, 'store'])->name('users.create');
        Route::delete('/users/delete/{id}', [RegisteredUserController::class, 'destroy'])->name('users.delete');
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('/projects/create', [ProjectController::class, 'store'])->name('projects.create');
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
//Route::get('admin/users/{id}', [RegisteredUserController::class, 'find']);

//Route::post('/users/create',[RegisteredUserController::class, 'store'])->name('users.create');
//Route::get('admin/users', [RegisteredUserController::class, 'index'])->name('users');
