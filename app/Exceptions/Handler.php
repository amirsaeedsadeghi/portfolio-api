<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP JSON response.
     *
     * This method standardizes API error responses by mapping common
     * exception types (e.g., ValidationException, AuthenticationException,
     * AuthorizationException, ModelNotFoundException) to structured JSON.
     *
     * Each response includes:
     * - status: false
     * - message: human-readable message
     * - error: {
     *     code: machine-readable error code (e.g., VALIDATION_ERROR),
     *     message: same human-readable message,
     *     details: optional array of error details
     *   }
     * - meta: { request_id: string }
     *
     * @param \Illuminate\Http\Request $request The current HTTP request instance.
     * @param \Throwable $e The exception that was thrown during request handling.
     * @return \Symfony\Component\HttpFoundation\Response A JSON-formatted HTTP response.
     */
    public function render($request, Throwable $e)
    {
        $rid = $request->headers->get('X-Request-Id')
            ?? (app()->bound('request_id') ? app('request_id') : (string) \Illuminate\Support\Str::uuid());

        $json = function (string $code, string $message, int $status, array $details = []) use ($rid) {
            return response()->json([
                'status'  => false,
                'message' => $message,
                'error'   => [
                    'code'    => $code,
                    'message' => $message,
                    'details' => empty($details) ? null : $details,
                ],
                'meta'    => ['request_id' => $rid],
            ], $status);
        };

        if ($e instanceof ValidationException) {
            $errors = collect($e->errors())->mapWithKeys(fn($msgs, $key) => [Str::camel($key) => $msgs])->all();
            return $json('VALIDATION_ERROR', 'The given data was invalid.', 422, $errors);
        }
        if ($e instanceof AuthenticationException) return $json('UNAUTHENTICATED', 'Unauthenticated.', 401);
        if ($e instanceof AuthorizationException)  return $json('FORBIDDEN', 'Forbidden.', 403);
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException)
            return $json('NOT_FOUND', 'Resource not found.', 404);

        if ($e instanceof MethodNotAllowedHttpException) {
            return $json('METHOD_NOT_ALLOWED', 'Method not allowed.', 405);
        }
        if ($e instanceof ThrottleRequestsException) return $json('RATE_LIMITED', 'Too many requests.', 429);

        report($e);
        $msg = config('app.debug') ? $e->getMessage() : 'Internal server error.';
        return $json('INTERNAL_ERROR', $msg, 500);
    }
}
