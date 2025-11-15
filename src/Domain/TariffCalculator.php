<?php 

declare(strict_types=1);

namespace App\Domain;

final class TariffCalculator
{
    private array $rules = [];

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function calculate(string $vehicleType, int $parkedHours) : float
    {
        $key = strtolower($vehicleType);
        
        if(!isset($this->rules[$key])) {
            return 0.0;
        }

        $finalTariff = $this->rules[$key]->calculate($parkedHours);
        return $finalTariff;
    }
}