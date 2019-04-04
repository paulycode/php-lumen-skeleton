<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof HttpResponseException) {
            return $response;
        }

        if ($e instanceof AuthorizationException) {
            return new Response(
                'nginx will override this message',
                Response::HTTP_FORBIDDEN
            );
        }

        if ($e instanceof ModelNotFoundException) {
            return new Response(
                'nginx will override this message',
                Response::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof ValidationException) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'reasons' => $e->errors(),
            ], $e->status);
        }

        if ($e instanceof HttpException) {
            switch ($e->getStatusCode()) {
                case Response::HTTP_BAD_REQUEST:
                case Response::HTTP_UNAUTHORIZED:
                case Response::HTTP_FORBIDDEN:
                case Response::HTTP_NOT_FOUND:
                case Response::HTTP_INTERNAL_SERVER_ERROR:
                case Response::HTTP_BAD_GATEWAY:
                case Response::HTTP_SERVICE_UNAVAILABLE:
                case Response::HTTP_GATEWAY_TIMEOUT:
                    return new Response(
                        'nginx will override this message',
                        $e->getStatusCode(),
                        $e->getHeaders()
                    );
            }
        }

        return new JsonResponse([
            'message' => $e->getMessage() ?: Response::$statusTexts[$status],
        ], 500, method_exists($e, 'getHeaders') ? $e->getHeaders() : []);
    }
}
