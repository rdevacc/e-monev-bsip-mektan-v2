<?php

namespace App\Http\Middleware;

use App\Models\Kegiatan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EditSecureRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is Super Admin or Admin will pass the Auth
        if(!Auth::check() || $request->route('kegiatan') && (auth()->user()->role->id == 1 || auth()->user()->role->id == 2)){
            return $next($request);
        }

        // Check if user has login and own the data
        if (!Auth::check()) {
            $a = $request->path();
            $pisah = explode('/', $a);
            $id = intval($pisah["3"]);
            $data = Kegiatan::find($id);
                        
            if($data && $data->pj->id != auth()->user()->id){
                return abort(403);
            }
        }

        return $next($request);
    }
}
