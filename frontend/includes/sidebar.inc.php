<?php
//---------------- Incluindo autenticação ----------------//
// Garante acesso aos dados da sessão da escola logada
require_once '../includes/auth.inc.php';

//---------------- Pegando gestor logado ----------------//
// O nome vem da sessão criada no login
$nomeDoGestor = getNomeGestorLogado();

//---------------- Pegando escola logada ----------------//
// Esse ID será usado na busca do aluno
$idEscolaLogada = getEscolaLogadaId();
?>


<aside class="sidebar" id="sidebar">

    <!-- Avatar -->
    <div class="sidebar-user">
        <img src="../assets/img/icons/user.svg" alt="imagem de avatar" class="sidebar-avatar">
        <span class="sidebar-label"><?php echo($nomeDoGestor); ?></span>
    </div>

   

    <!-- Busca -->
    <form class="sidebar-search" action="../actions/buscarAluno.act.php" method="GET" autocomplete="off">

        <img class="sidebar-search-icon" src="../assets/img/icons/search.svg" alt="Buscar" width="18" height="18">

        <input 
            class="sidebar-label" 
            type="text" 
            name="busca" 
            id="buscarAlunoInput"
            placeholder="Buscar aluno..."
            data-escola="<?php echo $idEscolaLogada; ?>"
        >

        <!---------------- Sugestões da busca ---------------->
        <div class="sugestoes-busca" id="sugestoesBusca"></div>

    </form>

    <!-- Navegação -->
    <nav class="sidebar-nav">

        <a href="dashboard.php" class="sidebar-item">
            <img src="../assets/img/icons/dashboardSide.svg" alt="Dashboard">
            <span class="sidebar-label">Dashboard</span>
        </a>

        <a href="alunos.php" class="sidebar-item">
            <img src="../assets/img/icons/alunosSide.svg" alt="Alunos">
            <span class="sidebar-label">Alunos</span>
        </a>

        <a href="professores.php" class="sidebar-item">
            <img src="../assets/img/icons/profSide.svg" alt="Professores">
            <span class="sidebar-label">Professores</span>
        </a>

        <a href="turmas.php" class="sidebar-item">
            <img src="../assets/img/icons/TurmasSide.svg" alt="Turmas">
            <span class="sidebar-label">Turmas</span>
        </a>

        <a href="financeiro.php" class="sidebar-item">
            <img src="../assets/img/icons/paymentSide.svg" alt="Financeiro">
            <span class="sidebar-label">Financeiro</span>
        </a>

    </nav>

   <!-- Logo rodapé -->
<div class="sidebar-footer">
    <img class="sidebar-logo-full sidebar-label" src="../assets/img/logos/Freehead Logo W&O.svg" alt="Freehead">
</div>
</aside>