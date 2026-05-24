<?php
require_once '../includes/conexao.inc.php';

$total_turmas = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM turmas");
if ($res) $total_turmas = mysqli_fetch_assoc($res)['total'];

$turmas = [];
$res = mysqli_query($conn, "
    SELECT t.id, t.nome, t.horario, t.status,
           i.nome        AS idioma,
           i.bandeira    AS bandeira,
           p.nome        AS professor,
           COUNT(a.id)   AS total_alunos
    FROM turmas t
    LEFT JOIN idiomas     i ON t.idioma_id     = i.id
    LEFT JOIN professores p ON t.professor_id  = p.id
    LEFT JOIN alunos      a ON a.turma_id      = t.id
    GROUP BY t.id
    ORDER BY t.nome ASC
");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $turmas[] = $row;
}

$idiomas = [];
$res = mysqli_query($conn, "SELECT id, nome FROM idiomas ORDER BY nome ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $idiomas[] = $row;
}

$professores = [];
$res = mysqli_query($conn, "SELECT id, nome FROM professores WHERE status = 'Ativo' ORDER BY nome ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $professores[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>
<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>
    <main class="conteudo">

        <div class="page-header">
            <h1>Turmas</h1>
            <p>Gerencie as Turmas da sua escola</p>
        </div>

        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-criar-turma')">+ Nova Turma</button>
            <button class="btn-acao">Filtrar</button>
            <button class="btn-acao">⬇ Exportar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" placeholder="Buscar Turma..." oninput="filtrarTabela(this.value, 'tabela-turmas')">
            </div>
        </div>

        <div class="card-tabela">
            <div class="tabela-header">
                <span>Lista de Turmas</span>
                <span class="tabela-registros"><?= $total_turmas ?> registro<?= $total_turmas !== 1 ? 's' : '' ?></span>
            </div>
            <table id="tabela-turmas">
                <thead>
                    <tr>
                        <th>Nome da Turma</th>
                        <th>Idioma</th>
                        <th>Professor</th>
                        <th>Alunos</th>
                        <th>Horário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($turmas)): ?>
                        <tr><td colspan="7" style="text-align:center;opacity:0.5;padding:30px 0">Nenhuma turma cadastrada ainda.</td></tr>
                    <?php else: ?>
                        <?php foreach ($turmas as $t):
                            $statusClass = $t['status'] === 'Ativa' ? 'badge-ativo' : 'badge-inativo';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($t['nome']) ?></td>
                            <td>
                                <?php if ($t['idioma']): ?>
                                    <span class="badge-idioma">
                                        <?php if ($t['bandeira']): ?>
                                            <img src="../assets/img/images/<?= htmlspecialchars($t['bandeira']) ?>" alt="">
                                        <?php endif; ?>
                                        <?= htmlspecialchars($t['idioma']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="opacity:0.4">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $t['professor'] ? htmlspecialchars($t['professor']) : '<span style="opacity:0.4">—</span>' ?></td>
                            <td><?= (int)$t['total_alunos'] ?></td>
                            <td><?= htmlspecialchars($t['horario'] ?? '—') ?></td>
                            <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($t['status']) ?></span></td>
                            <td>
                                <div class="td-acoes">
                                    <button class="btn-tabela" onclick="abrirModalComDados('modal-ver-turma', <?= json_encode($t) ?>)">Ver</button>
                                    <button class="btn-tabela" onclick="abrirModalComDados('modal-editar-turma', <?= json_encode($t) ?>)">Editar</button>
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

<!-- MODAL: Criar Turma -->
<div class="modal-overlay" id="modal-criar-turma">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-criar-turma')">✕</button>
        <h2>Criar Turma</h2>
        <h3>Informações</h3>
        <form method="POST" action="../actions/criar_turma.php">
            <div class="modal-inputs">
                <div class="input-grupo">
                    <label>Nome da turma</label>
                    <input type="text" name="nome" placeholder="Ex: Inglês A1" required>
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
                        <label>Professor</label>
                        <select name="professor_id">
                            <option value="">Selecione...</option>
                            <?php foreach ($professores as $prof): ?>
                                <option value="<?= $prof['id'] ?>"><?= htmlspecialchars($prof['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="input-grupo">
                    <label>Horário</label>
                    <input type="text" name="horario" placeholder="Ex: Seg/Qua 18h">
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-criar-turma')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Criar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Ver Turma -->
<div class="modal-overlay" id="modal-ver-turma">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-turma')">✕</button>
        <h2>Detalhes da Turma</h2>
        <div id="ver-turma-conteudo" style="color:var(--branco);font-size:var(--texto-tamanho);display:flex;flex-direction:column;gap:10px;margin:16px 0;"></div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-ver-turma')">Fechar</button>
        </div>
    </div>
</div>

<!-- MODAL: Editar Turma -->
<div class="modal-overlay" id="modal-editar-turma">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-editar-turma')">✕</button>
        <h2>Editar Turma</h2>
        <h3>Informações</h3>
        <form method="POST" action="../actions/editar_turma.php">
            <input type="hidden" name="id" id="edit-turma-id">
            <div class="modal-inputs">
                <div class="input-grupo">
                    <label>Nome da turma</label>
                    <input type="text" name="nome" id="edit-turma-nome" placeholder="Nome da turma...">
                </div>
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Idioma</label>
                        <select name="idioma_id" id="edit-turma-idioma">
                            <option value="">Selecione...</option>
                            <?php foreach ($idiomas as $idioma): ?>
                                <option value="<?= $idioma['id'] ?>"><?= htmlspecialchars($idioma['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-grupo">
                        <label>Status</label>
                        <select name="status" id="edit-turma-status">
                            <option value="Ativa">Ativa</option>
                            <option value="Inativa">Inativa</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-editar-turma')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
<script>
function abrirModalComDados(modalId, dados) {
    abrirModal(modalId);

    if (modalId === 'modal-ver-turma') {
        const campos = [
            ['Nome',      dados.nome],
            ['Idioma',    dados.idioma    || '—'],
            ['Professor', dados.professor || '—'],
            ['Alunos',    dados.total_alunos],
            ['Horário',   dados.horario   || '—'],
            ['Status',    dados.status],
        ];
        document.getElementById('ver-turma-conteudo').innerHTML = campos
            .map(([l, v]) => `<div><strong style="color:var(--laranja)">${l}:</strong> ${v}</div>`)
            .join('');
    }

    if (modalId === 'modal-editar-turma') {
        document.getElementById('edit-turma-id').value     = dados.id;
        document.getElementById('edit-turma-nome').value   = dados.nome;
        document.getElementById('edit-turma-status').value = dados.status;
        const sel = document.getElementById('edit-turma-idioma');
        for (let opt of sel.options) {
            if (opt.text === dados.idioma) { opt.selected = true; break; }
        }
    }
}
</script>
</body>
</html>