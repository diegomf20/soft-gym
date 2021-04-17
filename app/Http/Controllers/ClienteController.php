<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Exports\ClienteExport;
use App\Http\Requests\ClienteRequest;
use App\Http\Requests\ClienteEditarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->type) {
            case 'excel':
                return (new ClienteExport)->download('cliente.xlsx');
                break;
            
            default:
                # code...
                break;
        }

        $texto_busqueda=explode(" ",$request->search);

        $clientes=Cliente::where(DB::raw("CONCAT(dni,' ',nombres,' ',ape_paterno,' ',ape_materno)"),"like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $clientes=$clientes->where(DB::raw("CONCAT(dni,' ',nombres,' ',ape_paterno,' ',ape_materno)"),"like","%".$texto_busqueda[$i]."%");
        }

        $clientes=$clientes->paginate(5);

        return response()->json($clientes);
    }

    public function store(ClienteRequest $request)
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

    public function update(ClienteEditarRequest $request, $dni)
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

    public function historial($id){
        $query="SELECT P.descripcion, M.fecha_inicio,M.fecha_fin 
                FROM membresia M 
                INNER JOIN ingreso I on I.id=M.ingreso_id
                INNER JOIN producto P on M.producto_id=P.id
                WHERE I.cliente_id=?
                ORDER BY fecha_fin DESC";
        $data=DB::select(DB::raw("$query"),[$id]);
        return response()->json($data);
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
