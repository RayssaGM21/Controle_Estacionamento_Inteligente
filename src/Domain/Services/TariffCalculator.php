<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\TariffRules;

/**
 * CalculadoraDeTarifas
 *
 * Calcula tarifas de estacionamento com base no tipo de veículo e horas estacionadas.
 * Usa o padrão strategy para suportar diferentes tipos de veículo com suas regras.
 *
 * @package App\Domain\Services
 */
final class TariffCalculator
{
    /**
        * @var array<string, TariffRules> Regras de tarifa por tipo de veículo
     */
    private array $rules = [];

    /**
     * Construtor
     *
     * @param array<string, TariffRules> $rules Mapa do tipo de veículo para sua regra de tarifa
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Calcula a tarifa para uma sessão de estacionamento
     *
     * @param string $vehicleType Tipo de veículo (carro, motocicleta, caminhão)
     * @param int $parkedHours Número de horas estacionadas
     * @return float Valor da tarifa calculada
     */
    public function calculate(string $vehicleType, int $parkedHours): float
    {
        $key = strtolower($vehicleType);

        if (!isset($this->rules[$key])) {
            return 0.0;
        }

        $finalTariff = $this->rules[$key]->calculate($parkedHours);

        return $finalTariff;
    }
}
