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

    <main class="flex-1 p-8 lg:p-12">
        <header class="mb-10">
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Configurações</h1>
            <p class="text-gray-500 text-sm mt-2">Gerencie as informações da sua conta e do seu negócio.</p>
        </header>

        <div class="max-w-3xl space-y-8">

            <?php if (session('sucesso')): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-500 px-6 py-4 rounded-2xl flex items-center gap-3 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span class="font-bold text-sm"><?= session('sucesso') ?></span>
                </div>
            <?php endif; ?>

            <section class="bg-card p-8 rounded-3xl shadow-2xl border border-gray-800/30 transition-all duration-300 hover:border-cyan-500/30">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1.5 h-6 bg-[#00e5ff] rounded-full shadow-[0_0_10px_rgba(0,229,255,0.5)]"></div>
                    <h3 class="text-white text-xl font-bold">Perfil Pessoal</h3>
                </div>

                <form action="<?= route('configuracoes.salvar') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                    <div class="md:col-span-1">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Nome Completo</label>
                        <input type="text" name="nome" value="<?= $usuario->nome ?? '' ?>"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">E-mail de Contato</label>
                        <input type="email" name="email" value="<?= $usuario->email ?? '' ?>"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Nova Senha (deixe em branco para manter)</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-8 py-3 rounded-xl font-bold flex items-center gap-2 transition btn-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span>Salvar Perfil</span>
                        </button>
                    </div>
                </form>
            </section>

            <section class="bg-card p-8 rounded-3xl shadow-2xl border border-gray-800/30 transition-all duration-300 hover:border-purple-500/30">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1.5 h-6 bg-purple-500 rounded-full shadow-[0_0_10px_rgba(168,85,247,0.5)]"></div>
                    <h3 class="text-white text-xl font-bold">Configurações do Negócio</h3>
                </div>

                <form action="<?= route('configuracoes.salvar') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                    <div class="md:col-span-2">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Razão Social / Nome Fantasia</label>
                        <input type="text" name="empresa" value="<?= $usuario->empresa->nome ?? '' ?>" placeholder="Ex: Minha Empresa Ltda"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-purple-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">CNPJ</label>
                        <input type="text" name="cnpj" value="<?= $usuario->empresa->cnpj ?? '' ?>" placeholder="00.000.000/0000-00"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-purple-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Endereço</label>
                        <input type="text" name="endereco" value="<?= $usuario->empresa->endereco ?? '' ?>" placeholder="Rua, Número, Bairro"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-purple-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Cidade</label>
                        <input type="text" name="cidade" value="<?= $usuario->empresa->cidade ?? '' ?>" placeholder="Ex: São Paulo"
                            class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-purple-500 transition shadow-inner">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-gray-400 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Estado (UF)</label>
                        <select name="estado" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3.5 px-4 text-gray-300 focus:outline-none focus:border-purple-500 transition shadow-inner appearance-none">
                            <option value="">Selecione...</option>
                            <?php
                            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                            foreach ($estados as $uf): ?>
                                <option value="<?= $uf ?>" <?= (isset($usuario->empresa->estado) && $usuario->empresa->estado == $uf) ? 'selected' : '' ?>><?= $uf ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="bg-white hover:bg-gray-200 text-black px-8 py-3 rounded-xl font-bold flex items-center gap-2 transition shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                            <span>Atualizar Empresa</span>
                        </button>
                    </div>
                </form>
            </section>

            <section class="bg-card p-6 rounded-3xl border border-red-900/10 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-500/10 rounded-2xl text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                            <line x1="12" y1="2" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold">Encerrar Sessão</h3>
                        <p class="text-gray-500 text-xs tracking-wide">Desconectar sua conta com segurança.</p>
                    </div>
                </div>

                <button type="button" onclick="abrirModal('modalLogout')" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 px-6 py-3 rounded-xl font-bold transition duration-300">
                    Sair da Conta
                </button>
            </section>
        </div>
    </main>

    <div id="modalLogout" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all">
        <div class="bg-[#0f172a] border border-gray-800 p-8 rounded-3xl max-w-sm w-full shadow-2xl">
            <div class="w-16 h-16 bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <h3 class="text-white text-xl font-bold text-center mb-2">Sair do Sistema?</h3>
            <p class="text-gray-400 text-center text-sm mb-8">Você precisará fazer login novamente para acessar o painel.</p>
            <div class="flex gap-3">
                <button onclick="fecharModal('modalLogout')" class="flex-1 px-4 py-3 rounded-xl bg-gray-800 text-white font-bold hover:bg-gray-700 transition">Voltar</button>
                <a href="/logout" class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 text-center transition shadow-lg shadow-red-500/20">Sair</a>
            </div>
        </div>
    </div>

    <div id="modalSalvar" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all">
        <div class="bg-[#0f172a] border border-gray-800 p-8 rounded-3xl max-w-sm w-full shadow-2xl">
            <div class="w-16 h-16 bg-cyan-500/10 text-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <h3 class="text-white text-xl font-bold text-center mb-2">Salvar Alterações?</h3>
            <p class="text-gray-400 text-center text-sm mb-8">Os dados atualizados serão gravados permanentemente.</p>
            <div class="flex gap-3">
                <button onclick="fecharModal('modalSalvar')" class="flex-1 px-4 py-3 rounded-xl bg-gray-800 text-white font-bold hover:bg-gray-700 transition">Cancelar</button>
                <button id="confirmarBtn" class="flex-1 px-4 py-3 rounded-xl bg-cyan-500 text-black font-bold hover:bg-cyan-400 transition shadow-lg">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="/js/configuracoes.js"></script>
</body>

</html>