<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Stock;
use App\Models\DetalleEgreso;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Carbon\Carbon;

class EgresoController extends Controller
{
    public function index(Request $request){
        $query="SELECT 	M.id, 
                        C.descripcion descripcion_concepto,
                        M.referencia,
                        M.fecha,
                        M.monto,
                        CU.descripcion descripcion_cuenta,
                        M.estado 
                FROM movimiento M 
                INNER JOIN concepto C ON C.id=M.concepto_id
                INNER JOIN cuenta CU ON CU.id=M.cuenta_id
                WHERE tipo='E'
                ORDER BY fecha DESC, id DESC";
        $ingresos=$this->paginate($query,[],10,$request->page);
        return response()->json($ingresos);
    }
    public function show($id)
    {   
        // dd();
        $query="SELECT 	M.id, 
                        C.descripcion descripcion_concepto,
                        M.referencia,
                        M.fecha,
                        M.monto,
                        CU.descripcion descripcion_cuenta 
                FROM movimiento M 
                INNER JOIN concepto C ON C.id=M.concepto_id
                INNER JOIN cuenta CU ON CU.id=M.cuenta_id
                WHERE tipo='E'
                AND M.id=?
                ORDER BY fecha DESC, id DESC";
        $egreso=DB::select(DB::raw($query), [$id])[0];
        $egreso->detalles=DetalleEgreso::where('id',$id)->get();
        
        return response()->json($egreso);
    }
    public function store(Request $request){
        $fecha=Carbon::parse($request->fecha);
        $movimiento=new Movimiento();
        $movimiento->concepto_id=$request->concepto_id;
        $movimiento->cuenta_id=$request->cuenta_id;
        $movimiento->referencia=$request->referencia;
        $movimiento->monto=0;
        $movimiento->fecha=$fecha;
        $movimiento->save();
        $items=$request->items;
        $total=0;
        for ($i=0; $i < count($items); $i++) { 
            $producto_id=$items[$i]['producto_id'];
            $monto=$items[$i]['monto'];
            $cantidad=$items[$i]['cantidad'];
            $descripcion=$items[$i]['descripcion'];
            $detalle=new DetalleEgreso();
            $detalle->movimiento_id=$movimiento->id;
            $detalle->producto_id=$producto_id;
            $detalle->monto=$monto;
            $detalle->cantidad=$cantidad;
            $detalle->descripcion=$descripcion;
            $detalle->save();
            $total+=$cantidad*$monto;

            if ($producto_id!=null) {
                $stock=new Stock();
                $stock->producto_id=$producto_id;
                $stock->tipo="I";
                $stock->referencia_id=$movimiento->id;
                $stock->cantidad=$cantidad;
                $stock->fecha=$fecha;
                $stock->save();
            }
        }
        $movimiento->monto=$total;
        $movimiento->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Egreso Registrado"
        ]);
    }

    public function destroy($id)
    {
        $movimiento=Movimiento::where('id',$id)->first();
        $movimiento->estado='I';
        $movimiento->save();
        $stocks=Stock::where('tipo','E')
                        ->where('referencia_id',$id)
                        ->get();
        foreach ($stocks as $key => $stock) {
            $stock->delete();
        }
        $membresias=Membresia::where('ingreso_id',$id)->get();
        foreach ($membresias as $key => $membresia) {
            $membresia->delete();
        }
        return response()->json([
            "status"    =>  "OK",
            "message"   =>  "Ingreso Anulado"
        ]);
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
