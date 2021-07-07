<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // if (! $request->expectsJson()) {
        //     return json_encode(["success" => false]);//route('login');
        // }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $request->headers->set('Accept', 'application/json');

        if($jwt = $request->cookie("jwt")){
            $request->headers->set("Authorization", "Bearer " . $jwt);
        }
        $this->authenticate($request, $guards);

        if(collect(Auth::user())->get('status', 0) == 0){
            return ResponseHelper::error(__("Acesso bloqueado"), Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new \Illuminate\Auth\AuthenticationException;
    }
}
