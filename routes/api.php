<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\EssentialsController;
use App\Http\Controllers\Dashboard\FilemanagersController;
use App\Http\Controllers\Dashboard\CountriesController;
use App\Http\Controllers\Dashboard\ProvincesController;
use App\Http\Controllers\Dashboard\CitiesController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\StaffsController;
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

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth:api'])->group(function () {
    // Essentials API
    Route::post('essentials-roles-list', [EssentialsController::class, 'rolesList']);
    Route::post('essentials-permissions-list', [EssentialsController::class, 'permissionsList']);
    Route::post('essentials-countries-list', [EssentialsController::class, 'countriesList']);
    Route::post('essentials-provinces-list', [EssentialsController::class, 'provincesList']);
    // Filemanagers API
    Route::post('filemanagers', [FilemanagersController::class, 'index']);
    Route::post('filemanagers-folder', [FilemanagersController::class, 'folder']);
    Route::post('filemanagers-folder-create', [FilemanagersController::class, 'folderCreate']);
    Route::delete('filemanagers-folder-delete', [FilemanagersController::class, 'folderDelete']);
    Route::post('filemanagers-file-upload/{folder_id}', [FilemanagersController::class, 'fileUpload']);
    Route::delete('filemanagers-file-delete', [FilemanagersController::class, 'fileDelete']);
    // Countries API
    Route::post('countries-list', [CountriesController::class, 'index']);
    Route::put('countries-store', [CountriesController::class, 'store']);
    Route::post('countries-edit', [CountriesController::class, 'edit']);
    Route::patch('countries-update', [CountriesController::class, 'update']);
    Route::delete('countries-delete', [CountriesController::class, 'delete']);
    Route::patch('countries-enable', [CountriesController::class, 'enable']);
    Route::patch('countries-disable', [CountriesController::class, 'disable']);
    Route::delete('countries-selected-delete', [CountriesController::class, 'selectedDelete']);
    Route::delete('countries-selected-enable', [CountriesController::class, 'selectedEnable']);
    Route::delete('countries-selected-disable', [CountriesController::class, 'selectedDisable']);
    // Provinces API
    Route::post('provinces-list', [ProvincesController::class, 'index']);
    Route::put('provinces-store', [ProvincesController::class, 'store']);
    Route::post('provinces-edit', [ProvincesController::class, 'edit']);
    Route::patch('provinces-update', [ProvincesController::class, 'update']);
    Route::delete('provinces-delete', [ProvincesController::class, 'delete']);
    Route::patch('provinces-enable', [ProvincesController::class, 'enable']);
    Route::patch('provinces-disable', [ProvincesController::class, 'disable']);
    Route::delete('provinces-selected-delete', [ProvincesController::class, 'selectedDelete']);
    Route::delete('provinces-selected-enable', [ProvincesController::class, 'selectedEnable']);
    Route::delete('provinces-selected-disable', [ProvincesController::class, 'selectedDisable']);
    // Cities API
    Route::post('cities-list', [CitiesController::class, 'index']);
    Route::put('cities-store', [CitiesController::class, 'store']);
    Route::post('cities-edit', [CitiesController::class, 'edit']);
    Route::patch('cities-update', [CitiesController::class, 'update']);
    Route::delete('cities-delete', [CitiesController::class, 'delete']);
    Route::patch('cities-enable', [CitiesController::class, 'enable']);
    Route::patch('cities-disable', [CitiesController::class, 'disable']);
    Route::delete('cities-selected-delete', [CitiesController::class, 'selectedDelete']);
    Route::delete('cities-selected-enable', [CitiesController::class, 'selectedEnable']);
    Route::delete('cities-selected-disable', [CitiesController::class, 'selectedDisable']);
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
    // Staffs API
    Route::post('staffs-list', [StaffsController::class, 'index']);
    Route::put('staffs-store', [StaffsController::class, 'store']);
    Route::post('staffs-edit', [StaffsController::class, 'edit']);
    Route::patch('staffs-update', [StaffsController::class, 'update']);
    Route::delete('staffs-delete', [StaffsController::class, 'delete']);
    Route::patch('staffs-enable', [StaffsController::class, 'enable']);
    Route::patch('staffs-disable', [StaffsController::class, 'disable']);
    Route::delete('staffs-selected-delete', [StaffsController::class, 'selectedDelete']);
    Route::delete('staffs-selected-enable', [StaffsController::class, 'selectedEnable']);
    Route::delete('staffs-selected-disable', [StaffsController::class, 'selectedDisable']);
});
