<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Product;
use App\Models\User;
use App\Services\PagarmeRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (Auth::user()) {
            $user = User::with("activeProducts")->find(Auth::user()->id)->toArray();
            $data = $user['activeProducts'];
        } else 
            $data = Product::all()->jsonSerialize();
        return ResponseHelper::success($data, __("Retornando produtos"));
    }

    public function search(Request $request)
    {
        $filters = [];
        $response = "";
        return response()->json(Product::all()->jsonSerialize());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function favorites(){
        $favs = $data = [];
        if($user = Auth::user()){
            $favs = User::find($user->id)->favoriteProducts;

            foreach($favs as $fav) {
                $data[] = $fav->toArray();
            }
        }

        return ResponseHelper::success($data, __("Retornando produtos favoritos"));
    }

    public function setFavorite(Product $product)
    {
        if ($user = Auth::user()) {

            if (empty($product))
                return ResponseHelper::error(__("Produto não encontrado"), Response::HTTP_NOT_FOUND);

            $is_inserted = false;
            $favs = [];
            foreach ($user->favoriteProducts as $fav) {
                if ($fav->id != $product->id)
                    $favs[] = $fav->id;
                else
                    $is_inserted = true;
            }

            if(!$is_inserted)
                $favs[] = $product->id;

            $user->favoriteProducts()->sync($favs);
            $user->save();

            $message = $is_inserted 
            ? __("Produto {$product->id} removido") 
            : __("Produto {$product->id} adicionado");
            return ResponseHelper::success($product, $message);
        }
    }

    public function actives()
    {
        $favs = $data = [];
        if ($user = Auth::user()) {

            $plan = $user->subscriptions;
            if (!$plan) {
                return ResponseHelper::error(__("Usuário não tem assinatura ativa"), Response::HTTP_BAD_REQUEST);
            }
            $favs = User::find($user->id)->activeProducts;

            foreach ($favs as $fav) {
                $data[] = $fav->toArray();
            }
        }

        return ResponseHelper::success($data);
    }

    public function setActive(Product $product)
    {
        $error = "";
        $user = User::with("subscriptions")->find(Auth::user()->id);
        if (!$user) {
            $error = __("Usuário não encontrado");
        }

        $subscription = $user->subscriptions()->where("status", PagarmeRequestService::STATUS_PAID)->first();

        if (!$subscription) {
            return ResponseHelper::error(__("Usuário não tem assinatura ativa"), Response::HTTP_BAD_REQUEST);
        }
        $subscription->toArray();

        if (empty($product))
            return ResponseHelper::error(__("Produto não encontrado"), Response::HTTP_NOT_FOUND);

        $is_inserted = false;
        $actives = [];
        foreach ($user->activeProducts as $active) {
            if ($active->id != $product->id)
                $actives[] = $active->id;
            else
                $is_inserted = true;
        }

        if (!$is_inserted)
            $actives[] = $product->id;

        // Validação de negócio
        if (count($actives) > $subscription->plan->max_bots) {
            return ResponseHelper::error("Número máximo de robôs atingido. " . ($subscription->plan->max_bots == 0 ? "" : "Desative outro robô primeiro."), Response::HTTP_UNAUTHORIZED);
        }

        $user->activeProducts()->sync($actives);
        $user->save();

        $message = $is_inserted 
        ? __("Produto {$product->id} desativado com sucesso") 
        : __("Produto {$product->id} ativado com sucesso");

        return ResponseHelper::success($product, $message);
        
    }
}
