<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos | Admin</title>
    <link rel="stylesheet" href="/css/pedidos.css">
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">

</head>

<body class="flex min-h-screen bg-[#020617] text-white">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Pedidos</h1>
                <p class="text-gray-400 text-sm">pedidos no total</p>
            </div>
            <button class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 transition shadow-[0_0_15px_rgba(0,229,255,0.3)]">
                <span class="text-xl">+</span> Novo Pedido
            </button>
        </header>

        <div class="flex gap-4 mb-6">
            <div class="flex-1 relative">
                <input type="text" placeholder="Buscar pedidos..." 
                       class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-11 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                <span class="absolute left-4 top-3.5 text-gray-500 text-lg">🔍</span>
            </div>
            <button class="bg-card border border-gray-800 p-3 rounded-xl hover:bg-gray-800 transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18L14 12v7l-4 3v-10L3 4z"/></svg>
            </button>
            <div class="relative">
                <select class="appearance-none bg-card border border-gray-800 py-3 px-6 pr-10 rounded-xl text-gray-300 outline-none focus:border-cyan-500">
                    <option>Todos</option>
                    <option>Processando</option>
                    <option>Enviado</option>
                </select>
                <span class="absolute right-3 top-4 text-gray-500 pointer-events-none">▼</span>
            </div>
        </div>

        <div class="bg-card rounded-xl overflow-hidden border border-gray-800/50 shadow-2xl">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-[10px] uppercase tracking-widest border-b border-gray-800">
                        <th class="px-6 py-5 font-bold">Pedido</th>
                        <th class="px-6 py-5 font-bold">Cliente</th>
                        <th class="px-6 py-5 font-bold">Status</th>
                        <th class="px-6 py-5 font-bold">Total</th>
                        <th class="px-6 py-5 text-right font-bold">Ações</th>
                    </tr>
                </thead>
                
            </table>
        </div>
    </main>
<script src="/js/pedidos.js"></script>
</body>
</html>