/**
 * tabela.js
 * Utilitário para filtrar tabelas por texto (busca client-side).
 * TODO: futuramente substituir por busca server-side via fetch/AJAX para datasets grandes.
 *
 * @param {string} termo - Texto digitado pelo usuário
 * @param {string} tabelaId - ID da tabela HTML a ser filtrada
 */
function filtrarTabela(termo, tabelaId) {
    const tabela = document.getElementById(tabelaId);
    if (!tabela) return;

    const termoLower = termo.toLowerCase().trim();
    const linhas = tabela.querySelectorAll('tbody tr');
    let visiveis = 0;

    linhas.forEach(function(linha) {
        const texto = linha.textContent.toLowerCase();
        const mostrar = termoLower === '' || texto.includes(termoLower);
        linha.style.display = mostrar ? '' : 'none';
        if (mostrar) visiveis++;
    });

    // Atualiza contador de registros visíveis se existir
    const contador = document.querySelector('.tabela-registros');
    if (contador) {
        contador.textContent = visiveis + ' registro(s)';
    }
}
