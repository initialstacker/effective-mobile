<?php declare(strict_types=1);

namespace App\Domain\Task\Commands;

use App\Shared\Command;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class DeleteTaskCommand extends Command
{
    /**
     * The unique identifier of the task to be deleted.
     *
     * @var int
     */
    #[Cast(type: IntegerCast::class, param: null)]
    public int $id;
}
