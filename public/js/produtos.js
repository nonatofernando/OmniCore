$(document).ready(function () {
    // Configuração do CSRF Token para o Laravel
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
    });

    // ==========================================
    // 1. INICIALIZAÇÃO E LISTENERS DE EVENTOS
    // ==========================================
    
    // Inicializa a tela buscando categorias e produtos
    buscar_categorias();
    $("#filtro_categoria").val("");
    buscar_produtos();

    // -- Eventos de Modal --
    $("#btn_novo_produto").on("click", function () {
        limpar_formulario_produto();
        abrir_modal("#modal_novo_produto");
    });

    $(".close-modal-btn, .close-modal-trigger").on("click", function () {
        fechar_modais();
    });

    $(document).on("click", ".btn_editar_produto", function (e) {
        e.preventDefault();
        const id_produto = $(this).data("id");
        buscar_detalhes_produto(id_produto);
    });

    // -- Eventos de Filtro e Busca --
    $("#filtro_categoria").change(function () {
        buscar_produtos($(this).val(), $("#input_busca").val());
    });

    let debounce_timer;
    $("#input_busca").on("input", function () {
        clearTimeout(debounce_timer);
        debounce_timer = setTimeout(() => {
            buscar_produtos($("#filtro_categoria").val(), $(this).val());
        }, 500);
    });

    // -- Ações Principais (Salvar / Atualizar / Excluir) --
    $("#salvar_produto").click(function () {
        salvar_produto_api();
    });

    $("#btn_atualizar_produto").click(function () {
        atualizar_produto_api();
    });

    $("#btn_excluir_produto").click(function () {
        const id_produto = $("#edit_produto_id").val();
        if (confirm("Tem certeza que deseja excluir este produto permanentemente?")) {
            excluir_produto_api(id_produto);
        }
    });
});


// ==========================================
// 2. FUNÇÕES DE REQUISIÇÃO (API / AJAX)
// ==========================================

function buscar_categorias() {
    $.ajax({
        type: "GET",
        url: "/produtos/get-categorias", // Rota atualizada
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: function (res) {
            if (res.status === "sucesso" && res.categorias) {
                montar_select_categorias(res.categorias);
            }
        },
        error: function () {
            console.error("Erro ao carregar as categorias.");
        },
    });
}

function buscar_produtos(categoria_id = "", busca = "") {
    $.ajax({
        type: "GET",
        url: "/produtos/get-produtos", // Rota atualizada
        data: {
            id_usuario: $("#id_usuario_menu").val(),
            categoria_id: categoria_id,
            busca: busca,
        },
        beforeSend: function () {
            const tbody = $("#produtos_table_tbody");
            tbody.html(`<tr><td colspan="5" class="text-center py-10 text-cyan-500 animate-pulse font-bold">Carregando produtos...</td></tr>`);
        },
        success: (res) => {
            const produtos = res.produtos || [];
            $("#total_produtos").text(`${produtos.length} produtos cadastrados`);
            montar_tabela_produtos(produtos);
        },
        error: () => {
            $("#produtos_table_tbody").html(`<tr><td colspan="5" class="text-center py-10 text-red-500">Erro ao carregar produtos. Tente novamente.</td></tr>`);
        },
    });
}

function buscar_detalhes_produto(id_produto) {
    $.ajax({
        type: "GET",
        url: `/produtos/detalhes/${id_produto}`, // Rota atualizada
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: function (res) {
            if (res.status === "sucesso") {
                montar_formulario_edicao(res.produto);
                abrir_modal("#modal_detalhes_produto");
            }
        },
        error: function () {
            alert("Erro ao carregar detalhes do produto.");
        }
    });
}

function salvar_produto_api() {
    const btn = $("#salvar_produto");
    const dados = coletar_dados_formulario(false);

    if (!dados.nome || !dados.preco) {
        $("#erro_novo_produto").text("Nome e preço são obrigatórios.");
        return;
    }

    btn.prop("disabled", true).text("Processando...");

    $.ajax({
        type: "POST",
        url: "/produtos/salvar", // Rota atualizada
        data: dados,
        success: function (res) {
            fechar_modais();
            buscar_produtos();
            mostrar_feedback("sucesso", "Criado!", res.mensagem); // Assumindo que você tem essa função global
        },
        error: function (err) {
            $("#erro_novo_produto").text(err.responseJSON?.mensagem || "Erro ao salvar.");
        },
        complete: () => btn.prop("disabled", false).text("Salvar Produto"),
    });
}

function atualizar_produto_api() {
    const btn = $("#btn_atualizar_produto");
    const dados = coletar_dados_formulario(true);
    const id_produto = dados.id_produto;

    btn.prop("disabled", true).text("Salvando...");

    $.ajax({
        type: "POST",
        url: `/produtos/atualizar/${id_produto}`, // Rota atualizada
        data: dados,
        success: function (res) {
            fechar_modais();
            buscar_produtos();
            mostrar_feedback("sucesso", "Atualizado!", res.mensagem);
        },
        error: function (err) {
            alert(err.responseJSON?.mensagem || "Erro ao atualizar.");
        },
        complete: () => btn.prop("disabled", false).text("Salvar Alterações"),
    });
}

function excluir_produto_api(id_produto) {
    $.ajax({
        type: "DELETE",
        url: `/produtos/excluir/${id_produto}`, // Rota atualizada
        data: { id_usuario: $("#id_usuario_menu").val() },
        success: function (res) {
            fechar_modais();
            buscar_produtos();
            mostrar_feedback("sucesso", "Excluído!", res.mensagem);
        },
        error: function () {
            mostrar_feedback("erro", "Erro!", "Não foi possível excluir o produto.");
        },
    });
}


// ==========================================
// 3. FUNÇÕES DE MONTAGEM E UI (VIEWS)
// ==========================================

function montar_tabela_produtos(produtos) {
    const tbody = $("#produtos_table_tbody");
    tbody.empty();

    if (produtos.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="5" class="text-center py-10 text-gray-500">
                    Nenhum produto encontrado.
                </td>
            </tr>
        `);
        return;
    }

    produtos.forEach((produto) => {
        const limite_estoque = produto.estoque_minimo || 5;
        const estoque_cor = produto.estoque > limite_estoque ? "bg-green-500/20 text-green-400" : "bg-red-500/20 text-red-400";
        
        const nome_categoria = produto.categoria_dados ? produto.categoria_dados.nome : "Sem Categoria";

        const preco_formatado = Number(produto.preco).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });

        const status_badge = produto.status === 'ativo' 
            ? `<span class="bg-green-500/20 text-green-400 text-[10px] px-2 py-0.5 rounded ml-2 uppercase">Ativo</span>`
            : `<span class="bg-gray-500/20 text-gray-400 text-[10px] px-2 py-0.5 rounded ml-2 uppercase">Inativo</span>`;

        tbody.append(`
            <tr class="text-gray-300 text-sm border-b border-gray-800 hover:bg-[#0f172a]/50">
                <td class="px-6 py-4 font-semibold text-white">
                    <div class="flex items-center">
                        ${produto.nome} ${status_badge}
                    </div>
                    <p class="text-gray-500 text-xs font-normal mt-1 truncate max-w-[200px]">${produto.descricao || ""}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="bg-gray-800 text-[10px] px-2 py-1 rounded text-gray-300 uppercase tracking-wider">
                        ${nome_categoria}
                    </span>
                </td>
                <td class="px-6 py-4 font-semibold text-white">${preco_formatado}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold ${estoque_cor}">
                        ${produto.estoque} un
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="javascript:void(0)" data-id="${produto.id}" class="btn_editar_produto text-cyan-400 hover:text-cyan-300 font-semibold transition">Editar</a>
                </td>
            </tr>
        `);
    });
}

function montar_select_categorias(categorias) {
    let options = '<option value="">Todas as Categorias</option>';
    categorias.forEach(function (categoria) {
        options += `<option value="${categoria.id}">${categoria.nome.toUpperCase()}</option>`;
    });
    
    // Atualiza o filtro principal
    $("#filtro_categoria").html(options);
    
    // Se você tiver selects de categoria nos modais de criação/edição, atualize-os aqui também:
    // $("#categoria_produto, #edit_categoria_produto").html(options); 
}

function montar_formulario_edicao(produto) {
    $("#edit_produto_id").val(produto.id);
    $("#edit_nome_produto").val(produto.nome);
    $("#edit_descricao_produto").val(produto.descricao);
    $("#edit_preco_produto").val(produto.preco);
    $("#edit_custo_produto").val(produto.custo);
    $("#edit_estoque_produto").val(produto.estoque);
    $("#edit_estoque_minimo_produto").val(produto.estoque_minimo);
    $("#edit_estoque_maximo_produto").val(produto.estoque_maximo);
    $("#edit_categoria_produto").val(produto.categoria_id);
    $("#edit_imagem_url_produto").val(produto.imagem_url);
    $("#edit_status_produto").val(produto.status);
}

function limpar_formulario_produto() {
    $("#nome_produto").val("");
    $("#descricao_produto").val("");
    $("#preco_produto").val("");
    $("#custo_produto").val("");
    $("#estoque_produto").val("");
    $("#estoque_minimo_produto").val("0");
    $("#estoque_maximo_produto").val("");
    $("#categoria_produto").val("");
    $("#imagem_url_produto").val("");
    $("#status_produto").val("ativo");
    $("#erro_novo_produto").text("");
}

// ==========================================
// 4. LÓGICA DE NEGÓCIO E UTILITÁRIOS
// ==========================================

function coletar_dados_formulario(is_edicao) {
    const prefixo = is_edicao ? "edit_" : "";
    
    let dados = {
        id_usuario: $("#id_usuario_menu").val(),
        nome: $(`#${prefixo}nome_produto`).val(),
        descricao: $(`#${prefixo}descricao_produto`).val(),
        preco: $(`#${prefixo}preco_produto`).val(),
        custo: $(`#${prefixo}custo_produto`).val(),
        estoque: $(`#${prefixo}estoque_produto`).val(),
        estoque_minimo: $(`#${prefixo}estoque_minimo_produto`).val(),
        estoque_maximo: $(`#${prefixo}estoque_maximo_produto`).val(),
        categoria_id: $(`#${prefixo}categoria_produto`).val(),
        imagem_url: $(`#${prefixo}imagem_url_produto`).val(),
        status: $(`#${prefixo}status_produto`).val()
    };

    if (is_edicao) {
        dados.id_produto = $("#edit_produto_id").val();
    } else {
        // Garantir que status tenha um padrão na criação se vier vazio
        dados.status = dados.status || 'ativo'; 
    }

    return dados;
}

function abrir_modal(seletor) {
    $(seletor).removeClass("hidden").css("display", "flex");
    $("body").addClass("overflow-hidden");
}

function fechar_modais() {
    $("#modal_novo_produto, #modal_detalhes_produto").addClass("hidden").css("display", "none");
    $("body").removeClass("overflow-hidden");
}