<?php

declare(strict_types=1);

namespace App\Domanin\Rules;

use App\Domain\TollRules;

final class CarRules implements TollRules 
{
    public function calculate(int $hours)
    {
        return $hours * 5;
    }
}