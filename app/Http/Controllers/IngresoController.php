<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Stock;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Movimiento;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngresoController extends Controller
{
    
    public function index(Request $request)
    {
        $query="SELECT 	I.id,
                        C.dni,
                        CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                        I.descuento,
                        I.total,
                        DATE_FORMAT(I.created_at,'%d/%m/%Y %h:%i %p') fecha,
                        M.estado
                FROM ingreso I
                INNER JOIN cliente C on C.id=I.cliente_id
                INNER JOIN movimiento M on M.id=I.movimiento_id
                ORDER BY I.created_at DESC";
        $ingresos=$this->paginate($query,[],10,$request->page);
        return response()->json($ingresos);
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
                        $membresia=new Membresia();
                        $membresia->ingreso_id=$ingreso->id;
                        $membresia->cantidad=$cantidad;
                        $membresia->fecha_inicio=$fecha_inicio;
                        // $membresia->fecha_fin=Carbon::parse($fecha_inicio)->addMonthsNoOverflow($cantidad);
                        $membresia->fecha_fin=Carbon::parse($fecha_inicio)->addMonths($cantidad)->subDay();
                        $membresia->producto_id=$producto_id;
                        $membresia->save();
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

    public function show(Ingreso $ingreso)
    {
        //
    }

    public function destroy($id)
    {
        $ingreso=Ingreso::where('id',$id)->first();
        $movimiento=Movimiento::where('id',$ingreso->movimiento_id)->first();
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
