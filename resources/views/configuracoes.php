<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações | Admin</title>
    <link rel="stylesheet" href="/css/configuracoes.css">
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
</head>
<body class="flex min-h-screen">

    <?php include resource_path('views/partials/menu_lateral.php'); ?>

    <main class="flex-1 p-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-white">Configurações</h1>
            <p class="text-gray-400 text-sm">Gerencie as configurações do sistema</p>
        </header>

        <div class="max-w-2xl space-y-6">
            
            <section class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Perfil</h3>
                
                <form action="/configuracoes/salvar" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Nome</label>
                        <input type="text" name="nome" value="<?= $usuario->nome ?>" 
                               class="w-full bg-[#0f172a] border border-gray-800 rounded-lg py-3 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Email</label>
                        <input type="email" name="email" value="<?= $usuario->email ?>" 
                               class="w-full bg-[#0f172a] border border-gray-800 rounded-lg py-3 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Nome da Empresa</label>
                        <input type="text" name="empresa" placeholder="Ex: Minha Empresa Ltda" 
                               class="w-full bg-[#0f172a] border border-gray-800 rounded-lg py-3 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition">
                    </div>

                    <button type="submit" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 transition btn-glow mt-4">
                        <span>💾</span> Salvar
                    </button>
                </form>
            </section>

            <section class="bg-card p-8 rounded-2xl border border-gray-800/50">
                <h3 class="text-white font-bold mb-6">Conta</h3>
                
                <a href="/logout" class="inline-flex items-center gap-2 bg-[#f43f5e] hover:bg-red-500 text-white px-6 py-2.5 rounded-lg font-bold transition shadow-lg shadow-red-900/20">
                    <span>↪</span> Sair da Conta
                </a>
            </section>

        </div>
    </main>

    <script src="/js/configuracoes.js"></script>
</body>
</html>