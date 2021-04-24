<?php

namespace App\Http\Controllers;

use App\Http\Requests\EgresoRequest;
use App\Models\Movimiento;
use App\Models\Membresia;
use App\Models\Stock;
use App\Models\DetalleEgreso;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\MovimientoExport;
use Carbon\Carbon;

class EgresoController extends Controller
{
    public function index(Request $request){
        $query="SELECT 	M.id, 
                        M.fecha,
                        C.descripcion descripcion_concepto,
                        M.referencia,
                        M.monto,
                        CU.descripcion descripcion_cuenta,
                        M.estado, 
                        M.creado_por,
                        M.eliminado_por
                FROM movimiento M 
                INNER JOIN concepto C ON C.id=M.concepto_id
                INNER JOIN cuenta CU ON CU.id=M.cuenta_id
                WHERE tipo='E' 
                AND M.fecha BETWEEN ? AND ?
                AND C.id NOT IN('ETR','ITR')
                ORDER BY fecha DESC, id DESC";
        switch ($request->type) {
            case 'excel':
                $data=DB::select($query, [
                    $request->fecha_inicio,
                    $request->fecha_fin
                ]);

                return (new MovimientoExport([
                    'id','fecha','concepto','referencia','monto','cuenta','estado','creado por','eliminado por'
                ],$data))->download('ingresos_'.$request->fecha_inicio.'_'.$request->fecha_fin.'.xlsx');
                break;
            
            default:
                $ingresos=$this->paginate($query,[
                    $request->fecha_inicio,
                    $request->fecha_fin
                ],10,$request->page);
                return response()->json($ingresos);
                break;
        }
    }
    public function show($id)
    {   
        // dd();
        $query="SELECT 	M.id, 
                        C.descripcion descripcion_concepto,
                        M.referencia,
                        M.fecha,
                        M.monto,
                        M.creado_por,
                        M.eliminado_por,
                        CU.descripcion descripcion_cuenta 
                FROM movimiento M 
                INNER JOIN concepto C ON C.id=M.concepto_id
                INNER JOIN cuenta CU ON CU.id=M.cuenta_id
                WHERE tipo='E'
                AND M.id=?
                ORDER BY fecha DESC, id DESC";
        $egreso=DB::select(DB::raw($query), [$id])[0];
        $egreso->detalles=DetalleEgreso::where('movimiento_id',$id)->get();
        
        return response()->json($egreso);
    }
    public function store(EgresoRequest $request){
        $fecha=Carbon::parse($request->fecha);
        $movimiento=new Movimiento();
        $movimiento->concepto_id=$request->concepto_id;
        $movimiento->cuenta_id=$request->cuenta_id;
        $movimiento->referencia=$request->referencia;
        $movimiento->monto=0;
        $movimiento->fecha=$fecha;
        $movimiento->creado_por=$request->user;
        $movimiento->save();
        $items=$request->items;

        if (count($items)==0) {
            return response()->json([
                    "status"    =>  "WARNING",    
                    "message"   =>  "Agregue al menos un detalle.",    
                ]);
        }
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

    public function destroy($id, Request $request)
    {
        $movimiento=Movimiento::where('id',$id)->first();
        $movimiento->estado='I';
        $movimiento->eliminado_por=$request->user;
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
            "message"   =>  "Egreso Anulado"
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
