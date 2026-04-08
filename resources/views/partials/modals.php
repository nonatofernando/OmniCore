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
                            <input id="id_produto_select" type="number" class="quantidade w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300" placeholder="Qtd" min="1" value="1">
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
        <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar modal-body">
        </div>
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