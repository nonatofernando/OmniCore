<div id="modal_novo_pedido" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm close-modal-trigger"></div>
    <div class="relative bg-[#0f172a] border border-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center bg-[#0f172a]">
            <h3 class="text-xl font-bold text-white">Novo Pedido</h3>
            <button type="button" class="text-gray-400 hover:text-white transition close-modal-btn text-2xl">&times;</button>
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Cliente</label>
                    <select id="id_cliente_select" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 focus:outline-none focus:border-cyan-500 transition"></select>
                </div>
                <div class="bg-[#020617]/50 p-4 rounded-xl border border-gray-800/50">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-3 tracking-wider">Produtos</label>
                    <div id="produtos_container" class="space-y-3">
                        <div class="produto_item flex gap-2">
                            <select class="produto_id flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300 focus:border-cyan-500 outline-none"></select>
                            <input type="number" class="quantidade w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300" placeholder="Qtd" min="1" value="1">
                            <button type="button" class="remover_produto bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 rounded-lg transition-all font-bold">X</button>
                        </div>
                    </div>
                    <button type="button" id="adicionar_produto" class="mt-4 text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 transition">
                        <span class="text-lg">+</span> Adicionar outro produto
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Total (R$)</label>
                        <input type="number" id="total" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white focus:border-cyan-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Pagamento</label>
                        <select id="metodo_pagamento" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 outline-none focus:border-cyan-500">
                            <option value="pix">PIX</option>
                            <option value="cartao_credito">Cartão de Crédito</option>
                            <option value="cartao_debito">Cartão de Débito</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Observações</label>
                    <textarea id="observacoes" rows="2" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 outline-none focus:border-cyan-500"></textarea>
                </div>
                <div id="erro_novo_pedido" class="text-red-400 text-sm italic"></div>
            </div>
        </div>
        <div class="px-6 py-4 bg-[#0f172a] border-t border-gray-800 flex justify-end gap-3">
            <button type="button" class="px-6 py-2.5 text-gray-400 hover:text-white font-bold transition close-modal-btn">Cancelar</button>
            <button type="button" id="salvar_pedido" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-8 py-2.5 rounded-xl font-bold transition shadow-[0_0_20px_rgba(0,229,255,0.3)]">
                Salvar Pedido
            </button>
        </div>
    </div>
</div>

<div id="modal_detalhes_pedido" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm close-modal-trigger"></div>
    <div class="relative bg-[#0f172a] border border-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center bg-[#0f172a]">
            <h3 class="text-xl font-bold text-white">Editar Pedido</h3>
            <button type="button" class="text-gray-400 hover:text-white transition close-modal-btn text-2xl">&times;</button>
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar modal-body"></div>
        <div class="px-6 py-4 bg-[#0f172a] border-t border-gray-800 flex justify-between gap-3">
            <button type="button" id="btn_excluir_pedido" class="px-4 py-2.5 text-red-500 hover:bg-red-500/10 font-bold transition rounded-xl border border-red-500/20">
                Excluir Pedido
            </button>
            <div class="flex gap-3">
                <button type="button" class="px-6 py-2.5 text-gray-400 hover:text-white font-bold transition close-modal-btn">Cancelar</button>
                <button type="button" id="btn_atualizar_pedido" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-8 py-2.5 rounded-xl font-bold transition shadow-[0_0_20px_rgba(0,229,255,0.3)]">
                    Salvar Alterações
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modal_novo_produto" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity">
    <div class="bg-[#020617] w-full max-w-3xl max-h-[90vh] rounded-2xl border border-gray-800 shadow-2xl flex flex-col relative">

        <header class="p-6 border-b border-gray-800 flex justify-between items-center bg-[#0f172a] rounded-t-2xl">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <span class="text-cyan-400">+</span> Cadastrar Produto
            </h2>
            <button class="close-modal-btn text-gray-500 hover:text-white text-3xl leading-none transition">&times;</button>
        </header>

        <div class="p-6 overflow-y-auto space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Nome do Produto *</label>
                    <input type="text" id="nome_produto" placeholder="Ex: Teclado Mecânico RGB" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Descrição</label>
                    <textarea id="descricao_produto" rows="2" placeholder="Detalhes do produto..." class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Preço de Venda (R$) *</label>
                    <input type="number" step="0.01" id="preco_produto" placeholder="0.00" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Custo (R$)</label>
                    <input type="number" step="0.01" id="custo_produto" placeholder="0.00" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Categoria</label>
                    <select id="categoria_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                        <option value="">Sem Categoria</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Status</label>
                    <select id="status_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>

                <div class="md:col-span-2 grid grid-cols-3 gap-5 bg-cyan-500/5 p-4 rounded-xl border border-cyan-500/10">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Estoque Atual</label>
                        <input type="number" id="estoque_produto" value="0" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Estoque Mín.</label>
                        <input type="number" id="estoque_minimo_produto" value="0" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Estoque Máx.</label>
                        <input type="number" id="estoque_maximo_produto" placeholder="Opcional" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">URL da Imagem</label>
                    <input type="text" id="imagem_url_produto" placeholder="https://..." class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>

            </div>
        </div>

        <footer class="p-6 border-t border-gray-800 bg-[#0f172a] flex justify-end gap-3 rounded-b-2xl items-center">
            <span id="erro_novo_produto" class="text-red-400 text-xs font-bold mr-auto"></span>
            <button class="close-modal-btn px-5 py-2.5 rounded-lg font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition">Cancelar</button>
            <button id="salvar_produto" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-6 py-2.5 rounded-lg font-bold transition shadow-[0_0_15px_rgba(0,229,255,0.2)]">Salvar Produto</button>
        </footer>
    </div>
</div>


<div id="modal_detalhes_produto" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity">
    <div class="bg-[#020617] w-full max-w-3xl max-h-[90vh] rounded-2xl border border-gray-800 shadow-2xl flex flex-col relative">

        <header class="p-6 border-b border-gray-800 flex justify-between items-center bg-[#0f172a] rounded-t-2xl">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <span class="text-cyan-400">✎</span> Editar Produto
            </h2>
            <button class="close-modal-btn text-gray-500 hover:text-white text-3xl leading-none transition">&times;</button>
        </header>

        <div class="p-6 overflow-y-auto space-y-5">
            <input type="hidden" id="edit_produto_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Nome do Produto *</label>
                    <input type="text" id="edit_nome_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Descrição</label>
                    <textarea id="edit_descricao_produto" rows="2" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Preço de Venda (R$) *</label>
                    <input type="number" step="0.01" id="edit_preco_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Custo (R$)</label>
                    <input type="number" step="0.01" id="edit_custo_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Categoria</label>
                    <select id="edit_categoria_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                        <option value="">Sem Categoria</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Status</label>
                    <select id="edit_status_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>

                <div class="md:col-span-2 grid grid-cols-3 gap-5 bg-cyan-500/5 p-4 rounded-xl border border-cyan-500/10">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Estoque Atual</label>
                        <input type="number" id="edit_estoque_produto" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Estoque Mín.</label>
                        <input type="number" id="edit_estoque_minimo_produto" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">Estoque Máx.</label>
                        <input type="number" id="edit_estoque_maximo_produto" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1 tracking-widest">URL da Imagem</label>
                    <input type="text" id="edit_imagem_url_produto" class="w-full bg-[#0f172a] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                </div>

            </div>
        </div>

        <footer class="p-6 border-t border-gray-800 bg-[#0f172a] flex justify-between items-center rounded-b-2xl">
            <button id="btn_excluir_produto" class="text-red-500 hover:text-red-400 hover:bg-red-500/10 px-4 py-2 rounded-lg font-bold text-sm transition">Excluir Produto</button>

            <div class="flex gap-3">
                <button class="close-modal-btn px-5 py-2.5 rounded-lg font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition">Cancelar</button>
                <button id="btn_atualizar_produto" class="bg-[#00e5ff] hover:bg-cyan-400 text-black px-6 py-2.5 rounded-lg font-bold transition shadow-[0_0_15px_rgba(0,229,255,0.2)]">Salvar Alterações</button>
            </div>
        </footer>
    </div>
</div>
<div id="modal_feedback" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-[#020617] border border-gray-800 rounded-2xl w-full max-w-md p-6 shadow-xl animate-fade-in">
        <div class="flex justify-center mb-4">
            <div id="modal_feedback_icon" class="w-14 h-14 flex items-center justify-center rounded-full text-2xl font-bold"></div>
        </div>
        <h2 id="modal_feedback_titulo" class="text-center text-lg font-semibold text-white mb-2"></h2>
        <p id="modal_feedback_msg" class="text-center text-gray-400 text-sm mb-6"></p>
        <div class="flex justify-center">
            <button id="modal_feedback_btn" class="px-5 py-2 rounded-lg font-semibold transition-all close-modal-btn"></button>
        </div>
    </div>
</div>
<div id="modal_novo_cliente" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-[#0f172a] border border-gray-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Cadastrar Novo Cliente</h2>
            <button class="close-modal-trigger text-gray-500 hover:text-white">✕</button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Nome Completo</label>
                <input type="text" id="nome_cliente" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 focus:border-cyan-500 outline-none transition">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">E-mail</label>
                    <input type="email" id="email_cliente" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 focus:border-cyan-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Telefone</label>
                    <input type="text" id="telefone_cliente" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 focus:border-cyan-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Empresa</label>
                <input type="text" id="empresa_cliente" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 focus:border-cyan-500 outline-none">
            </div>
        </div>
        <div class="p-6 border-t border-gray-800 flex justify-end gap-3">
            <button class="close-modal-trigger px-5 py-2 text-gray-400 hover:text-white transition">Cancelar</button>
            <button id="salvar_cliente" class="bg-cyan-500 hover:bg-cyan-400 text-black px-6 py-2 rounded-lg font-bold transition btn-glow">Salvar Cliente</button>
        </div>
    </div>
</div>

<div id="modal_detalhes_cliente" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-[#0f172a] border border-gray-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Editar Cliente</h2>
            <button class="close-modal-trigger text-gray-500 hover:text-white">✕</button>
        </div>
        <div class="modal-body p-6">
            </div>
        <div class="p-6 border-t border-gray-800 flex justify-between gap-3">
            <button id="btn_excluir_cliente" class="text-red-500 hover:text-red-400 font-bold text-sm">Excluir Cliente</button>
            <div class="flex gap-3">
                <button class="close-modal-trigger px-5 py-2 text-gray-400 hover:text-white transition">Cancelar</button>
                <button id="btn_atualizar_cliente" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded-lg font-bold transition">Atualizar Dados</button>
            </div>
        </div>
    </div>
</div>
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.2s ease-out;
    }
</style>