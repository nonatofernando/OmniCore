$(document).ready(function () {
    get_vendas_semanais();
});

function tempo_decorrido(da) {
    let agora = new Date();
    let data = new Date(da);
    let diferenca = Math.floor((agora - data) / 1000);

    let minutos = Math.floor(diferenca / 60);
    let horas = Math.floor(diferenca / 3600);
    let dias = Math.floor(diferenca / 86400);

    if (minutos < 1) return "Agora mesmo";
    if (minutos < 60) return `Há ${minutos} minuto${minutos > 1 ? "s" : ""}`;
    if (horas < 24) return `Há ${horas} hora${horas > 1 ? "s" : ""}`;
    if (dias === 1) return "Ontem";
    return `Há ${dias} dias`;
}

function capitalize(texto) {
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}

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

function get_vendas_semanais() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/vendas-semanais",

        beforeSend: function () {
            $(".loader").show();
        },

        success: function (res) {
            $(".loader").hide();

            $("#total_pedidos_hoje").text(res.total_pedidos);
            $("#total_pendentes").text(res.pendentes);
            $("#total_entregues").text(res.entregues);
            $("#total_receita").text(
                `R$ ${parseFloat(res.receita).toFixed(2)}`,
            );

            let pedidos_html = "";

            res.pedidos.forEach(function (pedido) {
                let tempo = tempo_decorrido(pedido.created_at);
                let status_class = get_status_class(pedido.status);
                let status_texto = capitalize(pedido.status);

                pedidos_html += `
            <div class="flex items-center justify-between pb-3 border-b border-gray-700/50 last:border-0">
                <div>
                    <p class="font-medium text-sm text-gray-200">${pedido.numero_pedido}</p>
                    <p class="text-xs text-gray-400">${tempo}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm text-cyan-400">R$ ${parseFloat(pedido.total).toFixed(2)}</p>
                    <span class="text-[10px] ${status_class} px-2 py-0.5 rounded border">
                        ${status_texto}
                    </span>
                </div>
            </div>
        `;
            });

            $("#pedidos_recentes").html(pedidos_html);

            let produtosHtml = "";

            res.produtos.forEach(function (produto) {
                let tempo = tempo_decorrido(produto.created_at);
                let status_class = get_status_class(produto.status);
                let status_texto = capitalize(produto.status);

                produtosHtml += `
                    <div class="flex items-center justify-between bg-gray-800/30 p-3 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="bg-cyan-900/30 text-cyan-400 p-2 rounded-lg text-sm">${produto.icone || "📦"}</div>
                            <div>
                                <p class="text-sm font-medium">${produto.nome}</p>
                                <p class="text-xs text-gray-400">${produto.vendidos || 0} vendas</p>
                                
                                <p class="text-xs mt-1 ${status_class}">${status_texto} • ${tempo}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-cyan-400">R$ ${produto.preco}</span>
                    </div>`;
            });

            $("#top_produtos_vendidos").html(produtosHtml);

            renderizarGraficoVendasGreal(res.labels, res.series);

            const performace_html = `
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-300">Meta Mensal de Vendas</span>
                        <span class="text-sm font-bold text-cyan-400">${res.performance.vendas.porcentagem}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-cyan-400 h-2 rounded-full" style="width: ${res.performance.vendas.porcentagem_barra}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-right">R$ ${res.performance.vendas.receita_atual} / R$ ${res.performance.vendas.meta_valor}</p>
                </div>
                
                <div class="mt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-300">Taxa de Entregas Realizadas</span>
                        <span class="text-sm font-bold text-green-400">${res.performance.entregas.taxa_sucesso}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-green-400 h-2 rounded-full" style="width: ${res.performance.entregas.taxa_sucesso}%"></div>
                    </div>
                </div>`;

            $("#metricas_performance").html(performace_html);
        },

        error: function (err) {
            $(".loader").hide();
            console.error("Erro:", err);
        },
    });
}

function renderizarGraficoVendasGreal(labels, series) {
    if (typeof Highcharts === "undefined") {
        console.error("Highcharts não está carregado!");
        return;
    }

    Highcharts.chart("salesChart", {
        chart: {
            type: "areaspline",
            backgroundColor: "transparent",
            style: {
                fontFamily: "Inter, ui-sans-serif, system-ui, sans-serif",
            },
        },
        title: { text: null },
        xAxis: {
            categories: labels,
            gridLineWidth: 0,
            lineColor: "#374151",
            labels: { style: { color: "#9ca3af", fontSize: "11px" } },
        },
        yAxis: {
            title: { text: null },
            gridLineColor: "#374151",
            gridLineDashStyle: "Dash",
            labels: {
                style: { color: "#9ca3af", fontSize: "11px" },
                formatter: function () {
                    return "R$ " + this.value;
                },
            },
        },
        tooltip: {
            backgroundColor: "#1f2937",
            style: { color: "#f3f4f6" },
            borderWidth: 1,
            borderColor: "#374151",
            shared: true,
            valuePrefix: "R$ ",
        },
        plotOptions: {
            areaspline: {
                fillColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, "rgba(34, 211, 238, 0.3)"],
                        [1, "rgba(34, 211, 238, 0)"],
                    ],
                },
                marker: {
                    enabled: true,
                    fillColor: "#1f2937",
                    lineWidth: 2,
                    lineColor: "#22d3ee",
                },
                lineWidth: 3,
                lineColor: "#22d3ee",
            },
        },
        series: [
            {
                name: "Vendas",
                data: series,
                showInLegend: false,
            },
        ],
        credits: { enabled: false },
    });
}