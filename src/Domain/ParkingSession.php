<?php 
declare(strict_types=1);

namespace App\Domain;
use DateTime;


final class ParkingSession
{
    private ?int $id;
    private string $plate;
    private string $vehicleType;
    private int $parkedHours;
    private float $finalTariff;
    private DateTime $entryTime; 

    public function __construct(string $plate, string $vehicleType, int $parkedHours, float $finalTariff, DateTime $entryTime) {
        $this->plate = $plate;
        $this->vehicleType = $vehicleType;
        $this->parkedHours = $parkedHours;
        $this->finalTariff = $finalTariff;
        $this->entryTime = $entryTime;
    }

    public function id(): int { return $this->id; }
    public function plate(): string { return $this->plate; }
    public function vehicleType(): string { return $this->vehicleType; }
    public function parkedHours(): int { return $this->parkedHours; }
    public function finalTariff(): float { return $this->finalTariff; }
    public function entryTime(): DateTime { return $this->entryTime; }

}