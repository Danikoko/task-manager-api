<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;

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

// Start guest-protected routes
Route::middleware('guest')->prefix('auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// End guest-protected routes

Route::middleware('auth:sanctum')->group(function() {
    // Start category management routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'getCategories']);
        Route::get('/{category}', [CategoryController::class, 'getSingleCategory']);
        Route::post('/', [CategoryController::class, 'createCategory']);
        Route::patch('/{category}', [CategoryController::class, 'updateCategory']);
        Route::delete('/{category}', [CategoryController::class, 'deleteCategory']);
    });
    // End category management routes

    // Start task management routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'getTasks']);
        Route::get('/{task}', [TaskController::class, 'getSingleTask']);
        Route::post('/', [TaskController::class, 'createTask']);
        Route::patch('/{task}', [TaskController::class, 'updateTask']);
        Route::patch('/{task}/toggle-task-completion', [TaskController::class, 'toggleTaskCompletion']);
        Route::delete('/{task}', [TaskController::class, 'deleteTask']);
    });
    Route::get('/get-task-report', [TaskController::class, 'getTaskReport']);
    // End task management routes

    // Start profile management routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'getProfile']);
        Route::patch('/', [ProfileController::class, 'updateProfile']);
    });
    // End profile management routes
});
