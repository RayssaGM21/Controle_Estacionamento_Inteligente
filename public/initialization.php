<?php 
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Application\ParkingSessionService;
use App\Domain\TariffCalculator;
use App\Domain\Rules\CarRules;
use App\Domain\Rules\MotorcycleRules;
use App\Domain\Rules\TruckRules;
use App\Domain\ParkingSessionValidator;
use App\Domain\ParkingSessionRepository;

$calculator = new TariffCalculator([
    'car' => new CarRules(),
    'motorcycle' => new MotorcycleRules(),
    'truck' => new TruckRules(),
]);

$repository = new ParkingSessionRepository();

$validator = new ParkingSessionValidator();

$parkingService = new ParkingSessionService($repository, $validator, $calculator);
