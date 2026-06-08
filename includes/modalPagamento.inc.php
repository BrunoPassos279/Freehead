<?php
/*
 * modalPagamento.inc.php
 * Modal para registrar pagamento.
 *
 * Pode ser usado em:
 * - financeiro.php: escolhendo o aluno por busca
 * - pageAluno.php: aluno já vem preenchido automaticamente
 */
?>

<!---------------- Overlay do modal ---------------->
<div class="modal-overlay" id="modalPagamentoOverlay"></div>

<!---------------- Modal de pagamento ---------------->
<div class="modal" id="modalPagamento">

    <!---------------- Cabeçalho ---------------->
    <div class="modal-header">
        <h2 class="modal-titulo" id="modalPagamentoTitulo">Novo pagamento</h2>

        <button type="button" class="modal-fechar" id="modalPagamentoFechar" aria-label="Fechar modal">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!---------------- Corpo do modal ---------------->
    <div class="modal-body">

        <form id="formPagamento" class="form-modal" action="../actions/registrarPagamento.act.php" method="POST">

            <!---------------- Origem do formulário ---------------->
            <input type="hidden" name="origem" value="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?>">

            <?php if (isset($idAlunoPagamentoFixo) && !empty($idAlunoPagamentoFixo)): ?>

                <!---------------- Aluno fixo - usado na página do aluno ---------------->
                <input type="hidden" name="id_aluno" value="<?php echo htmlspecialchars($idAlunoPagamentoFixo); ?>">

                <div class="form-linha">
                    <div class="form-grupo">
                        <label>Aluno</label>

                        <input 
                            type="text" 
                            value="<?php echo htmlspecialchars($nomeAlunoPagamentoFixo ?? 'Aluno selecionado'); ?>" 
                            disabled
                        >
                    </div>
                </div>

            <?php else: ?>

                <!---------------- Buscar aluno - usado no financeiro ---------------->
                <div class="form-linha">
                    <div class="form-grupo campo-busca-pagamento">
                        <label for="campoBuscaAlunoPagamento">Aluno</label>

                        <input 
                            type="text" 
                            id="campoBuscaAlunoPagamento" 
                            placeholder="Digite o nome do aluno"
                            autocomplete="off"
                        >

                        <input type="hidden" id="campoIdAlunoPagamento" name="id_aluno">

                        <div class="sugestoes-pagamento" id="sugestoesPagamento">
                            <?php if (!empty($alunosPagamento)): ?>

                                <?php foreach ($alunosPagamento as $alunoPagamento): ?>
                                    <div 
                                        class="sugestao-pagamento"
                                        data-id="<?php echo htmlspecialchars($alunoPagamento['id_aluno']); ?>"
                                        data-nome="<?php echo htmlspecialchars($alunoPagamento['nome']); ?>"
                                    >
                                        <?php echo htmlspecialchars($alunoPagamento['nome']); ?>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="sugestao-pagamento-vazia">
                                    Nenhum aluno disponível
                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <!---------------- Tipo de pagamento ---------------->
            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoTipoPagamento">Tipo de pagamento</label>

                    <select id="campoTipoPagamento" name="tipo_pagamento" required>
                        <option value="mensalidade">Mensalidade</option>
                        <option value="material">Material didático</option>
                        <option value="matricula">Matrícula</option>
                        <option value="aula_extra">Aula extra</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
            </div>

            <!---------------- Mês de referência e valor ---------------->
            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoMesReferencia">Mês de referência</label>
                    <input type="month" id="campoMesReferencia" name="mes_referencia" required>
                </div>

                <div class="form-grupo">
                    <label for="campoValorPagamento">Valor</label>

                    <input 
                        type="text" 
                        id="campoValorPagamento" 
                        name="valor" 
                        inputmode="decimal"
                        placeholder="Se vazio, usa o valor da matrícula"
                    >
                </div>
            </div>

            <!---------------- Forma de pagamento ---------------->
            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoFormaPagamento">Forma de pagamento</label>

                    <select id="campoFormaPagamento" name="forma_pagamento" required>
                        <option value="">Selecione</option>
                        <option value="Pix">Pix</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão de débito">Cartão de débito</option>
                        <option value="Cartão de crédito">Cartão de crédito</option>
                        <option value="Boleto">Boleto</option>
                    </select>
                </div>
            </div>

            <!---------------- Observação ---------------->
            <div class="form-linha">
                <div class="form-grupo">
                    <label for="campoObservacaoPagamento">Observação</label>

                    <textarea 
                        id="campoObservacaoPagamento" 
                        name="observacao" 
                        rows="3" 
                        placeholder="Opcional"
                    ></textarea>
                </div>
            </div>

        </form>
    </div>

    <!---------------- Rodapé ---------------->
    <div class="modal-footer">
        <button type="submit" class="btn-modal btn-salvar" id="btnSalvarPagamento" form="formPagamento">
            Registrar pagamento
        </button>
    </div>

</div>