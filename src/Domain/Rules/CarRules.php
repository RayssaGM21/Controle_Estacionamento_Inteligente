<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\TariffRules;

/**
 * RegrasCarro
 *
 * Regra de cálculo de tarifa para carros.
 * Tarifa: $5 por hora
 *
 * @package App\Domain\Rules
 */
final class CarRules implements TariffRules
{
    /**
     * Calcula a tarifa para carro
     *
     * @param int $hours Número de horas estacionadas
     * @return float Tarifa calculada ($5/h)
     */
    public function calculate(int $hours): float
    {
        return $hours * 5;
    }
}
