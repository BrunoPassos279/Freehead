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

        <!-- Cabeçalho -->
        <div class="page-header">
            <h1>Professores</h1>
            <p>Gerencie os Professores da sua escola</p>
        </div>

        <!-- Barra de ações -->
        <div class="barra-acoes">
            <button class="btn-acao btn-primario" onclick="abrirModal('modal-cadastrar-professor')">+ Novo Professor</button>
            <button class="btn-acao">Filtrar</button>
            <button class="btn-acao">⬇ Exportar</button>
            <div class="barra-busca">
                <img src="../assets/img/icons/search.svg" alt="Buscar" width="16" height="16">
                <input type="text" id="busca-professor" placeholder="Buscar Professor..." oninput="filtrarTabela(this.value, 'tabela-professores')">
            </div>
        </div>

        <!-- Tabela -->
        <div class="card-tabela">
            <div class="tabela-header">
                <span>Lista de Professores</span>
                <span class="tabela-registros" id="count-professores">/* registros do banco */</span>
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
                    <!-- TODO: Substituir por loop PHP com dados do banco -->
                    <?php
                    $cores = ['#3b82f6','#ef4444','#f97316','#22c55e','#6366f1'];
                    $professores_mock = [
                        ['iniciais'=>'BP','nome'=>'Bruno Passos',    'cpf'=>'123.456.789-00','idioma'=>'Inglês', 'bandeira'=>'bandeiraEUA.svg',     'turmas'=>3,'status'=>'Ativo'],
                        ['iniciais'=>'CM','nome'=>'Carlos Mendes',   'cpf'=>'234.567.890-11','idioma'=>'Espanhol','bandeira'=>'bandeiraEspanha.svg','turmas'=>2,'status'=>'Ativo'],
                        ['iniciais'=>'PS','nome'=>'Priya Silva',     'cpf'=>'345.678.901-22','idioma'=>'Francês', 'bandeira'=>'bandeiraFran%C3%A7a.svg','turmas'=>1,'status'=>'Ativo'],
                        ['iniciais'=>'RG','nome'=>'Roberto Gomes',   'cpf'=>'456.789.012-33','idioma'=>'Inglês', 'bandeira'=>'bandeiraEUA.svg',     'turmas'=>0,'status'=>'Inativo'],
                        ['iniciais'=>'FC','nome'=>'Fernanda Costa',  'cpf'=>'567.890.123-44','idioma'=>'Alemão',  'bandeira'=>'bandeiraAlemanha.svg','turmas'=>2,'status'=>'Inativo'],
                    ];
                    foreach ($professores_mock as $i => $p):
                        $cor = $cores[$i % count($cores)];
                        $statusClass = $p['status'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo';
                    ?>
                    <tr>
                        <td>
                            <div class="td-nome">
                                <div class="avatar-iniciais" style="background-color:<?= $cor ?>"><?= $p['iniciais'] ?></div>
                                <?= $p['nome'] ?>
                            </div>
                        </td>
                        <td><?= $p['cpf'] ?></td>
                        <td>
                            <span class="badge-idioma">
                                <img src="../assets/img/images/<?= $p['bandeira'] ?>" alt=""><?= $p['idioma'] ?>
                            </span>
                        </td>
                        <td><?= $p['turmas'] ?></td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= $p['status'] ?></span></td>
                        <td>
                            <div class="td-acoes">
                                <button class="btn-tabela" onclick="abrirModal('modal-ver-professor')">Ver</button>
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-professor')">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label for="p-nome">Nome completo</label>
                    <input type="text" id="p-nome" placeholder="Digite o nome...">
                </div>
                <div class="input-grupo">
                    <label for="p-cpf">CPF</label>
                    <input type="text" id="p-cpf" placeholder="000.000.000-00">
                </div>
            </div>
            <div class="modal-row">
                <div class="input-grupo">
                    <label for="p-email">Email</label>
                    <input type="email" id="p-email" placeholder="email@exemplo.com">
                </div>
                <div class="input-grupo">
                    <label for="p-tel">Telefone</label>
                    <input type="text" id="p-tel" placeholder="(00) 00000-0000">
                </div>
            </div>
            <div class="input-grupo">
                <label for="p-idioma">Idioma que leciona</label>
                <select id="p-idioma">
                    <!-- TODO: popular com idiomas da escola vindos do banco -->
                    <option value="">Selecione um idioma...</option>
                    <option value="1">Inglês</option>
                    <option value="2">Espanhol</option>
                    <option value="3">Francês</option>
                    <option value="4">Alemão</option>
                    <option value="5">Japonês</option>
                    <option value="6">Árabe</option>
                </select>
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-cadastrar-professor')">Cancelar</button>
            <button class="btn btn-laranja">Cadastrar</button>
        </div>
    </div>
</div>

<!-- MODAL: Ver Professor -->
<div class="modal-overlay" id="modal-ver-professor">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-professor')">✕</button>
        <h2>Detalhes do Professor</h2>
        <p style="color:rgba(255,255,255,0.5);font-size:var(--texto-tamanho)">
            <!-- TODO: popular dinamicamente com dados do professor selecionado -->
            Os dados do professor selecionado aparecerão aqui.
        </p>
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
        <div class="modal-inputs">
            <div class="modal-row">
                <div class="input-grupo">
                    <label for="ep-nome">Nome completo</label>
                    <input type="text" id="ep-nome" placeholder="Nome do professor...">
                </div>
                <div class="input-grupo">
                    <label for="ep-cpf">CPF</label>
                    <input type="text" id="ep-cpf" placeholder="000.000.000-00">
                </div>
            </div>
            <div class="input-grupo">
                <label for="ep-idioma">Idioma</label>
                <select id="ep-idioma">
                    <option value="1">Inglês</option>
                    <option value="2">Espanhol</option>
                    <option value="3">Francês</option>
                </select>
            </div>
            <div class="input-grupo">
                <label for="ep-status">Status</label>
                <select id="ep-status">
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-editar-professor')">Cancelar</button>
            <button class="btn btn-laranja">Salvar</button>
        </div>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
</body>
</html>
