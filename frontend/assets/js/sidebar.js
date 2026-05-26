/** Marca o item ativo da sidebar com base na URL atual.*/
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.sidebar-item');
    const paginaAtual = window.location.pathname.split('/').pop();

    links.forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && href === paginaAtual) {
            link.style.backgroundColor = '#1e3a5f';
            link.style.borderLeft = '3px solid var(--laranja)';
        }
    });
});

//---------------- Busca inteligente de alunos ----------------//

const buscarAlunoInput = document.getElementById('buscarAlunoInput');
const sugestoesBusca = document.getElementById('sugestoesBusca');

if (buscarAlunoInput && sugestoesBusca) {
    buscarAlunoInput.addEventListener('input', function () {
        const termoBusca = buscarAlunoInput.value.trim().toLowerCase();
        const idEscolaLogada = Number(buscarAlunoInput.dataset.escola);

        sugestoesBusca.innerHTML = '';

        //---------------- Esconde sugestões se o campo estiver vazio ----------------//
        if (termoBusca === '') {
            sugestoesBusca.style.display = 'none';
            return;
        }

        //---------------- Lendo banco temporário JSON ----------------//
        fetch('dados.json')
            .then(response => response.json())
            .then(dados => {
                const alunosEncontrados = dados.alunos.filter(aluno => {
                    return aluno.id_escola === idEscolaLogada &&
                           aluno.nome.toLowerCase().includes(termoBusca);
                });

                //---------------- Caso não encontre nenhum aluno ----------------//
                if (alunosEncontrados.length === 0) {
                    sugestoesBusca.innerHTML = `
                        <div class="sugestao-vazia">
                            Nenhum aluno encontrado
                        </div>
                    `;

                    sugestoesBusca.style.display = 'block';
                    return;
                }

                //---------------- Criando sugestões encontradas ----------------//
                alunosEncontrados.forEach(aluno => {
                    const item = document.createElement('div');

                    item.classList.add('sugestao-aluno');
                    item.textContent = aluno.nome;

                    item.addEventListener('click', function () {
                        window.location.href = `aluno.php?id_aluno=${aluno.id_aluno}`;
                    });

                    sugestoesBusca.appendChild(item);
                });

                sugestoesBusca.style.display = 'block';
            });
    });

    //---------------- Fechando sugestões ao clicar fora ----------------//
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.sidebar-search')) {
            sugestoesBusca.style.display = 'none';
        }
    });
}