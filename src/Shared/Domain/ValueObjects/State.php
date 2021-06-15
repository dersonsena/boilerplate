<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidStateException;
use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\ValueObjectBase;

final class State extends ValueObjectBase
{
    private string $uf;

    private static array $ufList = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    ];

    /**
     * @throws InvalidStateException
     * @throws InvalidDomainParamException
     */
    public function __construct(string $uf)
    {
        if (empty($uf)) {
            throw new InvalidDomainParamException('UF cannot be empty');
        }

        $uf = strtoupper($uf);

        if (strlen($uf) !== 2) {
            throw new InvalidDomainParamException('UF must be exactly 2 characters long');
        }

        if (!array_key_exists($uf, self::$ufList)) {
            throw new InvalidStateException($uf);
        }

        $this->uf = $uf;
    }

    public function value()
    {
        return $this->uf;
    }

    public function name(bool $uppercase = false): string
    {
        $name = self::$ufList[$this->uf];

        if ($uppercase === false) {
            return $name;
        }

        return mb_strtoupper($name);
    }
}
