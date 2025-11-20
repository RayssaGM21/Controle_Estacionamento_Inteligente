<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * RepositorioDeEntradas
 *
 * Interface que define o contrato para operações de persistência das entradas.
 * Implementações devem tratar armazenamento e recuperação das entradas.
 *
 * @package App\Domain
 */
interface ParkingSessionRepository
{
    /**
     * Retorna todas as entradas de estacionamento
     *
     * @return array<ParkingSession> Array com todas as entradas
     */
    public function getAll(): array;

    /**
     * Retorna uma entrada pelo ID
     *
     * @param int $id ID da entrada
     * @return ParkingSession|null Entrada ou null se não encontrada
     */
    public function getById(int $id): ?ParkingSession;

    /**
     * Remove uma entrada pelo ID
     *
     * @param int $id ID da entrada
     * @return ParkingSession Entrada removida
     * @throws \RuntimeException Se a entrada não for encontrada
     */
    public function delete(int $id): ParkingSession;

    /**
     * Adiciona uma nova entrada
     *
     * @param ParkingSession $object Entrada a ser persistida
     * @return void
     */
    public function add(ParkingSession $object): void;

    /**
     * Atualiza uma entrada existente
     *
     * @param ParkingSession $object Entrada a ser atualizada
     * @return void
     */
    public function update(ParkingSession $object): void;
}
