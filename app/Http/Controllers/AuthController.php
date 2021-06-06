<?php

namespace App\Http\Controllers;

use App\Http\Traits\apiResponser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use apiResponser;

    public function register(Request $request){
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'password' => Hash::make($request->input('password')),
            'status' => 1,
            'balance' => 0,

        ]);
        
        return $this->success([$user]);
    }


    public function login(Request $request){
        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error("Credenciais inválidas", Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie("jwt", $token, 60 * 24); //1 dia

        return $this->success([], 'Sucesso')->withCookie($cookie);
    }

    public function user() {
        return Auth::user();
    }

    public function logout(Request $request){
        $cookie = Cookie::forget("jwt");
        return $this->success([], "Sucesso")->withCookie($cookie);
    }
}
