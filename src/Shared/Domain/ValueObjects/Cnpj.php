<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidCnpjException;
use App\Shared\Domain\ValueObjectBase;

final class Cnpj extends ValueObjectBase
{
    /**
     * @var string
     */
    private $cnpj;

    public function __construct(string $cnpj)
    {
        if (is_null($cnpj) || empty($cnpj)) {
            throw new InvalidCnpjException("CNPJ cannot be null or empty");
        }

        $cnpjSanitized = preg_replace('/[^a-zA-Z0-9]/', '', $cnpj);

        if (strlen($cnpjSanitized) !== 14) {
            throw new InvalidCnpjException("CNPJ should be 14 characters.");
        }

        if (!$this->validate($cnpj)) {
            throw new InvalidCnpjException('Invalid CNPJ.');
        }

        $this->cnpj = $cnpjSanitized;
    }

    /**
     * @param string $cnpj
     * @return bool
     */
    private function validate(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    /**
     * @return string
     */
    public function formatted(): string
    {
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3\/\$4-\$5", $this->cnpj);
    }

    public function value()
    {
        return $this->cnpj;
    }
}
