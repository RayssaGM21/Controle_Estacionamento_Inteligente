<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * RegrasDeTarifa
 *
 * Interface que define o contrato para regras de tarifa por veículo.
 * Implementações devem fornecer a lógica de cálculo para cada tipo de veículo.
 *
 * @package App\Domain
 */
interface TariffRules
{
    /**
        * Calcula a tarifa para um dado número de horas
        *
        * @param int $hours Número de horas estacionadas
        * @return float Valor da tarifa calculada
     */
    public function calculate(int $hours): float;
}
