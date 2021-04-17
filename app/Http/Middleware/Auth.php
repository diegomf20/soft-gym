<?php
namespace App\Http\Middleware;
use Closure;
class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next, $guard = null)
    {
        $usuario=$request->header('Authorization');
        $request->merge(['user'=>$usuario]);
        return $next($request);
    }
}