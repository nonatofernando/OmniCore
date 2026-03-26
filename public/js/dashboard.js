$(document).ready(function () {
    // 1. Preenche os dados de texto e listas
    preencherDadosDashboardMocados();
    
    // 2. Renderiza o gráfico
    getdadosGraficoVendasSemana();
});

function preencherDadosDashboardMocados() {
    // Oculta o loader e mostra o container do gráfico
    $("#loader").fadeOut(300);

    // --- CARDS DE TOTAIS ---
    $("#total_pedidos_hoje").text("45");
    $("#variacao_pedidos_hoje").html('↑ +12% <span class="text-gray-500 font-normal">vs ontem</span>').removeClass('text-red-400').addClass('text-green-400');

    $("#total_pendentes").text("12");
    $("#variacao_pendentes").html('↓ -2% <span class="text-gray-500 font-normal">vs ontem</span>').removeClass('text-green-400').addClass('text-red-400');

    $("#total_entregues").text("33");
    $("#variacao_entregues").html('↑ +8% <span class="text-gray-500 font-normal">vs ontem</span>').removeClass('text-red-400').addClass('text-green-400');

    $("#total_receita").text("R$ 8.450,75");
    $("#variacao_receita").html('↑ +15% <span class="text-gray-500 font-normal">vs ontem</span>').removeClass('text-red-400').addClass('text-green-400');

    // --- PEDIDOS RECENTES (5 ITENS) ---
    const pedidosHtml = `
        <div class="flex items-center justify-between pb-3 border-b border-gray-700/50 last:border-0">
            <div>
                <p class="font-medium text-sm text-gray-200">#1056 - Ana Beatriz</p>
                <p class="text-xs text-gray-400">Há 15 minutos</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-cyan-400">R$ 250,00</p>
                <span class="text-[10px] bg-orange-900/30 text-orange-400 px-2 py-0.5 rounded border border-orange-800/50">Pendente</span>
            </div>
        </div>

        <div class="flex items-center justify-between pb-3 border-b border-gray-700/50 last:border-0">
            <div>
                <p class="font-medium text-sm text-gray-200">#1055 - Carlos Eduardo</p>
                <p class="text-xs text-gray-400">Há 2 horas</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-cyan-400">R$ 125,50</p>
                <span class="text-[10px] bg-green-900/30 text-green-400 px-2 py-0.5 rounded border border-green-800/50">Entregue</span>
            </div>
        </div>

        <div class="flex items-center justify-between pb-3 border-b border-gray-700/50 last:border-0">
            <div>
                <p class="font-medium text-sm text-gray-200">#1054 - Mariana Silva</p>
                <p class="text-xs text-gray-400">Há 5 horas</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-cyan-400">R$ 540,90</p>
                <span class="text-[10px] bg-green-900/30 text-green-400 px-2 py-0.5 rounded border border-green-800/50">Entregue</span>
            </div>
        </div>

        <div class="flex items-center justify-between pb-3 border-b border-gray-700/50 last:border-0">
            <div>
                <p class="font-medium text-sm text-gray-200">#1053 - Roberto Alves</p>
                <p class="text-xs text-gray-400">Ontem</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-cyan-400">R$ 89,90</p>
                <span class="text-[10px] bg-green-900/30 text-green-400 px-2 py-0.5 rounded border border-green-800/50">Entregue</span>
            </div>
        </div>

        <div class="flex items-center justify-between pb-3 border-b border-gray-700/50 last:border-0">
            <div>
                <p class="font-medium text-sm text-gray-200">#1052 - Juliana Costa</p>
                <p class="text-xs text-gray-400">Ontem</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-cyan-400">R$ 320,00</p>
                <span class="text-[10px] bg-red-900/30 text-red-400 px-2 py-0.5 rounded border border-red-800/50">Cancelado</span>
            </div>
        </div>
    `;

    // Tenta inserir pelo ID (se você adicionou no HTML). 
    // Se não achar o ID, usa o seletor alternativo que busca pelo título.
    if ($("#pedidos_recentes").length) {
        $("#pedidos_recentes").html(pedidosHtml);
    } else {
        $("h3:contains('Pedidos Recentes')").parent().next(".space-y-5").html(pedidosHtml);
    }

    // --- TOP PRODUTOS ---
    const produtosHtml = `
        <div class="flex items-center justify-between bg-gray-800/30 p-3 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-900/30 text-cyan-400 p-2 rounded-lg text-sm">🎧</div>
                <div>
                    <p class="text-sm font-medium">Fone de Ouvido Bluetooth</p>
                    <p class="text-xs text-gray-400">124 vendas</p>
                </div>
            </div>
            <span class="text-sm font-semibold text-cyan-400">R$ 199,90</span>
        </div>
        <div class="flex items-center justify-between bg-gray-800/30 p-3 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-900/30 text-cyan-400 p-2 rounded-lg text-sm">⌚</div>
                <div>
                    <p class="text-sm font-medium">Smartwatch Pro</p>
                    <p class="text-xs text-gray-400">89 vendas</p>
                </div>
            </div>
            <span class="text-sm font-semibold text-cyan-400">R$ 349,00</span>
        </div>
        <div class="flex items-center justify-between bg-gray-800/30 p-3 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-900/30 text-cyan-400 p-2 rounded-lg text-sm">🔌</div>
                <div>
                    <p class="text-sm font-medium">Cabo USB-C Rápido</p>
                    <p class="text-xs text-gray-400">56 vendas</p>
                </div>
            </div>
            <span class="text-sm font-semibold text-cyan-400">R$ 45,50</span>
        </div>
    `;
    $("#top_produtos_vendidos").html(produtosHtml);

    // --- MÉTRICAS DE PERFORMANCE ---
    const performanceHtml = `
        <div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-300">Meta Mensal de Vendas</span>
                <span class="text-sm font-bold text-cyan-400">75%</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-cyan-400 h-2 rounded-full" style="width: 75%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-2 text-right">R$ 8.450 / R$ 11.200</p>
        </div>
        
        <div class="mt-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-300">Taxa de Entrega no Prazo</span>
                <span class="text-sm font-bold text-green-400">92%</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-green-400 h-2 rounded-full" style="width: 92%"></div>
            </div>
        </div>
    `;
    $("#metricas_performance").html(performanceHtml);
}

// --- GRÁFICO (Mockado) ---
function getdadosGraficoVendasSemana() {
    const mockLabels = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
    const mockSeries = [1250.00, 980.50, 2100.90, 1540.00, 3200.75, 890.20, 450.00];

    // Adiciona um pequeno delay para a animação do loader aparecer
    setTimeout(() => {
        renderizarGraficoVendasGreal(mockLabels, mockSeries);
    }, 600);
}

function renderizarGraficoVendasGreal(labels, series) {
    // Certifique-se de que a biblioteca do Highcharts está sendo carregada no seu HTML
    if(typeof Highcharts === 'undefined') {
        console.error("Highcharts não está carregado!");
        return;
    }

    Highcharts.chart("salesChart", {
        chart: {
            type: "areaspline",
            backgroundColor: "transparent",
            style: { fontFamily: "Inter, ui-sans-serif, system-ui, sans-serif" },
        },
        title: { text: null },
        xAxis: {
            categories: labels,
            gridLineWidth: 0,
            lineColor: "#374151", // border-gray-700
            labels: { style: { color: "#9ca3af", fontSize: "11px" } }, // text-gray-400
        },
        yAxis: {
            title: { text: null },
            gridLineColor: "#374151", // border-gray-700
            gridLineDashStyle: 'Dash',
            labels: {
                style: { color: "#9ca3af", fontSize: "11px" },
                formatter: function () { return "R$ " + this.value; },
            },
        },
        tooltip: {
            backgroundColor: "#1f2937", // bg-gray-800
            style: { color: "#f3f4f6" }, // text-gray-100
            borderWidth: 1,
            borderColor: "#374151", // border-gray-700
            shared: true,
            valuePrefix: "R$ ",
        },
        plotOptions: {
            areaspline: {
                fillColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, "rgba(34, 211, 238, 0.3)"], // cyan-400 com opacidade
                        [1, "rgba(34, 211, 238, 0)"],
                    ],
                },
                marker: { 
                    enabled: true,
                    fillColor: "#1f2937",
                    lineWidth: 2,
                    lineColor: "#22d3ee" 
                },
                lineWidth: 3,
                lineColor: "#22d3ee", // cyan-400
            },
        },
        series: [{
            name: "Vendas",
            data: series,
            showInLegend: false,
        }],
        credits: { enabled: false },
    });
}