<?php declare(strict_types=1);

namespace App\Domain\Auth\Commands;

use Illuminate\Http\Request;

final class LogoutCommand
{
	/**
     * Constructs a new LogoutCommand instance.
     *
     * @param Request $request
     */
	public function __construct(
		public private(set) Request $request
	) {}
}
