<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property \App\Models\User $resource
 */
final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        return [
            'id' => $user->id,
            'avatar' => $this->whenNotNull(
                value: $user->avatar ?? null
            ),
            'name' => $user->name,
            'email' => $user->email,
            'role' => $this->whenNotNull(
                value: $user->role !== null ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'slug' => $user->role->slug
                ] : null
            ),
            'datetime' => [
                'created_at' => $user->created_at?->format(
                    format: 'Y-m-d H:i:s'
                ),
                'updated_at' => $user->updated_at?->format(
                    format: 'Y-m-d H:i:s'
                ),
            ],
        ];
    }
}
