<?php declare(strict_types=1);

namespace App\Domain\Auth\Queries;

use App\Shared\Query;
use Illuminate\Http\Request;

final class CheckMeQuery extends Query
{
	/**
     * Constructs a new CheckMeQuery instance.
     *
     * @param Request $request
     */
	public function __construct(
		public private(set) Request $request
	) {}
}
