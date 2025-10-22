<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};

final class ResourceResponse implements Responsable
{
    /**
     * Constructs a new ResourceResponse instance.
     *
     * @param mixed $data
     * @param int $status
     */
    public function __construct(
        private mixed $data,
        private int $status = Response::HTTP_OK
    ) {}

    /**
     * Converts the response to a JSON response.
     *
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $requestId = Context::get(key: 'request_id');
        $timestamp = Context::get(key: 'timestamp');

        $data = [
            'status' => $this->status,
            'data' => $this->data,
            'metadata' => [
                'request_id' => $requestId,
                'timestamp' => $timestamp
            ],
        ];

        return new JsonResponse(
            data: $data, status: $this->status
        );
    }
}
