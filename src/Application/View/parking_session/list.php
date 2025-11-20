<?php

declare(strict_types=1);

/**
 * View de Listagem de Entradas
 *
 * Exibe todas as entradas de estacionamento em formato de tabela com opções de editar/excluir.
 *
 * @param array $sessions Array de objetos ParkingSession
 */
?>

<div class="mb-10">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900">Entradas de Estacionamento</h1>
            <p class="text-slate-600 mt-2 text-lg">Total de entradas: <span class="font-bold text-slate-900"><?php echo count($sessions ?? []); ?></span></p>
        </div>
        <a href="/session/create" class="px-7 py-3 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1 flex items-center gap-2 shadow-md">
            <i class="fas fa-plus"></i> Nova Entrada
        </a>
    </div>

    <?php if (empty($sessions)): ?>
    <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-16 text-center premium-shadow">
        <p class="text-6xl mb-6 text-slate-300"><i class="fas fa-inbox"></i></p>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Nenhuma entrada encontrada</h3>
        <p class="text-slate-600 text-lg mb-8">Ainda não há entradas no estacionamento.</p>
        <a href="/session/create" class="px-7 py-3 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1 inline-flex items-center gap-2 shadow-md">
            <i class="fas fa-plus"></i> Criar primeira entrada
        </a>
    </div>
    <?php else: ?>
    <div class="stat-card rounded-xl shadow-md overflow-hidden border border-slate-100 premium-shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-white border-b-2 border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Placa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tipo</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Horas</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tarifa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Horário de Entrada</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($sessions as $session): ?>
                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo htmlspecialchars((string)$session->getId()); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-800 font-mono font-semibold"><?php echo htmlspecialchars($session->getPlate()); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <?php
                            $types = ['car' => '<span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"><i class="fas fa-car"></i> Carro</span>', 'motorcycle' => '<span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold"><i class="fas fa-motorcycle"></i> Moto</span>', 'truck' => '<span class="inline-flex items-center gap-2 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold"><i class="fas fa-truck"></i> Caminhão</span>'];
                            echo $types[$session->getVehicleType()] ?? htmlspecialchars((string)$session->getVehicleType());
                            ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-800 font-medium"><?php echo htmlspecialchars((string)$session->getParkedHours()); ?>h</td>
                        <td class="px-6 py-4 text-sm font-bold text-emerald-700">R$ <?php echo number_format($session->getFinalTariff(), 2, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-600"><?php echo htmlspecialchars($session->getEntryTime()->format('d/m/Y H:i')); ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="/session/edit?id=<?php echo htmlspecialchars((string)$session->getId()); ?>" 
                                   class="px-4 py-2 bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-lg text-xs hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                <button onclick="deleteSession(<?php echo htmlspecialchars((string)$session->getId()); ?>)" 
                                        class="px-4 py-2 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg text-xs hover:shadow-lg font-semibold transition-all duration-200 hover:-translate-y-1">
                                    <i class="fas fa-trash mr-1"></i> Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
/**
 * Exclui sessão com confirmação
 */
function deleteSession(sessionId) {
            Swal.fire({
        title: 'Excluir entrada?',
        text: 'Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/session/delete?id=' + sessionId;
        }
    });
}
</script>
