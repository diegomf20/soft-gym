<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\;

Route::resources([
    'producto'=>'ProductoController',
    'cliente'=>'ClienteController',
    'cuenta'=>'CuentaController',
    'concepto'=>'ConceptoController',
    'ingreso'=>'IngresoController',
    'egreso'=>'EgresoController',
]);
Route::get('indicadores', 'IndicadoresController@index');
Route::get('cumpleanios', 'IndicadoresController@cumpleanios');
Route::get('indicador/egresos', 'IndicadoresController@egresos');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
