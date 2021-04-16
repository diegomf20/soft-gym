<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function membresias(){
        $hoy=Carbon::now()->format('Y-m-d');
        $query="SELECT 	MAX(M.fecha_inicio) fecha_inicio,
                        MAX(M.fecha_fin) fecha_fin,
                        C.dni,
                        CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                        P.descripcion descripcion_producto,
                        DATEDIFF(MAX(fecha_fin),?) vencimiento
                FROM membresia M 
                INNER JOIN ingreso I ON M.ingreso_id=I.id
                INNER JOIN cliente C ON I.cliente_id=C.id
                INNER JOIN producto P ON P.id=M.producto_id
                WHERE M.fecha_fin >= ?
                GROUP BY CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno), dni,P.descripcion
                ORDER BY vencimiento ASC";
        $membresias=DB::select(DB::raw("$query"),[$hoy,Carbon::parse($hoy)->subDays(7)]);
        return response()->json($membresias);
    }

    public function recurrentes(Request $request){
        $query="SELECT 	C.dni,
                                CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                                COUNT(*) servicios_ventas,
                                SUM(I.total) total 
                FROM cliente C
                INNER JOIN ingreso I on C.id=I.cliente_id
                WHERE I.created_at>='2021-04-10'
                AND I.created_at<='2021-04-14'
                GROUP BY dni
                ORDER BY total DESC";
        $data=DB::select(DB::raw("$query"),[
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
        // $data=$this->paginate($query,[],10,$request->page);
        return response()->json($data);
    }

    public function balance(Request $request){
        $query="SELECT C.id,C.descripcion,SUM(IF(tipo='I',monto,0)) ingreso,SUM(IF(tipo='E',monto,0)) egreso 
                FROM movimiento M
                INNER JOIN concepto C on C.id=M.concepto_id
                WHERE fecha>=?
                AND fecha<=?
                AND M.estado='A'
                GROUP BY C.id
                ORDER BY tipo";
        $data=DB::select(DB::raw("$query"),[
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
        // $data=$this->paginate($query,[],10,$request->page);
        return response()->json($data);
    }

    public function balance_anual(Request $request){
        $year=$request->year;
        $query="(SELECT 	C.descripcion,
                            SUM(IF(tipo='I' AND MONTH(fecha)=1,monto,0)) ing_01,
                            SUM(IF(tipo='E' AND MONTH(fecha)=1,monto,0)) egr_01,
                            SUM(IF(tipo='I' AND MONTH(fecha)=2,monto,0)) ing_02,
                            SUM(IF(tipo='E' AND MONTH(fecha)=2,monto,0)) egr_02,
                            SUM(IF(tipo='I' AND MONTH(fecha)=3,monto,0)) ing_03,
                            SUM(IF(tipo='E' AND MONTH(fecha)=3,monto,0)) egr_03,
                            SUM(IF(tipo='I' AND MONTH(fecha)=4,monto,0)) ing_04,
                            SUM(IF(tipo='E' AND MONTH(fecha)=4,monto,0)) egr_04,
                            SUM(IF(tipo='I' AND MONTH(fecha)=5,monto,0)) ing_05,
                            SUM(IF(tipo='E' AND MONTH(fecha)=5,monto,0)) egr_05,
                            SUM(IF(tipo='I' AND MONTH(fecha)=6,monto,0)) ing_06,
                            SUM(IF(tipo='E' AND MONTH(fecha)=6,monto,0)) egr_06,
                            SUM(IF(tipo='I' AND MONTH(fecha)=7,monto,0)) ing_07,
                            SUM(IF(tipo='E' AND MONTH(fecha)=7,monto,0)) egr_07,
                            SUM(IF(tipo='I' AND MONTH(fecha)=8,monto,0)) ing_08,
                            SUM(IF(tipo='E' AND MONTH(fecha)=8,monto,0)) egr_08,
                            SUM(IF(tipo='I' AND MONTH(fecha)=9,monto,0)) ing_09,
                            SUM(IF(tipo='E' AND MONTH(fecha)=9,monto,0)) egr_09,
                            SUM(IF(tipo='I' AND MONTH(fecha)=10,monto,0)) ing_10,
                            SUM(IF(tipo='E' AND MONTH(fecha)=10,monto,0)) egr_10,
                            SUM(IF(tipo='I' AND MONTH(fecha)=11,monto,0)) ing_11,
                            SUM(IF(tipo='E' AND MONTH(fecha)=11,monto,0)) egr_11,
                            SUM(IF(tipo='I' AND MONTH(fecha)=12,monto,0)) ing_12,
                            SUM(IF(tipo='E' AND MONTH(fecha)=12,monto,0)) egr_12
                    FROM movimiento M
                    INNER JOIN concepto C on C.id=M.concepto_id
                    WHERE YEAR(fecha)=2021
                    AND M.estado='A'
                    GROUP BY C.descripcion
                    ORDER BY tipo)
                    UNION
                    (SELECT 	'TOTAL' descripcion,
                            SUM(IF(tipo='I' AND MONTH(fecha)=1,monto,0)) ing_01,
                            SUM(IF(tipo='E' AND MONTH(fecha)=1,monto,0)) egr_01,
                            SUM(IF(tipo='I' AND MONTH(fecha)=2,monto,0)) ing_02,
                            SUM(IF(tipo='E' AND MONTH(fecha)=2,monto,0)) egr_02,
                            SUM(IF(tipo='I' AND MONTH(fecha)=3,monto,0)) ing_03,
                            SUM(IF(tipo='E' AND MONTH(fecha)=3,monto,0)) egr_03,
                            SUM(IF(tipo='I' AND MONTH(fecha)=4,monto,0)) ing_04,
                            SUM(IF(tipo='E' AND MONTH(fecha)=4,monto,0)) egr_04,
                            SUM(IF(tipo='I' AND MONTH(fecha)=5,monto,0)) ing_05,
                            SUM(IF(tipo='E' AND MONTH(fecha)=5,monto,0)) egr_05,
                            SUM(IF(tipo='I' AND MONTH(fecha)=6,monto,0)) ing_06,
                            SUM(IF(tipo='E' AND MONTH(fecha)=6,monto,0)) egr_06,
                            SUM(IF(tipo='I' AND MONTH(fecha)=7,monto,0)) ing_07,
                            SUM(IF(tipo='E' AND MONTH(fecha)=7,monto,0)) egr_07,
                            SUM(IF(tipo='I' AND MONTH(fecha)=8,monto,0)) ing_08,
                            SUM(IF(tipo='E' AND MONTH(fecha)=8,monto,0)) egr_08,
                            SUM(IF(tipo='I' AND MONTH(fecha)=9,monto,0)) ing_09,
                            SUM(IF(tipo='E' AND MONTH(fecha)=9,monto,0)) egr_09,
                            SUM(IF(tipo='I' AND MONTH(fecha)=10,monto,0)) ing_10,
                            SUM(IF(tipo='E' AND MONTH(fecha)=10,monto,0)) egr_10,
                            SUM(IF(tipo='I' AND MONTH(fecha)=11,monto,0)) ing_11,
                            SUM(IF(tipo='E' AND MONTH(fecha)=11,monto,0)) egr_11,
                            SUM(IF(tipo='I' AND MONTH(fecha)=12,monto,0)) ing_12,
                            SUM(IF(tipo='E' AND MONTH(fecha)=12,monto,0)) egr_12
                    FROM movimiento M
                    INNER JOIN concepto C on C.id=M.concepto_id
                    WHERE YEAR(fecha)=2021
                    AND M.estado='A'
                    ORDER BY tipo)";
        $data=DB::select($query,[$year]);
        return response()->json($data);
    }

    public function paginate($query,$param,$per_page = 10,$page = 1){
        // dd((int)$page);
        $total=DB::select(DB::raw("SELECT count(*) conteo FROM ($query) AL"),$param)[0]->conteo;
        $last_page=(int)ceil($total/$per_page);
        $offset=($page-1)*$per_page;
        
        $raw_query=DB::select(DB::raw("$query limit $per_page offset $offset"),$param);
        return [
                "current_page"  =>  (int)$page,
                "data"          =>  $raw_query,
                "total"         =>  $total,
                "last_page"     =>  $last_page
            ];
    }    
}
