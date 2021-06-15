<?php

namespace Tests\Unit\Shared\Domain\Fakes;

use App\Shared\Domain\ValueObjectBase;

final class AnyValueObject extends ValueObjectBase
{
    public string $publicProp = 'any public value';
    private string $protectedProp = 'any protected value';
    private string $privateProp;

    public function __construct(string $privateProp)
    {
        $this->privateProp = $privateProp;
    }

    public function value()
    {
        return $this->privateProp;
    }
}
