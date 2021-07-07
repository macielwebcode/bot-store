<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $cards = $user->cards();

        $data = [];
        foreach($cards as $c){
            $data[] = $c->toJson();
        }

        return ResponseHelper::success($data);
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
     * @param  \App\Models\UserCard  $userCard
     * @return \Illuminate\Http\Response
     */
    public function show(UserCard $userCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserCard  $userCard
     * @return \Illuminate\Http\Response
     */
    public function edit(UserCard $userCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserCard  $userCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserCard $userCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserCard  $userCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserCard $userCard)
    {
        //
    }
}
