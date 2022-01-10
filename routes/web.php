<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', fn() => clearCache());

Route::get('/', function () {
    try {
        return event(new \App\Events\SendMessage());
    } catch (\Exception $exception) {
        return $exception->getMessage();
    }
    return "Metapol Core - " . now()->format("Y");
});