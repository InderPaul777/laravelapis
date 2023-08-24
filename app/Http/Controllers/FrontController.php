<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User\User;
use App\Models\ResetCodePassword;
use Illuminate\Support\Str;
use App\Mail\FrontRegisterMail;
use App\Mail\SendCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class FrontController extends Controller
{
    public $successStatus = 200;
    public $notFoundStatus = 404;

    public function __construct()
    {
      //  $this->middleware('auth:api', ['except' => ['login','registration', 'forgotPassword', 'resetPassword', 'refreshToken', 'changePassword', 'refreshToken']]);
    }

    /** 
     * Register user api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function registration(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $errors[0] = $validator->errors();
            return response()->json(['errors'=>$validator->messages()->all()], 401);    
        }
        try
        {
            $data = []; 
            $input = $request->all();
            $data['id'] = Str::uuid()->toString();
            $data['first_name'] = $input['first_name'];
            $data['last_name'] = $input['last_name'];
            if(isset($data['middle_name'])){
            $data['middle_name'] = $input['middle_name'];
            }
            $data['email'] = $input['email'];
            $data['verified'] = 0;
            $data['is_active'] = 0;
            $data['password'] = bcrypt($input['password']);
            $user = User::create($data);
            Mail::to($input['email'])->send(new FrontRegisterMail($data)); // Send link to
            return response()->json(['message'=>'Account created successfully, password details sent on mail.'], $this->successStatus);
        }
        catch(\Exception $e)
        {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors'=>$errors], 401); 
        }
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();       
            return response()->json(['errors'=>$validator->messages()->all()], 401);    
        }
        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);
        if(!$token) {
            return response()->json([               
                'errors' => array('Invalid Username/password'),
            ], 401);
        }else{
            // Validate Veriied/Is_active/force_change_password
            return response()->json(['expires_in' => auth()->factory()->getTTL() * 60,'token' => $token]);
        }
      
        
    }

    /** 
     * refresh token api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function refreshToken()
    {
        $token = '';
        $token = $token ? $token : Auth::getToken();
        if(!$token){
            $errors[0] = 'Token not provided.';
            return response()->json(['errors'=>$errors], 401);
        }
        try{
            return $this->respondWithToken(auth()->refresh());
        }catch(TokenInvalidException $e){
            $errors[0] = 'This token is invalid.';
            return response()->json(['errors'=>$errors], 401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        try{
            return response()->json([
                'token' => $token,               
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }catch(TokenInvalidException $e){
            $errors[0] = 'This token is invalid.';
            return response()->json(['errors'=>$errors], 401);
        }
    }

    /** 
     * Forgot password api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function forgotPassword(Request $request){ 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) { 
            return response()->json(['errors'=>$validator->errors()->all()], 401);            
        }
        try
        {
            ResetCodePassword::where('email', $request->email)->delete();
            $data['email'] = $request->email;
            $data['code'] = mt_rand(100000, 999999);
            $codeData = ResetCodePassword::create($data);
            Mail::to($request->email)->send(new SendCodeMail($codeData->code));
            return response(['message' => 'Password reset code is sent on your email.'], 200);
        }
        catch(Exception $e)
        {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors'=>$errors], 401); 
        }
    }

     /** 
     * Reset password api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function resetPassword(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        try
        {
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
            if ($passwordReset->created_at > now()->addHour()) {
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }
            $user = User::firstWhere('email', $passwordReset->email);
            $user->update(bcrypt($request->only('password')));
            $passwordReset->delete();
            return response(['message' =>'password has been successfully reset'], 200);
        }
        catch(Exception $e)
        {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors'=>$errors], 401); 
        }
    }

    /** 
    * Change password api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function changePassword(Request $request, $id) 
    { 
        $validator = Validator::make($request->all(), [
            'password' => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        try
        {
            $input = $request->all(); 
            $data['password'] = bcrypt($input['password']);
            $user = User::where('id', $id)->update($data);
            return response()->json(['message'=>'Password updated successfully.'], $this->successStatus); 
        }
        catch(Exception $e)
        {
            $errors[0] = 'Something went wrong, please try again later.';
            return response()->json(['errors'=>$errors], 401); 
        }
    }

}
