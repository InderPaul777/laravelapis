<?php

namespace App\Services\Permission;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class PermissionService
{
    protected $obj;
    public function __construct($obj)

    {
        $this->obj = $obj;
    }
    public function createPermission($request)
    {
        $response = Validator::make($request->all(), [
            'name' => "required|unique:permissions",
            'slug' => "required|unique:permissions"
        ]);

        if ($response->fails()) {
            return response()->json(["error" => $response->errors()->first()], Response::HTTP_BAD_REQUEST);
        }

        $this->obj->name = $request->name;
        $this->obj->slug = $request->slug;
        $this->obj->id = Str::uuid();
        $this->obj->created_by = Str::uuid();

        if ($this->obj->save()) {
            return response()->json(["success" => "Permission created succesfully"], Response::HTTP_OK);
        }
    }
    public function getAllPermissions()
    {
        $allPermissions = $this->obj->get();
        $data = [];
        $a = 0;
        if (count($allPermissions) == 0) {
            return response()->json(["error" => "noRecordFound"], Response::HTTP_BAD_REQUEST);
        } else {
            foreach ($allPermissions as  $permission) {
                $data[$a]['udid'] = $permission['id'];
                $data[$a]['name'] = $permission['name'];
                $a++;
            }
        }
        return response()->json(["data" => $data]);
    }

    public function permissionDetail($id)
    {
        $permissionDetail = $this->obj->where('id', $id)->first();
        if ($permissionDetail != Null) {
            $data['id'] = $permissionDetail['id'];
            $data['name'] = $permissionDetail['name'];
            return response()->json(["data" => $data]);
        } else {
            return response()->json(["error" => "permission detail not found"], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updatePermission($request, $id)
    {
        $permission = $this->obj->where('id', $id)->first();
        if ($permission != Null) {
            $response = Validator::make($request->all(), [
                'name' => "required|unique:permissions"
            ]);

            if ($response->fails()) {
                return response()->json(["error" => $response->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
            $permission['name'] = $request['name'];
            $permission->save();
            return response()->json(["success" => "permission details updated succesfully."]);
        } else {
            return response()->json(["error" => "permission not found"], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deletePermission($id)
    {
        $permission = $this->obj->where('id', $id)->delete();
        // dd($permission);
        if ($permission == 1) {
            return response()->json(["success" => "permission deleted succesfully."], Response::HTTP_OK);
        } else {
            return response()->json(["error" => "permission not found"], Response::HTTP_BAD_REQUEST);
        }
    }
}