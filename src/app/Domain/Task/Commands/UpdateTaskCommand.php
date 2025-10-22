<?php declare(strict_types=1);

namespace App\Domain\Task\Commands;

use App\Shared\Command;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class UpdateTaskCommand extends Command
{
    /**
     * The unique identifier of the task to update.
     *
     * @var int
     */
    #[Cast(type: IntegerCast::class, param: null)]
    public int $id;

    /**
     * The title of the task.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $title;

    /**
     * The description of the task.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $description;
    
    /**
     * The status of the task (true for active, false for inactive).
     *
     * @var bool
     */
    #[Cast(type: BooleanCast::class, param: null)]
    public bool $status;
}
