<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\BalanceAnualExport;
use App\Exports\ProductoAnualExport;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function membresias(Request $request){

        $texto_busqueda=explode(" ",$request->search);
        
        $sql_search="AND CONCAT(nombres,' ',ape_paterno,' ',ape_materno) like '%".$texto_busqueda[0]."%'";
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $sql_search=$sql_search." AND CONCAT(nombres,' ',ape_paterno,' ',ape_materno) like '%".$texto_busqueda[$i]."%'";
        }


        $hoy=Carbon::now()->format('Y-m-d');
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
                WHERE M.fecha_fin >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                $sql_search
                GROUP BY CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno), dni,P.descripcion
                ORDER BY vencimiento ASC";
        $membresias=DB::select(DB::raw("$query"));
        return response()->json($membresias);
    }

    public function recurrentes(Request $request){
        $query="SELECT 	C.dni,
                                CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                                COUNT(*) servicios_ventas,
                                SUM(I.total) total 
                FROM cliente C
                INNER JOIN ingreso I on C.id=I.cliente_id
                WHERE I.created_at>=?
                AND I.created_at<=?
                GROUP BY C.id
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
                AND C.id NOT IN('ETR','ITR')
                GROUP BY C.id
                ORDER BY tipo";
        $data=DB::select(DB::raw("$query"),[
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
        // $data=$this->paginate($query,[],10,$request->page);
        return response()->json($data);
    }
    public function resumen(){
        $query="SELECT 	CU.descripcion,
                        SUM(IF(tipo='I',monto,0)) - SUM(IF(tipo='E',monto,0)) total
                FROM cuenta CU
                LEFT JOIN movimiento M on CU.id=M.cuenta_id
                LEFT JOIN concepto C on C.id=M.concepto_id
                WHERE M.estado='A' OR ISNULL(M.estado)
                GROUP BY CU.descripcion";
        $data=DB::select($query);
        return response()->json($data);    
    }
    
    public function cuenta(Request $request){
        $query="SELECT 	CU.descripcion descripcion,
                        SUM(IF(tipo='I',monto,0)) ingreso,
                        SUM(IF(tipo='E',monto,0)) egreso 
                FROM movimiento M
                INNER JOIN concepto C on C.id=M.concepto_id
                INNER JOIN cuenta CU on CU.id=M.cuenta_id
                WHERE fecha BETWEEN ? AND ?
                AND M.estado='A'
                AND C.id NOT IN('ETR','ITR')
                GROUP BY CU.descripcion
                ORDER BY tipo";
        $data=DB::select(DB::raw("$query"),[
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
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
                    WHERE YEAR(fecha)=?
                    AND M.estado='A'
                    AND C.id NOT IN('ETR','ITR')
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
                    WHERE YEAR(fecha)=?
                    AND M.estado='A'
                    AND C.id NOT IN('ETR','ITR')
                    ORDER BY tipo)";
        $data=DB::select($query,[$year,$year]);
        return (new BalanceAnualExport($data))->download("balance_anual_$year.xlsx");
        
        // return response()->json($data);
    }

    public function producto_anual(Request $request){
        $year=$request->year;
        $query="SELECT 	p.codigo,
                        p.descripcion,
                        SUM(IF(MONTH(fecha)=1,cantidad,0)) ENERO,
                        SUM(IF(MONTH(fecha)=2,cantidad,0)) FEBRERO,
                        SUM(IF(MONTH(fecha)=3,cantidad,0)) MARZO,
                        SUM(IF(MONTH(fecha)=4,cantidad,0)) ABRIL,
                        SUM(IF(MONTH(fecha)=5,cantidad,0)) MAYO,
                        SUM(IF(MONTH(fecha)=6,cantidad,0)) JUNIO,
                        SUM(IF(MONTH(fecha)=7,cantidad,0)) JULIO,
                        SUM(IF(MONTH(fecha)=8,cantidad,0)) AGOSTO,
                        SUM(IF(MONTH(fecha)=9,cantidad,0)) SEPTIEMBRE,
                        SUM(IF(MONTH(fecha)=10,cantidad,0)) OCTUBRE,
                        SUM(IF(MONTH(fecha)=11,cantidad,0)) NOVIEMBRE,
                        SUM(IF(MONTH(fecha)=12,cantidad,0)) DICIEMBRE
                FROM producto P
                INNER JOIN stock S on P.id=S.producto_id
                WHERE S.tipo='E'
                AND YEAR(fecha)=?
                GROUP BY P.descripcion";
        $data=DB::select($query,[$year]);
        return (new ProductoAnualExport($data))->download("productos_anual_$year.xlsx");
    }

    public function producto_diario(Request $request){
        $day=$request->day;
        $query="SELECT 	p.codigo,
                        p.descripcion,
                        SUM(cantidad) total
                FROM producto P
                INNER JOIN stock S on P.id=S.producto_id
                WHERE S.tipo='E'
                AND DATE(fecha)=?
                GROUP BY P.descripcion";
        $data=DB::select($query,[$day]);
        return response()->json($data);
        // return (new ProductoAnualExport($data))->download("productos_diario_$year.xlsx");
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
