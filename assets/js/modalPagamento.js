/* ============================================================
   modalPagamento.js
   Controla o modal de novo pagamento.
   Usado em financeiro.php e pageAluno.php
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    //---------------- Referências principais ----------------//
    const overlay = document.getElementById('modalPagamentoOverlay');
    const modal = document.getElementById('modalPagamento');
    const fechar = document.getElementById('modalPagamentoFechar');
    const form = document.getElementById('formPagamento');

    //---------------- Referências da busca de aluno ----------------//
    const campoBuscaAluno = document.getElementById('campoBuscaAlunoPagamento');
    const campoIdAluno = document.getElementById('campoIdAlunoPagamento');
    const sugestoesPagamento = document.getElementById('sugestoesPagamento');
    const sugestoes = document.querySelectorAll('.sugestao-pagamento');


    //---------------- Abrir modal ----------------//
    function abrirModalPagamento() {
        if (!overlay || !modal) {
            alert('Modal de pagamento não encontrado no HTML.');
            return;
        }

        if (form) {
            form.reset();
        }

        const campoMesReferencia = document.getElementById('campoMesReferencia');

        if (campoMesReferencia) {
            const hoje = new Date();
            const ano = hoje.getFullYear();
            const mes = String(hoje.getMonth() + 1).padStart(2, '0');

            campoMesReferencia.value = `${ano}-${mes}`;
        }

        if (campoIdAluno) {
            campoIdAluno.value = '';
        }

        if (sugestoesPagamento) {
            sugestoesPagamento.style.display = 'none';
        }

        overlay.classList.add('ativo');
        modal.classList.add('ativo');
    }


    //---------------- Fechar modal ----------------//
    function fecharModalPagamento() {
        if (overlay) {
            overlay.classList.remove('ativo');
        }

        if (modal) {
            modal.classList.remove('ativo');
        }

        if (sugestoesPagamento) {
            sugestoesPagamento.style.display = 'none';
        }
    }


    //---------------- Clique no botão novo pagamento ----------------//
    document.addEventListener('click', function (event) {
        const btnNovoPagamento = event.target.closest('.btn-novo-pagamento');

        if (!btnNovoPagamento) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        abrirModalPagamento();
    });


    //---------------- Fechar no botão X ----------------//
    if (fechar) {
        fechar.addEventListener('click', fecharModalPagamento);
    }


    //---------------- Fechar clicando no overlay ----------------//
    if (overlay) {
        overlay.addEventListener('click', fecharModalPagamento);
    }


    //---------------- Busca de aluno ----------------//
    if (campoBuscaAluno && sugestoesPagamento) {
        campoBuscaAluno.addEventListener('input', function () {
            const termo = campoBuscaAluno.value.trim().toLowerCase();

            if (campoIdAluno) {
                campoIdAluno.value = '';
            }

            if (!termo) {
                sugestoesPagamento.style.display = 'none';

                sugestoes.forEach(sugestao => {
                    sugestao.style.display = 'block';
                });

                return;
            }

            let encontrou = false;

            sugestoes.forEach(sugestao => {
                const nome = sugestao.dataset.nome.toLowerCase();

                if (nome.includes(termo)) {
                    sugestao.style.display = 'block';
                    encontrou = true;
                } else {
                    sugestao.style.display = 'none';
                }
            });

            sugestoesPagamento.style.display = encontrou ? 'block' : 'none';
        });
    }


    //---------------- Selecionando aluno da sugestão ----------------//
    sugestoes.forEach(sugestao => {
        sugestao.addEventListener('click', function () {
            if (campoBuscaAluno) {
                campoBuscaAluno.value = sugestao.dataset.nome;
            }

            if (campoIdAluno) {
                campoIdAluno.value = sugestao.dataset.id;
            }

            if (sugestoesPagamento) {
                sugestoesPagamento.style.display = 'none';
            }
        });
    });


    //---------------- Fechar sugestões ao clicar fora ----------------//
    document.addEventListener('click', function (event) {
        if (!sugestoesPagamento || !campoBuscaAluno) {
            return;
        }

        const clicouNaBusca = event.target.closest('.campo-busca-pagamento');

        if (!clicouNaBusca) {
            sugestoesPagamento.style.display = 'none';
        }
    });


    //---------------- Validação simples antes de enviar ----------------//
    if (form) {
        form.addEventListener('submit', function (event) {
            const inputAluno = form.querySelector('input[name="id_aluno"]');

            if (inputAluno && !inputAluno.value) {
                event.preventDefault();

                if (campoBuscaAluno) {
                    campoBuscaAluno.focus();
                }
            }
        });
    }

});