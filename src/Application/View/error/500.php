<?php

declare(strict_types=1);

/**
 * Página de Erro 500 - Erro Interno do Servidor
 *
 * Exibe mensagem amigável quando ocorre um erro interno.
 *
 * @param string $message Mensagem de erro
 */
?>

<div class="flex flex-col items-center justify-center">
    <div class="text-center max-w-lg">
        <h1 class="text-9xl font-black text-slate-900 opacity-10 -mb-8">500</h1>

        <div class="relative z-10 mb-8">
            <i class="fas fa-triangle-exclamation text-6xl text-slate-400 mb-6 block"></i>
            <h2 class="text-3xl font-bold text-slate-900 mb-3">Algo deu errado</h2>
            <p class="text-slate-600 text-base leading-relaxed">
                Ocorreu um erro ao processar sua requisição. Nossos desenvolvedores foram notificados.
            </p>
        </div>

        <?php if (!empty($message)): ?>
        <div class="bg-slate-50 rounded-lg p-4 mb-8 border border-slate-200 max-h-32 overflow-y-auto">
            <p class="text-xs text-slate-600 mb-2 uppercase font-semibold tracking-wide text-left">Detalhes do Erro:</p>
            <p class="font-mono text-xs text-slate-700 text-left break-all">
                <?php echo htmlspecialchars($message ?? 'Erro desconhecido'); ?>
            </p>
        </div>
        <?php endif; ?>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="/" class="px-6 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-semibold transition-colors duration-200 text-sm">
                <i class="fas fa-home mr-2"></i> Ir para Início
            </a>
            <a href="javascript:history.back()" class="px-6 py-2.5 bg-slate-200 text-slate-900 rounded-lg hover:bg-slate-300 font-semibold transition-colors duration-200 text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Tentar Novamente
            </a>
        </div>
    </div>
</div>

