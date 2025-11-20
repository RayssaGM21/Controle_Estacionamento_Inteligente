<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\TariffRules;

/**
 * RegrasCaminhao
 *
 * Regra de cálculo de tarifa para caminhões.
 * Tarifa: $10 por hora
 *
 * @package App\Domain\Rules
 */
final class TruckRules implements TariffRules
{
    /**
     * Calcula a tarifa para caminhão
     *
     * @param int $hours Número de horas estacionadas
     * @return float Tarifa calculada ($10/h)
     */
    public function calculate(int $hours): float
    {
        return $hours * 10;
    }
}
