<?php
declare(strict_types=1);

require __DIR__ . '/initialization.php';

$result = $parkingService->add($_POST);


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle Estacionamento Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/css/all.css">

</head>
<body class="bg-[url('./images/parking.jpg')] bg-cover min-h-screen flex justify-center items-center p-4">
    <div class="w-full max-w-4xl">
        <div class="bg-blue-950/20 backdrop-blur rounded-t-2xl p-10 pb-0 border-b-0 border border-neutral-700/50">
            <h1 class="text-stone-100 font-bold text-3xl tracking-wide">Registro de Sessão</h1>
            <p class="text-gray-400 mt-1">Sistema de controle de estacionamento</p>
        </div>
        <div class="bg-blue-950/20 backdrop-blur rounded-b-2xl p-10 pt-6 border-t-0 shadow-sm shadow-blue-100/10 border border-neutral-700/50">
            <div class="border border-blue-500/20 mb-5"></div>
            <form method="post" action="" class="grid grid-cols-2 gap-6">
                
                
                <div class="flex flex-col">
                    <label class="text-blue-500 text-sm mb-2 font-medium">Placa do veículo</label>
                    <input name="plate" type="text" placeholder="ABC-1234" class="px-4 py-3 bg-slate-900 placeholder-gray-500 border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                </div>

                <div class="flex flex-col">
                    <label class="text-blue-500 text-sm mb-2 font-medium">Tipo de veículo</label>
                    <select name="vehicleType" type="string" class="px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 cursor-pointer">
                        <option value="car" class="bg-slate-900">Carro</option>
                        <option value="motorcycle" class="bg-slate-900">Moto</option>
                        <option value="truck" class="bg-slate-900">Caminhão</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-blue-500 text-sm mb-2 font-medium">Horario de Entrada</label>
                    <input name="entryTime" type="datetime-local" class="px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                </div>

                <div class="flex flex-col">
                    <label class="text-blue-500 text-sm mb-2 font-medium">Horas Estacionadas</label>
                    <input name="parkedHours" type="number" placeholder="0" min="0" step="1" class="px-4 py-3 bg-slate-900 placeholder-white border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                </div>

                <div class="p4 flex items-center justify-center col-span-full">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 hover:scale-105 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-blue-900/50">Registrar Sessão</button>
                </div>
            </form>
            <div class="grid grid-cols-2 gap-6"></div>
        </div>
    </div>
</body>
</html>