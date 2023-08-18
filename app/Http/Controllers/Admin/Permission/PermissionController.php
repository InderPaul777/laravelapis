<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission\Permission;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new PermissionService(new Permission());
    }

    public function createPermission(Request $request)
    {
        return $this->service->createPermission($request);
    }

    public function getAllPermissions()
    {
        return $this->service->getAllPermissions();
    }

    public function permissionDetail($id)
    {
        return $this->service->permissionDetail($id);
    }
    public function updatePermission(Request $request, $id)
    {
        // dd($request->all());
        return $this->service->updatePermission($request, $id);
    }

    public function deletePermission($id)
    {
        return $this->service->deletePermission($id);
    }
}
