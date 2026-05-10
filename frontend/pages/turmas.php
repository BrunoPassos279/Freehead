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
                <span class="tabela-registros">/* registros do banco */</span>
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
                    <!-- TODO: Substituir por loop PHP com dados do banco -->
                    <?php
                    $turmas_mock = [
                        ['nome'=>'Inglês A1',   'idioma'=>'Inglês',   'bandeira'=>'bandeiraEUA.svg',         'professor'=>'Bruno Passos',   'alunos'=>12,'horario'=>'Seg/Qua 18h','status'=>'Ativa'],
                        ['nome'=>'Inglês B2',   'idioma'=>'Inglês',   'bandeira'=>'bandeiraEUA.svg',         'professor'=>'Bruno Passos',   'alunos'=>8, 'horario'=>'Ter/Qui 19h','status'=>'Ativa'],
                        ['nome'=>'Espanhol B2', 'idioma'=>'Espanhol', 'bandeira'=>'bandeiraEspanha.svg',     'professor'=>'Carlos Mendes',  'alunos'=>10,'horario'=>'Sex 10h',    'status'=>'Ativa'],
                        ['nome'=>'Francês A2',  'idioma'=>'Francês',  'bandeira'=>'bandeiraFran%C3%A7a.svg', 'professor'=>'Priya Silva',    'alunos'=>6, 'horario'=>'Sáb 09h',   'status'=>'Inativa'],
                    ];
                    foreach ($turmas_mock as $t):
                        $statusClass = $t['status'] === 'Ativa' ? 'badge-ativo' : 'badge-inativo';
                    ?>
                    <tr>
                        <td><?= $t['nome'] ?></td>
                        <td>
                            <span class="badge-idioma">
                                <img src="../assets/img/images/<?= $t['bandeira'] ?>" alt=""><?= $t['idioma'] ?>
                            </span>
                        </td>
                        <td><?= $t['professor'] ?></td>
                        <td><?= $t['alunos'] ?></td>
                        <td><?= $t['horario'] ?></td>
                        <td><span class="badge-status <?= $statusClass ?>"><?= $t['status'] ?></span></td>
                        <td>
                            <div class="td-acoes">
                                <button class="btn-tabela" onclick="abrirModal('modal-ver-turma')">Ver</button>
                                <button class="btn-tabela" onclick="abrirModal('modal-editar-turma')">Editar</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
        <div class="modal-inputs">
            <div class="input-grupo">
                <label>Nome da turma</label>
                <input type="text" placeholder="Ex: Inglês A1">
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
                    <label>Professor</label>
                    <select>
                        <!-- TODO: popular com professores do banco -->
                        <option value="">Selecione...</option>
                    </select>
                </div>
            </div>
            <div class="input-grupo">
                <label>Horário</label>
                <input type="text" placeholder="Ex: Seg/Qua 18h">
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-criar-turma')">Cancelar</button>
            <button class="btn btn-laranja">Criar</button>
        </div>
    </div>
</div>

<!-- MODAL: Ver Turma -->
<div class="modal-overlay" id="modal-ver-turma">
    <div class="modal">
        <button class="modal-fechar" onclick="fecharModal('modal-ver-turma')">✕</button>
        <h2>Detalhes da Turma</h2>
        <p style="color:rgba(255,255,255,0.5);font-size:var(--texto-tamanho)">
            <!-- TODO: popular com dados da turma selecionada -->
            Os dados da turma aparecerão aqui.
        </p>
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
        <div class="modal-inputs">
            <div class="input-grupo">
                <label>Nome da turma</label>
                <input type="text" placeholder="Nome da turma...">
            </div>
            <div class="modal-row">
                <div class="input-grupo">
                    <label>Idioma</label>
                    <select>
                        <option>Inglês</option>
                        <option>Espanhol</option>
                    </select>
                </div>
                <div class="input-grupo">
                    <label>Status</label>
                    <select>
                        <option>Ativa</option>
                        <option>Inativa</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-acoes">
            <button class="btn btn-branco" onclick="fecharModal('modal-editar-turma')">Cancelar</button>
            <button class="btn btn-laranja">Salvar</button>
        </div>
    </div>
</div>

<script src="../assets/js/sidebar.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/tabela.js"></script>
</body>
</html>
