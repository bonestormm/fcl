<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('formato')->group(function () {
    Route::get('crear-formato', function(){return view('formato.crear-formato');});
    Route::post('generar-formato', [DocumentoController::class,'crearDocumento']);
    Route::get('excel', [DocumentoController::class,'excelCrear']);
});