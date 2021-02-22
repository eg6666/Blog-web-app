<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'bail|required|exists:users|max:255|regex:/@fti\.edu\.al$/',
            'password' => 'bail|required|regex:/^.*(?=.{8,30})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!$#%.,^&@]).*$/',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (($token=auth()->attempt($validator->validated())) && User::where('email', $request->email)->value('access')==1) {
            return $this->createNewToken($token);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
        }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'fname' => 'bail|required|max:20',
            'lname' => 'bail|required|max:20',
            'gender' => 'bail|required|max:1',
            'birthday' => 'bail|required|date_format:"Y-m-d"|before:tomorrow',
            'email' => 'bail|required|unique:users|max:255|regex:/@fti\.edu\.al$/',
            'password' => 'bail|required|regex:/^.*(?=.{8,30})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!$#%.,^&@]).*$/',
            'user_type_id' => 'bail|required|min:1|exists:user_types,id',
            'department_id' => 'bail|required|min:1|exists:departments,id',
            'access' => 'boolean'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => Hash::make($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
