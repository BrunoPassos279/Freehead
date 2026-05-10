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
                <span class="tabela-registros">/* registros do banco */</span>
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
                    <!-- TODO: Substituir por loop PHP com dados do banco -->
                    <?php
                    $gestores_mock = [
                        ['iniciais'=>'MA','nome'=>'Maria Andrade', 'cpf'=>'999.888.777-66','email'=>'maria@escola.com', 'cargo'=>'Diretora',    'status'=>'Ativo'],
                        ['iniciais'=>'CS','nome'=>'Carlos Souza',  'cpf'=>'888.777.666-55','email'=>'carlos@escola.com','cargo'=>'Coordenador','status'=>'Ativo'],
                    ];
                    $cores = ['#3b82f6','#f97316'];
                    foreach ($gestores_mock as $i => $g):
                        $cor = $cores[$i % count($cores)];
                        $statusClass = $g['status'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                    ?>
                    <tr>
                        <td>
                            <div class="td-nome">
                                <div class="avatar-iniciais" style="background-color:<?= $cor ?>"><?= $g['iniciais'] ?></div>
                                <?= $g['nome'] ?>
                            </div>
                        </td>
                        <td><?= $g['cpf'] ?></td>
                        <td><?= $g['email'] ?></td>
                        <td><?= $g['cargo'] ?></td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= $g['status'] ?></span></td>
                        <td>
                            <div class="td-acoes">
                                <button class="btn-tabela" onclick="abrirModal('modal-ver-gestor')">Ver</button>
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-gestor')">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<!-- MODAL: Cadastrar Gestor -->
<div class="modal-overlay" id="modal-cadastrar-gestor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-cadastrar-gestor')">✕</button>
        <h2>Cadastrar Gestor</h2>
        <h3>Informações</h3>
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Nome completo</label>
                    <input type="text" placeholder="Nome do gestor...">
                </div>
                <div class="input-grupo">
                    <label>CPF</label>
                    <input type="text" placeholder="000.000.000-00">
                </div>
            </div>
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Email</label>
                    <input type="email" placeholder="email@exemplo.com">
                </div>
                <div class="input-grupo">
                    <label>Cargo</label>
                    <input type="text" placeholder="Ex: Coordenador">
                </div>
            </div>
            <div class="input-grupo">
                <label>Senha</label>
                <input type="password" placeholder="Senha de acesso...">
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-cadastrar-gestor')">Cancelar</button>
            <button class="btn btn-laranja">Cadastrar</button>
        </div>
    </div>
</div>

<!-- MODAL: Ver Gestor -->
<div class="modal-overlay" id="modal-ver-gestor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-gestor')">✕</button>
        <h2>Detalhes do Gestor</h2>
        <p style="color:rgba(255,255,255,0.5);font-size:var(--texto-tamanho)">
            <!-- TODO: popular dinamicamente -->
            Os dados do gestor aparecerão aqui.
        </p>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-ver-gestor')">Fechar</button>
        </div>
    </div>
</div>

<!-- MODAL: Editar Gestor -->
<div class="modal-overlay" id="modal-editar-gestor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-editar-gestor')">✕</button>
        <h2>Editar Gestor</h2>
        <h3>Informações</h3>
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Nome</label>
                    <input type="text" placeholder="Nome do gestor...">
                </div>
                <div class="input-grupo">
                    <label>Cargo</label>
                    <input type="text" placeholder="Cargo...">
                </div>
            </div>
            <div class="input-grupo">
                <label>Status</label>
                <select>
                    <option>Ativo</option>
                    <option>Inativo</option>
                </select>
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-editar-gestor')">Cancelar</button>
            <button class="btn btn-laranja">Salvar</button>
        </div>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
</body>
</html>
