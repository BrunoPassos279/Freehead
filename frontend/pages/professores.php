<?php
require_once '../includes/conexao.inc.php';

$total_professores = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM professores");
if ($res) $total_professores = mysqli_fetch_assoc($res)['total'];

$professores = [];
$res = mysqli_query($conn, "
    SELECT p.id, p.nome, p.cpf, p.status,
           i.nome     AS idioma,
           i.bandeira AS bandeira,
           COUNT(t.id) AS total_turmas
    FROM professores p
    LEFT JOIN idiomas i ON p.idioma_id = i.id
    LEFT JOIN turmas  t ON t.professor_id = p.id
    GROUP BY p.id
    ORDER BY p.nome ASC
");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $professores[] = $row;
}

$idiomas = [];
$res = mysqli_query($conn, "SELECT id, nome FROM idiomas ORDER BY nome ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $idiomas[] = $row;
}

$cores = ['#3b82f6','#ef4444','#f97316','#22c55e','#6366f1'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professores - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>
<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>
    <main class="conteudo">

        <div class="page-header">
            <h1>Professores</h1>
            <p>Gerencie os Professores da sua escola</p>
        </div>

        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-cadastrar-professor')">+ Novo Professor</button>
            <button class="btn-acao">Filtrar</button>
            <button class="btn-acao">⬇ Exportar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" placeholder="Buscar Professor..." oninput="filtrarTabela(this.value, 'tabela-professores')">
            </div>
        </div>

        <div class="card-tabela">
            <div class="tabela-header">
                <span>Lista de Professores</span>
                <span class="tabela-registros"><?= $total_professores ?> registro<?= $total_professores !== 1 ? 's' : '' ?></span>
            </div>
            <table id="tabela-professores">
                <thead>
                    <tr>
                        <th>Professor</th>
                        <th>CPF</th>
                        <th>Idioma</th>
                        <th>Turmas</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($professores)): ?>
                        <tr><td colspan="6" style="text-align:center;opacity:0.5;padding:30px 0">Nenhum professor cadastrado ainda.</td></tr>
                    <?php else: ?>
                        <?php foreach ($professores as $i => $p):
                            $cor = $cores[$i % count($cores)];
                            $partes = explode(' ', trim($p['nome']));
                            $iniciais = mb_strtoupper(mb_substr($p['nome'], 0, 1));
                            if (count($partes) > 1) $iniciais .= mb_strtoupper(mb_substr(end($partes), 0, 1));
                            $statusClass = $p['status'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                        ?>
                        <tr>
                            <td>
                                <div class="td-nome">
                                    <div class="avatar-iniciais" style="background-color:<?= $cor ?>"><?= $iniciais ?></div>
                                    <?= htmlspecialchars($p['nome']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($p['cpf']) ?></td>
                            <td>
                                <?php if ($p['idioma']): ?>
                                    <span class="badge-idioma">
                                        <?php if ($p['bandeira']): ?>
                                            <img src="../assets/img/images/<?= htmlspecialchars($p['bandeira']) ?>" alt="">
                                        <?php endif; ?>
                                        <?= htmlspecialchars($p['idioma']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="opacity:0.4">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= (int)$p['total_turmas'] ?></td>
                            <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                            <td>
                                <div class="td-acoes">
                                    <button class="btn-tabela" onclick="abrirModalComDados('modal-ver-professor', <?= json_encode($p) ?>)">Ver</button>
                                    <button class="btn-tabela" onclick="abrirModalComDados('modal-editar-professor', <?= json_encode($p) ?>)">Editar</button>
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

<!-- MODAL: Cadastrar Professor -->
<div class="modal-overlay" id="modal-cadastrar-professor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-cadastrar-professor')">✕</button>
        <h2>Cadastrar Professor</h2>
        <h3>Informações</h3>
        <form method="POST" action="../actions/cadastrar_professor.php">
            <div class="modal-inputs">
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Nome completo</label>
                        <input type="text" name="nome" placeholder="Digite o nome..." required>
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
                <div class="input-grupo">
                    <label>Idioma que leciona</label>
                    <select name="idioma_id">
                        <option value="">Selecione um idioma...</option>
                        <?php foreach ($idiomas as $idioma): ?>
                            <option value="<?= $idioma['id'] ?>"><?= htmlspecialchars($idioma['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-cadastrar-professor')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Ver Professor -->
<div class="modal-overlay" id="modal-ver-professor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-professor')">✕</button>
        <h2>Detalhes do Professor</h2>
        <div id="ver-professor-conteudo" style="color:var(--branco);font-size:var(--texto-tamanho);display:flex;flex-direction:column;gap:10px;margin:16px 0;"></div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-ver-professor')">Fechar</button>
        </div>
    </div>
</div>

<!-- MODAL: Editar Professor -->
<div class="modal-overlay" id="modal-editar-professor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-editar-professor')">✕</button>
        <h2>Editar Professor</h2>
        <h3>Informações</h3>
        <form method="POST" action="../actions/editar_professor.php">
            <input type="hidden" name="id" id="edit-prof-id">
            <div class="modal-inputs">
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Nome completo</label>
                        <input type="text" name="nome" id="edit-prof-nome" placeholder="Nome do professor...">
                    </div>
                    <div class="input-grupo">
                        <label>CPF</label>
                        <input type="text" name="cpf" id="edit-prof-cpf" placeholder="000.000.000-00">
                    </div>
                </div>
                <div class="input-grupo">
                    <label>Idioma</label>
                    <select name="idioma_id" id="edit-prof-idioma">
                        <option value="">Selecione...</option>
                        <?php foreach ($idiomas as $idioma): ?>
                            <option value="<?= $idioma['id'] ?>"><?= htmlspecialchars($idioma['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-grupo">
                    <label>Status</label>
                    <select name="status" id="edit-prof-status">
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-editar-professor')">Cancelar</button>
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

    if (modalId === 'modal-ver-professor') {
        const campos = [
            ['Nome',   dados.nome],
            ['CPF',    dados.cpf],
            ['Idioma', dados.idioma || '—'],
            ['Turmas', dados.total_turmas],
            ['Status', dados.status],
        ];
        document.getElementById('ver-professor-conteudo').innerHTML = campos
            .map(([l, v]) => `<div><strong style="color:var(--laranja)">${l}:</strong> ${v}</div>`)
            .join('');
    }

    if (modalId === 'modal-editar-professor') {
        document.getElementById('edit-prof-id').value     = dados.id;
        document.getElementById('edit-prof-nome').value   = dados.nome;
        document.getElementById('edit-prof-cpf').value    = dados.cpf;
        document.getElementById('edit-prof-status').value = dados.status;
        const sel = document.getElementById('edit-prof-idioma');
        for (let opt of sel.options) {
            if (opt.text === dados.idioma) { opt.selected = true; break; }
        }
    }
}
</script>
</body>
</html>