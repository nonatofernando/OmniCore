<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios | Admin</title>
    <link rel="stylesheet" href="/css/relatorios.css">
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
</head>

<body class="flex h-screen overflow-hidden">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8 overflow-hidden">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-white">Relatórios</h1>
            <p class="text-gray-400 text-sm">Análise detalhada do seu negócio</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Lucro Estimado</p>
                <h2 id="card-receita" class="text-2xl font-bold mt-2 text-white">...</h2>
            </div>
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Ticket Médio</p>
                <h2 id="card-ticket" class="text-2xl font-bold mt-2 text-white">...</h2>
            </div>
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Total Pedidos</p>
                <h2 id="card-pedidos" class="text-2xl font-bold mt-2 text-white">...</h2>
            </div>
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Valor em Estoque</p>
                <h2 id="card-estoque" class="text-2xl font-bold mt-2 text-white">...</h2>
            </div>
        </div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[calc(100vh-280px)] overflow-y-auto scrollbar-hide auto-rows-max">            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Faturamento Mensal</h3>
                <div class="h-[300px]">
                    <canvas id="chartFaturamento"></canvas>
                </div>
            </div>

            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Receita vs Custo (Lucro Bruto)</h3>
                <div class="h-[300px]">
                    <canvas id="chartReceitaCusto"></canvas>
                </div>
            </div>

            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Faturamento por Categoria</h3>
                <div class="h-[300px]">
                    <canvas id="chartCategorias"></canvas>
                </div>
            </div>

            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Produtos Vendidos</h3>
                <div class="relative w-full h-[300px]">
                    <canvas id="chartTopProdutos"></canvas>
                </div>
            </div>

            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Status dos Pedidos</h3>
                <div class="h-[300px] flex justify-center">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>

            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Saúde do Estoque</h3>
                <div class="h-[300px] flex justify-center">
                    <canvas id="chartEstoque"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script src="/js/relatorios.js"></script>
</body>

</html>