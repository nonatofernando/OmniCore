<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/css/relatorios.css">
</head>
<body class="flex min-h-screen">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-white">Relatórios</h1>
            <p class="text-gray-400 text-sm">Análise detalhada do seu negócio</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Receita Total</p>
                <h2 class="text-2xl font-bold mt-2 text-white">R$ <?= number_format($receita_total, 2, ',', '.') ?></h2>
            </div>
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Ticket Médio</p>
                <h2 class="text-2xl font-bold mt-2 text-white">R$ <?= number_format($ticket_medio, 2, ',', '.') ?></h2>
            </div>
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Total Produtos</p>
                <h2 class="text-2xl font-bold mt-2 text-white"><?= $total_produtos ?></h2>
            </div>
            <div class="bg-card p-6 rounded-xl border border-gray-800/50">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Estoque Total</p>
                <h2 class="text-2xl font-bold mt-2 text-white"><?= $estoque_total ?></h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Receita por Pagamento</h3>
                <div class="h-[300px]">
                    <canvas id="chartPagamentos"></canvas>
                </div>
            </div>

            <div class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Pedidos por Status</h3>
                <div class="h-[300px] flex flex-col items-center">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        const dadosPagamento = <?= json_encode($pagamentos) ?>;
        const dadosStatus = <?= json_encode($status_pedidos) ?>;
    </script>
    <script src="/js/relatorios.js"></script>
</body>
</html>