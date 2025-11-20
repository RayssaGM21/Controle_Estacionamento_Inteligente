<?php

declare(strict_types=1);

namespace App\Application\Controller;

/**
 * ControladorBase
 *
 * Classe controladora base para manipulação de requisições e renderização de views.
 * Fornece métodos comuns para tratamento de respostas e renderização de views.
 *
 * @package App\Application\Controller
 * @author Equipe de Desenvolvimento
 */
abstract class BaseController
{
    /**
     * Renderiza um template de view com dados usando layout
     *
     * @param string $viewName Nome do template de view (caminho relativo a View/)
     * @param array<string, mixed> $data Dados a passar para a view
     * @param string $title Título da página HTML
     * @return void
     */
    protected function render(string $viewName, array $data = [], string $title = 'Sistema de Estacionamento'): void
    {
        // Extrai dados para o escopo atual
        extract($data, EXTR_SKIP);
        
        // Inícia o buffer de saída para o conteúdo da view
        ob_start();
        
        // Inclui o arquivo da view
        require __DIR__ . '/../View/' . $viewName . '.php';
        
        // Obtém o conteúdo da view
        $content = ob_get_clean();
        
        // Define variáveis para o layout
        $title = $title;
        
        // Inclui o layout com o conteúdo
        require __DIR__ . '/../View/layout.php';
    }

    /**
     * Envia resposta JSON
     *
     * @param array<string, mixed> $data Dados da resposta
     * @param int $statusCode Código de status HTTP
     * @return void
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Redireciona para outra URL
     *
     * @param string $url URL de destino
     * @param int $statusCode Código de status HTTP (301, 302, etc)
     * @return void
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Obtém parâmetro GET com padrão opcional
     *
     * @param string $key Nome do parâmetro
     * @param mixed $default Valor padrão se não encontrado
     * @return mixed
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Obtém parâmetro POST com padrão opcional
     *
     * @param string $key Nome do parâmetro
     * @param mixed $default Valor padrão se não encontrado
     * @return mixed
     */
    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Verifica se o método da requisição é POST
     *
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Verifica se o método da requisição é GET
     *
     * @return bool
     */
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}
