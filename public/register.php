<?php


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle Estacionamento Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-[url('./images/parking.jpg')] bg-cover min-h-screen flex justify-center items-center p-4">
    <div class="w-full max-w-4xl">
        <div class="bg-blue-950/30 backdrop-blur rounded-t-2xl p-5 border rounde border-amber-400">
            <h1 class="text-amber-400 font-bold text-3xl tracking-wide">Registro de Sessão</h1>
            <p class="text-gray-400 mt-1">Sistema de controle de estacionamento</p>
        </div>
        <div class="bg-blue-950/30 backdrop-blur rounded-b-2xl p-8 border rounde border-amber-400">
            <form class="grid grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label for="" class="text-amber-400 text-sm mb-2 font-medium">Placa do veículo</label>
                    <input type="text" placeholder="ABC-1234" class="px-4 py-3 bg-slate-900 placeholder-gray-500 border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none">
                </div>

                <div class="flex flex-col">
                    <label for="" class="text-amber-400 text-sm mb-2 font-medium">Tipo de veículo</label>
                    <select type="datetime-local" class="px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 cursor-pointer">
                        <option value="" class="bg-slate-900">Carro</option>
                        <option value="" class="bg-slate-900">Moto</option>
                        <option value="" class="bg-slate-900">Caminhão</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="" class="text-amber-400 text-sm mb-2 font-medium">Horario de Entrada</label>
                    <input type="datetime-local" class="px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none">
                </div>

                <div class="flex flex-col">
                    <label for="" class="text-amber-400 text-sm mb-2 font-medium">Horas Estacionadas</label>
                    <input type="number" placeholder="0" min="0" step="1" class="px-4 py-3 bg-slate-900 placeholder-white border border-slate-700 rounded-lg text-gray-200 transition-all duration-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none">
                </div>
            </form>
            <div class="grid grid-cols-2 gap-6"></div>
        </div>
    </div>
</body>
</html>