<?php

declare(strict_types=1);

/**
 * View do Painel Inicial
 *
 * Exibe o painel principal com estatísticas do estacionamento e ações rápidas.
 *
 * @param array $dashboard Array de estatísticas contendo:
 *                          - total_sessions: Total de entradas
 *                          - total_billing: Receita total
 *                          - parked_hours: Total de horas estacionadas
 *                          - average_tariff: Tarifa média por entrada
 *                          - by_type: Estatísticas agrupadas por tipo de veículo
 */

$dashboard = $dashboard ?? [];
$dashboard['total_sessions'] = (int)($dashboard['total_sessions'] ?? 0);
$dashboard['total_billing'] = (float)($dashboard['total_billing'] ?? 0.0);
$dashboard['parked_hours'] = (int)($dashboard['parked_hours'] ?? 0);
$dashboard['average_tariff'] = (float)($dashboard['average_tariff'] ?? 0.0);
$dashboard['by_type'] = $dashboard['by_type'] ?? [];
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <a href="/session/create" class="card-hover stat-card rounded-xl shadow-md p-7 text-center hover:shadow-2xl border border-slate-100 group">
        <div class="text-5xl mb-4 text-blue-600 group-hover:scale-110 transition-transform"><i class="fas fa-plus-circle"></i></div>
        <h3 class="text-lg font-bold text-slate-800">Criar Entrada</h3>
        <p class="text-slate-600 text-sm mt-2">Registrar nova entrada no estacionamento</p>
    </a>
    <a href="/session/list" class="card-hover stat-card rounded-xl shadow-md p-7 text-center hover:shadow-2xl border border-slate-100 group">
        <div class="text-5xl mb-4 text-emerald-600 group-hover:scale-110 transition-transform"><i class="fas fa-list"></i></div>
        <h3 class="text-lg font-bold text-slate-800">Ver Entradas</h3>
        <p class="text-slate-600 text-sm mt-2">Navegar por todas as entradas</p>
    </a>
    <a href="/report/dashboard" class="card-hover stat-card rounded-xl shadow-md p-7 text-center hover:shadow-2xl border border-slate-100 group">
        <div class="text-5xl mb-4 text-amber-600 group-hover:scale-110 transition-transform"><i class="fas fa-chart-pie"></i></div>
        <h3 class="text-lg font-bold text-slate-800">Relatórios</h3>
        <p class="text-slate-600 text-sm mt-2">Gerar relatórios de receita</p>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-shadow premium-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Total de Entradas</p>
                <p class="text-4xl font-bold text-slate-900 mt-3"><?php echo number_format((int)($dashboard['total_sessions'] ?? 0)); ?></p>
            </div>
            <div class="text-5xl text-blue-500 opacity-20"><i class="fas fa-car"></i></div>
        </div>
        <p class="text-xs text-slate-500 mt-3">Ativas e concluídas</p>
    </div>

    <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-shadow premium-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Receita Total</p>
                <p class="text-4xl font-bold text-emerald-700 mt-3">R$ <?php echo number_format((float)($dashboard['total_billing'] ?? 0), 2, ',', '.'); ?></p>
            </div>
            <div class="text-5xl text-emerald-500 opacity-20"><i class="fas fa-coins"></i></div>
        </div>
        <p class="text-xs text-slate-500 mt-3">Arrecadado até agora</p>
    </div>

    <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-shadow premium-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Horas Estacionadas</p>
                <p class="text-4xl font-bold text-slate-900 mt-3"><?php echo number_format((int)($dashboard['parked_hours'] ?? 0)); ?></p>
            </div>
            <div class="text-5xl text-amber-500 opacity-20"><i class="fas fa-hourglass-end"></i></div>
        </div>
        <p class="text-xs text-slate-500 mt-3">Total de horas estacionadas</p>
    </div>

    <div class="stat-card rounded-xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-shadow premium-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Tarifa Média</p>
                <p class="text-4xl font-bold text-slate-900 mt-3">R$ <?php echo number_format((float)($dashboard['average_tariff'] ?? 0), 2, ',', '.'); ?></p>
            </div>
            <div class="text-5xl text-violet-500 opacity-20"><i class="fas fa-tag"></i></div>
        </div>
        <p class="text-xs text-slate-500 mt-3">Por entrada</p>
    </div>
</div>

<!-- Receita por Tipo de Veículo -->
<div class="stat-card rounded-xl shadow-md p-8 mb-10 border border-slate-100 premium-shadow">
    <h2 class="text-2xl font-bold text-slate-900 mb-8 flex items-center gap-2">
        <i class="fas fa-chart-bar text-blue-600"></i> Receita por Tipo de Veículo
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php
        $vehicleTypes = [
            'car' => ['icon' => 'fas fa-car', 'label' => 'Carros', 'color' => 'blue', 'gradient' => 'from-blue-500 to-blue-600'],
            'motorcycle' => ['icon' => 'fas fa-motorcycle', 'label' => 'Motos', 'color' => 'red', 'gradient' => 'from-red-500 to-red-600'],
            'truck' => ['icon' => 'fas fa-truck', 'label' => 'Caminhões', 'color' => 'amber', 'gradient' => 'from-amber-500 to-amber-600'],
        ];

        foreach ($vehicleTypes as $type => $info):
            $typeData = isset($dashboard['by_type'][$type]) ? $dashboard['by_type'][$type] : ['quantity' => 0, 'billing' => 0.0];
        ?>
        <div class="gradient-to-br from-slate-50 to-slate-100 rounded-xl p-6 border border-slate-200 hover:shadow-lg transition-all hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br <?php echo $info['gradient']; ?> rounded-lg flex items-center justify-center">
                    <i class="<?php echo $info['icon']; ?> text-white text-lg"></i>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500"><?php echo $info['label']; ?></span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-slate-900">
                    <?php echo (int)($typeData['quantity'] ?? 0); ?>
                </p>
                <p class="text-xs text-slate-600 mt-1 mb-3">veículos</p>
                <div class="h-1 w-full bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r <?php echo $info['gradient']; ?> rounded-full" style="width: <?php echo ($typeData['quantity'] ?? 0) > 0 ? min(100, (($typeData['quantity'] ?? 0) / ($dashboard['total_sessions'] ?? 1)) * 100) : 0; ?>%"></div>
                </div>
                <p class="text-sm font-bold text-emerald-700 mt-4">
                    R$ <?php echo number_format((float)($typeData['billing'] ?? 0), 2, ',', '.'); ?>
                </p>
                <p class="text-xs text-slate-500">receita total</p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

