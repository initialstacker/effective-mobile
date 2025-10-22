<?php declare(strict_types=1);

namespace App\Buses;

use App\Contracts\CommandBusContract;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Shared\Command;

final class CommandBus implements CommandBusContract
{
    /**
     * Constructs a new CommandBus instance.
     *
     * @param Dispatcher $commandBus
     */
    public function __construct(
        private Dispatcher $commandBus
    ) {}

    /**
     * Dispatches a command and returns the result.
     *
     * @param object $command
     * @return mixed|null
     */
    public function send(object $command): mixed
    {
        return $this->commandBus->dispatch(
            command: $command
        );
    }

    /**
     * Registers a mapping of commands to their handlers.
     *
     * @param array<string, string> $map
     */
    public function register(array $map): void
    {
        $this->commandBus->map(map: $map);
    }
}
