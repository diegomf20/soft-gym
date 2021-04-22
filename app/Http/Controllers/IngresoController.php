<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Stock;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Movimiento;
use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use App\Exports\MovimientoExport;
use App\Http\Requests\IngresoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngresoController extends Controller
{
    
    public function index(Request $request)
    {
        
        $query="SELECT 	I.id,
                        DATE_FORMAT(I.created_at,'%d/%m/%Y %h:%i %p') fecha,
                        C.dni,
                        IFNULL(C.id=null,'Cliente Anonimo',CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno)) descripcion_cliente,
                        I.descuento,
                        I.total,
                        M.estado,
                        M.creado_por,
                        M.eliminado_por
                FROM ingreso I
                LEFT JOIN cliente C on C.id=I.cliente_id
                INNER JOIN movimiento M on M.id=I.movimiento_id
                WHERE M.fecha BETWEEN ? AND ?
                ORDER BY I.created_at DESC";
        switch ($request->type) {
            case 'excel':
                $data=DB::select($query, [
                    $request->fecha_inicio,
                    $request->fecha_fin
                ]);

                return (new MovimientoExport([
                    'id','fecha','DNI','cliente','descuento','total','estado','creado por','eliminado por'
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

    public function store(IngresoRequest $request)
    {
        $cliente=Cliente::where('id',$request->cliente_id)->first();
        $movimiento=new Movimiento();
        $movimiento->concepto_id=$request->has('concepto_id') ? $request->concepto_id:'IXC';
        $movimiento->cuenta_id=$request->cuenta_id;
        $movimiento->referencia=$request->referencia.' - '.$cliente->nombres.' '.$cliente->ape_paterno;
        $movimiento->monto=0;
        $movimiento->fecha=Carbon::now();
        $movimiento->creado_por=$request->user;
        $movimiento->save();
        $ingreso=new Ingreso();
        $ingreso->descuento=$request->descuento;
        $ingreso->total=0;
        $ingreso->movimiento_id=$movimiento->id;
        $ingreso->cliente_id=$request->cliente_id;
        $ingreso->save();

        $items=$request->items;
        if (count($items)==0) {
            return response()->json([
                    "status"    =>  "WARNING",    
                    "message"   =>  "Agregue al menos un detalle.",    
                ]);
        }
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
                    if ($producto->pago=='M') {
                        $fecha_inicio=$item['fecha_inicio'];
                        $membresia=new Membresia();
                        $membresia->ingreso_id=$ingreso->id;
                        $membresia->cantidad=$cantidad;
                        $membresia->fecha_inicio=$fecha_inicio;
                        $membresia->fecha_fin=Carbon::parse($fecha_inicio)->addDays(30*$cantidad)->subDay();
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

    public function store_rapic(Request $request){
        $producto_id=$request->producto_id;
        $monto=$request->monto;
        $cantidad=$request->cantidad;
        $total=$cantidad*$monto;

        $movimiento=new Movimiento();
        $movimiento->concepto_id=$request->has('concepto_id') ? $request->concepto_id : 'IXC';
        $movimiento->cuenta_id=$request->cuenta_id;
        $movimiento->referencia='Ingreso Rapido - Cliente Anonimo';
        $movimiento->monto=$total;
        $movimiento->fecha=Carbon::now();
        $movimiento->creado_por=$request->user;
        $movimiento->save();
        $ingreso=new Ingreso();
        $ingreso->descuento=$request->descuento;
        $ingreso->total=$total;
        $ingreso->movimiento_id=$movimiento->id;
        $ingreso->cliente_id=$request->cliente_id;
        $ingreso->save();
        //detalle de venta
        $detalle=new DetalleIngreso();
        $detalle->ingreso_id=$ingreso->id;
        $detalle->producto_id=$producto_id;
        $detalle->monto=$monto;
        $detalle->cantidad=$cantidad;
        $detalle->save();
        //movimiento de stock
        $stock=new Stock();
        $stock->producto_id=$producto_id;
        $stock->tipo="E";
        $stock->referencia_id=$ingreso->id;
        $stock->cantidad=$cantidad;
        $stock->fecha=Carbon::now();
        $stock->save();

        return response()->json([
            "status"=>"OK",
            "message"=>"Ingreso Registrado"
        ]);
    }

    public function show($id)
    {   
        $query="SELECT 	I.id,
                        C.dni,
                        CONCAT(C.nombres,' ',C.ape_paterno,' ',C.ape_materno) descripcion_cliente,
                        I.descuento,
                        I.total,
                        DATE_FORMAT(I.created_at,'%d/%m/%Y %h:%i %p') fecha,
                        M.estado,
                        M.creado_por,
                        M.eliminado_por
                FROM ingreso I
                INNER JOIN cliente C on C.id=I.cliente_id
                INNER JOIN movimiento M on M.id=I.movimiento_id
                WHERE I.id=?";
        $ingreso=DB::select(DB::raw($query), [$id])[0];
        // $ingreso=Ingreso::where('id',$id)
        //                     ->select(DB::raw("DATE_FORMAT(ingreso.created_at,'%d/%m/%Y %h:%i %p') fecha"),'ingreso.')
        //                     ->first();
        $detalle=DetalleIngreso::join('producto','producto.id','=','detalle_ingreso.producto_id')
                    ->where('ingreso_id',$id)->get();
        $ingreso->detalles=$detalle;
        return response()->json($ingreso);
    }

    public function destroy($id,Request $request)
    {
        $ingreso=Ingreso::where('id',$id)->first();
        $movimiento=Movimiento::where('id',$ingreso->movimiento_id)->first();
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
