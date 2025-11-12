<?php

declare(strict_types=1);

namespace App\Domain;

interface TollRules
{
    public function calculate();
}