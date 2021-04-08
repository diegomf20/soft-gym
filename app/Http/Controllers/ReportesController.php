<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function membresias(){
        $hoy=Carbon::now()->format('Y-m-d');
        $query="SELECT 	M.fecha_inicio,
                        M.fecha_fin,
                        CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                        P.descripcion descripcion_producto
                FROM membresia M 
                INNER JOIN ingreso I ON M.ingreso_id=I.id
                INNER JOIN cliente C ON I.cliente_id=C.id
                INNER JOIN producto P ON P.id=M.producto_id
                WHERE M.fecha_fin >= ?";
        $membresias=DB::select(DB::raw("$query"),[$hoy]);
        return response()->json($membresias);
    }
}
