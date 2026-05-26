/* ============================================================
   modalTurma.js
   Controla o modal da página de turmas.
   Caminho: assets/js/modalTurma.js
   ============================================================ */


/* ============================================================
   CONFIGURAÇÃO CENTRAL — troque os caminhos ao migrar para BD
   ============================================================ */
const API = {
    salvar:      '../actions/salvarTurma.php',
    editar:      '../actions/editarTurma.php',
    excluir:     '../actions/excluirTurma.php',
    matricular:  '../actions/matricularAluno.php',
    dados:       'dados.json'   // fonte dos selects e da lista de alunos
};


/* ============================================================
   1. REFERÊNCIAS
   ============================================================ */
const overlay        = document.getElementById('modalOverlay');
const modal          = document.getElementById('modalTurma');
const modalTitulo    = document.getElementById('modalTitulo');
const modalFechar    = document.getElementById('modalFechar');

const campoId        = document.getElementById('campoId');
const campoNomeTurma = document.getElementById('campoNomeTurma');
const campoIdioma    = document.getElementById('campoIdioma');
const campoNivel     = document.getElementById('campoNivel');
const campoProfessor = document.getElementById('campoProfessor');
const campoStatus    = document.getElementById('campoStatus');
const campoDia       = document.getElementById('campoDia');
const campoHoraIni   = document.getElementById('campoHoraInicio');
const campoHoraFim   = document.getElementById('campoHoraFim');
const campoDataIni   = document.getElementById('campoDataInicio');
const campoDataFim   = document.getElementById('campoDataFim');
const campoCapac     = document.getElementById('campoCapacidade');
const campoObs       = document.getElementById('campoObservacao');

const btnSalvar      = document.getElementById('btnSalvar');
const btnSalvarEdicao= document.getElementById('btnSalvarEdicao');
const acoesEdicao    = document.getElementById('acoesEdicao');
const btnEditar      = document.getElementById('btnEditar');
const btnExcluir     = document.getElementById('btnExcluir');
const btnIncluirAluno= document.getElementById('btnIncluirAluno');

const painelExclusao      = document.getElementById('painelExclusao');
const nomeExclusao        = document.getElementById('nomeExclusao');
const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
const btnConfirmarExclusao= document.getElementById('btnConfirmarExclusao');

const painelAluno      = document.getElementById('painelAluno');
const listaAlunos      = document.getElementById('listaAlunos');
const btnCancelarAluno = document.getElementById('btnCancelarAluno');
const btnConfirmarAluno= document.getElementById('btnConfirmarAluno');

let alunoSelecionadoId = null;
let dadosCache = null; // Cache do JSON para não buscar múltiplas vezes


/* ============================================================
   2. UTILITÁRIOS
   ============================================================ */
function todosOsCampos() {
    return document.getElementById('formTurma').querySelectorAll('input, select');
}

function bloquearCampos() {
    todosOsCampos().forEach(el => el.disabled = true);
}

function liberarCampos() {
    todosOsCampos().forEach(el => { if (el.type !== 'hidden') el.disabled = false; });
}

function limparCampos() {
    todosOsCampos().forEach(el => el.value = '');
    campoStatus.value = 'ativa';
}

function fecharPaineis() {
    painelExclusao.classList.remove('ativo');
    painelAluno.classList.remove('ativo');
}

function abrirModal() {
    overlay.classList.add('ativo');
    modal.classList.add('ativo');
    fecharPaineis();
}

function fecharModal() {
    overlay.classList.remove('ativo');
    modal.classList.remove('ativo');
    fecharPaineis();
    limparCampos();
    alunoSelecionadoId    = null;
    btnSalvar.style.display       = 'block';
    btnSalvarEdicao.style.display = 'none';
    acoesEdicao.style.display     = 'none';
    btnEditar.style.display       = 'inline-flex';
}

async function chamarAcao(url, dados) {
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });
        return await res.json();
    } catch (err) {
        console.error('Erro:', err);
        return { sucesso: false, mensagem: 'Erro de conexão.' };
    }
}

// Carrega o JSON uma vez e guarda em cache
async function getDados() {
    if (dadosCache) return dadosCache;
    const r = await fetch(API.dados);
    dadosCache = await r.json();
    return dadosCache;
}


/* ============================================================
   3. POPULAR SELECTS DE NÍVEL E PROFESSOR
   Chamado ao abrir o modal e ao trocar o idioma.
   idIdioma: filtra os níveis por idioma (null = todos)
   idNivelSelecionado / idProfSelecionado: marca a opção certa
   ============================================================ */
async function popularSelects(idIdioma = null, idNivelSelecionado = null, idProfSelecionado = null) {
    const dados = await getDados();

    // ---- Nível ----
    campoNivel.innerHTML = '<option value="">Selecione...</option>';
    dados.niveis
        .filter(n => !idIdioma || n.id_idioma === parseInt(idIdioma))
        .forEach(n => {
            const opt = document.createElement('option');
            opt.value       = n.id_nivel;
            opt.textContent = n.nome_nivel;
            if (n.id_nivel === parseInt(idNivelSelecionado)) opt.selected = true;
            campoNivel.appendChild(opt);
        });

    // ---- Professor ----
    campoProfessor.innerHTML = '<option value="">Selecione...</option>';
    dados.professores.forEach(p => {
        const opt = document.createElement('option');
        opt.value       = p.id_professor;
        opt.textContent = p.nome;
        if (p.id_professor === parseInt(idProfSelecionado)) opt.selected = true;
        campoProfessor.appendChild(opt);
    });
}

// Quando o usuário troca o idioma no select, recarrega os níveis
campoIdioma.addEventListener('change', function () {
    popularSelects(this.value);
});


/* ============================================================
   4. MODO NOVO
   ============================================================ */
async function abrirModoNovo() {
    modalTitulo.textContent = 'Nova Turma';
    limparCampos();
    liberarCampos();
    await popularSelects();

    btnSalvar.style.display       = 'block';
    acoesEdicao.style.display     = 'none';
    btnSalvarEdicao.style.display = 'none';

    abrirModal();
}


/* ============================================================
   5. MODO VISUALIZAR
   dataset vem dos data-* do .cardTurma clicado
   ============================================================ */
async function abrirModoVisualizar(dataset) {
    modalTitulo.textContent = dataset.nome_turma;

    // Preenche campos simples
    campoId.value        = dataset.id_turma;
    campoNomeTurma.value = dataset.nome_turma;
    campoIdioma.value    = dataset.id_idioma;
    campoDia.value       = dataset.dia_semana;
    campoHoraIni.value   = dataset.hora_inicio;
    campoHoraFim.value   = dataset.hora_fim;
    campoDataIni.value   = dataset.data_inicio;
    campoDataFim.value   = dataset.data_fim;
    campoCapac.value     = dataset.capacidade;
    campoObs.value       = dataset.observacao || '';
    campoStatus.value    = dataset.status;

    // Popula e seleciona nível e professor corretos
    await popularSelects(dataset.id_idioma, dataset.id_nivel, dataset.id_professor);

    bloquearCampos();

    btnSalvar.style.display       = 'none';
    acoesEdicao.style.display     = 'flex';
    btnSalvarEdicao.style.display = 'none';
    btnEditar.style.display       = 'inline-flex';

    abrirModal();
}


/* ============================================================
   6. EVENTOS ABRIR/FECHAR
   ============================================================ */
modalFechar.addEventListener('click', fecharModal);
overlay.addEventListener('click', fecharModal);

document.addEventListener('click', function (e) {
    const card = e.target.closest('.cardTurma');
    if (card) { abrirModoVisualizar(card.dataset); return; }

    const btnNovo = e.target.closest('.btn-nova-turma');
    if (btnNovo) { abrirModoNovo(); }
});


/* ============================================================
   7. EDITAR
   ============================================================ */
btnEditar.addEventListener('click', function () {
    liberarCampos();
    modalTitulo.textContent       = 'Editando turma';
    btnEditar.style.display       = 'none';
    btnSalvarEdicao.style.display = 'block';
});


/* ============================================================
   8. SALVAR ALTERAÇÕES
   ============================================================ */
btnSalvarEdicao.addEventListener('click', async function () {
    if (!campoNomeTurma.value.trim()) { campoNomeTurma.focus(); return; }

    const res = await chamarAcao(API.editar, {
        id_turma:    campoId.value,
        nome_turma:  campoNomeTurma.value.trim(),
        id_idioma:   campoIdioma.value,
        id_nivel:    campoNivel.value,
        id_professor:campoProfessor.value,
        status:      campoStatus.value,
        dia_semana:  campoDia.value.trim(),
        hora_inicio: campoHoraIni.value,
        hora_fim:    campoHoraFim.value,
        data_inicio: campoDataIni.value,
        data_fim:    campoDataFim.value,
        capacidade:  campoCapac.value,
        observacao:  campoObs.value.trim()
    });

    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao salvar: ' + res.mensagem);
});


/* ============================================================
   9. SALVAR NOVA TURMA
   ============================================================ */
btnSalvar.addEventListener('click', async function () {
    if (!campoNomeTurma.value.trim()) { campoNomeTurma.focus(); return; }

    const res = await chamarAcao(API.salvar, {
        nome_turma:  campoNomeTurma.value.trim(),
        id_idioma:   campoIdioma.value,
        id_nivel:    campoNivel.value,
        id_professor:campoProfessor.value,
        status:      campoStatus.value,
        dia_semana:  campoDia.value.trim(),
        hora_inicio: campoHoraIni.value,
        hora_fim:    campoHoraFim.value,
        data_inicio: campoDataIni.value,
        data_fim:    campoDataFim.value,
        capacidade:  campoCapac.value,
        observacao:  campoObs.value.trim()
    });

    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao salvar: ' + res.mensagem);
});


/* ============================================================
   10. EXCLUIR
   ============================================================ */
btnExcluir.addEventListener('click', function () {
    nomeExclusao.textContent = campoNomeTurma.value;
    painelExclusao.classList.add('ativo');
});

btnCancelarExclusao.addEventListener('click', () => painelExclusao.classList.remove('ativo'));

btnConfirmarExclusao.addEventListener('click', async function () {
    const res = await chamarAcao(API.excluir, { id_turma: campoId.value });
    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao excluir: ' + res.mensagem);
});


/* ============================================================
   11. INCLUIR ALUNO NA TURMA
   ============================================================ */
btnIncluirAluno.addEventListener('click', function () {
    alunoSelecionadoId = null;
    carregarAlunos();
    painelAluno.classList.add('ativo');
});

btnCancelarAluno.addEventListener('click', () => painelAluno.classList.remove('ativo'));

btnConfirmarAluno.addEventListener('click', async function () {
    if (!alunoSelecionadoId) { alert('Selecione um aluno.'); return; }

    const res = await chamarAcao(API.matricular, {
        id_turma: campoId.value,
        id_aluno: alunoSelecionadoId
    });

    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao matricular: ' + res.mensagem);
});


/* ============================================================
   12. CARREGAR ALUNOS NO PAINEL DE SELEÇÃO
   ============================================================ */
async function carregarAlunos() {
    listaAlunos.innerHTML = '<p class="msg-lista">Carregando alunos...</p>';

    try {
        const dados  = await getDados();
        listaAlunos.innerHTML = '';

        if (dados.alunos.length === 0) {
            listaAlunos.innerHTML = '<p class="msg-lista">Nenhum aluno encontrado.</p>';
            return;
        }

        dados.alunos.forEach(aluno => {
            const item = document.createElement('div');
            item.classList.add('item-selecao');
            item.innerHTML = `
                <div>
                    <div class="item-selecao-nome">${aluno.nome}</div>
                    <div class="item-selecao-info">ID ${aluno.id_aluno} · ${aluno.email}</div>
                </div>
                <div class="item-radio"></div>
            `;
            item.addEventListener('click', () => {
                document.querySelectorAll('#listaAlunos .item-selecao')
                    .forEach(el => el.classList.remove('selecionado'));
                item.classList.add('selecionado');
                alunoSelecionadoId = aluno.id_aluno;
            });
            listaAlunos.appendChild(item);
        });
    } catch {
        listaAlunos.innerHTML = '<p class="msg-lista erro">Erro ao carregar alunos.</p>';
    }
}