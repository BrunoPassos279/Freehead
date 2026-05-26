/* ============================================================
   modalAluno.js
   Controla o modal da página de alunos.
   Caminho: assets/js/modalAluno.js

   COMO FUNCIONA:
   - O PHP coloca todos os dados do aluno nos atributos data-*
     de cada <tr class="linha-aluno"> na tabela.
   - Ao clicar numa linha, o JS lê esses atributos e preenche
     o modal com os dados reais daquele aluno.
   - Ao clicar em "+ Novo aluno", o modal abre em branco.
   - O botão "Salvar alterações" chama o arquivo PHP de ação
     que atualiza o JSON (e futuramente o banco de dados).

   PARA MIGRAR PARA BANCO DE DADOS:
   - Mantenha os mesmos arquivos de ação PHP (salvar, editar,
     excluir, transferir). Só mude o interior dessas funções
     para usar mysqli/PDO em vez de file_get_contents.
   ============================================================ */


/* ============================================================
   CONFIGURAÇÃO CENTRAL
   Todos os caminhos para os arquivos PHP de ação ficam aqui.
   Quando migrar para banco de dados, só muda este bloco.
   ============================================================ */
const API = {
    salvar:      '../actions/salvarAluno.php',
    editar:      '../actions/editarAluno.php',
    excluir:     '../actions/excluirAluno.php',
    transferir:  '../actions/transferirAluno.php',
    listarTurmas:'../data/freehead_mock.json'   // futuramente: '../actions/listarTurmas.php'
};


/* ============================================================
   1. REFERÊNCIAS AOS ELEMENTOS DO DOM
   ============================================================ */
const overlay        = document.getElementById('modalOverlay');
const modal          = document.getElementById('modalAluno');
const modalTitulo    = document.getElementById('modalTitulo');
const modalFechar    = document.getElementById('modalFechar');

// Campos do formulário
const campoId        = document.getElementById('campoId');
const campoNome      = document.getElementById('campoNome');
const campoNasc      = document.getElementById('campoNascimento');
const campoCadastro  = document.getElementById('campoDataCadastro');
const campoEndereco  = document.getElementById('campoEndereco');
const campoPai       = document.getElementById('campoPai');
const campoMae       = document.getElementById('campoMae');
const campoTelAluno  = document.getElementById('campoTelAluno');
const campoTelResp   = document.getElementById('campoTelResp');
const campoEmail     = document.getElementById('campoEmail');

// Botões
const btnSalvar      = document.getElementById('btnSalvar');
const btnSalvarEdicao= document.getElementById('btnSalvarEdicao');
const acoesEdicao    = document.getElementById('acoesEdicao');
const btnEditar      = document.getElementById('btnEditar');
const btnExcluir     = document.getElementById('btnExcluir');
const btnMudarTurma  = document.getElementById('btnMudarTurma');

// Painel de exclusão
const painelExclusao      = document.getElementById('painelExclusao');
const nomeExclusao        = document.getElementById('nomeExclusao');
const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
const btnConfirmarExclusao= document.getElementById('btnConfirmarExclusao');

// Painel de turma
const painelTurma      = document.getElementById('painelTurma');
const listaTurmas      = document.getElementById('listaTurmas');
const btnCancelarTurma = document.getElementById('btnCancelarTurma');
const btnConfirmarTurma= document.getElementById('btnConfirmarTurma');

// Controle interno
let turmaSelecionadaId = null;


/* ============================================================
   2. FUNÇÕES UTILITÁRIAS
   ============================================================ */

// Bloqueia todos os campos (modo visualização)
function bloquearCampos() {
    document.getElementById('formAluno')
        .querySelectorAll('input')
        .forEach(el => el.disabled = true);
}

// Libera todos os campos (modo edição)
function liberarCampos() {
    document.getElementById('formAluno')
        .querySelectorAll('input')
        .forEach(el => { if (el.type !== 'hidden') el.disabled = false; });
}

// Limpa todos os campos
function limparCampos() {
    document.getElementById('formAluno')
        .querySelectorAll('input')
        .forEach(el => el.value = '');
}

// Fecha os painéis internos sem fechar o modal
function fecharPaineis() {
    painelExclusao.classList.remove('ativo');
    painelTurma.classList.remove('ativo');
}

// Abre o modal
function abrirModal() {
    overlay.classList.add('ativo');
    modal.classList.add('ativo');
    fecharPaineis();
}

// Fecha o modal e reseta tudo
function fecharModal() {
    overlay.classList.remove('ativo');
    modal.classList.remove('ativo');
    fecharPaineis();
    limparCampos();
    turmaSelecionadaId = null;
    // Reseta estado dos botões
    btnSalvar.style.display       = 'block';
    btnSalvarEdicao.style.display = 'none';
    acoesEdicao.style.display     = 'none';
    btnEditar.style.display       = 'inline-flex';
}

// Envia dados para um arquivo PHP via fetch (POST com JSON)
async function chamarAcao(url, dados) {
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });
        return await res.json();
    } catch (err) {
        console.error('Erro na requisição:', err);
        return { sucesso: false, mensagem: 'Erro de conexão.' };
    }
}


/* ============================================================
   3. MODO NOVO — abre o modal em branco para cadastro
   ============================================================ */
function abrirModoNovo() {
    modalTitulo.textContent = 'Novo Aluno';
    limparCampos();
    liberarCampos();

    // Preenche a data de cadastro com hoje
    campoCadastro.value = new Date().toISOString().split('T')[0];

    // Mostra botão salvar, esconde os demais
    btnSalvar.style.display       = 'block';
    acoesEdicao.style.display     = 'none';
    btnSalvarEdicao.style.display = 'none';

    abrirModal();
}


/* ============================================================
   4. MODO VISUALIZAR — abre com os dados do aluno clicado
   Recebe o dataset do <tr> (todos os data-* como objeto)
   ============================================================ */
function abrirModoVisualizar(dataset) {
    modalTitulo.textContent = dataset.nome;

    // Preenche cada campo com o dado vindo do PHP via data-*
    campoId.value       = dataset.id;
    campoNome.value     = dataset.nome;
    campoNasc.value     = dataset.nascimento;
    campoCadastro.value = dataset.data_cadastro;
    campoEndereco.value = dataset.endereco;
    campoPai.value      = dataset.pai;
    campoMae.value      = dataset.mae;
    campoTelAluno.value = dataset.telefone_aluno;
    campoTelResp.value  = dataset.telefone_responsavel;
    campoEmail.value    = dataset.email;

    // Campos bloqueados — só visualização
    bloquearCampos();

    // Esconde salvar, mostra botões de ação
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

// Clique em linha da tabela (aluno existente) ou botão novo
document.addEventListener('click', function (e) {
    const linha = e.target.closest('.linha-aluno');
    if (linha) { abrirModoVisualizar(linha.dataset); return; }

    const btnNovo = e.target.closest('.btn-novo-aluno');
    if (btnNovo) { abrirModoNovo(); }
});


/* ============================================================
   6. BOTÃO EDITAR — libera os campos para digitação
   ============================================================ */
btnEditar.addEventListener('click', function () {
    liberarCampos();
    modalTitulo.textContent       = 'Editando aluno';
    btnEditar.style.display       = 'none';
    btnSalvarEdicao.style.display = 'block';
});


/* ============================================================
   7. BOTÃO SALVAR ALTERAÇÕES — envia edição para o PHP
   ============================================================ */
btnSalvarEdicao.addEventListener('click', async function () {
    if (!campoNome.value.trim()) { campoNome.focus(); return; }

    const dados = {
        id_aluno:             campoId.value,
        nome:                 campoNome.value.trim(),
        nascimento:           campoNasc.value,
        data_cadastro:        campoCadastro.value,
        endereco:             campoEndereco.value.trim(),
        pai:                  campoPai.value.trim(),
        mae:                  campoMae.value.trim(),
        telefone_aluno:       campoTelAluno.value.trim(),
        telefone_responsavel: campoTelResp.value.trim(),
        email:                campoEmail.value.trim()
    };

    const res = await chamarAcao(API.editar, dados);

    if (res.sucesso) {
        fecharModal();
        location.reload(); // Atualiza a tabela com os novos dados
    } else {
        alert('Erro ao salvar: ' + res.mensagem);
    }
});


/* ============================================================
   8. BOTÃO SALVAR NOVO ALUNO
   ============================================================ */
btnSalvar.addEventListener('click', async function () {
    if (!campoNome.value.trim()) { campoNome.focus(); return; }

    const dados = {
        nome:                 campoNome.value.trim(),
        nascimento:           campoNasc.value,
        data_cadastro:        campoCadastro.value,
        endereco:             campoEndereco.value.trim(),
        pai:                  campoPai.value.trim(),
        mae:                  campoMae.value.trim(),
        telefone_aluno:       campoTelAluno.value.trim(),
        telefone_responsavel: campoTelResp.value.trim(),
        email:                campoEmail.value.trim()
    };

    const res = await chamarAcao(API.salvar, dados);

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        alert('Erro ao salvar: ' + res.mensagem);
    }
});


/* ============================================================
   9. BOTÃO EXCLUIR — abre painel de confirmação
   ============================================================ */
btnExcluir.addEventListener('click', function () {
    nomeExclusao.textContent = campoNome.value;
    painelExclusao.classList.add('ativo');
});

btnCancelarExclusao.addEventListener('click', function () {
    painelExclusao.classList.remove('ativo');
});

btnConfirmarExclusao.addEventListener('click', async function () {
    const res = await chamarAcao(API.excluir, { id_aluno: campoId.value });

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        alert('Erro ao excluir: ' + res.mensagem);
    }
});


/* ============================================================
   10. BOTÃO MUDAR DE TURMA — abre painel de seleção
   ============================================================ */
btnMudarTurma.addEventListener('click', function () {
    turmaSelecionadaId = null;
    carregarTurmas();
    painelTurma.classList.add('ativo');
});

btnCancelarTurma.addEventListener('click', function () {
    painelTurma.classList.remove('ativo');
});

btnConfirmarTurma.addEventListener('click', async function () {
    if (!turmaSelecionadaId) { alert('Selecione uma turma.'); return; }

    const res = await chamarAcao(API.transferir, {
        id_aluno: campoId.value,
        id_turma: turmaSelecionadaId
    });

    if (res.sucesso) {
        fecharModal();
        location.reload();
    } else {
        alert('Erro ao transferir: ' + res.mensagem);
    }
});


/* ============================================================
   11. CARREGAR TURMAS NO PAINEL DE SELEÇÃO
   Busca o JSON e monta a lista.
   Quando migrar para banco de dados: troque API.listarTurmas
   por um PHP que retorna as turmas em JSON.
   ============================================================ */
function carregarTurmas() {
    listaTurmas.innerHTML = '<p class="msg-lista">Carregando turmas...</p>';

    fetch(API.listarTurmas)
        .then(r => r.json())
        .then(dados => {
            const turmas  = dados.turmas.filter(t => t.status === 'ativa');
            const idiomas = dados.idiomas;
            const niveis  = dados.niveis;

            listaTurmas.innerHTML = '';

            if (turmas.length === 0) {
                listaTurmas.innerHTML = '<p class="msg-lista">Nenhuma turma ativa encontrada.</p>';
                return;
            }

            turmas.forEach(turma => {
                const idioma = idiomas.find(i => i.id_idioma === turma.id_idioma);
                const nivel  = niveis.find(n => n.id_nivel  === turma.id_nivel);

                const item = document.createElement('div');
                item.classList.add('item-selecao');
                item.dataset.id = turma.id_turma;
                item.innerHTML = `
                    <div>
                        <div class="item-selecao-nome">${turma.nome_turma}</div>
                        <div class="item-selecao-info">
                            ${idioma?.nome ?? ''} · ${nivel?.nome_nivel ?? ''} ·
                            ${turma.dia_semana} · ${turma.hora_inicio}–${turma.hora_fim}
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