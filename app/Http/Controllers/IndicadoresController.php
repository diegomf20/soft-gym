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
        $query="SELECT nombres, ape_paterno, ape_materno, DATE_FORMAT(fecha_nacimiento,'%d de %M') fecha 
                FROM cliente 
                WHERE MONTH(fecha_nacimiento)=?";
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
}
