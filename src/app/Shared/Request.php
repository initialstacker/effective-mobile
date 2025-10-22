<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    abstract public function rules(): array;

    /**
     * Handle failed validation.
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $requestId = Context::get(key: 'request_id');
        $timestamp = Context::get(key: 'timestamp');

        $response = new JsonResponse(data: [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'result' => [
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ],
            'metadata' => [
                'request_id' => $requestId,
                'timestamp' => $timestamp,
            ],
        ], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        
        throw new HttpResponseException(response: $response);
    }
}
