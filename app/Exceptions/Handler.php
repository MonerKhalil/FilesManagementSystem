<?php

namespace App\Exceptions;

use App\MyApplication\MyApp;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return JsonResponse
     */
    public function register()
    {
        try {
            $this->renderable(function (NotFoundHttpException $e, $request) {
                return MyApp::Json()->errorHandle("Http","the Route is Not Found .",$e->getCode());
            });
            $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
                return MyApp::Json()->errorHandle("Method",$e->getMessage(),$e->getCode());
            });
            $this->renderable(function (AccessDeniedHttpException $e, $request) {
                return MyApp::Json()->errorHandle("Access",
                    "You do not have permission to access this process .",$e->getCode());
            });
            $this->renderable(function (AuthenticationException $e, $request) {
                return MyApp::Json()->errorHandle("Auth",
                    "The current token is not authenticated .",$e->getCode());
            });
            $this->renderable(function (QueryException $e, $request) {
                return MyApp::Json()->errorHandle("DataBase",$e->getMessage(),$e->getCode());
            });
            $this->renderable(function (ValidationException $e, $request) {
                return MyApp::Json()->errorHandle("Validation",$e->errors(),$e->getCode());
            });
            $this->renderable(function (\Exception $e, $request) {
                return MyApp::Json()->errorHandle("Exception",[$e::class=>$e->getMessage()],$e->getCode());
            });
        }catch (\Exception $e) {
            return MyApp::Json()->errorHandle("Exception",[$e::class=>$e->getMessage()],$e->getCode());
        }
    }
}
