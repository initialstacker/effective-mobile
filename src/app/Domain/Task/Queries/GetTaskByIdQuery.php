<?php declare(strict_types=1);

namespace App\Domain\Task\Queries;

use App\Shared\Query;

final class GetTaskByIdQuery extends Query
{
	/**
     * Constructs a new GetTaskByIdQuery instance.
     *
     * @param int $taskId
     */
	public function __construct(
		public private(set) int $taskId
	) {}
}
