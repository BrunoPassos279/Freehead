/* ============================================================
   modalTurma.js
   Controla o modal da página de turmas.
   Caminho: assets/js/modalTurma.js
   ============================================================ */


/* ============================================================
   CONFIGURAÇÃO CENTRAL
   ============================================================ */
const API = {
    salvar:      '../actions/salvarTurma.act.php',
    editar:      '../actions/editarTurma.act.php',
    excluir:     '../actions/excluirTurma.act.php',
    matricular:  '../actions/matricularAluno.act.php',
    dados:       '../actions/dadosModalTurma.act.php'
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

const mensagemValidacaoTurma = document.getElementById('mensagemValidacaoTurma');

const btnSalvar       = document.getElementById('btnSalvar');
const btnSalvarEdicao = document.getElementById('btnSalvarEdicao');
const acoesEdicao     = document.getElementById('acoesEdicao');
const btnEditar       = document.getElementById('btnEditar');
const btnExcluir      = document.getElementById('btnExcluir');
const btnIncluirAluno = document.getElementById('btnIncluirAluno');

const painelExclusao       = document.getElementById('painelExclusao');
const nomeExclusao         = document.getElementById('nomeExclusao');
const btnCancelarExclusao  = document.getElementById('btnCancelarExclusao');
const btnConfirmarExclusao = document.getElementById('btnConfirmarExclusao');

const painelAluno       = document.getElementById('painelAluno');
const listaAlunos       = document.getElementById('listaAlunos');
const btnCancelarAluno  = document.getElementById('btnCancelarAluno');
const btnConfirmarAluno = document.getElementById('btnConfirmarAluno');

const campoValorMensalidade = document.getElementById('campoValorMensalidade');
const campoDiaVencimento    = document.getElementById('campoDiaVencimento');
const mensagemValidacaoMatricula = document.getElementById('mensagemValidacaoMatricula');

let alunoSelecionadoId = null;
let dadosCache = null;


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
    todosOsCampos().forEach(el => {
        if (el.type !== 'hidden') {
            el.disabled = false;
        }
    });
}

function limparCampos() {
    todosOsCampos().forEach(el => {
        el.value = '';
    });

    campoStatus.value = 'ativa';

    campoIdioma.disabled = true;
    campoIdioma.innerHTML = '<option value="">Escolha um professor primeiro</option>';

    campoNivel.disabled = true;
    campoNivel.innerHTML = '<option value="">Escolha um idioma primeiro</option>';

    limparValidacaoTurma();
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

    alunoSelecionadoId = null;

    btnSalvar.style.display = 'block';
    btnSalvarEdicao.style.display = 'none';
    acoesEdicao.style.display = 'none';
    btnEditar.style.display = 'inline-flex';
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

        return {
            sucesso: false,
            mensagem: 'Erro de conexão.'
        };
    }
}

async function getDados() {
    if (dadosCache) {
        return dadosCache;
    }

    const r = await fetch(API.dados);
    dadosCache = await r.json();

    return dadosCache;
}

function sugerirDiaVencimentoPelaTurma() {
    let dataInicio = campoDataIni.value;

    if (!dataInicio) {
        return '10';
    }

    const partes = dataInicio.split('-');

    if (partes.length !== 3) {
        return '10';
    }

    const dia = Number(partes[2]);

    if (!dia || dia < 1 || dia > 31) {
        return '10';
    }

    return String(dia);
}


/* ============================================================
   3. POPULAR SELECTS
   Fluxo correto:
   Professor -> Idioma -> Nível
   ============================================================ */
async function popularSelects(idProfSelecionado = null, idIdiomaSelecionado = null, idNivelSelecionado = null) {
    const dados = await getDados();

    //---------------- Professor ----------------//
    campoProfessor.innerHTML = '<option value="">Selecione...</option>';

    dados.professores.forEach(professor => {
        const opt = document.createElement('option');

        opt.value = professor.id_professor;
        opt.textContent = professor.nome;

        if (Number(professor.id_professor) === Number(idProfSelecionado)) {
            opt.selected = true;
        }

        campoProfessor.appendChild(opt);
    });

    //---------------- Idioma ----------------//
    campoIdioma.innerHTML = '';

    if (!idProfSelecionado) {
        campoIdioma.disabled = true;
        campoIdioma.innerHTML = '<option value="">Escolha um professor primeiro</option>';

        campoNivel.disabled = true;
        campoNivel.innerHTML = '<option value="">Escolha um idioma primeiro</option>';
        return;
    }

    const professorSelecionado = dados.professores.find(professor => {
        return Number(professor.id_professor) === Number(idProfSelecionado);
    });

    const idiomasDoProfessor = professorSelecionado?.idiomas_ids
        ? professorSelecionado.idiomas_ids.split(',').map(id => Number(id))
        : [];

    if (idiomasDoProfessor.length === 0) {
        campoIdioma.disabled = true;
        campoIdioma.innerHTML = '<option value="">Professor sem idiomas cadastrados</option>';

        campoNivel.disabled = true;
        campoNivel.innerHTML = '<option value="">Escolha um idioma primeiro</option>';
        return;
    }

    campoIdioma.disabled = false;
    campoIdioma.innerHTML = '<option value="">Selecione...</option>';

    dados.idiomas
        .filter(idioma => idiomasDoProfessor.includes(Number(idioma.id_idioma)))
        .forEach(idioma => {
            const opt = document.createElement('option');

            opt.value = idioma.id_idioma;
            opt.textContent = idioma.nome;

            if (Number(idioma.id_idioma) === Number(idIdiomaSelecionado)) {
                opt.selected = true;
            }

            campoIdioma.appendChild(opt);
        });

    //---------------- Nível ----------------//
    campoNivel.innerHTML = '';

    if (!idIdiomaSelecionado) {
        campoNivel.disabled = true;
        campoNivel.innerHTML = '<option value="">Escolha um idioma primeiro</option>';
        return;
    }

    campoNivel.disabled = false;
    campoNivel.innerHTML = '<option value="">Selecione...</option>';

    dados.niveis
        .filter(nivel => Number(nivel.id_idioma) === Number(idIdiomaSelecionado))
        .forEach(nivel => {
            const opt = document.createElement('option');

            opt.value = nivel.id_nivel;
            opt.textContent = nivel.nome_nivel;

            if (Number(nivel.id_nivel) === Number(idNivelSelecionado)) {
                opt.selected = true;
            }

            campoNivel.appendChild(opt);
        });
}


/* ============================================================
   4. EVENTOS DOS SELECTS
   ============================================================ */
campoProfessor.addEventListener('change', function () {
    popularSelects(this.value, null, null);
});

campoIdioma.addEventListener('change', function () {
    popularSelects(campoProfessor.value, this.value, null);
});


/* ============================================================
   5. VALIDAÇÃO VISUAL DA TURMA
   ============================================================ */
function limparValidacaoTurma() {
    if (!mensagemValidacaoTurma) {
        return;
    }

    mensagemValidacaoTurma.classList.remove('ativo');
    mensagemValidacaoTurma.innerHTML = '';

    [
        campoNomeTurma,
        campoProfessor,
        campoIdioma,
        campoNivel,
        campoHoraIni,
        campoHoraFim,
        campoDataIni
    ].forEach(campo => {
        campo.classList.remove('campo-obrigatorio-erro');
    });
}

function validarTurma() {
    limparValidacaoTurma();

    const camposObrigatorios = [
        { campo: campoNomeTurma, nome: 'Nome da turma' },
        { campo: campoProfessor, nome: 'Professor' },
        { campo: campoIdioma, nome: 'Idioma' },
        { campo: campoNivel, nome: 'Nível' },
        { campo: campoHoraIni, nome: 'Horário de início' },
        { campo: campoHoraFim, nome: 'Horário de fim' },
        { campo: campoDataIni, nome: 'Data de início' }
    ];

    const camposInvalidos = camposObrigatorios.filter(item => {
        return !item.campo.value;
    });

    if (camposInvalidos.length === 0) {
        return true;
    }

    camposInvalidos.forEach(item => {
        item.campo.classList.add('campo-obrigatorio-erro');
    });

    const nomesCampos = camposInvalidos.map(item => item.nome).join(', ');

    mensagemValidacaoTurma.innerHTML = `Preencha os campos obrigatórios: ${nomesCampos}.`;
    mensagemValidacaoTurma.classList.add('ativo');

    camposInvalidos[0].campo.focus();

    return false;
}


/* ============================================================
   6. VALIDAÇÃO VISUAL DA MATRÍCULA
   ============================================================ */
function limparValidacaoMatricula() {
    if (!mensagemValidacaoMatricula) {
        return;
    }

    mensagemValidacaoMatricula.classList.remove('ativo');
    mensagemValidacaoMatricula.innerHTML = '';

    campoValorMensalidade.classList.remove('campo-obrigatorio-erro');
    campoDiaVencimento.classList.remove('campo-obrigatorio-erro');
}

function validarMatricula() {
    limparValidacaoMatricula();

    const erros = [];

    if (!alunoSelecionadoId) {
        erros.push('Aluno');
    }

    if (!campoValorMensalidade.value || Number(campoValorMensalidade.value) <= 0) {
        erros.push('Valor da mensalidade');
        campoValorMensalidade.classList.add('campo-obrigatorio-erro');
    }

    if (
        !campoDiaVencimento.value ||
        Number(campoDiaVencimento.value) < 1 ||
        Number(campoDiaVencimento.value) > 31
    ) {
        erros.push('Dia de vencimento');
        campoDiaVencimento.classList.add('campo-obrigatorio-erro');
    }

    if (erros.length === 0) {
        return true;
    }

    mensagemValidacaoMatricula.innerHTML = `Preencha corretamente: ${erros.join(', ')}.`;
    mensagemValidacaoMatricula.classList.add('ativo');

    return false;
}


/* ============================================================
   7. MODO NOVO
   ============================================================ */
async function abrirModoNovo() {
    modalTitulo.textContent = 'Nova Turma';

    limparCampos();
    liberarCampos();

    campoIdioma.disabled = true;
    campoNivel.disabled = true;

    await popularSelects(null, null, null);

    btnSalvar.style.display = 'block';
    acoesEdicao.style.display = 'none';
    btnSalvarEdicao.style.display = 'none';

    abrirModal();
}


/* ============================================================
   8. MODO VISUALIZAR
   ============================================================ */
async function abrirModoVisualizar(dataset) {
    modalTitulo.textContent = dataset.nome_turma;

    campoId.value = dataset.id_turma;
    campoNomeTurma.value = dataset.nome_turma;
    campoDia.value = dataset.dia_semana;
    campoHoraIni.value = dataset.hora_inicio;
    campoHoraFim.value = dataset.hora_fim;
    campoDataIni.value = dataset.data_inicio;
    campoDataFim.value = dataset.data_fim;
    campoCapac.value = dataset.capacidade;
    campoObs.value = dataset.observacao || '';
    campoStatus.value = dataset.status;

    await popularSelects(dataset.id_professor, dataset.id_idioma, dataset.id_nivel);

    bloquearCampos();

    btnSalvar.style.display = 'none';
    acoesEdicao.style.display = 'flex';
    btnSalvarEdicao.style.display = 'none';
    btnEditar.style.display = 'inline-flex';

    abrirModal();
}


/* ============================================================
   9. EVENTOS ABRIR/FECHAR
   ============================================================ */
modalFechar.addEventListener('click', fecharModal);
overlay.addEventListener('click', fecharModal);

document.addEventListener('click', function (e) {
    const btnNovo = e.target.closest('.btn-nova-turma');

    if (btnNovo) {
        abrirModoNovo();
    }
});


/* ============================================================
   10. EDITAR
   ============================================================ */
btnEditar.addEventListener('click', function () {
    liberarCampos();

    modalTitulo.textContent = 'Editando turma';
    btnEditar.style.display = 'none';
    btnSalvarEdicao.style.display = 'block';
});


/* ============================================================
   11. SALVAR ALTERAÇÕES
   ============================================================ */
btnSalvarEdicao.addEventListener('click', async function () {
    if (!validarTurma()) {
        return;
    }

    const res = await chamarAcao(API.editar, {
        id_turma: campoId.value,
        nome_turma: campoNomeTurma.value.trim(),
        id_idioma: campoIdioma.value,
        id_nivel: campoNivel.value,
        id_professor: campoProfessor.value,
        status: campoStatus.value,
        dia_semana: campoDia.value.trim(),
        hora_inicio: campoHoraIni.value,
        hora_fim: campoHoraFim.value,
        data_inicio: campoDataIni.value,
        data_fim: campoDataFim.value,
        capacidade: campoCapac.value,
        observacao: campoObs.value.trim()
    });

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        mensagemValidacaoTurma.innerHTML = res.mensagem;
        mensagemValidacaoTurma.classList.add('ativo');
    }
});


/* ============================================================
   12. SALVAR NOVA TURMA
   ============================================================ */
btnSalvar.addEventListener('click', async function () {
    if (!validarTurma()) {
        return;
    }

    const res = await chamarAcao(API.salvar, {
        nome_turma: campoNomeTurma.value.trim(),
        id_idioma: campoIdioma.value,
        id_nivel: campoNivel.value,
        id_professor: campoProfessor.value,
        status: campoStatus.value,
        dia_semana: campoDia.value.trim(),
        hora_inicio: campoHoraIni.value,
        hora_fim: campoHoraFim.value,
        data_inicio: campoDataIni.value,
        data_fim: campoDataFim.value,
        capacidade: campoCapac.value,
        observacao: campoObs.value.trim()
    });

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        mensagemValidacaoTurma.innerHTML = res.mensagem;
        mensagemValidacaoTurma.classList.add('ativo');
    }
});


/* ============================================================
   13. EXCLUIR
   ============================================================ */
btnExcluir.addEventListener('click', function () {
    nomeExclusao.textContent = campoNomeTurma.value;
    painelExclusao.classList.add('ativo');
});

btnCancelarExclusao.addEventListener('click', () => {
    painelExclusao.classList.remove('ativo');
});

btnConfirmarExclusao.addEventListener('click', async function () {
    const res = await chamarAcao(API.excluir, {
        id_turma: campoId.value
    });

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        mensagemValidacaoTurma.innerHTML = res.mensagem;
        mensagemValidacaoTurma.classList.add('ativo');
    }
});


/* ============================================================
   14. INCLUIR ALUNO NA TURMA
   ============================================================ */
btnIncluirAluno.addEventListener('click', function () {
    alunoSelecionadoId = null;

    campoValorMensalidade.value = '';
    campoDiaVencimento.value = sugerirDiaVencimentoPelaTurma();

    limparValidacaoMatricula();

    carregarAlunos();
    painelAluno.classList.add('ativo');
});

btnCancelarAluno.addEventListener('click', () => {
    painelAluno.classList.remove('ativo');
});

btnConfirmarAluno.addEventListener('click', async function () {
    if (!validarMatricula()) {
        return;
    }

    const dadosMatricula = {
        id_turma: campoId.value,
        id_aluno: alunoSelecionadoId,
        valor_mensalidade: campoValorMensalidade.value,
        dia_vencimento: campoDiaVencimento.value
    };

    const res = await chamarAcao(API.matricular, dadosMatricula);

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        mensagemValidacaoMatricula.innerHTML = res.mensagem;
        mensagemValidacaoMatricula.classList.add('ativo');
    }
});


/* ============================================================
   15. CARREGAR ALUNOS
   ============================================================ */
async function carregarAlunos() {
    listaAlunos.innerHTML = '<p class="msg-lista">Carregando alunos...</p>';

    try {
        const dados = await getDados();

        listaAlunos.innerHTML = '';

        if (!dados.alunos || dados.alunos.length === 0) {
            listaAlunos.innerHTML = '<p class="msg-lista">Nenhum aluno encontrado.</p>';
            return;
        }

        dados.alunos.forEach(aluno => {
            const item = document.createElement('div');

            item.classList.add('item-selecao');

            item.innerHTML = `
                <div>
                    <div class="item-selecao-nome">${aluno.nome}</div>
                    <div class="item-selecao-info">ID ${aluno.id_aluno} · ${aluno.email ?? ''}</div>
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


/* ============================================================
   16. EVENTOS VINDOS DO MODAL DE DETALHES
   ============================================================ */
document.addEventListener('editarTurmaPeloDetalhe', async function (event) {
    const card = event.detail?.card;

    if (!card) {
        return;
    }

    await abrirModoVisualizar(card.dataset);

    liberarCampos();

    modalTitulo.textContent = 'Editando turma';
    btnEditar.style.display = 'none';
    btnSalvarEdicao.style.display = 'block';
});

document.addEventListener('incluirAlunoPeloDetalhe', async function (event) {
    const card = event.detail?.card;

    if (!card) {
        return;
    }

    await abrirModoVisualizar(card.dataset);

    alunoSelecionadoId = null;

    campoValorMensalidade.value = '';
    campoDiaVencimento.value = sugerirDiaVencimentoPelaTurma();

    limparValidacaoMatricula();

    carregarAlunos();
    painelAluno.classList.add('ativo');
});