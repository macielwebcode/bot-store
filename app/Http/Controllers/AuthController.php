<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(Request $request){
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'password' => Hash::make($request->input('password')),
            'status' => 1,
            'balance' => 0,

        ]);
        
        return $user;
    }


    public function login(Request $request){
        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response([
                'message' => "Credenciais inválidas"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie("jwt", $token, 60 * 24); //1 dia

        return response([
            'message' => 'Sucesso',
            // "token" => $token
        ])->withCookie($cookie);
    }

    public function user() {
        return Auth::user();
    }

    public function logout(Request $request){
        $cookie = Cookie::forget("jwt");
        return response([
            'message' => "Sucesso",
        ])->withCookie($cookie);
    }
}