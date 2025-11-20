<?php

declare(strict_types=1);

/**
 * Página de Erro 404 - Rota Não Encontrada
 *
 * Exibe mensagem amigável quando a rota não existe.
 *
 * @param string $requestUri A URI que não foi encontrada
 */
?>

<div class="flex flex-col items-center justify-center">
    <div class="text-center max-w-lg">
        <h1 class="text-9xl font-black text-slate-900 opacity-10 -mb-8">404</h1>

        <div class="relative z-10 mb-8">
            <i class="fas fa-map text-6xl text-slate-400 mb-6 block"></i>
            <h2 class="text-3xl font-bold text-slate-900 mb-3">Página não encontrada</h2>
            <p class="text-slate-600 text-base leading-relaxed">
                A rota que você tentou acessar não existe em nosso sistema.
            </p>
        </div>

        <div class="bg-slate-50 rounded-lg p-4 mb-8 border border-slate-200">
            <p class="text-xs text-slate-600 mb-2 uppercase font-semibold tracking-wide">URL Solicitada:</p>
            <p class="font-mono text-sm text-slate-900 break-all">
                <?php echo htmlspecialchars($requestUri ?? 'Rota desconhecida'); ?>
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="/" class="px-6 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-semibold transition-colors duration-200 text-sm">
                <i class="fas fa-home mr-2"></i> Ir para Início
            </a>
            <a href="javascript:history.back()" class="px-6 py-2.5 bg-slate-200 text-slate-900 rounded-lg hover:bg-slate-300 font-semibold transition-colors duration-200 text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>
</div>

