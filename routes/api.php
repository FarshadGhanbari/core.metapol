<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\EssentialsController;
use App\Http\Controllers\Dashboard\FilemanagersController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\UsersController;
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

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('sendCode', [AuthController::class, 'sendCode']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    //
});


Route::prefix('dashboard')->name('dashboard.')->middleware(['auth:api'])->group(function () {
    // Essentials API
    Route::post('essentials-roles-list', [EssentialsController::class, 'rolesList']);
    Route::post('essentials-permissions-list', [EssentialsController::class, 'permissionsList']);
    // Filemanagers API
    Route::post('filemanagers', [FilemanagersController::class, 'index']);
    Route::post('filemanagers-folder', [FilemanagersController::class, 'folder']);
    Route::post('filemanagers-folder-create', [FilemanagersController::class, 'folderCreate']);
    Route::delete('filemanagers-folder-delete', [FilemanagersController::class, 'folderDelete']);
    Route::post('filemanagers-file-upload/{folder_id}', [FilemanagersController::class, 'fileUpload']);
    Route::delete('filemanagers-file-delete', [FilemanagersController::class, 'fileDelete']);
    // Roles API
    Route::post('roles-list', [RolesController::class, 'index']);
    Route::put('roles-store', [RolesController::class, 'store']);
    Route::post('roles-edit', [RolesController::class, 'edit']);
    Route::patch('roles-update', [RolesController::class, 'update']);
    Route::delete('roles-delete', [RolesController::class, 'delete']);
    Route::patch('roles-enable', [RolesController::class, 'enable']);
    Route::patch('roles-disable', [RolesController::class, 'disable']);
    Route::delete('roles-selected-delete', [RolesController::class, 'selectedDelete']);
    Route::delete('roles-selected-enable', [RolesController::class, 'selectedEnable']);
    Route::delete('roles-selected-disable', [RolesController::class, 'selectedDisable']);
    // Users API
    Route::post('users-list', [UsersController::class, 'index']);
    Route::put('users-store', [UsersController::class, 'store']);
    Route::post('users-edit', [UsersController::class, 'edit']);
    Route::patch('users-update', [UsersController::class, 'update']);
    Route::delete('users-delete', [UsersController::class, 'delete']);
    Route::patch('users-enable', [UsersController::class, 'enable']);
    Route::patch('users-disable', [UsersController::class, 'disable']);
    Route::delete('users-selected-delete', [UsersController::class, 'selectedDelete']);
    Route::delete('users-selected-enable', [UsersController::class, 'selectedEnable']);
    Route::delete('users-selected-disable', [UsersController::class, 'selectedDisable']);
});
