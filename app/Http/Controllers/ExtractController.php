<?php

namespace App\Http\Controllers;

use App\Models\Extract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $extracts = $user->extracts->toArray();

        return $this->success($extracts);
    }
    /**
     * Display a search of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // if($request->get(""))
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
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function show(Extract $extract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function edit(Extract $extract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extract $extract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Extract $extract)
    {
        //
    }
}
