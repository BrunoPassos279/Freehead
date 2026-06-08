document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.card-financeiro-clicavel');

    const overlay = document.getElementById('modalPendenciasOverlay');
    const modal = document.getElementById('modalPendenciasFinanceiras');
    const btnFechar = document.getElementById('btnFecharPendenciasFinanceiras');
    const titulo = document.getElementById('tituloPendenciasFinanceiras');
    const lista = document.getElementById('listaPendenciasFinanceiras');

    if (!overlay || !modal) {
        return;
    }

    function abrirModal() {
        overlay.classList.add('ativo');
        modal.classList.add('ativo');
    }

    function fecharModal() {
        overlay.classList.remove('ativo');
        modal.classList.remove('ativo');
    }

    function formatarValor(valor) {
        return Number(valor || 0).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    function formatarData(data) {
        if (!data) {
            return '-';
        }

        const partes = data.split('-');

        if (partes.length !== 3) {
            return data;
        }

        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    async function carregarPendencias(tipo) {
        lista.innerHTML = '<p>Carregando...</p>';

        titulo.textContent = tipo === 'atrasado'
            ? 'Mensalidades em atraso'
            : 'Mensalidades a receber';

        abrirModal();

        try {
            const resposta = await fetch(`../actions/buscarPendenciasFinanceiras.act.php?tipo=${tipo}`);
            const dados = await resposta.json();

            if (!dados.sucesso) {
                lista.innerHTML = `<p>${dados.mensagem || 'Erro ao buscar pendências.'}</p>`;
                return;
            }

            if (!dados.pendencias || dados.pendencias.length === 0) {
                lista.innerHTML = '<p>Nenhum aluno encontrado.</p>';
                return;
            }

            lista.innerHTML = dados.pendencias.map(item => `
                <div class="item-pendencia-financeira">
                    <div>
                        <strong>${item.nome_aluno}</strong>
                        <p>${item.nome_turma || 'Sem turma'}</p>
                        <p>Vencimento: ${formatarData(item.data_vencimento)}</p>
                    </div>

                    <div class="valor-pendencia">
                        ${formatarValor(item.valor_mensalidade)}
                    </div>

                    <a 
                        href="pageAluno.php?id_aluno=${item.id_aluno}" 
                        class="btn-ver-aluno"
                    >
                        Ver aluno
                    </a>
                </div>
            `).join('');

        } catch (erro) {
            lista.innerHTML = '<p>Erro de conexão ao buscar pendências.</p>';
        }
    }

    cards.forEach(card => {
        card.addEventListener('click', function () {
            carregarPendencias(card.dataset.tipo);
        });
    });

    btnFechar?.addEventListener('click', fecharModal);
    overlay.addEventListener('click', fecharModal);
});