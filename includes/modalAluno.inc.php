<?php
/* ============================================================
   modalAluno.inc.php
   HTML do modal de alunos — cadastro e visualização/edição.
   
   Inclua no final do <body> de alunos.php:
     <?php include '../includes/modalAluno.inc.php'; ?>
   ============================================================ */
?>

<!-- Fundo escuro -->
<div class="modal-overlay" id="modalOverlay"></div>

<!-- Caixa do modal -->
<div class="modal" id="modalAluno">

    <!-- Cabeçalho -->
    <div class="modal-header">
        <h2 class="modal-titulo" id="modalTitulo">Novo Aluno</h2>
        <button class="modal-fechar" id="modalFechar" aria-label="Fechar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <!-- Formulário -->
    <div class="modal-body">
        <form id="formAluno" class="form-modal" autocomplete="off">

            <!-- ID do aluno (oculto, usado na edição) -->
            <input type="hidden" id="campoId" name="id_aluno">

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoNome">Nome completo</label>
                    <input type="text" id="campoNome" name="nome" placeholder="Ex: Carlos Silva" required>
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoNascimento">Data de nascimento</label>
                    <input type="date" id="campoNascimento" name="nascimento">
                </div>
                
                <input type="hidden" id="campoDataCadastro" name="data_cadastro">
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoEndereco">Endereço</label>
                    <input type="text" id="campoEndereco" name="endereco" placeholder="Ex: Rua das Flores, 123">
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoPai">Nome do pai</label>
                    <input type="text" id="campoPai" name="pai" placeholder="Ex: Roberto Silva">
                </div>
                <div class="form-grupo">
                    <label for="campoMae">Nome da mãe</label>
                    <input type="text" id="campoMae" name="mae" placeholder="Ex: Cláudia Silva">
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoTelAluno">Telefone do aluno</label>
                    <input type="tel" id="campoTelAluno" name="telefone_aluno" placeholder="(11) 99999-9999">
                </div>
                <div class="form-grupo">
                    <label for="campoTelResp">Telefone do responsável</label>
                    <input type="tel" id="campoTelResp" name="telefone_responsavel" placeholder="(11) 99999-9999">
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoEmail">E-mail</label>
                    <input type="email" id="campoEmail" name="email" placeholder="Ex: carlos@email.com">
                </div>
            </div>

        </form>
    </div>

    <!-- Rodapé com botões -->
    <div class="modal-footer">

        <!-- Botão de salvar novo aluno (só aparece no modo "novo") -->
        <button class="btn-modal btn-salvar" id="btnSalvar">Salvar aluno</button>

        <!-- Botões do modo visualização (só aparecem ao clicar num aluno existente) -->
        <div class="acoes-edicao" id="acoesEdicao" style="display:none;">

            <button class="btn-modal btn-editar" id="btnEditar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </button>

            <button class="btn-modal btn-excluir" id="btnExcluir">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                Excluir
            </button>

            <button class="btn-modal btn-acao" id="btnMudarTurma">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                Mudar de turma
            </button>

        </div>

        <!-- Botão salvar edição (só aparece depois de clicar em Editar) -->
        <button class="btn-modal btn-salvar" id="btnSalvarEdicao" style="display:none;">Salvar alterações</button>

    </div>

    <!-- ---- Painel: confirmação de exclusão ---- -->
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

    <!-- ---- Painel: seleção de turma (mudar de turma) ---- -->
    <div class="painel-interno painel-selecao" id="painelTurma">
        <h3>Mudar de turma</h3>
        <p>Selecione a nova turma para o aluno:</p>
        <div class="lista-selecao" id="listaTurmas"></div>
        <div class="painel-btns">
            <button class="btn-modal btn-cancelar" id="btnCancelarTurma">Cancelar</button>
            <button class="btn-modal btn-salvar" id="btnConfirmarTurma">Confirmar transferência</button>
        </div>
    </div>

</div>