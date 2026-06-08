<?php
//---------------- Iniciando sessão ----------------//
// Verifica se já existe uma sessão ativa antes de iniciar uma nova
// Isso evita erro caso outro arquivo já tenha chamado session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//---------------- Verificando se o usuário está logado ----------------//
// Retorna true se existir uma escola salva na sessão
function usuarioEstaLogado() {
    return isset($_SESSION['id_escola']);
}

//---------------- Pegando ID da escola logada ----------------//
// Essa função será usada nas páginas para filtrar os dados da escola atual
function getEscolaLogadaId() {
    return $_SESSION['id_escola'] ?? null;
}

//---------------- Pegando nome do gestor logado ----------------//
// Essa função será usada no dashboard e sidebar
function getNomeGestorLogado() {
    return $_SESSION['nome_gestor'] ?? 'Gestor';
}

//---------------- Pegando nome da escola logada ----------------//
// Essa função será útil depois em telas de perfil/configuração
function getNomeEscolaLogada() {
    return $_SESSION['nome_escola'] ?? 'Escola';
}

//---------------- Validando sessão ----------------//
// Se não existir escola logada, manda o usuário de volta para o login
function validarSessao() {
    if (!usuarioEstaLogado()) {
        header('Location: index.php?erro=login_obrigatorio');
        exit;
    }
}

//---------------- Redirecionando usuário já logado ----------------//
// Se o usuário já estiver logado e tentar acessar o login, manda para o dashboard
function redirecionarSeLogado() {
    if (usuarioEstaLogado()) {
        header('Location: dashboard.php');
        exit;
    }
}

//---------------- Encerrando sessão ----------------//
// Remove todos os dados da sessão e finaliza o login
function encerrarSessao() {
    session_unset();
    session_destroy();
}