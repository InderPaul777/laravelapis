<?php

namespace App\Services\Role;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class RoleService
{
    protected $obj;
    public function __construct($obj)

    {
        $this->obj = $obj;
    }
    public function createRole($request)
    {
        $response = Validator::make($request->all(), [
            'name' => "required|unique:roles"
        ]);

        if ($response->fails()) {
            return response()->json(["message" => $response->errors()->first()], Response::HTTP_BAD_REQUEST);
        }

        $this->obj->name = $request->name;
        $this->obj->id = Str::uuid();
        $this->obj->created_by = Str::uuid();

        if ($this->obj->save()) {
            return response()->json(["message" => "Role created succesfully"], Response::HTTP_OK);
        }
    }
    public function getAllRoles()
    {
        $allRoles = $this->obj->get();
        $data = [];
        $a = 0;
        if (count($allRoles) == 0) {
            return response()->json(["message" => "noRecordFound"], Response::HTTP_BAD_REQUEST);
        } else {
            foreach ($allRoles as  $role) {
                $data[$a]['udid'] = $role['id'];
                $data[$a]['name'] = $role['name'];
                $a++;
            }
        }
        return array("data" => $data, "message" => "",);
    }

    public function roleDetail($id)
    {
        $roleDetail = $this->obj->where('id', $id)->first();
        if ($roleDetail != Null) {
            $data['id'] = $roleDetail['id'];
            $data['name'] = $roleDetail['name'];
            return response()->json(["data" => $data]);
        } else {
            return response()->json(["message" => "role detail not found"], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateRole($request, $id)
    {
        // dd($request);
        $response = Validator::make($request->all(), [
            'name' => "required|unique:roles"
        ]);

        if ($response->fails()) {
            return response()->json(["message" => $response->errors()->first()], Response::HTTP_BAD_REQUEST);
        }
        $role = $this->obj->where('id', $id)->first();
        if ($role != Null) {
            $role['name'] = $request['name'];
            $role->save();
            return response()->json([ "message"=>"Role details updated succesfully."]);
        } else {
            return response()->json(["message" => "role not found"], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteRole($id)
    {
        $role = $this->obj->where('id', $id)->delete();
        // dd($role);
        if($role==1){
            return response()->json([ "message"=>"Role deleted succesfully."], Response::HTTP_OK);
        }else
        {
            return response()->json(["message" => "role not found"], Response::HTTP_BAD_REQUEST);
        }
    }
}
