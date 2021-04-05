<?php

namespace App\Http\Controllers;

use App\Models\Menbresia;
use App\Models\Stock;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Movimiento;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngresoController extends Controller
{
    
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        
        $cliente=Cliente::where('id',$request->cliente_id)->first();
        $movimiento=new Movimiento();
        $movimiento->concepto_id=1;
        $movimiento->cuenta_id=$request->cuenta_id;
        $movimiento->referencia=$request->referencia.' - '.$cliente->nombres.' '.$cliente->ape_paterno;
        $movimiento->monto=0;
        $movimiento->fecha=Carbon::now();
        $movimiento->save();
        $ingreso=new Ingreso();
        $ingreso->descuento=$request->descuento;
        $ingreso->total=0;
        // $ingreso->fecha=Carbon::now();
        $ingreso->movimiento_id=$movimiento->id;
        $ingreso->cliente_id=$request->cliente_id;
        $ingreso->save();

        $items=$request->items;
        $total=0;
        for ($i=0; $i < count($items); $i++) { 
            $item=$items[$i];
            $producto_id=$item['producto_id'];
            $monto=$item['monto'];
            $cantidad=$item['cantidad'];
            $detalle=new DetalleIngreso();
            $detalle->ingreso_id=$ingreso->id;
            $detalle->producto_id=$producto_id;
            $detalle->monto=$monto;
            $detalle->cantidad=$cantidad;
            $detalle->save();
            $total+=$cantidad*$monto;

            $producto=Producto::where('id',$producto_id)->first();
            switch ($producto->tipo) {
                case 'P':
                    $stock=new Stock();
                    $stock->producto_id=$producto_id;
                    $stock->tipo="E";
                    $stock->referencia_id=$ingreso->id;
                    $stock->cantidad=$cantidad;
                    $stock->fecha=Carbon::now();
                    $stock->save();
                    break;
                case 'S':
                    if ($producto->pago='M') {
                        $fecha_inicio=$item['fecha_inicio'];
                        $menbresia=new Menbresia();
                        $menbresia->ingreso_id=$ingreso->id;
                        $menbresia->cantidad=$cantidad;
                        $menbresia->fecha_inicio=$fecha_inicio;
                        // $menbresia->fecha_fin=Carbon::parse($fecha_inicio)->addMonthsNoOverflow($cantidad);
                        $menbresia->fecha_fin=Carbon::parse($fecha_inicio)->addMonths($cantidad)->subDay();
                        $menbresia->producto_id=$producto_id;
                        $menbresia->save();
                    }
                    break;
            }
        }
        $total=$total-$request->descuento;
        $movimiento->monto=$total;
        $movimiento->save();
        $ingreso->total=$total;
        $ingreso->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Ingreso Registrado"
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function show(Ingreso $ingreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function edit(Ingreso $ingreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingreso $ingreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingreso $ingreso)
    {
        //
    }
}
