<?php
require_once '../includes/conexao.inc.php';

// ── Contagem total de alunos ──────────────────────────────────────────────────
$total_alunos = 0;
$res_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alunos");
if ($res_total) {
    $total_alunos = mysqli_fetch_assoc($res_total)['total'];
}

// ── Lista de alunos ───────────────────────────────────────────────────────────
$alunos = [];
$res_alunos = mysqli_query($conn, "
    SELECT a.id, a.nome, a.cpf, a.status,
           i.nome      AS idioma,
           i.bandeira  AS bandeira,
           t.nome      AS turma,
           a.mensalidade
    FROM alunos a
    LEFT JOIN idiomas  i ON a.idioma_id = i.id
    LEFT JOIN turmas   t ON a.turma_id  = t.id
    ORDER BY a.nome ASC
");
if ($res_alunos) {
    while ($row = mysqli_fetch_assoc($res_alunos)) {
        $alunos[] = $row;
    }
}

// ── Idiomas disponíveis (para o select do modal) ──────────────────────────────
$idiomas = [];
$res_idiomas = mysqli_query($conn, "SELECT id, nome FROM idiomas ORDER BY nome ASC");
if ($res_idiomas) {
    while ($row = mysqli_fetch_assoc($res_idiomas)) {
        $idiomas[] = $row;
    }
}

// ── Turmas disponíveis (para o select do modal) ───────────────────────────────
$turmas = [];
$res_turmas = mysqli_query($conn, "SELECT id, nome FROM turmas ORDER BY nome ASC");
if ($res_turmas) {
    while ($row = mysqli_fetch_assoc($res_turmas)) {
        $turmas[] = $row;
    }
}

// ── Cores para avatar ─────────────────────────────────────────────────────────
$cores = ['#3b82f6', '#ef4444', '#f97316', '#22c55e', '#6366f1'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>

<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>

    <main class="conteudo">

        <div class="page-header">
            <h1>Alunos</h1>
            <p>Gerencie os Alunos da sua escola</p>
        </div>

        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-cadastrar-aluno')">+ Novo Aluno</button>
            <button class="btn-acao">Filtrar</button>
            <button class="btn-acao">⬇ Exportar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" placeholder="Buscar Aluno..." oninput="filtrarTabela(this.value, 'tabela-alunos')">
            </div>
        </div>

        <div class="card-tabela">
            <div class="tabela-header">
                <span>Lista de Alunos</span>
                <span class="tabela-registros"><?= $total_alunos ?> registro<?= $total_alunos !== 1 ? 's' : '' ?></span>
            </div>
            <table id="tabela-alunos">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>CPF</th>
                        <th>Idioma(s)</th>
                        <th>Turma</th>
                        <th>Mensalidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alunos)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; opacity:0.5; padding: 30px 0;">
                                Nenhum aluno cadastrado ainda.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alunos as $i => $a):
                            $cor        = $cores[$i % count($cores)];
                            $iniciais   = mb_strtoupper(mb_substr($a['nome'], 0, 1));
                            $partes     = explode(' ', trim($a['nome']));
                            if (count($partes) > 1) {
                                $iniciais .= mb_strtoupper(mb_substr(end($partes), 0, 1));
                            }
                            $statusClass = $a['status'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                            $mensalidade = 'R$ ' . number_format((float)$a['mensalidade'], 2, ',', '.');
                        ?>
                        <tr>
                            <td>
                                <div class="td-nome">
                                    <div class="avatar-iniciais" style="background-color:<?= $cor ?>"><?= $iniciais ?></div>
                                    <?= htmlspecialchars($a['nome']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($a['cpf']) ?></td>
                            <td>
                                <?php if ($a['idioma']): ?>
                                    <span class="badge-idioma">
                                        <?php if ($a['bandeira']): ?>
                                            <img src="../assets/img/images/<?= htmlspecialchars($a['bandeira']) ?>" alt="">
                                        <?php endif; ?>
                                        <?= htmlspecialchars($a['idioma']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="opacity:0.4">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $a['turma'] ? htmlspecialchars($a['turma']) : '<span style="opacity:0.4">—</span>' ?></td>
                            <td><?= $mensalidade ?></td>
                            <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($a['status']) ?></span></td>
                            <td>
                                <div class="td-acoes">
                                    <button class="btn-tabela"
                                        onclick="abrirModalComDados('modal-ver-aluno', <?= json_encode($a) ?>)">Ver</button>
                                    <button class="btn-tabela"
                                        onclick="abrirModalComDados('modal-editar-aluno', <?= json_encode($a) ?>)">Editar</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<!-- ===== MODAL: Cadastrar Aluno ===== -->
<div class="modal-overlay" id="modal-cadastrar-aluno">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-cadastrar-aluno')">✕</button>
        <h2>Cadastrar Aluno</h2>
        <h3>Informações</h3>
        <form method="POST" action="../actions/cadastrar_aluno.php">
            <div class="modal-inputs">
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Nome completo</label>
                        <input type="text" name="nome" placeholder="Nome do aluno..." required>
                    </div>
                    <div class="input-grupo">
                        <label>CPF</label>
                        <input type="text" name="cpf" placeholder="000.000.000-00">
                    </div>
                </div>
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@exemplo.com">
                    </div>
                    <div class="input-grupo">
                        <label>Telefone</label>
                        <input type="text" name="telefone" placeholder="(00) 00000-0000">
                    </div>
                </div>
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Idioma</label>
                        <select name="idioma_id">
                            <option value="">Selecione...</option>
                            <?php foreach ($idiomas as $idioma): ?>
                                <option value="<?= $idioma['id'] ?>"><?= htmlspecialchars($idioma['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-grupo">
                        <label>Turma</label>
                        <select name="turma_id">
                            <option value="">Selecione...</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="input-grupo">
                    <label>Mensalidade (R$)</label>
                    <input type="text" name="mensalidade" placeholder="0,00">
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-cadastrar-aluno')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Ver Aluno ===== -->
<div class="modal-overlay" id="modal-ver-aluno">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-aluno')">✕</button>
        <h2>Detalhes do Aluno</h2>
        <div id="ver-aluno-conteudo" style="color:var(--branco); font-size:var(--texto-tamanho); display:flex; flex-direction:column; gap:10px; margin: 16px 0;">
            <!-- preenchido via JS -->
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-ver-aluno')">Fechar</button>
        </div>
    </div>
</div>

<!-- ===== MODAL: Editar Aluno ===== -->
<div class="modal-overlay" id="modal-editar-aluno">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-editar-aluno')">✕</button>
        <h2>Editar Aluno</h2>
        <h3>Informações</h3>
        <form method="POST" action="../actions/editar_aluno.php">
            <input type="hidden" name="id" id="edit-aluno-id">
            <div class="modal-inputs">
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Nome completo</label>
                        <input type="text" name="nome" id="edit-aluno-nome" placeholder="Nome do aluno...">
                    </div>
                    <div class="input-grupo">
                        <label>Status</label>
                        <select name="status" id="edit-aluno-status">
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                </div>
                <div class="input-grupo">
                    <label>Idioma</label>
                    <select name="idioma_id" id="edit-aluno-idioma">
                        <option value="">Selecione...</option>
                        <?php foreach ($idiomas as $idioma): ?>
                            <option value="<?= $idioma['id'] ?>"><?= htmlspecialchars($idioma['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-editar-aluno')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
<script>
// Abre modal e injeta dados do aluno selecionado
function abrirModalComDados(modalId, dados) {
    abrirModal(modalId);

    if (modalId === 'modal-ver-aluno') {
        const campos = [
            ['Nome',        dados.nome],
            ['CPF',         dados.cpf],
            ['Idioma',      dados.idioma  || '—'],
            ['Turma',       dados.turma   || '—'],
            ['Mensalidade', 'R$ ' + parseFloat(dados.mensalidade).toFixed(2).replace('.', ',')],
            ['Status',      dados.status],
        ];
        document.getElementById('ver-aluno-conteudo').innerHTML = campos
            .map(([label, val]) =>
                `<div><strong style="color:var(--laranja)">${label}:</strong> ${val}</div>`)
            .join('');
    }

    if (modalId === 'modal-editar-aluno') {
        document.getElementById('edit-aluno-id').value     = dados.id;
        document.getElementById('edit-aluno-nome').value   = dados.nome;
        document.getElementById('edit-aluno-status').value = dados.status;
        const sel = document.getElementById('edit-aluno-idioma');
        // Seleciona o idioma pelo nome (fallback simples)
        for (let opt of sel.options) {
            if (opt.text === dados.idioma) { opt.selected = true; break; }
        }
    }
}
</script>
</body>
</html>