<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/clientes.css">
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gridClientes">

            <div class="bg-card p-6 rounded-2xl border border-gray-800/50 hover:border-cyan-500/30 transition-all duration-300 cliente-card relative group">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-cyan-950/40 border border-cyan-800/50 rounded-lg flex items-center justify-center text-cyan-400 font-bold text-lg">
                        J
                    </div>
                    <div>
                        <h3 class="font-bold text-white group-hover:text-cyan-400 transition">João Silva</h3>
                        <p class="text-gray-500 text-xs tracking-tight">Tech Solutions</p>
                    </div>
                    <button class="absolute top-6 right-6 text-gray-700 hover:text-white transition">•••</button>
                </div>
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <span>📧</span> joao@empresa.com
                    </div>
                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <span>📞</span> (11) 99999-9999
                    </div>
                </div>
                <span class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-900/20 text-emerald-500 border border-emerald-800/30">
                    Ativo
                </span>
            </div>

            <div class="bg-card p-6 rounded-2xl border border-gray-800/50 hover:border-cyan-500/30 transition-all duration-300 cliente-card relative group">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-cyan-950/40 border border-cyan-800/50 rounded-lg flex items-center justify-center text-cyan-400 font-bold text-lg">
                        M
                    </div>
                    <div>
                        <h3 class="font-bold text-white group-hover:text-cyan-400 transition">Maria Souza</h3>
                        <p class="text-gray-500 text-xs tracking-tight">Design Studio</p>
                    </div>
                    <button class="absolute top-6 right-6 text-gray-700 hover:text-white transition">•••</button>
                </div>
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <span>📧</span> maria@design.com
                    </div>
                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <span>📞</span> (11) 88888-8888
                    </div>
                </div>
                <span class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-red-900/20 text-red-500 border border-red-800/30">
                    Inativo
                </span>
            </div>

        </div>
    </main>

    <script src="/js/clientes.js"></script>
</body>
</html>