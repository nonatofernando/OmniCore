$(document).ready(function () {
    // Inicialização
    buscar_categorias();
    buscar_produtos();

    $("#btn_novo_produto").on("click", function () {
        limpar_formulario_produto();
        $("#modal_produto_titulo").text("Novo Produto");
        $("#btn_excluir_produto").addClass("hidden"); // Esconde excluir na criação
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

    $("#salvar_produto_final").click(function () {
        const id = $("#edit_produto_id").val();
        if (id) {
            atualizar_produto_api(id);
        } else {
            salvar_produto_api();
        }
    });

    $("#btn_excluir_produto").click(function () {
        const id_produto = $("#edit_produto_id").val();
        if (confirm("Tem certeza que deseja excluir este produto?")) {
            excluir_produto_api(id_produto);
        }
    });
});

function buscar_categorias() {
    $.ajax({
        type: "GET",
        url: "/produtos/get-categorias",
        success: function (res) {
            if (res.status === "sucesso") {
                let options = '<option value="">Todas as Categorias</option>';
                res.categorias.forEach(cat => {
                    options += `<option value="${cat.id}">${cat.nome.toUpperCase()}</option>`;
                });
                $("#filtro_categoria, #categoria_produto").html(options);
            }
        }
    });
}

function buscar_produtos(categoria_id = "", busca = "") {
    $.ajax({
        type: "GET",
        url: "/produtos/get-produtos",
        data: { categoria_id, busca },
        beforeSend: () => {
            $("#produtos_table_tbody").html('<tr><td colspan="5" class="text-center py-10 text-cyan-500 animate-pulse">Carregando...</td></tr>');
        },
        success: (res) => {
            const produtos = res.produtos || [];
            $("#total_produtos").text(`${produtos.length} produtos cadastrados`);
            montar_tabela_produtos(produtos);
        }
    });
}

function buscar_detalhes_produto(id) {
    $.ajax({
        type: "GET",
        url: `/produtos/detalhes/${id}`,
        success: function (res) {
            if (res.status === "sucesso") {
                const p = res.produto;
                $("#edit_produto_id").val(p.id);
                $("#nome_produto").val(p.nome);
                $("#descricao_produto").val(p.descricao);
                $("#preco_produto").val(p.preco);
                $("#custo_produto").val(p.custo);
                $("#estoque_produto").val(p.estoque);
                $("#estoque_minimo_produto").val(p.estoque_minimo);
                $("#categoria_produto").val(p.categoria_id);
                $("#status_produto").val(p.status);
                
                $("#modal_produto_titulo").text("Editar Produto");
                $("#btn_excluir_produto").removeClass("hidden");
                abrir_modal("#modal_novo_produto");
            }
        }
    });
}

function salvar_produto_api() {
    const dados = coletar_dados();
    $.ajax({
        type: "POST",
        url: "/produtos/salvar",
        data: dados,
        success: function (res) {
            fechar_modais();
            buscar_produtos();
            mostrar_feedback("✓", "Sucesso", "Produto cadastrado!");
        }
    });
}

function atualizar_produto_api(id) {
    const dados = coletar_dados();
    $.ajax({
        type: "POST",
        url: `/produtos/atualizar/${id}`,
        data: dados,
        success: function (res) {
            fechar_modais();
            buscar_produtos();
            mostrar_feedback("✓", "Atualizado", "Produto atualizado com sucesso!");
        }
    });
}

function excluir_produto_api(id) {
    $.ajax({
        type: "DELETE",
        url: `/produtos/excluir/${id}`,
        success: function () {
            fechar_modais();
            buscar_produtos();
            mostrar_feedback("!", "Excluído", "Produto removido.");
        }
    });
}

function coletar_dados() {
    return {
        id_usuario: $("#id_usuario_menu").val(),
        nome: $("#nome_produto").val(),
        descricao: $("#descricao_produto").val(),
        preco: $("#preco_produto").val(),
        custo: $("#custo_produto").val(),
        estoque: $("#estoque_produto").val(),
        estoque_minimo: $("#estoque_minimo_produto").val(),
        categoria_id: $("#categoria_produto").val(),
        status: $("#status_produto").val()
    };
}

function montar_tabela_produtos(produtos) {
    const tbody = $("#produtos_table_tbody");
    tbody.empty();
    
    if(produtos.length === 0) {
        tbody.append('<tr><td colspan="5" class="text-center py-10 text-gray-500">Nenhum resultado.</td></tr>');
        return;
    }

    produtos.forEach(p => {
        const preco = Number(p.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        const corEstoque = p.estoque <= (p.estoque_minimo || 0) ? "text-red-400 bg-red-500/10" : "text-green-400 bg-green-500/10";
        
        tbody.append(`
            <tr class="border-b border-gray-800 hover:bg-white/5 transition">
                <td class="px-6 py-4">
                    <div class="font-bold text-white">${p.nome}</div>
                    <div class="text-xs text-gray-500">${p.status}</div>
                </td>
                <td class="px-6 py-4"><span class="text-xs bg-gray-800 px-2 py-1 rounded">${p.categoria_nome || 'Geral'}</span></td>
                <td class="px-6 py-4 font-mono">${preco}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs ${corEstoque}">${p.estoque} un</span></td>
                <td class="px-6 py-4 text-right">
                    <button data-id="${p.id}" class="btn_editar_produto text-cyan-400 hover:underline">Editar</button>
                </td>
            </tr>
        `);
    });
}

function abrir_modal(sel) {
    $(sel).removeClass("hidden").addClass("flex");
    $("body").css("overflow", "hidden");
}

function fechar_modais() {
    $(".fixed.inset-0").addClass("hidden").removeClass("flex");
    $("body").css("overflow", "auto");
}

function limpar_formulario_produto() {
    $("#edit_produto_id").val("");
    $("#nome_produto, #descricao_produto, #preco_produto, #custo_produto, #estoque_produto").val("");
    $("#estoque_minimo_produto").val("0");
    $("#status_produto").val("ativo");
}

function mostrar_feedback(icon, titulo, msg) {
    $("#modal_feedback_icon").text(icon);
    $("#modal_feedback_titulo").text(titulo);
    $("#modal_feedback_msg").text(msg);
    abrir_modal("#modal_feedback");
}