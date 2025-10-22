<?php declare(strict_types=1);

namespace App\Http\Requests;

use App\Shared\Request;
use Illuminate\Validation\Rules\Password;

final class TaskRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $commonRules = [
            'title' => ['bail', 'required', 'string', 'min:2', 'max:80'],
            'description' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
            'status' => ['bail', 'required', 'boolean'],
        ];

        return match ($this->method()) {
            'POST' => $commonRules,
            'PUT' => [
                ...['id' => ['bail', 'required', 'integer', 'exists:tasks,id']],
                ...$commonRules,
            ],
            default => []
        };
    }

    /**
     * Prepare and sanitize input data before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(input: [
            'id' => $this->route('id')
        ]);

        if ($this->has(key: 'status')) {
            $this->merge(input: [
                'status' => filter_var(
                    value: $this->boolean(key: 'status'),
                    filter: FILTER_VALIDATE_BOOLEAN,
                    options: FILTER_NULL_ON_FAILURE
                )
            ]);
        }
    }
}
