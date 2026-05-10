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
                <span class="tabela-registros">/* registros do banco */</span>
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
                    <!-- TODO: Substituir por loop PHP com dados do banco -->
                    <?php
                    $cores = ['#3b82f6','#ef4444','#f97316','#22c55e','#6366f1'];
                    $alunos_mock = [
                        ['iniciais'=>'AP','nome'=>'Ana Paula',      'cpf'=>'111.222.333-44','idioma'=>'Inglês','bandeira'=>'bandeiraEUA.svg',      'turma'=>'Inglês A1','mensalidade'=>'R$ 120,80','status'=>'Ativo'],
                        ['iniciais'=>'JP','nome'=>'João Pedro',     'cpf'=>'222.333.444-55','idioma'=>'Espanhol','bandeira'=>'bandeiraEspanha.svg','turma'=>'Esp B2', 'mensalidade'=>'R$ 120,80','status'=>'Ativo'],
                        ['iniciais'=>'LM','nome'=>'Lucas Martins',  'cpf'=>'333.444.555-66','idioma'=>'Francês','bandeira'=>'bandeiraFran%C3%A7a.svg','turma'=>'Fr A2','mensalidade'=>'R$ 120,80','status'=>'Inativo'],
                    ];
                    foreach ($alunos_mock as $i => $a):
                        $cor = $cores[$i % count($cores)];
                        $statusClass = $a['status'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                    ?>
                    <tr>
                        <td>
                            <div class="td-nome">
                                <div class="avatar-iniciais" style="background-color:<?= $cor ?>"><?= $a['iniciais'] ?></div>
                                <?= $a['nome'] ?>
                            </div>
                        </td>
                        <td><?= $a['cpf'] ?></td>
                        <td>
                            <span class="badge-idioma">
                                <img src="../assets/img/images/<?= $a['bandeira'] ?>" alt=""><?= $a['idioma'] ?>
                            </span>
                        </td>
                        <td><?= $a['turma'] ?></td>
                        <td><?= $a['mensalidade'] ?></td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= $a['status'] ?></span></td>
                        <td>
                            <div class="td-acoes">
                                <button class="btn-tabela" onclick="abrirModal('modal-ver-aluno')">Ver</button>
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-aluno')">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<!-- MODAL: Cadastrar Aluno -->
<div class="modal-overlay" id="modal-cadastrar-aluno">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-cadastrar-aluno')">✕</button>
        <h2>Cadastrar Aluno</h2>
        <h3>Informações</h3>
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Nome completo</label>
                    <input type="text" placeholder="Nome do aluno...">
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
                    <label>Telefone</label>
                    <input type="text" placeholder="(00) 00000-0000">
                </div>
            </div>
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Idioma</label>
                    <select>
                        <!-- TODO: popular com idiomas da escola -->
                        <option value="">Selecione...</option>
                        <option>Inglês</option>
                        <option>Espanhol</option>
                        <option>Francês</option>
                        <option>Alemão</option>
                    </select>
                </div>
                <div class="input-grupo">
                    <label>Turma</label>
                    <select>
                        <!-- TODO: popular com turmas disponíveis do banco -->
                        <option value="">Selecione...</option>
                    </select>
                </div>
            </div>
            <div class="input-grupo">
                <label>Mensalidade (R$)</label>
                <input type="text" placeholder="0,00">
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-cadastrar-aluno')">Cancelar</button>
            <button class="btn btn-laranja">Cadastrar</button>
        </div>
    </div>
</div>

<!-- MODAL: Ver Aluno -->
<div class="modal-overlay" id="modal-ver-aluno">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-aluno')">✕</button>
        <h2>Detalhes do Aluno</h2>
        <p style="color:rgba(255,255,255,0.5);font-size:var(--texto-tamanho)">
            <!-- TODO: popular dinamicamente com dados do aluno selecionado -->
            Os dados do aluno selecionado aparecerão aqui.
        </p>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-ver-aluno')">Fechar</button>
        </div>
    </div>
</div>

<!-- MODAL: Editar Aluno -->
<div class="modal-overlay" id="modal-editar-aluno">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-editar-aluno')">✕</button>
        <h2>Editar Aluno</h2>
        <h3>Informações</h3>
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Nome completo</label>
                    <input type="text" placeholder="Nome do aluno...">
                </div>
                <div class="input-grupo">
                    <label>Status</label>
                    <select>
                        <option>Ativo</option>
                        <option>Inativo</option>
                    </select>
                </div>
            </div>
            <div class="input-grupo">
                <label>Idioma</label>
                <select>
                    <option>Inglês</option>
                    <option>Espanhol</option>
                    <option>Francês</option>
                </select>
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-editar-aluno')">Cancelar</button>
            <button class="btn btn-laranja">Salvar</button>
        </div>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
</body>
</html>
