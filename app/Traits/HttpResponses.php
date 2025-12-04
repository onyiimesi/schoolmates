<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait HttpResponses
{
    /**
     * @param mixed $data
     * @param ?string $message
     * @param int $code
     * @return JsonResponse
     */
	protected function success(mixed $data, $message = null, $code = Response::HTTP_OK): JsonResponse
    {
		return new JsonResponse([
			'status' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}

    /**
     * @param mixed $data
     * @param ?string $message
     * @param int $code
     * @return JsonResponse
     */
	protected function error(mixed $data, $message = null, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
		return new JsonResponse([
			'status' => false,
			'message' => $message,
			'data' => $data
		], $code);
	}

    /**
     * @param mixed $collection
     * @param ?string $message
     * @param int $code
     * @param ?array $extraMeta
     * @return JsonResponse
     */
    protected function withPagination(mixed $collection, $message = null, $code = Response::HTTP_OK, ?array $extraMeta = []): JsonResponse
    {
        return new JsonResponse([
            'status' => true,
            'message' => $message,
            'data' => $collection->items(),
            'pagination' => [
                'current_page' => $collection->currentPage(),
                'last_page' => $collection->lastPage(),
                'per_page' => $collection->perPage(),
                'total' => $collection->total(),
                'prev_page_url' => $collection->previousPageUrl(),
                'next_page_url' => $collection->nextPageUrl(),
            ],
            'meta' => $extraMeta ?? [],
        ], $code);
    }
}
