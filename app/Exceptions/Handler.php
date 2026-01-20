<?php

namespace App\Exceptions;

use App\Traits\HttpResponses;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HttpResponses;

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
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (config('app.env') === 'production') {
                Log::channel('slack')->error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'Line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'url' => request()->fullUrl(),
                    'input' => request()->all(),
                ]);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->json()) {
                return $this->success(null, "Not Found", 404);
            }
            throw $e;
        });

        $this->shouldRenderJsonWhen(function ($request) {
            return $request->is('api/*') || $request->expectsJson();
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json(['error' => "Too many attempts. Wait for a while and try again."], 429);
        }

        return parent::render($request, $exception);
    }
}
