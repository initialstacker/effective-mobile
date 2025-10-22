<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property \App\Models\Task $resource
 */
final class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $task = $this->resource;

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $this->whenNotNull(
                value: $task->description ?? null
            ),
            'status' => $task->status,
            'datetime' => [
                'created_at' => $task->created_at?->format(
                    format: 'Y-m-d H:i:s'
                ),
                'updated_at' => $task->updated_at?->format(
                    format: 'Y-m-d H:i:s'
                ),
            ],
        ];
    }
}
