<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $cuentas=Cuenta::all();
        }else{
            $cuentas=Cuenta::paginate(5);
        }
        return response()->json($cuentas);
    }

    public function store(Request $request)
    {
        $cuenta=new Cuenta();
        $cuenta->descripcion=$request->descripcion;
        $cuenta->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Cuenta Registrada",
        ]);
    }

    public function show($id)
    {
        $cuenta=Cuenta::where('id',$id)->first();
        return response()->json($cuenta);
    }

    public function update(Request $request, $id)
    {
        $cuenta=Cuenta::where('id',$id)->first();
        $cuenta->descripcion=$request->descripcion;
        $cuenta->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Cuenta Actualizada",
        ]);
    }

    public function destroy(Cuenta $cuenta)
    {
        //
    }
}
