<?php

declare(strict_types=1);

require __DIR__ . '/initialization.php';

/**
 * Front Controller e Rotas
 *
 * Ponto principal para requisições HTTP
 */

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$basePath = '/';
if (strpos($requestUri, '/public/') !== false) {
    $requestUri = str_replace('/public/', '/', $requestUri);
}

$requestUri = str_replace('/controle_estacionamento_inteligente/', '/', $requestUri);

// Rotas
try {
    switch ($requestUri) {
        case '/':
        case '/index.php':
            $homeController->index();
            break;

        case '/session/create':
            if ($requestMethod === 'GET') {
                $parkingSessionController->showCreateForm();
            } elseif ($requestMethod === 'POST') {
                $parkingSessionController->create();
            }
            break;

        case '/session/list':
            $parkingSessionController->listAll();
            break;

        case '/session/edit':
            if ($requestMethod === 'GET') {
                $parkingSessionController->showEditForm();
            } elseif ($requestMethod === 'POST') {
                $parkingSessionController->update();
            }
            break;

        case '/session/delete':
            $parkingSessionController->delete();
            break;

        case '/report/dashboard':
            $reportController->dashboard();
            break;

        case '/report/detailed':
            $reportController->detailed();
            break;

        case '/report/export-csv':
            $reportController->exportCsv();
            break;

        default:
            $errorController->notFound($requestUri);
            break;
    }
} catch (\Exception $e) {
    $errorController->internalError($e->getMessage());
}
