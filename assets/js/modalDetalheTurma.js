/* ============================================================
   modalDetalheTurma.js
   Abre detalhes da turma ao clicar no card.
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('modalDetalheTurmaOverlay');
    const modal = document.getElementById('modalDetalheTurma');
    const btnFechar = document.getElementById('btnFecharDetalheTurma');

    const detalheNomeTurma = document.getElementById('detalheNomeTurma');
    const detalheIdiomaNivel = document.getElementById('detalheIdiomaNivel');
    const detalheProfessor = document.getElementById('detalheProfessor');
    const detalheStatusTurma = document.getElementById('detalheStatusTurma');
    const detalheDias = document.getElementById('detalheDias');
    const detalheHorario = document.getElementById('detalheHorario');
    const detalheDataInicio = document.getElementById('detalheDataInicio');
    const detalheCapacidade = document.getElementById('detalheCapacidade');
    const detalheObservacao = document.getElementById('detalheObservacao');
    const detalheQtdAlunos = document.getElementById('detalheQtdAlunos');
    const listaAlunosTurma = document.getElementById('listaAlunosTurma');
    const mensagemDetalheTurma = document.getElementById('mensagemDetalheTurma');

    const btnEditarTurmaDetalhe = document.getElementById('btnEditarTurmaDetalhe');
    const btnIncluirAlunoDetalhe = document.getElementById('btnIncluirAlunoDetalhe');

    let cardAtual = null;
    let turmaAtual = null;

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
        limparMensagem();
    }

    function limparMensagem() {
        if (!mensagemDetalheTurma) return;

        mensagemDetalheTurma.classList.remove('ativo', 'erro', 'sucesso');
        mensagemDetalheTurma.textContent = '';
    }

    function mostrarMensagem(texto, tipo = 'erro') {
        if (!mensagemDetalheTurma) return;

        mensagemDetalheTurma.textContent = texto;
        mensagemDetalheTurma.classList.remove('erro', 'sucesso');
        mensagemDetalheTurma.classList.add('ativo', tipo);
    }

    function formatarData(data) {
        if (!data) return '—';

        const partes = data.split('-');

        if (partes.length !== 3) {
            return data;
        }

        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    function formatarHora(hora) {
        if (!hora) return '';
        return hora.substring(0, 5);
    }

    function textoStatus(status) {
        switch (status) {
            case 'ativa':
                return 'Ativa';
            case 'encerrada':
                return 'Encerrada';
            case 'cancelada':
                return 'Cancelada';
            default:
                return 'Inativa';
        }
    }

    function aplicarClasseStatus(status) {
        detalheStatusTurma.className = 'statusTurma';

        switch (status) {
            case 'ativa':
                detalheStatusTurma.classList.add('status-ativa');
                break;
            case 'encerrada':
                detalheStatusTurma.classList.add('status-encerrada');
                break;
            case 'cancelada':
                detalheStatusTurma.classList.add('status-cancelada');
                break;
            default:
                detalheStatusTurma.classList.add('status-inativa');
                break;
        }
    }

    async function buscarDetalhesTurma(idTurma) {
        const resposta = await fetch(`../actions/buscarDetalhesTurma.act.php?id_turma=${idTurma}`);
        return await resposta.json();
    }

    function preencherTurma(turma) {
        turmaAtual = turma;

        detalheNomeTurma.textContent = turma.nome_turma || 'Turma sem nome';
        detalheIdiomaNivel.textContent = `${turma.nome_idioma || 'Sem idioma'} • ${turma.nome_nivel || 'Sem nível'}`;
        detalheProfessor.textContent = `Prof: ${turma.nome_professor || 'Sem professor'}`;

        detalheStatusTurma.textContent = textoStatus(turma.status);
        aplicarClasseStatus(turma.status);

        detalheDias.textContent = turma.dia_semana || '—';

        const horaInicio = formatarHora(turma.hora_inicio);
        const horaFim = formatarHora(turma.hora_fim);

        detalheHorario.textContent = horaInicio && horaFim
            ? `${horaInicio} às ${horaFim}`
            : '—';

        detalheDataInicio.textContent = formatarData(turma.data_inicio);

        detalheCapacidade.textContent = turma.capacidade
            ? `${turma.qtd_alunos || 0}/${turma.capacidade}`
            : `${turma.qtd_alunos || 0}`;

        detalheObservacao.textContent = turma.observacao || '—';
    }

    function renderizarAlunos(alunos) {
        const total = alunos.length;

        detalheQtdAlunos.textContent = total === 1
            ? '1 aluno'
            : `${total} alunos`;

        if (total === 0) {
            listaAlunosTurma.innerHTML = `
                <p class="texto-config">Nenhum aluno matriculado nesta turma.</p>
            `;
            return;
        }

        listaAlunosTurma.innerHTML = alunos.map(aluno => `
            <div class="item-aluno-turma">
                <div>
                    <strong>${aluno.nome}</strong>
                    <p>${aluno.email || 'Sem e-mail'}</p>
                </div>

                <a href="pageAluno.php?id_aluno=${aluno.id_aluno}" class="btn-ver-aluno">
                    Ver aluno
                </a>
            </div>
        `).join('');
    }

    async function abrirDetalhesTurma(card) {
        try {
            cardAtual = card;

            const idTurma = card.dataset.id_turma;

            if (!idTurma) {
                return;
            }

            listaAlunosTurma.innerHTML = '<p>Carregando alunos...</p>';
            abrirModal();

            const resposta = await buscarDetalhesTurma(idTurma);

            if (!resposta.sucesso) {
                mostrarMensagem(resposta.mensagem || 'Erro ao buscar turma.', 'erro');
                return;
            }

            preencherTurma(resposta.turma);
            renderizarAlunos(resposta.alunos || []);

        } catch (erro) {
            mostrarMensagem('Erro de conexão ao buscar detalhes da turma.', 'erro');
        }
    }

    document.addEventListener('click', function (event) {
        const card = event.target.closest('.cardTurma');

        if (!card) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        abrirDetalhesTurma(card);
    }, true);

    btnFechar?.addEventListener('click', fecharModal);
    overlay.addEventListener('click', fecharModal);

    btnEditarTurmaDetalhe?.addEventListener('click', function () {
        if (!cardAtual) return;

        fecharModal();

        document.dispatchEvent(new CustomEvent('editarTurmaPeloDetalhe', {
            detail: {
                card: cardAtual,
                turma: turmaAtual
            }
        }));
    });

    btnIncluirAlunoDetalhe?.addEventListener('click', function () {
        if (!cardAtual) return;

        fecharModal();

        document.dispatchEvent(new CustomEvent('incluirAlunoPeloDetalhe', {
            detail: {
                card: cardAtual,
                turma: turmaAtual
            }
        }));
    });
});