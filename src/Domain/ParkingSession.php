<?php

declare(strict_types=1);

namespace App\Domain;

use DateTime;

/**
 * EntradaEstacionamento
 *
 * Entidade que representa uma entrada de estacionamento com dados relevantes:
 * - Placa do veículo
 * - Tipo de veículo (car, motorcycle, truck)
 * - Duração em horas
 * - Tarifa calculada
 * - Timestamp de entrada
 *
 * @package App\Domain
 */
final class ParkingSession
{
    private ?int $id = null;
    private string $plate;
    private string $vehicleType;
    private int $parkedHours;
    private float $finalTariff;
    private DateTime $entryTime;

    /**
     * Construtor
     *
     * @param string $plate Placa do veículo
     * @param string $vehicleType Tipo de veículo (car, motorcycle, truck)
     * @param int $parkedHours Número de horas estacionadas
     * @param float $finalTariff Valor total da tarifa
     * @param DateTime $entryTime Timestamp da entrada
     */
    public function __construct(
        string $plate,
        string $vehicleType,
        int $parkedHours,
        float $finalTariff,
        DateTime $entryTime
    ) {
        $this->plate = $plate;
        $this->vehicleType = $vehicleType;
        $this->parkedHours = $parkedHours;
        $this->finalTariff = $finalTariff;
        $this->entryTime = $entryTime;
    }

    /**
     * Retorna o ID da entrada
     *
     * @return int|null ID da entrada ou null se ainda não persistido
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Define o ID da entrada (para persistência)
     *
     * @param int $id ID da entrada
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retorna a placa do veículo
     *
     * @return string Placa do veículo
     */
    public function getPlate(): string
    {
        return $this->plate;
    }

    /**
     * Retorna o tipo de veículo
     *
     * @return string Código do tipo de veículo
     */
    public function getVehicleType(): string
    {
        return $this->vehicleType;
    }

    /**
     * Retorna o número de horas estacionadas
     *
     * @return int Horas estacionadas
     */
    public function getParkedHours(): int
    {
        return $this->parkedHours;
    }

    /**
     * Retorna a tarifa final calculada
     *
     * @return float Valor total da tarifa
     */
    public function getFinalTariff(): float
    {
        return $this->finalTariff;
    }

    /**
     * Retorna o timestamp de entrada
     *
     * @return DateTime Horário de entrada
     */
    public function getEntryTime(): DateTime
    {
        return $this->entryTime;
    }
}
