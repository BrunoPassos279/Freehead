<?php
/* ============================================================
   modalTurma.inc.php
   HTML do modal de turmas — cadastro e visualização/edição.

   Inclua no final do <body> de turmas.php:
     <?php include '../includes/modalTurma.inc.php'; ?>
   ============================================================ */
?>

<!-- Fundo escuro -->
<div class="modal-overlay" id="modalOverlay"></div>

<!-- Caixa do modal -->
<div class="modal" id="modalTurma">

    <!-- Cabeçalho -->
    <div class="modal-header">
        <h2 class="modal-titulo" id="modalTitulo">Nova Turma</h2>
        <button class="modal-fechar" id="modalFechar" aria-label="Fechar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <!-- Formulário -->
    <div class="modal-body">
        <form id="formTurma" class="form-modal" autocomplete="off">

            <input type="hidden" id="campoId" name="id_turma">

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoNomeTurma">Nome da turma</label>
                    <input type="text" id="campoNomeTurma" name="nome_turma" placeholder="Ex: Inglês Básico - Manhã" required>
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoIdioma">Idioma</label>
                    <select id="campoIdioma" name="id_idioma">
                        <option value="">Selecione...</option>
                        <option value="1">Inglês</option>
                        <option value="2">Espanhol</option>
                        <option value="3">Francês</option>
                        <option value="4">Alemão</option>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="campoNivel">Nível</label>
                    <!-- Populado via JS conforme o idioma selecionado -->
                    <select id="campoNivel" name="id_nivel">
                        <option value="">Selecione um idioma primeiro</option>
                    </select>
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoProfessor">Professor</label>
                    <!-- Populado via JS com os professores do JSON -->
                    <select id="campoProfessor" name="id_professor">
                        <option value="">Selecione...</option>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="campoStatus">Status</label>
                    <select id="campoStatus" name="status">
                        <option value="ativa">Ativa</option>
                        <option value="encerrada">Encerrada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoDia">Dia(s) da semana</label>
                    <input type="text" id="campoDia" name="dia_semana" placeholder="Ex: Segunda e Quarta">
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoHoraInicio">Horário de início</label>
                    <input type="time" id="campoHoraInicio" name="hora_inicio">
                </div>
                <div class="form-grupo">
                    <label for="campoHoraFim">Horário de fim</label>
                    <input type="time" id="campoHoraFim" name="hora_fim">
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoDataInicio">Data de início</label>
                    <input type="date" id="campoDataInicio" name="data_inicio">
                </div>
                <div class="form-grupo">
                    <label for="campoDataFim">Data de fim</label>
                    <input type="date" id="campoDataFim" name="data_fim">
                </div>
            </div>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoCapacidade">Capacidade (nº de alunos)</label>
                    <input type="number" id="campoCapacidade" name="capacidade" placeholder="Ex: 8" min="1">
                </div>
                <div class="form-grupo">
                    <label for="campoObservacao">Observação</label>
                    <input type="text" id="campoObservacao" name="observacao" placeholder="Opcional">
                </div>
            </div>

        </form>
    </div>

    <!-- Rodapé -->
    <div class="modal-footer">

        <button class="btn-modal btn-salvar" id="btnSalvar">Salvar turma</button>

        <div class="acoes-edicao" id="acoesEdicao" style="display:none;">

            <button class="btn-modal btn-editar" id="btnEditar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </button>

            <button class="btn-modal btn-excluir" id="btnExcluir">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                Excluir turma
            </button>

            <button class="btn-modal btn-acao" id="btnIncluirAluno">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Incluir aluno
            </button>

        </div>

        <button class="btn-modal btn-salvar" id="btnSalvarEdicao" style="display:none;">Salvar alterações</button>

    </div>

    <!-- Painel: confirmação de exclusão -->
    <div class="painel-interno painel-exclusao" id="painelExclusao">
        <div class="exclusao-icone">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <p class="exclusao-titulo">Tem certeza que deseja excluir a turma<br><strong id="nomeExclusao"></strong>?</p>
        <p class="exclusao-aviso">Essa ação não pode ser desfeita.</p>
        <div class="painel-btns">
            <button class="btn-modal btn-cancelar" id="btnCancelarExclusao">Cancelar</button>
            <button class="btn-modal btn-excluir" id="btnConfirmarExclusao">Sim, excluir</button>
        </div>
    </div>

    <!-- Painel: incluir aluno na turma -->
    <div class="painel-interno painel-selecao" id="painelAluno">
        <h3>Incluir aluno na turma</h3>
        <p>Selecione o aluno que deseja matricular:</p>
        <div class="lista-selecao" id="listaAlunos"></div>
        <div class="painel-btns">
            <button class="btn-modal btn-cancelar" id="btnCancelarAluno">Cancelar</button>
            <button class="btn-modal btn-salvar" id="btnConfirmarAluno">Confirmar matrícula</button>
        </div>
    </div>

</div>