<div id="modal_novo_pedido" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm close-modal-trigger"></div>

    <div class="relative bg-[#0f172a] border border-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Novo Pedido</h3>
            <button type="button" class="close-modal-btn text-gray-400 hover:text-white text-2xl">&times;</button>
        </div>

        <div class="p-6 max-h-[70vh] overflow-y-auto">

            <div class="grid gap-4">

                <div>
                    <label class="text-xs font-bold text-gray-500">Cliente</label>
                    <select id="id_novo_produto_cliente_select" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300"></select>
                </div>

                <div class="bg-[#020617]/50 p-4 rounded-xl border border-gray-800">
                    <label class="text-xs font-bold text-gray-500 mb-3 block">Produtos</label>

                    <div id="produtos_container" class="space-y-3">

                        <div class="produto_item flex gap-2">
                            <select class="produto_id flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300"></select>

                            <input type="number" class="quantidade w-20 bg-[#020617] border border-gray-800 rounded-lg px-3 text-sm text-gray-300" value="1">

                            <button type="button" class="remover_produto bg-red-500/10 text-red-500 px-3 rounded-lg">X</button>
                        </div>

                    </div>

                    <button type="button" id="adicionar_produto" class="mt-4 text-xs text-cyan-400">
                        + Adicionar produto
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-xs font-bold text-gray-500">Total</label>
                        <input type="number" id="total" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Pagamento</label>
                        <select id="metodo_pagamento" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300">
                            <option value="pix">PIX</option>
                            <option value="cartao_credito">Cartão Crédito</option>
                            <option value="cartao_debito">Cartão Débito</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                    </div>

                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500">Observações</label>
                    <textarea id="observacoes" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300"></textarea>
                </div>

                <div id="erro_novo_pedido" class="text-red-400 text-sm"></div>

            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-800 flex justify-end gap-3">
            <button class="close-modal-btn text-gray-400">Cancelar</button>
            <button id="salvar_novo_pedido" class="bg-cyan-400 text-black px-6 py-2 rounded-xl font-bold">
                Salvar Pedido
            </button>
        </div>

    </div>
</div>

<div id="modal_detalhes_pedido" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm close-modal-trigger"></div>

    <div class="relative bg-[#0f172a] border border-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Editar Pedido</h3>
            <button class="close-modal-btn text-gray-400 text-2xl">&times;</button>
        </div>

        <div class="p-6 max-h-[70vh] overflow-y-auto modal-body">

            <div class="mb-4">
                <label class="text-xs font-bold text-gray-500">Cliente</label>
                <select id="edit_cliente_id" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300"></select>
            </div>

        </div>

        <div class="px-6 py-4 border-t border-gray-800 flex justify-between">

            <button id="btn_excluir_pedido" class="text-red-500">
                Excluir
            </button>

            <div class="flex gap-3">
                <button class="close-modal-btn text-gray-400">Cancelar</button>
                <button id="btn_atualizar_pedido" class="bg-cyan-400 text-black px-6 py-2 rounded-xl font-bold">
                    Salvar
                </button>
            </div>

        </div>

    </div>
</div>

<div id="modal_feedback" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-[#020617] border border-gray-800 rounded-2xl w-full max-w-md p-8 shadow-2xl animate-fade-in">
        <div class="flex justify-center mb-6">
            <div id="modal_feedback_icon" class="w-16 h-16 flex items-center justify-center rounded-full text-3xl font-bold">
                </div>
        </div>
        
        <h2 id="modal_feedback_titulo" class="text-center text-xl font-bold text-white mb-2"></h2>
        <p id="modal_feedback_msg" class="text-center text-gray-400 text-sm mb-8 leading-relaxed"></p>
        
        <div class="flex justify-center">
            <button class="close-modal-btn w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-xl transition-all">
                Entendido
            </button>
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

<div id="modal_novo_produto" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm close-modal-trigger"></div>
        <div class="relative bg-[#0f172a] border border-gray-800 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 id="modal_produto_titulo" class="text-xl font-bold text-white">Novo Produto</h3>
                <button class="close-modal-btn text-gray-400 text-2xl">&times;</button>
            </div>
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <input type="hidden" id="edit_produto_id">
                <div>
                    <label class="text-xs text-gray-400">Nome</label>
                    <input id="nome_produto" type="text" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-white">
                </div>
                <div>
                    <label class="text-xs text-gray-400">Descrição</label>
                    <textarea id="descricao_produto" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-gray-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-400">Preço</label>
                        <input id="preco_produto" type="number" step="0.01" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Custo</label>
                        <input id="custo_produto" type="number" step="0.01" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-white">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-400">Estoque</label>
                        <input id="estoque_produto" type="number" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Mínimo</label>
                        <input id="estoque_minimo_produto" type="number" value="0" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-white">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Status</label>
                        <select id="status_produto" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-gray-300">
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-400">Categoria</label>
                    <select id="categoria_produto" class="w-full bg-[#020617] border border-gray-800 rounded-lg p-3 text-gray-300"></select>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-800 flex justify-end gap-3">
                <button id="btn_excluir_produto" class="text-red-500 mr-auto hidden">Excluir</button>
                <button class="close-modal-btn text-gray-400">Cancelar</button>
                <button id="salvar_produto_final" class="bg-cyan-400 text-black px-6 py-2 rounded-lg font-bold">Salvar</button>
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

    .bg-card {
    background-color: #0f172a;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>