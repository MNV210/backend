<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UploadController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/import', [ImportController::class, 'import'])->name('import');
Route::post('/upload-image', [UploadController::class, 'uploadToS3']);
