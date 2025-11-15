<?php 

declare(strict_type=1);

namespace App\Application;

use App\Domain\ParkingSession;
use App\Domain\ParkingSessionRepository;
use App\Domain\ParkingSessionValidator;
use App\Domain\TariffCalculator;

final class ParkingSessionService 
{
    private ParkingSessionRepository $repository;
    private ParkingSessionValidator $validator;
    private TariffCalculator $calculator;

    public function __construct(ParkingSessionRepository $repository, ParkingSessionValidator $validator, TariffCalculator $calculator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->calculator = $calculator;
    }

    public function add(array $input) : array
    {
        $errors = $this->validator->validate($input);

        if ($errors!== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        $plate = strtoupper(trim((string)$input['plate']));
        $vehicleType = strtoupper(trim((string)$input['vehicleType']));
        $parkedHours = (int)($input['parkedHours']);
        $entryTime = $input['entryTime'] ?? date('c');
        $finalTariff = $this->calculator->calculate($vehicleType, $parkedHours);

        $session = new ParkingSession(
            $plate, $vehicleType, $parkedHours, $finalTariff, $entryTime
        );

        // ParkingSessionRepository -> Add()

    }

    public function delete(int $id)
    {

    }

    public function update()
    {

    }

    public function getAll()
    {

    }

    public function getById(int $id)
    {

    }
}

