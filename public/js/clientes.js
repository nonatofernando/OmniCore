$(document).ready(function () {
    // Configuração global para injetar o Token CSRF do Laravel nas buscas POST/DELETE via Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    carregar_clientes();

    $("#inputBusca").on("input", function () {
        carregar_clientes($(this).val(), $("#filtroStatus").val());
    });

    $("#filtroStatus").on("change", function () {
        carregar_clientes($("#inputBusca").val(), $(this).val());
    });

    // Gatilho correto associado estritamente ao ID exclusivo do botão de abertura
    $("#id_btn_modal_novo_cliente").on("click", function (e) {
        e.preventDefault();
        limpar_formulario_cliente();
        $("#modal_novo_cliente").removeClass("hidden").addClass("flex");
        $("body").addClass("overflow-hidden");
    });

    $(document).on("click", ".btn-editar-cliente", function () {
        abrir_modal_edicao($(this).data("id"));
    });

    // Gerenciador de fechamento unificado para qualquer tipo de modal ativo
    $(document).on("click", ".close-modal-btn, .close-modal-trigger", function () {
        const modal = $(this).closest(".fixed.inset-0");
        modal.addClass("hidden").removeClass("flex");
        $("body").removeClass("overflow-hidden");
    });

    $("#salvar_cliente").click(function () {
        salvar_cliente();
    });

    $("#btn_atualizar_cliente").click(function () {
        atualizar_cliente();
    });

    $("#btn_excluir_cliente").click(function () {
        const id = $("#edit_cliente_id").val();
        if (id && confirm("Tem certeza que deseja excluir este cliente permanentemente?")) {
            excluir_cliente(id);
        }
    });
});

function carregar_clientes(busca = "", status = "") {
    const grid = $("#gridClientes");
    const contador = $("#contador-clientes");

    $.ajax({
        type: "GET",
        url: "/clientes/get-clientes",
        data: { busca: busca, status: status },
        beforeSend: function () {
            grid.addClass("opacity-50");
        },
        success: (res) => {
            grid.removeClass("opacity-50").empty();
            const clientes = res.clientes || [];
            contador.text(`${clientes.length} cliente(s) encontrado(s)`);

            if (clientes.length === 0) {
                grid.append('<p class="col-span-full text-center text-gray-500 py-10 font-medium">Nenhum cliente encontrado.</p>');
                return;
            }

            clientes.forEach((cliente) => {
                const inicial = cliente.nome ? cliente.nome.charAt(0).toUpperCase() : "?";
                const status_class = cliente.status === "ativo"
                        ? "bg-emerald-900/20 text-emerald-500 border-emerald-800/30"
                        : "bg-red-900/20 text-red-500 border-red-800/30";

                grid.append(`
                    <div class="bg-card p-6 rounded-2xl border border-gray-800/50 hover:border-cyan-500/30 transition-all duration-300 cliente-card relative group">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-cyan-950/40 border border-cyan-800/50 rounded-lg flex items-center justify-center text-cyan-400 font-bold text-lg">${inicial}</div>
                            <div>
                                <h3 class="font-bold text-white group-hover:text-cyan-400 transition">${cliente.nome}</h3>
                                <p class="text-gray-500 text-xs tracking-tight">${cliente.empresa || "Sem empresa"}</p>
                            </div>
                            <button data-id="${cliente.id}" class="btn-editar-cliente absolute top-6 right-6 text-gray-700 hover:text-white transition">•••</button>
                        </div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-gray-400 text-sm"><span>📧</span> ${cliente.email || "N/A"}</div>
                            <div class="flex items-center gap-2 text-gray-400 text-sm"><span>📞</span> ${cliente.telefone || "N/A"}</div>
                        </div>
                        <span class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border ${status_class}">${cliente.status}</span>
                    </div>
                `);
            });
        },
    });
}

function abrir_modal_edicao(id) {
    const $modal = $("#modal_detalhes_cliente");
    const $body = $modal.find(".modal-body");

    $modal.removeClass("hidden").addClass("flex");
    $("body").addClass("overflow-hidden");
    $body.html('<div class="text-center py-10 text-cyan-500 animate-pulse font-bold">Carregando dados...</div>');

    $.ajax({
        type: "GET",
        url: `/clientes/detalhes/${id}`,
        success: function (res) {
            const c = res.cliente;
            $body.html(`
                <input type="hidden" id="edit_cliente_id" value="${c.id}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Nome Completo</label>
                        <input type="text" id="edit_nome" value="${c.nome}" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">E-mail</label>
                        <input type="email" id="edit_email" value="${c.email || ""}" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Status</label>
                            <select id="edit_status" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500">
                                <option value="ativo" ${c.status === "ativo" ? "selected" : ""}>Ativo</option>
                                <option value="inativo" ${c.status === "inativo" ? "selected" : ""}>Inativo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Telefone</label>
                            <input type="text" id="edit_telefone" value="${c.telefone || ""}" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Empresa</label>
                        <input type="text" id="edit_empresa" value="${c.empresa || ""}" class="w-full bg-[#020617] border border-gray-800 rounded-xl py-3 px-4 text-white outline-none focus:border-cyan-500">
                    </div>
                </div>
            `);
        },
    });
}

function salvar_cliente() {
    const btn = $("#salvar_cliente");
    const dados = {
        nome: $("#nome_cliente").val(),
        email: $("#email_cliente").val(),
        telefone: $("#telefone_cliente").val(),
        empresa: $("#empresa_cliente").val(),
    };

    if (!dados.nome || dados.nome.trim() === "") {
        return mostrarFeedback("erro", "Campo Obrigatório", "O nome do cliente é necessário.");
    }

    btn.prop("disabled", true).text("Salvando...");

    $.ajax({
        type: "POST",
        url: "/clientes/salvar",
        data: dados,
        success: function () {
            $("#modal_novo_cliente").addClass("hidden").removeClass("flex");
            $("body").removeClass("overflow-hidden");
            carregar_clientes();
            mostrarFeedback("sucesso", "Sucesso!", "Cliente cadastrado com sucesso.");
        },
        error: function () {
            mostrarFeedback("erro", "Erro", "Não foi possível salvar o cliente.");
        },
        complete: () => btn.prop("disabled", false).text("Salvar Cliente"),
    });
}

function atualizar_cliente() {
    const id = $("#edit_cliente_id").val();
    const btn = $("#btn_atualizar_cliente");
    const dados = {
        nome: $("#edit_nome").val(),
        status: $("#edit_status").val(),
        email: $("#edit_email").val(),
        telefone: $("#edit_telefone").val(),
        empresa: $("#edit_empresa").val(),
    };

    btn.prop("disabled", true).text("Atualizando...");

    $.ajax({
        type: "POST",
        url: `/clientes/atualizar/${id}`,
        data: dados,
        success: function () {
            $("#modal_detalhes_cliente").addClass("hidden").removeClass("flex");
            $("body").removeClass("overflow-hidden");
            carregar_clientes();
            mostrarFeedback("sucesso", "Atualizado!", "Os dados foram salvos.");
        },
        complete: () => btn.prop("disabled", false).text("Atualizar Dados"),
    });
}

function excluir_cliente(id) {
    $.ajax({
        type: "DELETE",
        url: `/clientes/excluir/${id}`,
        success: function () {
            $("#modal_detalhes_cliente").addClass("hidden").removeClass("flex");
            $("body").removeClass("overflow-hidden");
            carregar_clientes();
            mostrarFeedback("sucesso", "Excluído", "Cliente removido do sistema.");
        },
    });
}

function mostrarFeedback(tipo, titulo, msg) {
    const modal = $("#modal_feedback");
    const icon = $("#modal_feedback_icon");
    const btn = modal.find(".close-modal-btn");

    $("#modal_feedback_titulo").text(titulo);
    $("#modal_feedback_msg").text(msg);

    icon.removeClass("bg-emerald-500/20 text-emerald-500 bg-red-500/20 text-red-500");
    btn.removeClass("bg-emerald-500 hover:bg-emerald-600 bg-red-500 hover:bg-red-600 bg-gray-800 hover:bg-gray-700 text-white");

    if (tipo === "sucesso") {
        icon.html("✓").addClass("bg-emerald-500/20 text-emerald-500");
        btn.text("Ótimo").addClass("bg-emerald-500 text-black hover:bg-emerald-400 w-full font-bold py-3 px-6 rounded-xl transition-all");
    } else {
        icon.html("!").addClass("bg-red-500/20 text-red-500");
        btn.text("Entendido").addClass("bg-red-500 text-white hover:bg-red-600 w-full font-bold py-3 px-6 rounded-xl transition-all");
    }

    modal.removeClass("hidden").addClass("flex");
}

function limpar_formulario_cliente() {
    $("#modal_novo_cliente").find("input").val("");
}