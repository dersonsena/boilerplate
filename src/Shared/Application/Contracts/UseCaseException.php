<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts;

interface UseCaseException
{
    public function details(): array;
}