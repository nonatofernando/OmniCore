let formAtivo = null;

function abrirModal(id) {
    const modal = document.getElementById(id);
    modal.classList.replace('hidden', 'flex');
}

function fecharModal(id) {
    const modal = document.getElementById(id);
    modal.classList.replace('flex', 'hidden');
}

// Intercepta todos os formulários da página
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Trava o envio automático
        formAtivo = this; // Armazena qual card foi clicado
        abrirModal('modalSalvar');
    });
});

// Ao clicar em confirmar dentro do modal de salvar
document.getElementById('confirmarBtn').addEventListener('click', function() {
    if (formAtivo) {
        formAtivo.submit(); // Envia o formulário correto
    }
});