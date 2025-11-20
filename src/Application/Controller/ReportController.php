<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Service\ReportService;

/**
 * ControladorDeRelatorios
 *
 * Trata todas as operações de geração de relatórios:
 * - Exibir painel de relatórios
 * - Gerar estatísticas por tipo de veículo
 * - Exportar dados para CSV
 * - Exibir informações detalhadas das sessões
 *
 * @package App\Application\Controller
 */
final class ReportController extends BaseController
{
    private ReportService $reportService;

    /**
     * Construtor
     *
     * @param ReportService $reportService Serviço para geração de relatórios
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Exibe o painel principal de relatórios
     *
     * @return void
     */
    public function dashboard(): void
    {
        $dashboard = $this->reportService->getPanelDashboard();
        $report = $this->reportService->generateReportByType();

        $this->render('report/dashboard', [
            'dashboard' => $dashboard,
            'report' => $report,
        ], 'Reports Dashboard');
    }

    /**
     * Exibe relatório detalhado com todas as sessões
     *
     * @return void
     */
    public function detailed(): void
    {
        $report = $this->reportService->generateReportByType();
        $sessions = $this->reportService->getAllSessions();
        
        // Calcula o resumo iterando pelos objetos ParkingSession
        $totalBilling = 0.0;
        $totalHours = 0;
        
        foreach ($sessions as $session) {
            $totalBilling += $session->getFinalTariff();
            $totalHours += $session->getParkedHours();
        }
        
        $summary = [
            'total_revenue' => $totalBilling,
            'total_hours' => $totalHours,
            'average_tariff' => count($sessions) > 0 ? $totalBilling / count($sessions) : 0,
        ];

        $this->render('report/detailed', [
            'report' => $report,
            'sessions' => $sessions,
            'summary' => $summary,
        ], 'Detailed Report');
    }

    /**
     * Exporta os dados do relatório para o formato CSV
     *
     * @return void
     */
    public function exportCsv(): void
    {
        $csv = $this->reportService->exportToCSV();

        header('Content-Type: text/csv; charset=UTF-8');
        header(
            'Content-Disposition: attachment; filename="parking_report_' .
            date('Y-m-d_H-i-s') . '.csv"'
        );

        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $csv;
        exit;
    }
}
