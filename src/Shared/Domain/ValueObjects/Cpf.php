<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidCPFException;
use App\Shared\Domain\ValueObjectBase;

final class Cpf extends ValueObjectBase
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        if (is_null($cpf) || empty($cpf)) {
            throw new InvalidCPFException("CPF cannot be null or empty");
        }

        $cpfSanitized = (string)preg_replace('/[^a-zA-Z0-9]/', '', $cpf);

        if (strlen($cpfSanitized) !== 11) {
            throw new InvalidCPFException("CPF should be 11 characters.");
        }

        if (!$this->validate($cpfSanitized)) {
            throw new InvalidCPFException('Invalid CPF.');
        }

        $this->cpf = $cpfSanitized;
    }

    /**
     * @param string $cpf
     * @return bool
     */
    private function validate(string $cpf): bool
    {
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function formatted(): string
    {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $this->cpf);
    }

    public function value()
    {
        return $this->cpf;
    }
}