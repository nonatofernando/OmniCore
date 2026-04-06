$(document).ready(function () {
    get_vendas_semanais();
});

/**
 * Calcula o tempo decorrido desde uma data e retorna uma string amigável.
 *
 * @param {string|Date} data_str Data de referência
 * @returns {string} Tempo decorrido em formato humano ("Agora mesmo", "Há X minutos", etc.)
 */
function tempo_decorrido(data_str) {
    const agora = new Date();
    const data = new Date(data_str);
    const diff = Math.floor((agora - data) / 1000);

    const minutos = Math.floor(diff / 60);
    const horas = Math.floor(diff / 3600);
    const dias = Math.floor(diff / 86400);

    if (minutos < 1) return "Agora mesmo";
    if (minutos < 60) return `Há ${minutos} minuto${minutos > 1 ? "s" : ""}`;
    if (horas < 24) return `Há ${horas} hora${horas > 1 ? "s" : ""}`;
    if (dias === 1) return "Ontem";
    return `Há ${dias} dias`;
}

/**
 * Capitaliza a primeira letra de um texto.
 *
 * @param {string} texto Texto a ser capitalizado
 * @returns {string} Texto com a primeira letra maiúscula
 */
function capitalize(texto) {
    return texto ? texto.charAt(0).toUpperCase() + texto.slice(1) : "";
}

/**
 * Retorna a classe CSS correspondente ao status de um pedido.
 *
 * @param {string} status Status do pedido ("pendente", "entregue", "cancelado")
 * @returns {string} Classes CSS para estilização
 */
function get_status_class(status) {
    switch (status) {
        case "pendente":
            return "bg-orange-900/30 text-orange-400 border-orange-800/50";
        case "entregue":
            return "bg-green-900/30 text-green-400 border-green-800/50";
        case "cancelado":
            return "bg-red-900/30 text-red-400 border-red-800/50";
        default:
            return "bg-gray-800 text-gray-300 border-gray-600";
    }
}

/**
 * Calcula a variação percentual entre dois valores.
 *
 * @param {number} atual Valor atual
 * @param {number} anterior Valor anterior
 * @returns {number} Percentual de variação (positivo ou negativo)
 */
function calcular_variacao(atual, anterior) {
    atual = Number(atual || 0);
    anterior = Number(anterior || 0);
    if (anterior === 0) return atual > 0 ? 100 : 0;
    return ((atual - anterior) / anterior) * 100;
}

/**
 * Retorna uma string HTML estilizada mostrando a variação percentual.
 *
 * @param {number} valor Percentual de variação
 * @returns {string} HTML com cor verde se positivo e vermelha se negativo
 */
function render_variacao(valor) {
    const positivo = valor >= 0;
    return `
        <span class="${positivo ? "text-green-400" : "text-red-400"}">
            ${positivo ? "+" : ""}${valor.toFixed(1)}%
        </span>
        <span class="text-gray-500 font-normal"> vs ontem</span>
    `;
}

/**
 * Faz requisição AJAX para obter dados de vendas semanais e renderiza tudo.
 *
 * @returns {void}
 */
function get_vendas_semanais() {
    $.ajax({
        type: "GET",
        url: "/vendas-semanais",
        dataType: "json",
        data: { id_usuario: $("#id_usuario_menu").val() },
        beforeSend: () => $(".loader").show(),
        success: function (res) {
            $(".loader").hide();
            render_cards(res);
            render_pedidos(res.pedidos || []);
            render_produtos(res.produtos || []);
            render_grafico(res);
            render_performance(res.performance);
        },
        error: function (err) {
            $(".loader").hide();
            console.error("Erro:", err);
        },
    });
}

/**
 * Atualiza os cards principais de pedidos, pendentes, entregues e receita.
 *
 * @param {Object} res Objeto com dados de vendas
 * @param {number} res.total_pedidos Total de pedidos do dia
 * @param {number} res.pendentes Total de pedidos pendentes
 * @param {number} res.entregues Total de pedidos entregues
 * @param {number} res.receita Receita total
 * @param {number} res.pedidos_ontem Total de pedidos do dia anterior
 * @param {number} res.pendentes_ontem Total de pendentes do dia anterior
 * @param {number} res.entregues_ontem Total de entregues do dia anterior
 * @param {number} res.receita_ontem Receita do dia anterior
 * @returns {void}
 */
function render_cards(res) {
    $("#total_pedidos_hoje").text(res.total_pedidos || 0);
    $("#total_pendentes").text(res.pendentes || 0);
    $("#total_entregues").text(res.entregues || 0);
    $("#total_receita").text(`R$ ${Number(res.receita || 0).toFixed(2)}`);

    $("#variacao_pedidos_hoje").html(
        render_variacao(
            calcular_variacao(res.total_pedidos, res.pedidos_ontem),
        ),
    );
    $("#variacao_pendentes").html(
        render_variacao(calcular_variacao(res.pendentes, res.pendentes_ontem)),
    );
    $("#variacao_entregues").html(
        render_variacao(calcular_variacao(res.entregues, res.entregues_ontem)),
    );
    $("#variacao_receita").html(
        render_variacao(calcular_variacao(res.receita, res.receita_ontem)),
    );
}

/**
 * Renderiza lista de pedidos recentes.
 *
 * @param {Array<Object>} pedidos Lista de pedidos
 * @param {string} pedidos[].numero_pedido Número do pedido
 * @param {string|Date} pedidos[].created_at Data de criação
 * @param {number} pedidos[].total Total do pedido
 * @param {string} pedidos[].status Status do pedido
 * @returns {void}
 */
function render_pedidos(pedidos) {
    let html = "";
    pedidos.forEach((p) => {
        html += `
            <div class="flex items-center justify-between pb-3 border-b border-gray-700/50">
                <div>
                    <p class="font-medium text-sm text-gray-200">${p.numero_pedido}</p>
                    <p class="text-xs text-gray-400">${tempo_decorrido(p.created_at)}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm text-cyan-400">R$ ${Number(p.total || 0).toFixed(2)}</p>
                    <span class="text-[10px] ${get_status_class(p.status)} px-2 py-0.5 rounded border">
                        ${capitalize(p.status)}
                    </span>
                </div>
            </div>
        `;
    });
    $("#pedidos_recentes").html(
        html || "<p class='text-gray-400 text-sm'>Nenhum pedido</p>",
    );
}

/**
 * Renderiza os produtos mais vendidos.
 *
 * @param {Array<Object>} produtos Lista de produtos
 * @param {string} produtos[].icone Ícone do produto
 * @param {string} produtos[].nome Nome do produto
 * @param {number} produtos[].vendidos Quantidade vendida
 * @param {number} produtos[].preco Preço do produto
 * @returns {void}
 */
function render_produtos(produtos) {
    let html = "";
    produtos.forEach((p) => {
        html += `
            <div class="flex items-center justify-between bg-gray-800/30 p-3 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-cyan-900/30 text-cyan-400 p-2 rounded-lg text-sm">
                        ${p.icone || "📦"}
                    </div>
                    <div>
                        <p class="text-sm font-medium">${p.nome}</p>
                        <p class="text-xs text-gray-400">${p.vendidos || 0} vendas</p>
                    </div>
                </div>
                <span class="text-sm font-semibold text-cyan-400">
                    R$ ${Number(p.preco || 0).toFixed(2)}
                </span>
            </div>
        `;
    });
    $("#top_produtos_vendidos").html(
        html || "<p class='text-gray-400 text-sm'>Sem produtos</p>",
    );
}

/**
 * Renderiza gráfico principal de vendas por semana.
 *
 * @param {Object} res Objeto contendo os dados do gráfico
 * @param {Array<string>} res.labels Labels do eixo X
 * @param {Array<number>} res.series_passada Valores da semana passada
 * @param {Array<number>} res.series_atual Valores da semana atual
 * @returns {void}
 */
function render_grafico(res) {
    Highcharts.chart("salesChart", {
        chart: { type: "areaspline", backgroundColor: "transparent" },
        title: { text: null },
        xAxis: {
            categories: res.labels || [],
            labels: { style: { color: "#9ca3af" } },
        },
        yAxis: {
            title: { text: null },
            labels: {
                formatter: function () {
                    return "R$ " + this.value;
                },
            },
        },
        tooltip: { shared: true, valuePrefix: "R$ " },
        plotOptions: { series: { animation: { duration: 800 } } },
        series: [
            {
                name: "Semana passada",
                data: res.series_passada || [],
                color: "rgba(158,159,163,0.4)",
            },
            {
                name: "Semana atual",
                data: res.series_atual || [],
                color: "#22d3ee",
            },
        ],
        credits: { enabled: false },
    });
}

/**
 * Renderiza gráfico de performance de entregues e taxa.
 *
 * @param {Object} performance Objeto contendo métricas de performance
 * @param {number} performance.entregues_passado Entregues na semana passada
 * @param {number} performance.entregues_atual Entregues na semana atual
 * @param {number} performance.taxa_passado Taxa % na semana passada
 * @param {number} performance.taxa_atual Taxa % na semana atual
 * @returns {void}
 */
function render_performance(performance) {
    Highcharts.chart("performanceChart", {
        chart: { type: "column", backgroundColor: "transparent" },
        title: {
            text: "Performance de Entregues e Taxa",
            align: "left",
            style: { color: "#fff", fontSize: "14px" },
        },
        xAxis: {
            categories: ["Entregues", "Taxa %"],
            labels: { style: { color: "#9ca3af" } },
        },
        yAxis: { min: 0, title: { text: "Quantidade / %" } },
        tooltip: {
            shared: true,
            headerFormat: "<b>{point.key}</b><br/>",
            pointFormat: "{series.name}: <b>{point.y}</b>",
        },
        plotOptions: {
            column: {
                grouping: true,
                shadow: false,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: { color: "#fff", fontWeight: "bold" },
                },
            },
        },
        series: [
            {
                name: "Anterior",
                color: "rgba(158, 159, 163, 0.5)",
                data: [performance.entregues_passado, performance.taxa_passado],
            },
            {
                name: "Atual",
                color: "#22d3ee",
                data: [performance.entregues_atual, performance.taxa_atual],
            },
        ],
        credits: { enabled: false },
        exporting: { enabled: true },
    });
}
