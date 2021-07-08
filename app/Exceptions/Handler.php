<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $data = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
    
            ResponseHelper::log($data, "___ERROR___");
    
        });
    }

    public function render($request, Throwable $e)
    {

        $data = [
            'code'      => $e->getCode(),
            'message'   => $e->getMessage(),
            'target'    => $request->path(),
            'trace'     => $e->getTraceAsString()
        ];

        ResponseHelper::log($data, "___DEBUG___");

        list($message, $http_code) = $this->getErrorMessageAndHttpCode(get_class($e));

        return ResponseHelper::error(
            $message, 
            $http_code, 
            []
        );
    }

    private function getErrorMessageAndHttpCode($error = ""){

        // Gets the messages and HTTP codes from 
        $types = config("errors.types", []);

        $message = __("Um erro ocorreu durante a execução. Tente mais tarde");
        $http_error = Response::HTTP_INTERNAL_SERVER_ERROR;

        foreach($types as $type => $item){
            if($type == $error){
                $message = collect(collect($item)->get('message', $message))->get(env("APP_LANGUAGE", 'pt_br'), $message);
                $http_error = collect($item)->get('code', $http_error);
                
            }
        }
        return [$message, $http_error];
    }
}
