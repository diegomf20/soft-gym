<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Exports\ProductoExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('all')) {
            if ($request->has('tipo')) {
                $productos=Producto::where('tipo',$request->tipo)->get();
            }else {   
                $productos=Producto::all();
            }
        }else{
            $query="SELECT  P.id,
                            P.codigo,
                            P.descripcion,
                            P.marca,
                            P.precio,
                            COALESCE(SUM(IF(S.tipo='I',1,-1)*S.cantidad),0) stock 
                    FROM producto P
                    LEFT JOIN stock S on S.producto_id=P.id
                    WHERE P.tipo='P'
                    GROUP BY P.id";
            if ($request->has('excel')) {
                $raw_query=DB::select($query);
                return Excel::download(new ProductoExport($raw_query), "rpt-stock.xlsx");
            }
            $productos=$this->paginate($query,[],10,$request->page);
            // =Producto::where('tipo',$request->tipo)->paginate(5);
        }
        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $producto=new Producto();
        $producto->descripcion=$request->descripcion;
        $producto->marca=$request->marca;
        $producto->precio=$request->precio;
        $producto->tipo=$request->tipo;
        if ($request->has('pago')) $producto->pago=$request->pago;
        $producto->save();
        if ($producto->tipo=='P') {
            $conteo=DB::select(DB::raw("SELECT COUNT(*) conteo FROM producto WHERE tipo='P'"))[0]->conteo;
            $producto->codigo='P'.str_pad($conteo, 3, "0", STR_PAD_LEFT);
            $producto->save();
        }else{
            $conteo=DB::select(DB::raw("SELECT COUNT(*) conteo FROM producto WHERE tipo='S'"))[0]->conteo;
            $producto->codigo='S'.str_pad($conteo, 3, "0", STR_PAD_LEFT);
            $producto->save();
        }
        return response()->json([
            "status"=>"OK",
            "message"=>"Producto Registrado",
        ]);
    }

    public function show($id)
    {
        $producto=Producto::where('id',$id)->first();
        return response()->json($producto);
    }

    public function update(Request $request, $id)
    {
        $producto=Producto::where('id',$id)->first();
        $producto->descripcion=$request->descripcion;
        $producto->marca=$request->marca;
        $producto->precio=$request->precio;
        $producto->tipo=$request->tipo;
        if ($request->has('pago')) $producto->pago=$request->pago;
        if ($request->has('tipo')) $producto->tipo=$request->tipo;
        $producto->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Producto Actualizado",
        ]);
    }

    public function destroy(Producto $producto)
    {
        //
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
