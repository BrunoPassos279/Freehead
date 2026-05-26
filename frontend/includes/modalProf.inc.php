<?php
/* ============================================================
   modalProf.inc.php
   HTML do modal de professores — cadastro e visualização/edição.

   Inclua no final do <body> de professores.php:
     <?php include '../includes/modalProf.inc.php'; ?>
   ============================================================ */
?>

<!-- Fundo escuro -->
<div class="modal-overlay" id="modalOverlay"></div>

<!-- Caixa do modal -->
<div class="modal" id="modalProf">

    <!-- Cabeçalho -->
    <div class="modal-header">
        <h2 class="modal-titulo" id="modalTitulo">Novo Professor</h2>
        <button class="modal-fechar" id="modalFechar" aria-label="Fechar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <!-- Formulário -->
    <div class="modal-body">
        <form id="formProf" class="form-modal" autocomplete="off">

            <input type="hidden" id="campoId" name="id_professor">

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoNome">Nome completo</label>
                    <input type="text" id="campoNome" name="nome" placeholder="Ex: Fernanda Lima" required>
                </div>
            </div>

            <!-- Checkboxes de idiomas -->
            <div class="form-grupo">
                <span class="label-campo">Idiomas que leciona</span>
                <div class="checkboxes-grupo" id="checkboxIdiomas">
                    <label><input type="checkbox" name="idiomas[]" value="1" id="cb1"> Inglês</label>
                    <label><input type="checkbox" name="idiomas[]" value="2" id="cb2"> Espanhol</label>
                    <label><input type="checkbox" name="idiomas[]" value="3" id="cb3"> Francês</label>
                    <label><input type="checkbox" name="idiomas[]" value="4" id="cb4"> Alemão</label>
                </div>
            </div>

        </form>
    </div>

    <!-- Rodapé -->
    <div class="modal-footer">

        <button class="btn-modal btn-salvar" id="btnSalvar">Salvar professor</button>

        <div class="acoes-edicao" id="acoesEdicao" style="display:none;">

            <button class="btn-modal btn-editar" id="btnEditar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </button>

            <button class="btn-modal btn-excluir" id="btnExcluir">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                Excluir
            </button>

            <button class="btn-modal btn-acao" id="btnAdicionarTurma">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Adicionar em turma
            </button>

        </div>

        <button class="btn-modal btn-salvar" id="btnSalvarEdicao" style="display:none;">Salvar alterações</button>

    </div>

    <!-- Painel: confirmação de exclusão -->
    <div class="painel-interno painel-exclusao" id="painelExclusao">
        <div class="exclusao-icone">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <p class="exclusao-titulo">Tem certeza que deseja excluir<br><strong id="nomeExclusao"></strong>?</p>
        <p class="exclusao-aviso">Essa ação não pode ser desfeita.</p>
        <div class="painel-btns">
            <button class="btn-modal btn-cancelar" id="btnCancelarExclusao">Cancelar</button>
            <button class="btn-modal btn-excluir" id="btnConfirmarExclusao">Sim, excluir</button>
        </div>
    </div>

    <!-- Painel: adicionar em turma -->
    <div class="painel-interno painel-selecao" id="painelTurma">
        <h3>Adicionar em turma</h3>
        <p>Selecione a turma para associar este professor:</p>
        <div class="lista-selecao" id="listaTurmas"></div>
        <div class="painel-btns">
            <button class="btn-modal btn-cancelar" id="btnCancelarTurma">Cancelar</button>
            <button class="btn-modal btn-salvar" id="btnConfirmarTurma">Confirmar</button>
        </div>
    </div>

</div>