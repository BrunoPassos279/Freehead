<!-- includes/cadastroEscola.inc.php -->
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/cadastroEscola.php");
    exit();
}

$nomeEscola = trim($_POST["nomeEscola"] ?? "");
$nomeGestor = trim($_POST["nomeGestor"] ?? "");
$email      = trim($_POST["email"] ?? "");
$senha      = trim($_POST["senha"] ?? "");
$cnpj       = trim($_POST["cnpj"] ?? "");
$idiomas    = $_POST["idiomas"] ?? [];

// Verifica se todos os campos estão preenchidos
if (empty($nomeEscola) || empty($nomeGestor) || empty($email) || empty($senha) || empty($cnpj)) {
    header("Location: ../pages/cadastroEscola.php?erro=1");
    exit();
}

// Verifica se ao menos um idioma foi selecionado
if (empty($idiomas)) {
    header("Location: ../pages/cadastroEscola.php?erro=3");
    exit();
}

require_once 'conexao.inc.php';

// ============================================
// TODO: CRUD — inserir escola na tabela `escolas`
// Tabela: escolas
// Colunas: nome_escola, cnpj, email, senha
// Verificar se email ou cnpj já existem antes de inserir → erro=2
// Após inserir escola, pegar o id_escola gerado
//
// TODO: CRUD — inserir gestor na tabela `usuarios`
// Tabela: usuarios
// Colunas: id_escola, nome, email, senha, nivel_permissao = 'Master'
// Senha deve ser salva com password_hash($senha, PASSWORD_DEFAULT)
//
// TODO: CRUD — vincular idiomas na tabela `idiomas_escolas`
// Tabela: idiomas_escolas
// Colunas: id_escola, id_idioma
// Iterar sobre o array $idiomas e inserir um registro para cada
// ============================================

header("Location: ../pages/index.php");
exit();