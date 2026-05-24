<?php
// PARÂMETROS DO BANCO: Substitua os dados abaixo pelas variáveis vindas da sua consulta do CRUD
$lucro_bruto   = $dados_financeiros['bruto'] ?? 0.00;
$lucro_liquido = $dados_financeiros['liquido'] ?? 0.00;
$despesas      = $dados_financeiros['despesas'] ?? 0.00;

// O seu CRUD deve preencher este array vindo da tabela 'pagamentos'
$pagamentos_reais = $pagamentos_reais ?? []; 
$total_registros = count($pagamentos_reais);

// O seu CRUD deve preencher este array vindo da tabela 'alunos' para listar no modal
$alunos_lista = $alunos_lista ?? []; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>

<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>

    <main class="conteudo">
        <div class="page-header">
            <h1>Financeiro</h1>
            <p>Gerencie os pagamentos e as finanças da sua escola</p>
        </div>

        <div class="financeiro-cards">
            <div class="fin-card">
                <div class="fin-card-icon"></div>
                <div class="fin-card-info">
                    <h3>Lucro Bruto</h3>
                    <span class="fin-card-valor">R$ <?= number_format($lucro_bruto, 2, ',', '.') ?></span>
                </div>
            </div>
            <div class="fin-card">
                <div class="fin-card-info">
                    <h3>Lucro Líquido</h3>
                    <span class="fin-card-valor">R$ <?= number_format($lucro_liquido, 2, ',', '.') ?></span>
                </div>
            </div>
            <div class="fin-card">
                <div class="fin-card-icon"></div>
                <div class="fin-card-info">
                    <h3>Despesas</h3>
                    <span class="fin-card-valor">R$ <?= number_format($despesas, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-novo-pagamento')">+ Novo Pagamento</button>
            <button class="btn-acao">⬇ Exportar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" placeholder="Buscar pagamento..." oninput="filtrarTabela(this.value, 'tabela-pagamentos')">
            </div>
        </div>

        <div class="card-tabela">
            <div class="tabela-header">
                <span>Pagamentos</span>
                <span class="tabela-registros"><?= $total_registros ?> registro(s) no banco</span>
            </div>
            <table id="tabela-pagamentos">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Mensalidade</th>
                        <th>Vencimento</th>
                        <th>Idioma(s)</th>
                        <th>Status</th>
                        <th>Data de pagamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(!empty($pagamentos_reais)):
                        foreach ($pagamentos_reais as $p):
                            $statusClass = strtolower($p['status']) === 'pago' ? 'badge-pago' : 'badge-pendente';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td>R$ <?= number_format($p['mensalidade'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($p['vencimento']) ?></td>
                        <td>
                            <span class="badge-idioma">
                                <img src="../assets/img/images/<?= htmlspecialchars($p['bandeira']) ?>" alt=""><?= htmlspecialchars($p['idioma']) ?>
                            </span>
                        </td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                        <td><?= htmlspecialchars($p['data_pag']) ?></td>
                        <td>
                            <div class="td-acoes">
                                <!-- PARAMETRO ENVIADO NO JAVASCRIPT: Passa o ID do elemento para o Modal saber quem editar -->
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-pagamento', <?= $p['id'] ?>)">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    else:
                        echo "<tr><td colspan='7' style='text-align:center; color:gray;'>Nenhum registro encontrado.</td></tr>";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- ===================== MODAL: Novo Pagamento ===================== -->
<div class="modal-overlay" id="modal-novo-pagamento">
    <div class="modal modal-pagamento">
        <button class="modal-fechar" onclick="fecharModal('modal-novo-pagamento')">✕</button>
        
        <form action="../crud/inserir-pagamento.php" method="POST">
            <h2>Novo Pagamento</h2>
            <h3>Selecione o Aluno</h3>

            <div class="modal-busca-aluno">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16">
                <input type="text" id="busca-aluno-modal" placeholder="Buscar aluno..." oninput="filtrarListaAlunos(this.value)">
            </div>

            <div class="modal-lista-alunos" id="lista-alunos-modal">
                <?php foreach ($alunos_lista as $a): ?>
                <label class="aluno-item" data-nome="<?= strtolower($a['nome']) ?>">
                    <!-- Passando o ID real do aluno do banco de dados -->
                    <input type="radio" name="aluno_id" value="<?= $a['id'] ?>" required>
                    <div class="avatar-iniciais" style="background-color:<?= $a['cor'] ?>;width:32px;height:32px;font-size:11px"><?= htmlspecialchars($a['iniciais']) ?></div>
                    <div class="aluno-item-info">
                        <span class="aluno-item-nome"><?= htmlspecialchars($a['nome']) ?></span>
                        <span class="aluno-item-idioma"><?= htmlspecialchars($a['idioma']) ?></span>
                    </div>
                    <span class="aluno-item-check">✓</span>
                </label>
                <?php endforeach; ?>
            </div>

            <h3>Dados do Pagamento</h3>
            <div class="modal-inputs">
                <div class="modal-row">
                    <div class="input-grupo">
                        <label for="pag-valor">Mensalidade (R$)</label>
                        <input type="text" name="valor" id="pag-valor" placeholder="0,00" required>
                    </div>
                    <div class="input-grupo">
                        <label for="pag-vencimento">Vencimento</label>
                        <input type="date" name="vencimento" id="pag-vencimento" required>
                    </div>
                </div>
                <div class="modal-row">
                    <div class="input-grupo">
                        <label for="pag-idioma">Idioma</label>
                        <select name="idioma_id" id="pag-idioma" required>
                            <option value="">Selecione...</option>
                            <option value="1">Inglês</option>
                            <option value="2">Espanhol</option>
                        </select>
                    </div>
                    <div class="input-grupo">
                        <label for="pag-status">Status</label>
                        <select name="status" id="pag-status">
                            <option value="Pendente">Pendente</option>
                            <option value="Pago">Pago</option>
                        </select>
                    </div>
                </div>
                <div class="input-grupo">
                    <label for="pag-data-pag">Data de pagamento</label>
                    <input type="date" name="data_pagamento" id="pag-data-pag">
                </div>
            </div>

            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-novo-pagamento')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Registrar Pagamento</button>
            </div>
        </form>
    </div>
</div>

<!-- Mantidos os scripts originais no fim do seu arquivo -->
<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
</body>
</html>