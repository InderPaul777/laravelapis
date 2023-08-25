<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Jobs\RegisterEmailJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Models\Permission\Permission;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Models\UserPermission;

class UserController extends Controller
{
    public $successStatus = 200;
    public $notFoundStatus = 404;

    /**
     * Create user api
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
        ]);
        if ($validator->fails()) {
            $errors[0] = $validator->errors();
            return response()->json(['errors' => $validator->messages()->all()], 401);
        }
        try {
            $data = [];
            $input = $request->all();
            $data['id'] = Str::uuid()->toString();
            $data['first_name'] = $input['first_name'];
            $data['last_name'] = $input['last_name'];
            $data['middle_name'] = $input['middle_name'];
            $data['email'] = $input['email'];
            $data['verified'] = $input['verified'];
            $data['is_active'] = $input['is_active'];
            $data['force_change_password'] = 0;
            $sendPassword = rand();
            $data['password'] = bcrypt($sendPassword);
            $user = User::create($data);
            if (config('app.SEND_LINK_OR_PASSWORD_IN_MAIL') == 0) {
                $data['sendPassword'] = 'Your password is (' . $sendPassword . ').';
            } else {
                $data['sendPassword'] = 'Your create password link is here ' . config('app.url') . '/api/user/setPassword/' . $data['id'];
            }
            dispatch(new RegisterEmailJob($data));
            return response()->json(['message' => 'Account created successfully, password details sent on mail.'], $this->successStatus);
        } catch (\Exception $e) {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors' => $errors], 401);
        }
    }

    /**
     * Update user api
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all()], 401);
        }
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::where('id', $id)->update($input);
            return response()->json(['message' => 'User details updated successfully.'], $this->successStatus);
        } catch (\Exception $e) {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors' => $errors], 401);
        }
    }

    /**
     * assign permission api
     *
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'permissions' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all()], 401);
        }
        try {
            $input = $request->all();
            $permissions = $input['permissions'];
            // $existing = DB::table('user_permissions')->where('user_id', $input['user_id'])->pluck('permission_id')->toArray();

            $userPermissions = UserPermission::where('user_id', $input['user_id'])->pluck('permission_id')->toArray();
            // dd($userPermissions);
             $deleteThese = array_diff($userPermissions, $permissions);
            if(!empty($deleteThese)){                
                DB::table('user_permissions')->whereIn('permission_id', $deleteThese)->where('user_id', $input['user_id'])->delete();
            }
            $saveThese = array_diff($permissions, $userPermissions);
            $user = JWTAuth::user();
            if (!empty($saveThese)) {
                $fillable = [];
                foreach ($saveThese as $key => $these) {
                    $existPermission = Permission::where('id', $these)->first();
                    if(!empty($existPermission)) {
                        $fillable[$key]['id'] = Str::uuid()->toString();
                        $fillable[$key]['user_id'] = $input['user_id'];
                        $fillable[$key]['permission_id'] = $these;
                        $fillable[$key]['created_by'] = $user->id;
                        $fillable[$key]['created_at'] = date('Y-m-d H:i:s');
                    }
                    $entered = UserPermission::insert($fillable);
                    if ($entered === 0) {
                        return response()->json(['message' => 'Something went wrong, please try again later.'], 500);
                    }
                }
            }
            return response()->json(['message' => 'Permissions assigned successfully.'], $this->successStatus);
        } catch (\Exception $e) {
            echo $e->getMessage();
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors' => $errors], 401);
        }
    }

    /**
     * update permission api
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all()], 401);
        }
        try {
            $input = $request->all();
            $permissions = $input['permissions'];
            // $existing = DB::table('user_permissions')->where('user_id', $input['user_id'])->pluck('permission_id')->toArray();

            $userPermissions = UserPermission::where('user_id', $input['user_id'])->pluck('permission_id')->toArray();
            // dd($userPermissions);
             $deleteThese = array_diff($userPermissions, $permissions);
            if(!empty($deleteThese)){                
                DB::table('user_permissions')->whereIn('permission_id', $deleteThese)->where('user_id', $input['user_id'])->delete();
            }
            $saveThese = array_diff($permissions, $userPermissions);
            $user = JWTAuth::user();
            if (!empty($saveThese)) {
                $fillable = [];
                foreach ($saveThese as $key => $these) {
                    $existPermission = Permission::where('id', $these)->first();
                    if(!empty($existPermission)) {
                        $fillable[$key]['id'] = Str::uuid()->toString();
                        $fillable[$key]['user_id'] = $input['user_id'];
                        $fillable[$key]['permission_id'] = $these;
                        $fillable[$key]['created_by'] = $user->id;
                        $fillable[$key]['updated_at'] = date('Y-m-d H:i:s');
                    }
                    $entered = UserPermission::insert($fillable);
                    if ($entered === 0) {
                        return response()->json(['message' => 'Something went wrong, please try again later.'], 500);
                    }
                }
            }
            return response()->json(['message' => 'Permissions updated successfully.'], $this->successStatus);
        } catch (\Exception $e) {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors' => $errors], 401);
        }
    }

    /**
     * Get all users api
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        $totalRecords = 0;
        $order = 'created_at';
        $orderBy = 'asc';
        $limit = 10;
        $page = 1;
        $search = '';
        if ($request->has('order') && $request->input('order') != '') {
            $order = $request->input('order');
        }
        if ($request->has('orderBy') && $request->input('orderBy') != '') {
            $orderBy = $request->input('orderBy');
        }
        if ($request->has('limit') && $request->input('limit') != '') {
            $limit = $request->input('limit');
        }
        if ($request->has('page') && $request->input('page') != '') {
            $page = $request->input('page');
        }
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
        }
        $page = $page - 1;
        $skip = $page * $limit;
        if ($search != '') {
            $totalRecords = User::where('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%')->count();
            $users = User::where('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%')
                ->orderBy($order, $orderBy)
                ->skip($skip)
                ->take($limit)
                ->get();
        } else {
            $totalRecords = User::count();
            $users = User::orderBy($order, $orderBy)
                ->skip($skip)
                ->take($limit)
                ->get();
        }
        return response()->json(['totalRecords ' => $totalRecords, 'users' => $users], $this->successStatus);
    }

    /**
     * Get single user details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userDetails($id)
    {
        try {
            $User = User::find($id);
            if ($User) {
                return response()->json($User, $this->successStatus);
            } else {
                $errors[0] = 'User detail not found.';
                return response()->json(['errors' => $errors], $this->notFoundStatus);
            }
        } catch (\Exception $e) {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors' => $errors], 401);
        }
    }

    /**
     * delete user api
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $User = User::find($id);
            if ($User) {
                $User->delete();
                return response()->json(['success' => 'User deleted successfully.'], $this->successStatus);
            } else {
                $errors[0] = 'User detail not found.';
                return response()->json(['errors' => $errors], $this->notFoundStatus);
            }
        } catch (\Exception $e) {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors' => $errors], 401);
        }
    }
    public function permissions()
    {
        $data = [];
        $count = 0;
        foreach (Route::getRoutes()->getIterator() as $route) { //$routes[] = $route->uri;
            if (str_contains($route->uri, 'api') && $route->getName() != '') {
                $countPermissions = Permission::where('slug', $route->getName())->count();
                if (0 == $countPermissions) {
                    $name = ucwords(implode(' ', preg_split('/(?=[A-Z])/', $route->getName())));
                    $data[$count]['id'] = Str::uuid()->toString();
                    $data[$count]['name'] = $name;
                    $data[$count]['slug'] = $route->getName();
                    $data[$count]['created_by'] = JWTAuth::toUser(JWTAuth::getToken())->id;
                    $count++;
                }
            }
        }
        if (0 < count($data)) {
            Permission::insert($data);
        }
    }
}