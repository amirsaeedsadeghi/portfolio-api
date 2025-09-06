<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

/**
 * Trait ApiResponse
 *
 * Provides a standardized JSON response structure for API controllers.
 */
trait ApiResponse
{
    /**
     * Resolve or generate the current request ID.
     *
     * Priority:
     * 1. X-Request-Id header from the incoming request
     * 2. Bound "request_id" in the service container (set by middleware)
     * 3. Generate a new UUID (fallback)
     *
     * @return string The request ID used for tracing/log correlation.
     */
    private function rid(): ?string
    {
        if (request()->headers->has('X-Request-Id')) {
            return request()->headers->get('X-Request-Id');
        }


        if (app()->bound('request_id')) {
            return app('request_id');
        }

        return (string) \Illuminate\Support\Str::uuid();
    }
    /**
     * Return a success JSON response.
     *
     * @param mixed $data The payload to be returned (can be array, object, collection, etc.)
     * @param string $message A message describing the response
     * @param int $code HTTP status code (default: 200)
     * @return JsonResponse
     *
     * @example success(['token' => 'abc123'], 'Login successful')
     */
    public function success(mixed $data = null, string $message = 'OK', int $code = 200): JsonResponse
    {
        if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            $original = $data->toResponse(request())->getData(true);

            $meta = $original['meta'] ?? null;
            $meta = is_array($meta) ? $meta + ['request_id' => $this->rid()] : ['request_id' => $this->rid()];
            $links = $original['links'] ?? null;
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $original['data'] ?? null,
                'meta' => $meta,
                'links' => $links
            ], $code);
        }
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'meta' => ['request_id' => $this->rid()]
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message The error message
     * @param int $code HTTP status code (default: 400)
     * @param mixed $errors Optional additional error payload (e.g., validation errors)
     * @return JsonResponse
     *
     * @example error('Validation failed', 422, ['email' => ['Email is required']])
     */
    public function error(string $message = 'Somthing went wrong.', int $code = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
            'meta' => ['request_id' => $this->rid()]
        ];
        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }
        return response()->json($response, $code);
    }

    /**
     * Return a 204 No Content response.
     *
     * @return Response
     */
    public function noContent(): Response
    {
        return response()->noContent();
    }
}
