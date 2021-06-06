<?php

namespace App\Http\Controllers;

use App\Http\Traits\apiResponser;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use apiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Product::all()->jsonSerialize());
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

        return $this->success($data);
    }

    public function setFavorite(Request $request){
        if($user = Auth::user()){

            $product = Product::find($request->input('product_id'));

            if(empty($product))
                return $this->error("Produto não encontrado", Response::HTTP_NOT_FOUND);
            
            $is_inserted = false;
            $favs = [];
            foreach($user->favoriteProducts as $fav){
                if($fav->id != $product->id)
                    $favs[] = $fav->id;
                else
                    $is_inserted = true;
            }

            if(!$is_inserted)
                $favs[] = $product->id;

            $user->favoriteProducts()->sync($favs);
            $user->save();

            return $this->success($product);
        }
    }
}
