<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IndicadoresController extends Controller
{
    public function index(){
        $hoy=Carbon::now()->format('Y-M-d');
        dd($hoy);
        $query="SELECT count(*) total 
                FROM menbresia 
                WHERE fecha_fin>=?";
        $menbresias=DB::select(DB::raw("$query"),[])[0]->total;
        return response()->json([
            "menbresias"=> $menbresias
        ]);
    }
}
