<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use apiResponser;

    public function index(){
        $users = User::paginate(env("ITEMS_PER_PAGE", 10));

        return $this->success($users, __("Retornando Usuários"));
    }

    public function show(User $user){
        return $user 
            ? $this->success($user, __("Retornando usuário")) 
            : $this->error(__("Usuário não encontrado"), 404);
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
            return $this->error(__("Campos inválidos"), 500);
        }

        $user->cpf = !empty($cpf) ? $cpf : $user->cpf;
        $user->street = !empty($street) ? $street : $user->street;
        $user->district = !empty($district) ? $district : $user->district;
        $user->complement = !empty($complement) ? $complement : $user->complement;
        $user->number = !empty($number) ? $number : $user->number;
        $user->state = !empty($state) ? $state : $user->state;
        $user->city = !empty($city) ? $city : $user->city;
        
        Log::info("Editando user [ {$user->id} ]...");
        Log::info(json_encode($data));
        
        $user->save();

        return $this->success($user, __("Usuário editado"));
    }

    public function toggleActive(User $user){

        // $user = User::find($userId);

        if(!$user){
            return $this->error(__("Usuário não encontrado"), 404);
        }
        $status = !$user->status;
        $user->status = $status;

        $user->save();
        $message = $status ? __("Usuário ativado com sucesso") : __("Usuário desativado com sucesso");

        return $this->success($user, $message);
    }

}
