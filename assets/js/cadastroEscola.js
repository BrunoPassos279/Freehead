/* ============================================================
   cadastroEscola.js
   Validação em tempo real do cadastro de escola.
   Não altera o visual base dos inputs.
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.form');

    if (!form) {
        return;
    }

    const campos = {
        nomeEscola: document.getElementById('nomeEscola'),
        nomeGestor: document.getElementById('nomeGestor'),
        email: document.getElementById('email'),
        senha: document.getElementById('senha'),
        confirmarSenha: document.getElementById('confirmarSenha'),
        cnpj: document.getElementById('cnpj')
    };

    if (campos.cnpj) {
        campos.cnpj.addEventListener('input', function () {
            campos.cnpj.value = formatarCNPJ(campos.cnpj.value);
        });
    }

    const checkboxesIdiomas = document.querySelectorAll('input[name="idiomas[]"]');
    const areaIdiomas = document.querySelector('.checkIdioma');

    let tentouEnviar = false;

    function limparErro(campo) {
        if (!campo) return;

        campo.classList.remove('campo-erro');

        const erro = document.querySelector(`[data-erro="${campo.id}"]`);

        if (erro) {
            erro.remove();
        }
    }

    function mostrarErro(campo, mensagem) {
        if (!campo) return;

        limparErro(campo);

        campo.classList.add('campo-erro');

        const span = document.createElement('span');
        span.classList.add('mensagem-campo-erro');
        span.dataset.erro = campo.id;
        span.textContent = mensagem;

        campo.insertAdjacentElement('afterend', span);
    }

    function limparErroIdiomas() {
        const erro = document.querySelector('[data-erro="idiomas"]');

        if (erro) {
            erro.remove();
        }

        if (areaIdiomas) {
            areaIdiomas.classList.remove('campo-erro-box');
        }
    }

    function mostrarErroIdiomas(mensagem) {
        limparErroIdiomas();

        if (areaIdiomas) {
            areaIdiomas.classList.add('campo-erro-box');

            const span = document.createElement('span');
            span.classList.add('mensagem-campo-erro');
            span.dataset.erro = 'idiomas';
            span.textContent = mensagem;

            areaIdiomas.insertAdjacentElement('afterend', span);
        }
    }

    function emailValido(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function cnpjValido(cnpj) {
        const somenteNumeros = cnpj.replace(/\D/g, '');
        return somenteNumeros.length === 14;
    }

    function algumIdiomaSelecionado() {
        return Array.from(checkboxesIdiomas).some(cb => cb.checked);
    }

    function validarFormulario(mostrarMensagens = false) {
        let valido = true;

        Object.values(campos).forEach(limparErro);
        limparErroIdiomas();

        if (!campos.nomeEscola.value.trim()) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.nomeEscola, 'Informe o nome da escola.');
        }

        if (!campos.nomeGestor.value.trim()) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.nomeGestor, 'Informe o nome do gestor.');
        }

        if (!campos.email.value.trim()) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.email, 'Informe o e-mail.');
        } else if (!emailValido(campos.email.value.trim())) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.email, 'Informe um e-mail válido.');
        }

        if (!campos.senha.value.trim()) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.senha, 'Informe a senha.');
        } else if (campos.senha.value.length < 6) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.senha, 'A senha precisa ter pelo menos 6 caracteres.');
        }

        if (!campos.confirmarSenha.value.trim()) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.confirmarSenha, 'Confirme a senha.');
        } else if (campos.senha.value !== campos.confirmarSenha.value) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.confirmarSenha, 'As senhas não conferem.');
        }

        if (!campos.cnpj.value.trim()) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.cnpj, 'Informe o CNPJ.');
        } else if (!cnpjValido(campos.cnpj.value)) {
            valido = false;
            if (mostrarMensagens) mostrarErro(campos.cnpj, 'O CNPJ precisa ter 14 números.');
        }

        if (!algumIdiomaSelecionado()) {
            valido = false;
            if (mostrarMensagens) mostrarErroIdiomas('Selecione pelo menos um idioma.');
        }

        return valido;
    }

    Object.values(campos).forEach(campo => {
        if (!campo) return;

        campo.addEventListener('input', function () {
            if (tentouEnviar) {
                validarFormulario(true);
            }
        });
    });

    checkboxesIdiomas.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (tentouEnviar) {
                validarFormulario(true);
            }
        });
    });

    form.addEventListener('submit', function (event) {
        tentouEnviar = true;

        const valido = validarFormulario(true);

        if (!valido) {
            event.preventDefault();

            const primeiroErro = document.querySelector('.campo-erro');

            if (primeiroErro) {
                primeiroErro.focus();
            }
        }
    });
});

function formatarCNPJ(valor) {
    valor = valor.replace(/\D/g, '');
    valor = valor.slice(0, 14);

    if (valor.length <= 2) {
        return valor;
    }

    if (valor.length <= 5) {
        return valor.replace(/^(\d{2})(\d+)/, '$1.$2');
    }

    if (valor.length <= 8) {
        return valor.replace(/^(\d{2})(\d{3})(\d+)/, '$1.$2.$3');
    }

    if (valor.length <= 12) {
        return valor.replace(/^(\d{2})(\d{3})(\d{3})(\d+)/, '$1.$2.$3/$4');
    }

    return valor.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
}