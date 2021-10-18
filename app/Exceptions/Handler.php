<?php

namespace App\Exceptions;

use Log;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if($exception->getCode() !== 0) {
            Log::critical(
                "code=[{$exception->getCode()}] 
                file=[{$exception->getFile()}] 
                line=[{$exception->getLine()}] 
                message=[{$exception->getMessage()}]"
            );
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof NotFoundHttpException) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->unauthorized($request, $exception);/*
            return response()->json([
                'success' => false,
                'code' => 403,
                'message' => "Unauthorized",
                'data' => [
                    'errors' => [
                        'unauthorized' => "No posee los permisos suficientes para realizar esta acción."
                    ]
                ]
            ], 403);*/
        }
        
            //return $exception->getCode()."asdas";
        

        Log::info("EXCEPCIÓN SIN VALIDAR DE TIPO: " . get_class($exception));
        Log::info("Si esta excepción no afecta comportamiento o es una excepción generada intencionalmente, ignorar");
        if(config('app.debug')){
            return parent::render($request, $exception);
        }

        return response('Sorry something went wrong :(', 500);
        
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

    protected function unauthorized($request, AuthorizationException $exception)
    {
        $url = explode("/", $request->path());
        if ($url[0] == "api" && $url[1] !== "oauth") {
            return response()->json([
                'success' => false,
                'code' => 403,
                'message' => "Unauthorized",
                'data' => [
                    'errors' => [
                        'unauthorized' => "No posee los permisos suficientes para realizar esta acción."
                    ]
                ]
            ], 403);
        }

        return parent::render($request, $exception);
    }

    private function isFrontEnd($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
