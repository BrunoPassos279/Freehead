<?php
//---------------- Incluindo autenticação ----------------//
// Esse arquivo inicia a sessão e permite validar o login
require_once __DIR__ . '/../includes/auth.inc.php';

//---------------- Incluindo repository de professores ----------------//
// Esse arquivo busca professores no banco real
require_once __DIR__ . '/../repositories/ProfessorRepository.php';

//---------------- Validando sessão ----------------//
// Se o usuário não estiver logado, volta para a tela de login
validarSessao();

//---------------- ID da escola logada ----------------//
// Usado para filtrar professores da escola atual
$idEscolaLogada = getEscolaLogadaId();

//---------------- Buscando idiomas permitidos da escola ----------------//
// Usado no modal de cadastro/edição de professor
$idiomasPermitidosProfessor = buscarIdiomasPermitidosProfessor($idEscolaLogada);

//---------------- Buscando professores no banco ----------------//
// Retorna apenas professores ativos da escola logada
$professores = buscarProfessoresPorEscola($idEscolaLogada);
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
    
    <!---------------- TELA ---------------->
    <div class="pagina">

        <?php include __DIR__ . '/../includes/sidebar.inc.php'; ?>

        <main class="conteudo">

            <!---------------- Introdução da página ---------------->
            <div class="introPage">
                <div class="textIntroPage">
                    <h1>Professores</h1>
                    <p><?php echo count($professores); ?> docentes ativos</p>
                </div>

                <?php $btnLabel = "+ Novo professor"; ?>
                <?php $btnClass = "btn-laranja btn-tamanho btn-novo-professor"; ?>
                <?php include __DIR__ . '/../includes/btn.inc.php'; ?>
            </div>
            <!---------------- Fim introdução da página ---------------->

            <!---------------- Cards dos professores ---------------->
            <div class="cardsProf">

                <?php if (!empty($professores)): ?>

                    <?php foreach ($professores as $professor): ?>

                        <?php
                        //---------------- IDs dos idiomas ----------------//
                        // Usado pelo JS para marcar os checkboxes no modal
                        $idiomasIds = $professor['idiomas_ids'] ?? '';

                        $idiomasNomes = [];

                        if (!empty($professor['idiomas_nomes'])) {
                            $idiomasNomes = explode(', ', $professor['idiomas_nomes']);
                        }

                        //---------------- Quantidade de turmas ----------------//
                        $qtdTurmas = $professor['qtd_turmas'] ?? 0;
                        ?>

                        <!--
                            data-idiomas="1,3" → IDs separados por vírgula.
                            O JS usa isso para marcar os checkboxes corretos no modal.
                        -->
                        <div class="cardProf"
                            data-id="<?php echo htmlspecialchars($professor['id_professor']); ?>"
                            data-nome="<?php echo htmlspecialchars($professor['nome'] ?? ''); ?>"
                            data-email="<?php echo htmlspecialchars($professor['email'] ?? ''); ?>"
                            data-telefone="<?php echo htmlspecialchars($professor['telefone'] ?? ''); ?>"
                            data-idiomas="<?php echo htmlspecialchars($professor['idiomas_ids'] ?? ''); ?>">

                            <div class="identificacao">
                                <img src="../assets/img/images/perfilProf.svg" alt="Foto de perfil">

                                <div class="infoProf">
                                    <h4><?php echo htmlspecialchars($professor['nome'] ?? ''); ?></h4>
                                    <p>ID: <?php echo htmlspecialchars($professor['id_professor']); ?></p>
                                </div>
                            </div>

                            <div class="idiomas">
                                <?php if (!empty($idiomasNomes)): ?>

                                    <?php foreach ($idiomasNomes as $nomeIdioma): ?>
                                        <p><?php echo htmlspecialchars($nomeIdioma); ?></p>
                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <p>Sem idioma</p>

                                <?php endif; ?>
                            </div>

                            <div class="contagemClasses">
                                <hr>
                                <p>TURMAS</p>
                                <h5><?php echo htmlspecialchars($qtdTurmas); ?></h5>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>Nenhum professor encontrado.</p>

                <?php endif; ?>

            </div>
            <!---------------- Fim cards dos professores ---------------->

        </main>
    </div>

    <!---------------- Modal de professor ---------------->
    <?php include __DIR__ . '/../includes/modalProf.inc.php'; ?>

    <!---------------- Scripts ---------------->
    <script src="../assets/js/modalProfessor.js?v=2"></script>
    <script src="../assets/js/sidebar.js"></script>

</body>
</html>