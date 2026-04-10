$(document).ready(function () {
    popular_select_clientes("id_novo_produto_cliente_select");
    popular_select_produtos("id_novo_produto_select");
    
    $(".close-modal-btn, .close-modal-trigger").click(function() {
        $("#modal_novo_pedido").addClass("hidden").removeClass("flex");
        $("body").css("overflow", "auto"); 
    });

});

function mostrarFeedback(tipo, titulo, mensagem) {
    const modal = $("#modal_feedback");
    const icon = $("#modal_feedback_icon");
    const titulo_el = $("#modal_feedback_titulo");
    const msg = $("#modal_feedback_msg");
    const btn = $("#modal_feedback_btn");

    // Reset
    icon.removeClass();
    btn.removeClass();

    if (tipo === "sucesso") {
        icon.addClass(
            "w-14 h-14 flex items-center justify-center rounded-full bg-green-500/20 text-green-400",
        );
        icon.html("✔");

        btn.addClass(
            "bg-green-500 hover:bg-green-400 text-white px-5 py-2 rounded-lg",
        );
    } else {
        icon.addClass(
            "w-14 h-14 flex items-center justify-center rounded-full bg-red-500/20 text-red-400",
        );
        icon.html("✖");

        btn.addClass(
            "bg-red-500 hover:bg-red-400 text-white px-5 py-2 rounded-lg",
        );
    }

    titulo_el.text(titulo);
    msg.text(mensagem);
    btn.text("OK");

    // Mostrar modal
    modal.removeClass("hidden").css("display", "flex");

    // Fechar
    btn.off("click").on("click", function () {
        modal.addClass("hidden").css("display", "none");
    });
}

/**
 * Popula qualquer select de clientes (sem seleção automática)
 * @param {string} select_id - ID do select que será preenchido
 */
function popular_select_clientes(select_id) {
    const $select = $("#" + select_id);

    $.ajax({
        type: "GET",
        url: "/clientes/get-clientes",
        data: {
            id_usuario: $("#id_usuario_menu").val(),
        },
        beforeSend: function () {
            $select.html('<option value="">Carregando...</option>');
        },
        success: function (res) {
            let options_html = '<option value="">Selecione um cliente</option>';
            (res.clientes || []).forEach(
                (cliente) =>
                    (options_html += `<option value="${cliente.id}">${cliente.nome}</option>`),
            );
            $("#id_novo_produto_cliente_select").html(options_html);
        },
        error: function () {
            $select.html('<option value="">Erro ao carregar clientes</option>');
        },
    });
}

/**
 * Preenche um elemento select com a lista de produtos disponíveis.
 * @param {string} select_id - ID do select
 */
function popular_select_produtos(select_id) {
    const $select = $("#" + select_id);

    $.ajax({
        type: "GET",
        url: "/produtos/get-produtos",
        data: { 
            id_usuario: $("#id_usuario_menu").val() 
        },
        beforeSend: function () {
            $select.html('<option value="">Carregando...</option>');
        },
        success: function (res) {
            let options_html = '<option value="">Selecione um produto</option>';

            (res.produtos || []).forEach((p) => {
                options_html += `
                    <option value="${p.id}" data-preco="${p.preco}">
                        ${p.nome} - R$ ${parseFloat(p.preco).toFixed(2)}
                    </option>
                `;
            });

            $select.html(options_html);
        },
        error: function () {
            $select.html('<option value="">Erro ao carregar produtos</option>');
        },
    });
}