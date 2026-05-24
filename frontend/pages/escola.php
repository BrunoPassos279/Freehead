<?php
require_once '../includes/conexao.inc.php';

// Busca dados da escola (assumindo escola_id na sessão ou tabela com 1 registro)
$escola = [];
$escola_id = $_SESSION['escola_id'] ?? 1;
$res = mysqli_query($conn, "SELECT * FROM escolas WHERE id = $escola_id LIMIT 1");
if ($res) $escola = mysqli_fetch_assoc($res) ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escola - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/inner.css">
</head>
<body>
<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>
    <main class="conteudo">

        <div class="page-header">
            <h1>Escola</h1>
            <p>Gerencie as informações da sua instituição</p>
        </div>

        <form method="POST" action="../actions/salvar_escola.php">
            <input type="hidden" name="id" value="<?= $escola['id'] ?? '' ?>">

            <div class="configs-grid">

                <!-- Card esquerdo: dados principais -->
                <div class="configs-card">
                    <div class="input-grupo">
                        <label for="cfg-nome-escola">Nome da escola</label>
                        <input type="text" id="cfg-nome-escola" name="nome"
                               value="<?= htmlspecialchars($escola['nome'] ?? '') ?>"
                               placeholder="Nome da escola...">
                    </div>
                    <div class="input-grupo">
                        <label for="cfg-cnpj">CNPJ</label>
                        <input type="text" id="cfg-cnpj" name="cnpj"
                               value="<?= htmlspecialchars($escola['cnpj'] ?? '') ?>"
                               placeholder="00.000.000/0000-00">
                    </div>
                    <div class="input-grupo">
                        <label for="cfg-email">E-mail de contato</label>
                        <input type="email" id="cfg-email" name="email"
                               value="<?= htmlspecialchars($escola['email'] ?? '') ?>"
                               placeholder="contato@escola.com.br">
                    </div>
                    <div class="input-grupo">
                        <label for="cfg-tel">Telefone</label>
                        <input type="text" id="cfg-tel" name="telefone"
                               value="<?= htmlspecialchars($escola['telefone'] ?? '') ?>"
                               placeholder="(00) 00000-0000">
                    </div>
                </div>

                <!-- Card direito: endereço -->
                <div class="configs-card">
                    <div class="input-grupo">
                        <label for="cfg-cep">CEP</label>
                        <input type="text" id="cfg-cep" name="cep"
                               value="<?= htmlspecialchars($escola['cep'] ?? '') ?>"
                               placeholder="00000-000">
                    </div>
                    <div class="input-grupo">
                        <label for="cfg-rua">Rua / Número</label>
                        <input type="text" id="cfg-rua" name="rua"
                               value="<?= htmlspecialchars($escola['rua'] ?? '') ?>"
                               placeholder="Av. Exemplo, 1000">
                    </div>
                    <div class="input-grupo">
                        <label for="cfg-cidade">Cidade / Estado</label>
                        <input type="text" id="cfg-cidade" name="cidade"
                               value="<?= htmlspecialchars($escola['cidade'] ?? '') ?>"
                               placeholder="São Paulo / SP">
                    </div>
                    <div class="input-grupo">
                        <label for="cfg-pais">País</label>
                        <select id="cfg-pais" name="pais">
                            <?php
                            $paises = ['BR' => 'Brasil', 'US' => 'Estados Unidos', 'PT' => 'Portugal'];
                            foreach ($paises as $cod => $label):
                                $selected = ($escola['pais'] ?? 'BR') === $cod ? 'selected' : '';
                            ?>
                                <option value="<?= $cod ?>" <?= $selected ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="configs-acoes">
                <a href="configuracoes.php" class="btn btn-branco">Cancelar</a>
                <button type="submit" class="btn btn-laranja">Salvar alterações</button>
            </div>
        </form>

    </main>
</div>
<script src="../assets/js/sidebar.js"></script>
</body>
</html>