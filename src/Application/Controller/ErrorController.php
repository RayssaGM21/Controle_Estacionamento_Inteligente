<?php

declare(strict_types=1);

namespace App\Application\Controller;

/**
 * Controlador de Erros
 *
 * Gerencia a exibição de páginas de erro do sistema.
 */
class ErrorController extends BaseController
{
    /**
     * Exibe página de erro 404 (Não Encontrado)
     *
     * @param string $requestUri URI que foi solicitada
     * @return void
     */
    public function notFound(string $requestUri = ''): void
    {
        http_response_code(404);
        $data = [
            'requestUri' => $requestUri,
        ];
        $this->render('error/404', $data, 'Página Não Encontrada');
    }

    /**
     * Exibe página de erro 500 (Erro Interno do Servidor)
     *
     * @param string $message Mensagem de erro
     * @return void
     */
    public function internalError(string $message = ''): void
    {
        http_response_code(500);
        $data = [
            'message' => $message,
        ];
        $this->render('error/500', $data, 'Erro Interno do Servidor');
    }
}
