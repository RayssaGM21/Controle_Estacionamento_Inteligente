<?php

declare(strict_types=1);

/**
 * View de Edição de Entrada
 *
 * Exibe o formulário para editar uma entrada existente.
 *
 * @param object $session Objeto ParkingSession atual
 * @param array $errors Mensagens de validação (se houver)
 * @param array $oldInput Dados anteriores do formulário (para repopulação em caso de erro)
 */

$session = $session ?? null;
$errors = $errors ?? [];
$oldInput = $oldInput ?? [];

if (!$session) {
    echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4"><p class="text-red-700">Erro: Nenhuma sessão encontrada.</p></div>';
    return;
}
?>

<div class="max-w-4xl mx-auto">
    <div class="stat-card rounded-xl shadow-md p-8 border border-slate-100 premium-shadow">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-pencil-alt text-amber-600"></i> Editar Entrada
            </h1>
            <p class="text-slate-600 mt-1 text-sm">Placa: <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded text-sm"><?php echo htmlspecialchars((string)$session->getPlate()); ?></span></p>
        </div>

        <!-- Exibir Erros de Validação -->
        <?php if (!empty($errors)): ?>
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-300 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-red-800 mb-2 flex items-center gap-2 text-sm"><i class="fas fa-exclamation-circle"></i> Erros:</h3>
            <ul class="space-y-1">
                <?php foreach ($errors as $error): ?>
                <li class="text-red-700 text-sm">• <?php echo htmlspecialchars((string)$error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="/session/edit" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$session->getId()); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="vehicleType" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-car text-blue-600 mr-1"></i> Tipo de Veículo
                    </label>
                    <select name="vehicleType" id="vehicleType" required class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-slate-900 text-sm font-medium hover:border-slate-400 transition-all">
                        <option value="">Selecione...</option>
                        <option value="car" <?php echo ((string)($oldInput['vehicleType'] ?? $session->getVehicleType())) === 'car' ? 'selected' : ''; ?>>Carro (R$ 5/h)</option>
                        <option value="motorcycle" <?php echo ((string)($oldInput['vehicleType'] ?? $session->getVehicleType())) === 'motorcycle' ? 'selected' : ''; ?>>Moto (R$ 3/h)</option>
                        <option value="truck" <?php echo ((string)($oldInput['vehicleType'] ?? $session->getVehicleType())) === 'truck' ? 'selected' : ''; ?>>Caminhão (R$ 10/h)</option>
                    </select>
                </div>

                <div>
                    <label for="plate" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-tag text-blue-600 mr-1"></i> Placa (somente leitura)
                    </label>
                    <input
                        type="text"
                        name="plate"
                        id="plate"
                        value="<?php echo htmlspecialchars((string)$session->getPlate()); ?>"
                        readonly
                        class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg bg-slate-100 text-slate-600 font-mono text-sm cursor-not-allowed font-semibold"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="parkedHours" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-hourglass-end text-blue-600 mr-1"></i> Horas
                    </label>
                    <input
                        type="number"
                        name="parkedHours"
                        id="parkedHours"
                        placeholder="2"
                        value="<?php echo htmlspecialchars((string)($oldInput['parkedHours'] ?? $session->getParkedHours())); ?>"
                        min="1"
                        required
                        class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm hover:border-slate-400 transition-all"
                    >
                </div>

                <div>
                    <label for="entryTime" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Entrada
                    </label>
                    <input
                        type="datetime-local"
                        name="entryTime"
                        id="entryTime"
                        value="<?php echo htmlspecialchars((string)($oldInput['entryTime'] ?? $session->getEntryTime()->format('Y-m-d\TH:i'))); ?>"
                        required
                        class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm hover:border-slate-400 transition-all"
                    >
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-300 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-700 font-semibold uppercase tracking-wide mb-1"><i class="fas fa-calculator text-blue-600 mr-1"></i> Nova Tarifa:</p>
                        <span id="tariffPreview" class="text-xl font-bold text-emerald-700">R$ <?php echo number_format((float)$session->getFinalTariff(), 2, ',', '.'); ?></span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-600 mb-1 font-semibold">Original:</p>
                        <p class="text-xl font-bold text-slate-800">R$ <?php echo number_format((float)$session->getFinalTariff(), 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-4 border-t border-slate-200">
                <a href="/session/list" class="px-5 py-2 text-slate-900 border-2 border-slate-300 rounded-lg hover:bg-slate-100 font-semibold transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="px-5 py-2 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg font-semibold transition-all text-sm flex items-center gap-2 shadow-md">
                    <i class="fas fa-check-circle"></i> Atualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Atualiza a visualização de tarifa com base no tipo de veículo e horas
 */
const vehicleTypeSelect = document.getElementById('vehicleType');
const parkedHoursInput = document.getElementById('parkedHours');
const tariffPreview = document.getElementById('tariffPreview');

const tariffRates = {
    'car': 5,
    'motorcycle': 3,
    'truck': 10
};

function updateTariff() {
    const type = vehicleTypeSelect.value;
    const hours = parseInt(parkedHoursInput.value) || 0;
    
    if (type && hours > 0) {
        const tariff = (tariffRates[type] || 0) * hours;
        tariffPreview.textContent = 'R$ ' + tariff.toFixed(2).replace('.', ',');
    }
}

vehicleTypeSelect.addEventListener('change', updateTariff);
parkedHoursInput.addEventListener('input', updateTariff);
</script>
