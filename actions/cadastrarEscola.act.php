<?php
//---------------- Incluindo repository de escola ----------------//
require_once __DIR__ . '/../repositories/EscolaRepository.php';

//---------------- Página de cadastro ----------------//
$paginaCadastro = '../pages/cadastroEscola.php';

//---------------- Página de login ----------------//
$paginaLogin = '../pages/index.php';

//---------------- Validando método ----------------//
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $paginaCadastro);
    exit;
}

//---------------- Pegando dados do formulário ----------------//
$nomeEscola      = trim($_POST['nomeEscola'] ?? '');
$nomeGestor      = trim($_POST['nomeGestor'] ?? '');
$email           = trim($_POST['email'] ?? '');
$senha           = $_POST['senha'] ?? '';
$confirmarSenha  = $_POST['confirmarSenha'] ?? '';
$cnpj            = trim($_POST['cnpj'] ?? '');
$idiomas         = $_POST['idiomas'] ?? [];

//---------------- Limpando CNPJ ----------------//
$cnpj = preg_replace('/\D/', '', $cnpj);

//---------------- Validando campos obrigatórios ----------------//
if (
    empty($nomeEscola) ||
    empty($nomeGestor) ||
    empty($email) ||
    empty($senha) ||
    empty($confirmarSenha) ||
    empty($cnpj)
) {
    header('Location: ' . $paginaCadastro . '?erro=campos_obrigatorios');
    exit;
}

//---------------- Validando confirmação de senha ----------------//
if ($senha !== $confirmarSenha) {
    header('Location: ' . $paginaCadastro . '?erro=senhas_diferentes');
    exit;
}

//---------------- Validando idiomas ----------------//
if (empty($idiomas) || !is_array($idiomas)) {
    header('Location: ' . $paginaCadastro . '?erro=idioma_obrigatorio');
    exit;
}

//---------------- Validando email duplicado ----------------//
if (verificarEmailExistente($email)) {
    header('Location: ' . $paginaCadastro . '?erro=email_existente');
    exit;
}

//---------------- Validando CNPJ duplicado ----------------//
if (verificarCnpjExistente($cnpj)) {
    header('Location: ' . $paginaCadastro . '?erro=cnpj_existente');
    exit;
}

//---------------- Cadastrando escola ----------------//
$resultado = cadastrarEscola(
    $nomeEscola,
    $nomeGestor,
    $email,
    $senha,
    $cnpj,
    $idiomas
);

//---------------- Tratando erro ----------------//
if (!$resultado['sucesso']) {
    echo '<pre>';
    echo "Erro ao cadastrar escola:\n\n";
    echo $resultado['mensagem'] ?? 'Erro sem mensagem';
    echo '</pre>';
    exit;
}

//---------------- Redirecionando para login ----------------//
header('Location: ' . $paginaLogin . '?sucesso=escola_cadastrada');
exit;
