/* ============================================================
   configuracoesEscola.js
   Controla o modal de configurações da escola.
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    const btnAbrir = document.getElementById('btnAbrirConfiguracoesEscola');
    const btnFechar = document.getElementById('btnFecharConfiguracoesEscola');
    const overlay = document.getElementById('modalConfiguracoesOverlay');
    const modal = document.getElementById('modalConfiguracoesEscola');

    const abas = document.querySelectorAll('.aba-config');
    const conteudos = document.querySelectorAll('.conteudo-config');
    const mensagem = document.getElementById('mensagemConfiguracoes');

    const configNomeEscola = document.getElementById('configNomeEscola');
    const configGestor = document.getElementById('configGestor');
    const configEmail = document.getElementById('configEmail');
    const configCnpj = document.getElementById('configCnpj');

    const listaIdiomasConfig = document.getElementById('listaIdiomasConfig');
    const configIdiomaNivel = document.getElementById('configIdiomaNivel');
    const configNomeNivel = document.getElementById('configNomeNivel');
    const listaNiveisConfig = document.getElementById('listaNiveisConfig');

    const configSenhaAtual = document.getElementById('configSenhaAtual');
    const configNovaSenha = document.getElementById('configNovaSenha');
    const configConfirmarSenha = document.getElementById('configConfirmarSenha');

    let dadosConfiguracao = {
        escola: null,
        idiomas: [],
        niveis: []
    };

    if (!btnAbrir || !overlay || !modal) {
        return;
    }

    function abrirModal() {
        overlay.classList.add('ativo');
        modal.classList.add('ativo');
        carregarConfiguracoes();
    }

    function fecharModal() {
        overlay.classList.remove('ativo');
        modal.classList.remove('ativo');
        limparMensagem();
    }

    function limparMensagem() {
        if (!mensagem) return;

        mensagem.classList.remove('ativo', 'erro', 'sucesso');
        mensagem.textContent = '';
    }

    function mostrarMensagem(texto, tipo = 'sucesso') {
        if (!mensagem) return;

        mensagem.textContent = texto;
        mensagem.classList.remove('erro', 'sucesso');
        mensagem.classList.add('ativo', tipo);
    }

    async function chamarApi(url, dados = null) {
        const opcoes = dados
            ? {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dados)
            }
            : {};

        const resposta = await fetch(url, opcoes);
        return await resposta.json();
    }

    async function carregarConfiguracoes() {
        try {
            const resposta = await chamarApi('../actions/buscarConfiguracoesEscola.act.php');

            if (!resposta.sucesso) {
                mostrarMensagem('Erro ao carregar configurações.', 'erro');
                return;
            }

            dadosConfiguracao = resposta;

            preencherDadosEscola();
            renderizarIdiomas();
            renderizarSelectIdiomasNivel();
            renderizarNiveis();

        } catch (erro) {
            mostrarMensagem('Erro de conexão ao carregar configurações.', 'erro');
        }
    }

    function preencherDadosEscola() {
        const escola = dadosConfiguracao.escola;

        if (!escola) return;

        configNomeEscola.value = escola.nome_escola ?? '';
        configGestor.value = escola.gestor ?? '';
        configEmail.value = escola.email ?? '';
        configCnpj.value = escola.cnpj ?? '';
    }

    function renderizarIdiomas() {
        listaIdiomasConfig.innerHTML = '';

        dadosConfiguracao.idiomas.forEach(idioma => {
            const label = document.createElement('label');

            label.innerHTML = `
                <input 
                    type="checkbox" 
                    name="idiomas_config[]" 
                    value="${idioma.id_idioma}"
                    ${idioma.ativo ? 'checked' : ''}
                >
                ${idioma.nome_idioma}
            `;

            listaIdiomasConfig.appendChild(label);
        });
    }

    function renderizarSelectIdiomasNivel() {
        configIdiomaNivel.innerHTML = '<option value="">Selecione um idioma</option>';

        dadosConfiguracao.idiomas
            .filter(idioma => idioma.ativo)
            .forEach(idioma => {
                const option = document.createElement('option');

                option.value = idioma.id_idioma;
                option.textContent = idioma.nome_idioma;

                configIdiomaNivel.appendChild(option);
            });
    }

    function obterNomeIdioma(idIdioma) {
        const idioma = dadosConfiguracao.idiomas.find(i => Number(i.id_idioma) === Number(idIdioma));
        return idioma ? idioma.nome_idioma : 'Idioma';
    }

    function renderizarNiveis() {
        const idIdiomaSelecionado = configIdiomaNivel.value;

        if (!idIdiomaSelecionado) {
            listaNiveisConfig.innerHTML = '<p class="texto-config">Selecione um idioma para ver os níveis.</p>';
            return;
        }

        const niveis = dadosConfiguracao.niveis.filter(nivel => {
            return Number(nivel.id_idioma) === Number(idIdiomaSelecionado);
        });

        if (niveis.length === 0) {
            listaNiveisConfig.innerHTML = '<p class="texto-config">Nenhum nível cadastrado para este idioma.</p>';
            return;
        }

        listaNiveisConfig.innerHTML = `
            <h4>${obterNomeIdioma(idIdiomaSelecionado)}</h4>
            <ul>
                ${niveis.map(nivel => `<li>${nivel.nome_nivel}</li>`).join('')}
            </ul>
        `;
    }

    btnAbrir.addEventListener('click', function (event) {
        event.preventDefault();
        abrirModal();
    });

    btnFechar?.addEventListener('click', fecharModal);
    overlay.addEventListener('click', fecharModal);

    abas.forEach(aba => {
        aba.addEventListener('click', function () {
            const idAba = aba.dataset.aba;

            abas.forEach(item => item.classList.remove('ativa'));
            conteudos.forEach(item => item.classList.remove('ativo'));

            aba.classList.add('ativa');

            const conteudo = document.getElementById(idAba);

            if (conteudo) {
                conteudo.classList.add('ativo');
            }

            limparMensagem();
        });
    });

    configIdiomaNivel?.addEventListener('change', renderizarNiveis);

    document.getElementById('btnSalvarDadosEscola')?.addEventListener('click', async function () {
        try {
            const resposta = await chamarApi('../actions/salvarDadosConfiguracoesEscola.act.php', {
                nome_escola: configNomeEscola.value,
                gestor: configGestor.value,
                email: configEmail.value
            });

            if (resposta.sucesso) {
                mostrarMensagem('Dados salvos com sucesso.', 'sucesso');
                await carregarConfiguracoes();
            } else {
                mostrarMensagem(resposta.mensagem, 'erro');
            }

        } catch (erro) {
            mostrarMensagem('Erro ao salvar dados.', 'erro');
        }
    });

    document.getElementById('btnSalvarIdiomasEscola')?.addEventListener('click', async function () {
        const idiomasSelecionados = Array
            .from(document.querySelectorAll('input[name="idiomas_config[]"]:checked'))
            .map(input => input.value);

        try {
            const resposta = await chamarApi('../actions/salvarIdiomasConfiguracoesEscola.act.php', {
                idiomas: idiomasSelecionados
            });

            if (resposta.sucesso) {
                mostrarMensagem('Idiomas salvos com sucesso.', 'sucesso');
                await carregarConfiguracoes();
            } else {
                mostrarMensagem(resposta.mensagem, 'erro');
            }

        } catch (erro) {
            mostrarMensagem('Erro ao salvar idiomas.', 'erro');
        }
    });

    document.getElementById('btnAdicionarNivelEscola')?.addEventListener('click', async function () {
        try {
            const resposta = await chamarApi('../actions/adicionarNivelConfiguracoesEscola.act.php', {
                id_idioma: configIdiomaNivel.value,
                nome_nivel: configNomeNivel.value
            });

            if (resposta.sucesso) {
                configNomeNivel.value = '';
                mostrarMensagem('Nível adicionado com sucesso.', 'sucesso');
                await carregarConfiguracoes();

                renderizarNiveis();
            } else {
                mostrarMensagem(resposta.mensagem, 'erro');
            }

        } catch (erro) {
            mostrarMensagem('Erro ao adicionar nível.', 'erro');
        }
    });

    document.getElementById('btnSalvarSenhaEscola')?.addEventListener('click', async function () {
        try {
            const resposta = await chamarApi('../actions/salvarSenhaConfiguracoesEscola.act.php', {
                senha_atual: configSenhaAtual.value,
                nova_senha: configNovaSenha.value,
                confirmar_senha: configConfirmarSenha.value
            });

            if (resposta.sucesso) {
                configSenhaAtual.value = '';
                configNovaSenha.value = '';
                configConfirmarSenha.value = '';

                mostrarMensagem('Senha alterada com sucesso.', 'sucesso');
            } else {
                mostrarMensagem(resposta.mensagem, 'erro');
            }

        } catch (erro) {
            mostrarMensagem('Erro ao alterar senha.', 'erro');
        }
    });
});