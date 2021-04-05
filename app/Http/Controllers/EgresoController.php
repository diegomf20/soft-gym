<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Stock;
use App\Models\DetalleEgreso;
use Illuminate\Http\Request;

use Carbon\Carbon;

class EgresoController extends Controller
{
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
}
