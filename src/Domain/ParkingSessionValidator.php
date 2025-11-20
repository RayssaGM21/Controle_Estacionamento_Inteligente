<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * ValidadorDeEntrada
 *
 * Valida os dados de entrada de uma sessão de estacionamento conforme as regras de negócio.
 * Garante que o formato da placa, o tipo de veículo, as horas e o timestamp estejam corretos.
 *
 * @package App\Domain
 */
final class ParkingSessionValidator
{
    /**
     * Valida os dados de entrada da sessão de estacionamento
     *
     * @param array<string, mixed> $input Dados de entrada a serem validados
     * @return array<string> Array de mensagens de erro de validação
     */
    public function validate(array $input): array
    {
        $errors = [];

        $plate = strtoupper(trim((string)($input['plate'] ?? '')));
        $vehicleType = strtolower(trim((string)($input['vehicleType'] ?? '')));
        $parkedHours = (int)($input['parkedHours'] ?? 0);
        $entryTime = $input['entryTime'] ?? null;

        $platePattern = '/^[A-Z0-9\-]{5,10}$/';
        $allowedVehicleTypes = ['car', 'motorcycle', 'truck'];

        if ($plate === '' || !preg_match($platePattern, $plate)) {
            $errors[] = 'Formato de placa inválido (necessário: letras/números/hífem, ex: ABC-1234).';
        }

        if (!in_array($vehicleType, $allowedVehicleTypes, true)) {
            $errors[] = 'Tipo de veículo inválido (carro/moto/caminhão).';
        }

        if ($parkedHours <= 0) {
            $errors[] = 'Horas estacionadas devem ser maiores que zero.';
        }

        if (\DateTime::createFromFormat('Y-m-d\TH:i', $entryTime) === false
            && \DateTime::createFromFormat('Y-m-d H:i:s', $entryTime) === false
        ) {
            $errors[] = 'Formato de horário de entrada inválido.';
        }

        return $errors;
    }
}
