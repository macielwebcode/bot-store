<?php

namespace App\Http\Controllers;

use App\Http\Traits\apiResponser;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use apiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = User::with("notifications")->find(Auth::user()->id)->toArray();

        $data = $user['notifications'];

        return $this->success($data, __("Retornando notificações do cliente"));
        
    }

    public function unread()
    { 
        $user = User::with("notifications")->find(Auth::user()->id);

        $notifications = $user->notifications()->where("is_read", false)->get();

        return $this->success($notifications, __("Retornando notificações do cliente"));
    }

    public function setRead(Request $request, Notification $notification)
    {
        $notification->is_read = true;
        $notification->save();

        return $this->success($notification, __("Marcando notificação como lida"));
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
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
