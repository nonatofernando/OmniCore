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
            <button id="id_btn_modal_novo_cliente" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 transition btn-glow">
                <span class="text-xl">+</span> Novo Cliente
            </button>
        </header>

        <div class="flex flex-col md:flex-row gap-4 mb-10 w-full">
            <div class="relative flex-grow">
                <input type="text" id="inputBusca" placeholder="Buscar clientes..."
                    class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-11 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                <span class="absolute left-4 top-3.5 text-gray-500">🔍</span>
            </div>

            <div class="relative">
                <select id="filtroStatus"
                    class="appearance-none w-full md:w-48 bg-[#0f172a] border border-gray-800 py-3 px-6 pr-10 rounded-xl text-gray-300 outline-none focus:border-cyan-500 transition">
                    <option value="">Todos os Status</option>
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>
                <span class="absolute right-3 top-4 text-gray-500 pointer-events-none text-xs">▼</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[27rem] overflow-y-auto scrollbar-hide" id="gridClientes"> </div>
    </main>

    <script src="/js/clientes.js"></script>
</body>

</html>