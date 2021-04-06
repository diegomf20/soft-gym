<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes=Cliente::paginate(5);
        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $cliente=new Cliente();
        $cliente->dni=$request->dni;
        $cliente->nombres=$request->nombres;
        $cliente->ape_paterno=$request->ape_paterno;
        $cliente->ape_materno=$request->ape_materno;
        $cliente->telefono=$request->telefono;
        $cliente->email=$request->email;
        $cliente->direccion=$request->direccion;
        $cliente->fecha_nacimiento=$request->fecha_nacimiento;
        $cliente->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Cliente Registrado",
        ]);
    }

    public function show($dni)
    {
        $cliente=Cliente::where('dni',$dni)->first();
        return response()->json($cliente);
    }

    public function update(Request $request, $dni)
    {
        $cliente=Cliente::where('dni',$dni)->first();
        $cliente->nombres=$request->nombres;
        $cliente->ape_paterno=$request->ape_paterno;
        $cliente->ape_materno=$request->ape_materno;
        $cliente->telefono=$request->telefono;
        $cliente->email=$request->email;
        $cliente->direccion=$request->direccion;
        $cliente->fecha_nacimiento=$request->fecha_nacimiento;
        $cliente->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Cliente Actualizado",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        
    }
}
