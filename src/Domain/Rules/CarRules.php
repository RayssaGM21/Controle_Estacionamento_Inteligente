<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\TariffRules;

final class CarRules implements TariffRules
{
    public function calculate(int $hours) : float
    {
        return $hours * 5;
    }
}