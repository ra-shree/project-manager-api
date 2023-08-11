<?php

use App\Http\Controllers\AggregateController;
use App\Http\Controllers\ApiAuthenticationController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\RegularControllers\UserController;

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

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'admin'], 'as' => 'admin.'], function () {
    Route::get('/users/managers', [UserController::class, 'indexManager'])->name('users.managers');
    Route::get('/projects/{project}/members', [ProjectController::class, 'findMembers'])->name('projects.members');
    Route::get('/summary/count', [AggregateController::class, 'summary'])->name('summary.count');
    Route::get('/summary/project/{keyword}', [AggregateController::class, 'project'])->name('summary.project');
    Route::get('/summary/task/{keyword}', [AggregateController::class, 'tasks'])->name('summary.tasks');
    Route::apiResources([
        'users' => UserController::class,
        'projects' => ProjectController::class,
        'tasks' => TaskController::class,
    ]);
    Route::apiResource('/members', ProjectMemberController::class)->except('show');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum'], 'as' => 'user.'], function () {
    Route::get('/projects/{project}/members', [ProjectController::class, 'findMembers'])->name('projects.members');
    Route::get('/summary/count', [AggregateController::class, 'userSummary'])->name('summary.count');
    Route::get('/summary/projects', [AggregateController::class, 'userProjects'])->name('summary.projects');
    Route::get('/summary/tasks', [AggregateController::class, 'userTasks'])->name('summary.tasks');
    Route::delete('/members/{project_id}/remove/{user_id}', [ProjectMemberController::class, 'removeDeveloper'])->name('members.remove');
    Route::apiResource('/projects', ProjectController::class)->only('index', 'show', 'update');
    Route::apiResources([
        'tasks' => TaskController::class,
        'members' => ProjectMemberController::class,
    ]);
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [ApiAuthenticationController::class, 'destroy']);
});

Route::post('/login', [ApiAuthenticationController::class, 'store']);
