<?php

declare(strict_types=1);

/**
 * View de Criação de Entrada
 *
 * Exibe o formulário para criar uma nova entrada de estacionamento.
 * Inclui seleção do tipo de veículo, placa e horas estacionadas.
 *
 * @param array $errors Mensagens de validação (se houver)
 * @param array $oldInput Dados anteriores do formulário (para repopulação em caso de erro)
 */
?>

<div class="max-w-4xl mx-auto">
    <div class="stat-card rounded-xl shadow-md p-8 border border-slate-100 premium-shadow">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-plus-circle text-blue-600"></i> Criar Entrada
            </h1>
            <p class="text-slate-600 mt-1 text-sm">Registre uma nova entrada de veículo</p>
        </div>

        <!-- Display Validation Errors -->
        <?php if (!empty($errors)): ?>
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-300 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-red-800 mb-2 flex items-center gap-2 text-sm"><i class="fas fa-exclamation-circle"></i> Erros:</h3>
            <ul class="space-y-1">
                <?php foreach ($errors as $error): ?>
                <li class="text-red-700 text-sm">• <?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="/session/create" class="space-y-5">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Vehicle Type Selection -->
                <div>
                    <label for="vehicleType" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-car text-blue-600 mr-1"></i> Tipo de Veículo
                    </label>
                    <select name="vehicleType" id="vehicleType" required class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-slate-900 text-sm font-medium hover:border-slate-400 transition-all">
                        <option value="">Selecione...</option>
                        <option value="car" <?php echo ($oldInput['vehicleType'] ?? '') === 'car' ? 'selected' : ''; ?>>Carro (R$ 5/h)</option>
                        <option value="motorcycle" <?php echo ($oldInput['vehicleType'] ?? '') === 'motorcycle' ? 'selected' : ''; ?>>Moto (R$ 3/h)</option>
                        <option value="truck" <?php echo ($oldInput['vehicleType'] ?? '') === 'truck' ? 'selected' : ''; ?>>Caminhão (R$ 10/h)</option>
                    </select>
                </div>

                <!-- License Plate -->
                <div>
                    <label for="plate" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-tag text-blue-600 mr-1"></i> Placa
                    </label>
                    <input
                        type="text"
                        name="plate"
                        id="plate"
                        placeholder="ABC-1234"
                        value="<?php echo htmlspecialchars($oldInput['plate'] ?? ''); ?>"
                        required
                        class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm hover:border-slate-400 transition-all"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Parked Hours -->
                <div>
                    <label for="parkedHours" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-hourglass-end text-blue-600 mr-1"></i> Horas
                    </label>
                    <input
                        type="number"
                        name="parkedHours"
                        id="parkedHours"
                        placeholder="2"
                        value="<?php echo htmlspecialchars($oldInput['parkedHours'] ?? ''); ?>"
                        min="1"
                        required
                        class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm hover:border-slate-400 transition-all"
                    >
                </div>

                <!-- Entry Time -->
                <div>
                    <label for="entryTime" class="block text-xs font-bold text-slate-900 mb-2 uppercase tracking-wide">
                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Entrada
                    </label>
                    <input
                        type="datetime-local"
                        name="entryTime"
                        id="entryTime"
                        value="<?php echo htmlspecialchars($oldInput['entryTime'] ?? ''); ?>"
                        required
                        class="w-full px-4 py-2 border-2 border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm hover:border-slate-400 transition-all"
                    >
                </div>
            </div>

            <!-- Dynamic Tariff Preview -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-300 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-800 font-semibold"><i class="fas fa-calculator text-blue-600 mr-2"></i> Tarifa:</p>
                    <span id="tariffPreview" class="text-2xl font-bold text-emerald-700">R$ 0,00</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 justify-end pt-4 border-t border-slate-200">
                <a href="/session/list" class="px-5 py-2 text-slate-900 border-2 border-slate-300 rounded-lg hover:bg-slate-100 font-semibold transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="px-5 py-2 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg hover:shadow-lg font-semibold transition-all text-sm flex items-center gap-2 shadow-md">
                    <i class="fas fa-check-circle"></i> Criar
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
    } else {
        tariffPreview.textContent = 'R$ 0,00';
    }
}

vehicleTypeSelect.addEventListener('change', updateTariff);
parkedHoursInput.addEventListener('input', updateTariff);

// Set current time as default entry time
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('entryTime').value) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        document.getElementById('entryTime').value = `${year}-${month}-${day}T${hours}:${minutes}`;
    }
});
</script>
