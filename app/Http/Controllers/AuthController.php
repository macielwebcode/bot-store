<?php

namespace App\Http\Controllers;

use App\Http\Traits\apiResponser;
use App\Models\User;
use App\Services\PagarmeRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use apiResponser;

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name"              => 'required|string',
            "email"             => 'required|string',
            "cpf"               => 'required|string|max:11',
            "phone"             => 'required|string',
            "password"          => 'required|string',
        ], [
            'name' => "nome",
            'phone' => "celular",
            'password' => "senha",
        ]);

        if ($validator->fails()) {
            return $this->error($validator->getMessageBag()->first(), 500);
        } else {

            // Waits success for commit
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'cpf' => $request->input('cpf'),
                'phone' => substr($request->input('phone'), 0, 11),
                'password' => Hash::make($request->input('password')),
                'status' => 1,
                'balance' => 0,

            ]);

            $operation = new PagarmeRequestService;
            $result_customer = $operation->createCustomer(
                $user->name,
                $user->email,
                $user->id,
                [$user->phone],
                [
                    (object)[
                        'type'   => 'cpf',
                        'number' => $user->cpf
                    ],
                ]
            );

            if (isset($result_customer->errors)) {
                DB::rollBack();
                return $this->error(__("Falha ao resgistrar usuário"), 500);
            }

            $user->pagarme_id = $result_customer->id;
            $user->save();

            DB::commit();

            return $this->success($user, __("Retornando usuário"));
        }
    }


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error(__("Credenciais inválidas"), Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie("jwt", $token, 60 * 24); //1 dia

        return $this->success($user, __('Sucesso'))->withCookie($cookie);
    }

    public function user()
    {
        return $this->success(Auth::user(), __("Retornando usuário"));
    }

    public function forgot(Request $request)
    {
        if(Auth::user()) {
            return $this->error(__("Faça logout antes de realizar essa operação"), 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return $this->error(__("Campos inválidos"), 500);
        }

        $email = $request->input("email");

        $user = User::where("email", $email)->first();
        if (!$user) {
            return $this->error(__("Usuário não encontrado"), 404);
        }

        Password::sendResetLink($request->all());

        return $this->success(null, __("Email de recuperação enviado"));
    }

    public function reset(Request $request)
    {

        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return $this->error(__("Token inválido"), 400);
        }

        return $this->success(null, __("Senha alterada com sucesso"));
    }

    public function logout(Request $request)
    {
        if(Auth::check()) {
            return $this->error(__("Faça logout antes de realizar essa operação"), 403);
        }

        $cookie = Cookie::forget("jwt");
        return $this->success([], __("Logout feito com sucesso"))->withCookie($cookie);
    }
}
