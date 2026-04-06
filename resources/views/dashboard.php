<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin</title>
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/css/dashboard.css">
</head>

<body class="flex h-screen overflow-hidden">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-bold">Dashboard</h1>
            <p class="text-gray-400 text-sm">Bem-vindo de volta! Aqui está a visão geral.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-card p-6 rounded-xl relative overflow-hidden">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Pedidos Hoje</p>
                <h2 id="total_pedidos_hoje" class="text-3xl font-bold mt-1"></h2>
                <h2 class="loader font-bold mt-1">-</h2>
                <p id="variacao_pedidos_hoje" class="text-green-400 text-xs mt-2 font-medium">
                    <span class="text-gray-500 font-normal">vs ontem</span>
                </p>
            </div>

            <div class="bg-card p-6 rounded-xl relative overflow-hidden">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Pendentes</p>
                <h2 id="total_pendentes" class="text-3xl font-bold mt-1"></h2>
                <h2 class="loader font-bold mt-1">-</h2>
                <p id="variacao_pendentes" class="text-red-400 text-xs mt-2 font-medium">
                    <span class="text-gray-500 font-normal">vs ontem</span>
                </p>
            </div>

            <div class="bg-card p-6 rounded-xl relative overflow-hidden">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Entregues</p>
                <h2 id="total_entregues" class="text-3xl font-bold mt-1"></h2>
                <h2 class="loader font-bold mt-1">-</h2>
                <p id="variacao_entregues" class="text-green-400 text-xs mt-2 font-medium">
                    <span class="text-gray-500 font-normal">vs ontem</span>
                </p>
            </div>

            <div class="bg-card p-6 rounded-xl relative overflow-hidden">
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Receita</p>
                <h2 id="total_receita" class="text-3xl font-bold mt-1">R$ </h2>
                <p id="variacao_receita" class="text-green-400 text-xs mt-2 font-medium">
                    <span class="text-gray-500 font-normal">vs ontem</span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-card p-6 rounded-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold">Visão Geral de Vendas</h3>
                    <span class="text-[10px] bg-cyan-900/30 text-cyan-400 px-2 py-1 rounded border border-cyan-800">
                        Esta Semana
                    </span>
                </div>

                <div id="chart-container" class="relative w-full" style="height: 300px;">
                    <div class="loader absolute inset-0 flex items-center justify-center bg-card z-10">
                        Carregando gráfico...
                    </div>
                    <div id="salesChart"></div>
                </div>
            </div>

            <div class="bg-card p-6 rounded-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold">Pedidos Recentes</h3>
                    <a href="/pedidos" class="text-cyan-400 text-xs hover:underline">Ver Todos</a>
                </div>

                <div class="loader flex items-center justify-center">
                    Carregando pedidos...
                </div>

                <div id="pedidos_recentes" class="space-y-5"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-8">
            <div class="bg-card p-6 rounded-xl">
                <h3 class="font-bold mb-6">Top Produtos</h3>

                <div class="loader flex items-center justify-center">
                    Carregando produtos...
                </div>

                <div id="top_produtos_vendidos" class="space-y-4"></div>
            </div>

            <div class="bg-card p-6 rounded-xl">
                <div id="performanceChart" ></div>
            </div>
        </div>
    </main>

    <script src="/js/dashboard.js"></script>

</body>

</html>