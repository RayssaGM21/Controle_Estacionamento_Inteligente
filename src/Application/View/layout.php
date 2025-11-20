<?php

declare(strict_types=1);

/**
 * Template de Layout Base
 *
 * Estrutura HTML principal compartilhada por todas as views.
 * Fornece navegação, estilos e funcionalidades comuns.
 *
 * @param string $title Título da página
 * @param string $content Conteúdo da view (output buffer)
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Gestão de Estacionamento'); ?> - Sistema de Estacionamento Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #eef2f5 100%);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1a3a52 0%, #0f2438 100%);
        }
        .gradient-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .gradient-success {
            background: linear-gradient(135deg, #2d8659 0%, #1a5236 100%);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #c97b3e 0%, #8b5a2b 100%);
        }
        .gradient-accent {
            background: linear-gradient(135deg, #2a5a7a 0%, #1a3f52 100%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .premium-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1) inset;
        }
        .text-premium-dark {
            color: #1a3a52;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <nav class="gradient-bg shadow-2xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="/image/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-white text-xl font-bold tracking-tight">Estacionamento Inteligente</h1>
                    <p class="text-blue-200 text-xs">Gestão Profissional</p>
                </div>
            </div>
            <div class="flex gap-8">
                <a href="/" class="text-blue-100 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 hover:bg-white hover:bg-opacity-10"><i class="fas fa-home mr-2"></i>Início</a>
                <a href="/session/list" class="text-blue-100 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 hover:bg-white hover:bg-opacity-10"><i class="fas fa-list mr-2"></i>Entradas</a>
                <a href="/report/dashboard" class="text-blue-100 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 hover:bg-white hover:bg-opacity-10"><i class="fas fa-chart-line mr-2"></i>Relatórios</a>
            </div>
        </div>
    </nav>

    <div class="flex-grow max-w-7xl mx-auto w-full px-6 py-10">
        <?php echo $content ?? ''; ?>
    </div>

    <footer class="bg-gradient-to-r from-slate-900 to-slate-800 text-white text-center py-8 mt-auto border-t border-slate-700">
        <p class="font-medium">&copy; 2025 Sistema de Estacionamento Inteligente</p>
        <p class="text-slate-400 text-sm mt-1">Solução profissional de gestão de estacionamento</p>
    </footer>
</body>
</html>
