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

        $this->repository->add($session);
        return ['ok' => true, 'id' => $session->id()];

    }

    public function delete(int $id) : array
    {
        $exist = $this->repository->getById($id);

        if(!$exist) {
            return ['ok' => false, 'errors'=> "Não foi possível encontrar esta Sessão."];
        }

        $this->repository->delete($id);
        return ['ok' => true];

    }

    public function update(int $id, array $input) : array
    {
        $exist = $this->repository->getById($id);
        if (!$exist) {
            return ['ok' => false, 'errors' => "Sessão não encontrada."];
        }

        $errors = $this->validator->validate($input);
        if ($errors !==[]) {
            return ['ok' => false, 'errors' => $errors];

        }

        $plate = strtoupper(trim((string)$input['plate']));
        $vehicleType = strtoupper(trim((string)$input['vehicleType']));
        $parkedHours = (int)($input['parkedHours']);
        $entryTime = $input['entryTime'] ?? date('c');
        $finalTariff = $this->calculator->calculate($vehicleType, $parkedHours);

        $updatedSession = new ParkingSession(
            $plate,
            $vehicleType,
            $parkedHours,
            $finalTariff,
            $entryTime
        );

        $this->repository->update($updatedSession);
        
        return ['ok' => true];
    }

    public function getAll() : array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?ParkingSession
    {
        return $this->repository->getById($id);
    }
}

