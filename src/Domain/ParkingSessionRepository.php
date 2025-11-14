<?php

declare(strict_types=1);

namespace App\Domain;

interface ParkingSessionRepository
{
    public function getAll(): array;

    public function getById(int $id): ?ParkingSession;

    public function delete(int $id): ParkingSession;

    public function add(ParkingSession $object): void;

    public function update(ParkingSession $object): void;

}