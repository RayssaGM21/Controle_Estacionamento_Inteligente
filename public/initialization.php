<?php

declare(strict_types=1);

/**
 * Inicialização da Aplicação
 *
 * Carrega o autoload do Composer, inicializa o banco SQLite,
 * cria as dependências e configura o tratamento de erros.
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Domain;
use App\Application\Controller\HomeController;
use App\Application\Controller\ParkingSessionController;
use App\Application\Controller\ReportController;
use App\Application\Controller\ErrorController;
use App\Application\Service\ReportService;
use App\Infrastructure\Database\Database;
use App\Infrastructure\Repository\SqliteParkingSessionRepository;
use App\Domain\ParkingSessionValidator;
use App\Domain\Services\TariffCalculator;
use App\Domain\Rules\CarRules;
use App\Domain\Rules\MotorcycleRules;
use App\Domain\Rules\TruckRules;

/**
 * Inicialização do Banco
 *
 * Inicializa o singleton do SQLite com o caminho de armazenamento.
 * O banco é criado automaticamente se não existir.
 * A tabela `parking_sessions` é criada na primeira conexão.
 */
try {
    $storagePath = __DIR__ . '/../storage/parking.sqlite';
    $database = Database::getInstance($storagePath);
} catch (\PDOException $e) {
    die('Databse falhou ao inicializar: ' . $e->getMessage());
}

/**
 * Inicialização do Repositório
 *
 * `SqliteParkingSessionRepository` realiza as operações de banco
 * para as entradas do estacionamento usando SQLite/PDO.
 */
$repository = new SqliteParkingSessionRepository();

/**
 * Inicialização da Camada de Serviços
 *
 * `TariffCalculator`: Calcula tarifas por tipo de veículo e horas
 * `ParkingSessionValidator`: Valida dados das entradas
 * `ReportService`: Gera relatórios e dashboards
 */
$tariffCalculator = new TariffCalculator([
    'car' => new CarRules(),
    'motorcycle' => new MotorcycleRules(),
    'truck' => new TruckRules(),
]);

$validator = new ParkingSessionValidator();
$reportService = new ReportService($repository);

/**
 * Inicialização dos Controladores
 *
 * Os controladores tratam requisições HTTP e orquestram respostas.
 * Todos estendem `BaseController` para utilitários compartilhados.
 */
$homeController = new HomeController($reportService);
$parkingSessionController = new ParkingSessionController(
    $repository,
    $validator,
    $tariffCalculator
);
$reportController = new ReportController($reportService);
$errorController = new ErrorController();

