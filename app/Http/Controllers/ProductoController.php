<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $productos=Producto::all();
        }else{
            $productos=Producto::where('tipo',$request->tipo)->paginate(5);
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
        if ($request->has('tipo')) $producto->tipo=$request->tipo;
        $producto->save();
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
}
