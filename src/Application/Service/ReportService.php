<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\ParkingSessionRepository;

/**
 * ServicoDeRelatorios
 *
 * Responsável pela geração de relatórios e agregação de dados.
 * Fornece métodos para:
 * - Gerar relatórios por tipo de veículo
 * - Criar estatísticas para o painel (dashboard)
 * - Exportar dados para CSV
 *
 * @package App\Application\Service
 */
final class ReportService
{
    private ParkingSessionRepository $repository;

    /**
     * Construtor
     *
     * @param ParkingSessionRepository $repository Repositório para acesso a dados
     */
    public function __construct(ParkingSessionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Gera um relatório abrangente por tipo de veículo
     *
     * Retorna estatísticas incluindo quantidade e faturamento total para cada tipo de veículo.
     *
     * @return array<string, mixed> Dados do relatório com totais e detalhamento por tipo
     */
    public function generateReportByType(): array
    {
        $sessions = $this->repository->getAll();

        $report = [
            'total_general' => [
                'vehicles' => 0,
                'billing' => 0.0,
            ],
            'by_type' => [
                'car' => ['quantity' => 0, 'billing' => 0.0, 'description' => 'Carros'],
                'motorcycle' => ['quantity' => 0, 'billing' => 0.0, 'description' => 'Motos'],
                'truck' => ['quantity' => 0, 'billing' => 0.0, 'description' => 'Caminhões'],
            ],
        ];

        foreach ($sessions as $session) {
            $type = strtolower($session->getVehicleType());
            $tariff = $session->getFinalTariff();

            $report['total_general']['vehicles']++;
            $report['total_general']['billing'] += $tariff;

            if (isset($report['by_type'][$type])) {
                $report['by_type'][$type]['quantity']++;
                $report['by_type'][$type]['billing'] += $tariff;
            }
        }

        $report['total_general']['billing'] = round($report['total_general']['billing'], 2);
        foreach ($report['by_type'] as &$type) {
            $type['billing'] = round($type['billing'], 2);
        }

        return $report;
    }

    /**
     * Obtém os dados do painel (dashboard) com informações agregadas
     *
     * @return array<string, mixed> Estatísticas do painel
     */
    public function getPanelDashboard(): array
    {
        $sessions = $this->repository->getAll();
        $report = $this->generateReportByType();

        $totalHours = 0;
        $averageTariff = 0.0;

        foreach ($sessions as $session) {
            $totalHours += $session->getParkedHours();
            $averageTariff += $session->getFinalTariff();
        }

        $totalSessions = count($sessions);
        $averageTariff = $totalSessions > 0 ? round($averageTariff / $totalSessions, 2) : 0.0;

        return [
            'total_sessions' => $totalSessions,
            'total_billing' => $report['total_general']['billing'],
            'parked_hours' => $totalHours,
            'average_tariff' => $averageTariff,
            'by_type' => $report['by_type'],
        ];
    }

    /**
     * Exporta todas as sessões para o formato CSV
     *
     * @return string Dados no formato CSV
     */
    public function exportToCSV(): string
    {
        $sessions = $this->repository->getAll();
        $csv = "Placa,Tipo,Horas,Tarifa,Horário de Entrada\n";

        foreach ($sessions as $session) {
            $line = sprintf(
                "%s,%s,%d,%.2f,%s\n",
                $session->getPlate(),
                $this->translateVehicleType($session->getVehicleType()),
                $session->getParkedHours(),
                $session->getFinalTariff(),
                $session->getEntryTime()->format('Y-m-d H:i:s')
            );
            $csv .= $line;
        }

        return $csv;
    }

    /**
     * Retorna todas as sessões
     *
     * @return array<\App\Domain\ParkingSession> Todas as sessões de estacionamento
     */
    public function getAllSessions(): array
    {
        return $this->repository->getAll();
    }

    /**
     * Traduz o tipo de veículo para um nome legível ao usuário
     *
     * @param string $type Código do tipo de veículo
     *     * @return string Tipo de veículo traduzido
     */
    private function translateVehicleType(string $type): string
    {
        $translations = [
            'car' => 'Carro',
            'motorcycle' => 'Moto',
            'truck' => 'Caminhão',
        ];

        return $translations[strtolower($type)] ?? $type;
    }
}
