<?php

use App\Http\Controllers\ApiAuthenticationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/create',[RegisteredUserController::class, 'store'])->name('users.create');
        Route::put('/users/update/{user_id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/delete/{user_id}', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('/users/{user_role}', [UserController::class, 'findByRole'])->name('users.role');
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('/projects/create', [ProjectController::class, 'store'])->name('projects.create');
        Route::put('/projects/update/{project_id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/delete/{project_id}', [ProjectController::class, 'destroy'])->name('projects.delete');
    });


Route::prefix('user')
    ->name('user.')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/users/{role}', [UserController::class, 'findByRole'])->name('users.developer');
        Route::get('/projects', [ProjectController::class, 'projectViaRole'])->name('manager.projects');
        Route::get('/projects/{project_id}', [ProjectController::class, 'show'])->name('project.show');
        Route::patch('/projects/status/{project_id}', [ProjectController::class, 'updateStatus'])->name('project.update');
        Route::get('/projects/{project_id}/members', [ProjectController::class, 'findMembers'])->name('projects.members');
        Route::get('/projects/{project_id}/add', [ProjectMemberController::class, 'getDeveloper'])->name('project.members.get');
        Route::post('/projects/{project_id}/add', [ProjectMemberController::class, 'addDeveloper'])->name('project.members.post');
        Route::delete('/projects/{project_id}/remove/{user_id}', [ProjectMemberController::class, 'removeDeveloper'])->name('project.members.delete');
        Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('task.create');
        Route::put('/tasks/update/{task_id}', [TaskController::class, 'update'])->name('task.update');
        Route::patch('/tasks/status/{task_id}', [TaskController::class, 'completed'])->name('task.completed');
        Route::delete('/tasks/delete/{task_id}', [TaskController::class, 'destroy'])->name('task.delete');
    });

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/logout',[ApiAuthenticationController::class, 'destroy'])->name('logout');
    });

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [ApiAuthenticationController::class, 'destroy']);
});

Route::post('/login', [ApiAuthenticationController::class, 'store']);
