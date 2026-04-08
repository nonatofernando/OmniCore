$(document).ready(function () {
    $("#filtro_status").val("");
    carregarPedidos();
    getDadosClientes();

    $("#btn_novo_pedido, #id_btn_modal_novo_pedido").on("click", function () {
        limparFormularioPedido();
        $("#modal_novo_pedido").removeClass("hidden").css("display", "flex");
        $("body").addClass("overflow-hidden");
        popularSelectProduto($(".produto_id"));
    });

    $(document).on("click", ".btn-ver-detalhes", function (e) {
        e.preventDefault();
        abrirModalEdicao($(this).data("id"));
    });

    $(".close-modal-btn, .close-modal-trigger").on("click", function () {
        $("#modal_novo_pedido, #modal_detalhes_pedido")
            .addClass("hidden")
            .css("display", "none");
        $("body").removeClass("overflow-hidden");
    });

    $("#filtro_status").change(function () {
        carregarPedidos($(this).val(), $("#buscar_pedidos").val());
    });

    $("#buscar_pedidos").on("input", function () {
        carregarPedidos($("#filtro_status").val(), $(this).val());
    });

    $(document).on(
        "click",
        "#adicionar_produto, #add_produto_edicao",
        function () {
            const isEdicao = $(this).attr("id") === "add_produto_edicao";
            const container = isEdicao
                ? "#produtos_edicao_container"
                : "#produtos_container";

            const html = `
            <div class="${isEdicao ? "produto_item_edicao" : "produto_item"} flex gap-2">
                <select class="${isEdicao ? "prod_id_edicao" : "produto_id"} flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300 outline-none focus:border-cyan-500"></select>
                <input type="number" class="${isEdicao ? "prod_qtd_edicao" : "quantidade"} w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300" min="1" value="1">
                <button type="button" class="${isEdicao ? "remover_prod_edicao" : "remover_produto"} bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 rounded-lg font-bold transition-all">X</button>
            </div>`;

            const $item = $(html);
            $(container).append($item);
            popularSelectProduto($item.find("select"));
        },
    );

    $(document).on(
        "click",
        ".remover_produto, .remover_prod_edicao",
        function () {
            const isEdicao = $(this).hasClass("remover_prod_edicao");
            const classe = isEdicao ? ".produto_item_edicao" : ".produto_item";

            if ($(classe).length > 1) {
                $(this).closest(classe).remove();
                calcularTotal();
            }
        },
    );

    $(document).on(
        "change",
        ".produto_id, .prod_id_edicao, .quantidade, .prod_qtd_edicao",
        function () {
            calcularTotal();
        },
    );

    $("#salvar_pedido").click(function () {
        salvarPedido();
    });

    $("#btn_atualizar_pedido").click(function () {
        atualizarPedido();
    });

    $("#btn_excluir_pedido").click(function () {
        const id = $("#edit_pedido_id").val();
        if (
            confirm(
                "Tem certeza que deseja excluir este pedido permanentemente?",
            )
        ) {
            excluirPedido(id);
        }
    });
});

/**
 * Carrega a lista de pedidos do servidor e renderiza a tabela.
 * * @param {string} filtro - Status do pedido para filtrar (ex: 'pendente', 'entregue').
 * @param {string} busca - Termo de busca (nome do cliente ou número do pedido).
 */
function carregarPedidos(filtro = "", busca = "") {
    $.ajax({
        type: "GET",
        url: "/pedidos/get-pedidos",
        data: {
            id_usuario: $("#id_usuario_menu").val(),
            status: filtro,
            busca: busca,
        },
        success: (res) => {
            const tbody = $("#pedidos-table tbody");
            tbody.empty();
            (res.pedidos || []).forEach((pedido) => {
                const status_classes = {
                    entregue: "bg-green-500/20 text-green-400",
                    pendente: "bg-yellow-500/20 text-yellow-400",
                    processando: "bg-blue-500/20 text-blue-400",
                    enviado: "bg-purple-500/20 text-purple-400",
                    cancelado: "bg-red-500/20 text-red-400",
                };
                const cor =
                    status_classes[pedido.status] ||
                    "bg-gray-500/20 text-gray-400";
                tbody.append(`
                    <tr class="text-gray-300 text-sm border-b border-gray-800 hover:bg-[#0f172a]/50">
                        <td class="px-6 py-4 font-semibold">${pedido.numero_pedido}</td>
                        <td class="px-6 py-4">Cliente #${pedido.cliente_id}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold ${cor}">${pedido.status}</span></td>
                        <td class="px-6 py-4 font-semibold">R$ ${parseFloat(pedido.total).toFixed(2)}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="javascript:void(0)" data-id="${pedido.id}" class="btn-ver-detalhes text-cyan-400 hover:text-cyan-300 font-semibold">Editar</a>
                        </td>
                    </tr>`);
            });
        },
    });
}

/**
 * Busca os dados de um pedido específico e abre o modal para edição.
 * * @param {number|string} id - O ID do pedido a ser editado.
 */
function abrirModalEdicao(id) {
    const $modal = $("#modal_detalhes_pedido");
    const $body = $modal.find(".modal-body");

    $modal.removeClass("hidden").css("display", "flex");
    $("body").addClass("overflow-hidden");
    $body.html(
        '<div class="text-center py-10 text-cyan-500 animate-pulse font-bold">Carregando dados do pedido...</div>',
    );

    $.ajax({
        type: "GET",
        url: `/pedidos/detalhes/${id}`,
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: function (res) {
            const p = res.pedido;
            let html = `
                <input type="hidden" id="edit_pedido_id" value="${p.id}">
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Cliente</label>
                        <select id="edit_cliente_id" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-gray-300 outline-none focus:border-cyan-500"></select>
                    </div>

                    <div class="bg-[#020617]/50 p-4 rounded-xl border border-gray-800/50">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-3 tracking-wider">Produtos</label>
                        <div id="produtos_edicao_container" class="space-y-3">
                            ${p.produtos
                                .map(
                                    (prod) => `
                                <div class="produto_item_edicao flex gap-2">
                                    <select class="prod_id_edicao flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-white">
                                        <option value="${prod.id}" data-preco="${prod.preco}" selected>${prod.nome}</option>
                                    </select>
                                    <input type="number" class="prod_qtd_edicao w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-white" min="1" value="${prod.quantidade}">
                                    <button type="button" class="remover_prod_edicao bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 rounded-lg font-bold">X</button>
                                </div>`,
                                )
                                .join("")}
                        </div>
                        <button type="button" id="add_produto_edicao" class="mt-4 text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                            <span class="text-lg">+</span> Adicionar outro produto
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Status</label>
                            <select id="edit_status" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500">
                                <option value="pendente" ${p.status == "pendente" ? "selected" : ""}>Pendente</option>
                                <option value="processando" ${p.status == "processando" ? "selected" : ""}>Processando</option>
                                <option value="enviado" ${p.status == "enviado" ? "selected" : ""}>Enviado</option>
                                <option value="entregue" ${p.status == "entregue" ? "selected" : ""}>Entregue</option>
                                <option value="cancelado" ${p.status == "cancelado" ? "selected" : ""}>Cancelado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Pagamento</label>
                            <select id="edit_metodo" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500">
                                <option value="pix" ${p.metodo_pagamento == "pix" ? "selected" : ""}>PIX</option>
                                <option value="cartao_credito" ${p.metodo_pagamento == "cartao_credito" ? "selected" : ""}>Cartão de Crédito</option>
                                <option value="dinheiro" ${p.metodo_pagamento == "dinheiro" ? "selected" : ""}>Dinheiro</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Observações</label>
                        <textarea id="edit_obs" rows="2" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500">${p.observacoes || ""}</textarea>
                    </div>

                    <div class="bg-cyan-500/5 p-4 rounded-xl border border-cyan-500/20 flex justify-between items-center">
                        <span class="text-cyan-400 text-sm font-bold uppercase tracking-widest">Total do Pedido</span>
                        <span id="total_edicao" class="text-white text-xl font-black font-mono tracking-tighter">R$ ${parseFloat(p.total).toFixed(2)}</span>
                    </div>
                </div>`;

            $body.html(html);
            popularSelectClientesEdicao(p.cliente ? p.cliente.id : null);
            $(".prod_id_edicao").each(function () {
                popularSelectProduto($(this));
            });
        },
    });
}

/**
 * Preenche o elemento select de clientes dentro do modal de edição.
 * * @param {number|null} selectedId - ID do cliente que deve vir selecionado por padrão.
 */
function popularSelectClientesEdicao(selectedId) {
    $.ajax({
        type: "GET",
        url: "/clientes/get-clientes",
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: function (res) {
            let options = '<option value="">Selecione um cliente</option>';
            (res.clientes || []).forEach((c) => {
                options += `<option value="${c.id}" ${c.id == selectedId ? "selected" : ""}>${c.nome}</option>`;
            });
            $("#edit_cliente_id").html(options);
        },
    });
}

/**
 * Preenche um elemento select com a lista de produtos disponíveis.
 * * @param {jQuery} select - O elemento jQuery do tipo <select> que será preenchido.
 */
function popularSelectProduto(select) {
    const originalValue = select.val();
    $.ajax({
        type: "GET",
        url: "/produtos/get-produtos",
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: (res) => {
            let options = '<option value="">Selecione um produto</option>';
            (res.produtos || []).forEach((p) => {
                const selected = p.id == originalValue ? "selected" : "";
                options += `<option value="${p.id}" data-preco="${p.preco}" ${selected}>${p.nome} - R$ ${parseFloat(p.preco).toFixed(2)}</option>`;
            });
            select.html(options);
        },
    });
}

/**
 * Calcula o valor total do pedido com base nos produtos e quantidades selecionados.
 * Funciona dinamicamente tanto no modal de Novo Pedido quanto no de Edição.
 */
function calcularTotal() {
    let total = 0;
    const isEdicao = $("#modal_detalhes_pedido").is(":visible");
    const container = isEdicao ? ".produto_item_edicao" : ".produto_item";
    const select = isEdicao ? ".prod_id_edicao" : ".produto_id";
    const inputQtd = isEdicao ? ".prod_qtd_edicao" : ".quantidade";

    $(container).each(function () {
        const preco =
            parseFloat(
                $(this).find(`${select} option:selected`).data("preco"),
            ) || 0;
        const qtd = parseInt($(this).find(inputQtd).val()) || 0;
        total += preco * qtd;
    });

    if (isEdicao) {
        $("#total_edicao").text(`R$ ${total.toFixed(2)}`);
    } else {
        $("#total").val(total.toFixed(2));
    }
}

/**
 * Coleta os dados do formulário de Novo Pedido e envia para o backend.
 */
function salvarPedido() {
    const btn = $("#salvar_pedido");
    const id_cliente = $("#id_cliente_select").val();
    let produtos = [];

    $(".produto_item").each(function () {
        const id = $(this).find(".produto_id").val();
        const qtd = $(this).find(".quantidade").val();
        if (id) produtos.push({ id_produto: id, qtd_produto: qtd });
    });

    if (!id_cliente || produtos.length === 0) {
        $("#erro_novo_pedido").text(
            "Selecione um cliente e ao menos um produto.",
        );
        return;
    }

    btn.prop("disabled", true).text("Processando...");
    $.ajax({
        type: "POST",
        url: "/pedidos/salvar",
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            id_cliente: id_cliente,
            id_usuario: $("#id_usuario_menu").val(),
            valor_total: $("#total").val(),
            metodo_pagamento: $("#metodo_pagamento").val(),
            obs_pedido: $("#observacoes").val(),
            lista_produtos: produtos,
        },
        success: function () {
            $("#modal_novo_pedido").addClass("hidden").css("display", "none");
            carregarPedidos();
            mostrarFeedback(
                "sucesso",
                "Criado!",
                "Pedido registrado com sucesso.",
            );
        },
        complete: () => btn.prop("disabled", false).text("Salvar Pedido"),
    });
}

/**
 * Coleta os dados alterados no modal de Edição e envia para o backend.
 */
function atualizarPedido() {
    const id = $("#edit_pedido_id").val();
    const btn = $("#btn_atualizar_pedido");
    let produtos = [];

    $(".produto_item_edicao").each(function () {
        const pId = $(this).find(".prod_id_edicao").val();
        const pQtd = $(this).find(".prod_qtd_edicao").val();
        if (pId) produtos.push({ id_produto: pId, qtd_produto: pQtd });
    });

    btn.prop("disabled", true).text("Salvando...");
    $.ajax({
        type: "POST",
        url: `/pedidos/atualizar/${id}`,
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            id_usuario: $("#id_usuario_menu").val(),
            id_cliente: $("#edit_cliente_id").val(),
            status: $("#edit_status").val(),
            metodo_pagamento: $("#edit_metodo").val(),
            observacoes: $("#edit_obs").val(),
            lista_produtos: produtos,
            valor_total: $("#total_edicao").text().replace("R$ ", ""),
        },
        success: function () {
            $("#modal_detalhes_pedido")
                .addClass("hidden")
                .css("display", "none");
            carregarPedidos();
            mostrarFeedback(
                "sucesso",
                "Atualizado!",
                "Pedido alterado com sucesso.",
            );
        },
        complete: () => btn.prop("disabled", false).text("Salvar Alterações"),
    });
}

/**
 * Solicita ao backend a exclusão permanente de um pedido.
 * * @param {number|string} id - ID do pedido a ser excluído.
 */
function excluirPedido(id) {
    $.ajax({
        type: "DELETE",
        url: `/pedidos/excluir/${id}`,
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            id_usuario: $("#id_usuario_menu").val(),
        },
        success: function () {
            $("#modal_detalhes_pedido")
                .addClass("hidden")
                .css("display", "none");
            carregarPedidos();
            mostrarFeedback(
                "sucesso",
                "Excluído!",
                "O pedido foi removido do sistema.",
            );
        },
    });
}

/**
 * Limpa todos os campos do modal de Novo Pedido e reseta a lista de produtos.
 */
function limparFormularioPedido() {
    $("#id_cliente_select, #total, #observacoes").val("");
    $("#produtos_container").html(`
        <div class="produto_item flex gap-2">
            <select class="produto_id flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300 outline-none"></select>
            <input type="number" class="quantidade w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300" min="1" value="1">
            <button type="button" class="remover_produto bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 rounded-lg font-bold">X</button>
        </div>`);
    $("#erro_novo_pedido").text("");
}

/**
 * Busca a lista de clientes do backend e preenche o select principal.
 */
function getDadosClientes() {
    $.ajax({
        type: "GET",
        url: "/clientes/get-clientes",
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: function (res) {
            let options = '<option value="">Selecione um cliente</option>';
            (res.clientes || []).forEach(
                (c) =>
                    (options += `<option value="${c.id}">${c.nome}</option>`),
            );
            $("#id_cliente_select").html(options);
        },
    });
}
