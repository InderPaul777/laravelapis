<?php

namespace App\Http\Middleware;

use App\Models\Permission\Permission;
use Closure;
use GuzzleHttp\Psr7\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());
        // dd($user->id);
        $permissoion =  \Request::route()->getName();
        // dd($permissoion );
        $PermissionId = Permission::where('slug', $permissoion)->pluck('id')->first();
        dd($PermissionId ); 
        //     return redirect()->back();
        // }
        return $next($request);
    }
}
