<?php

declare(strict_types=1);

/**
 * View do Painel de Relatórios
 *
 * Exibe métricas chave e estatísticas em formato de painel executivo.
 *
 * @param array $dashboard Array de estatísticas contendo métricas principais
 */
?>

<div class="mb-10">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900">Painel de Relatórios</h1>
            <p class="text-slate-600 mt-2 text-lg">Resumo executivo e métricas principais</p>
        </div>
        <a href="/report/export-csv" class="px-7 py-3 bg-gradient-to-br from-emerald-600 to-emerald-700 text-white rounded-xl hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-md">
            <i class="fas fa-download"></i> Exportar CSV
        </a>
    </div>
</div>

<?php
$dashboard = $dashboard ?? [];
$dashboard['total_sessions'] = (int)($dashboard['total_sessions'] ?? 0);
$dashboard['total_billing'] = (float)($dashboard['total_billing'] ?? 0.0);
$dashboard['parked_hours'] = (int)($dashboard['parked_hours'] ?? 0);
$dashboard['average_tariff'] = (float)($dashboard['average_tariff'] ?? 0.0);
$dashboard['by_type'] = $dashboard['by_type'] ?? [];
?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="stat-card rounded-xl shadow-md p-7 border border-slate-100 hover:shadow-xl transition-all duration-300 premium-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Total de Entradas</p>
                <p class="text-4xl font-bold text-slate-900 mt-3"><?php echo number_format((int)($dashboard['total_sessions'] ?? 0)); ?></p>
                <p class="text-xs text-slate-500 mt-3">Ativas e concluídas</p>
            </div>
            <div class="text-5xl text-blue-400 opacity-20"><i class="fas fa-door-open"></i></div>
        </div>
    </div>

    <div class="stat-card rounded-xl shadow-md p-7 border border-slate-100 hover:shadow-xl transition-all duration-300 premium-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Receita Total</p>
                <p class="text-4xl font-bold text-emerald-700 mt-3">R$ <?php echo number_format((float)($dashboard['total_billing'] ?? 0), 2, ',', '.'); ?></p>
                <p class="text-xs text-slate-500 mt-3">Todas as sessões</p>
            </div>
            <div class="text-5xl text-emerald-400 opacity-20"><i class="fas fa-coins"></i></div>
        </div>
    </div>

    <div class="stat-card rounded-xl shadow-md p-7 border border-slate-100 hover:shadow-xl transition-all duration-300 premium-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Horas Estacionadas</p>
                <p class="text-4xl font-bold text-slate-900 mt-3"><?php echo number_format((int)($dashboard['parked_hours'] ?? 0)); ?></p>
                <p class="text-xs text-slate-500 mt-3">Total de horas</p>
            </div>
            <div class="text-5xl text-amber-400 opacity-20"><i class="fas fa-hourglass-end"></i></div>
        </div>
    </div>

    <div class="stat-card rounded-xl shadow-md p-7 border border-slate-100 hover:shadow-xl transition-all duration-300 premium-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Tarifa Média</p>
                <p class="text-4xl font-bold text-slate-900 mt-3">R$ <?php echo number_format((float)($dashboard['average_tariff'] ?? 0), 2, ',', '.'); ?></p>
                <p class="text-xs text-slate-500 mt-3">Por sessão</p>
            </div>
            <div class="text-5xl text-violet-400 opacity-20"><i class="fas fa-tag"></i></div>
        </div>
    </div>
</div>

<!-- Receita por Tipo de Veículo -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <div class="stat-card rounded-xl shadow-md p-8 border border-slate-100 premium-shadow">
        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <i class="fas fa-chart-doughnut text-blue-600"></i> Distribuição de Receita
        </h2>
        <canvas id="revenueChart"></canvas>
    </div>

    <div class="stat-card rounded-xl shadow-md p-8 border border-slate-100 premium-shadow">
        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <i class="fas fa-list-check text-emerald-600"></i> Por Tipo de Veículo
        </h2>
        <div class="space-y-3">
            <?php
            $vehicleTypes = [
                'car' => ['icon' => 'fas fa-car', 'label' => 'Carros', 'gradient' => 'from-blue-500 to-blue-600'],
                'motorcycle' => ['icon' => 'fas fa-motorcycle', 'label' => 'Motos', 'gradient' => 'from-red-500 to-red-600'],
                'truck' => ['icon' => 'fas fa-truck', 'label' => 'Caminhões', 'gradient' => 'from-amber-500 to-amber-600'],
            ];

            foreach ($vehicleTypes as $type => $info):
                $typeData = isset($dashboard['by_type'][$type]) ? $dashboard['by_type'][$type] : ['quantity' => 0, 'billing' => 0.0];
            ?>
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg border border-slate-200 hover:shadow-md transition-all">
                <div class="flex items-center gap-4 flex-1">
                    <div class="w-12 h-12 bg-gradient-to-br <?php echo $info['gradient']; ?> rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="<?php echo $info['icon']; ?> text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900"><?php echo $info['label']; ?></p>
                        <p class="text-xs text-slate-500"><?php echo (int)($typeData['quantity'] ?? 0); ?> entradas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-emerald-700">R$ <?php echo number_format((float)($typeData['billing'] ?? 0), 2, ',', '.'); ?></p>
                    <p class="text-xs text-slate-500">receita</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="flex gap-4 justify-center mb-12">
    <a href="/session/list" class="px-8 py-3 bg-slate-200 text-slate-900 rounded-xl hover:bg-slate-300 font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-sm">
        <i class="fas fa-arrow-left"></i> Voltar às Entradas
    </a>
    <a href="/report/detailed" class="px-8 py-3 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-md">
        Relatório Detalhado <i class="fas fa-arrow-right"></i>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3"></script>
<script>
/**
 * Inicializa o gráfico de receita em forma de rosca
 */
const ctx = document.getElementById('revenueChart').getContext('2d');

const chartData = {
    labels: [
        'Carros',
        'Motos',
        'Caminhões'
    ],
    datasets: [{
        label: 'Revenue',
        data: [
            <?php echo (float)($dashboard['by_type']['car']['billing'] ?? 0); ?>,
            <?php echo (float)($dashboard['by_type']['motorcycle']['billing'] ?? 0); ?>,
            <?php echo (float)($dashboard['by_type']['truck']['billing'] ?? 0); ?>
        ],
        backgroundColor: [
            'rgba(59, 130, 246, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(234, 179, 8, 0.8)'
        ],
        borderColor: [
            'rgb(59, 130, 246)',
            'rgb(239, 68, 68)',
            'rgb(234, 179, 8)'
        ],
        borderWidth: 2
    }]
};

new Chart(ctx, {
    type: 'doughnut',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
