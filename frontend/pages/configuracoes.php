<?php
// Pega o ID da escola e do gestor logado da sessão que seu CRUD gerou
$id_escola_logada = $_SESSION['escola_id'] ?? '';
$id_gestor_logado = $_SESSION['gestor_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>

<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>

    <main class="conteudo">
        <div class="page-header">
            <h1>Configurações</h1>
            <p>Gerencie as preferências do sistema</p>
        </div>

        <div class="settings-list">
            
            <!-- PARAMETRO ADICIONADO: Enviando o ID da escola para carregar os dados certos -->
            <a href="escola.php?id=<?= $id_escola_logada ?>" class="settings-item">
                <div class="settings-item-icon">
                    <img src="../assets/img/icons/school.svg" alt="Escola" width="20" height="20">
                </div>
                <div class="settings-item-content">
                    <span class="settings-item-title">Dados da Escola</span>
                    <span class="settings-item-desc">Nome, CNPJ, contato e endereço</span>
                </div>
                <img class="settings-item-arrow" src="../assets/img/icons/chevron-right.svg" alt="Acessar" width="20" height="20">
            </a>

            <!-- PARAMETRO ADICIONADO: Enviando o ID do gestor para carregar o perfil certo -->
            <a href="perfil.php?id=<?= $id_gestor_logado ?>" class="settings-item">
                <div class="settings-item-icon">
                    <img src="../assets/img/icons/user.svg" alt="Perfil" width="20" height="20">
                </div>
                <div class="settings-item-content">
                    <span class="settings-item-title">Meu Perfil</span>
                    <span class="settings-item-desc">Informações pessoais e senha</span>
                </div>
                <img class="settings-item-arrow" src="../assets/img/icons/chevron-right.svg" alt="Acessar" width="20" height="20">
            </a>

            <!-- Outros itens permanecem iguais -->
            <a href="#" class="settings-item">
                <div class="settings-item-icon">
                    <img src="../assets/img/icons/notification.svg" alt="Notificações" width="20" height="20">
                </div>
                <div class="settings-item-content">
                    <span class="settings-item-title">Notificações</span>
                    <span class="settings-item-desc">Configure como você recebe alertas</span>
                </div>
                <img class="settings-item-arrow" src="../assets/img/icons/chevron-right.svg" alt="Acessar" width="20" height="20">
            </a>

            <a href="#" class="settings-item">
                <div class="settings-item-icon">
                    <img src="../assets/img/icons/security.svg" alt="Segurança" width="20" height="20">
                </div>
                <div class="settings-item-content">
                    <span class="settings-item-title">Segurança</span>
                    <span class="settings-item-desc">Autenticação e controle de acesso</span>
                </div>
                <img class="settings-item-arrow" src="../assets/img/icons/chevron-right.svg" alt="Acessar" width="20" height="20">
            </a>

            <a href="#" class="settings-item">
                <div class="settings-item-icon">
                    <img src="../assets/img/icons/palette.svg" alt="Aparência" width="20" height="20">
                </div>
                <div class="settings-item-content">
                    <span class="settings-item-title">Aparência</span>
                    <span class="settings-item-desc">Tema e visual do sistema</span>
                </div>
                <img class="settings-item-arrow" src="../assets/img/icons/chevron-right.svg" alt="Acessar" width="20" height="20">
            </a>
        </div>
    </main>
</div>

<script src="../assets/js/sidebar.js"></script>
</body>
</html>