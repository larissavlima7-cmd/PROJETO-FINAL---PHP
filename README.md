# Aromas da Lari - Sistema interno para gestão de perfumaria

## 1. Introdução
### 1.1 Objetivo
O sistema Aromas da Lari tem o intuito de facilitar o controle de vendas e gerenciamento de uma perfumaria, por meio de um design clean e moderno, além de uma interface intuitiva e facilitada. 

### 1.2 Escopo do Sistema
O sistema escopo deste documento contempla o gerenciamento completo (Cadastro, Listagem, Edição e Exclusão - CRUD) de quatro pilares principais: **Usuários, Clientes, Produtos e Pedidos**. E trata-se de uma ferramenta de uso estritamente interno.

---

## 2. Descrição Geral
### 2.1 Funções do Sistema

O sistema deve executar as seguintes macro-funções:

* Gerenciamento de Clientes (dados de contato e endereço).
* Gerenciamento de Usuários (controle de acessos e identificação).
* Gerenciamento de Produtos (controle de estoque e preços de perfumes/cosméticos).
* Gerenciamento de Pedidos (fluxo de vendas).

### 2.2 Características dos Usuários
* **Usuários:** acesso total ao sistema,gestão de usuários, realização de vendas (pedidos), cadastro de clientes e consulta de produtos.

---
## 3. Requisitos Específicos (Padrão ISO 29148)

### 3.1 Requisitos Funcionais (RF)

#### Gestão de Usuários e Acessos
* **[RF001] Autenticação de Usuários:** O sistema **deve** permitir o login de usuários mediante nome e senha.

#### Módulo de Clientes
* **[RF002] CRUD de Clientes:** O sistema **deve** permitir cadastrar, listar, editar e excluir registros de clientes (Nome, CEP, Telefone).

#### Módulo de Usuários

* **[RF003] CRUD de Usuários:** O sistema **deve** permitir cadastrar, listar, editar e excluir registros de usuários (nome, senha).

#### Módulo de Produtos (Perfumaria)

* **[RF004] CRUD de Produtos:** O sistema **deve** permitir o cadastro de produtos contendo: Nome, Marca, Preço de Venda e Quantidade em Estoque.

#### Módulo de Pedidos (Vendas)

* **[RF005] Registro de Vendas:** O sistema **deve** permitir a criação de um pedido vinculando um cliente, um usuário e a um ou mais produtos.
* **[RF006] Baixa Automática de Estoque:** O sistema **deve** subtrair a quantidade de produtos vendidos do estoque assim que o pedido for finalizado.
* **[RF007] Reposição Automática de Estoque:** O sistema deve incrementar automaticamente o saldo de produtos em estoque nas seguintes condições:

        a) Cancelamento de Pedido: Quando o status de um pedido for alterado para "Cancelado", a quantidade total de itens desse pedido deve retornar ao estoque imediatamente.

        b) Redução de Quantidade em Edição: Quando um pedido for editado e a quantidade de um produto for diminuída, a diferença exata entre a quantidade antiga e a nova deve ser devolvida ao estoque assim que a alteração for salva.

---
### 3.2 Requisitos Não-Funcionais (RNF)

#### Usabilidade e Interface (Alinhado ao seu design clean)
* **[RNF001] Design Clean e Intuitivo:** A interface do sistema **deve** seguir as diretrizes de Design Minimalista, utilizando uma paleta de cores e uma estilização restrita.
* **[RNF002] Curva de Aprendizado:** O sistema **deve** ser projetado de forma que um novo usuário consiga realizar uma venda em menos de 3 minutos de treinamento prévio.

---

## 3.3 Requisitos de Dados (Persistência e Estrutura)
O sistema deve utilizar um Banco de Dados Relacional para garantir a consistência das operações de estoque e pedidos. Abaixo estão descritas as entidades lógicas baseadas nos requisitos operacionais.

### 3.3.1 Dicionário de Dados (Estrutura das Tabelas)

**Tabela: Usuários**
| Nome do Campo | Tipo de Dado | Chave | Descrição |
| ------------- | ------------ | ----- | --------- |
| id            | SERIAL       | PRIMARY KEY | Chave primária auto-incrementável. |
| nome          | VARCHAR(100) | NOT NULL | Nome do usuário. |
| senha         | VARCHAR(255) | NOT NULL | Senha. |


---
**Tabela: Clientes**
| Nome do Campo | Tipo de Dado | Chave | Descrição |
| ------------- | ------------ | ----- | --------- |
| id            | SERIAL       | PRIMARY KEY | chave primária auto-incrementável. |
| nome          | VARCHAR(100) | NOT NULL | Nome do cliente. |
| cep    | INTEGER | NOT NULL | CEP da rua para os dados do endereço. |
| numerocasa | INTEGER |  NOT NULL | Número da casa
| telefone | VARCHAR(11) | NOT NULL | Telefone de contato do cliente. |


---
**Tabela: Produtos**
| Nome do Campo | Tipo de Dado | Chave | Descrição |
| ------------- | ------------ | ----- | --------- |
| id            | SERIAL       | PRIMARY KEY | chave primária auto-incrementável. |
| nome          | VARCHAR(100) | NOT NULL | Nome do produto. |
| marca | VARCHAR| NOT NULL | marca do produto. |
| preco | NUMERIC | NOT NULL | valor do produto
| quant_estoque | INTEGER | NOT NULL | quantidade de produto no estoque
| imagem | VARCHAR(255) | NOT NULL | link para imagem do produto. |


---
**Tabela: Pedidos**
| Nome do Campo | Tipo de Dado | Chave | Descrição |
| ------------- | ------------ | ----- | --------- |
| id            | SERIAL       | PRIMARY KEY | chave primária auto-incrementável. |
| idclientes       | INTEGER | FOREIGN KEY | chave estrangeira da tabela clientes. |
| idusuarios       | INTEGER | FOREIGN KEY | chave estrangeira da tabela usuários. |
| idprodutos       | INTEGER | FOREIGN KEY | chave estrangeira da tabela produtos. |
| quantidade| INTEGER | NOT NULL | quantidade do produto a ser pedido.
| idstatus  | INTEGER | FOREIGN KEY | chave estrangeira da tabela status_pedido. |


---
**Tabela: Status_pedido**
| Nome do Campo | Tipo de Dado | Chave | Descrição |
| ------------- | ------------ | ----- | --------- |
| id            | SERIAL       | PRIMARY KEY | chave primária auto-incrementável. |
| descricao          | VARCHAR(100) | NOT NULL | Tipo de Status (Entregue, Cancelado, Rota de Entrega, ...) |
