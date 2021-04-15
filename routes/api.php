<?php
ini_set('session.gc_maxlifetime', 28800);
session_start();
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\;

Route::post('login', 'UsersController@login');
Route::post('logout', 'UsersController@logout');
Route::get('comprobar', 'UsersController@comprobar');
Route::get('indicadores', 'IndicadoresController@index');
Route::get('cumpleanios', 'IndicadoresController@cumpleanios');
Route::get('indicador/egresos', 'IndicadoresController@egresos');

Route::get('reportes/membresias', 'ReportesController@membresias');
Route::get('reportes/balance', 'ReportesController@balance');
Route::get('reportes/recurrentes', 'ReportesController@recurrentes');
Route::get('user/{id}/privilegios', 'UsersController@privilegios');
Route::post('user/{id}/privilegios', 'UsersController@updatePrivilegios');
Route::resources([
    'modulo'=>'ModuloController',
    'user'=>'UsersController',
    'producto'=>'ProductoController',
    'cliente'=>'ClienteController',
    'cuenta'=>'CuentaController',
    'concepto'=>'ConceptoController',
    'egreso'=>'EgresoController',
    'ingreso'=>'IngresoController',
]);
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
