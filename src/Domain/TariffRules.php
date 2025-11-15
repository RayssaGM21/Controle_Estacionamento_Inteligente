<?php

declare(strict_types=1);

namespace App\Domain;

interface TariffRules
{
    public function calculate(int $hours);
}