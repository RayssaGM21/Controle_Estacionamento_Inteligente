<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\TollRules;

final class MotorcycleRules implements TollRules
{
    public function calculate(int $hours)
    {
        return $hours * 3;
    }
}