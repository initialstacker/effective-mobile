<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Shared\Controller;
use App\Contracts\CommandBusContract;
use App\Contracts\QueryBusContract;
use App\Domain\Task\Queries\GetAllTasksQuery;
use App\Domain\Task\Queries\GetTaskByIdQuery;
use App\Domain\Task\Commands\CreateTaskCommand;
use App\Domain\Task\Commands\UpdateTaskCommand;
use App\Domain\Task\Commands\DeleteTaskCommand;
use App\Http\Requests\TaskRequest;
use App\Http\Responses\MessageResponse;
use App\Http\Responses\ResourceResponse;
use App\Http\Resources\TaskResource;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Where;
use Illuminate\Http\{Response, Request};

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:sanctum')]
final class TaskController extends Controller
{
    /**
     * Constructs a new TaskController instance.
     *
     * @param CommandBusContract $commandBus
     * @param QueryBusContract $queryBus
     */
    public function __construct(
        private readonly CommandBusContract $commandBus,
        private readonly QueryBusContract $queryBus
    ) {}

    /**
     * Retrieve all tasks.
     *
     * @return ResourceResponse
     */
    #[Route(methods: 'GET', uri: '/tasks')]
    public function index(): ResourceResponse
    {
        $query = new GetAllTasksQuery();
        $result = $this->queryBus->ask(query: $query);

        if (!blank(value: $result)) {
            return new ResourceResponse(
                data: TaskResource::collection(resource: $result),
                status: Response::HTTP_OK
            );
        }

        return new ResourceResponse(
            data: ['message' => 'No tasks found.'],
            status: Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Retrieve a task by its ID.
     *
     * @param int $id
     * @return ResourceResponse
     */
    #[Where(param: 'id', constraint: '[0-9]+')]
    #[Route(methods: 'GET', uri: '/tasks/{id}')]
    public function show(int $id): ResourceResponse
    {
        $query = new GetTaskByIdQuery(taskId: $id);
        $result = $this->queryBus->ask(query: $query);

        if (!blank(value: $result)) {
            return new ResourceResponse(
                data: new TaskResource(resource: $result),
                status: Response::HTTP_OK
            );
        }

        return new ResourceResponse(
            data: ['message' => 'Task not found.'],
            status: Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Create a new task.
     *
     * @param TaskRequest $request
     * @return MessageResponse
     */
    #[Route(methods: 'POST', uri: '/tasks')]
    public function store(TaskRequest $request): MessageResponse
    {
        $command = CreateTaskCommand::fromRequest(request: $request);
        $result = $this->commandBus->send(command: $command);

        if ($result) {
            return new MessageResponse(
                message: 'Task successfully created!',
                status: Response::HTTP_CREATED
            );
        }

        return new MessageResponse(
            message: 'Failed to create task.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Update an existing task by ID.
     *
     * @param int $id
     * @param TaskRequest $request
     * 
     * @return MessageResponse
     */
    #[Where(param: 'id', constraint: '[0-9]+')]
    #[Route(methods: 'PUT', uri: '/tasks/{id}')]
    public function update(int $id, TaskRequest $request): MessageResponse
    {
        $command = UpdateTaskCommand::fromRequest(request: $request);
        $result = $this->commandBus->send(command: $command);

        if ($result) {
            return new MessageResponse(
                message: 'Task successfully updated!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Failed to update task.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Delete a task by ID.
     *
     * @param int $id
     * @return MessageResponse
     */
    #[Where(param: 'id', constraint: '[0-9]+')]
    #[Route(methods: 'DELETE', uri: '/tasks/{id}')]
    public function delete(int $id): MessageResponse
    {
        $command = DeleteTaskCommand::fromArray(['id' => $id]);
        $result = $this->commandBus->send(command: $command);

        if ($result) {
            return new MessageResponse(
                message: 'Task successfully deleted!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Failed to delete task.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
