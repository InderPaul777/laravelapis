<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Role\RoleController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('role', [RoleController::class,'createRole'])->name('role');
Route::get('allroles', [RoleController::class, 'getAllRoles']);
Route::get('roledetail/{id}',[RoleController::class, 'roleDetail']);
Route::patch('updaterole/{id}',[RoleController::class,'updateRole']);
Route::delete('deleterole/{id}',[RoleController::class,'deleteRole']);
