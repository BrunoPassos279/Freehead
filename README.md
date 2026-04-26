
 
<div align="center">
  <img src="https://github.com/user-attachments/assets/41727f8d-5bfb-4384-8904-ee698f44c573" width="247" alt="Logo branca detail">
  
  # Sistema de Gestão Escolar
  
</div>

<img width="3000" height="100" alt="212284100-561aa473-3905-4a80-b561-0d28506553ee" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />


<img width="1910" height="817" alt="image" src="https://github.com/user-attachments/assets/619f13ad-3b52-4ada-9d82-f50116c20281" />




## Sobre o Projeto
<img width="3000" height="100" alt="212284100-561aa473-3905-4a80-b561-0d28506553ee" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />


O **Freehead** é um sistema web de gestão escolar desenvolvido em PHP, com o objetivo de auxiliar gestores no controle e organização das atividades administrativas de uma instituição de ensino.

A plataforma foi projetada para centralizar informações essenciais, permitindo o gerenciamento de alunos, turmas e pagamentos de forma simples, eficiente e escalável.

---

## Objetivo
<img width="3000" height="100" alt="212284100-561aa473-3905-4a80-b561-0d28506553ee" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />


O principal objetivo do Freehead é oferecer uma solução prática para:

* Cadastro e gerenciamento de alunos
* Organização de turmas
* Controle financeiro básico (mensalidades e pagamentos)
* Centralização das informações escolares em um único sistema

---

##  Funcionalidades
<img width="3000" height="100" alt="212284100-561aa473-3905-4a80-b561-0d28506553ee" src="https://github.com/user-attachments/assets/ffa16b7c-9c8a-4fdc-b997-32bec19aec37" />


### 👤 Gestão de Usuários

* Cadastro e autenticação de gestores
* Controle de acesso ao sistema

### 🎓 Gestão de Alunos

* Cadastro de alunos
* Edição de informações
* Controle de status (ativo/inativo)

### 🏫 Gestão de Turmas

* Criação de turmas
* Associação de alunos às turmas
* Visualização de participantes

### 💰 Controle Financeiro

* Registro de mensalidades
* Controle de pagamentos
* Histórico financeiro por aluno

---

## 🧱 Estrutura do Sistema

O projeto segue uma organização modular para facilitar manutenção e escalabilidade:

```
/config        → Configurações gerais (ex: conexão com banco)
/models        → Regras de negócio e acesso a dados
/controllers   → Controle das requisições
/views         → Interface do usuário
/functions     → Funções auxiliares
```

---

## 🗄️ Banco de Dados

O sistema é estruturado com base nas seguintes entidades principais:

* **usuarios** → responsáveis pelo acesso ao sistema
* **alunos** → dados dos estudantes
* **turmas** → organização das classes
* **turma_alunos** → relação entre alunos e turmas
* **pagamentos** → controle financeiro

---

## 🔐 Segurança

Boas práticas básicas de segurança são aplicadas:

* Criptografia de senhas com `password_hash()`
* Uso de **Prepared Statements (PDO)** para evitar SQL Injection
* Validação de dados de entrada

---

## 🚀 Escopo Inicial (MVP)

A primeira versão do sistema contempla:

1. Autenticação de usuário
2. Cadastro de alunos
3. Criação de turmas
4. Vinculação de alunos às turmas

Funcionalidades adicionais, como relatórios e dashboards, poderão ser implementadas em versões futuras.

---

## Linguagens/Tecnologias Utilizadas
<table border="0">
  <tr>
    <td width="150" align="center" valign="middle">
     <img width="800" height="800" alt="219923809-b86dc415-a0c2-4a38-bc88-ad6cf06395a8" src="https://github.com/user-attachments/assets/03721d2d-9d7d-4ad2-b07d-af362ca23b80" />
    </td>
    <td valign="middle">
      <strong>Linguagens & Tecnologias:</strong><br>
      <code>PHP</code> <code>MySQL</code> <code>HTML5</code> <code>CSS3</code> <code>JavaScript</code>
    </td>
  </tr>
</table>

---

## 📌 Considerações Finais

O Freehead é um projeto em evolução, com foco em organização, clareza estrutural e possibilidade de crescimento. A proposta é construir uma base sólida que permita futuras melhorias sem comprometer a manutenção do sistema.

---
