<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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

         /*Log::info([
                "name" => $name,
                "email" => $email,
                "password"=> $passwordHash
            ]);*/

            return response()->json(['msg' => 'User registered successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Erro no metodo RegisterUser:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

}
}
