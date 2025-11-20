<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Service\ReportService;

/**
 * HomeController
 *
 * Handles application homepage and general navigation.
 * Displays main dashboard with quick statistics.
 *
 * @package App\Application\Controller
 */
final class HomeController extends BaseController
{
    private ReportService $reportService;

    /**
     * Constructor
     *
     * @param ReportService $reportService Service for report statistics
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display home page with dashboard
     *
     * @return void
     */
    public function index(): void
    {
        $dashboard = $this->reportService->getPanelDashboard();
        $this->render('home/index', ['dashboard' => $dashboard], 'Dashboard');
    }
}
