<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Shared\Controller;
use App\Contracts\CommandBusContract;
use App\Contracts\QueryBusContract;
use App\Domain\Auth\Commands\LoginCommand;
use App\Domain\Auth\Commands\LogoutCommand;
use App\Domain\Auth\Queries\CheckMeQuery;
use App\Http\Responses\ResourceResponse;
use App\Http\Responses\TokenResponse;
use App\Http\Responses\MessageResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Prefix;
use Illuminate\Http\{Response, Request};

#[Prefix(prefix: 'v1')]
final class AuthController extends Controller
{
    /**
     * Constructs a new AuthController instance.
     *
     * @param CommandBusContract $commandBus
     * @param QueryBusContract $queryBus
     */
    public function __construct(
        private readonly CommandBusContract $commandBus,
        private readonly QueryBusContract $queryBus
    ) {}

    /**
     * Handles user login requests.
     *
     * @param LoginRequest $request
     * @return TokenResponse
     */
    #[Route(methods: 'POST', uri: '/login')]
    public function login(LoginRequest $request): TokenResponse
    {
        $command = LoginCommand::fromRequest(request: $request);
        $result = $this->commandBus->send(command: $command);

        if (is_string(value: $result) && $result !== '') {
            return new TokenResponse(
                message: 'Token successfully generated!',
                token: $result
            );
        }

        return new TokenResponse(
            message: 'Failed to generate token.',
            status: Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Handles user logout requests.
     *
     * @param Request $request
     * @return MessageResponse
     */
    #[Route(methods: 'POST', uri: '/logout', middleware: 'auth:sanctum')]
    public function logout(Request $request): MessageResponse
    {
        $command = new LogoutCommand(request: $request);
        $result = $this->commandBus->send(command: $command);

        if ($result) {
            return new MessageResponse(
                message: 'You have been logged out successfully!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Logout failed. Please try again.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Checks and returns details of the currently authenticated user.
     *
     * @param Request $request
     * @return ResourceResponse
     */
    #[Route(methods: 'GET', uri: '/check-me', middleware: 'auth:sanctum')]
    public function checkMe(Request $request): ResourceResponse
    {
        $query = new CheckMeQuery(request: $request);
        $result = $this->queryBus->ask(query: $query);

        if ($result) {
            return new ResourceResponse(
                data: new UserResource(resource: $result),
                status: Response::HTTP_OK
            );
        }

        return new ResourceResponse(
            data: ['message' => 'You are not authenticated.'],
            status: Response::HTTP_UNAUTHORIZED
        );
    }
}
