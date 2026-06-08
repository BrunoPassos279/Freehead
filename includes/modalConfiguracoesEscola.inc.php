<?php
/* ============================================================
   modalConfiguracoesEscola.inc.php
   Modal de configurações da escola.
   ============================================================ */
?>

<div class="modal-overlay" id="modalConfiguracoesOverlay"></div>

<div class="modal modal-configuracoes" id="modalConfiguracoesEscola">

    <div class="modal-header">
        <h2 class="modal-titulo">Configurações da escola</h2>

        <button type="button" class="modal-fechar" id="btnFecharConfiguracoesEscola" aria-label="Fechar">
            ✕
        </button>
    </div>

    <div class="modal-body">

        <div class="abas-configuracoes">
            <button type="button" class="aba-config ativa" data-aba="dadosEscola">Dados</button>
            <button type="button" class="aba-config" data-aba="idiomasEscola">Idiomas</button>
            <button type="button" class="aba-config" data-aba="niveisEscola">Níveis</button>
            <button type="button" class="aba-config" data-aba="segurancaEscola">Senha</button>
        </div>

        <!---------------- Aba dados da escola ---------------->
        <section class="conteudo-config ativo" id="dadosEscola">
            <form id="formDadosEscola" class="form-modal">

                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="configNomeEscola">Nome da escola</label>
                        <input type="text" id="configNomeEscola" name="nome_escola">
                    </div>
                </div>

                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="configGestor">Nome do gestor</label>
                        <input type="text" id="configGestor" name="gestor">
                    </div>
                </div>

                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="configEmail">E-mail</label>
                        <input type="email" id="configEmail" name="email">
                    </div>
                </div>

                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="configCnpj">CNPJ</label>
                        <input type="text" id="configCnpj" name="cnpj" disabled>
                    </div>
                </div>

                <button type="button" class="btn-modal btn-salvar" id="btnSalvarDadosEscola">
                    Salvar dados
                </button>
            </form>
        </section>

        <!---------------- Aba idiomas da escola ---------------->
        <section class="conteudo-config" id="idiomasEscola">
            <p class="texto-config">
                Marque os idiomas que sua escola trabalha.
            </p>

            <div class="lista-idiomas-config" id="listaIdiomasConfig"></div>

            <button type="button" class="btn-modal btn-salvar" id="btnSalvarIdiomasEscola">
                Salvar idiomas
            </button>
        </section>

        <!---------------- Aba níveis por idioma ---------------->
        <section class="conteudo-config" id="niveisEscola">
            <p class="texto-config">
                Cadastre os níveis disponíveis para cada idioma da escola.
            </p>

            <div class="form-linha">
                <div class="form-grupo">
                    <label for="configIdiomaNivel">Idioma</label>
                    <select id="configIdiomaNivel">
                        <option value="">Selecione um idioma</option>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="configNomeNivel">Novo nível</label>
                    <input type="text" id="configNomeNivel" placeholder="Ex: Básico 1">
                </div>
            </div><br>

            <button type="button" class="btn-modal btn-salvar" id="btnAdicionarNivelEscola">
                Adicionar nível
            </button>

            <div class="lista-niveis-config" id="listaNiveisConfig">
                <p class="texto-config">Selecione um idioma para ver os níveis.</p>
            </div>
        </section>

        <!---------------- Aba senha ---------------->
        <section class="conteudo-config" id="segurancaEscola">
            <form id="formSenhaEscola" class="form-modal">

                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="configSenhaAtual">Senha atual</label>
                        <input type="password" id="configSenhaAtual">
                    </div>
                </div>

                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="configNovaSenha">Nova senha</label>
                        <input type="password" id="configNovaSenha">
                    </div>

                    <div class="form-grupo">
                        <label for="configConfirmarSenha">Confirmar nova senha</label>
                        <input type="password" id="configConfirmarSenha">
                    </div>
                </div>

                <button type="button" class="btn-modal btn-salvar" id="btnSalvarSenhaEscola">
                    Alterar senha
                </button>
            </form>
        </section>

        <div class="mensagem-config" id="mensagemConfiguracoes"></div>

    </div>
</div>