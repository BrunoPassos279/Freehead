/**
 * modal.js
 * Utilitário para abrir e fechar modais/popups.
 * TODO: futuramente popular modais com dados vindos do banco via fetch/AJAX.
 */

function abrirModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('aberto');
        document.body.style.overflow = 'hidden';
    }
}

function fecharModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('aberto');
        document.body.style.overflow = '';
    }
}

// Fechar modal ao clicar no overlay (fora do card)
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('aberto');
        document.body.style.overflow = '';
    }
});

// Fechar modal com tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.aberto').forEach(function(modal) {
            modal.classList.remove('aberto');
            document.body.style.overflow = '';
        });
    }
});
