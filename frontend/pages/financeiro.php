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
        <!-- Cards de resumo financeiro -->
        <div class="financeiro-cards">
            <div class="fin-card">
                <div class="fin-card-icon"></div>
                <div class="fin-card-info">
                    <h3>Lucro Bruto</h3>
                    <span class="fin-card-valor">R$ 0,0</span>
                </div>
            </div>
            <div class="fin-card">
                <div class="fin-card-icon"></div>
                <div class="fin-card-info">
                    <h3>Lucro Líquido</h3>
                    <span class="fin-card-valor">R$ 0,0</span>
                </div>
            </div>
            <div class="fin-card">
                <div class="fin-card-icon"></div>
                <div class="fin-card-info">
                    <h3>Despesas</h3>
                    <span class="fin-card-valor">R$ 0,0</span>
                </div>
            </div>
        </div>

        <!-- Barra de ações da tabela -->
        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-novo-pagamento')">+ Novo Pagamento</button>
            <button class="btn-acao">⬇ Exportar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" placeholder="Buscar pagamento..." oninput="filtrarTabela(this.value, 'tabela-pagamentos')">
            </div>
        </div>

        <!-- Tabela de pagamentos -->
        <div class="card-tabela">
            <div class="tabela-header">
                <span>Pagamentos</span>
                <span class="tabela-registros">/* total de registros virá do banco */</span>
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
                    <!-- TODO: Substituir por loop PHP com dados do banco -->
                    <?php
                    $pagamentos_mock = [
                        ['nome' => 'Bruno Passos',  'mensalidade' => 'R$ 120,80', 'vencimento' => '19/04/2026', 'idioma' => 'Inglês',   'bandeira' => 'bandeiraEUA.svg',         'status' => 'Pago',     'data_pag' => '19/04/2026'],
                        ['nome' => 'Ana Paula',     'mensalidade' => 'R$ 120,80', 'vencimento' => '19/04/2026', 'idioma' => 'Inglês',   'bandeira' => 'bandeiraEUA.svg',         'status' => 'Pago',     'data_pag' => '19/04/2026'],
                        ['nome' => 'João Pedro',    'mensalidade' => 'R$ 120,80', 'vencimento' => '19/04/2026', 'idioma' => 'Espanhol', 'bandeira' => 'bandeiraEspanha.svg',     'status' => 'Pendente', 'data_pag' => '-'],
                        ['nome' => 'Lucas Martins', 'mensalidade' => 'R$ 120,80', 'vencimento' => '19/04/2026', 'idioma' => 'Francês',  'bandeira' => 'bandeiraFran%C3%A7a.svg', 'status' => 'Pago',     'data_pag' => '19/04/2026'],
                    ];
                    foreach ($pagamentos_mock as $p):
                        $statusClass = strtolower($p['status']) === 'pago' ? 'badge-pago' : 'badge-pendente';
                    ?>
                    <tr>
                        <td><?= $p['nome'] ?></td>
                        <td><?= $p['mensalidade'] ?></td>
                        <td><?= $p['vencimento'] ?></td>
                        <td>
                            <span class="badge-idioma">
                                <img src="../assets/img/images/<?= $p['bandeira'] ?>" alt=""><?= $p['idioma'] ?>
                            </span>
                        </td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= $p['status'] ?></span></td>
                        <td><?= $p['data_pag'] ?></td>
                        <td>
                            <div class="td-acoes">
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-pagamento')">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<!-- ===================== MODAL: Novo Pagamento ===================== -->
<div class="modal-overlay" id="modal-novo-pagamento">
    <div class="modal modal-pagamento">
        <button class="modal-fechar" onclick="fecharModal('modal-novo-pagamento')">✕</button>
        <h2>Novo Pagamento</h2>
        <h3>Selecione o Aluno</h3>

        <!-- Busca de aluno dentro do modal -->
        <div class="modal-busca-aluno">
            <img src="../assets/img/icons/search.svg" alt="Buscar" width="16">
            <input type="text" id="busca-aluno-modal" placeholder="Buscar aluno..." oninput="filtrarListaAlunos(this.value)">
        </div>

        <!-- Lista de alunos -->
        <div class="modal-lista-alunos" id="lista-alunos-modal">
            <!-- TODO: popular via PHP com alunos do banco -->
            <?php
            $alunos_lista = [
                ['id'=>1, 'iniciais'=>'AP', 'nome'=>'Ana Paula',     'idioma'=>'Inglês',   'cor'=>'#3b82f6'],
                ['id'=>2, 'iniciais'=>'JP', 'nome'=>'João Pedro',    'idioma'=>'Espanhol', 'cor'=>'#ef4444'],
                ['id'=>3, 'iniciais'=>'LM', 'nome'=>'Lucas Martins', 'idioma'=>'Francês',  'cor'=>'#f97316'],
                ['id'=>4, 'iniciais'=>'BP', 'nome'=>'Bruno Passos',  'idioma'=>'Inglês',   'cor'=>'#22c55e'],
            ];
            foreach ($alunos_lista as $a): ?>
            <label class="aluno-item" data-nome="<?= strtolower($a['nome']) ?>">
                <input type="radio" name="aluno_id" value="<?= $a['id'] ?>">
                <div class="avatar-iniciais" style="background-color:<?= $a['cor'] ?>;width:32px;height:32px;font-size:11px"><?= $a['iniciais'] ?></div>
                <div class="aluno-item-info">
                    <span class="aluno-item-nome"><?= $a['nome'] ?></span>
                    <span class="aluno-item-idioma"><?= $a['idioma'] ?></span>
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
                    <input type="text" id="pag-valor" placeholder="0,00">
                </div>
                <div class="input-grupo">
                    <label for="pag-vencimento">Vencimento</label>
                    <input type="date" id="pag-vencimento">
                </div>
            </div>
            <div class="modal-row">
                <div class="input-grupo">
                    <label for="pag-idioma">Idioma</label>
                    <select id="pag-idioma">
                        <!-- TODO: popular com idiomas da escola -->
                        <option value="">Selecione...</option>
                        <option value="1">Inglês</option>
                        <option value="2">Espanhol</option>
                        <option value="3">Francês</option>
                        <option value="4">Alemão</option>
                    </select>
                </div>
                <div class="input-grupo">
                    <label for="pag-status">Status</label>
                    <select id="pag-status">
                        <option value="pendente">Pendente</option>
                        <option value="pago">Pago</option>
                    </select>
                </div>
            </div>
            <div class="input-grupo">
                <label for="pag-data-pag">Data de pagamento</label>
                <input type="date" id="pag-data-pag">
            </div>
        </div>

        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-novo-pagamento')">Cancelar</button>
            <button class="btn btn-laranja">Registrar Pagamento</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Editar Pagamento ===================== -->
<div class="modal-overlay" id="modal-editar-pagamento">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-editar-pagamento')">✕</button>
        <h2>Editar Pagamento</h2>
        <h3>Dados do Pagamento</h3>
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Mensalidade (R$)</label>
                    <input type="text" placeholder="0,00">
                </div>
                <div class="input-grupo">
                    <label>Vencimento</label>
                    <input type="date">
                </div>
            </div>
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Idioma</label>
                    <select>
                        <option>Inglês</option>
                        <option>Espanhol</option>
                        <option>Francês</option>
                    </select>
                </div>
                <div class="input-grupo">
                    <label>Status</label>
                    <select>
                        <option>Pendente</option>
                        <option>Pago</option>
                    </select>
                </div>
            </div>
            <div class="input-grupo">
                <label>Data de pagamento</label>
                <input type="date">
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-editar-pagamento')">Cancelar</button>
            <button class="btn btn-laranja">Salvar</button>
        </div>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script>
// Auto-abre o modal se navegar via dashboard com #novo-pagamento
if (window.location.hash === '#novo-pagamento') {
    document.addEventListener('DOMContentLoaded', function() {
        abrirModal('modal-novo-pagamento');
        history.replaceState(null, '', window.location.pathname);
    });
}
</script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
<script>
// Filtro de alunos dentro do modal de novo pagamento
function filtrarListaAlunos(termo) {
    const itens = document.querySelectorAll('#lista-alunos-modal .aluno-item');
    const termoLower = termo.toLowerCase();
    itens.forEach(function(item) {
        const nome = item.getAttribute('data-nome') || '';
        item.style.display = nome.includes(termoLower) ? '' : 'none';
    });
}
</script>
</body>
</html>
