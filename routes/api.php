<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\Permission\PermissionController;
use App\Http\Controllers\FrontController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/user', [UserController::class, 'user']);
Route::get('/users', [UserController::class, 'users']);
Route::get('/user/{id}', [UserController::class, 'userDetails']);
Route::patch('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);

Route::post('/user/assignPermissions', [UserController::class, 'assignPermissions']);
Route::post('/user/updatePermissions', [UserController::class, 'updatePermissions']);

Route::post('/user/registration', [FrontController::class, 'registration']);
Route::post('/user/login', [FrontController::class, 'login']);
Route::get('/refreshToken', [FrontController::class, 'refreshToken']);
Route::post('/user/forgotPassword', [FrontController::class, 'forgotPassword']);
Route::post('/user/resetPassword', [FrontController::class, 'resetPassword']);
Route::post('/user/changePassword/{id}', [FrontController::class, 'changePassword']);

Route::post('role', [RoleController::class,'createRole'])->name('role');
Route::get('roles', [RoleController::class, 'getAllRoles']);
Route::get('role/{id}',[RoleController::class, 'roleDetail']);
Route::patch('role/{id}',[RoleController::class,'updateRole']);
Route::delete('role/{id}',[RoleController::class,'deleteRole']);

Route::post('permission', [PermissionController::class,'createPermission'])->name('permission');
Route::get('permissions', [PermissionController::class, 'getAllPermissions']);
Route::get('permission/{id}',[PermissionController::class, 'permissionDetail']);
Route::patch('permission/{id}',[PermissionController::class,'updatePermission']);
Route::delete('permission/{id}',[PermissionController::class,'deletePermission']);

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
