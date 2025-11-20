<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\TariffRules;

/**
 * RegrasMoto
 *
 * Regra de cálculo de tarifa para motos.
 * Tarifa: $3 por hora
 *
 * @package App\Domain\Rules
 */
final class MotorcycleRules implements TariffRules
{
    /**
     * Calcula a tarifa para moto
     *
     * @param int $hours Número de horas estacionadas
     * @return float Tarifa calculada ($3/h)
     */
    public function calculate(int $hours): float
    {
        return $hours * 3;
    }
}
