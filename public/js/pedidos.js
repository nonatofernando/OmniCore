$(document).ready(function () {
    carregarPedidos();

    $("#id_btn_modal_novo_pedido").click(function () {
        $("#modal_novo_pedido").modal("show");
    });
});

function carregarPedidos() {
    $.ajax({
        type: "GET",
        data: {
            id_usuario: $("#id_usuario_menu").val(),
        },
        url: "/pedidos/get-pedidos",
        dataType: "json",
        success: (res) => {
            const tbody = $("#pedidos-table tbody");
            tbody.empty();

            res.pedidos.forEach((pedido) => {
                const data = new Date(pedido.created_at).toLocaleDateString(
                    "pt-BR",
                );

                const row = `
                    <tr class="text-gray-300 text-sm border-b border-gray-800 hover:bg-[#0f172a] transition">
                        <td class="px-6 py-4 font-semibold">${pedido.numero_pedido}</td>

                        <td class="px-6 py-4">
                            Cliente #${pedido.cliente_id}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                ${pedido.status === "entregue" ? "bg-green-500/20 text-green-400" : ""}
                                ${pedido.status === "pendente" ? "bg-yellow-500/20 text-yellow-400" : ""}
                            ">
                                ${pedido.status}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-semibold">
                            R$ ${parseFloat(pedido.total).toFixed(2)}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="/pedidos/${pedido.id}" 
                            class="text-cyan-400 hover:text-cyan-300 font-semibold">
                                Detalhes
                            </a>
                        </td>
                    </tr>
                `;

                tbody.append(row);
            });
        },
        error: (err) => {
            console.error("Erro ao carregar pedidos:", err);
        },
    });
}
