<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IndicadoresController extends Controller
{
    public function index(){
        $hoy=Carbon::now()->format('Y-m-d');
        // dd($hoy);
        $query="SELECT count(*) total 
                FROM membresia 
                WHERE fecha_fin>=?";
        $membresias=DB::select(DB::raw("$query"),[$hoy])[0]->total;
        return response()->json([
            "membresias"=> $membresias
        ]);
    }

    public function cumpleanios(){
        // dd(Carbon::now()->format('m'));
        $query="(   SELECT  nombres, 
                            ape_paterno, 
                            ape_materno, 
                            DATE_FORMAT(fecha_nacimiento,'%d de %M') fecha 
                    FROM cliente 
                    WHERE   CONCAT(
                                YEAR(CURDATE()),
                                DATE_FORMAT(fecha_nacimiento,'-%m-%d')
                            )
                            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                )
                UNION
                (   SELECT  nombres, 
                            ape_paterno, 
                            ape_materno, 
                            DATE_FORMAT(fecha_nacimiento,'%d de %M') fecha 
                    FROM cliente 
                    WHERE   CONCAT(
                                YEAR(CURDATE())+1,
                                DATE_FORMAT(fecha_nacimiento,'-%m-%d')
                            )
                            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                )";
        DB::statement("SET lc_time_names = 'es_ES'");
        $membresias=DB::select(DB::raw("$query"),[Carbon::now()->month]);
        return response()->json($membresias);
    }

    public function egresos(Request $request){
        $fecha=$request->fecha;
        $query="SELECT C.descripcion, SUM(monto) total
                FROM concepto C
                INNER JOIN movimiento M ON M.concepto_id=C.id
                WHERE C.tipo='E'
                AND DATE_FORMAT(M.fecha,'%Y-%m')=?
                GROUP BY C.id";
        $egresos=DB::select(DB::raw("$query"),[$fecha]);
        return response()->json($egresos);
    }

    public function ingresos_hoy(){
        $query="SELECT SUM(monto) total 
                FROM movimiento 
                where fecha = CURDATE()
                AND estado='A'";
         $ingresos=DB::select($query)[0]->total;
         return response()->json([
             "ingresos"=> $ingresos
         ]);
    }

    public function membresias(){
        $query="SELECT 	MAX(M.fecha_inicio) fecha_inicio,
                        MAX(M.fecha_fin) fecha_fin,
                        C.dni,
                        CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                        P.descripcion descripcion_producto,
                        DATEDIFF(MAX(fecha_fin),CURDATE()) vencimiento
                FROM membresia M 
                INNER JOIN ingreso I ON M.ingreso_id=I.id
                INNER JOIN cliente C ON I.cliente_id=C.id
                INNER JOIN producto P ON P.id=M.producto_id
                GROUP BY CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno), dni,P.descripcion
                HAVING vencimiento BETWEEN -5 AND 5
                ORDER BY vencimiento ASC";
         $membresias=DB::select($query);
         return response()->json($membresias);
    }
}
