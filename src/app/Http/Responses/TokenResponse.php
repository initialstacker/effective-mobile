<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};

final class TokenResponse implements Responsable
{
    /**
     * Constructs a new TokenResponse instance.
     *
     * @param string $message
     * @param int $status
     * @param string|null $token
     */
    public function __construct(
        private string $message,
        private int $status = Response::HTTP_OK,
        private ?string $token = null,
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
        
        $response = [
            'status' => $this->status,
            'data' => [
                'message' => $this->message
            ],
            'metadata' => [
                'request_id' => $requestId,
                'timestamp' => $timestamp,
            ],
        ];

        if ($this->token !== null) {
            $response['data']['access_token'] = $this->token;
            $response['data']['token_type'] = __('Bearer');
        }

        return new JsonResponse(
            data: $response, status: $this->status
        );
    }
}
