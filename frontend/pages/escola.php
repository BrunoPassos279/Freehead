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

        <!-- Grid de cards com os inputs -->
        <div class="configs-grid">

            <!-- Card esquerdo: dados principais -->
            <div class="configs-card">
                <div class="input-grupo">
                    <label for="cfg-nome-escola">Nome da escola</label>
                    <!-- TODO: popular com dados do banco -->
                    <input type="text" id="cfg-nome-escola" placeholder="Nome da escola...">
                </div>
                <div class="input-grupo">
                    <label for="cfg-cnpj">CNPJ</label>
                    <input type="text" id="cfg-cnpj" placeholder="00.000.000/0000-00">
                </div>
                <div class="input-grupo">
                    <label for="cfg-email">E-mail de contato</label>
                    <input type="email" id="cfg-email" placeholder="contato@escola.com.br">
                </div>
                <div class="input-grupo">
                    <label for="cfg-tel">Telefone</label>
                    <input type="text" id="cfg-tel" placeholder="(00) 00000-0000">
                </div>
            </div>

            <!-- Card direito: endereço -->
            <div class="configs-card">
                <div class="input-grupo">
                    <label for="cfg-cep">CEP</label>
                    <input type="text" id="cfg-cep" placeholder="00000-000">
                </div>
                <div class="input-grupo">
                    <label for="cfg-rua">Rua / Número</label>
                    <input type="text" id="cfg-rua" placeholder="Av. Exemplo, 1000">
                </div>
                <div class="input-grupo">
                    <label for="cfg-cidade">Cidade / Estado</label>
                    <input type="text" id="cfg-cidade" placeholder="São Paulo / SP">
                </div>
                <div class="input-grupo">
                    <label for="cfg-pais">País</label>
                    <select id="cfg-pais">
                        <!-- TODO: popular com lista de países -->
                        <option value="BR">Brasil</option>
                        <option value="US">Estados Unidos</option>
                        <option value="PT">Portugal</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="configs-acoes">
            <button class="btn btn-branco">Cancelar</button>
            <button class="btn btn-laranja">Salvar alterações</button>
        </div>

    </main>
</div>

<script src="../assets/js/sidebar.js"></script>
</body>
</html>