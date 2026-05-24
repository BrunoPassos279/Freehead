<?php
// PARÂMETRO DO BANCO: Alimente essa variável vinda do seu arquivo de consulta SQL
$gestores_reais = $gestores_reais ?? [];
$total_registros = count($gestores_reais);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestores - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>

<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>

    <main class="conteudo">
        <div class="page-header">
            <h1>Gestores</h1>
            <p>Gerencie os administradores da sua escola</p>
        </div>

        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-cadastrar-gestor')">+ Novo Gestor</button>
            <button class="btn-acao">Filtrar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" placeholder="Buscar Gestor..." oninput="filtrarTabela(this.value, 'tabela-gestores')">
            </div>
        </div>

        <div class="card-tabela">
            <div class="tabela-header">
                <span>Lista de Gestores</span>
                <span class="tabela-registros"><?= $total_registros ?> registros do banco</span>
            </div>
            <table id="tabela-gestores">
                <thead>
                    <tr>
                        <th>Gestor</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($gestores_reais)):
                        foreach ($gestores_reais as $g):
                            $statusClass = $g['status'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                            $cor_avatar = $g['cor'] ?? '#3b82f6';
                    ?>
                    <tr>
                        <td>
                            <div class="td-nome">
                                <div class="avatar-iniciais" style="background-color:<?= $cor_avatar ?>"><?= htmlspecialchars($g['iniciais']) ?></div>
                                <?= htmlspecialchars($g['nome']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($g['cpf']) ?></td>
                        <td><?= htmlspecialchars($g['email']) ?></td>
                        <td><?= htmlspecialchars($g['cargo']) ?></td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($g['status']) ?></span></td>
                        <td>
                            <div class="td-acoes">
                                <!-- Passando o ID dinâmico nas funções dos modais -->
                                <button class="btn-tabela" onclick="abrirModal('modal-ver-gestor', <?= $g['id'] ?>)">Ver</button>
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-gestor', <?= $g['id'] ?>)">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                        echo "<tr><td colspan='6' style='text-align:center; color:gray;'>Nenhum gestor encontrado.</td></tr>";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- MODAL: Cadastrar Gestor -->
<div class="modal-overlay" id="modal-cadastrar-gestor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-cadastrar-gestor')">✕</button>
        
        <form action="../crud/inserir-gestor.php" method="POST">
            <h2>Cadastrar Gestor</h2>
            <h3>Informações</h3>
            <div class="modal-inputs">
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Nome completo</label>
                        <input type="text" name="nome" placeholder="Nome do gestor..." required>
                    </div>
                    <div class="input-grupo">
                        <label>CPF</label>
                        <input type="text" name="cpf" placeholder="000.000.000-00" required>
                    </div>
                </div>
                <div class="modal-row">
                    <div class="input-grupo">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@exemplo.com" required>
                    </div>
                    <div class="input-grupo">
                        <label>Cargo</label>
                        <input type="text" name="cargo" placeholder="Ex: Coordenador" required>
                    </div>
                </div>
                <div class="input-grupo">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="Senha de acesso..." required>
                </div>
            </div>
            <div class="modal-acoes">
                <button type="button" class="btn btn-branco" onclick="fecharModal('modal-cadastrar-gestor')">Cancelar</button>
                <button type="submit" class="btn btn-laranja">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
</body>
</html>