<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Admin</title>
    <link rel="stylesheet" href="/css/clientes.css">
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
</head>

<body class="flex min-h-screen">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Clientes</h1>
                <p class="text-gray-400 text-sm" id="contador-clientes">3 clientes cadastrados</p>
            </div>
            <button class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 transition btn-glow">
                <span class="text-xl">+</span> Novo Cliente
            </button>
        </header>

        <div class="mb-10 max-w-sm">
            <div class="relative">
                <input type="text" id="inputBusca" placeholder="Buscar clientes..."
                    class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-11 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                <span class="absolute left-4 top-3.5 text-gray-500">🔍</span>
            </div>
        </div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[27rem] overflow-y-auto scrollbar-hide" id="gridClientes">        </div>
    </main>

    <script src="/js/clientes.js"></script>
</body>
</html>