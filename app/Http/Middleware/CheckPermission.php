<?php

namespace App\Http\Middleware;

use App\Models\Permission\Permission;
use Closure;
use GuzzleHttp\Psr7\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin\User\User;
use Illuminate\Support\Facades\DB;

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


        $user = (JWTAuth::toUser(JWTAuth::getToken()));
      if(isset($user->role) && 1 == $user->role ){
        return $next($request);
      }
        $permissoion =  \Request::route()->getName(); 
        //DB::enableQueryLog();
        $havePermission = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->select('users.*', 'roles.name as role_name', 'permissions.name as permission_name')
            ->where('permissions.slug', $permissoion)
            ->get();
            if (!$havePermission->isEmpty()) { 
                return $next($request);
            }else{
                $havePermission = DB::table('users')
                    ->join('user_permissions', 'users.id', '=', 'user_permissions.user_id')
                    ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                    ->select('users.*', 'permissions.name as permission_name')
                    ->where('permissions.slug', $permissoion)
                    ->get();
                    if (!$havePermission->isEmpty()) { 
                        return $next($request);
                    }else{
                        return response()->json(['message'=>"You don't have permission to access route"], 401);
                    }
            }
    }
}
