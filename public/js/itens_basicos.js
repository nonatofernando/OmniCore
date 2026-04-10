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
 *
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
            let options = '<option value="">Selecione um cliente</option>';

            (res.clientes || []).forEach((c) => {
                options += `
                    <option value="${c.id}">
                        ${c.nome}
                    </option>
                `;
            });

            $select.html(options);
        },
        error: function () {
            $select.html('<option value="">Erro ao carregar clientes</option>');
        },
    });
}
