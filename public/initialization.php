<?php 
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application\ParkingSessionService;
use App\Domain\TariffCalculator;
use App\Domain\Rules\CarRules;
use App\Domain\Rules\MotorcycleRules;
use App\Domain\Rules\TruckRules;

$calculator = new TariffCalculator([
    'car' => new CarRules(),
    'motorcycle' => new MotorcycleRules(),
    'truck' => new TruckRules(),
]);