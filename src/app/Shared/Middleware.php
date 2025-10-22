<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Http\Request;

abstract class Middleware
{
    /**
     * Abstract base class for HTTP middlewares.
     *
     * @param Request $request
     * @param \Closure $next
     * 
     * @return mixed
     */
    abstract public function handle(Request $request, \Closure $next): mixed;
}
