<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once '../includes/auth.inc.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para filtrar os dados do JSON temporário
$idEscolaLogada = getEscolaLogadaId();

$json  = file_get_contents('dados.json');
$dados = json_decode($json, true);

$professores       = array_filter($dados['professores'], fn($p) => $p['id_escola'] == $idEscolaLogada);
$professor_idiomas = $dados['professor_idioma'];
$idiomas           = $dados['idiomas'];
$turmas            = $dados['turmas'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professores - Freehead</title>
    <link rel="stylesheet" href="../assets/css/root.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/pages/professor.css">
    <link rel="stylesheet" href="../assets/css/modal.css">
</head>
<body>
<div class="pagina">
    <?php include '../includes/sidebar.inc.php'; ?>
    <main class="conteudo">

        <div class="introPage">
            <div class="textIntroPage">
                <h1>Professores</h1>
                <p><?php echo count($professores); ?> docentes ativos</p>
            </div>
            <?php $btnLabel = "+ Novo professor"; $btnClass = "btn-laranja btn-tamanho btn-novo-professor"; ?>
            <?php include '../includes/btn.inc.php'; ?>
        </div>

        <div class="cardsProf">
            <?php foreach ($professores as $professor): ?>
            <?php
            // ---- IDs e nomes dos idiomas ----
            $idiomasIds   = [];
            $idiomasNomes = [];
            foreach ($professor_idiomas as $pi) {
                if ($pi['id_professor'] === $professor['id_professor']) {
                    foreach ($idiomas as $i) {
                        if ($i['id_idioma'] === $pi['id_idioma']) {
                            $idiomasIds[]   = $i['id_idioma'];
                            $idiomasNomes[] = $i['nome'];
                        }
                    }
                }
            }

            // ---- Quantidade de turmas ----
            $qtdTurmas = count(array_filter($turmas, fn($t) => $t['id_professor'] === $professor['id_professor']));
            ?>

            <!--
                data-idiomas="1,3" → IDs separados por vírgula.
                O JS usa isso para marcar os checkboxes corretos no modal.
            -->
            <div class="cardProf"
                 data-id="<?php echo $professor['id_professor']; ?>"
                 data-nome="<?php echo htmlspecialchars($professor['nome']); ?>"
                 data-idiomas="<?php echo implode(',', $idiomasIds); ?>">

                <div class="identificacao">
                    <img src="../assets/img/images/perfilProf.svg" alt="Foto de perfil">
                    <div class="infoProf">
                        <h4><?php echo htmlspecialchars($professor['nome']); ?></h4>
                        <p>ID: <?php echo $professor['id_professor']; ?></p>
                    </div>
                </div>
                <div class="idiomas">
                    <?php foreach ($idiomasNomes as $nome): ?>
                        <p><?php echo $nome; ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="contagemClasses">
                    <hr>
                    <p>TURMAS</p>
                    <h5><?php echo $qtdTurmas; ?></h5>
                </div>
            </div>

            <?php endforeach; ?>
        </div>

    </main>
</div>

<?php include '../includes/modalProf.inc.php'; ?>
<script src="../assets/js/modalProfessor.js"></script>
<script src="../assets/js/sidebar.js"></script>
</body>
</html>