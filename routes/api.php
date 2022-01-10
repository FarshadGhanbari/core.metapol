<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\FilemanagerController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

    Route::post('filemanager', [FilemanagerController::class, 'index']);
    Route::post('filemanager-folder', [FilemanagerController::class, 'folder']);
    Route::post('filemanager-folder-create', [FilemanagerController::class, 'folderCreate']);
    Route::post('filemanager-folder-delete', [FilemanagerController::class, 'folderDelete']);
    Route::post('filemanager-file-upload/{folder_id}', [FilemanagerController::class, 'fileUpload']);
    Route::post('filemanager-file-delete', [FilemanagerController::class, 'fileDelete']);

    Route::post('users', [UserController::class, 'index']);

    Route::get('/statistics', function (Request $request) {
        return response()->json(\App\Models\Shared\Statistic::search($request->search)->select('ip', 'geo', 'device_type', 'platform', 'browser', 'created_at', 'updated_at')->latest()->paginate($request->perPage));
    });

});
