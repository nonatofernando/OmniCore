const currencyFormat = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
const formatBRL = (value) => {
    if (!value || isNaN(value)) return "R$ 0,00";
    return "R$ " + parseFloat(value).toLocaleString("pt-BR", currencyFormat);
};

$(document).ready(function () {
    Chart.defaults.color = "#9ca3af";
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.scale.grid.color = "rgba(31, 41, 55, 0.5)";

    function carregarDadosDashBoard() {
        let usuarioId = $("#id_usuario_menu").val() || 1;

        $.ajax({
            url: "/relatorios/get-dados",
            type: "GET",
            data: {
                id_cliente: usuarioId,
            },
            dataType: "json",
            success: function (response) {
                atualizarCards(response);
                renderizarGraficos(response);
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição:", error);
                alert("Não foi possível carregar os dados dos relatórios.");
            },
        });
    }

    function atualizarCards(dados) {
        $("#card-receita").text(formatBRL(dados.lucroEstimado));
        $("#card-ticket").text(formatBRL(dados.ticketMedio));
        $("#card-pedidos").text(dados.totalPedidos || 0);
        $("#card-estoque").text(formatBRL(dados.valorEmEstoque));
    }

    function renderizarGraficos(dados) {
        if (
            $("#chartFaturamento").length &&
            dados.faturamentoMensal &&
            dados.faturamentoMensal.length > 0
        ) {
            new Chart($("#chartFaturamento")[0].getContext("2d"), {
                type: "line",
                data: {
                    labels: dados.faturamentoMensal.map((item) => item.mes),
                    datasets: [
                        {
                            label: "Faturamento",
                            data: dados.faturamentoMensal.map((item) =>
                                parseFloat(item.faturamento),
                            ),
                            borderColor: "#10b981",
                            backgroundColor: "rgba(16, 185, 129, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: (ctx) => formatBRL(ctx.raw) },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => "R$ " + value },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        }

        if (
            $("#chartReceitaCusto").length &&
            dados.receitaVsCusto &&
            dados.receitaVsCusto.length > 0
        ) {
            new Chart($("#chartReceitaCusto")[0].getContext("2d"), {
                type: "bar",
                data: {
                    labels: dados.receitaVsCusto.map((item) => item.mes),
                    datasets: [
                        {
                            label: "Receita Bruta",
                            data: dados.receitaVsCusto.map((item) =>
                                parseFloat(item.receita),
                            ),
                            backgroundColor: "#3b82f6",
                            borderRadius: 4,
                        },
                        {
                            label: "Custo",
                            data: dados.receitaVsCusto.map((item) =>
                                parseFloat(item.custo),
                            ),
                            backgroundColor: "#ef4444",
                            borderRadius: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (ctx) =>
                                    ctx.dataset.label +
                                    ": " +
                                    formatBRL(ctx.raw),
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => "R$ " + value },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        }

        if (
            $("#chartCategorias").length &&
            dados.faturamentoPorCategoria &&
            dados.faturamentoPorCategoria.length > 0
        ) {
            new Chart($("#chartCategorias")[0].getContext("2d"), {
                type: "bar",
                data: {
                    labels: dados.faturamentoPorCategoria.map(
                        (item) => item.nome,
                    ),
                    datasets: [
                        {
                            label: "Total Vendido",
                            data: dados.faturamentoPorCategoria.map((item) =>
                                parseFloat(item.total_vendido),
                            ),
                            backgroundColor: "#8b5cf6",
                            borderRadius: 6,
                            barPercentage: 0.6,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: (ctx) => formatBRL(ctx.raw) },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => "R$ " + value },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        }

        if (
            $("#chartTopProdutos").length &&
            dados.topProdutos &&
            dados.topProdutos.length > 0
        ) {
            new Chart($("#chartTopProdutos")[0].getContext("2d"), {
                type: "bar",
                data: {
                    labels: dados.topProdutos.map((item) => item.nome),
                    datasets: [
                        {
                            label: "Unidades Vendidas",
                            data: dados.topProdutos.map((item) =>
                                parseInt(item.quantidade_vendida),
                            ),
                            backgroundColor: "#0ea5e9",
                            borderRadius: 4,
                        },
                    ],
                },
                options: {
                    indexAxis: "y",
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.raw + " unidades",
                            },
                        },
                    },
                    scales: {
                        x: { beginAtZero: true },
                        y: { grid: { display: false } },
                    },
                },
            });
        }

        if (
            $("#chartStatus").length &&
            dados.funilStatus &&
            dados.funilStatus.length > 0
        ) {
            new Chart($("#chartStatus")[0].getContext("2d"), {
                type: "doughnut",
                data: {
                    labels: dados.funilStatus.map(
                        (item) =>
                            item.status.charAt(0).toUpperCase() +
                            item.status.slice(1),
                    ),
                    datasets: [
                        {
                            data: dados.funilStatus.map((item) =>
                                parseInt(item.quantidade),
                            ),
                            backgroundColor: [
                                "#10b981",
                                "#f59e0b",
                                "#3b82f6",
                                "#ef4444",
                                "#8b5cf6",
                            ],
                            borderWidth: 0,
                            hoverOffset: 6,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "70%",
                    plugins: {
                        legend: {
                            position: "right",
                            labels: { usePointStyle: true, color: "#d1d5db" },
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) =>
                                    " " +
                                    ctx.label +
                                    ": " +
                                    ctx.raw +
                                    " pedidos",
                            },
                        },
                    },
                },
            });
        }

        if ($("#chartEstoque").length && dados.saudeEstoque) {
            new Chart($("#chartEstoque")[0].getContext("2d"), {
                type: "pie",
                data: {
                    labels: Object.keys(dados.saudeEstoque),
                    datasets: [
                        {
                            data: Object.values(dados.saudeEstoque),
                            backgroundColor: ["#10b981", "#ef4444"],
                            borderWidth: 0,
                            hoverOffset: 6,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: { usePointStyle: true, color: "#d1d5db" },
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) =>
                                    " " +
                                    ctx.label +
                                    ": " +
                                    ctx.raw +
                                    " produtos",
                            },
                        },
                    },
                },
            });
        }
    }

    carregarDadosDashBoard();
});
