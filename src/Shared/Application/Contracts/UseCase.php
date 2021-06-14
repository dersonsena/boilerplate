<?php

declare(strict_types=1);

namespace App\Shared\Infra\Contracts;

use App\Shared\Application\Contracts\Boundary;

interface UseCase
{
    /**
     * Base interface to application use cases
     * @param Boundary $input
     * @return mixed
     */
    public function execute(Boundary $input);
}
