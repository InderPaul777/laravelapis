<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\Permission\PermissionController;
use App\Http\Controllers\FrontController;
use App\Http\Middleware\CheckPermission;

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

Route::get('/permissions-update', [UserController::class, 'permissions']);
Route::post('/user', [UserController::class, 'user'])->name('createUser');
Route::get('/users', [UserController::class, 'users'])->name('getUsers');
Route::get('/user/{id}', [UserController::class, 'userDetails'])->name('getUserDetails');
Route::patch('/user/{id}', [UserController::class, 'update'])->name('updateUser');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('deleteUser');

//Auth::routes();
Route::middleware(['auth.jwt'])->group(function () {
    Route::post('/user/assignPermissions', [UserController::class, 'assignPermissions'])->name('assignPermissions')->middleware('check-permission');
    Route::post('/user/updatePermissions', [UserController::class, 'updatePermissions'])->name('updatePermissions')->middleware('check-permission');
});

Route::post('/user/registration', [FrontController::class, 'registration'])->name('registerUser');
Route::post('/user/login', [FrontController::class, 'login'])->name('loginUser');
Route::get('/refreshToken', [FrontController::class, 'refreshToken'])->name('refreshToken');
Route::post('/user/forgotPassword', [FrontController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/user/resetPassword', [FrontController::class, 'resetPassword'])->name('resetPassword');
Route::post('/user/changePassword/{id}', [FrontController::class, 'changePassword'])->name('changePassword');

Route::post('role', [RoleController::class,'createRole'])->name('role')->name('createRole');
Route::get('roles', [RoleController::class, 'getAllRoles'])->name('getRoles');
Route::get('role/{id}',[RoleController::class, 'roleDetail'])->name('getRoleDetails');
Route::patch('role/{id}',[RoleController::class,'updateRole'])->name('updateRole');
Route::delete('role/{id}',[RoleController::class,'deleteRole'])->name('deleteRole');

Route::post('permission', [PermissionController::class,'createPermission'])->name('createPermission');
Route::get('permissions', [PermissionController::class, 'getAllPermissions'])->name('getPermissions');
Route::get('permission/{id}',[PermissionController::class, 'permissionDetail'])->name('getPermissionDetails');
Route::patch('permission/{id}',[PermissionController::class,'updatePermission'])->name('updatePermission');
Route::delete('permission/{id}',[PermissionController::class,'deletePermission'])->name('deletePermission');

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
