<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Admin</title>
    <link rel="stylesheet" href="/css/produtos.css"> 
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="flex min-h-screen bg-[#020617] text-white">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Produtos</h1>
                <p class="text-gray-400 text-sm" id="total_produtos">Carregando...</p>
            </div>
            <button id="btn_novo_produto" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 transition shadow-[0_0_15px_rgba(0,229,255,0.3)]">
                <span class="text-xl">+</span> Novo Produto
            </button>
        </header>

        <div class="flex gap-4 mb-6">
            <div class="flex-1 relative">
                <input id="input_busca" type="text" placeholder="Buscar produtos por nome"
                    class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-11 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                <span class="absolute left-4 top-3.5 text-gray-500 text-lg">🔍</span>
            </div>
            <div class="relative">
                <select id="filtro_categoria" class="appearance-none bg-card border border-gray-800 py-3 px-6 pr-10 rounded-xl text-gray-300 outline-none focus:border-cyan-500">
                    <option value="">Todas as Categorias</option>
                    </select>
                <span class="absolute right-3 top-4 text-gray-500 pointer-events-none">▼</span>
            </div>
        </div>

        <div class="bg-card rounded-xl overflow-auto border border-gray-800/50 shadow-2xl"
            style="height: calc(95vh - 180px);">
            <table id="produtos-table" class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-[10px] uppercase tracking-widest border-b border-gray-800">
                        <th class="px-6 py-5 font-bold">Produto</th>
                        <th class="px-6 py-5 font-bold">Categoria</th>
                        <th class="px-6 py-5 font-bold">Preço</th>
                        <th class="px-6 py-5 font-bold">Estoque</th>
                        <th class="px-6 py-5 text-right font-bold">Ações</th>
                    </tr>
                </thead>
                <tbody id="produtos_table_tbody">
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    
    <script src="/js/produtos.js"></script>
</body>

</html>