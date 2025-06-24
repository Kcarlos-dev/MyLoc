<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class LoginController extends Controller
{
    public function RegisterUser(Request $request)
    {
        try {
            ['name' => $name, 'email' => $email,'phone'=> $phone, 'password' => $password] = $request->only(['name', 'email','phone', 'password']);
            if(strlen(trim($name)) <= 0
            || strlen(trim($email)) <= 0
            || strlen(trim($password)) <= 0
            || strlen(trim($phone)) <= 0
            ){
                return response()->json(['msg' => 'Need of data'], 400);
            };
            $passwordHash = password_hash($password,PASSWORD_DEFAULT);
            $user = new User();
            $user->name = $name;
            $user->user_type = 'User';
            $user->email = $email;
            $user->phone = $phone;
            $user->password = $passwordHash;
            $user->save();

            $token = JWTAuth::fromUser($user);


            return response()->json(['msg' => 'User registered successfully','token' => $token], 201);

        } catch (\Exception $e) {
            Log::error('Erro no metodo RegisterUser:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

}
    public function LoginUser(Request $request){
        try {
            ['email' => $email, 'password' => $password] = $request->only('email', 'password');
            if(strlen(trim($email)) <= 0
            || strlen(trim($password)) <= 0){
                return response()->json(['error' => 'Need email or password'], 401);
            }

            if(! $token = JWTAuth::attempt(['email' => $email, 'password' => $password])){
                Log::info($token);
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            return response()->json(['msg' => 'User return successfully','token' => $token],200);

        } catch (\Exception $e) {
            Log::error('Erro no metodo LoginUser:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }


    }
    public function AuthUser(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (! $user) {
                return response()->json([
                    'error' => 'User not found'
                ], 404);
            }

            return response()->json([
                'user' => $user
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Invalid or expired token'
            ], 401);
        }
    }
}
