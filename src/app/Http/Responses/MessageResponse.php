<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Support\Facades\Context;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class MessageResponse implements Responsable
{
    /**
     * Constructs a new MessageResponse instance.
     *
     * @param string $message
     * @param int $status
     */
    public function __construct(
        private string $message,
        private int $status,
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
            'data' => [
                'message' => $this->message,
            ],
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
