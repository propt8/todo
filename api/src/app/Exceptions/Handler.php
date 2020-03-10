<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $error = $this->errorResponse(
                    'TOKEN_EXPIRED',
                    Response::HTTP_UNAUTHORIZED,
                    [$exception->getMessage()]);
            } else if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $error = $this->errorResponse(
                    'TOKEN_INVALID',
                    Response::HTTP_UNAUTHORIZED,
                    [$exception->getMessage()]);
            } else if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                $error = $this->errorResponse(
                    'TOKEN_BLACKLISTED',
                    Response::HTTP_UNAUTHORIZED,
                    [$exception->getMessage()]);
            }
            if ($exception->getMessage() === 'Token not provided') {
                $error = $this->errorResponse(
                    'TOKEN_NOT_PROVIDED',
                    Response::HTTP_UNAUTHORIZED,
                    [$exception->getMessage()]);
            }
        } else if ($exception instanceof ApiException) {
            $error = $this->errorResponse(
                $exception->getMessage(),
                $exception->statusCode,
                $exception->error
            );
        } elseif ($exception instanceof ValidationException) {
            $error = $this->errorResponse(
                'THE_GIVEN_DATA_WAS_INVALID',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->errors()
            );
        } else if ($exception instanceof ModelNotFoundException) {
            $error = $this->errorResponse(
                'DATA_NOT_FOUND',
                Response::HTTP_NOT_FOUND,
                [$exception->getMessage()]
            );
        } else if ($exception instanceof RouteNotFoundException) {
            $error = $this->errorResponse(
                'ROUTE_NOT_FOUND',
                Response::HTTP_NOT_FOUND,
                [$exception->getMessage()]
            );
        } else {
            $error = $this->errorResponse(
                'INTERNAL_ERROR',
                Response::HTTP_BAD_REQUEST,
                [$exception->getMessage()]
            );
        }

        if (!empty($error)) {
            return $error;
        }

        return parent::render($request, $exception);
    }

    /**
     * @param string $title
     * @param int $code
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    private function errorResponse(
        string $title,
        int $code = Response::HTTP_BAD_REQUEST,
        ?array $errors = []
    )
    {
        // TODO: response transformer
        return response()->json([
            'errorCode' => $title,
            'errors' => app()->environment('development') ? $errors : null
        ], $code);
    }
}
