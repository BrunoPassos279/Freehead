/* ============================================================
   modalProfessor.js
   Controla o modal da página de professores.
   Caminho: assets/js/modalProfessor.js
   ============================================================ */


/* ============================================================
   CONFIGURAÇÃO CENTRAL — troque os caminhos ao migrar para BD
   ============================================================ */
const API = {
    salvar:      '../actions/salvarProfessor.act.php',
    editar:      '../actions/editarProfessor.act.php',
    excluir:     '../actions/excluirProfessor.act.php',
    associar:    '../actions/associarProfessorTurma.act.php',
    listarTurmas:'../actions/listarTurmas.act.php'
};


/* ============================================================
   1. REFERÊNCIAS
   ============================================================ */
const overlay        = document.getElementById('modalOverlay');
const modal          = document.getElementById('modalProf');
const modalTitulo    = document.getElementById('modalTitulo');
const modalFechar    = document.getElementById('modalFechar');

const campoId        = document.getElementById('campoId');
const campoNome      = document.getElementById('campoNome');

const btnSalvar      = document.getElementById('btnSalvar');
const btnSalvarEdicao= document.getElementById('btnSalvarEdicao');
const acoesEdicao    = document.getElementById('acoesEdicao');
const btnEditar      = document.getElementById('btnEditar');
const btnExcluir     = document.getElementById('btnExcluir');
const btnAdicionarTurma = document.getElementById('btnAdicionarTurma');

const painelExclusao      = document.getElementById('painelExclusao');
const nomeExclusao        = document.getElementById('nomeExclusao');
const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
const btnConfirmarExclusao= document.getElementById('btnConfirmarExclusao');

const painelTurma      = document.getElementById('painelTurma');
const listaTurmas      = document.getElementById('listaTurmas');
const btnCancelarTurma = document.getElementById('btnCancelarTurma');
const btnConfirmarTurma= document.getElementById('btnConfirmarTurma');

let turmaSelecionadaId = null;


/* ============================================================
   2. UTILITÁRIOS
   ============================================================ */

// Retorna todos os checkboxes de idioma
function getCheckboxes() {
    return document.querySelectorAll('#checkboxIdiomas input[type="checkbox"]');
}

function bloquearCampos() {
    campoNome.disabled = true;
    getCheckboxes().forEach(cb => cb.disabled = true);
}

function liberarCampos() {
    campoNome.disabled = false;
    getCheckboxes().forEach(cb => cb.disabled = false);
}

function limparCampos() {
    campoId.value = '';
    campoNome.value = '';
    getCheckboxes().forEach(cb => cb.checked = false);
}

function fecharPaineis() {
    painelExclusao.classList.remove('ativo');
    painelTurma.classList.remove('ativo');
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
    turmaSelecionadaId    = null;
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

// Marca os checkboxes dos idiomas de um professor
// idiomasStr: string com IDs separados por vírgula, ex: "1,3"
function marcarIdiomas(idiomasStr) {
    const ids = idiomasStr ? idiomasStr.split(',').map(s => s.trim()) : [];
    getCheckboxes().forEach(cb => {
        cb.checked = ids.includes(cb.value);
    });
}

// Retorna array com os IDs dos idiomas marcados
function getIdiomasMarcados() {
    return [...getCheckboxes()]
        .filter(cb => cb.checked)
        .map(cb => cb.value);
}


/* ============================================================
   3. MODO NOVO
   ============================================================ */
function abrirModoNovo() {
    modalTitulo.textContent = 'Novo Professor';
    limparCampos();
    liberarCampos();

    btnSalvar.style.display       = 'block';
    acoesEdicao.style.display     = 'none';
    btnSalvarEdicao.style.display = 'none';

    abrirModal();
}


/* ============================================================
   4. MODO VISUALIZAR
   dataset vem do data-* do .cardProf clicado
   ============================================================ */
function abrirModoVisualizar(dataset) {
    modalTitulo.textContent = dataset.nome;

    campoId.value   = dataset.id;
    campoNome.value = dataset.nome;

    // data-idiomas="1,3" → marca os checkboxes correspondentes
    marcarIdiomas(dataset.idiomas || '');

    bloquearCampos();

    btnSalvar.style.display       = 'none';
    acoesEdicao.style.display     = 'flex';
    btnSalvarEdicao.style.display = 'none';
    btnEditar.style.display       = 'inline-flex';

    abrirModal();
}


/* ============================================================
   5. EVENTOS DE ABRIR/FECHAR
   ============================================================ */
modalFechar.addEventListener('click', fecharModal);
overlay.addEventListener('click', fecharModal);

document.addEventListener('click', function (e) {
    const card = e.target.closest('.cardProf');
    if (card) { abrirModoVisualizar(card.dataset); return; }

    const btnNovo = e.target.closest('.btn-novo-professor');
    if (btnNovo) { abrirModoNovo(); }
});


/* ============================================================
   6. EDITAR
   ============================================================ */
btnEditar.addEventListener('click', function () {
    liberarCampos();
    modalTitulo.textContent       = 'Editando professor';
    btnEditar.style.display       = 'none';
    btnSalvarEdicao.style.display = 'block';
});


/* ============================================================
   7. SALVAR ALTERAÇÕES
   ============================================================ */
btnSalvarEdicao.addEventListener('click', async function () {
    if (!campoNome.value.trim()) { campoNome.focus(); return; }

    const res = await chamarAcao(API.editar, {
        id_professor: campoId.value,
        nome:         campoNome.value.trim(),
        idiomas:      getIdiomasMarcados()  // ex: ["1","3"]
    });

    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao salvar: ' + res.mensagem);
});


/* ============================================================
   8. SALVAR NOVO
   ============================================================ */
btnSalvar.addEventListener('click', async function () {
    if (!campoNome.value.trim()) { 
        campoNome.focus(); 
        return; 
    }

    const res = await chamarAcao(API.salvar, {
        nome: campoNome.value.trim(),
        idiomas: getIdiomasMarcados()
    });

    if (res.sucesso) { 
        fecharModal(); 
        location.reload(); 
    } else {
        alert('Erro ao salvar: ' + res.mensagem);
    }
});

/* ============================================================
   9. EXCLUIR
   ============================================================ */
btnExcluir.addEventListener('click', function () {
    nomeExclusao.textContent = campoNome.value;
    painelExclusao.classList.add('ativo');
});

btnCancelarExclusao.addEventListener('click', () => painelExclusao.classList.remove('ativo'));

btnConfirmarExclusao.addEventListener('click', async function () {
    const res = await chamarAcao(API.excluir, { id_professor: campoId.value });
    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao excluir: ' + res.mensagem);
});


/* ============================================================
   10. ADICIONAR EM TURMA
   ============================================================ */
btnAdicionarTurma.addEventListener('click', function () {
    turmaSelecionadaId = null;
    carregarTurmas();
    painelTurma.classList.add('ativo');
});

btnCancelarTurma.addEventListener('click', () => painelTurma.classList.remove('ativo'));

btnConfirmarTurma.addEventListener('click', async function () {
    if (!turmaSelecionadaId) { alert('Selecione uma turma.'); return; }

    const res = await chamarAcao(API.associar, {
        id_professor: campoId.value,
        id_turma:     turmaSelecionadaId
    });

    if (res.sucesso) { fecharModal(); location.reload(); }
    else alert('Erro ao associar: ' + res.mensagem);
});


/* ============================================================
   11. CARREGAR TURMAS
   ============================================================ */
function carregarTurmas() {
    listaTurmas.innerHTML = '<p class="msg-lista">Carregando...</p>';

    fetch(API.listarTurmas)
        .then(r => r.json())
        .then(res => {
            listaTurmas.innerHTML = '';

            if (!res.sucesso) {
                listaTurmas.innerHTML = '<p class="msg-lista erro">Erro ao carregar turmas.</p>';
                return;
            }

            const turmas = res.turmas;

            if (turmas.length === 0) {
                listaTurmas.innerHTML = '<p class="msg-lista">Nenhuma turma ativa encontrada.</p>';
                return;
            }

            turmas.forEach(turma => {
                const item = document.createElement('div');

                item.classList.add('item-selecao');
                item.dataset.id = turma.id_turma;

                item.innerHTML = `
                    <div>
                        <div class="item-selecao-nome">${turma.nome_turma}</div>
                        <div class="item-selecao-info">
                            ${turma.nome_idioma ?? ''} · 
                            ${turma.nome_nivel ?? ''} ·
                            ${turma.dia_semana ?? ''} · 
                            ${turma.hora_inicio ?? ''}–${turma.hora_fim ?? ''}
                        </div>
                    </div>
                    <div class="item-radio"></div>
                `;

                item.addEventListener('click', () => {
                    document.querySelectorAll('#listaTurmas .item-selecao')
                        .forEach(el => el.classList.remove('selecionado'));

                    item.classList.add('selecionado');
                    turmaSelecionadaId = turma.id_turma;
                });

                listaTurmas.appendChild(item);
            });
        })
        .catch(() => {
            listaTurmas.innerHTML = '<p class="msg-lista erro">Erro ao carregar turmas.</p>';
        });
}