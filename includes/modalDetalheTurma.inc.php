<?php
/* ============================================================
   modalDetalheTurma.inc.php
   Modal de detalhes da turma — visualização + lista de alunos.
   ============================================================ */
?>

<div class="modal-overlay" id="modalDetalheTurmaOverlay"></div>

<div class="modal modal-detalhe-turma" id="modalDetalheTurma">

    <div class="modal-header">
        <h2 class="modal-titulo" id="detalheNomeTurma">Detalhes da turma</h2>

        <button type="button" class="modal-fechar" id="btnFecharDetalheTurma" aria-label="Fechar">
            ✕
        </button>
    </div>

    <div class="modal-body">

        <div class="detalhe-turma-resumo">

            <div class="detalhe-turma-topo">
                <div>
                    <h3 id="detalheIdiomaNivel">Idioma / nível</h3>
                    <p id="detalheProfessor">Professor</p>
                </div>

                <span class="statusTurma" id="detalheStatusTurma">Status</span>
            </div>

            <div class="detalhe-turma-grid">
                <div>
                    <strong>Dias</strong>
                    <p id="detalheDias">—</p>
                </div>

                <div>
                    <strong>Horário</strong>
                    <p id="detalheHorario">—</p>
                </div>

                <div>
                    <strong>Início</strong>
                    <p id="detalheDataInicio">—</p>
                </div>

                <div>
                    <strong>Capacidade</strong>
                    <p id="detalheCapacidade">—</p>
                </div>
            </div>

            <div class="detalhe-observacao">
                <strong>Observação</strong>
                <p id="detalheObservacao">—</p>
            </div>

        </div>

        <div class="detalhe-alunos">
            <div class="detalhe-alunos-header">
                <h3>Alunos da turma</h3>
                <span id="detalheQtdAlunos">0 alunos</span>
            </div>

            <div class="lista-alunos-turma" id="listaAlunosTurma">
                <p>Carregando alunos...</p>
            </div>
        </div>

        <div class="mensagem-config" id="mensagemDetalheTurma"></div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn-modal btn-editar" id="btnEditarTurmaDetalhe">
            Editar turma
        </button>

        <button type="button" class="btn-modal btn-acao" id="btnIncluirAlunoDetalhe">
            Incluir aluno
        </button>
    </div>

</div>