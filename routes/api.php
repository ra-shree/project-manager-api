<?php

use App\Http\Controllers\AggregateController;
use App\Http\Controllers\ApiAuthenticationController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
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
    Route::get('/projects/{project}/members', [ProjectController::class, 'findMembers'])->name('projects.members');

    Route::group(['prefix' => 'summary', 'as' => 'summary.'], function () {
        Route::get('/project', [AggregateController::class, 'project'])->name('project');
        Route::get('/task', [AggregateController::class, 'tasks'])->name('tasks');
        Route::get('/', [AggregateController::class, 'summary'])->name('count');
    });

    Route::apiResources([
        'users' => UserController::class,
        'projects' => ProjectController::class,
        'tasks' => TaskController::class,
    ]);
    Route::apiResource('/members', ProjectMemberController::class)->except('show');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum'], 'as' => 'user.'], function () {
    Route::get('/projects/{project}/members', [ProjectController::class, 'findMembers'])->name('projects.members');

    Route::group(['prefix' => 'summary', 'as' => 'summary.'], function () {
        Route::get('/count', [AggregateController::class, 'userSummary'])->name('count');
        Route::get('/projects', [AggregateController::class, 'userProjects'])->name('projects');
        Route::get('/tasks', [AggregateController::class, 'userTasks'])->name('tasks');
    });

    Route::delete('/members/{project_id}/remove/{user_id}', [ProjectMemberController::class, 'removeDeveloper'])->name('members.remove');
    Route::apiResource('projects', ProjectController::class)->only('index', 'show', 'update');
    Route::apiResources([
        'tasks' => TaskController::class,
        'members' => ProjectMemberController::class,
    ]);
});

Route::post('/login', [ApiAuthenticationController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [ApiAuthenticationController::class, 'destroy']);
});

