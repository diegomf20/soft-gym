<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\;

Route::post('login', 'UsersController@login');
Route::post('logout', 'UsersController@logout');
Route::get('comprobar', 'UsersController@comprobar');
Route::get('indicadores', 'IndicadoresController@index');
Route::get('cumpleanios', 'IndicadoresController@cumpleanios');
Route::get('indicador/egresos', 'IndicadoresController@egresos');
Route::get('indicador/ingresos_hoy', 'IndicadoresController@ingresos_hoy');
Route::get('indicador/membresias', 'IndicadoresController@membresias');

Route::get('reportes/membresias', 'ReportesController@membresias');
Route::get('reportes/balance', 'ReportesController@balance');
Route::get('reportes/cuenta', 'ReportesController@cuenta');
Route::get('reportes/resumen', 'ReportesController@resumen');
Route::get('reportes/balance_anual', 'ReportesController@balance_anual');
Route::get('reportes/producto_anual', 'ReportesController@producto_anual');
Route::get('reportes/producto_diario', 'ReportesController@producto_diario');
Route::get('reportes/recurrentes', 'ReportesController@recurrentes');
Route::get('user/{id}/privilegios', 'UsersController@privilegios');
Route::post('user/{id}/privilegios', 'UsersController@updatePrivilegios');
Route::post('user/contrasenia', 'UsersController@contrasenia');
Route::post('user/reset', 'UsersController@reset');
Route::get('cliente/{id}/historial', 'ClienteController@historial');
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
