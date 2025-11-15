<?php

declare(strict_types=1);

namespace App\Domain;

final class ParkingSessionValidator
{
    public function validate(array $input): array
    {
        $errors = [];

        $plate = strtoupper(trim((string)$input['plate'] ?? ''));
        $vehicleType = strtolower(trim((string)$input['vehicleType'] ?? ''));
        $parkedHours = (int)($input['parkedHours'] ?? 0);
        $entryTime = $input['entryTime'] ?? null;

        $standardPlate = '/^[A-Z0-9-]{5,10}$/';
        $alloweds = ['car', 'motorcycle', 'truck'];

        if ($plate === '' || !preg_match($standardPlate, $plate)) {
            $errors[] = "Placa inválida (necessário ter letras/números e hízen, ex: ABC-1234). ";
        }

        if (!in_array($vehicleType, $alloweds, true)) {
            $errors[] = "Tipo de veículo inválido (car/motorcycle/truck). ";
        }

        if ($parkedHours < 0) {
            $errors[] = "Horas estacionadas inválidas.";
        }

        if (\DateTime::createFromFormat(\DateTime::ATOM, $entryTime) === false) {
            $errors[] = 'Horário de entrada inválido (use ISO 8601, ex: '.date('c').').';
        }

        return $errors;
    }
}