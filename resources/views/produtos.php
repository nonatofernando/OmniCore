<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Admin</title>
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/css/produtos.css">
</head>
<body class="flex min-h-screen">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Produtos</h1>
                <p class="text-gray-400 text-sm"><?= count($produtos) ?> produtos cadastrados</p>
            </div>
            <button class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 transition btn-glow">
                <span class="text-xl">+</span> Novo Produto
            </button>
        </header>

        <div class="mb-10 max-w-sm">
            <div class="relative">
                <input type="text" id="inputBusca" placeholder="Buscar produtos..." 
                       class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-11 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                <span class="absolute left-4 top-3.5 text-gray-500">🔍</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridProdutos">
            <?php foreach ($produtos as $produto): ?>
                <div class="bg-card p-6 rounded-2xl border border-gray-800/50 hover:border-cyan-500/30 transition-all duration-300 group product-card">
                    <div class="w-12 h-12 bg-cyan-950/30 border border-cyan-800/40 rounded-xl flex items-center justify-center text-cyan-400 mb-5 text-xl">
                        📦
                    </div>

                    <h3 class="font-bold text-lg mb-1 group-hover:text-cyan-400 transition"><?= $produto->nome ?></h3>
                    <p class="text-gray-500 text-xs uppercase tracking-tighter mb-4"><?= $produto->categoria ?></p>

                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-cyan-400 text-2xl font-bold">R$ <?= number_format($produto->preco, 2, ',', '.') ?></p>
                            <p class="text-gray-500 text-xs mt-2">Estoque: <span class="text-gray-300"><?= $produto->estoque ?></span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-amber-400 font-bold text-sm mb-2">★ <?= number_format($produto->nota, 1) ?></p>
                            <p class="text-gray-500 text-[11px]"><?= $produto->vendidos ?> vendidos</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="/js/produtos.js"></script>
</body>
</html>