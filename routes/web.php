<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DocumentoController;
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

Route::prefix('formato')->group(function () {
    Route::get('crear-formato', function(){return view('formato.crear-formato');});
    Route::post('generar-formato', [DocumentoController::class,'crearDocumento']);
    Route::get('excel', [DocumentoController::class,'excelCrear']);
});


//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');