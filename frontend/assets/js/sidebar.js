/**
 * sidebar.js
 * Marca o item ativo da sidebar com base na URL atual.
 */
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.sidebar-item');
    const paginaAtual = window.location.pathname.split('/').pop();

    links.forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && href === paginaAtual) {
            link.style.backgroundColor = '#1e3a5f';
            link.style.borderLeft = '3px solid var(--laranja)';
        }
    });
});
