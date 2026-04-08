$(document).ready(function () {
    $("#filtro_status").val("");

    carregarPedidos();
    getDadosClientes();

    $("#btn_novo_pedido, #id_btn_modal_novo_pedido").on("click", function () {
        $("#modal_novo_pedido").removeClass("hidden").css("display", "flex");
        $("body").addClass("overflow-hidden");
        popularSelectProduto($(".produto_id"));
    });

    $(".close-modal-btn, .close-modal-trigger").on("click", function () {
        $("#modal_novo_pedido").addClass("hidden").css("display", "none");
        $("body").removeClass("overflow-hidden");
    });

    $("#filtro_status").change(function () {
        carregarPedidos($(this).val(), $("#buscar_pedidos").val());
    });

    $("#buscar_pedidos").on("input", function () {
        carregarPedidos($("#filtro_status").val(), $(this).val());
    });

    $("#adicionar_produto").click(function () {
        const novo_item = `
            <div class="produto_item flex gap-2">
                <select class="produto_id flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300"></select>
                <input type="number" class="quantidade w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300" min="1" value="1">
                <button type="button" class="remover_produto bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 rounded-lg font-bold">X</button>
            </div>`;

        const $novo_item = $(novo_item);
        $("#produtos_container").append($novo_item);
        popularSelectProduto($novo_item.find(".produto_id"));
    });

    $(document).on("click", ".remover_produto", function () {
        if ($(".produto_item").length > 1) {
            $(this).closest(".produto_item").remove();
            calcularTotal();
        }
    });

    $(document).on("change", ".produto_id, .quantidade", function () {
        calcularTotal();
    });

    $(document).on("click", "#salvar_pedido", function () {
        salvarPedido();
    });
});

/**
 * Carrega pedidos via AJAX com filtro e busca
 * @param {string} filtro
 * @param {string} busca
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
        dataType: "json",
        success: (res) => {
            const tbody = $("#pedidos-table tbody");
            tbody.empty();

            let pedidos = res.pedidos || [];

            if (busca) {
                const termo = busca.toLowerCase();
                pedidos = pedidos.filter(
                    (p) =>
                        p.numero_pedido.toString().includes(termo) ||
                        p.cliente_id.toString().includes(termo),
                );
            }

            pedidos.forEach((pedido) => {
                const status_classes = {
                    entregue: "bg-green-500/20 text-green-400",
                    pendente: "bg-yellow-500/20 text-yellow-400",
                    processando: "bg-blue-500/20 text-blue-400",
                    enviado: "bg-purple-500/20 text-purple-400",
                    cancelado: "bg-red-500/20 text-red-400",
                };

                const classe_cor =
                    status_classes[pedido.status] ||
                    "bg-gray-500/20 text-gray-400";

                tbody.append(`
                    <tr class="text-gray-300 text-sm border-b border-gray-800 hover:bg-[#0f172a]/50">
                        <td class="px-6 py-4 font-semibold">${pedido.numero_pedido}</td>
                        <td class="px-6 py-4">Cliente #${pedido.cliente_id}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold ${classe_cor}">
                                ${pedido.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold">R$ ${parseFloat(pedido.total).toFixed(2)}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="/pedidos/${pedido.id}" class="text-cyan-400 hover:text-cyan-300 font-semibold">
                                Detalhes
                            </a>
                        </td>
                    </tr>
                `);
            });
        },
    });
}

/**
 * Carrega lista de clientes
 */
function getDadosClientes() {
    $("#id_cliente_select").html('<option value="">Carregando...</option>');

    $.ajax({
        type: "GET",
        url: "/clientes/get-clientes",
        data: { id_usuario: $("#id_usuario_menu").val() },
        dataType: "json",
        success: function (res) {
            $("#id_cliente_select")
                .empty()
                .append('<option value="">Selecione um cliente</option>');

            (res.clientes || []).forEach((cliente) => {
                $("#id_cliente_select").append(
                    `<option value="${cliente.id}">${cliente.nome}</option>`,
                );
            });
        },
        error: function () {
            $("#id_cliente_select").html(
                '<option value="">Erro ao carregar clientes</option>',
            );
        },
    });
}

/**
 * Popula select de produtos
 * @param {jQuery} select
 */
function popularSelectProduto(select) {
    select.html('<option value="">Carregando...</option>');

    $.ajax({
        type: "GET",
        url: "/produtos/get-produtos",
        data: { id_usuario: $("#id_usuario_menu").val() },
        dataType: "json",
        success: (res) => {
            select.empty();
            select.append('<option value="">Selecione</option>');

            (res.produtos || []).forEach((p) => {
                select.append(`
                    <option value="${p.id}" data-preco="${p.preco}">
                        ${p.nome}
                    </option>
                `);
            });
        },
        error: () => {
            select.html('<option value="">Erro ao carregar</option>');
        },
    });
}

/**
 * Retorna lista de produtos do formulário
 * @returns {Array}
 */
function getListaProdutos() {
    let lista = [];

    $(".produto_item").each(function () {
        const id = $(this).find(".produto_id").val();
        const qtd = $(this).find(".quantidade").val();

        if (id && qtd > 0) {
            lista.push({
                id_produto: id,
                qtd_produto: qtd,
            });
        }
    });

    return lista;
}

/**
 * Calcula valor total do pedido
 */
function calcularTotal() {
    let total = 0;

    $(".produto_item").each(function () {
        const preco =
            parseFloat(
                $(this).find(".produto_id option:selected").data("preco"),
            ) || 0;

        const qtd = parseInt($(this).find(".quantidade").val()) || 0;

        total += preco * qtd;
    });

    $("#total").val(total.toFixed(2));
}

/**
 * Envia pedido para o servidor
 */
function salvarPedido() {
    $("#erro_novo_pedido").text("");

    const btn = $("#salvar_pedido");
    if (btn.prop("disabled")) return;

    const id_cliente = $("#id_cliente_select").val();
    const lista_produtos = getListaProdutos();
    const total = $("#total").val();

    if (!id_cliente) {
        $("#erro_novo_pedido").text("Selecione um cliente");
        return;
    }

    if (lista_produtos.length === 0) {
        $("#erro_novo_pedido").text("Adicione produtos");
        return;
    }

    if (!total || parseFloat(total) <= 0) {
        $("#erro_novo_pedido").text("Total inválido");
        return;
    }

    btn.prop("disabled", true).text("Salvando...");

    $.ajax({
        type: "POST",
        url: "/pedidos/salvar",
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            id_cliente: id_cliente,
            id_usuario: $("#id_usuario_menu").val(),
            valor_total: total,
            metodo_pagamento: $("#metodo_pagamento").val(),
            obs_pedido: $("#observacoes").val(),
            lista_produtos: lista_produtos,
        },
        success: function (res) {
            if (res.status === "sucesso") {
                mostrarFeedback("sucesso", "Pedido criado!", res.mensagem);

                $("#modal_novo_pedido")
                    .addClass("hidden")
                    .css("display", "none");

                limparFormularioPedido();
                carregarPedidos();
            } else {
                mostrarFeedback("erro", "Erro", res.mensagem);
            }
        },
        error: function (xhr) {
            let msg = "Erro inesperado";

            if (xhr.responseJSON && xhr.responseJSON.mensagem) {
                msg = xhr.responseJSON.mensagem;
            }

            mostrarFeedback("erro", "Erro", msg);
        },
        complete: function () {
            btn.prop("disabled", false).text("Salvar Pedido");
        },
    });
}

/**
 * Reseta formulário de pedido
 */
function limparFormularioPedido() {
    $("#id_cliente_select").val("");
    $("#total").val("");
    $("#metodo_pagamento").val("pix");
    $("#observacoes").val("");
    $("#erro_novo_pedido").text("");

    $("#produtos_container").html(`
        <div class="produto_item flex gap-2">
            <select class="produto_id flex-1 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300"></select>
            <input type="number" class="quantidade w-20 bg-[#020617] border border-gray-800 rounded-lg py-2 px-3 text-sm text-gray-300" min="1" value="1">
            <button type="button" class="remover_produto bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 rounded-lg font-bold">X</button>
        </div>
    `);

    popularSelectProduto($(".produto_id"));
}
