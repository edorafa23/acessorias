# Acessorias

## Descrição do Projeto
Projeto desenvolvido para um processo seletivo, onde foi implementado um mini framework seguindo o padrão MVC. Com este projeto, é possível realizar login na plataforma e executar operações de CRUD para gerenciar clientes.

O escopo do projeto para o processo seletivo contempla as seguintes recomendações:

- **Estrutura da Tabela de Clientes**: a tabela de clientes possuir os seguintes campos: `clients`, `id`, `name`, `phone`, `email`, `created_at`, `updated_at`.
- **Dados Fictícios**: alimentar tabela com 120 registros fictícios.
- **Tecnologias Utilizadas**: desenvolver a solução utilizando PHP puro, HTML e Bootstrap.
- **Paginação**: na exibição de clientes, mostrar 10 clientes por página, com links de paginação.
- **Boas Práticas**: parte visual fica a critério do candidato, seguir boas práticas e implementar o que considerar relevante.

Todos os requisitos foram atendidos, e algumas implementações extras foram adicionadas:
- A tabela `persons` (clientes) inclui campos adicionais: `gender` (gênero), `birthdate` (data de nascimento), `interest` (interesses) e `deleted_at`.
- Uma nova tabela, `agents`, foi criada para gerenciar usuários administradores do sistema.

**Limitações e Melhorias Futuras**: devido ao prazo de entrega do projeto, alguns aspectos não foram implementados (por não serem requisitos obrigatórios do escopo inicial), como:
- Uso de máscara em alguns campos do formulário.
- Inserção de usuários via arquivo.
- Recursos de segurança.
- Cadastro de administradores (agents).

## Como Usar
Para utilizar o projeto, basta baixá-lo e executá-lo em um ambiente compatível como XAMPP, Laragon, entre outros.

### Passo a passo:
1. Recomendo baixar o [Laragon](https://laragon.org/download/).
2. Baixe o projeto e coloque-o na pasta `www` do Laragon.
   - No Windows, acesse o diretório `C:\laragon\www`.
3. Acesse o painel do Laragon, clique em **Web**.
4. Encontre a pasta do projeto e abra a pasta **public** para iniciar a aplicação.

## Tecnologias Utilizadas
- HTML
- CSS
- JavaScript
- [Flatpickr](https://flatpickr.js.org/) (para calendário)
- [DataTable](https://datatables.net/)

## Licença
Este projeto não possui uma licença.

## Contribuições
Este projeto não está aberto a contribuições externas.

## Contato
Rafael Luan Sevilha  
E-mail: [edorafa@hotmail.com](mailto:edorafa@hotmail.com)
