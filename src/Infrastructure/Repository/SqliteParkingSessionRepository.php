<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ParkingSession;
use App\Domain\ParkingSessionRepository;
use App\Infrastructure\Database\Database;
use PDO;

final class SqliteParkingSessionRepository implements ParkingSessionRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM parking_sessions ORDER BY id DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($rows as $row) {
            $result[] = $this->mapRowToEntity($row);
        }

        return $result;
    }

    public function getById(int $id): ?ParkingSession
    {
        $stmt = $this->pdo->prepare('SELECT * FROM parking_sessions WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapRowToEntity($row);
    }

    public function delete(int $id): ParkingSession
    {
        $existing = $this->getById($id);

        if ($existing === null) {
            throw new \RuntimeException('Parking session não encontrado.');
        }

        $stmt = $this->pdo->prepare('DELETE FROM parking_sessions WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $existing;
    }

    public function add(ParkingSession $object): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO parking_sessions
            (plate, vehicle_type, parked_hours, final_tariff, entry_time)
            VALUES (:plate, :vehicle_type, :parked_hours, :final_tariff, :entry_time)'
        );

        $stmt->execute([
            ':plate' => $object->getPlate(),
            ':vehicle_type' => $object->getVehicleType(),
            ':parked_hours' => $object->getParkedHours(),
            ':final_tariff' => $object->getFinalTariff(),
            ':entry_time' => $object->getEntryTime()->format('Y-m-d H:i:s'),
        ]);

        $id = (int)$this->pdo->lastInsertId();
        $object->setId($id);
    }

    public function update(ParkingSession $object): void
    {
        $id = $object->getId();

        if ($id === null) {
            throw new \RuntimeException('Não é possível atualizar uma sessão sem ID.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE parking_sessions SET
            plate = :plate,
            vehicle_type = :vehicle_type,
            parked_hours = :parked_hours,
            final_tariff = :final_tariff,
            entry_time = :entry_time
            WHERE id = :id'
        );

        $stmt->execute([
            ':plate' => $object->getPlate(),
            ':vehicle_type' => $object->getVehicleType(),
            ':parked_hours' => $object->getParkedHours(),
            ':final_tariff' => $object->getFinalTariff(),
            ':entry_time' => $object->getEntryTime()->format('Y-m-d H:i:s'),
            ':id' => $id,
        ]);
    }

    private function mapRowToEntity(array $row): ParkingSession
    {
        $entry = new ParkingSession(
            (string)$row['plate'],
            (string)$row['vehicle_type'],
            (int)$row['parked_hours'],
            (float)$row['final_tariff'],
            new \DateTime((string)$row['entry_time'])
        );
        $entry->setId((int)$row['id']);

        return $entry;
    }
}
