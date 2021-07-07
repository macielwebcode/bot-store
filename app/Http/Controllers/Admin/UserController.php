<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(){
        $users = User::paginate(env("ITEMS_PER_PAGE", 10));

        return ResponseHelper::success($users, __("Retornando Usuários"));
    }

    public function show(User $user){
        return $user 
            ? ResponseHelper::success($user, __("Retornando usuário")) 
            : ResponseHelper::error(__("Usuário não encontrado"), 404);
    }
    
    public function update(Request $request, User $user){

        $data = [];

        if(!empty($request->all()))
            $data = $request->all();

        $validator = Validator::make($data, [
            'street'        => 'string',
            'district'      => 'string',
            'complement'    => 'string',
            'number'        => 'integer',
            'state'         => 'string',
            'city'          => 'string',
            'country'       => 'string',
        ]);
        
        extract($data);
        if($validator->fails()){
            return ResponseHelper::error(__("Campos inválidos"), 500);
        }

        $user->cpf = !empty($cpf) ? $cpf : $user->cpf;
        $user->street = !empty($street) ? $street : $user->street;
        $user->district = !empty($district) ? $district : $user->district;
        $user->complement = !empty($complement) ? $complement : $user->complement;
        $user->number = !empty($number) ? $number : $user->number;
        $user->state = !empty($state) ? $state : $user->state;
        $user->city = !empty($city) ? $city : $user->city;
        
        ResponseHelper::log($data, "Editando user [{$user->id}]");
        
        $user->save();

        return ResponseHelper::success($user, __("Usuário editado"));
    }

    public function toggleActive(User $user){

        if(!$user){
            return ResponseHelper::error(__("Usuário não encontrado"), 404);
        }
        $status = !$user->status;
        $user->status = $status;

        $user->save();
        $message = $status ? __("Usuário ativado com sucesso") : __("Usuário desativado com sucesso");

        return ResponseHelper::success($user, $message);
    }

    public function toggleAdmin(User $user){

        $new_position = !$user->is_admin;
        $user->is_admin = $new_position;

        $user->save();

        $message = $new_position 
        ? __("Usuário é agora um administrador")
        : __("Usuário não é mais um administrador");

        return ResponseHelper::success($user, $message);
    }

}
