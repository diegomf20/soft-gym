<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Http\Requests\ConceptoRequest;
use App\Http\Requests\ConceptoEditarRequest;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $conceptos=Concepto::where('tipo',$request->tipo)->get();
        }else{
            $conceptos=Concepto::where('tipo',$request->tipo)->paginate(5);
        }
        return response()->json($conceptos);
    } 

    public function store(ConceptoRequest $request)
    {
        $concepto=new Concepto();
        $concepto->id=$request->id;
        $concepto->descripcion=$request->descripcion;
        $concepto->tipo=$request->tipo;
        $concepto->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Concepto Registrado",
        ]);
    }

    public function show($id)
    {
        $concepto=Concepto::where('id',$id)->first();
        return response()->json($concepto);
    }
    
    public function update(ConceptoEditarRequest $request, $id)
    {
        $concepto=Concepto::where('id',$id)->first();
        $concepto->descripcion=$request->descripcion;
        $concepto->tipo=$request->tipo;
        $concepto->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Concepto Actualizado",
        ]);
    }

    public function destroy($id)
    {
        
    }
}
