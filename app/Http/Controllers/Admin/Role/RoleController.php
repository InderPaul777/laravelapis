<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Services\Role\RoleService;
use App\Models\Role\Role;
use Illuminate\Validation\Rules\Exists;
use Response;

class RoleController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new RoleService(new Role);
    }

    public function createrole(Request $request)
    {
        return $this->service->createRole($request);
    }

    public function getAllRoles()
    {
        return $this->service->getAllRoles();
    }

    public function roleDetail($id)
    {
        return $this->service->roleDetail($id);
    }
    public function updateRole(Request $request, $id)
    {
        // dd($request->all());
        return $this->service->updateRole($request, $id);
    }

    public function deleteRole($id)
    {
        return $this->service->deleteRole($id);
    }
}