<?php

declare(strict_types=1);

/**
 * View de Relatório Detalhado
 *
 * Exibe relatório abrangente com todas as entradas em formato de tabela.
 *
 * @param array $sessions Todas as entradas de estacionamento
 * @param array $summary Estatísticas resumidas
 */
?>

<!-- Cabeçalho para impressão -->
<div class="hidden print:block mb-8 text-center border-b-2 border-slate-300 pb-6">
    <h1 class="text-2xl font-bold text-slate-900">Relatório Detalhado de Estacionamento</h1>
    <p class="text-slate-600 text-sm mt-2">Data de Geração: <?php echo date('d/m/Y H:i'); ?></p>
</div>

<div class="mb-10">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900">Relatório Detalhado</h1>
            <p class="text-slate-600 mt-2 text-lg">Registro completo de todas as entradas</p>
        </div>
        <div class="flex gap-4">
            <a href="/report/dashboard" class="px-7 py-3 bg-slate-200 text-slate-900 rounded-xl hover:bg-slate-300 font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Painel
            </a>
            <a href="/report/export-csv" class="px-7 py-3 bg-gradient-to-br from-emerald-600 to-emerald-700 text-white rounded-xl hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-md">
                <i class="fas fa-download"></i> Exportar CSV
            </a>
            <button onclick="window.print()" class="px-7 py-3 bg-slate-700 text-white rounded-xl hover:bg-slate-800 font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-md">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>

    <?php
    $sessions = $sessions ?? [];
    $summary = $summary ?? [];
    $summary['total_revenue'] = (float)($summary['total_revenue'] ?? 0.0);
    $summary['total_hours'] = (int)($summary['total_hours'] ?? 0);
    $summary['average_tariff'] = (float)($summary['average_tariff'] ?? 0.0);
    ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-10">
        <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-all premium-shadow">
            <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Total de Entradas</p>
            <p class="text-3xl font-bold text-slate-900 mt-2"><?php echo count($sessions ?? []); ?></p>
            <p class="text-xs text-slate-500 mt-2">Registros</p>
        </div>
        <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-all premium-shadow">
            <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Receita Total</p>
            <p class="text-3xl font-bold text-emerald-700 mt-2">R$ <?php echo number_format((float)($summary['total_revenue'] ?? 0), 2, ',', '.'); ?></p>
            <p class="text-xs text-slate-500 mt-2">Arrecadado</p>
        </div>
        <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-all premium-shadow">
            <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Total de Horas</p>
            <p class="text-3xl font-bold text-slate-900 mt-2"><?php echo number_format((int)($summary['total_hours'] ?? 0)); ?></p>
            <p class="text-xs text-slate-500 mt-2">Horas</p>
        </div>
        <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-all premium-shadow">
            <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Tarifa Média</p>
            <p class="text-3xl font-bold text-slate-900 mt-2">R$ <?php echo number_format((float)($summary['average_tariff'] ?? 0), 2, ',', '.'); ?></p>
            <p class="text-xs text-slate-500 mt-2">Por entrada</p>
        </div>
    </div>

    <?php if (empty($sessions)): ?>
    <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-16 text-center premium-shadow">
        <p class="text-6xl mb-6 text-slate-300"><i class="fas fa-inbox"></i></p>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Sem dados disponíveis</h3>
        <p class="text-slate-600 text-lg">Não há entradas para exibir no momento.</p>
    </div>
    <?php else: ?>
    <div class="stat-card rounded-xl shadow-md overflow-hidden border border-slate-100 premium-shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-white border-b-2 border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Placa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tipo de Veículo</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Horas</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Horário de Entrada</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Tarifa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($sessions as $session): ?>
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo htmlspecialchars((string)($session->getId() ?? '')); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-800 font-mono font-semibold"><?php echo htmlspecialchars((string)$session->getPlate()); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <?php
                            $types = [
                                'car' => '<span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"><i class="fas fa-car"></i> Carro</span>',
                                'motorcycle' => '<span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold"><i class="fas fa-motorcycle"></i> Moto</span>',
                                'truck' => '<span class="inline-flex items-center gap-2 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold"><i class="fas fa-truck"></i> Caminhão</span>'
                            ];
                            echo $types[(string)$session->getVehicleType()] ?? htmlspecialchars((string)$session->getVehicleType());
                            ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800"><?php echo htmlspecialchars((string)$session->getParkedHours()); ?> h</td>
                        <td class="px-6 py-4 text-sm text-slate-600"><?php echo htmlspecialchars($session->getEntryTime()->format('d/m/Y H:i')); ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-emerald-700 text-right">R$ <?php echo number_format((float)$session->getFinalTariff(), 2, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right font-semibold text-slate-800">TOTAL:</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-700 text-lg">
                            R$ <?php
                            $total = 0;
                            foreach ($sessions as $session) {
                                $total += $session->getFinalTariff();
                            }
                            echo number_format($total, 2, ',', '.');
                            ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
