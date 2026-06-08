<div align="center">
  <img src="https://github.com/user-attachments/assets/41727f8d-5bfb-4384-8904-ee698f44c573" width="247" alt="Freehead Logo">

## Sistema de Gestão para Escolas de Idiomas

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

<img width="1910" height="817" alt="Preview do sistema" src="https://github.com/user-attachments/assets/619f13ad-3b52-4ada-9d82-f50116c20281" />

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

<div align="center">

## Sobre o Projeto

</div>

O **Freehead** é um sistema web de gestão escolar desenvolvido em PHP, voltado para **escolas de idiomas**. O objetivo é centralizar todas as informações administrativas da escola em um único lugar: alunos, professores, turmas, matrículas e financeiro.

O sistema foi construído com uma arquitetura preparada para migração futura de banco de dados — hoje utiliza arquivos JSON como banco temporário, com a lógica encapsulada em **repositories** para que a troca por MySQL/PDO não exija reescrita das páginas.

---

<div align="center">

## Funcionalidades

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

### Autenticação
- Cadastro de escola com seleção de idiomas oferecidos
- Login e logout de gestores
- Controle de sessão com validação em todas as páginas

### Gestão de Alunos
- Cadastro completo (dados pessoais, responsáveis, contatos)
- Visualização detalhada por aluno (`pageAluno.php`)
- Edição de informações via modal
- Exclusão com confirmação
- Transferência de turma
- Busca inteligente com sugestões em tempo real na sidebar

### Gestão de Professores
- Cadastro de professores com seleção de idiomas que leciona
- Visualização por cards com contagem de turmas ativas
- Edição e exclusão via modal
- Associação a turmas

### Gestão de Turmas
- Criação de turmas com idioma, nível, professor, horário e capacidade
- Visualização por cards com status (ativa, encerrada, cancelada)
- Modal de detalhes com lista de alunos matriculados
- Inclusão de alunos diretamente pela turma

### Controle Financeiro
- Dashboard financeiro com receita do mês, a receber e em atraso
- Registro de pagamentos associados a alunos/matrículas
- Histórico de pagamentos com filtro por escola
- Visualização de pendências financeiras

### Configurações da Escola
- Edição de dados da escola (nome, gestor, contato)
- Gerenciamento de idiomas oferecidos
- Troca de senha

---

<div align="center">

## Estrutura do Projeto

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

```
Freehead/
│
├── actions/                    → Arquivos PHP que recebem requisições (salvar, editar, excluir...)
│   ├── salvarAluno.act.php
│   ├── editarAluno.act.php
│   ├── excluirAluno.act.php
│   ├── salvarProfessor.act.php
│   ├── salvarTurma.act.php
│   ├── registrarPagamento.act.php
│   ├── login.act.php
│   ├── logout.act.php
│   └── ...
│
├── assets/
│   ├── css/
│   │   ├── root.css            → Variáveis de cor, tipografia e tokens do design system
│   │   ├── global.css          → Reset, estilos base, sidebar e componentes reutilizáveis
│   │   ├── modal.css           → Estilos unificados de todos os modais
│   │   └── pages/              → CSS específico de cada página
│   ├── js/
│   │   ├── sidebar.js          → Marca item ativo e busca inteligente de alunos
│   │   ├── modalAluno.js       → Lógica do modal de alunos
│   │   ├── modalProfessor.js   → Lógica do modal de professores
│   │   ├── modalTurma.js       → Lógica do modal de turmas
│   │   ├── modalPagamento.js   → Lógica do modal de pagamentos
│   │   └── ...
│   └── img/                    → Ícones, imagens e logos do sistema
│
├── config/
│   └── database.php            → Configuração de conexão com banco de dados
│
├── includes/                   → Componentes PHP reutilizáveis
│   ├── sidebar.inc.php
│   ├── btn.inc.php
│   ├── input.inc.php
│   ├── auth.inc.php            → Funções de sessão e autenticação
│   ├── modalAluno.inc.php
│   ├── modalProf.inc.php
│   ├── modalTurma.inc.php
│   └── modalPagamento.inc.php
│
├── pages/                      → Páginas do sistema
│   ├── index.php               → Login
│   ├── cadastroEscola.php      → Cadastro de nova escola
│   ├── dashboard.php           → Painel principal
│   ├── alunos.php              → Lista de alunos
│   ├── pageAluno.php           → Detalhes do aluno
│   ├── professores.php         → Lista de professores
│   ├── turmas.php              → Lista de turmas
│   └── financeiro.php          → Controle financeiro
│
└── repositories/               → Camada de acesso a dados (preparada para MySQL)
    ├── AlunoRepository.php
    ├── ProfessorRepository.php
    ├── TurmaRepository.php
    ├── PagamentoRepository.php
    ├── AuthRepository.php
    ├── EscolaRepository.php
    ├── DashboardRepository.php
    └── ConfiguracoesEscolaRepository.php
```

---

<div align="center">

## Banco de Dados

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

O sistema foi modelado com as seguintes tabelas:

| Tabela | Descrição |
|---|---|
| `escolas` | Dados das escolas cadastradas no sistema |
| `idiomas` | Idiomas disponíveis na plataforma |
| `idiomas_escolas` | Relação entre escolas e os idiomas que oferecem |
| `niveis` | Níveis de cada idioma por escola (básico, intermediário, avançado...) |
| `professores` | Professores vinculados a uma escola |
| `professor_idioma` | Idiomas que cada professor leciona |
| `alunos` | Dados completos dos alunos |
| `turmas` | Turmas com idioma, nível, professor e horário |
| `matriculas` | Vínculo entre aluno e turma com status |
| `pagamentos` | Registro de mensalidades e pagamentos |
| `aulas` | Aulas realizadas ou canceladas por turma |
| `presenca` | Presença dos alunos por aula |

> **Banco atual:** JSON temporário (`dados.json`), lido via repositories PHP.
> **Migração:** Altere apenas os repositories para usar `PDO` — as páginas e actions não precisam de nenhuma mudança.

---

<div align="center">

## Arquitetura e Padrões

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

**Separação de responsabilidades**
Cada camada tem uma função clara: pages exibem, actions processam, repositories acessam dados. Nenhuma página faz consulta direta ao banco.

**Componentes reutilizáveis**
Botões (`btn.inc.php`), inputs (`input.inc.php`) e a sidebar (`sidebar.inc.php`) são incluídos via PHP em todas as páginas. Mudar um componente reflete em todo o sistema.

**Sistema de modais unificado**
Todos os modais (aluno, professor, turma, pagamento) compartilham um único `modal.css` e seguem o mesmo padrão de três estados: *novo*, *visualizar* e *editar*. Os dados chegam ao modal via atributos `data-*` do PHP, sem requisições extras ao servidor.

**Design system**
Cores, tipografia e espaçamentos centralizados em `root.css` com variáveis CSS. A paleta usa azul escuro como cor principal, laranja como destaque e branco off-white para fundos.

**Preparado para MySQL**
A camada de repositories encapsula toda a lógica de acesso a dados. Hoje lê JSON; amanhã lê MySQL — sem tocar nas páginas ou actions.

---

<div align="center">

## Tecnologias Utilizadas

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

<div align="center">
  <table border="0" width="100%">
    <tr align="center">
      <td width="20%" align="center" valign="middle">
        <img width="150" height="150" alt="Logo animada" src="https://github.com/user-attachments/assets/efb1796f-67b0-47cb-b823-5878610d08f8" />
      </td>
      <td width="80%" valign="middle">
        <table border="0" width="100%">
          <tr align="center">
            <td><code>PHP 8</code></td>
            <td><code>MySQL</code></td>
            <td><code>HTML5</code></td>
            <td><code>CSS3</code></td>
            <td><code>JavaScript</code></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>

---

<div align="center">

## Segurança

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

- Senhas criptografadas com `password_hash()` e verificadas com `password_verify()`
- Prepared Statements via PDO para prevenção de SQL Injection
- Validação de sessão em todas as páginas protegidas via `validarSessao()`
- Sanitização de dados com `htmlspecialchars()` na exibição
- Isolamento por escola: cada gestor acessa apenas os dados da sua própria escola

---

<div align="center">

## Como Rodar Localmente

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

**Pré-requisitos:** PHP 8+, servidor local (XAMPP, Laragon, WAMP ou similar)

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/freehead.git

# 2. Coloque a pasta dentro do diretório público do seu servidor
#    Ex: C:/xampp/htdocs/freehead

# 3. Acesse no navegador
http://localhost/freehead/pages/index.php
```

> O sistema já vem com um `dados.json` de exemplo. Nenhuma configuração de banco é necessária para rodar localmente.

---

<div align="center">

## Próximos Passos

</div>

<img width="3000" height="100" alt="divisor" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />

- [ ] Migração do banco de dados de JSON para MySQL via PDO
- [ ] Relatórios de frequência e desempenho por turma
- [ ] Controle de presença nas aulas
- [ ] Envio de notificações de pagamento por e-mail
- [ ] Perfil de acesso por nível (gestor, secretaria, professor)
- [ ] Versão responsiva completa para mobile

---

<div align="center">

Desenvolvido com 💙 por Bruno Passos, Bruno Rodrigues, Murilo e Isaac.

</div>