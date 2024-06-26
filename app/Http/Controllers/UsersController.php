<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserModulo;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserEditarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{
    public function login(Request $request){
        $user=User::where('usuario',$request->usuario)
                ->where('contrasenia',$request->contrasenia)
                ->first();
        try {
            if ($user!=null) {
                $_SESSION["user"]=$user;
                return response()->json([
                        "status" => "OK",
                        "message" => "Logeado",
                        "data" => $user
                    ]);
            }else{
                return response()->json([
                    "status" => "ERROR",
                    "message" => "Datos Incorrectos"
                ]);
            }
        } catch (Exception $th) {
        }
    }

    public function logout(Request $request){
        session_destroy();
        return response()->json([
            "status"=> "OK"
            ]);
        }
        
    public function comprobar(Request $request){
        // dd($request->session()->get("user"),session("user"));
        // dd();

        if (isset($_SESSION["user"])) {
            return response()->json([
                "status"=>"OK"
            ]);
        }else {
            return response()->json([
                "status"=>"ERROR"
            ]);
            
        }
    }

    public function index(Request $request){
        $texto_busqueda=explode(" ",$request->search);

        $users=User::where(DB::raw("CONCAT(usuario,' ',nombres,' ',ape_paterno,' ',ape_materno)"),"like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $users=$users->where(DB::raw("CONCAT(usuario,' ',nombres,' ',ape_paterno,' ',ape_materno)"),"like","%".$texto_busqueda[$i]."%");
        }
        $users=$users->paginate(6);

        // $users=User::paginate(6);
        return response()->json($users);
    }

    public function store(UserRequest $request){
        $user=new User();
        $user->nombres=$request->nombres;
        $user->ape_paterno=$request->ape_paterno;
        $user->ape_materno=$request->ape_materno;
        $user->usuario=$request->usuario;
        $user->contrasenia=$request->contrasenia;
        $user->save();
        return response()->json([
            "status"=>"OK",
            "message"=>"Usuario Registrado",
            ]);
        }
        
    public function show($id)
    {
        $user=User::where('id',$id)->first();
        return response()->json($user);
    }
    
    public function update($id,UserEditarRequest $request){
        $user=User::where('id',$id)->first();
        $user->nombres=$request->nombres;
        $user->ape_paterno=$request->ape_paterno;
        $user->ape_materno=$request->ape_materno;
        $user->save();
        return response()->json([
                    "status"=>"OK",
                    "message"=>"Usuario Actualizado",
                ]);
            }
    public function contrasenia(Request $request){
        $user=User::where('usuario',$request->user)
            ->where('contrasenia',$request->actual)
            ->first();
        // dd($request->all(),$user);
        if ($user==null) {
            return response()->json([
                        "status"=>"ERROR",
                        "message"=>"Contraseña Actual Incorrecta",
                    ]);
        }else{
            $user->contrasenia=$request->nueva;
            $user->save();
            return response()->json([
                        "status"=>"OK",
                        "message"=>"Contraseña Actualizada",
                    ]);
        }
                
    }
    public function reset(Request $request){
        $user=User::where('usuario',$request->usuario)
            ->first();
        $user->contrasenia=$request->nueva;
        $user->save();
        return response()->json([
                "status"=>"OK",
                "message"=>"Contraseña Reseteada",
            ]);
    }

    public function privilegios($id){
        $modulos=UserModulo::where('users_id',$id)
                    ->join('modulo','modulo.id','=','users_modulo.modulo_id')
                    ->select('modulo_id','modulo.*')
                    ->get();
        return response()->json($modulos);
    }
    public function updatePrivilegios($id,Request $request){
        $modulos=UserModulo::where('users_id',$id)->get();
        foreach ($modulos as $key => $modulo) {
            $modulo->delete();
        }
        $modulos_ids=$request->modulos;
        for ($i=0; $i < count($modulos_ids); $i++) { 
            $modulo_id=$modulos_ids[$i];
            $userModulo=new UserModulo();
            $userModulo->users_id=$id;
            $userModulo->modulo_id=$modulo_id;
            $userModulo->save();
        }
        return response()->json([
            "status"=>"OK",
            "message"=>"Privilegios Actualizados"
        ]);
    }
}
